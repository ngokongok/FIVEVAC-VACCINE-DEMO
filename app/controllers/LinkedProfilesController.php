<?php
// app/controllers/LinkedProfilesController.php

/**
 * Controller for managing linked vaccination records for customers.
 *
 * Customers can link their own profile or profiles of their relatives by
 * providing a valid phone number associated with a vaccination record. The
 * linking process uses a simple one‑time passcode (OTP) mechanism similar
 * to the account registration flow: after entering a phone number, the
 * system sends a code (simulated here), prompts the user to enter it, and
 * verifies the code within a one‑minute window. Upon successful
 * verification, a relationship record is stored in the `quanhelienket`
 * table with status `Đã xác thực`. Customers can view details of
 * linked profiles, see their injection history, and remove links that
 * they manage.
 */
class LinkedProfilesController extends Controller {

    /**
     * Display the linked profiles management page and handle linking steps.
     *
     * GET requests show the linking form and the list of existing links.
     * POST requests handle either the phone submission (step 1) or OTP
     * verification (step 2) depending on the submitted fields. The flow
     * adheres to the "Thêm quan hệ liên kết" use case provided by the
     * client, including error handling and attempt limits.
     */
    public function index() {
        require_role(['customer']);
        // If the user canceled the deletion of a linked profile, display a notification.
        if (isset($_GET['cancel'])) {
            flash('info', 'Quan hệ liên kết chưa được xóa.');
        }
        $db = new Database();
        $userId = current_user_id();

        // Automatically link the customer's own profile if it exists in
        // `thongtinkhachhang` and the link hasn't been created yet. We
        // consider a profile to belong to the user when their phone in
        // `taikhoan` matches a record in `thongtinkhachhang`. This block
        // executes before processing any POST requests so the list always
        // includes the personal profile.
        $this->autoLinkSelf($db, $userId);

        // Initialise a flag to determine whether to show the OTP form in this request.
        $inOtpStep = false;
        // Handle form submissions
        if (is_post()) {
            verify_csrf();
            // Determine whether this is the phone submission (step 1)
            // or the OTP verification (step 2) by checking the posted
            // parameters. The phone field exists only in step 1, while
            // step 2 contains an `otp` field.
            $phone = $_POST['phone'] ?? null;
            $otp   = $_POST['otp'] ?? null;
            // Step 1: user provided a phone number to link
            if ($phone !== null) {
                // Only enter OTP step when the phone submission is successful
                $success = $this->handlePhoneSubmission($phone, $db, $userId);
                if ($success) {
                    // Show the OTP input form immediately after sending the code.
                    $inOtpStep = true;
                }
            }
            // Step 2: user provided an OTP to verify the link
            elseif ($otp !== null) {
                $this->handleOtpVerification($otp, $db, $userId);
                // After verifying, redirect back to the page to display success/error messages.
                return redirect('linked_profiles');
            }
        } else {
            // If the user navigates or reloads the page, only clear the pending OTP
            // session when there is no ongoing linking process. If there is an
            // existing phone/OTP stored in session, persist the OTP step so that
            // users can re‑enter the code or request a resend without having to
            // submit the phone again.
            if (isset($_SESSION['link_phone']) || isset($_SESSION['link_profile_id'])) {
                // Existing session indicates we are in the middle of an OTP step
                $inOtpStep = true;
            } else {
                // No pending session; clear any remnants
                $this->clearLinkSession();
            }
        }

        // Retrieve existing verified links for display. Only records with
        // status "Đã xác thực" are shown to the customer.
        $db->query("SELECT lk.MaKH, kh.HoTen, kh.NgaySinh, kh.GioiTinh, kh.DiaChi, kh.SDT
                     FROM quanhelienket lk
                     JOIN thongtinkhachhang kh ON lk.MaKH = kh.MaKH
                     WHERE lk.MaTV = :tv AND lk.TrangThaiLienKet = 'Đã xác thực'");
        $db->bind(':tv', $userId);
        $linked = $db->resultSet() ?? [];

        // Preserve submitted phone/otp values to keep inputs populated on validation errors
        $submittedPhone = $_POST['phone'] ?? '';
        $submittedOtp   = $_POST['otp'] ?? '';

        // Render the view with the appropriate variables. The OTP step flag controls
        // whether the view displays the OTP form or the phone input form.
        $this->view('linked_profiles/index', [
            'linked'        => $linked,
            'inOtpStep'     => $inOtpStep,
            'submitted_phone' => $submittedPhone,
            'submitted_otp'   => $submittedOtp
        ]);
    }

    /**
     * Display detailed information for a linked profile and its injection
     * history. Only accessible if the profile is linked to the current
     * customer.
     *
     * @param string $id The MaKH (customer profile ID) to show
     */
    public function detail($id) {
        require_role(['customer']);
        $userId = current_user_id();
        $id = sanitize($id);
        $db = new Database();
        // Verify that the requested profile is linked to the user and is
        // confirmed (Đã xác thực). If not, redirect back with an error.
        $db->query("SELECT COUNT(*) AS cnt FROM quanhelienket WHERE MaTV = :tv AND MaKH = :kh AND TrangThaiLienKet = 'Đã xác thực'");
        $db->bind(':tv', $userId);
        $db->bind(':kh', $id);
        $row = $db->single();
        if (!$row || (int)$row['cnt'] === 0) {
            flash('error', 'Bạn không có quyền xem hồ sơ này.');
            return redirect('linked_profiles');
        }
        // Fetch profile information
        $db->query("SELECT * FROM thongtinkhachhang WHERE MaKH = :kh LIMIT 1");
        $db->bind(':kh', $id);
        $profile = $db->single();
        // Fetch injection records
        $db->query("SELECT * FROM phieutiem WHERE MaKH = :kh ORDER BY NgayTiem DESC, GioTiem DESC");
        $db->bind(':kh', $id);
        $injections = $db->resultSet() ?? [];
        $this->view('linked_profiles/detail', [
            'profile'    => $profile,
            'injections' => $injections
        ]);
    }

    /**
     * Remove a linked profile from the current customer's account. The
     * customer cannot remove their own profile and must confirm the action
     * via a POST request. This method handles the confirmation and
     * deletion logic.
     *
     * @param string $id The MaKH (customer profile ID) to unlink
     */
    public function delete($id) {
        require_role(['customer']);
        $userId = current_user_id();
        $id = sanitize($id);
        $db = new Database();
        // Ensure this profile is currently linked to the user
        $db->query("SELECT TrangThaiLienKet FROM quanhelienket WHERE MaTV = :tv AND MaKH = :kh LIMIT 1");
        $db->bind(':tv', $userId);
        $db->bind(':kh', $id);
        $link = $db->single();
        if (!$link || $link['TrangThaiLienKet'] !== 'Đã xác thực') {
            flash('error', 'Không tìm thấy hồ sơ liên kết.');
            return redirect('linked_profiles');
        }
        // Check if the profile belongs to the current user (self link). We
        // consider it a self link if the phone in thongtinkhachhang matches
        // the user’s phone in taikhoan. Self links cannot be deleted.
        $db->query("SELECT SDT FROM thongtinkhachhang WHERE MaKH = :kh LIMIT 1");
        $db->bind(':kh', $id);
        $kh = $db->single();
        $db->query("SELECT SDT FROM taikhoan WHERE MaND = :mand LIMIT 1");
        $db->bind(':mand', $userId);
        $acc = $db->single();
        if ($kh && $acc && $kh['SDT'] === $acc['SDT']) {
            flash('error', 'Bạn không thể xóa hồ sơ của chính mình.');
            return redirect('linked_profiles');
        }
        // Only proceed on POST (confirmation). On GET, show a simple
        // confirmation page.
        if (!is_post()) {
            $this->view('linked_profiles/delete', [ 'kh_id' => $id ]);
            return;
        }
        verify_csrf();
        // Remove the link record
        $db->query("DELETE FROM quanhelienket WHERE MaTV = :tv AND MaKH = :kh");
        $db->bind(':tv', $userId);
        $db->bind(':kh', $id);
        try {
            $db->execute();
            flash('info', 'Xóa hồ sơ liên kết thành công.');
        } catch (Exception $e) {
            flash('error', 'Không thể xóa liên kết: ' . $e->getMessage());
        }
        return redirect('linked_profiles');
    }

    /**
     * Handle phone submission for linking. Validates the phone format,
     * checks existence in the `thongtinkhachhang` table, and prepares an
     * OTP for verification. The OTP and associated data are stored in
     * the session under `link_*` keys.
     *
     * @param string   $phone  The phone number entered by the user
     * @param Database $db     Instance of the database connection
     * @param string   $userId Current user ID (MaND/MaTV)
     */
    private function handlePhoneSubmission(string $phone, Database $db, string $userId): bool {
        $phone = sanitize($phone);
        // Validate phone format (Vietnamese 10-digit starting with 0)
        if (!validate_phone($phone)) {
            // Ensure any stale OTP session is cleared so that the phone input is shown again
            $this->clearLinkSession();
            flash('error', 'Số điện thoại không hợp lệ. Vui lòng nhập 10 chữ số bắt đầu bằng 0.');
            return false;
        }
        // Look up the customer profile by phone
        $db->query("SELECT MaKH FROM thongtinkhachhang WHERE SDT = :s LIMIT 1");
        $db->bind(':s', $phone);
        $row = $db->single();
        if (!$row || empty($row['MaKH'])) {
            // Clear any previous OTP session to avoid showing OTP fields after error
            $this->clearLinkSession();
            flash('error', 'Không tìm thấy hồ sơ với số điện thoại này.');
            return false;
        }
        $maKh = $row['MaKH'];
        // Check if this relationship already exists and is verified
        $db->query("SELECT TrangThaiLienKet FROM quanhelienket WHERE MaTV = :tv AND MaKH = :kh LIMIT 1");
        $db->bind(':tv', $userId);
        $db->bind(':kh', $maKh);
        $existing = $db->single();
            if ($existing) {
                // Already linked; either pending or verified
                if ($existing['TrangThaiLienKet'] === 'Đã xác thực') {
                    flash('info', 'Hồ sơ này đã được liên kết với tài khoản của bạn.');
                } else {
                    flash('info', 'Hồ sơ này đang trong quá trình xác thực. Vui lòng nhập mã OTP trước đó.');
                }
                return false;
            }
        // Generate a 6‑digit OTP and store linking metadata in session
        $otp = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $_SESSION['link_phone']        = $phone;
        $_SESSION['link_profile_id']   = $maKh;
        $_SESSION['link_otp']          = $otp;
        $_SESSION['link_otp_expiry']   = time() + 60; // Valid for 1 minute
        $_SESSION['link_otp_attempts'] = 0;
        // Simulate sending OTP (in production, integrate SMS). Do not expose the code to the user.
        flash('info', 'Đã gửi mã xác thực.');
        return true;
    }

    /**
     * Handle OTP verification for linking. Validates the provided code
     * against session data, enforces expiry and attempt limits, and
     * creates a confirmed link upon success.
     *
     * @param string   $otp    The OTP entered by the user
     * @param Database $db     Database instance
     * @param string   $userId Current user ID
     */
    private function handleOtpVerification(string $otp, Database $db, string $userId): void {
        // Ensure we have a pending link request in session
        if (!isset($_SESSION['link_profile_id']) || !isset($_SESSION['link_otp'])) {
            flash('error', 'Không có yêu cầu liên kết hồ sơ đang chờ.');
            return;
        }
        // Check expiry
        if (time() > ($_SESSION['link_otp_expiry'] ?? 0)) {
            // Expired: clear data
            $this->clearLinkSession();
            flash('error', 'Mã xác thực đã hết hiệu lực. Vui lòng yêu cầu lại.');
            return;
        }
        // Validate OTP
        if ($otp === ($_SESSION['link_otp'] ?? '')) {
            // Success: insert new link record
            $maKh = $_SESSION['link_profile_id'];
            // Generate a new relationship ID (MaQH) based on current date/time
            $maQh = $this->generateRelationshipId($db);
            $db->query("INSERT INTO quanhelienket (MaQH, MaTV, MaKH, NgayLienKet, TrangThaiLienKet)
                        VALUES (:qh, :tv, :kh, NOW(), 'Đã xác thực')");
            $db->bind(':qh', $maQh);
            $db->bind(':tv', $userId);
            $db->bind(':kh', $maKh);
            try {
                $db->execute();
                flash('info', 'Liên kết hồ sơ thành công.');
            } catch (Exception $e) {
                flash('error', 'Không thể lưu liên kết: ' . $e->getMessage());
            }
            // Clear OTP session data
            $this->clearLinkSession();
            return;
        }
        // Incorrect code: increment attempts
        $_SESSION['link_otp_attempts'] = ($_SESSION['link_otp_attempts'] ?? 0) + 1;
        if ($_SESSION['link_otp_attempts'] >= 3) {
            // Too many attempts: abort linking
            $this->clearLinkSession();
            flash('error', 'Bạn đã nhập sai mã quá 3 lần. Yêu cầu liên kết đã bị hủy.');
            return;
        }
        // Wrong code but attempts remain
        flash('error', 'Mã xác thực không đúng. Vui lòng thử lại.');
    }

    /**
     * Automatically link the user's own profile if it exists in
     * `thongtinkhachhang` but hasn't been linked yet. This ensures the
     * user's personal record always appears in the linked profiles list
     * without manual intervention.
     *
     * @param Database $db     Database instance
     * @param string   $userId Current user ID
     */
    private function autoLinkSelf(Database $db, string $userId): void {
        // Find the user's phone from the accounts table
        $db->query("SELECT SDT FROM taikhoan WHERE MaND = :mand LIMIT 1");
        $db->bind(':mand', $userId);
        $acc = $db->single();
        if (!$acc || empty($acc['SDT'])) {
            return;
        }
        $phone = $acc['SDT'];
        // Find all profiles in thongtinkhachhang that share this phone number.
        // We no longer limit to a single record because multiple profiles
        // (e.g. family members) may legitimately use the same phone.
        $db->query("SELECT MaKH FROM thongtinkhachhang WHERE SDT = :s ORDER BY MaKH ASC");
        $db->bind(':s', $phone);
        $rows = $db->resultSet();
        if (!$rows) {
            return;
        }
        foreach ($rows as $row) {
            if (!isset($row['MaKH']) || empty($row['MaKH'])) {
                continue;
            }
            $maKh = $row['MaKH'];
            // Check if this link already exists
            $db->query("SELECT COUNT(*) AS cnt FROM quanhelienket WHERE MaTV = :tv AND MaKH = :kh");
            $db->bind(':tv', $userId);
            $db->bind(':kh', $maKh);
            $existing = $db->single();
            if ($existing && (int)$existing['cnt'] > 0) {
                // Skip if already linked
                continue;
            }
            // Create a verified link automatically
            $maQh = $this->generateRelationshipId($db);
            $db->query("INSERT INTO quanhelienket (MaQH, MaTV, MaKH, NgayLienKet, TrangThaiLienKet)
                        VALUES (:qh, :tv, :kh, NOW(), 'Đã xác thực')");
            $db->bind(':qh', $maQh);
            $db->bind(':tv', $userId);
            $db->bind(':kh', $maKh);
            try {
                $db->execute();
            } catch (Exception $e) {
                // Suppress errors silently. Self‑linking failure should not block the page.
            }
        }
    }

    /**
     * Generate a new relationship ID (MaQH) for the quanhelienket table.
     * The ID format is `QH` followed by a zero‑padded number based on
     * the current highest value. This ensures uniqueness across all
     * relationship records.
     *
     * @param Database $db Database instance
     * @return string     A unique relationship ID
     */
    private function generateRelationshipId(Database $db): string {
        // Get the maximum numeric suffix in existing IDs
        $db->query("SELECT MaQH FROM quanhelienket ORDER BY MaQH DESC LIMIT 1");
        $row = $db->single();
        $nextNumber = 1;
        if ($row && isset($row['MaQH'])) {
            // Extract numeric part after 'QH'
            $numPart = intval(substr($row['MaQH'], 2));
            $nextNumber = $numPart + 1;
        }
        return 'QH' . str_pad((string)$nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Clear session variables used during the linking process.
     */
    private function clearLinkSession(): void {
        unset(
            $_SESSION['link_phone'],
            $_SESSION['link_profile_id'],
            $_SESSION['link_otp'],
            $_SESSION['link_otp_expiry'],
            $_SESSION['link_otp_attempts']
        );
    }
}
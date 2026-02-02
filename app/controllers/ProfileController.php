<?php
// app/controllers/ProfileController.php

/**
 * Controller responsible for displaying and updating a user's profile
 * information as well as changing the account password.
 *
 * A logged-in user may view their profile (basic details from the
 * `nguoidung` table), edit their personal information, or change
 * their password stored in the `taikhoan` table. All operations
 * require the user to be authenticated.
 */
class ProfileController extends Controller {

    /**
     * Display the user's profile overview.
     *
     * Shows the full name, gender, and address pulled from the
     * `nguoidung` table for the current user. Provides links to edit
     * information and change password.
     */
    public function index() {
        // Only authenticated users can access their profile
        require_role(['customer','staff','admin']);
        $uid = current_user_id();
        $user = null;
        if ($uid) {
            try {
                $db = new Database();
                $db->query("SELECT HoVaTen, GioiTinh, DiaChi FROM nguoidung WHERE MaND = :id LIMIT 1");
                $db->bind(':id', $uid);
                $user = $db->single();
            } catch (Exception $e) {
                // On DB errors, leave $user as null
            }
        }
        
        $params = ['user' => $user];
        $role = current_user_role();
        if ($role === 'staff' || $role === 'admin') {
            $params['hide_navbar'] = true;
            $params['current_role'] = $role;
        }
        $this->view('profile/index', $params);
    }

    /**
     * Edit the user's personal information.
     *
     * Handles both GET and POST requests. On GET, it fetches the
     * current information and displays an editable form. On POST,
     * validates input and updates the record. If validation fails, an
     * error message is flashed and the form is re-rendered with the
     * submitted values.
     */
    public function edit() {
        require_role(['customer','staff','admin']);
        $uid = current_user_id();
        $db = new Database();
        if (is_post()) {
            verify_csrf();
            $name    = sanitize($_POST['HoVaTen'] ?? '');
            $gender  = sanitize($_POST['GioiTinh'] ?? '');
            $address = sanitize($_POST['DiaChi'] ?? '');
            $dob     = sanitize($_POST['NgaySinh'] ?? '');
            // Basic validation: name non-empty, gender is either 'Nam' or 'Nữ', date format if provided
            $valid = true;
            if (!validate_nonempty($name)) {
                flash('error', 'Họ tên không được để trống.');
                $valid = false;
            }
            if ($gender !== 'Nam' && $gender !== 'Nữ') {
                flash('error', 'Giới tính phải là Nam hoặc Nữ.');
                $valid = false;
            }
            // Validate date of birth format (allow empty)
            if ($dob !== '') {
                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob)) {
                    flash('error', 'Ngày sinh phải theo định dạng YYYY-MM-DD.');
                    $valid = false;
                }
            }
            if ($valid) {
                try {
                    $db->query("UPDATE nguoidung SET HoVaTen = :n, GioiTinh = :g, DiaChi = :d, NgaySinh = :dob WHERE MaND = :id");
                    $db->bind(':n', $name);
                    $db->bind(':g', $gender);
                    $db->bind(':d', $address);
                    // If date of birth is empty string, bind NULL
                    $db->bind(':dob', $dob === '' ? null : $dob);
                    $db->bind(':id', $uid);
                    $db->execute();
                    // Update session name
                    $_SESSION['user_name'] = $name;
                    flash('info', 'Cập nhật thông tin thành công.');
                    return redirect('profile');
                } catch (Exception $e) {
                    flash('error', 'Không thể cập nhật thông tin: ' . $e->getMessage());
                }
            }
            // If validation failed or update error, fall through to re-render form with submitted values
            $user = [
                'HoVaTen' => $name,
                'GioiTinh' => $gender,
                'DiaChi' => $address,
                'NgaySinh' => $dob,
            ];
            return $this->view('profile/edit', ['user' => $user]);
        }
        // GET: fetch current data
        $user = null;
        if ($uid) {
            try {
                $db->query("SELECT HoVaTen, GioiTinh, DiaChi, NgaySinh FROM nguoidung WHERE MaND = :id LIMIT 1");
                $db->bind(':id', $uid);
                $user = $db->single();
            } catch (Exception $e) {
                // ignore errors
            }
        }
        $this->view('profile/edit', ['user' => $user]);
    }

    /**
     * Change the user's password.
     *
     * Presents a form to enter the current password and a new password. On
     * submission, verifies the current password matches the stored value,
     * checks the new password length and confirmation, then hashes and
     * stores the new password. Success redirects back to the profile.
     */
    public function change_password() {
        require_role(['customer','staff','admin']);
        $uid = current_user_id();
        if (is_post()) {
            verify_csrf();
            $current = $_POST['current'] ?? '';
            $new     = $_POST['new'] ?? '';
            $confirm = $_POST['confirm'] ?? '';
            // Check new password length and confirmation
            if (strlen($new) < 8) {
                flash('error', 'Mật khẩu mới phải có ít nhất 8 ký tự.');
                return $this->view('profile/change_password', []);
            }
            if ($new !== $confirm) {
                flash('error', 'Xác nhận mật khẩu mới không khớp.');
                return $this->view('profile/change_password', []);
            }
            try {
                $db = new Database();
                // Fetch stored password from taikhoan table
                $db->query("SELECT MatKhau FROM taikhoan WHERE MaND = :id LIMIT 1");
                $db->bind(':id', $uid);
                $row = $db->single();
                if (!$row) {
                    flash('error', 'Không tìm thấy tài khoản.');
                    return $this->view('profile/change_password', []);
                }
                $stored    = $row['MatKhau'] ?? '';
                $isHashed  = (strlen($stored) === 60 && strpos($stored, '$') === 0);
                $validPass = false;
                if ($isHashed) {
                    $validPass = password_verify($current, $stored);
                } else {
                    $validPass = $current === $stored;
                }
                if (!$validPass) {
                    flash('error', 'Mật khẩu hiện tại không chính xác.');
                    return $this->view('profile/change_password', []);
                }
                // Update new password using the raw value provided by the user
                $db->query("UPDATE taikhoan SET MatKhau = :pw WHERE MaND = :id");
                $db->bind(':pw', $new);
                $db->bind(':id', $uid);
                $db->execute();
                flash('info', 'Đổi mật khẩu thành công.');
                return redirect('profile');
            } catch (Exception $e) {
                flash('error', 'Không thể đổi mật khẩu: ' . $e->getMessage());
            }
        }
        // Default: show form
        $this->view('profile/change_password', []);
    }
}
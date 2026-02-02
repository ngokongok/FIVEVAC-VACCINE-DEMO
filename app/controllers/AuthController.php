<?php
class AuthController extends Controller {
    /**
     * Legacy register method retained for backward compatibility.
     * Redirects to the first step of the phone-based registration flow.
     */
    public function register(){
        return $this->register_phone();
    }
    public function login(){
        if (is_post()){
            verify_csrf();
            // Use phone number as the only login identifier. Accept either 'phone' or
            // legacy 'username' field names for backward compatibility.
            $sdt = sanitize($_POST['phone'] ?? $_POST['username'] ?? '');
            $pass = $_POST['password'] ?? '';
            $db = new Database();
            $db->query("SELECT * FROM taikhoan WHERE SDT=:s LIMIT 1");
            $db->bind(':s',$sdt);
            $row = $db->single();
            // Accept both hashed and plain text passwords for legacy data. If the stored password
            // looks like a valid hash, use password_verify(); otherwise fall back to a plain comparison.
            $stored = $row['MatKhau'] ?? '';
            $isHashed = (strlen($stored) === 60 && strpos($stored, '$') === 0);
            $valid = false;
            if ($row) {
                if ($isHashed) {
                    $valid = password_verify($pass, $stored);
                } else {
                    $valid = $pass === $stored;
                }
            }

            if ($valid){
                /*
                 * Successful login
                 *
                 * In addition to setting a default role, capture the user's ID
                 * and full name from the associated tables. Storing these
                 * values in the session enables the application to display
                 * personalised information (e.g. in the navigation bar) and
                 * retrieve user-specific records without repeated queries.
                 */
                // Determine the user's role based on their MaND. Default to customer.
                $userId = $row['MaND'] ?? null;
                $role   = 'customer';
                try {
                    $dbRole = new Database();
                    // Check admin table
                    $dbRole->query("SELECT 1 FROM quantrivien WHERE MaQTV = :id LIMIT 1");
                    $dbRole->bind(':id', $userId);
                    if ($dbRole->single()) {
                        $role = 'admin';
                    } else {
                        // Check staff tables: nhanviencskh or bacsi
                        $dbRole->query("SELECT 1 FROM nhanviencskh WHERE MaNV = :id LIMIT 1");
                        $dbRole->bind(':id', $userId);
                        if ($dbRole->single()) {
                            $role = 'staff';
                        } else {
                            $dbRole->query("SELECT 1 FROM bacsi WHERE MaBS = :id LIMIT 1");
                            $dbRole->bind(':id', $userId);
                            if ($dbRole->single()) {
                                $role = 'staff';
                            } else {
                                // Check customer table (thanhvien) for completeness, though default covers it
                                $dbRole->query("SELECT 1 FROM thanhvien WHERE MaTV = :id LIMIT 1");
                                $dbRole->bind(':id', $userId);
                                if ($dbRole->single()) {
                                    $role = 'customer';
                                }
                            }
                        }
                    }
                } catch (Exception $e) {
                    // On error, default to customer
                    $role = 'customer';
                }
                // If the login request is for the staff portal (identified by the presence
                // of the `staff` query parameter) then only allow users who belong to
                // the bacsi, quantrivien or nhanviencskh tables. Otherwise, prevent
                // customers from logging into the staff site and show an error.
                if (isset($_GET['staff']) && !in_array($role, ['staff', 'admin'])) {
                    // Do not persist session or log the user in. Inform them of the restriction.
                    flash('error', 'Khách hàng không được đăng nhập vào website nhân viên');
                    // Redirect back to the staff login page
                    return redirect('auth/login&staff=1');
                }

                // Persist user ID and role in session
                $_SESSION['role']    = $role;
                $_SESSION['user_id'] = $userId;
                // Attempt to fetch and store the user's full name
                try {
                    $db2 = new Database();
                    $db2->query("SELECT HoVaTen FROM nguoidung WHERE MaND = :id LIMIT 1");
                    $db2->bind(':id', $_SESSION['user_id']);
                    $u = $db2->single();
                    if ($u && isset($u['HoVaTen'])) {
                        $_SESSION['user_name'] = $u['HoVaTen'];
                    }
                } catch (Exception $e) {
                    // Ignore any errors; user_name will remain unset
                }
                /*
                 * If a vaccine order was initiated prior to login, redirect
                 * immediately to the order creation form with the pending
                 * vaccine ID. This supports the flow where guests click
                 * "Đặt mua" on a vaccine card and are prompted to log in.
                 */
                if (!empty($_SESSION['pending_vaccine']) && current_user_role() === 'customer') {
                    $pending = $_SESSION['pending_vaccine'];
                    unset($_SESSION['pending_vaccine']);
                    // Use '&' separator instead of '?'. See Helpers::base_url() for details.
                    redirect('orders/create_onl&vx=' . urlencode($pending));
                }
                // Redirect based on role when no pending redirect exists
                $roleNow = current_user_role();
                if ($roleNow === 'admin') {
                    redirect('admin/dashboard');
                } elseif ($roleNow === 'staff') {
                    redirect('staff/dashboard');
                } else {
                    // Default to home for customers
                    redirect('');
                }
            } else {
                flash('error','Sai số ĐT hoặc mật khẩu');
                // Preserve the entered phone number so the user does not need to retype it
                $params = ['submitted_phone' => $sdt];
                // After a failed login attempt, render the appropriate login page again
                if (isset($_GET['staff'])) {
                    $params['hide_navbar'] = true;
                    return $this->view('auth/login', $params);
                } else {
                    return $this->view('auth/login_signup', $params);
                }
            }
        }
        // Decide which login view to render. If the "staff" flag is present in the query string,
        // show the staff login page without the signup option and hide the navigation bar.
        if (!is_post() && isset($_GET['staff'])) {
            // Show the dedicated login view and hide the navbar
            $this->view('auth/login', ['hide_navbar' => true]);
        } else {
            // Render the combined login/signup page instead of the old login page
            $this->view('auth/login_signup', []);
        }
    }


    /**
     * Initiate the password reset process.
     *
     * Step 1: Collect and validate the phone number associated with an existing account.
     * On GET: display a form where the user can enter their phone number. If a `staff`
     * query parameter is present, the page will be rendered without the navbar. On
     * POST: validate the phone number format and ensure it exists in the `taikhoan`
     * table. If valid, generate an OTP (fixed for demo), store it in session along
     * with an expiry timestamp and attempt counter, then redirect to the OTP entry
     * page. A flash message will notify the user that the OTP has been sent.
     */
    public function reset() {
        // If the user is already in the middle of a reset (i.e. phone stored in session),
        // redirect directly to the OTP entry page.
        if (isset($_SESSION['reset_phone'])) {
            return redirect('auth/reset_otp' . (isset($_GET['staff']) ? '&staff=1' : ''));
        }
        if (is_post()) {
            verify_csrf();
            $phone = sanitize($_POST['phone'] ?? '');
            // Validate phone format
            if (!validate_phone($phone)) {
                flash('error', 'Số điện thoại không hợp lệ. Vui lòng nhập số 10 chữ số bắt đầu bằng 0.');
                return $this->view('auth/reset', [
                    'hide_navbar' => isset($_GET['staff']),
                    'submitted_phone' => $phone
                ]);
            }
            // Check that the phone exists in taikhoan
            $db = new Database();
            $db->query("SELECT MaND FROM taikhoan WHERE SDT = :p LIMIT 1");
            $db->bind(':p', $phone);
            $account = $db->single();
            if (!$account) {
                flash('error', 'Không tìm thấy tài khoản với số điện thoại này.');
                return $this->view('auth/reset', [
                    'hide_navbar' => isset($_GET['staff']),
                    'submitted_phone' => $phone
                ]);
            }
            // Generate OTP (demo fixed) and store in session with expiry and attempts
            $otp = '000000';
            $_SESSION['reset_phone'] = $phone;
            $_SESSION['reset_otp'] = $otp;
            $_SESSION['reset_expiry'] = time() + 60; // OTP valid for 1 minute
            $_SESSION['reset_attempts'] = 0;
            flash('info', 'Đã gửi mã OTP tới số điện thoại của bạn. Mã có hiệu lực trong 1 phút.');
            // Redirect to OTP entry step
            return redirect('auth/reset_otp' . (isset($_GET['staff']) ? '&staff=1' : ''));
        }
        // GET: display phone entry form
        $params = [];
        if (isset($_GET['staff'])) {
            $params['hide_navbar'] = true;
        }
        $this->view('auth/reset', $params);
    }

    /**
     * Step 2 of password reset: verify the OTP and set a new password.
     *
     * On GET: display the OTP input and new password fields with a countdown timer.
     * On POST: validate the OTP and new password. If the OTP matches and has not
     * expired, update the user's password (hashed) in the database using the phone
     * stored in the session. After a successful reset, clear the session variables
     * related to the reset process and redirect to the appropriate login page.
     */
    public function reset_otp() {
        // Ensure a phone has been provided in step 1
        if (!isset($_SESSION['reset_phone'])) {
            return redirect('auth/reset' . (isset($_GET['staff']) ? '&staff=1' : ''));
        }
        if (is_post()) {
            verify_csrf();
            $otpInput = '';
            if (isset($_POST['digit'])) {
                $digits = $_POST['digit'];
                foreach ($digits as $d) {
                    $otpInput .= trim($d);
                }
            } else {
                $otpInput = sanitize($_POST['otp'] ?? '');
            }
            // Check expiry
            if (time() > ($_SESSION['reset_expiry'] ?? 0)) {
                flash('error', 'Mã OTP đã hết hiệu lực.');
                return redirect('auth/reset' . (isset($_GET['staff']) ? '&staff=1' : ''));
            }
            // Increment attempts
            $_SESSION['reset_attempts'] = ($_SESSION['reset_attempts'] ?? 0) + 1;
            if ($_SESSION['reset_attempts'] > 3) {
                flash('error', 'Bạn đã nhập sai OTP quá số lần cho phép.');
                unset($_SESSION['reset_phone'], $_SESSION['reset_otp'], $_SESSION['reset_expiry'], $_SESSION['reset_attempts']);
                return redirect('auth/reset' . (isset($_GET['staff']) ? '&staff=1' : ''));
            }
            // Validate OTP
            if ($otpInput !== ($_SESSION['reset_otp'] ?? '')) {
                flash('error', 'Mã OTP không đúng.');
                return $this->view('auth/reset_otp', ['hide_navbar' => isset($_GET['staff'])]);
            }
            // OTP valid: mark verified and proceed to password reset step
            $_SESSION['reset_verified'] = true;
            return redirect('auth/reset_password' . (isset($_GET['staff']) ? '&staff=1' : ''));
        }
        // GET: render OTP entry view without password field
        $params = [];
        if (isset($_GET['staff'])) {
            $params['hide_navbar'] = true;
        }
        $this->view('auth/reset_otp', $params);
    }

    /**
     * Step 3 of password reset: set a new password after successful OTP verification.
     */
    public function reset_password() {
        // Ensure phone and verification exist
        if (!isset($_SESSION['reset_phone']) || !($_SESSION['reset_verified'] ?? false)) {
            return redirect('auth/reset' . (isset($_GET['staff']) ? '&staff=1' : ''));
        }
        if (is_post()) {
            verify_csrf();
            $password = $_POST['password'] ?? '';
            $confirm  = $_POST['confirm_password'] ?? '';
            if (strlen($password) < 8) {
                flash('error', 'Mật khẩu phải có ít nhất 8 ký tự.');
                return $this->view('auth/reset_password', ['hide_navbar' => isset($_GET['staff'])]);
            }
            if ($password !== $confirm) {
                flash('error', 'Xác nhận mật khẩu không khớp.');
                return $this->view('auth/reset_password', ['hide_navbar' => isset($_GET['staff'])]);
            }
            $phone = $_SESSION['reset_phone'];
            try {
                $db = new Database();
                $db->query("UPDATE taikhoan SET MatKhau = :mk WHERE SDT = :phone");
                // Store the password as provided by the user rather than hashing it
                $db->bind(':mk', $password);
                $db->bind(':phone', $phone);
                $db->execute();
                // Clear session variables related to reset
                unset($_SESSION['reset_phone'], $_SESSION['reset_otp'], $_SESSION['reset_expiry'], $_SESSION['reset_attempts'], $_SESSION['reset_verified']);
                // Notify the user that the password update was successful
                flash('info', 'Cập nhật mật khẩu thành công.');
                // Redirect to appropriate login page
                if (isset($_GET['staff'])) {
                    return redirect('auth/login&staff=1');
                } else {
                    return redirect('auth/login');
                }
            } catch (Exception $e) {
                flash('error', 'Không thể cập nhật mật khẩu: ' . $e->getMessage());
            }
        }
        // GET: show new password form
        $params = [];
        if (isset($_GET['staff'])) {
            $params['hide_navbar'] = true;
        }
        $this->view('auth/reset_password', $params);
    }

    /**
     * Resend the OTP during password reset. Generates a new code and resets the expiry.
     */
    public function send_reset_otp() {
        if (!isset($_SESSION['reset_phone'])) {
            return redirect('auth/reset');
        }
        // Generate new OTP (demo fixed) and update expiry and attempts
        $otp = '000000';
        $_SESSION['reset_otp'] = $otp;
        $_SESSION['reset_expiry'] = time() + 60;
        $_SESSION['reset_attempts'] = 0;
        flash('info', 'Mã OTP mới đã được gửi.');
        return redirect('auth/reset_otp' . (isset($_GET['staff']) ? '&staff=1' : ''));
    }
    public function logout(){ logout(); }

    // Show the combined login & signup interface
    public function form(){
        $this->view('auth/login_signup', []);
    }

    /**
     * Step 1 of phone-based registration: input and validate phone number.
     * On GET: show the phone input form. On POST: validate phone format and uniqueness,
     * generate OTP and redirect to the OTP verification step.
     */
    public function register_phone() {
        if (is_post()) {
            verify_csrf();
            $phone = sanitize($_POST['phone'] ?? '');
            // Validate phone format
            if (!validate_phone($phone)) {
                flash('error', 'Số điện thoại không hợp lệ. Vui lòng nhập số 10 chữ số.');
                return $this->view('auth/register_phone', [ 'submitted_phone' => $phone ]);
            }
            // Check if phone already exists
            $db = new Database();
            $db->query("SELECT * FROM taikhoan WHERE SDT = :s LIMIT 1");
            $db->bind(':s', $phone);
            $existing = $db->single();
            if ($existing) {
                flash('error', 'Số điện thoại đã tồn tại. Vui lòng sử dụng số khác hoặc đăng nhập.');
                return $this->view('auth/register_phone', [ 'submitted_phone' => $phone ]);
            }
            // Generate OTP (for demo purposes we use a fixed code) and store in session
            $otp = '000000'; // demo code; replace with random generation in production
            $_SESSION['otp_phone'] = $phone;
            $_SESSION['otp_code'] = $otp;
            $_SESSION['otp_expiry'] = time() + 60; // OTP valid for 1 minute
            $_SESSION['otp_attempts'] = 0;
            // Notify user
            flash('info', 'Đã gửi mã OTP đến số điện thoại của bạn. Mã có hiệu lực trong 1 phút.');
            // Redirect to OTP verification step
            redirect('auth/verify_otp');
        }
        // Default: show phone entry form
        $this->view('auth/register_phone', []);
    }

    /**
     * Step 2: Verify the OTP entered by the user. Allows up to 3 attempts.
     * On GET: show the OTP input form. On POST: validate OTP and move to password step.
     */
    public function verify_otp() {
        // If no phone was submitted, redirect to phone input
        if (!isset($_SESSION['otp_phone'])) {
            redirect('auth/register_phone');
        }
        if (is_post()) {
            verify_csrf();
            // Collect the six digits from the form
            $digits = $_POST['digit'] ?? [];
            $code = '';
            foreach ($digits as $d) {
                $code .= trim($d);
            }
            // Check expiry
            if (time() > ($_SESSION['otp_expiry'] ?? 0)) {
                // Expired: allow user to resend
                unset($_SESSION['otp_code']);
                flash('error', 'Mã OTP đã hết hiệu lực.');
                return redirect('auth/verify_otp');
            }
            // Check code
            if ($code === ($_SESSION['otp_code'] ?? '')) {
                // Success: mark phone verified and proceed to password step
                $_SESSION['phone_verified'] = true;
                return redirect('auth/set_password');
            }
            // Incorrect code: increment attempts
            $_SESSION['otp_attempts'] = ($_SESSION['otp_attempts'] ?? 0) + 1;
            if ($_SESSION['otp_attempts'] >= 3) {
                // Too many attempts: abort registration
                flash('error', 'Bạn đã nhập sai mã OTP quá 3 lần. Đăng ký bị dừng lại.');
                // Clear session variables
                unset($_SESSION['otp_phone'], $_SESSION['otp_code'], $_SESSION['otp_expiry'], $_SESSION['otp_attempts']);
                return redirect('auth/register_phone');
            }
            // Wrong code but attempts remain: show error
            flash('error', 'Mã OTP không đúng! Hãy nhập lại.');
            return redirect('auth/verify_otp');
        }
        // Show OTP form
        $this->view('auth/verify_otp', []);
    }

    /**
     * Resend OTP to the phone number stored in the session. Generates a new code and resets expiry and attempts.
     */
    public function send_otp() {
        if (!isset($_SESSION['otp_phone'])) {
            return redirect('auth/register_phone');
        }
        // Generate new OTP (still fixed for demo) and set expiry
        $otp = '000000';
        $_SESSION['otp_code'] = $otp;
        $_SESSION['otp_expiry'] = time() + 60;
        $_SESSION['otp_attempts'] = 0;
        flash('info', 'Mã OTP mới đã được gửi.');
        return redirect('auth/verify_otp');
    }

    /**
     * Step 3: Set the password and complete registration after phone verification.
     */
    public function set_password() {
        // Ensure phone verification has been completed
        if (!($_SESSION['phone_verified'] ?? false) || !isset($_SESSION['otp_phone'])) {
            return redirect('auth/register_phone');
        }
        if (is_post()) {
            verify_csrf();
            $name = sanitize($_POST['name'] ?? 'Khách hàng Fivevac');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';
            // Validate password length and confirmation
            if (strlen($password) < 8) {
                flash('error', 'Mật khẩu phải có ít nhất 8 ký tự.');
                return $this->view('auth/set_password', []);
            }
            if ($password !== $confirm) {
                flash('error', 'Xác nhận mật khẩu không khớp.');
                return $this->view('auth/set_password', []);
            }
            // Phone number from session
            $phone = $_SESSION['otp_phone'];
            // Double check that the phone still does not exist (in case of race condition)
            $db = new Database();
            $db->query("SELECT * FROM taikhoan WHERE SDT = :s LIMIT 1");
            $db->bind(':s', $phone);
            $existing = $db->single();
            if ($existing) {
                flash('error', 'Số điện thoại đã tồn tại. Không thể tạo tài khoản.');
                // Clean up registration session variables
                unset($_SESSION['otp_phone'], $_SESSION['otp_code'], $_SESSION['otp_expiry'], $_SESSION['otp_attempts'], $_SESSION['phone_verified']);
                return $this->view('auth/register_phone', []);
            }
            
            try {
                $db->begin();
                
                $db->query("SELECT MaND FROM nguoidung ORDER BY MaND DESC LIMIT 1");
                $lastND = $db->single();
                $nextND = 1;
                if ($lastND && isset($lastND['MaND'])) {
                
                    $nextND = intval(substr($lastND['MaND'], 2)) + 1;
                }
                $newMaND = 'ND' . str_pad((string)$nextND, 3, '0', STR_PAD_LEFT);
                
                $db->query("SELECT MaTK FROM taikhoan ORDER BY MaTK DESC LIMIT 1");
                $lastTK = $db->single();
                $nextTK = 1;
                if ($lastTK && isset($lastTK['MaTK'])) {
                    $nextTK = intval(substr($lastTK['MaTK'], 2)) + 1;
                }
                $newMaTK = 'TK' . str_pad((string)$nextTK, 3, '0', STR_PAD_LEFT);
                
                $db->query("INSERT INTO nguoidung (MaND, HoVaTen) VALUES (:id, :name)");
                $db->bind(':id', $newMaND);
                $db->bind(':name', $name);
                $db->execute();
                
                $db->query("INSERT INTO taikhoan (MaTK, MaND, SDT, MatKhau) VALUES (:tk, :mand, :sdt, :mk)");
                $db->bind(':tk', $newMaTK);
                $db->bind(':mand', $newMaND);
                $db->bind(':sdt', $phone);
                $db->bind(':mk', $password);
                $db->execute();
                
                $db->query("INSERT INTO thanhvien (MaTV) VALUES (:id)");
                $db->bind(':id', $newMaND);
                $db->execute();
                $db->commit();
                
                // Clear registration-related session variables
                unset($_SESSION['otp_phone'], $_SESSION['otp_code'], $_SESSION['otp_expiry'], $_SESSION['otp_attempts'], $_SESSION['phone_verified']);
                
                // Notify user of successful registration. Do not auto-login the new account or immediately redirect.
                // Instead, render a dedicated success page so the user can choose when to navigate to the login page.
                flash('info', 'Đăng ký tài khoản thành công. Hãy chuyển đến trang Đăng nhập.');
                return $this->view('auth/register_success');
            } catch(Exception $e) {
                $db->rollBack();
                flash('error', 'Không thể tạo tài khoản: ' . $e->getMessage());
                return $this->view('auth/set_password', []);
            }
        }
        // Show set-password form
        $this->view('auth/set_password', []);
    }
}
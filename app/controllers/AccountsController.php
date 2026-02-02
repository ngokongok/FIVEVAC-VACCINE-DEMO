<?php
    // app/controllers/AccountsController.php
class AccountsController extends Controller {
    /**
     * Display a list of all accounts in the system. Each row includes
     * details from both the `taikhoan` and `nguoidung` tables and
     * provides links to edit or delete the account. Only admins may
     * access this page. (UC‑10)
     */
    public function index() {
        require_role(['admin']);
        // If a deletion was canceled (Quay về clicked), show an informational message
        if (isset($_GET['cancel'])) {
            flash('info', 'Tài khoản chưa được xóa.');
        }
        $db = new Database();
        // Join account and user tables to get human‑readable details
        $db->query("SELECT tk.MaTK, tk.MaND, tk.SDT, tk.MatKhau, nd.HoVaTen, nd.NgaySinh, nd.GioiTinh, nd.DiaChi
                    FROM taikhoan tk
                    JOIN nguoidung nd ON tk.MaND = nd.MaND
                    ORDER BY tk.MaTK ASC");
        $accounts = $db->resultSet();
        // Render the list with the admin sidebar. Hide the top navbar.
        $this->view('accounts/index', [
            'accounts'    => $accounts,
            'hide_navbar' => true,
            'title'       => 'Quản lý tài khoản'
        ]);
    }

    /**
     * Create a new user and account. Presents a form for input on GET
     * requests and processes submission on POST. Validates required
     * fields, phone number format and password strength. On success,
     * inserts into `nguoidung`, `taikhoan` and the appropriate role
     * table (`quantrivien`, `bacsi`, `nhanviencskh` or `thanhvien`).
     */
    public function add() {
        require_role(['admin']);
        if (is_post()) {
            verify_csrf();
            // Gather input and sanitize
            $name     = sanitize($_POST['name'] ?? '');
            $dob      = sanitize($_POST['dob'] ?? '');
            $gender   = sanitize($_POST['gender'] ?? '');
            $address  = sanitize($_POST['address'] ?? '');
            $phone    = sanitize($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';
            $role     = sanitize($_POST['role'] ?? '');
            // Basic required field check
            if ($name === '' || $phone === '' || $password === '') {
                flash('error', 'Vui lòng nhập đầy đủ thông tin bắt buộc.');
                return $this->view('accounts/add', [
                    'hide_navbar' => true,
                    'title'       => 'Thêm tài khoản'
                ]);
            }
            // Validate phone (must start with 0 and contain exactly 10 digits)
            if (!validate_phone($phone)) {
                flash('error', 'Số điện thoại không hợp lệ.');
                return $this->view('accounts/add', [
                    'hide_navbar' => true,
                    'title'       => 'Thêm tài khoản'
                ]);
            }
            // Validate password length
            if (strlen($password) < 8) {
                flash('error', 'Mật khẩu phải có ít nhất 8 ký tự.');
                return $this->view('accounts/add', [
                    'hide_navbar' => true,
                    'title'       => 'Thêm tài khoản'
                ]);
            }
            // Validate role against allowed options
            $allowed = ['admin','doctor','cskh','member'];
            if (!in_array($role, $allowed, true)) {
                flash('error', 'Vai trò không hợp lệ.');
                return $this->view('accounts/add', [
                    'hide_navbar' => true,
                    'title'       => 'Thêm tài khoản'
                ]);
            }
            $db = new Database();
            // Check if phone already exists in any account
            $db->query("SELECT COUNT(*) AS cnt FROM taikhoan WHERE SDT = :p");
            $db->bind(':p', $phone);
            $dupPhone = $db->single();
            if ($dupPhone && (int)($dupPhone['cnt'] ?? 0) > 0) {
                flash('error', 'Số điện thoại đã tồn tại.');
                return $this->view('accounts/add', []);
            }
            // Check duplicate person profile: same name, dob, gender and address
            $db->query("SELECT MaND FROM nguoidung WHERE HoVaTen = :name AND NgaySinh <=> :dob AND GioiTinh <=> :gender AND DiaChi = :addr LIMIT 1");
            $db->bind(':name', $name);
            $db->bind(':dob', $dob === '' ? null : $dob);
            $db->bind(':gender', $gender === '' ? null : $gender);
            $db->bind(':addr', $address);
            $dup = $db->single();
            if ($dup) {
                flash('error', 'Hồ sơ đã tồn tại, hãy nhập lại.');
                return $this->view('accounts/add', []);
            }
            // Generate next IDs for user and account
            // Determine next MaND (e.g. ND083)
            $db->query("SELECT MaND FROM nguoidung ORDER BY MaND DESC LIMIT 1");
            $lastND = $db->single();
            $nextND = 1;
            if ($lastND && isset($lastND['MaND'])) {
                $nextND = intval(substr($lastND['MaND'], 2)) + 1;
            }
            $newMaND = 'ND' . str_pad((string)$nextND, 3, '0', STR_PAD_LEFT);
            // Determine next MaTK (e.g. TK083)
            $db->query("SELECT MaTK FROM taikhoan ORDER BY MaTK DESC LIMIT 1");
            $lastTK = $db->single();
            $nextTK = 1;
            if ($lastTK && isset($lastTK['MaTK'])) {
                $nextTK = intval(substr($lastTK['MaTK'], 2)) + 1;
            }
            $newMaTK = 'TK' . str_pad((string)$nextTK, 3, '0', STR_PAD_LEFT);
            try {
                $db->begin();
                // Insert into nguoidung
                $db->query("INSERT INTO nguoidung (MaND, HoVaTen, NgaySinh, GioiTinh, DiaChi) VALUES (:id, :name, :dob, :gender, :addr)");
                $db->bind(':id', $newMaND);
                $db->bind(':name', $name);
                $db->bind(':dob', $dob === '' ? null : $dob);
                $db->bind(':gender', $gender === '' ? null : $gender);
                $db->bind(':addr', $address);
                $db->execute();
                // Insert into taikhoan
                $db->query("INSERT INTO taikhoan (MaTK, MaND, SDT, MatKhau) VALUES (:tk, :mand, :phone, :pw)");
                $db->bind(':tk', $newMaTK);
                $db->bind(':mand', $newMaND);
                $db->bind(':phone', $phone);
                $db->bind(':pw', password_hash($password, PASSWORD_BCRYPT));
                $db->execute();
                // Insert into role-specific table
                if ($role === 'admin') {
                    $db->query("INSERT INTO quantrivien (MaQTV) VALUES (:id)");
                    $db->bind(':id', $newMaND);
                    $db->execute();
                } elseif ($role === 'doctor') {
                    $db->query("INSERT INTO bacsi (MaBS) VALUES (:id)");
                    $db->bind(':id', $newMaND);
                    $db->execute();
                } elseif ($role === 'cskh') {
                    $db->query("INSERT INTO nhanviencskh (MaNV) VALUES (:id)");
                    $db->bind(':id', $newMaND);
                    $db->execute();
                } elseif ($role === 'member') {
                    $db->query("INSERT INTO thanhvien (MaTV) VALUES (:id)");
                    $db->bind(':id', $newMaND);
                    $db->execute();
                }
                $db->commit();
                flash('info', 'Tạo tài khoản thành công.');
                return redirect('accounts');
            } catch (Exception $e) {
                $db->rollBack();
                flash('error', 'Không thể tạo tài khoản: ' . $e->getMessage());
                return $this->view('accounts/add', []);
            }
        }
        // GET request: render the form
        $this->view('accounts/add', [
            'hide_navbar' => true,
            'title'       => 'Thêm tài khoản'
        ]);
    }

    /**
     * Edit an existing account. Only the phone number and password
     * can be changed. The current phone number is shown as default.
     * Validation ensures phone format and password length. On
     * submission, updates the `taikhoan` table accordingly. (UC‑10.2)
     */
    public function edit() {
        require_role(['admin']);
        // Accept ID from GET or POST
        $idParam = $_GET['id'] ?? ($_POST['id'] ?? null);
        if (!$idParam) {
            flash('error', 'Thiếu mã tài khoản.');
            return redirect('accounts');
        }
        if (is_post()) {
            verify_csrf();
            $id       = sanitize($_POST['id'] ?? '');
            $phone    = sanitize($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';
            // Validate phone format
            if (!validate_phone($phone)) {
                flash('error', 'Số điện thoại không hợp lệ.');
                return redirect('accounts/edit&id=' . urlencode($id));
            }
            // Validate password if provided
            if ($password !== '' && strlen($password) < 8) {
                flash('error', 'Mật khẩu phải có ít nhất 8 ký tự.');
                return redirect('accounts/edit&id=' . urlencode($id));
            }
            $db = new Database();
            // Ensure phone not used by another account
            $db->query("SELECT COUNT(*) AS cnt FROM taikhoan WHERE SDT = :phone AND MaTK <> :id");
            $db->bind(':phone', $phone);
            $db->bind(':id', $id);
            $dup = $db->single();
            if ($dup && (int)($dup['cnt'] ?? 0) > 0) {
                flash('error', 'Số điện thoại đã được sử dụng bởi tài khoản khác.');
                return redirect('accounts/edit&id=' . urlencode($id));
            }
            try {
                $db->begin();
                // Update phone number
                $db->query("UPDATE taikhoan SET SDT = :phone WHERE MaTK = :id");
                $db->bind(':phone', $phone);
                $db->bind(':id', $id);
                $db->execute();
                // Update password if provided. Store the raw password like other user flows
                if ($password !== '') {
                    $db->query("UPDATE taikhoan SET MatKhau = :pw WHERE MaTK = :id");
                    $db->bind(':pw', $password);
                    $db->bind(':id', $id);
                    $db->execute();
                }
                $db->commit();
                flash('info', 'Cập nhật tài khoản thành công.');
                return redirect('accounts');
            } catch (Exception $e) {
                $db->rollBack();
                flash('error', 'Không thể cập nhật tài khoản: ' . $e->getMessage());
                return redirect('accounts/edit&id=' . urlencode($id));
            }
        }
        // GET: load the account info
        $db = new Database();
        $db->query("SELECT * FROM taikhoan WHERE MaTK = :id LIMIT 1");
        $db->bind(':id', $idParam);
        $account = $db->single();
        if (!$account) {
            flash('error', 'Tài khoản không tồn tại.');
            return redirect('accounts');
        }
        // Render the edit form with admin sidebar.
        $this->view('accounts/edit', [
            'account'     => $account,
            'hide_navbar' => true,
            'title'       => 'Chỉnh sửa tài khoản'
        ]);
    }

    /**
     * Delete an account and all associated data. Displays a
     * confirmation prompt on GET and processes deletion on POST.
     * Associated entries in role tables and the `nguoidung` table are
     * removed. If the user cancels, no action is taken. (UC‑10.3)
     */
    public function delete() {
        require_role(['admin']);
        $idParam = $_GET['id'] ?? ($_POST['id'] ?? null);
        if (!$idParam) {
            flash('error', 'Thiếu mã tài khoản.');
                return redirect('accounts');
        }
        if (is_post()) {
            verify_csrf();
            // Check if the admin confirmed deletion
            if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
                $db = new Database();
                // Find MaND tied to this account
                $db->query("SELECT MaND FROM taikhoan WHERE MaTK = :id LIMIT 1");
                $db->bind(':id', $idParam);
                $row = $db->single();
                if (!$row) {
                    flash('error', 'Tài khoản không tồn tại.');
                    return redirect('accounts');
                }
                $mand = $row['MaND'];
                try {
                    $db->begin();
                    // Remove from role-specific tables. Some tables use different column names.
                    // Attempt deletion across all known role tables without errors if absent.
                    // Delete from quantrivien
                    $db->query("DELETE FROM quantrivien WHERE MaQTV = :mand");
                    $db->bind(':mand', $mand);
                    $db->execute();
                    // Delete from bacsi
                    $db->query("DELETE FROM bacsi WHERE MaBS = :mand");
                    $db->bind(':mand', $mand);
                    $db->execute();
                    // Delete from nhanviencskh
                    $db->query("DELETE FROM nhanviencskh WHERE MaNV = :mand");
                    $db->bind(':mand', $mand);
                    $db->execute();
                    // Delete from thanhvien
                    $db->query("DELETE FROM thanhvien WHERE MaTV = :mand");
                    $db->bind(':mand', $mand);
                    $db->execute();
                    // Delete account
                    $db->query("DELETE FROM taikhoan WHERE MaTK = :tk");
                    $db->bind(':tk', $idParam);
                    $db->execute();
                    // Delete user record
                    $db->query("DELETE FROM nguoidung WHERE MaND = :mand");
                    $db->bind(':mand', $mand);
                    $db->execute();
                    $db->commit();
                    flash('info', 'Đã xóa tài khoản cùng dữ liệu liên quan.');
                    return redirect('accounts');
                } catch (Exception $e) {
                    $db->rollBack();
                    flash('error', 'Không thể xóa tài khoản: ' . $e->getMessage());
                    return redirect('accounts');
                }
            }
            // If not confirmed, notify that the account was not deleted
            flash('info', 'Tài khoản chưa được xóa.');
            return redirect('accounts');
        }
        // GET: show confirmation prompt
        // Show confirmation prompt with sidebar.
        $this->view('accounts/delete', [
            'id'          => $idParam,
            'hide_navbar' => true,
            'title'       => 'Xóa tài khoản'
        ]);
    }
}
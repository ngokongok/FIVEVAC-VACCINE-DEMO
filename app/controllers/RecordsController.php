<?php
    // app/controllers/RecordsController.php
class RecordsController extends Controller {

    // Tra cứu hồ sơ tiêm chủng
    public function search() {
        // Cho phép khách hàng, nhân viên hoặc quản trị tra cứu
        require_role(['customer','staff','admin']);
        // Hide the main navbar when staff is viewing the search page
        $hide = (current_user_role() === 'staff');
        $this->view('records/search', [
            'hide_navbar' => $hide,
            'title'       => 'Tra cứu hồ sơ'
        ]);
    }

    // Thêm hồ sơ mới – chỉ nhân viên hoặc quản trị
    public function add_profile() {
        require_role(['staff','admin']);
        // Check that the current staff is CSKH (customer service). Doctors are not
        // allowed to add new profiles. This mirrors the sidebar logic.
        $dbRole = new Database();
        $isCSKH = false;
        try {
            $dbRole->query("SELECT 1 FROM nhanviencskh WHERE MaNV = :id LIMIT 1");
            $dbRole->bind(':id', current_user_id());
            $isCSKH = (bool)$dbRole->single();
        } catch (Exception $e) {}
        if (!$isCSKH) {
            flash('error', 'Bạn không có quyền tạo hồ sơ mới.');
            return redirect('records/manage');
        }
        if (is_post()) {
            verify_csrf();
            $name    = sanitize($_POST['name']    ?? '');
            $dob     = sanitize($_POST['dob']     ?? '');
            $gender  = sanitize($_POST['gender']  ?? '');
            $phone   = sanitize($_POST['phone']   ?? '');
            $address = sanitize($_POST['address'] ?? '');
            $disease = sanitize($_POST['disease'] ?? '');
            // Basic validation: required fields name, dob, phone, address
            if ($name === '' || $dob === '' || $phone === '' || $address === '') {
                flash('error', 'Vui lòng nhập đầy đủ thông tin.');
                return $this->view('records/add_profile', [
                    'hide_navbar' => true,
                    'title'       => 'Thêm hồ sơ mới'
                ]);
            }
            // Validate phone number format (10 digits starting with 0)
            if (!validate_phone($phone)) {
                flash('error', 'Số điện thoại không hợp lệ.');
                return $this->view('records/add_profile', [
                    'hide_navbar' => true,
                    'title'       => 'Thêm hồ sơ mới'
                ]);
            }
            $db = new Database();
            // Check for duplicates: existing row with same name, dob, gender, phone, address and disease
            $db->query("SELECT COUNT(*) AS cnt FROM thongtinkhachhang WHERE HoTen = :n AND NgaySinh = :d AND GioiTinh <=> :g AND SDT = :p AND DiaChi = :a AND (BenhNen IS NULL OR BenhNen = :dis)");
            $db->bind(':n', $name);
            $db->bind(':d', $dob);
            $db->bind(':g', $gender === '' ? null : $gender);
            $db->bind(':p', $phone);
            $db->bind(':a', $address);
            $db->bind(':dis', $disease);
            $dup = $db->single();
            if ($dup && (int)($dup['cnt'] ?? 0) > 0) {
                flash('error', 'Hồ sơ đã tồn tại');
                return $this->view('records/add_profile', [
                    'hide_navbar' => true,
                    'title'       => 'Thêm hồ sơ mới'
                ]);
            }
            // Generate new MaKH (e.g. KH087). Find highest numeric suffix and increment
            $db->query("SELECT MaKH FROM thongtinkhachhang ORDER BY MaKH DESC LIMIT 1");
            $row = $db->single();
            $nextNum = 1;
            if ($row && isset($row['MaKH'])) {
                $current = $row['MaKH'];
                $numPart = intval(substr($current, 2));
                $nextNum = $numPart + 1;
            }
            $newId = 'KH' . str_pad((string)$nextNum, 3, '0', STR_PAD_LEFT);
            // Insert new record
            $db->query("INSERT INTO thongtinkhachhang (MaKH, HoTen, NgaySinh, GioiTinh, DiaChi, SDT, BenhNen) VALUES (:id, :n, :d, :g, :a, :p, :dis)");
            $db->bind(':id', $newId);
            $db->bind(':n', $name);
            $db->bind(':d', $dob);
            $db->bind(':g', $gender === '' ? null : $gender);
            $db->bind(':a', $address);
            $db->bind(':p', $phone);
            $db->bind(':dis', $disease === '' ? null : $disease);
            try {
                $db->execute();
                flash('info', 'Tạo hồ sơ thành công.');
                return redirect('records/manage');
            } catch (Exception $e) {
                flash('error', 'Không thể tạo hồ sơ: ' . $e->getMessage());
                return $this->view('records/add_profile', [
                    'hide_navbar' => true,
                    'title'       => 'Thêm hồ sơ mới'
                ]);
            }
        }
        // GET: show form
        $this->view('records/add_profile', [
            'hide_navbar' => true,
            'title'       => 'Thêm hồ sơ mới'
        ]);
    }

    // Chỉnh sửa hồ sơ – chỉ nhân viên hoặc quản trị
    public function edit_profile() {
        require_role(['staff','admin']);
        // Only CSKH staff and admins may edit profiles. Doctors (bacsi) are
        // represented as staff in the session but are not listed in
        // nhanviencskh. If the current user is staff but not CSKH, deny.
        $canEdit = true;
        if (current_user_role() === 'staff') {
            try {
                $dbCheck = new Database();
                $dbCheck->query("SELECT 1 FROM nhanviencskh WHERE MaNV = :id LIMIT 1");
                $dbCheck->bind(':id', current_user_id());
                $canEdit = (bool)$dbCheck->single();
            } catch (Exception $e) {
                $canEdit = false;
            }
        }
        if (!$canEdit) {
            flash('error', 'Bạn không có quyền chỉnh sửa hồ sơ.');
            return redirect('records/manage');
        }
        // If form submitted via POST, process update
        if (is_post()) {
            verify_csrf();
            // Collect and sanitize input
            $id     = sanitize($_POST['profile_id'] ?? '');
            $name   = sanitize($_POST['name'] ?? '');
            $dob    = sanitize($_POST['dob'] ?? '');
            $gender = sanitize($_POST['gender'] ?? '');
            $phone  = sanitize($_POST['phone'] ?? '');
            $addr   = sanitize($_POST['address'] ?? '');
            $disease= sanitize($_POST['disease'] ?? '');
            // Validate required fields
            if ($id === '' || $name === '' || $dob === '' || $phone === '' || $addr === '') {
                flash('error', 'Vui lòng nhập đầy đủ thông tin.');
                return redirect('records/edit_profile&profile_id=' . urlencode($id));
            }
            // Validate phone number format (10 digits starting with 0)
            if (!validate_phone($phone)) {
                flash('error', 'Số điện thoại không hợp lệ.');
                return redirect('records/edit_profile&profile_id=' . urlencode($id));
            }
            // Check for duplicate record: any record with exact same fields except current ID
            try {
                $dbDup = new Database();
                $dbDup->query("SELECT COUNT(*) AS cnt FROM thongtinkhachhang WHERE MaKH <> :id AND HoTen = :name AND NgaySinh = :dob AND GioiTinh <=> :gender AND DiaChi = :addr AND SDT = :phone AND BenhNen <=> :disease");
                $dbDup->bind(':id', $id);
                $dbDup->bind(':name', $name);
                $dbDup->bind(':dob', $dob);
                $dbDup->bind(':gender', $gender === '' ? null : $gender);
                $dbDup->bind(':addr', $addr);
                $dbDup->bind(':phone', $phone);
                $dbDup->bind(':disease', $disease === '' ? null : $disease);
                $dupRow = $dbDup->single();
                if ($dupRow && (int)$dupRow['cnt'] > 0) {
                    flash('error', 'Hồ sơ đã tồn tại.');
                    return redirect('records/edit_profile&profile_id=' . urlencode($id));
                }
            } catch (Exception $e) {
                // ignore duplicate check on failure
            }
            // Update the record
            try {
                $dbUpdate = new Database();
                $dbUpdate->query("UPDATE thongtinkhachhang SET HoTen = :name, NgaySinh = :dob, GioiTinh = :gender, DiaChi = :addr, SDT = :phone, BenhNen = :disease WHERE MaKH = :id");
                $dbUpdate->bind(':name', $name);
                $dbUpdate->bind(':dob', $dob);
                $dbUpdate->bind(':gender', $gender);
                $dbUpdate->bind(':addr', $addr);
                $dbUpdate->bind(':phone', $phone);
                $dbUpdate->bind(':disease', $disease === '' ? null : $disease);
                $dbUpdate->bind(':id', $id);
                $dbUpdate->execute();
                flash('info', 'Cập nhật hồ sơ thành công.');
                return redirect('records/view_profile/' . urlencode($id));
            } catch (Exception $e) {
                flash('error', 'Không thể cập nhật hồ sơ: ' . $e->getMessage());
                return redirect('records/edit_profile&profile_id=' . urlencode($id));
            }
        }
        // GET: load data for editing
        $idParam = $_GET['profile_id'] ?? null;
        $profile = null;
        if ($idParam) {
            $idParam = sanitize($idParam);
            $db = new Database();
            $db->query("SELECT * FROM thongtinkhachhang WHERE MaKH = :kh LIMIT 1");
            $db->bind(':kh', $idParam);
            $profile = $db->single();
        }
        $this->view('records/edit_profile', [
            'hide_navbar' => true,
            'title'       => 'Chỉnh sửa hồ sơ',
            'profile'     => $profile
        ]);
    }

    // Thêm mũi tiêm vào hồ sơ – chỉ nhân viên hoặc quản trị
    public function add_injection() {
        require_role(['staff','admin']);
        $this->view('records/add_injection', [
            'hide_navbar' => true,
            'title'       => 'Thêm phiếu tiêm'
        ]);
    }

    /**
     * Show detailed information for a vaccination profile and its injection history.
     * Staff and admin users can view this page via the "Chi tiết" button in
     * the management interface. The page presents personal details and a
     * chronological list of injection records. A "Chỉnh sửa hồ sơ" button
     * allows staff to edit the profile.
     *
     * @param string $id The MaKH (profile ID) to display
     */
    public function view_profile($id) {
        require_role(['staff','admin']);
        $id = sanitize($id);
        $db = new Database();
        // Fetch profile information
        $db->query("SELECT * FROM thongtinkhachhang WHERE MaKH = :kh LIMIT 1");
        $db->bind(':kh', $id);
        $profile = $db->single();
        // Fetch injection records
        $db->query("SELECT * FROM phieutiem WHERE MaKH = :kh ORDER BY NgayTiem DESC, GioTiem DESC");
        $db->bind(':kh', $id);
        $injections = $db->resultSet() ?? [];
        // Determine if the current user is allowed to edit this profile. Only
        // CSKH staff and admins can edit profiles. Doctors can view but not
        // edit. To check CSKH, query the nhanviencskh table. If the user
        // has the 'admin' role, editing is always permitted.
        $allowEdit = false;
        if (current_user_role() === 'admin') {
            $allowEdit = true;
        } elseif (current_user_role() === 'staff') {
            try {
                $dbCheck = new Database();
                $dbCheck->query("SELECT 1 FROM nhanviencskh WHERE MaNV = :id LIMIT 1");
                $dbCheck->bind(':id', current_user_id());
                $allowEdit = (bool)$dbCheck->single();
            } catch (Exception $e) {
                $allowEdit = false;
            }
        }
        // Render the view
        $this->view('records/view_profile', [
            'hide_navbar' => true,
            'profile'     => $profile,
            'injections'  => $injections,
            'title'       => 'Chi tiết hồ sơ',
            'allow_edit'  => $allowEdit
        ]);
    }

    /**
     * Unified management page for vaccination profiles (Quản lý hồ sơ tiêm chủng).
     *
     * This method replaces the separate search and add profile pages for staff
     * users. It renders a single interface where staff can search existing
     * profiles and create new ones. Search results and form handling are
     * intentionally minimal; full search and validation logic would be
     * implemented by querying the database and validating input on POST.
     */
    public function manage() {
        require_role(['staff','admin']);
        // Only CSKH staff and admins may access this page. Doctors (bacsi) are
        // represented with the same 'staff' role but are not listed in
        // nhanviencskh. If the current user is staff but not CSKH, deny access.
        $allowAccess = true;
        if (current_user_role() === 'staff') {
            try {
                $dbCheck = new Database();
                $dbCheck->query("SELECT 1 FROM nhanviencskh WHERE MaNV = :id LIMIT 1");
                $dbCheck->bind(':id', current_user_id());
                $allowAccess = (bool)$dbCheck->single();
            } catch (Exception $e) {
                $allowAccess = false;
            }
        }
        if (!$allowAccess) {
            flash('error', 'Bạn không có quyền truy cập chức năng này.');
            // Redirect doctors back to the staff dashboard
            return redirect('staff/dashboard');
        }
        // Determine if a search is being performed. If the request is a POST and either
        // name or phone has been provided, we treat it as a search. Otherwise we load
        // all records by default.
        $results   = [];
        $searching = false;
        if (is_post()) {
            verify_csrf();
            $name  = sanitize($_POST['name']  ?? '');
            $phone = sanitize($_POST['phone'] ?? '');
            // Only treat as a search if at least one field is non-empty
            if ($name !== '' || $phone !== '') {
                $searching = true;
                $db    = new Database();
                $sql   = "SELECT * FROM thongtinkhachhang WHERE 1=1";
                if ($name !== '') {
                    $sql .= " AND HoTen LIKE :name";
                }
                if ($phone !== '') {
                    $sql .= " AND SDT LIKE :phone";
                }
                $db->query($sql);
                if ($name !== '') {
                    $db->bind(':name', '%' . $name . '%');
                }
                if ($phone !== '') {
                    $db->bind(':phone', '%' . $phone . '%');
                }
                try {
                    $results = $db->resultSet() ?? [];
                } catch (Exception $e) {
                    $results = [];
                }
            }
        }
        // If not searching, load all records from thongtinkhachhang to show the full list.
        if (!$searching) {
            try {
                $dbAll = new Database();
                $dbAll->query("SELECT * FROM thongtinkhachhang ORDER BY MaKH ASC");
                $results = $dbAll->resultSet() ?? [];
            } catch (Exception $e) {
                $results = [];
            }
        }
        $this->view('records/manage', [
            'hide_navbar' => true,
            'title'       => 'Quản lý hồ sơ tiêm chủng',
            'results'     => $results,
            'searching'   => $searching
        ]);
    }

    /**
     * Doctor-only management page for searching vaccination profiles (Tra cứu hồ sơ).
     *
     * Doctors (bacsi) can look up profiles by name or phone but cannot add or edit
     * records. By default, all profiles are listed unless a search is
     * performed. Search results are filtered by partial matches. If no
     * matches are found during a search, an informational message is displayed.
     */
    public function doctor_manage() {
        require_role(['staff','admin']);
        // Only allow access for doctors (staff not in nhanviencskh) or admins. CSKH
        // staff should continue to use the main manage page.
        $allowAccess = true;
        if (current_user_role() === 'staff') {
            try {
                $db = new Database();
                $db->query("SELECT 1 FROM nhanviencskh WHERE MaNV = :id LIMIT 1");
                $db->bind(':id', current_user_id());
                $isCSKH = (bool)$db->single();
                // Deny access if this staff user is CSKH
                $allowAccess = !$isCSKH;
            } catch (Exception $e) {
                // If the check fails, deny access as a precaution
                $allowAccess = false;
            }
        }
        if (!$allowAccess) {
            flash('error', 'Bạn không có quyền truy cập chức năng này.');
            return redirect('staff/dashboard');
        }
        // Handle search input
        $results   = [];
        $searching = false;
        if (is_post()) {
            verify_csrf();
            $name  = sanitize($_POST['name']  ?? '');
            $phone = sanitize($_POST['phone'] ?? '');
            if ($name !== '' || $phone !== '') {
                $searching = true;
                $db    = new Database();
                $sql   = "SELECT * FROM thongtinkhachhang WHERE 1=1";
                if ($name !== '') {
                    $sql .= " AND HoTen LIKE :name";
                }
                if ($phone !== '') {
                    $sql .= " AND SDT LIKE :phone";
                }
                $db->query($sql);
                if ($name !== '') {
                    $db->bind(':name', '%' . $name . '%');
                }
                if ($phone !== '') {
                    $db->bind(':phone', '%' . $phone . '%');
                }
                try {
                    $results = $db->resultSet() ?? [];
                } catch (Exception $e) {
                    $results = [];
                }
            }
        }
        // If not searching, load all profiles for display
        if (!$searching) {
            try {
                $dbAll = new Database();
                $dbAll->query("SELECT * FROM thongtinkhachhang ORDER BY MaKH ASC");
                $results = $dbAll->resultSet() ?? [];
            } catch (Exception $e) {
                $results = [];
            }
        }
        $this->view('records/doctor_manage', [
            'hide_navbar' => true,
            'title'       => 'Tra cứu hồ sơ',
            'results'     => $results,
            'searching'   => $searching
        ]);
    }

    /**
     * Display profile details for doctors. Doctors can view all personal
     * information and vaccination history but cannot edit the profile. A
     * "Thêm phiếu tiêm" button is provided to add a new injection record.
     *
     * @param string $id The MaKH (profile ID) to display
     */
    public function doctor_view_profile($id) {
        require_role(['staff','admin']);
        $id = sanitize($id);
        // Ensure the viewer is a doctor (non-CSKH staff) or admin
        $allowAccess = true;
        if (current_user_role() === 'staff') {
            try {
                $dbCheck = new Database();
                $dbCheck->query("SELECT 1 FROM nhanviencskh WHERE MaNV = :id LIMIT 1");
                $dbCheck->bind(':id', current_user_id());
                $isCSKH = (bool)$dbCheck->single();
                $allowAccess = !$isCSKH;
            } catch (Exception $e) {
                $allowAccess = false;
            }
        }
        if (!$allowAccess) {
            flash('error', 'Bạn không có quyền xem trang này.');
            return redirect('staff/dashboard');
        }
        $db = new Database();
        // Fetch the profile
        $db->query("SELECT * FROM thongtinkhachhang WHERE MaKH = :kh LIMIT 1");
        $db->bind(':kh', $id);
        $profile = $db->single();
        // Fetch injection history including MaVacXin if present
        $db->query("SELECT * FROM phieutiem WHERE MaKH = :kh ORDER BY NgayTiem DESC, GioTiem DESC");
        $db->bind(':kh', $id);
        $injections = $db->resultSet() ?? [];
        $this->view('records/doctor_view_profile', [
            'hide_navbar' => true,
            'title'       => 'Chi tiết hồ sơ',
            'profile'     => $profile,
            'injections'  => $injections
        ]);
    }

    /**
     * Allow a doctor to add a new injection record (phiếu tiêm) for a given
     * customer profile. The injection date/time are automatically set to
     * the current date/time. The doctor must provide a valid vaccine
     * code (MaVacXin) following the VX### pattern. If the code is
     * invalid or does not exist in the vacxin table, an error is shown.
     * On success, the injection record is inserted into the phieutiem
     * table and the doctor is redirected back to the profile detail.
     *
     * @param string $id The MaKH (profile ID) for which the injection is added
     */
    public function doctor_add_injection($id) {
        require_role(['staff','admin']);
        $id = sanitize($id);
        // Check doctor permission
        $allowAccess = true;
        if (current_user_role() === 'staff') {
            try {
                $dbPerm = new Database();
                $dbPerm->query("SELECT 1 FROM nhanviencskh WHERE MaNV = :id LIMIT 1");
                $dbPerm->bind(':id', current_user_id());
                $isCSKH = (bool)$dbPerm->single();
                $allowAccess = !$isCSKH;
            } catch (Exception $e) {
                $allowAccess = false;
            }
        }
        if (!$allowAccess) {
            flash('error', 'Bạn không có quyền thực hiện chức năng này.');
            return redirect('staff/dashboard');
        }
        // Retrieve profile to ensure it exists
        $db = new Database();
        $db->query("SELECT * FROM thongtinkhachhang WHERE MaKH = :kh LIMIT 1");
        $db->bind(':kh', $id);
        $profile = $db->single();
        if (!$profile) {
            flash('error', 'Không tìm thấy hồ sơ.');
            return redirect('records/doctor_manage');
        }
        // Prepare current date/time for auto-fill
        $today = date('Y-m-d');
        $now   = date('H:i:s');
        if (is_post()) {
            verify_csrf();
            // Sanitize and validate vaccine code
            $vaccine = sanitize($_POST['vaccine'] ?? '');
            // Validation: vaccine code cannot be blank
            if ($vaccine === '') {
                flash('error', 'Vui lòng nhập mã vắc xin.');
                return $this->view('records/doctor_add_injection', [
                    'hide_navbar' => true,
                    'title'       => 'Thêm phiếu tiêm',
                    'profile_id'  => $id,
                    'today'       => $today,
                    'time'        => $now,
                    'vaccine'     => $vaccine
                ]);
            }
            // Validate vaccine code pattern: must start with VX and three digits
            if (!preg_match('/^VX\d{3}$/', $vaccine)) {
                flash('error', 'Mã vắc xin phải có dạng VX001, VX002...');
                return $this->view('records/doctor_add_injection', [
                    'hide_navbar' => true,
                    'title'       => 'Thêm phiếu tiêm',
                    'profile_id'  => $id,
                    'today'       => $today,
                    'time'        => $now,
                    'vaccine'     => $vaccine
                ]);
            }
            // Check existence of vaccine in vacxin table
            $dbCheckVac = new Database();
            $dbCheckVac->query("SELECT 1 FROM vacxin WHERE MaVacXin = :v LIMIT 1");
            $dbCheckVac->bind(':v', $vaccine);
            $exists = $dbCheckVac->single();
            if (!$exists) {
                flash('error', 'Mã vắc xin không tồn tại trong hệ thống.');
                return $this->view('records/doctor_add_injection', [
                    'hide_navbar' => true,
                    'title'       => 'Thêm phiếu tiêm',
                    'profile_id'  => $id,
                    'today'       => $today,
                    'time'        => $now,
                    'vaccine'     => $vaccine
                ]);
            }
            // Generate new MaPhieuTiem (PT###) by incrementing the highest numeric suffix
            $dbGen = new Database();
            $dbGen->query("SELECT MaPhieuTiem FROM phieutiem ORDER BY MaPhieuTiem DESC LIMIT 1");
            $last = $dbGen->single();
            $nextNum = 1;
            if ($last && isset($last['MaPhieuTiem'])) {
                $curr = $last['MaPhieuTiem'];
                // Extract numeric part after PT prefix
                $numPart = intval(substr($curr, 2));
                $nextNum = $numPart + 1;
            }
            $newId = 'PT' . str_pad((string)$nextNum, 3, '0', STR_PAD_LEFT);
            // Insert new injection record into phieutiem. Note: the phieutiem table
            // does not have a MaVacXin column, so only store the basic injection data.
            $dbInsert = new Database();
            $dbInsert->query("INSERT INTO phieutiem (MaPhieuTiem, MaKH, NgayTiem, GioTiem) VALUES (:pt, :kh, :d, :t)");
            $dbInsert->bind(':pt', $newId);
            $dbInsert->bind(':kh', $id);
            $dbInsert->bind(':d', $today);
            $dbInsert->bind(':t', $now);
            try {
                $dbInsert->execute();
                flash('info', 'Thêm phiếu tiêm thành công.');
                return redirect('records/doctor_view_profile/' . urlencode($id));
            } catch (Exception $e) {
                flash('error', 'Không thể thêm phiếu tiêm: ' . $e->getMessage());
                return $this->view('records/doctor_add_injection', [
                    'hide_navbar' => true,
                    'title'       => 'Thêm phiếu tiêm',
                    'profile_id'  => $id,
                    'today'       => $today,
                    'time'        => $now,
                    'vaccine'     => $vaccine
                ]);
            }
        }
        // GET: Show the form with auto-filled date/time
        $this->view('records/doctor_add_injection', [
            'hide_navbar' => true,
            'title'       => 'Thêm phiếu tiêm',
            'profile_id'  => $id,
            'today'       => $today,
            'time'        => $now,
            'vaccine'     => ''
        ]);
    }

}
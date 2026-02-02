<?php
    // app/controllers/BranchesController.php
class BranchesController extends Controller {
    /**
     * Display a list of all branches. Each row includes the branch
     * code, name, address and status. Only administrators may
     * access this page. (UC‑12)
     */
    public function index() {
        require_role(['admin']);
        // If a deletion was canceled, notify admin that the branch was not removed
        if (isset($_GET['cancel'])) {
            flash('info', 'Chi nhánh chưa được xóa.');
        }
        $db = new Database();
        try {
            $db->query("SELECT * FROM chinhanh ORDER BY MaChiNhanh ASC");
            $branches = $db->resultSet();
        } catch (Exception $e) {
            $branches = [];
            flash('error', 'Lỗi truy vấn: ' . $e->getMessage());
        }
        $this->view('branches/index', [
            'branches'    => $branches,
            'hide_navbar' => true,
            'title'       => 'Quản lý chi nhánh'
        ]);
    }

    // Quản lý chi nhánh – chỉ dành cho quản trị viên
    public function add() {
        require_role(['admin']);
        // Handle POST submission
        if (is_post()) {
            verify_csrf();
            $name   = sanitize($_POST['name'] ?? '');
            $address= sanitize($_POST['address'] ?? '');
            $status = sanitize($_POST['status'] ?? 'Hoạt động');
            // Validate required fields
            if ($name === '' || $address === '') {
                flash('error', 'Vui lòng nhập đầy đủ thông tin chi nhánh.');
                return $this->view('branches/add', [
                    'hide_navbar' => true,
                    'title'       => 'Thêm chi nhánh'
                ]);
            }
            if ($status === '') {
                $status = 'Hoạt động';
            }
            $db = new Database();
            // Generate next branch code: CN###
            $db->query("SELECT MaChiNhanh FROM chinhanh ORDER BY MaChiNhanh DESC LIMIT 1");
            $last = $db->single();
            $next = 1;
            if ($last && isset($last['MaChiNhanh'])) {
                $next = intval(substr($last['MaChiNhanh'], 2)) + 1;
            }
            $newCode = 'CN' . str_pad((string)$next, 3, '0', STR_PAD_LEFT);
            try {
                $db->begin();
                $db->query("INSERT INTO chinhanh (MaChiNhanh, TenChiNhanh, DiaChi, TrangThaiHD) VALUES (:code, :name, :addr, :status)");
                $db->bind(':code', $newCode);
                $db->bind(':name', $name);
                $db->bind(':addr', $address);
                $db->bind(':status', $status);
                $db->execute();
                $db->commit();
                flash('info', 'Đã thêm chi nhánh mới.');
                return redirect('branches');
            } catch (Exception $e) {
                $db->rollBack();
                flash('error', 'Không thể thêm chi nhánh: ' . $e->getMessage());
                return $this->view('branches/add', [
                    'hide_navbar' => true,
                    'title'       => 'Thêm chi nhánh'
                ]);
            }
        }
        // GET: display the form
        $this->view('branches/add', [
            'hide_navbar' => true,
            'title'       => 'Thêm chi nhánh'
        ]);
    }

    public function edit() {
        require_role(['admin']);
        $idParam = $_GET['id'] ?? ($_POST['id'] ?? null);
        if (!$idParam) {
            flash('error', 'Thiếu mã chi nhánh.');
            return redirect('branches');
        }
        $db = new Database();
        if (is_post()) {
            verify_csrf();
            $code   = sanitize($_POST['id'] ?? '');
            $name   = sanitize($_POST['name'] ?? '');
            $address= sanitize($_POST['address'] ?? '');
            $status = sanitize($_POST['status'] ?? 'Hoạt động');
            if ($name === '' || $address === '') {
                flash('error', 'Vui lòng nhập đầy đủ thông tin chi nhánh.');
                return redirect('branches/edit&id=' . urlencode($code));
            }
            if ($status === '') {
                $status = 'Hoạt động';
            }
            try {
                $db->query("UPDATE chinhanh SET TenChiNhanh=:name, DiaChi=:addr, TrangThaiHD=:status WHERE MaChiNhanh=:code");
                $db->bind(':name', $name);
                $db->bind(':addr', $address);
                $db->bind(':status', $status);
                $db->bind(':code', $code);
                $db->execute();
                flash('info', 'Cập nhật chi nhánh thành công.');
                return redirect('branches');
            } catch (Exception $e) {
                flash('error', 'Không thể cập nhật chi nhánh: ' . $e->getMessage());
                return redirect('branches/edit&id=' . urlencode($code));
            }
        }
        // GET: fetch branch record
        $db->query("SELECT * FROM chinhanh WHERE MaChiNhanh = :id LIMIT 1");
        $db->bind(':id', $idParam);
        $branch = $db->single();
        if (!$branch) {
            flash('error', 'Chi nhánh không tồn tại.');
            return redirect('branches');
        }
        $this->view('branches/edit', [
            'branch'      => $branch,
            'hide_navbar' => true,
            'title'       => 'Chỉnh sửa chi nhánh'
        ]);
    }

    public function delete() {
        require_role(['admin']);
        $idParam = $_GET['id'] ?? ($_POST['id'] ?? null);
        if (!$idParam) {
            flash('error', 'Thiếu mã chi nhánh.');
            return redirect('branches');
        }
        if (is_post()) {
            verify_csrf();
            if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
                $db = new Database();
                try {
                    // Check related orders and stock
                    $db->query("SELECT COUNT(*) AS cnt FROM donhangonl WHERE MaChiNhanh = :id");
                    $db->bind(':id', $idParam);
                    $cnt1 = $db->single();
                    $db->query("SELECT COUNT(*) AS cnt FROM donhangpos WHERE MaChiNhanh = :id");
                    $db->bind(':id', $idParam);
                    $cnt2 = $db->single();
                    $db->query("SELECT COUNT(*) AS cnt FROM chitiettonkho WHERE MaChiNhanh = :id");
                    $db->bind(':id', $idParam);
                    $cnt3 = $db->single();
                    $total = (int)($cnt1['cnt'] ?? 0) + (int)($cnt2['cnt'] ?? 0) + (int)($cnt3['cnt'] ?? 0);
                    if ($total > 0) {
                        flash('error', 'Không thể xóa chi nhánh do có dữ liệu liên quan.');
                        return redirect('branches');
                    }
                    // Safe to delete
                    $db->query("DELETE FROM chinhanh WHERE MaChiNhanh = :id");
                    $db->bind(':id', $idParam);
                    $db->execute();
                    flash('info', 'Đã xóa chi nhánh thành công.');
                    return redirect('branches');
                } catch (Exception $e) {
                    flash('error', 'Không thể xóa chi nhánh: ' . $e->getMessage());
                    return redirect('branches');
                }
            }
            // Cancel deletion: notify admin
            flash('info', 'Chi nhánh chưa được xóa.');
            return redirect('branches');
        }
        // GET: show confirmation prompt
        $this->view('branches/delete', [
            'code'        => $idParam,
            'hide_navbar' => true,
            'title'       => 'Xóa chi nhánh'
        ]);
    }

}
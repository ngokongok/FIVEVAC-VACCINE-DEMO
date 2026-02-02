<?php
/**
 * Controller for managing inventory detail records (chitiettonkho).
 * Administrators can view, add, update and delete stock details. Each
 * record ties a branch and a vaccine to quantities in stock and
 * quantities already used. Inventory details are crucial for
 * controlling vaccine availability across branches and ensuring
 * accurate stock levels. The controller follows patterns used in
 * other management controllers (e.g. AccountsController, InventoryController).
 */
class StockController extends Controller {
    /**
     * Display a list of inventory detail records. Each row shows the
     * record code, branch name, vaccine name, current quantity and
     * used quantity. Administrators see a button to add a new record.
     */
    public function index() {
        require_role(['admin']);
        // Notify if deletion of a stock record was canceled
        if (isset($_GET['cancel'])) {
            flash('info', 'Chi tiết tồn kho chưa được xóa.');
        }
        $db = new Database();
        try {
            // Join with branch and vaccine tables to get human‑readable names.
            $db->query("SELECT ct.MaCTTK, ct.MaChiNhanh, cn.TenChiNhanh,
                               ct.MaVacXin, v.TenVacXin,
                               ct.SoLuongHienTai, ct.SoLuongDaSuDung
                        FROM chitiettonkho ct
                        JOIN chinhanh cn ON ct.MaChiNhanh = cn.MaChiNhanh
                        JOIN vacxin v ON ct.MaVacXin = v.MaVacXin
                        ORDER BY ct.MaCTTK ASC");
            $items = $db->resultSet();
        } catch (Exception $e) {
            $items = [];
            flash('error', 'Lỗi truy vấn: ' . $e->getMessage());
        }
        $this->view('stock/index', [
            'items'       => $items,
            'hide_navbar' => true,
            'title'       => 'Quản lý tồn kho'
        ]);
    }

    /**
     * Add a new inventory detail record. Administrators select a branch
     * and a vaccine and specify the initial quantity on hand. The
     * combination of branch and vaccine must be unique. Used quantity
     * defaults to zero. Validation ensures all fields are provided
     * and numeric values are non‑negative. (UC 13.1)
     */
    public function add() {
        require_role(['admin']);
        $db = new Database();
        // Fetch branches and vaccines for dropdowns
        $db->query("SELECT MaChiNhanh, TenChiNhanh FROM chinhanh ORDER BY MaChiNhanh ASC");
        $branches = $db->resultSet();
        $db->query("SELECT MaVacXin, TenVacXin FROM vacxin ORDER BY MaVacXin ASC");
        $vaccines = $db->resultSet();
        if (is_post()) {
            verify_csrf();
            $branch = sanitize($_POST['branch'] ?? '');
            $vaccine = sanitize($_POST['vaccine'] ?? '');
            $quantity = sanitize($_POST['quantity'] ?? '');
            // Validate required fields
            if ($branch === '' || $vaccine === '' || $quantity === '') {
                flash('error', 'Vui lòng nhập đầy đủ thông tin.');
                return $this->view('stock/add', [
                    'branches'    => $branches,
                    'vaccines'    => $vaccines,
                    'hide_navbar' => true,
                    'title'       => 'Thêm chi tiết tồn kho'
                ]);
            }
            // Validate numeric quantity
            if (!is_numeric($quantity) || (int)$quantity < 0) {
                flash('error', 'Số lượng hiện tại phải là số không âm.');
                return $this->view('stock/add', [
                    'branches'    => $branches,
                    'vaccines'    => $vaccines,
                    'hide_navbar' => true,
                    'title'       => 'Thêm chi tiết tồn kho'
                ]);
            }
            // Check unique combination of branch and vaccine
            $db->query("SELECT MaCTTK FROM chitiettonkho WHERE MaChiNhanh = :b AND MaVacXin = :v LIMIT 1");
            $db->bind(':b', $branch);
            $db->bind(':v', $vaccine);
            $existing = $db->single();
            if ($existing) {
                flash('error', 'Bản ghi tồn kho cho chi nhánh và vắc xin này đã tồn tại.');
                return $this->view('stock/add', [
                    'branches'    => $branches,
                    'vaccines'    => $vaccines,
                    'hide_navbar' => true,
                    'title'       => 'Thêm chi tiết tồn kho'
                ]);
            }
            // Generate next MaCTTK code (CTTK###)
            $db->query("SELECT MaCTTK FROM chitiettonkho ORDER BY MaCTTK DESC LIMIT 1");
            $last = $db->single();
            $next = 1;
            if ($last && isset($last['MaCTTK'])) {
                $numeric = intval(substr($last['MaCTTK'], 4));
                $next = $numeric + 1;
            }
            $newCode = 'CTTK' . str_pad((string)$next, 3, '0', STR_PAD_LEFT);
            try {
                $db->begin();
                $db->query("INSERT INTO chitiettonkho (MaCTTK, MaChiNhanh, MaVacXin, SoLuongHienTai, SoLuongDaSuDung)
                            VALUES (:code, :branch, :vaccine, :qty, 0)");
                $db->bind(':code', $newCode);
                $db->bind(':branch', $branch);
                $db->bind(':vaccine', $vaccine);
                $db->bind(':qty', (int)$quantity);
                $db->execute();
                $db->commit();
                flash('info', 'Đã thêm chi tiết tồn kho mới.');
                return redirect('stock');
            } catch (Exception $e) {
                $db->rollBack();
                flash('error', 'Không thể thêm: ' . $e->getMessage());
                return $this->view('stock/add', [
                    'branches'    => $branches,
                    'vaccines'    => $vaccines,
                    'hide_navbar' => true,
                    'title'       => 'Thêm chi tiết tồn kho'
                ]);
            }
        }
        // GET: display the add form
        $this->view('stock/add', [
            'branches'    => $branches,
            'vaccines'    => $vaccines,
            'hide_navbar' => true,
            'title'       => 'Thêm chi tiết tồn kho'
        ]);
    }

    /**
     * Edit an existing inventory detail record. Administrators can
     * increase or decrease the current quantity by specifying
     * quantities entered and exported. The used quantity is read‑only
     * and displayed for reference. Branch and vaccine are fixed and
     * cannot be changed. (UC 13.2)
     */
    public function edit() {
        require_role(['admin']);
        $idParam = $_GET['id'] ?? ($_POST['id'] ?? null);
        if (!$idParam) {
            flash('error', 'Thiếu mã chi tiết tồn kho.');
            return redirect('stock');
        }
        $db = new Database();
        if (is_post()) {
            verify_csrf();
            $code = sanitize($_POST['id'] ?? '');
            $in  = sanitize($_POST['nhap'] ?? '0');
            $out = sanitize($_POST['xuat'] ?? '0');
            // Validate numeric inputs
            if (!is_numeric($in) || (int)$in < 0 || !is_numeric($out) || (int)$out < 0) {
                flash('error', 'Giá trị nhập vào/xuất ra phải là số không âm.');
                return redirect('stock/edit&id=' . urlencode($code));
            }
            // Fetch current record for computing new quantity
            $db->query("SELECT SoLuongHienTai, SoLuongDaSuDung FROM chitiettonkho WHERE MaCTTK = :code LIMIT 1");
            $db->bind(':code', $code);
            $record = $db->single();
            if (!$record) {
                flash('error', 'Chi tiết tồn kho không tồn tại.');
                return redirect('stock');
            }
            $current = (int)($record['SoLuongHienTai'] ?? 0);
            $used    = (int)($record['SoLuongDaSuDung'] ?? 0);
            $inVal   = (int)$in;
            $outVal  = (int)$out;
            $newQty  = $current + $inVal - $outVal;
            if ($newQty < $used) {
                flash('error', 'Số lượng hiện tại không đủ, không thể xuất ra nhiều hơn số lượng khả dụng.');
                return redirect('stock/edit&id=' . urlencode($code));
            }
            try {
                $db->query("UPDATE chitiettonkho SET SoLuongHienTai = :qty WHERE MaCTTK = :code");
                $db->bind(':qty', $newQty);
                $db->bind(':code', $code);
                $db->execute();
                flash('info', 'Đã cập nhật số lượng thành công.');
                return redirect('stock');
            } catch (Exception $e) {
                flash('error', 'Không thể cập nhật: ' . $e->getMessage());
                return redirect('stock/edit&id=' . urlencode($code));
            }
        }
        // GET: load record and show edit form
        $db->query("SELECT ct.MaCTTK, ct.MaChiNhanh, cn.TenChiNhanh,
                           ct.MaVacXin, v.TenVacXin,
                           ct.SoLuongHienTai, ct.SoLuongDaSuDung
                    FROM chitiettonkho ct
                    JOIN chinhanh cn ON ct.MaChiNhanh = cn.MaChiNhanh
                    JOIN vacxin v ON ct.MaVacXin = v.MaVacXin
                    WHERE ct.MaCTTK = :id LIMIT 1");
        $db->bind(':id', $idParam);
        $item = $db->single();
        if (!$item) {
            flash('error', 'Chi tiết tồn kho không tồn tại.');
            return redirect('stock');
        }
        $this->view('stock/edit', [
            'item'        => $item,
            'hide_navbar' => true,
            'title'       => 'Chỉnh sửa chi tiết tồn kho'
        ]);
    }

    /**
     * Delete an inventory detail record. Deletion is only allowed
     * if the record has no remaining quantity and no used quantity.
     * Administrators are prompted to confirm deletion. (UC 13.3)
     */
    public function delete() {
        require_role(['admin']);
        $idParam = $_GET['id'] ?? ($_POST['id'] ?? null);
        if (!$idParam) {
            flash('error', 'Thiếu mã chi tiết tồn kho.');
            return redirect('stock');
        }
        if (is_post()) {
            verify_csrf();
            if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
                $db = new Database();
                // Load record to check conditions
                $db->query("SELECT SoLuongHienTai, SoLuongDaSuDung FROM chitiettonkho WHERE MaCTTK = :id LIMIT 1");
                $db->bind(':id', $idParam);
                $rec = $db->single();
                if (!$rec) {
                    flash('error', 'Chi tiết tồn kho không tồn tại.');
                    return redirect('stock');
                }
                $current = (int)($rec['SoLuongHienTai'] ?? 0);
                $used    = (int)($rec['SoLuongDaSuDung'] ?? 0);
                if ($current > 0 || $used > 0) {
                    flash('error', 'Không thể xóa do vẫn còn số lượng tồn hoặc đã sử dụng.');
                    return redirect('stock');
                }
                try {
                    $db->query("DELETE FROM chitiettonkho WHERE MaCTTK = :id");
                    $db->bind(':id', $idParam);
                    $db->execute();
                    flash('info', 'Đã xóa chi tiết tồn kho.');
                    return redirect('stock');
                } catch (Exception $e) {
                    flash('error', 'Không thể xóa: ' . $e->getMessage());
                    return redirect('stock');
                }
            }
            // Cancel deletion: notify admin
            flash('info', 'Chi tiết tồn kho chưa được xóa.');
            return redirect('stock');
        }
        // GET: show confirm page
        // Fetch record to display names for confirmation
        $db = new Database();
        $db->query("SELECT ct.MaCTTK, ct.MaChiNhanh, cn.TenChiNhanh,
                           ct.MaVacXin, v.TenVacXin
                    FROM chitiettonkho ct
                    JOIN chinhanh cn ON ct.MaChiNhanh = cn.MaChiNhanh
                    JOIN vacxin v ON ct.MaVacXin = v.MaVacXin
                    WHERE ct.MaCTTK = :id LIMIT 1");
        $db->bind(':id', $idParam);
        $item = $db->single();
        if (!$item) {
            flash('error', 'Chi tiết tồn kho không tồn tại.');
            return redirect('stock');
        }
        $this->view('stock/delete', [
            'item'        => $item,
            'hide_navbar' => true,
            'title'       => 'Xóa chi tiết tồn kho'
        ]);
    }
}
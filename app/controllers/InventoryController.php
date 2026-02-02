<?php
class InventoryController extends Controller {
    /**
     * List all vaccines in the system for administrators. Displays
     * each vaccine's code, name, manufacturing date, expiry date,
     * price and description. A button at the top allows creation of
     * new vaccines. (UC‑11.1)
     */
    public function index() {
        require_role(['admin']);
        // Show notification if deletion of a vaccine was canceled
        if (isset($_GET['cancel'])) {
            flash('info', 'Vắc xin chưa được xóa.');
        }
        $db = new Database();
        try {
            $db->query("SELECT * FROM vacxin ORDER BY MaVacXin ASC");
            $vaccines = $db->resultSet();
        } catch (Exception $e) {
            $vaccines = [];
            flash('error', 'Lỗi truy vấn: ' . $e->getMessage());
        }
        $this->view('inventory/index', [
            'vaccines'    => $vaccines,
            'hide_navbar' => true,
            'title'       => 'Quản lý vắc xin'
        ]);
    }
    public function add_vaccine(){
        require_role(['admin']);
        // Handle form submission
        if (is_post()) {
            verify_csrf();
            $name    = sanitize($_POST['name'] ?? '');
            $nsx     = sanitize($_POST['nsx'] ?? '');
            $hsd     = sanitize($_POST['hsd'] ?? '');
            $price   = sanitize($_POST['price'] ?? '');
            $desc    = sanitize($_POST['desc'] ?? '');
            // Validate required fields
            if ($name === '' || $nsx === '' || $hsd === '' || $price === '') {
                flash('error', 'Vui lòng nhập đầy đủ thông tin bắt buộc.');
                return $this->view('inventory/add_vaccine', [
                    'hide_navbar' => true,
                    'title'       => 'Thêm vắc xin'
                ]);
            }
            // Validate numeric price (>0)
            if (!is_numeric($price) || (float)$price <= 0) {
                flash('error', 'Giá phải là một số dương.');
                return $this->view('inventory/add_vaccine', [
                    'hide_navbar' => true,
                    'title'       => 'Thêm vắc xin'
                ]);
            }
            // Validate dates; ensure HSD >= NSX
            $tsNsx = strtotime($nsx);
            $tsHsd = strtotime($hsd);
            if ($tsNsx === false || $tsHsd === false) {
                flash('error', 'Định dạng ngày không hợp lệ.');
                return $this->view('inventory/add_vaccine', [
                    'hide_navbar' => true,
                    'title'       => 'Thêm vắc xin'
                ]);
            }
            if ($tsHsd < $tsNsx) {
                flash('error', 'Hạn sử dụng phải lớn hơn hoặc bằng ngày sản xuất.');
                return $this->view('inventory/add_vaccine', [
                    'hide_navbar' => true,
                    'title'       => 'Thêm vắc xin'
                ]);
            }
            $db = new Database();
            // Generate new MaVacXin (VX###)
            $db->query("SELECT MaVacXin FROM vacxin ORDER BY MaVacXin DESC LIMIT 1");
            $last = $db->single();
            $next = 1;
            if ($last && isset($last['MaVacXin'])) {
                $next = intval(substr($last['MaVacXin'], 2)) + 1;
            }
            $newCode = 'VX' . str_pad((string)$next, 3, '0', STR_PAD_LEFT);
            try {
                $db->begin();
                $db->query("INSERT INTO vacxin (MaVacXin, TenVacXin, NSX, HSD, Gia, Mota) VALUES (:code, :name, :nsx, :hsd, :price, :desc)");
                $db->bind(':code', $newCode);
                $db->bind(':name', $name);
                $db->bind(':nsx', $nsx);
                $db->bind(':hsd', $hsd);
                $db->bind(':price', (float)$price);
                $db->bind(':desc', $desc);
                $db->execute();
                $db->commit();
                flash('info', 'Đã thêm vắc xin mới.');
                return redirect('inventory');
            } catch (Exception $e) {
                $db->rollBack();
                flash('error', 'Không thể thêm vắc xin: ' . $e->getMessage());
                return $this->view('inventory/add_vaccine', [
                    'hide_navbar' => true,
                    'title'       => 'Thêm vắc xin'
                ]);
            }
        }
        // GET: show the form
        $this->view('inventory/add_vaccine', [
            'hide_navbar' => true,
            'title'       => 'Thêm vắc xin'
        ]);
    }
    public function edit_vaccine(){
        require_role(['admin']);
        // Accept ID via GET or POST
        $idParam = $_GET['id'] ?? ($_POST['id'] ?? null);
        if (!$idParam) {
            flash('error', 'Thiếu mã vắc xin.');
            return redirect('inventory');
        }
        if (is_post()) {
            verify_csrf();
            $code   = sanitize($_POST['id'] ?? '');
            $name   = sanitize($_POST['name'] ?? '');
            $nsx    = sanitize($_POST['nsx'] ?? '');
            $hsd    = sanitize($_POST['hsd'] ?? '');
            $price  = sanitize($_POST['price'] ?? '');
            $desc   = sanitize($_POST['desc'] ?? '');
            // Validate required fields
            if ($name === '' || $nsx === '' || $hsd === '' || $price === '') {
                flash('error', 'Vui lòng nhập đầy đủ thông tin bắt buộc.');
                return redirect('inventory/edit_vaccine&id=' . urlencode($code));
            }
            if (!is_numeric($price) || (float)$price <= 0) {
                flash('error', 'Giá phải là một số dương.');
                return redirect('inventory/edit_vaccine&id=' . urlencode($code));
            }
            $tsNsx = strtotime($nsx);
            $tsHsd = strtotime($hsd);
            if ($tsNsx === false || $tsHsd === false || $tsHsd < $tsNsx) {
                flash('error', 'Ngày sản xuất/hạn sử dụng không hợp lệ.');
                return redirect('inventory/edit_vaccine&id=' . urlencode($code));
            }
            $db = new Database();
            try {
                $db->query("UPDATE vacxin SET TenVacXin=:name, NSX=:nsx, HSD=:hsd, Gia=:price, Mota=:desc WHERE MaVacXin=:code");
                $db->bind(':name', $name);
                $db->bind(':nsx', $nsx);
                $db->bind(':hsd', $hsd);
                $db->bind(':price', (float)$price);
                $db->bind(':desc', $desc);
                $db->bind(':code', $code);
                $db->execute();
                flash('info', 'Cập nhật vắc xin thành công.');
                return redirect('inventory');
            } catch (Exception $e) {
                flash('error', 'Không thể cập nhật: ' . $e->getMessage());
                return redirect('inventory/edit_vaccine&id=' . urlencode($code));
            }
        }
        // GET: load vaccine record
        $db = new Database();
        $db->query("SELECT * FROM vacxin WHERE MaVacXin = :id LIMIT 1");
        $db->bind(':id', $idParam);
        $vaccine = $db->single();
        if (!$vaccine) {
            flash('error', 'Vắc xin không tồn tại.');
            return redirect('inventory');
        }
        $this->view('inventory/edit_vaccine', [
            'vaccine'     => $vaccine,
            'hide_navbar' => true,
            'title'       => 'Chỉnh sửa vắc xin'
        ]);
    }
    public function delete_vaccine(){
        require_role(['admin']);
        $idParam = $_GET['id'] ?? ($_POST['id'] ?? null);
        if (!$idParam) {
            flash('error', 'Thiếu mã vắc xin.');
            return redirect('inventory');
        }
        if (is_post()) {
            verify_csrf();
            if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
                $db = new Database();
                try {
                    $db->begin();
                    $db->query("DELETE FROM vacxin WHERE MaVacXin = :code");
                    $db->bind(':code', $idParam);
                    $db->execute();
                    $db->commit();
                    flash('info', 'Đã xóa vắc xin khỏi hệ thống.');
                    return redirect('inventory');
                } catch (Exception $e) {
                    $db->rollBack();
                    flash('error', 'Không thể xóa vắc xin: ' . $e->getMessage());
                    return redirect('inventory');
                }
            }
            // Cancel deletion: notify admin
            flash('info', 'Vắc xin chưa được xóa.');
            return redirect('inventory');
        }
        // GET: show confirmation prompt
        $this->view('inventory/delete_vaccine', [
            'code'        => $idParam,
            'hide_navbar' => true,
            'title'       => 'Xóa vắc xin'
        ]);
    }
    public function update_stock(){
        require_role(['staff','admin']);
        if (is_post()){
            verify_csrf();
            $branch = sanitize($_POST['branch'] ?? '');
            $code = sanitize($_POST['code'] ?? '');
            $qty = intval($_POST['qty'] ?? 0);
            if (!validate_nonempty($branch) || !validate_nonempty($code) || $qty==0){
                flash('error','Thiếu thông tin'); return $this->view('inventory/update_stock',[]);
            }
            $db = new Database();
            try {
                $db->query("UPDATE chitiettonkho SET SoLuongHienTai = SoLuongHienTai + :q WHERE MaChiNhanh=:b AND MaVacXin=:v");
                $db->bind(':q',$qty); $db->bind(':b',$branch); $db->bind(':v',$code);
                $db->execute();
                flash('success','Đã cập nhật SoLuongHienTai.');
            } catch(Exception $e){
                flash('error','Lỗi tồn kho: '.$e->getMessage());
            }
        }
        $this->view('inventory/update_stock', [
            'hide_navbar' => true,
            'title'       => 'Cập nhật tồn kho'
        ]);
    }
}
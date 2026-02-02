<?php
class TransactionsController extends Controller {
    public function search_pos(){
        require_role(['staff','admin']);
        $this->view('transactions/search_pos', [
            'hide_navbar' => true,
            'title'       => 'Tra cứu hóa đơn POS'
        ]);
    }
    public function create_pos() {
        // Only allow staff or admin to create POS orders. Further restrict to
        // customer service staff (nhanviencskh) when the role is 'staff'.
        require_role(['staff','admin']);
        // Determine if current staff user belongs to the CSKH group.
        $isCSKH = false;
        if (current_user_role() === 'staff') {
            try {
                $dbRole = new Database();
                $dbRole->query("SELECT 1 FROM nhanviencskh WHERE MaNV = :id LIMIT 1");
                $dbRole->bind(':id', current_user_id());
                $isCSKH = (bool)$dbRole->single();
            } catch (Exception $e) {
                $isCSKH = false;
            }
            // If not CSKH, redirect back to staff dashboard
            if (!$isCSKH) {
                flash('error', 'Bạn không có quyền tạo đơn hàng POS.');
                return redirect('staff/dashboard');
            }
        }
        // Fetch lists of customers, vaccines and branches for the form
        $customers = [];
        $vaccines  = [];
        $branches  = [];
        try {
            $db = new Database();
            $db->query("SELECT MaKH, HoTen, SDT FROM thongtinkhachhang ORDER BY HoTen");
            $customers = $db->resultSet();
            $db->query("SELECT MaVacXin, TenVacXin, Gia FROM vacxin ORDER BY TenVacXin");
            $vaccines = $db->resultSet();
            $db->query("SELECT MaChiNhanh, TenChiNhanh FROM chinhanh WHERE TrangThaiHD = 'Hoạt động' ORDER BY MaChiNhanh");
            $branches = $db->resultSet();
        } catch (Exception $e) {
            $customers = [];
            $vaccines  = [];
            $branches  = [];
        }
        // Initialise selected values for form repopulation
        $customerId = '';
        $vaccineId  = '';
        $branchId   = '';
        $payment    = '';
        if (is_post()) {
            verify_csrf();
            // Retrieve form data
            $customerId = sanitize($_POST['customer'] ?? '');
            $vaccineId  = sanitize($_POST['vaccine']  ?? '');
            $branchId   = sanitize($_POST['branch']   ?? '');
            $payment    = sanitize($_POST['payment']  ?? '');
            // Validate required fields
            if (!validate_nonempty($customerId) || !validate_nonempty($vaccineId) || !validate_nonempty($branchId) || !validate_nonempty($payment)) {
                flash('error', 'Vui lòng điền đầy đủ thông tin.');
                return $this->view('transactions/create_pos', [
                    'hide_navbar' => true,
                    'title'       => 'Tạo đơn hàng POS',
                    'customers'   => $customers,
                    'vaccines'    => $vaccines,
                    'branches'    => $branches,
                    'selected_customer' => $customerId,
                    'selected_vaccine'  => $vaccineId,
                    'selected_branch'   => $branchId,
                    'selected_payment'  => $payment
                ]);
            }
            $db = new Database();
            try {
                $db->begin();
                // Verify customer exists
                $db->query("SELECT HoTen, SDT FROM thongtinkhachhang WHERE MaKH = :kh LIMIT 1");
                $db->bind(':kh', $customerId);
                $customerRow = $db->single();
                if (!$customerRow) {
                    throw new Exception('Khách hàng không tồn tại');
                }
                // Verify vaccine exists and get price
                $db->query("SELECT Gia FROM vacxin WHERE MaVacXin = :vx LIMIT 1");
                $db->bind(':vx', $vaccineId);
                $vaccineRow = $db->single();
                if (!$vaccineRow) {
                    throw new Exception('Vắc xin không tồn tại');
                }
                $price = (float)$vaccineRow['Gia'];
                // Verify branch exists
                $db->query("SELECT 1 FROM chinhanh WHERE MaChiNhanh = :b LIMIT 1");
                $db->bind(':b', $branchId);
                if (!$db->single()) {
                    throw new Exception('Chi nhánh không tồn tại');
                }
                // Check stock availability
                $db->query("SELECT SoLuongHienTai, SoLuongDaSuDung FROM chitiettonkho WHERE MaChiNhanh = :b AND MaVacXin = :vx LIMIT 1");
                $db->bind(':b', $branchId);
                $db->bind(':vx', $vaccineId);
                $stock = $db->single();
                $available = ($stock ? intval($stock['SoLuongHienTai']) - intval($stock['SoLuongDaSuDung']) : 0);
                if ($available < 1) {
                    throw new Exception('Vắc xin đã hết hàng tại chi nhánh này');
                }
                // Generate new MaDHpos (e.g. POS001)
                $db->query("SELECT MaDHpos FROM donhangpos ORDER BY MaDHpos DESC LIMIT 1");
                $lastIdRow = $db->single();
                $nextNumber = 1;
                if ($lastIdRow && isset($lastIdRow['MaDHpos'])) {
                    $lastId = $lastIdRow['MaDHpos'];
                    // Extract numeric part by stripping non-digits
                    $numPart = intval(substr($lastId, 3));
                    $nextNumber = $numPart + 1;
                }
                $newId = 'POS' . str_pad((string)$nextNumber, 3, '0', STR_PAD_LEFT);
                // Insert order
                $db->query("INSERT INTO donhangpos (MaDHpos, MaKH, MaChiNhanh, MaVacXin, NgayTao, ThanhTien, HinhThucThanhToan, TrangThaiDH) VALUES (:id, :kh, :b, :vx, NOW(), :price, :pay, 'Thành công')");
                $db->bind(':id', $newId);
                $db->bind(':kh', $customerId);
                $db->bind(':b', $branchId);
                $db->bind(':vx', $vaccineId);
                $db->bind(':price', $price);
                $db->bind(':pay', $payment);
                $db->execute();
                // Update stock usage
                $db->query("UPDATE chitiettonkho SET SoLuongDaSuDung = SoLuongDaSuDung + 1 WHERE MaChiNhanh = :b AND MaVacXin = :vx");
                $db->bind(':b', $branchId);
                $db->bind(':vx', $vaccineId);
                $db->execute();
                $db->commit();
                flash('success', 'Đã tạo đơn hàng POS thành công. Mã đơn hàng: ' . $newId);
                return redirect('transactions/create_pos');
            } catch (Exception $e) {
                $db->rollBack();
                flash('error', 'Lỗi tạo đơn hàng POS: ' . $e->getMessage());
                // Fall through to display the form again with lists
            }
        }
        $this->view('transactions/create_pos', [
            'hide_navbar' => true,
            'title'       => 'Tạo đơn hàng POS',
            'customers'   => $customers,
            'vaccines'    => $vaccines,
            'branches'    => $branches,
            'selected_customer' => $customerId ?? '',
            'selected_vaccine'  => $vaccineId  ?? '',
            'selected_branch'   => $branchId   ?? '',
            'selected_payment'  => $payment    ?? ''
        ]);
    }

    /**
     * Unified search and listing for both online and POS orders.
     * Staff can search by customer name, phone number and/or order code.
     * When no search criteria is provided, the most recent orders are shown
     * (combining donhangonl and donhangpos) sorted by date descending.
     *
     * URL: transactions/search_orders
     */
    public function search_orders() {
        require_role(['staff','admin']);
        // Restrict this page to customer service staff (nhanviencskh) and admins.
        if (current_user_role() === 'staff') {
            $isCSKH = false;
            try {
                $dbRole = new Database();
                $dbRole->query("SELECT 1 FROM nhanviencskh WHERE MaNV = :id LIMIT 1");
                $dbRole->bind(':id', current_user_id());
                $isCSKH = (bool)$dbRole->single();
            } catch (Exception $e) {
                $isCSKH = false;
            }
            if (!$isCSKH) {
                flash('error', 'Bạn không có quyền truy cập chức năng này.');
                return redirect('staff/dashboard');
            }
        }
        $name  = '';
        $phone = '';
        $code  = '';
        $orders = [];
        $searching = false;
        // If the request is POST, capture search parameters
        if (is_post()) {
            verify_csrf();
            $name  = sanitize($_POST['name'] ?? '');
            $phone = sanitize($_POST['phone'] ?? '');
            $code  = sanitize($_POST['code'] ?? '');
            $searching = true;
        }
        try {
            $db = new Database();
            // Prepare ONL orders query with dynamic conditions
            $onlSql = "SELECT d.MaDHonl AS MaDH, 'Online' AS Loai, u.HoVaTen AS KhachHang, tk.SDT AS SDT, c.TenChiNhanh, v.TenVacXin, d.NgayTao, d.ThanhTien, d.HinhThucThanhToan, d.TrangThaiDH
                       FROM donhangonl d
                       LEFT JOIN nguoidung u ON d.MaTV = u.MaND
                       LEFT JOIN taikhoan tk ON u.MaND = tk.MaND
                       LEFT JOIN chinhanh c ON d.MaChiNhanh = c.MaChiNhanh
                       LEFT JOIN vacxin v ON d.MaVacXin = v.MaVacXin
                       WHERE 1 = 1";
            // Build conditions for online orders
            $onlParams = [];
            if (validate_nonempty($name)) {
                $onlSql .= " AND u.HoVaTen LIKE :onl_name";
                $onlParams[':onl_name'] = '%' . $name . '%';
            }
            if (validate_nonempty($phone)) {
                $onlSql .= " AND tk.SDT LIKE :onl_phone";
                $onlParams[':onl_phone'] = '%' . $phone . '%';
            }
            if (validate_nonempty($code)) {
                $onlSql .= " AND d.MaDHonl LIKE :onl_code";
                $onlParams[':onl_code'] = '%' . $code . '%';
            }
            $db->query($onlSql);
            foreach ($onlParams as $k => $v) {
                $db->bind($k, $v);
            }
            $ordersOnl = $db->resultSet();

            // Prepare POS orders query with dynamic conditions
            $posSql = "SELECT dp.MaDHpos AS MaDH, 'POS' AS Loai, kh.HoTen AS KhachHang, kh.SDT AS SDT, c.TenChiNhanh, v.TenVacXin, dp.NgayTao, dp.ThanhTien, dp.HinhThucThanhToan, dp.TrangThaiDH
                       FROM donhangpos dp
                       LEFT JOIN thongtinkhachhang kh ON dp.MaKH = kh.MaKH
                       LEFT JOIN chinhanh c ON dp.MaChiNhanh = c.MaChiNhanh
                       LEFT JOIN vacxin v ON dp.MaVacXin = v.MaVacXin
                       WHERE 1 = 1";
            $posParams = [];
            if (validate_nonempty($name)) {
                $posSql .= " AND kh.HoTen LIKE :pos_name";
                $posParams[':pos_name'] = '%' . $name . '%';
            }
            if (validate_nonempty($phone)) {
                $posSql .= " AND kh.SDT LIKE :pos_phone";
                $posParams[':pos_phone'] = '%' . $phone . '%';
            }
            if (validate_nonempty($code)) {
                $posSql .= " AND dp.MaDHpos LIKE :pos_code";
                $posParams[':pos_code'] = '%' . $code . '%';
            }
            $db->query($posSql);
            foreach ($posParams as $k => $v) {
                $db->bind($k, $v);
            }
            $ordersPos = $db->resultSet();

            // Merge the two result sets
            $orders = array_merge($ordersOnl, $ordersPos);
            // Sort by date descending (most recent first)
            usort($orders, function ($a, $b) {
                return strcmp($b['NgayTao'], $a['NgayTao']);
            });
        } catch (Exception $e) {
            $orders = [];
        }
        // Build lists of all distinct customer names and phone numbers for the search drop‑downs
        $customerNames = [];
        $customerPhones = [];
        try {
            $dbList = new Database();
            // Fetch unique names from both customer tables
            $dbList->query("SELECT HoVaTen AS name FROM nguoidung UNION SELECT HoTen FROM thongtinkhachhang ORDER BY name");
            $customerNames = $dbList->resultSet();
            // Fetch unique phone numbers
            $dbList->query("SELECT SDT FROM taikhoan UNION SELECT SDT FROM thongtinkhachhang ORDER BY SDT");
            $customerPhones = $dbList->resultSet();
        } catch (Exception $e) {
            $customerNames = [];
            $customerPhones = [];
        }
        $this->view('transactions/search_orders', [
            'hide_navbar'    => true,
            'title'          => 'Quản lý đơn hàng',
            'orders'         => $orders,
            'searching'      => $searching,
            'name'           => $name,
            'phone'          => $phone,
            'code'           => $code,
            'customerNames'  => $customerNames,
            'customerPhones' => $customerPhones
        ]);
    }

    /**
     * Display details of a specific order (online or POS). The order ID
     * parameter can correspond to either the MaDHonl or MaDHpos. The type
     * is inferred by attempting to look up the ID in each table. If found
     * in donhangonl, the order is treated as an online order; if found
     * in donhangpos, it is treated as a POS order. When the order cannot
     * be found, a simple message is shown.
     *
     * @param string $id Order identifier (e.g. ONL001 or POS001)
     */
    public function order_detail($id = '') {
        require_role(['staff','admin']);
        $orderId = sanitize($id);
        $detail = null;
        $type   = '';
        try {
            $db = new Database();
            // Attempt to fetch online order details
            $db->query(
                "SELECT d.MaDHonl AS MaDH, 'Online' AS Loai, u.HoVaTen AS KhachHang, tk.SDT AS SDT, c.TenChiNhanh, c.DiaChi AS DiaChiChiNhanh, v.TenVacXin, v.Gia AS DonGia, d.NgayTao, d.ThanhTien, d.HinhThucThanhToan, d.TrangThaiDH
                 FROM donhangonl d
                 LEFT JOIN nguoidung u ON d.MaTV = u.MaND
                 LEFT JOIN taikhoan tk ON u.MaND = tk.MaND
                 LEFT JOIN chinhanh c ON d.MaChiNhanh = c.MaChiNhanh
                 LEFT JOIN vacxin v ON d.MaVacXin = v.MaVacXin
                 WHERE d.MaDHonl = :id LIMIT 1"
            );
            $db->bind(':id', $orderId);
            $row = $db->single();
            if ($row) {
                $detail = $row;
                $type   = 'onl';
            } else {
                // Attempt to fetch POS order details
                $db->query(
                    "SELECT dp.MaDHpos AS MaDH, 'POS' AS Loai, kh.HoTen AS KhachHang, kh.SDT AS SDT, c.TenChiNhanh, c.DiaChi AS DiaChiChiNhanh, v.TenVacXin, v.Gia AS DonGia, dp.NgayTao, dp.ThanhTien, dp.HinhThucThanhToan, dp.TrangThaiDH
                     FROM donhangpos dp
                     LEFT JOIN thongtinkhachhang kh ON dp.MaKH = kh.MaKH
                     LEFT JOIN chinhanh c ON dp.MaChiNhanh = c.MaChiNhanh
                     LEFT JOIN vacxin v ON dp.MaVacXin = v.MaVacXin
                     WHERE dp.MaDHpos = :id LIMIT 1"
                );
                $db->bind(':id', $orderId);
                $row2 = $db->single();
                if ($row2) {
                    $detail = $row2;
                    $type   = 'pos';
                }
            }
        } catch (Exception $e) {
            $detail = null;
        }
        $this->view('transactions/order_detail', [
            'hide_navbar' => true,
            'title'       => 'Chi tiết đơn hàng',
            'detail'      => $detail,
            'orderId'     => $orderId,
            'type'        => $type
        ]);
    }
}
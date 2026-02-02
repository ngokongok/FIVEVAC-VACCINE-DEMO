<?php
class OrdersController extends Controller {
    public function index(){
        // Chỉ cho phép khách hàng tạo đơn online
        require_role(['customer']);
        $this->view('orders/create_onl',[]);
    }

    public function create_onl(){
        require_role(['customer']);
        // Capture vaccine from query string to prefill the form. Use sanitize to
        // avoid XSS and injection. If provided, fetch the full vaccine record
        // for display (name, description, price, etc.). Additionally, detect
        // whether the user is editing a previously created pending order.
        $pendingOrder = $_SESSION['pending_order'] ?? null;
        $editId        = sanitize($_GET['order'] ?? '');
        $isEditing     = false;
        // Determine if this is an edit flow by checking the order ID in the query.
        // We will use database lookups when the pending session is missing.
        $vxParam       = sanitize($_GET['vx'] ?? '');
        $selectedBranchFromDb = '';

        // When editing an existing order, always fetch the vaccine code and branch from
        // the database so that we can populate the form even if editing is not allowed.
        if ($editId !== '') {
            try {
                $dbTmp = new Database();
                $dbTmp->query("SELECT MaVacXin, MaChiNhanh, TrangThaiDH, MaTV FROM donhangonl WHERE MaDHonl = :id LIMIT 1");
                $dbTmp->bind(':id', $editId);
                $orderRec = $dbTmp->single();
                if ($orderRec && isset($orderRec['MaVacXin'])) {
                    // Always set vaccine param based on the order record so that branches list can be built
                    $vxParam = $orderRec['MaVacXin'];
                    // Preselect the branch from the DB when editing
                    $selectedBranchFromDb = $orderRec['MaChiNhanh'];
                    // Only allow modifying the order if it belongs to the current user and is still pending
                    if ($orderRec['MaTV'] == current_user_id() && $orderRec['TrangThaiDH'] === 'Chờ xử lý') {
                        $isEditing = true;
                    }
                }
            } catch (Exception $e) {
                // ignore errors; fallback to default behaviour
            }
        }
        // If we have a pending order in session and the IDs match, use session data to override
        if (!empty($editId) && $pendingOrder && isset($pendingOrder['id']) && $pendingOrder['id'] === $editId) {
            $isEditing = true;
            $vxParam = $pendingOrder['vaccine'];
            // Use the pending order's branch as preselected
            $selectedBranchFromDb = $pendingOrder['branch'] ?? $selectedBranchFromDb;
        }
        // Resolve the actual vaccine code for this order. This prioritises the URL `vx` parameter,
        // then the pending order data, and finally the database record if `order` is provided. By
        // determining the vaccine code up front, the branch list and vaccine details can always be
        // retrieved even when editing is not allowed.
        $actualVx = '';
        if (!empty($vxParam)) {
            $actualVx = $vxParam;
        }
        // If a pending order exists for this ID, prefer its vaccine code for display
        if ($actualVx === '' && $pendingOrder && !empty($editId) && $pendingOrder['id'] === $editId) {
            $actualVx = $pendingOrder['vaccine'];
        }
        // Fallback: fetch the vaccine code from the database using the order record (regardless of status)
        if ($actualVx === '' && !empty($editId)) {
            try {
                $dbTmp2 = new Database();
                $dbTmp2->query("SELECT MaVacXin FROM donhangonl WHERE MaDHonl = :id LIMIT 1");
                $dbTmp2->bind(':id', $editId);
                $ord = $dbTmp2->single();
                if ($ord && isset($ord['MaVacXin'])) {
                    $actualVx = $ord['MaVacXin'];
                }
            } catch (Exception $e) {
                // ignore
            }
        }
        // Look up vaccine details for the determined vaccine code
        $vaccine = [];
        if (!empty($actualVx)) {
            try {
                $dbVx = new Database();
                $dbVx->query("SELECT * FROM vacxin WHERE MaVacXin = :v LIMIT 1");
                $dbVx->bind(':v', $actualVx);
                $vaccine = $dbVx->single() ?: [];
            } catch (Exception $e) {
                $vaccine = [];
            }
        }
        // Determine which image to show for the vaccine. Prefer a dedicated image
        // mapped by vaccine code, falling back to a small pool of generic
        // promotional shots when no specific mapping exists. This keeps the
        // ordering page consistent with the catalogue display.
        $imgMap = [
            'VX001' => 'vx_hepb.jpg',    // Hepatitis B
            'VX002' => 'vx_flu.jpg',     // Influenza (seasonal flu)
            'VX003' => 'vx_mmr.jpg',     // Measles, mumps and rubella
            'VX004' => 'vx_dtp.jpg',     // Diphtheria, tetanus and pertussis (DTP)
            'VX005' => 'vx_hpv.jpg',     // Human papillomavirus (HPV)
            'VX006' => 'vx_pfizer.jpg',  // Pfizer‑BioNTech COVID‑19 vaccine
            'VX007' => 'vx_astra.jpg',   // AstraZeneca COVID‑19 vaccine
            'VX008' => 'vx_tetanus.jpg', // Tetanus
            'VX009' => 'vx_polio.jpg',   // Polio
            'VX010' => 'vx_hepa.jpg'     // Hepatitis A
        ];
        $fallbackImages = ['fivevac2.jpg','fivevac3.jpg','fivevac4.jpg'];
        // Default to an empty string if no vaccine specified
        $vaccineImage = '';
        if (!empty($actualVx) && isset($imgMap[$actualVx])) {
            $vaccineImage = $imgMap[$actualVx];
        } elseif (!empty($actualVx)) {
            // If a code does not have a dedicated image, use a deterministic
            // selection from the fallback set based on the vaccine code hash
            $hash = abs(crc32($actualVx));
            $vaccineImage = $fallbackImages[$hash % count($fallbackImages)];
        }
        // Fetch all branches for the drop‑down using the resolved vaccine code. Only active branches
        // with available stock are returned.
        $branches = [];
        try {
            if (!empty($actualVx)) {
                $dbBr = new Database();
                $dbBr->query(
                    "SELECT cn.MaChiNhanh, cn.TenChiNhanh, cn.DiaChi
                     FROM chitiettonkho ct
                     JOIN chinhanh cn ON ct.MaChiNhanh = cn.MaChiNhanh
                     WHERE ct.MaVacXin = :v
                       AND (ct.SoLuongHienTai - ct.SoLuongDaSuDung) > 0
                       AND cn.TrangThaiHD = 'Hoạt động'
                     ORDER BY cn.MaChiNhanh"
                );
                $dbBr->bind(':v', $actualVx);
                $branches = $dbBr->resultSet();
            }
        } catch (Exception $e) {
            $branches = [];
        }
        if (is_post()){
            verify_csrf();
            $branch = sanitize($_POST['branch'] ?? '');
            $date   = sanitize($_POST['date'] ?? '');  // YYYY-MM-DD
            $time   = sanitize($_POST['time'] ?? '');  // HH:MM
            $vx     = sanitize($_POST['vaccine'] ?? '');
            // Validate mandatory fields
            if (!validate_nonempty($branch) || !validate_nonempty($date) || !validate_nonempty($time) || !validate_nonempty($vx)){
                flash('error','Vui lòng điền đầy đủ thông tin.');
                return $this->view('orders/create_onl', [
                    'vx_default'     => $vxParam,
                    'vaccine'        => $vaccine,
                    'vaccine_image'  => $vaccineImage,
                    'branches'       => $branches,
                    'selected_branch'=> $branch,
                    'selected_date'  => $date,
                    'selected_time'  => $time
                ]);
            }
            // Validate that appointment is at least 24 hours in the future
            try {
                $now = new DateTime('now', new DateTimeZone('Asia/Ho_Chi_Minh'));
                $appointment = new DateTime($date . ' ' . $time, new DateTimeZone('Asia/Ho_Chi_Minh'));
                $diff = $now->diff($appointment);
                $hoursDiff = ($diff->days * 24) + $diff->h + ($diff->i / 60);
                if ($appointment < $now || $hoursDiff < 24) {
                    flash('error', 'Ngày giờ tiêm phải cách thời điểm hiện tại ít nhất 24 giờ.');
                    return $this->view('orders/create_onl', [
                        'vx_default'     => $vxParam,
                        'vaccine'        => $vaccine,
                        'vaccine_image'  => $vaccineImage,
                        'branches'       => $branches,
                        'selected_branch'=> $branch,
                        'selected_date'  => $date,
                        'selected_time'  => $time
                    ]);
                }
            } catch (Exception $e) {
                flash('error', 'Ngày giờ tiêm không hợp lệ.');
                return $this->view('orders/create_onl', [
                    'vx_default'     => $vxParam,
                    'vaccine'        => $vaccine,
                    'vaccine_image'  => $vaccineImage,
                    'branches'       => $branches,
                    'selected_branch'=> $branch,
                    'selected_date'  => $date,
                    'selected_time'  => $time
                ]);
            }
            $db = new Database();
            try {
                $db->begin();
                // Kiểm tra tồn khả dụng: SoLuongHienTai - SoLuongDaSuDung >= 1
                $db->query("SELECT SoLuongHienTai, SoLuongDaSuDung FROM chitiettonkho WHERE MaChiNhanh=:b AND MaVacXin=:v LIMIT 1");
                $db->bind(':b', $branch);
                $db->bind(':v', $vx);
                $stock = $db->single();
                $avail = ($stock ? intval($stock['SoLuongHienTai']) - intval($stock['SoLuongDaSuDung']) : 0);
                if ($avail < 1){ throw new Exception('Vắc xin đã hết hàng tại chi nhánh này'); }

                // Determine vaccine price if available
                $price = isset($vaccine['Gia']) ? (int)$vaccine['Gia'] : 0;

                if ($isEditing) {
                    // Update existing pending order or order from DB
                    $orderId = $pendingOrder['id'] ?? $editId;
                    // Determine the previous branch for inventory adjustment
                    $oldBranch = $selectedBranchFromDb;
                    if ($pendingOrder && isset($pendingOrder['branch'])) {
                        $oldBranch = $pendingOrder['branch'];
                    }
                    // If the branch has changed, revert the old reservation and reserve the new one
                    if ($oldBranch && $oldBranch !== $branch) {
                        // Increase stock back on old branch
                        $db->query("UPDATE chitiettonkho SET SoLuongHienTai = SoLuongHienTai + 1 WHERE MaChiNhanh = :oldb AND MaVacXin = :vold");
                        $db->bind(':oldb', $oldBranch);
                        $db->bind(':vold', $vx);
                        $db->execute();
                        // Decrease stock on new branch
                        $db->query("UPDATE chitiettonkho SET SoLuongHienTai = SoLuongHienTai - 1 WHERE MaChiNhanh = :newb AND MaVacXin = :vold");
                        $db->bind(':newb', $branch);
                        $db->bind(':vold', $vx);
                        $db->execute();
                    }
                    // Update order details
                    $db->query("UPDATE donhangonl SET MaChiNhanh = :b, MaVacXin = :v, ThanhTien = :price, NgayTao = NOW() WHERE MaDHonl = :id");
                    $db->bind(':b', $branch);
                    $db->bind(':v', $vx);
                    $db->bind(':price', $price);
                    $db->bind(':id', $orderId);
                    $db->execute();
                    $db->commit();
                    // Update pending order session (create one if missing) so that payment page has context
                    $_SESSION['pending_order'] = [
                        'id'      => $orderId,
                        'branch'  => $branch,
                        'vaccine' => $vx,
                        'date'    => $date,
                        'time'    => $time,
                        'price'   => $price
                    ];
                    // Update last_order_url to include order id for editing
                    // Preserve the vaccine param in the URL so that the edit page knows which vaccine to load
                    $_SESSION['last_order_url'] = 'orders/create_onl?vx=' . urlencode($vx) . '&order=' . urlencode($orderId);
                    flash('success','Thông tin đơn hàng đã được cập nhật. Vui lòng thanh toán trong 10 phút.');
                    redirect('payments/qr');
                } else {
                    // Create new order
                    // Generate next order ID (ONL###)
                    $db->query("SELECT COALESCE(MAX(CAST(SUBSTRING(MaDHonl, 4) AS UNSIGNED)), 0) AS max_num FROM donhangonl");
                    $maxRow = $db->single();
                    $nextOrderNum = intval($maxRow['max_num']) + 1;
                    $orderId = 'ONL' . str_pad($nextOrderNum, 3, '0', STR_PAD_LEFT);
                    // Determine current user's ID
                    $maTV = current_user_id();
                    // Insert new order with status 'Chờ xử lý' and payment method 'Chuyển khoản'
                    $db->query("INSERT INTO donhangonl (MaDHonl, MaTV, MaChiNhanh, MaVacXin, NgayTao, ThanhTien, HinhThucThanhToan, TrangThaiDH) VALUES (:id, :tv, :b, :v, NOW(), :price, 'Chuyển khoản', 'Chờ xử lý')");
                    $db->bind(':id', $orderId);
                    $db->bind(':tv', $maTV);
                    $db->bind(':b', $branch);
                    $db->bind(':v', $vx);
                    $db->bind(':price', $price);
                    $db->execute();
                    // Reserve inventory: decrement available stock by 1 for the chosen branch
                    $db->query("UPDATE chitiettonkho SET SoLuongHienTai = SoLuongHienTai - 1 WHERE MaChiNhanh = :b AND MaVacXin = :v");
                    $db->bind(':b', $branch);
                    $db->bind(':v', $vx);
                    $db->execute();
                    $db->commit();
                    // Save pending order in session
                    $_SESSION['pending_order'] = [
                        'id'      => $orderId,
                        'branch'  => $branch,
                        'vaccine' => $vx,
                        'date'    => $date,
                        'time'    => $time,
                        'price'   => $price
                    ];
                    // Build last_order_url with order id parameter
                    // Preserve the vaccine param in the URL so that the edit page knows which vaccine to load
                    $_SESSION['last_order_url'] = 'orders/create_onl?vx=' . urlencode($vx) . '&order=' . urlencode($orderId);
                    flash('success','Yêu cầu đặt mua đã được lưu. Vui lòng thanh toán trong 10 phút.');
                    redirect('payments/qr');
                }
            } catch(Exception $e){
                $db->rollBack();
                // Provide a user-friendly error message instead of exposing SQL details
                flash('error','Đã xảy ra lỗi khi xử lý đơn. Vui lòng thử lại.');
            }
        }
        // On GET or after errors, render the form with the vaccine details, image
        // and branch list. If editing, prepopulate the selected branch/date/time.
        // Determine selected branch/date/time values for prepopulating the form. Use
        // pendingOrder values when available; otherwise, fall back to DB values for branch.
        $preselectedBranch = '';
        $preselectedDate   = '';
        $preselectedTime   = '';
        if ($isEditing) {
            if ($pendingOrder && isset($pendingOrder['branch'])) {
                $preselectedBranch = $pendingOrder['branch'];
                $preselectedDate   = $pendingOrder['date'] ?? '';
                $preselectedTime   = $pendingOrder['time'] ?? '';
            } elseif (!empty($selectedBranchFromDb)) {
                $preselectedBranch = $selectedBranchFromDb;
            }
        }
        $this->view('orders/create_onl', [
            // Use the resolved vaccine code for the hidden input in the form
            'vx_default'      => $actualVx,
            'vaccine'         => $vaccine,
            'vaccine_image'   => $vaccineImage,
            'branches'        => $branches,
            'selected_branch' => $preselectedBranch,
            'selected_date'   => $preselectedDate,
            'selected_time'   => $preselectedTime
        ]);
    }
}
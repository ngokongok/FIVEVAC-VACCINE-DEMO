<?php
    // app/controllers/PaymentsController.php
class PaymentsController extends Controller {

    // Hiển thị mã QR thanh toán cho khách hàng
    public function qr() {
        require_role(['customer']);
        // When the user submits the payment form, simulate processing the
        // transaction and display a success message. In a real system this
        // would integrate with a payment gateway and update the order
        // status accordingly.
        if (is_post()) {
            verify_csrf();
            // Check for a pending order in the session. Only process if it exists.
            if (!empty($_SESSION['pending_order'])) {
                $po = $_SESSION['pending_order'];
                unset($_SESSION['pending_order']);
                // Also clear the last order URL. Once payment is confirmed, the
                // user should create a fresh order rather than reusing the old one.
                if (isset($_SESSION['last_order_url'])) {
                    unset($_SESSION['last_order_url']);
                }
                $orderId = $po['id'];
                $branch  = $po['branch'];
                $vx      = $po['vaccine'];
                $date    = $po['date'];
                $time    = $po['time'];
                try {
                    $db = new Database();
                    $db->begin();
                    // Update the order status to 'Đang xử lý' or 'Thành công'
                    $db->query("UPDATE donhangonl SET TrangThaiDH = 'Đang xử lý' WHERE MaDHonl = :id");
                    $db->bind(':id', $orderId);
                    $db->execute();
                    // Generate next schedule ID (LHxxx) based on existing numeric codes
                    $db->query("SELECT MaLichHen FROM lichhentiem");
                    $codes = $db->resultSet();
                    $max = 0;
                    foreach ($codes as $c) {
                        $code = $c['MaLichHen'] ?? '';
                        if (preg_match('/^LH(\d+)$/', $code, $m)) {
                            $num = intval($m[1]);
                            if ($num > $max) {
                                $max = $num;
                            }
                        }
                    }
                    $nextNum = $max + 1;
                    // Pad with leading zeros to maintain 3-digit format
                    $maLichHen = 'LH' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
                    // Create the appointment schedule using the stored date/time
                    $ngaygio = $date . ' ' . $time . ':00';
                    $db->query("INSERT INTO lichhentiem (MaLichHen, MaDHonl, NgayGio, GioMoi, TrangThai) VALUES (:mlh, :idh, :ng, NULL, 'Đã tạo')");
                    $db->bind(':mlh', $maLichHen);
                    $db->bind(':idh', $orderId);
                    $db->bind(':ng', $ngaygio);
                    $db->execute();
                    // Inventory adjustment is now handled at order creation/reservation.
                    // Do not modify stock quantities on payment confirmation.
                    // Mark order as 'Thành công'
                    $db->query("UPDATE donhangonl SET TrangThaiDH = 'Thành công' WHERE MaDHonl = :id2");
                    $db->bind(':id2', $orderId);
                    $db->execute();
                    $db->commit();
                } catch (Exception $e) {
                    // Rollback if any error occurs and inform the user
                    if (isset($db)) { $db->rollBack(); }
                    flash('error', 'Thanh toán thất bại: ' . $e->getMessage());
                    return redirect('appointments');
                }
                flash('success','Thanh toán thành công. Lịch hẹn của bạn đã được ghi nhận.');
                // Redirect to appointment list after successful payment
                return redirect('appointments');
            }
            // If no pending order, just display a generic success message
            flash('success','Thanh toán thành công.');
            return redirect('appointments');
        }
        $this->view('payments/qr', []);
    }

}
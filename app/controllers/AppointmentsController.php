<?php
    // app/controllers/AppointmentsController.php
class AppointmentsController extends Controller {

    /**
     * Display a list of the current user's appointments.
     *
     * Retrieves appointment records by joining the appropriate tables to
     * include the vaccine name and branch information. For demonstration
     * purposes, if no reliable user mapping is defined, all appointments
     * will be shown. Each row includes a link to view details and to
     * request a schedule change.
     */
    public function index() {
        require_role(['customer']);
        $uid = current_user_id();
        $appointments = [];
        try {
            $db = new Database();
            /*
             * Only load appointments belonging to the current user. The
             * MaTV field in donhangonl references MaND via the thanhvien
             * table, so we filter on MaTV = current_user_id().
             */
            $sql = "SELECT lh.MaLichHen, lh.NgayGio, lh.GioMoi, lh.TrangThai, vx.TenVacXin, cn.TenChiNhanh
                    FROM lichhentiem lh
                    JOIN donhangonl d ON lh.MaDHonl = d.MaDHonl
                    LEFT JOIN vacxin vx ON d.MaVacXin = vx.MaVacXin
                    LEFT JOIN chinhanh cn ON d.MaChiNhanh = cn.MaChiNhanh
                    WHERE d.MaTV = :uid";
            $db->query($sql);
            $db->bind(':uid', current_user_id());
            $appointments = $db->resultSet();
        } catch (Exception $e) {
            flash('error', 'Không thể tải danh sách lịch hẹn: ' . $e->getMessage());
        }
        $this->view('appointments/list', ['appointments' => $appointments]);
    }

    /**
     * Request a change to an existing appointment.
     *
     * Accepts a MaLichHen parameter. On GET, displays the current
     * appointment details and provides a form for entering a new
     * date/time. On POST, updates the record's GioMoi and sets the
     * TrangThai to 'Chờ phê duyệt'.
     *
     * @param string|null $id Appointment ID
     */
    public function edit($id = null) {
        require_role(['customer']);
        if (!$id) {
            flash('error', 'Không xác định mã lịch hẹn.');
            return redirect('appointments');
        }
        $db = new Database();
        // Fetch appointment with ownership check (belongs to current user)
        try {
            $db->query("SELECT lh.MaLichHen, lh.MaDHonl, lh.NgayGio, lh.GioMoi, lh.TrangThai, d.MaTV
                        FROM lichhentiem lh
                        JOIN donhangonl d ON lh.MaDHonl = d.MaDHonl
                        WHERE lh.MaLichHen = :id AND d.MaTV = :uid LIMIT 1");
            $db->bind(':id', $id);
            $db->bind(':uid', current_user_id());
            $appt = $db->single();
            if (!$appt) {
                flash('error', 'Không tìm thấy lịch hẹn của bạn.');
                return redirect('appointments');
            }
            // Enforce business rules: must be at least 24h before the scheduled time, no prior change request, status must be 'Đã tạo'
            $now = new DateTime();
            $scheduled = new DateTime($appt['NgayGio']);
            $diffSeconds = $scheduled->getTimestamp() - $now->getTimestamp();
            $canChange = true;
            if ($diffSeconds < 24 * 3600) {
                $canChange = false;
                flash('error', 'Lịch hẹn chỉ được phép sửa trước thời gian hẹn ít nhất 24 giờ.');
            }
            if (!empty($appt['GioMoi']) || $appt['TrangThai'] !== 'Đã tạo') {
                $canChange = false;
                flash('error', 'Phiếu lịch hẹn này đã có yêu cầu thay đổi hoặc đang chờ xử lý.');
            }
            // If not allowed to change, redirect
            if (!$canChange) {
                return redirect('appointments');
            }
        } catch (Exception $e) {
            flash('error', 'Không thể tải lịch hẹn: ' . $e->getMessage());
            return redirect('appointments');
        }
        // If POST: process the change request
        if (is_post()) {
            verify_csrf();
            $date = sanitize($_POST['date'] ?? '');
            $time = sanitize($_POST['time'] ?? '');
            // Basic validation: require date and time
            if (!validate_nonempty($date) || !validate_nonempty($time)) {
                flash('error', 'Vui lòng nhập đầy đủ ngày và giờ.');
                return $this->view('appointments/edit', [
                    'appointment'    => $appt,
                    'selected_date'  => $date,
                    'selected_time'  => $time
                ]);
            }
            $new = $date . ' ' . $time . ':00';
            // Validate new date/time: ensure at least 24 hours from now and within working hours
            try {
                $tz    = new DateTimeZone('Asia/Ho_Chi_Minh');
                $newDT = new DateTime($new, $tz);
                $now   = new DateTime('now', $tz);
                // Must be at least 24 hours from now
                $diff  = $now->diff($newDT);
                $hoursDiff = ($diff->days * 24) + $diff->h + ($diff->i / 60);
                if ($newDT <= $now || $hoursDiff < 24) {
                    flash('error', 'Ngày giờ tiêm phải cách thời điểm hiện tại ít nhất 24 giờ.');
                    return $this->view('appointments/edit', [
                        'appointment' => $appt,
                        'selected_date' => $date,
                        'selected_time' => $time
                    ]);
                }
                // Check working hours (07:00 to 20:00). Extract hour and minute
                $hour = (int)$newDT->format('H');
                $minute = (int)$newDT->format('i');
                // convert to minutes since midnight
                $totalMinutes = $hour * 60 + $minute;
                $startMinutes = 7 * 60;
                $endMinutes   = 20 * 60;
                if ($totalMinutes < $startMinutes || $totalMinutes > $endMinutes) {
                    flash('error', 'Giờ tiêm phải nằm trong khoảng 07:00 đến 20:00.');
                    return $this->view('appointments/edit', [
                        'appointment' => $appt,
                        'selected_date' => $date,
                        'selected_time' => $time
                    ]);
                }
            } catch (Exception $e) {
                // Invalid date/time format
                flash('error', 'Ngày giờ mới không hợp lệ.');
                return $this->view('appointments/edit', [
                    'appointment' => $appt,
                    'selected_date' => $date,
                    'selected_time' => $time
                ]);
            }
            try {
                $db->query("UPDATE lichhentiem SET GioMoi = :gm, TrangThai = 'Chờ phê duyệt' WHERE MaLichHen = :id");
                $db->bind(':gm', $new);
                $db->bind(':id', $id);
                $db->execute();
                flash('info', 'Yêu cầu đổi lịch hẹn đã được gửi.');
                return redirect('appointments');
            } catch (Exception $e) {
                flash('error', 'Không thể cập nhật lịch hẹn: ' . $e->getMessage());
            }
        }
        // GET: show the edit form
        $this->view('appointments/edit', ['appointment' => $appt]);
    }
    // Khách hàng yêu cầu đổi lịch hẹn
    public function request_change() {
        require_role(['customer']);
        $this->view('appointments/request_change', []);
    }

    // Nhân viên phê duyệt đổi lịch hẹn
    public function approve_change() {
        require_role(['staff']);
        $db = new Database();
        // Handle approval or rejection of a specific request
        if (is_post()) {
            verify_csrf();
            $id = sanitize($_POST['id'] ?? '');
            $decision = sanitize($_POST['decision'] ?? '');
            try {
                // Fetch the record to ensure it exists and is pending
                $db->query("SELECT MaLichHen, GioMoi FROM lichhentiem WHERE MaLichHen = :id AND TrangThai = 'Chờ phê duyệt' LIMIT 1");
                $db->bind(':id', $id);
                $appt = $db->single();
                if (!$appt) {
                    flash('error', 'Không tìm thấy yêu cầu đổi lịch chờ phê duyệt.');
                    return redirect('appointments/approve_change');
                }
                if ($decision === 'accept') {
                    // Before accepting, validate the requested new time (GioMoi) is at least 24h from now and within working hours (07:00-20:00)
                    $new = $appt['GioMoi'];
                    try {
                        $tz = new DateTimeZone('Asia/Ho_Chi_Minh');
                        $newDT = new DateTime($new, $tz);
                        $now   = new DateTime('now', $tz);
                        $diff  = $now->diff($newDT);
                        $hoursDiff = ($diff->days * 24) + $diff->h + ($diff->i / 60);
                        $hour  = (int)$newDT->format('H');
                        $minute= (int)$newDT->format('i');
                        $totalMinutes = $hour * 60 + $minute;
                        $startMinutes = 7 * 60;
                        $endMinutes   = 20 * 60;
                        if ($newDT <= $now || $hoursDiff < 24 || $totalMinutes < $startMinutes || $totalMinutes > $endMinutes) {
                            flash('error', 'Không thể phê duyệt: thời gian hẹn mới không hợp lệ.');
                            return redirect('appointments/approve_change');
                        }
                    } catch (Exception $e) {
                        flash('error', 'Không thể phê duyệt: thời gian hẹn mới không hợp lệ.');
                        return redirect('appointments/approve_change');
                    }
                    // Accept: update NgayGio to GioMoi, clear GioMoi, set TrangThai to 'Chấp nhận'
                    $db->query("UPDATE lichhentiem SET NgayGio = GioMoi, GioMoi = NULL, TrangThai = 'Chấp nhận' WHERE MaLichHen = :id");
                    $db->bind(':id', $id);
                    $db->execute();
                    flash('success', 'Đã chấp nhận yêu cầu đổi lịch.');
                } elseif ($decision === 'deny') {
                    // Deny: set TrangThai to 'Từ chối'
                    $db->query("UPDATE lichhentiem SET TrangThai = 'Từ chối' WHERE MaLichHen = :id");
                    $db->bind(':id', $id);
                    $db->execute();
                    flash('success', 'Đã từ chối yêu cầu đổi lịch.');
                } else {
                    flash('error', 'Lựa chọn không hợp lệ.');
                }
                return redirect('appointments/approve_change');
            } catch (Exception $e) {
                flash('error', 'Không thể cập nhật yêu cầu: ' . $e->getMessage());
                return redirect('appointments/approve_change');
            }
        }
        // Otherwise, list all pending change requests
        $requests = [];
        try {
            $db->query("SELECT MaLichHen, MaDHonl, NgayGio, GioMoi FROM lichhentiem WHERE TrangThai = 'Chờ phê duyệt' ORDER BY NgayGio ASC");
            $requests = $db->resultSet();
        } catch (Exception $e) {
            flash('error', 'Không thể tải danh sách yêu cầu: ' . $e->getMessage());
        }
        $this->view('appointments/approve_change', [
            'hide_navbar' => true,
            'title'       => 'Phê duyệt yêu cầu chỉnh sửa lịch hẹn',
            'requests'    => $requests
        ]);
    }

}
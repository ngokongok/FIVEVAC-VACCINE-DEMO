<?php
    // app/controllers/ConsultController.php
class ConsultController extends Controller {

    /**
     * Display the consultation request page and handle new requests.
     *
     * This method serves as both the entry point for customers wishing to
     * submit a new consultation request and the listing of their prior
     * requests. When accessed via GET it will retrieve all consultation
     * records belonging to the current user, optionally filtered by a
     * search term and/or response status. When accessed via POST it
     * validates the submitted description, generates a new ticket ID
     * (MaTuVan) and inserts the request into the database. Success and
     * error messages are delivered via flash notifications and the
     * visitor is redirected back to the same page.
     */
    public function request() {
        require_role(['customer']);
        $db = new Database();
        // Handle form submission for a new consultation request
        if (is_post()) {
            verify_csrf();
            $content = sanitize($_POST['content'] ?? '');
            // Ensure the description is at least 50 characters. Use mb_strlen if available.
            $len = function_exists('mb_strlen') ? mb_strlen($content, 'UTF-8') : strlen($content);
            if ($len < 50) {
                flash('error', 'Nội dung mô tả quá ngắn, vui lòng nhập ít nhất 50 ký tự.');
            } else {
                try {
                    $code = $this->generateConsultCode();
                    $db->query("INSERT INTO phieutuvan (MaTuVan, MaTV, MaBS, NgayTao, NoiDungYeuCau, TrangThaiPhanHoi) VALUES (:id, :uid, NULL, :date, :content, 'Chưa trả lời')");
                    $db->bind(':id', $code);
                    $db->bind(':uid', current_user_id());
                    $db->bind(':date', date('Y-m-d'));
                    $db->bind(':content', $content);
                    $db->execute();
                    flash('info', 'Yêu cầu tư vấn của bạn đã được gửi thành công.');
                    return redirect('consult/request');
                } catch (Exception $e) {
                    flash('error', 'Không thể gửi yêu cầu tư vấn: ' . $e->getMessage());
                }
            }
        }
        // Build the query to retrieve the current user's consultation history
        $keyword = sanitize($_GET['q'] ?? '');
        $status  = sanitize($_GET['status'] ?? '');
        $sql  = "SELECT MaTuVan, NoiDungYeuCau, TrangThaiPhanHoi, NgayTao, NgayPhanHoi FROM phieutuvan WHERE MaTV = :uid";
        $params = [':uid' => current_user_id()];
        if ($keyword !== '') {
            $sql .= " AND (MaTuVan LIKE :kw OR NoiDungYeuCau LIKE :kw)";
            $params[':kw'] = '%' . $keyword . '%';
        }
        if ($status !== '') {
            $sql .= " AND TrangThaiPhanHoi = :st";
            $params[':st'] = $status;
        }
        $sql .= " ORDER BY NgayTao DESC, MaTuVan DESC";
        $consults = [];
        try {
            $db->query($sql);
            foreach ($params as $k => $v) {
                $db->bind($k, $v);
            }
            $rows = $db->resultSet();
            // Compute a shortened preview of the question for display
            foreach ($rows as $row) {
                $desc = $row['NoiDungYeuCau'] ?? '';
                // Use multibyte-safe truncation if available
                if (function_exists('mb_strimwidth')) {
                    $short = mb_strimwidth($desc, 0, 50, '...', 'UTF-8');
                } else {
                    $short = strlen($desc) > 50 ? substr($desc, 0, 47) . '...' : $desc;
                }
                $row['short'] = $short;
                $consults[] = $row;
            }
        } catch (Exception $e) {
            flash('error', 'Không thể tải lịch sử tư vấn: ' . $e->getMessage());
        }
        // Pass both the list and current search filters to the view
        $this->view('consult/request', [
            'consults' => $consults,
            'keyword'  => $keyword,
            'status'   => $status
        ]);
    }

    /**
     * Show the details of a single consultation request.
     *
     * Only the owner of the request can view the details. If the record
     * does not belong to the current user or cannot be found, an error
     * message is displayed and the user is redirected to the request page.
     *
     * @param string|null $id The consultation ticket ID
     */
    public function detail($id = null) {
        require_role(['customer']);
        if (!$id) {
            flash('error', 'Không xác định mã phiếu tư vấn.');
            return redirect('consult/request');
        }
        try {
            $db = new Database();
            $db->query("SELECT * FROM phieutuvan WHERE MaTuVan = :id AND MaTV = :uid LIMIT 1");
            $db->bind(':id', $id);
            $db->bind(':uid', current_user_id());
            $consult = $db->single();
            if (!$consult) {
                flash('error', 'Không tìm thấy phiếu tư vấn của bạn.');
                return redirect('consult/request');
            }
        } catch (Exception $e) {
            flash('error', 'Không thể truy xuất dữ liệu: ' . $e->getMessage());
            return redirect('consult/request');
        }
        $this->view('consult/detail', ['consult' => $consult]);
    }

    /**
     * Generate a unique consultation ticket ID (MaTuVan).
     *
     * Ticket IDs follow the pattern TV001, TV002, ... by selecting
     * the current maximum code and incrementing the numeric portion.
     * If no existing records are found, TV001 is returned.
     *
     * @return string A new unique ticket ID starting with 'TV'
     */
    private function generateConsultCode() {
        $db = new Database();
        try {
            $db->query("SELECT MaTuVan FROM phieutuvan WHERE MaTuVan LIKE 'TV%' ORDER BY MaTuVan DESC LIMIT 1");
            $row = $db->single();
            if ($row && !empty($row['MaTuVan'])) {
                $last = $row['MaTuVan'];
                $num  = intval(substr($last, 2)) + 1;
                return 'TV' . str_pad($num, 3, '0', STR_PAD_LEFT);
            }
        } catch (Exception $e) {
            // On error, fall back to a unique ID based on time
        }
        // Default if no rows or an exception occurred
        return 'TV001';
    }

    // Nhân viên trả lời yêu cầu tư vấn
    public function respond($id = null) {
        // Chỉ cho phép nhân viên trả lời
        require_role(['staff']);
        $db = new Database();
        // Handle response submission
        if (is_post()) {
            verify_csrf();
            $ticketId = sanitize($_POST['ticket_id'] ?? '');
            $reply    = sanitize($_POST['reply'] ?? '');
            // Ensure reply length >= 50 characters
            $len = function_exists('mb_strlen') ? mb_strlen($reply, 'UTF-8') : strlen($reply);
            if ($len < 50) {
                flash('error', 'Nội dung phản hồi phải có ít nhất 50 ký tự.');
                return redirect('consult/respond/' . urlencode($ticketId));
            }
            try {
                // Fetch the record to ensure it exists and is pending
                $db->query("SELECT * FROM phieutuvan WHERE MaTuVan = :id AND TrangThaiPhanHoi = 'Chưa trả lời' LIMIT 1");
                $db->bind(':id', $ticketId);
                $consult = $db->single();
                if (!$consult) {
                    flash('error', 'Không tìm thấy yêu cầu tư vấn cần phản hồi hoặc phiếu đã được xử lý.');
                    return redirect('consult/respond');
                }
                // Update with reply, date and assign current staff/doctor as MaBS
                $db->query("UPDATE phieutuvan SET MaBS = :staffId, NgayPhanHoi = NOW(), NoiDungPhanHoi = :answer, TrangThaiPhanHoi = 'Đã trả lời' WHERE MaTuVan = :id");
                $db->bind(':staffId', current_user_id());
                $db->bind(':answer', $reply);
                $db->bind(':id', $ticketId);
                $db->execute();
                flash('success', 'Đã gửi phản hồi thành công.');
                return redirect('consult/respond');
            } catch (Exception $e) {
                flash('error', 'Không thể lưu phản hồi: ' . $e->getMessage());
                return redirect('consult/respond/' . urlencode($ticketId));
            }
        }
        // If an ID is provided in the URL, show the response form for that ticket
        if ($id) {
            try {
                $db->query("SELECT MaTuVan, NoiDungYeuCau FROM phieutuvan WHERE MaTuVan = :id AND TrangThaiPhanHoi = 'Chưa trả lời' LIMIT 1");
                $db->bind(':id', $id);
                $consult = $db->single();
                if (!$consult) {
                    flash('error', 'Không tìm thấy yêu cầu tư vấn cần phản hồi hoặc phiếu đã được xử lý.');
                    return redirect('consult/respond');
                }
                // Show form with the full question
                return $this->view('consult/respond', [
                    'hide_navbar' => true,
                    'title'       => 'Phản hồi tư vấn',
                    'consult'     => $consult
                ]);
            } catch (Exception $e) {
                flash('error', 'Không thể truy xuất dữ liệu: ' . $e->getMessage());
                return redirect('consult/respond');
            }
        }
        // Otherwise, list all pending consultation requests
        $consults = [];
        try {
            $db->query("SELECT MaTuVan, NoiDungYeuCau, NgayTao FROM phieutuvan WHERE TrangThaiPhanHoi = 'Chưa trả lời' ORDER BY NgayTao DESC");
            $rows = $db->resultSet();
            foreach ($rows as $row) {
                // Truncate long questions for display
                $desc = $row['NoiDungYeuCau'] ?? '';
                if (function_exists('mb_strimwidth')) {
                    $row['short'] = mb_strimwidth($desc, 0, 60, '...', 'UTF-8');
                } else {
                    $row['short'] = strlen($desc) > 60 ? substr($desc, 0, 57) . '...' : $desc;
                }
                $consults[] = $row;
            }
        } catch (Exception $e) {
            flash('error', 'Không thể tải danh sách yêu cầu tư vấn: ' . $e->getMessage());
        }
        $this->view('consult/respond', [
            'hide_navbar' => true,
            'title'       => 'Phản hồi tư vấn',
            'consults'    => $consults
        ]);
    }

    // Lịch sử tư vấn – dành cho nhân viên hoặc quản trị
    public function history() {
        require_role(['staff','admin']);
        $this->view('consult/history', [
            'hide_navbar' => true,
            'title'       => 'Tra cứu lịch sử tư vấn'
        ]);
    }

}
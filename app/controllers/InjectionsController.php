<?php
// app/controllers/InjectionsController.php

/**
 * Controller for displaying a customer's vaccination history (lịch sử tiêm).
 *
 * The history shows all injection records (phieutiem) associated with the
 * logged‑in user's personal profile. A personal profile is determined by
 * matching the user's phone number in the `taikhoan` table with a record
 * in `thongtinkhachhang`. If no matching profile is found, the list
 * remains empty. Only customers can access this page.
 */
class InjectionsController extends Controller {
    /**
     * Show the logged in customer's injection history.
     */
    public function history() {
        require_role(['customer']);
        $db = new Database();
        $userId = current_user_id();
        // Find the user's phone
        $db->query("SELECT SDT FROM taikhoan WHERE MaND = :mand LIMIT 1");
        $db->bind(':mand', $userId);
        $acc = $db->single();
        $injections = [];
        if ($acc && !empty($acc['SDT'])) {
            // Find corresponding MaKH from thongtinkhachhang
            $db->query("SELECT MaKH FROM thongtinkhachhang WHERE SDT = :s LIMIT 1");
            $db->bind(':s', $acc['SDT']);
            $row = $db->single();
            if ($row && !empty($row['MaKH'])) {
                $maKh = $row['MaKH'];
                // Fetch injection history
                $db->query("SELECT * FROM phieutiem WHERE MaKH = :kh ORDER BY NgayTiem DESC, GioTiem DESC");
                $db->bind(':kh', $maKh);
                $injections = $db->resultSet() ?? [];
            }
        }
        $this->view('injections/history', [ 'injections' => $injections ]);
    }
}
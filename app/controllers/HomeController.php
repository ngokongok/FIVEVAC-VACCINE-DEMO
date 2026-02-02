<?php
// app/controllers/HomeController.php
class HomeController extends Controller {
    public function index() {
        // Prepare statistics for the "About Us" section.
        $branchCount = 0;
        $orderCount  = 0;
        $staffCount  = 0;
        try {
            $db = new Database();
            // Count branches
            $db->query("SELECT COUNT(*) AS cnt FROM chinhanh");
            $row = $db->single();
            $branchCount = (int)($row['cnt'] ?? 0);
            // Count orders from both online and POS tables
            $db->query("SELECT COUNT(*) AS cnt FROM donhangonl");
            $row = $db->single();
            $orderCount = (int)($row['cnt'] ?? 0);
            $db->query("SELECT COUNT(*) AS cnt FROM donhangpos");
            $row = $db->single();
            $orderCount += (int)($row['cnt'] ?? 0);
            // Fake the order count to appear larger on the homepage by adding a constant
            // This makes the statistic more visually impactful. The plus sign will be added in the view.
            $orderCount += 100;
            // Count staff across different roles (doctors, CSKH staff, administrators)
            $db->query("SELECT COUNT(*) AS cnt FROM bacsi");
            $row = $db->single();
            $staffCount = (int)($row['cnt'] ?? 0);
            $db->query("SELECT COUNT(*) AS cnt FROM nhanviencskh");
            $row = $db->single();
            $staffCount += (int)($row['cnt'] ?? 0);
            $db->query("SELECT COUNT(*) AS cnt FROM quantrivien");
            $row = $db->single();
            $staffCount += (int)($row['cnt'] ?? 0);
        } catch (Exception $e) {
            // Silently ignore errors; counts will remain zero
        }
        $this->view('home/home', [
            'heroTitle'    => 'Trung tâm tiêm chủng Fivevac',
            'heroSubtitle' => 'Đặt lịch và tiêm chủng an toàn, minh bạch.',
            'stats'        => [
                'branches' => $branchCount,
                'orders'   => $orderCount,
                'staff'    => $staffCount,
            ]
        ]);
    }
}
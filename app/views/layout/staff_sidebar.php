<?php
/* app/views/layout/staff_sidebar.php
 * Sidebar navigation for staff users. This sidebar is intended to be displayed
 * on the left side of the staff dashboard and other staff pages. Each link
 * corresponds to a major staff function such as viewing pending
 * consultations, managing stock, searching or adding records, processing
 * appointment change requests and handling POS transactions. The current
 * active section is highlighted based on the `url` query parameter.
 */
if (!defined('IN_APP')) {
    http_response_code(404);
    exit;
}

// Determine the current path from the `url` query parameter. If not set,
// default to an empty string. This allows us to highlight the active menu
// item below. We trim any trailing slashes for consistency.
$currentPath = trim($_GET['url'] ?? '', '/');

// Determine staff role membership. A user can be either a CSKH (customer service)
// employee or a doctor (bacsi). Admins (quantrivien) are not treated as staff here.
try {
    $dbSidebar = new Database();
    // Check CSKH membership
    $dbSidebar->query("SELECT 1 FROM nhanviencskh WHERE MaNV = :id LIMIT 1");
    $dbSidebar->bind(':id', current_user_id());
    $isCSKH = (bool)$dbSidebar->single();
    // Check doctor membership
    $dbSidebar->query("SELECT 1 FROM bacsi WHERE MaBS = :id LIMIT 1");
    $dbSidebar->bind(':id', current_user_id());
    $isDoctor = (bool)$dbSidebar->single();
} catch (Exception $e) {
    $isCSKH = false;
    $isDoctor = false;
}

// Helper closure to determine if a link should be marked as active. A link
// is considered active if the beginning of the current path matches the
// provided prefix. This allows for nested routes (e.g. records/search and
// records/add_profile) to share the same top-level highlight.
$isActive = function (string $prefix) use ($currentPath): string {
    return (strpos($currentPath, $prefix) === 0) ? 'active' : '';
};
?>
<nav class="col-lg-3 mb-3">
  <div class="list-group">
    <a href="<?= base_url('staff/dashboard') ?>" class="list-group-item list-group-item-action <?= $isActive('staff/dashboard') ?>">
      <strong>Tổng quan</strong>
    </a>
    <?php
      // Render doctor-specific menu
      if ($isDoctor) {
          echo '<a href="' . base_url('consult/respond') . '" class="list-group-item list-group-item-action ' . $isActive('consult/respond') . '">Phản hồi tư vấn</a>';
          echo '<a href="' . base_url('records/doctor_manage') . '" class="list-group-item list-group-item-action ' . $isActive('records/doctor_manage') . '">Quản lý hồ sơ tiêm chủng</a>';
      } elseif ($isCSKH) {
          // Render CSKH-specific menu
          echo '<a href="' . base_url('appointments/approve_change') . '" class="list-group-item list-group-item-action ' . $isActive('appointments/approve_change') . '">Phê duyệt yêu cầu chỉnh sửa lịch hẹn</a>';
          echo '<a href="' . base_url('records/manage') . '" class="list-group-item list-group-item-action ' . $isActive('records/manage') . '">Quản lý hồ sơ tiêm chủng</a>';
          echo '<a href="' . base_url('transactions/search_orders') . '" class="list-group-item list-group-item-action ' . $isActive('transactions/search_orders') . '">Quản lý đơn hàng</a>';
          echo '<a href="' . base_url('transactions/create_pos') . '" class="list-group-item list-group-item-action ' . $isActive('transactions/create_pos') . '">Tạo đơn hàng POS</a>';
      } else {
          // Fallback: non-CSKH and non-doctor staff (e.g. other roles) see nothing extra
      }
    ?>
    <!-- Removed logout link from the sidebar. Logout is available via the user dropdown in the header. -->
  </div>
</nav>
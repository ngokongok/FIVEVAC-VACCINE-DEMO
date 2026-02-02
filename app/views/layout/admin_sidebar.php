<?php
/* app/views/layout/admin_sidebar.php
 * Sidebar navigation for administrator users. This sidebar displays a
 * list of major administration functions on the left side of the
 * admin dashboard and related pages. Each link corresponds to a
 * use‑case such as viewing revenue statistics, managing user
 * accounts, maintaining vaccine information, managing branches,
 * managing partner links, updating stock and processing POS
 * transactions. The currently active link is highlighted based on
 * the current URL prefix. A logout link is also provided.  This
 * component is similar in structure to the staff sidebar but with
 * admin‑specific options.  (UC‑11)
 */
if (!defined('IN_APP')) {
    http_response_code(404);
    exit;
}

// Determine the current path from the `url` query parameter. This
// allows highlighting the active menu item. Trim any trailing slash
// for consistent prefix matching.
$currentPath = trim($_GET['url'] ?? '', '/');

// Helper closure to determine if a given prefix matches the
// beginning of the current path. When true, returns the CSS class
// 'active' to highlight the link.
$isActive = function (string $prefix) use ($currentPath): string {
    return (strpos($currentPath, $prefix) === 0) ? 'active' : '';
};
?>
<nav class="col-lg-3 mb-3">
  <div class="list-group">
    <a href="<?= base_url('admin/dashboard') ?>" class="list-group-item list-group-item-action <?= $isActive('admin/dashboard') ?>">
      <strong>Tổng quan</strong>
    </a>
    <a href="<?= base_url('accounts') ?>" class="list-group-item list-group-item-action <?= $isActive('accounts') ?>">
      Quản lý tài khoản
    </a>
    <a href="<?= base_url('inventory') ?>" class="list-group-item list-group-item-action <?= $isActive('inventory') ?>">
      Quản lý vắc xin
    </a>
    <a href="<?= base_url('branches') ?>" class="list-group-item list-group-item-action <?= $isActive('branches') ?>">
      Quản lý chi nhánh
    </a>
    <a href="<?= base_url('stock') ?>" class="list-group-item list-group-item-action <?= $isActive('stock') ?>">
      Quản lý tồn kho
    </a>
    <!-- Removed additional admin links (Liên kết, Tồn kho, POS) and logout from the sidebar -->
  </div>
</nav>
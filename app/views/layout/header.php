
<?php
/* app/views/layout/header.php (polished) */
// Prevent direct access to view files by ensuring the app is loaded through index.php
if (!defined('IN_APP')) {
    http_response_code(404);
    exit;
}
?>
<!doctype html>
<html lang="vi">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= APP_NAME ?></title>
    <!-- local lite first; CDN second if available -->
    <link href="<?= base_url('assets/css/bootstrap-lite.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/theme.css') ?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for interactive homepage elements -->
    <link href="<?= base_url('assets/css/custom.css') ?>" rel="stylesheet">
  </head>
  <body>
    <?php if (empty($hide_navbar)): ?>
    <nav class="navbar">
      <div class="container container-narrow d-flex align-items-center justify-content-between">
        <a class="navbar-brand d-flex align-items-center" href="<?= base_url('') ?>">
          <img src="<?= base_url('assets/images/logo_fivevac.jpg') ?>" alt="Fivevac logo">
          <span>Fivevac</span>
        </a>
        <!-- Navigation menu adapted to user roles -->
        <ul class="d-flex gap-3 list-unstyled mb-0">
          <!-- Always show home -->
          <li><a class="nav-link" href="<?= base_url('') ?>">Trang chủ</a></li>

          <!-- Guest links: browse vaccines, login & register -->
          <?php if (current_user_role() === 'guest'): ?>
            <!-- Guests can browse the vaccine catalog and are offered login/register links -->
            <li><a class="nav-link" href="<?= base_url('vaccines') ?>">Vắc xin</a></li>
            <li><a class="nav-link" href="<?= base_url('auth/form') ?>">Đăng nhập</a></li>
            <li><a class="nav-link" href="<?= base_url('auth/register_phone') ?>">Đăng ký</a></li>
          <?php endif; ?>

          <!-- Customer links -->
          <?php if (current_user_role() === 'customer'): ?>
            <!-- Logged-in customers can browse vaccines and request consultations -->
            <li><a class="nav-link" href="<?= base_url('vaccines') ?>">Vắc xin</a></li>
            <li><a class="nav-link" href="<?= base_url('consult/request') ?>">Tư vấn</a></li>
            <!-- Replace the old personal records link with linked profiles management -->
            <li><a class="nav-link" href="<?= base_url('linked_profiles') ?>">Quan hệ liên kết</a></li>
            <!-- Lịch hẹn đã được chuyển vào menu tài khoản -->
          <?php endif; ?>

          <!-- Staff links -->
          <?php if (current_user_role() === 'staff'): ?>
            <!-- For staff users, the main navigation is provided in the sidebar. Do not show
                 additional navigation links in the top bar. The user dropdown (below) will
                 still be rendered to allow access to the profile and logout. -->
          <?php endif; ?>

          <!-- Admin links -->
          <?php if (current_user_role() === 'admin'): ?>
            <li><a class="nav-link" href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
            <li><a class="nav-link" href="<?= base_url('inventory/update_stock') ?>">Tồn kho</a></li>
            <li><a class="nav-link" href="<?= base_url('accounts') ?>">Tài khoản</a></li>
            <li><a class="nav-link" href="<?= base_url('branches/add') ?>">Chi nhánh</a></li>
            <li><a class="nav-link" href="<?= base_url('links/add') ?>">Liên kết</a></li>
            <li><a class="nav-link" href="<?= base_url('transactions/search_pos') ?>">POS</a></li>
          <?php endif; ?>

          <!-- Logged-in users: show user dropdown instead of a plain logout link -->
          <?php if (current_user_role() !== 'guest'): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?= htmlspecialchars(current_user_name() ?? 'Tài khoản') ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                <li><a class="dropdown-item" href="<?= base_url('profile') ?>">Hồ sơ tài khoản</a></li>
                <?php if (current_user_role() === 'customer'): ?>
                  <li><a class="dropdown-item" href="<?= base_url('appointments') ?>">Lịch hẹn</a></li>
                  <li><a class="dropdown-item" href="<?= base_url('injections/history') ?>">Lịch sử tiêm</a></li>
                <?php endif; ?>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?= base_url('auth/logout') ?>">Đăng xuất</a></li>
              </ul>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>
    <?php endif; ?>
    <?php
    // When the navbar is hidden (typically for staff pages), we still want to
    // provide a user dropdown in the top-right corner. This ensures that
    // staff can access their profile or logout from any page without the
    // primary navigation menu. Only render this if there is a logged-in
    // user and the navbar is intentionally hidden.
    ?>
    <?php if (!empty($hide_navbar) && current_user_role() !== 'guest'): ?>
    <div class="container container-narrow mt-2 d-flex justify-content-end">
      <div class="dropdown">
        <a class="text-decoration-none dropdown-toggle" href="#" id="staffUserMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <?= htmlspecialchars(current_user_name() ?? 'Tài khoản') ?>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="staffUserMenu">
          <li><a class="dropdown-item" href="<?= base_url('profile') ?>">Hồ sơ tài khoản</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="<?= base_url('auth/logout') ?>">Đăng xuất</a></li>
        </ul>
      </div>
    </div>
    <?php endif; ?>
    <main class="container container-narrow my-3">

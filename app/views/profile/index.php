<?php
/*
 * View: profile/index.php
 *
 * Displays the current user's personal information and provides
 * navigation to edit details or change the password.
 */
?>
<div class="row">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-body">
        <h3 class="card-title mb-1">Hồ sơ cá nhân</h3>
        <p class="text-secondary">Thông tin tài khoản của bạn</p>
        <?php
        $role = current_user_role();
        if ($role === 'staff') {
          echo '<a href="' . base_url('staff/dashboard') . '" class="btn btn-outline-secondary mb-3">Quay về</a>';
        } elseif ($role === 'admin') {
          echo '<a href="' . base_url('admin/dashboard') . '" class="btn btn-outline-secondary mb-3">Quay về</a>';
        }
        ?>
        <!-- Flash messages -->
        <?php if($msg = flash_get('error')): ?>
          <div class="alert alert-danger" role="alert">
            <?= $msg ?>
          </div>
        <?php endif; ?>
        <?php if($msg = flash_get('info')): ?>
          <div class="alert alert-success" role="alert">
            <?= $msg ?>
          </div>
        <?php endif; ?>
        <?php if($user): ?>
          <table class="table">
            <tbody>
              <tr>
                <th>Họ và tên</th>
                <td><?= htmlspecialchars($user['HoVaTen']) ?></td>
              </tr>
              <tr>
                <th>Giới tính</th>
                <td><?= htmlspecialchars($user['GioiTinh'] ?? '') ?></td>
              </tr>
              <tr>
                <th>Địa chỉ</th>
                <td><?= htmlspecialchars($user['DiaChi'] ?? '') ?></td>
              </tr>
            </tbody>
          </table>
        <?php else: ?>
          <p>Không tìm thấy thông tin hồ sơ.</p>
        <?php endif; ?>
        <a href="<?= base_url('profile/edit') ?>" class="btn btn-primary me-2">Chỉnh sửa thông tin</a>
        <a href="<?= base_url('profile/change_password') ?>" class="btn btn-outline-secondary">Đổi mật khẩu</a>
      </div>
    </div>
  </div>
  <!-- Decorative image removed to keep the profile page clean -->
</div>
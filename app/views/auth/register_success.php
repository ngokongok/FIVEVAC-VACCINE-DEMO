<?php /* app/views/auth/register_success.php */ ?>
<!-- Registration success page: displays a success message and a link to the login page. -->
<div class="py-5" style="background-color: #eaf2ff; min-height: calc(100vh - 64px);">
  <div class="container">
    <?php if ($msg = flash_get('info')): ?>
      <div class="row justify-content-center mb-4">
        <div class="col-md-8">
          <div class="alert alert-success text-center mb-0">
            <?= htmlspecialchars($msg) ?>
          </div>
        </div>
      </div>
    <?php endif; ?>
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card shadow-sm border-0">
          <div class="card-body text-center">
            <h3 class="card-title mb-3">Đăng ký thành công</h3>
            <p class="mb-4">Tài khoản của bạn đã được tạo. Nhấn vào nút bên dưới để chuyển đến trang đăng nhập.</p>
            <a href="<?= base_url('auth/login') ?>" class="btn btn-primary">Đến trang Đăng nhập</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
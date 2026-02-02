<?php /* app/views/auth/reset_password.php */ ?>
<div class="row justify-content-center mt-5">
  <div class="col-md-6 col-lg-4">
    <div class="card shadow-sm border-0">
      <div class="card-body p-4">
        <h3 class="card-title mb-4 text-center">Đặt lại mật khẩu</h3>
        <?php if ($err = flash_get('error')): ?>
          <div class="alert alert-danger mb-3 text-center"><?= $err ?></div>
        <?php endif; ?>
        <?php if ($info = flash_get('info')): ?>
          <div class="alert alert-success mb-3 text-center"><?= $info ?></div>
        <?php endif; ?>
        <form method="post" action="<?= base_url('auth/reset_password' . (isset($_GET['staff']) ? '&staff=1' : '')) ?>">
          <?php csrf_field(); ?>
          <div class="mb-3">
            <label class="form-label">Mật khẩu mới</label>
            <input type="password" class="form-control" name="password" required placeholder="Nhập mật khẩu mới (ít nhất 8 ký tự)">
          </div>
          <div class="mb-3">
            <label class="form-label">Xác nhận mật khẩu</label>
            <input type="password" class="form-control" name="confirm_password" required placeholder="Nhập lại mật khẩu mới">
          </div>
          <button type="submit" class="btn btn-primary w-100">Cập nhật mật khẩu</button>
        </form>
      </div>
    </div>
  </div>
</div>
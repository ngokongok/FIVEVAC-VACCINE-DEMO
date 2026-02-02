<?php /* app/views/auth/register_phone.php */ ?>
<div class="row justify-content-center mt-5">
  <div class="col-md-6 col-lg-4">
    <div class="card shadow-sm border-0">
      <div class="card-body p-4">
        <h3 class="card-title mb-4 text-center">Đăng ký</h3>
        <?php if ($m = flash_get('error')): ?>
          <div class="alert alert-danger text-center"><?= $m ?></div>
        <?php endif; ?>
        <?php if ($info = flash_get('info')): ?>
          <div class="alert alert-success text-center"><?= $info ?></div>
        <?php endif; ?>
        <form method="post" action="<?= base_url('auth/register_phone') ?>">
          <?php csrf_field(); ?>
          <div class="mb-3">
            <label class="form-label">Số điện thoại</label>
            <input type="text" class="form-control form-control-lg" name="phone" required placeholder="090xxxxxxxx" value="<?= isset($submitted_phone) ? htmlspecialchars($submitted_phone) : '' ?>">
          </div>
          <button type="submit" class="btn btn-primary w-100">Gửi mã OTP</button>
        </form>
        <p class="text-center mt-3 mb-0">Đã có tài khoản? <a href="<?= base_url('auth/form') ?>">Đăng nhập</a></p>
      </div>
    </div>
  </div>
</div>
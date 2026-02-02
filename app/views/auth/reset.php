<?php /* app/views/auth/reset.php */ ?>
<div class="row justify-content-center mt-5">
  <div class="col-md-6 col-lg-4">
    <div class="card shadow-sm border-0">
      <div class="card-body p-4">
        <h3 class="card-title mb-4 text-center">Quên mật khẩu</h3>
        <?php if ($m = flash_get('error')): ?>
          <div class="alert alert-danger text-center mb-3"><?= $m ?></div>
        <?php endif; ?>
        <?php if ($info = flash_get('info')): ?>
          <div class="alert alert-success text-center mb-3"><?= $info ?></div>
        <?php endif; ?>
        <form method="post" action="<?= base_url('auth/reset' . (isset($_GET['staff']) ? '&staff=1' : '')) ?>">
          <?php csrf_field(); ?>
          <div class="mb-3">
            <label class="form-label">Số điện thoại</label>
            <input type="text" class="form-control form-control-lg" name="phone" required placeholder="090xxxxxxxx" value="<?= isset($submitted_phone) ? htmlspecialchars($submitted_phone) : '' ?>">
          </div>
          <button type="submit" class="btn btn-primary w-100">Gửi mã OTP</button>
        </form>
        <p class="text-center mt-3 mb-0">
          <?php if (!isset($_GET['staff'])): ?>
            Đã nhớ mật khẩu? <a href="<?= base_url('auth/form') ?>">Đăng nhập</a>
          <?php else: ?>
            Quay lại <a href="<?= base_url('auth/login&staff=1') ?>">Đăng nhập</a>
          <?php endif; ?>
        </p>
      </div>
    </div>
  </div>
</div>
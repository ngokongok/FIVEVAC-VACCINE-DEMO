<?php /* app/views/auth/set_password.php */ ?>
<div class="row justify-content-center mt-5">
  <div class="col-md-6 col-lg-4">
    <div class="card shadow-sm border-0">
      <div class="card-body p-4">
        <h3 class="card-title mb-4 text-center">Đặt mật khẩu</h3>
        <?php if ($err = flash_get('error')): ?>
          <div class="alert alert-danger mb-3 text-center"><?= $err ?></div>
        <?php endif; ?>
        <form method="post" action="<?= base_url('auth/set_password') ?>">
          <?php csrf_field(); ?>
          <div class="mb-3">
            <label class="form-label">Họ và tên</label>
            <input type="text" name="name" class="form-control form-control-lg" required placeholder="Họ và tên">
          </div>
          <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <!-- The placeholder text is kept generic; validation rules ensure minimum length -->
            <input type="password" name="password" class="form-control form-control-lg" required placeholder="Mật khẩu">
          </div>
          <div class="mb-4">
            <label class="form-label">Xác nhận mật khẩu</label>
            <input type="password" name="confirm_password" class="form-control form-control-lg" required placeholder="Nhập lại mật khẩu">
          </div>
          <button type="submit" class="btn btn-primary w-100">Tạo tài khoản</button>
        </form>
      </div>
    </div>
  </div>
</div>
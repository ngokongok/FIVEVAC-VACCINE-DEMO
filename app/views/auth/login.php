<div class="row">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-body">
        <?php if($m=flash_get('error')): ?><div class='alert alert-danger'><?= $m ?></div><?php endif; ?>
        <?php if($m=flash_get('info')): ?><div class='alert alert-success'><?= $m ?></div><?php endif; ?>
        <h3 class="card-title mb-1">Đăng nhập</h3>
        <!-- Removed UC labels for cleaner UI -->
        <?php verify_csrf(); ?>
            <form method="post" action="">
          <div class="mb-3">
            <label class="form-label">Số điện thoại</label>
            <!-- Accept phone number only for staff and customer logins. Legacy username field is removed -->
            <input type="tel" class="form-control" name="phone" required placeholder="09xxxxxxxx" value="<?= isset($submitted_phone) ? htmlspecialchars($submitted_phone) : '' ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" name="password" required>
          </div>
          <button class="btn btn-primary">Đăng nhập</button>
          <?php
            // Determine the correct reset link based on whether this is the staff login
            $resetUrl = isset($_GET['staff']) ? 'auth/reset&staff=1' : 'auth/reset';
          ?>
          <a class="btn btn-outline-secondary ms-2" href="<?= base_url($resetUrl) ?>">Quên mật khẩu</a>
        <?php csrf_field(); ?>
            </form>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <img class="img-fluid rounded shadow-sm" src="<?= base_url('assets/images/fivevac2.jpg') ?>" alt="Login">
  </div>
</div>
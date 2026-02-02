
<div class="row">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-body">
        <h3 class="card-title mb-1">Đổi mật khẩu</h3>
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
        <form method="post" action="">
          <div class="mb-3">
            <label class="form-label">Mật khẩu hiện tại</label>
            <input type="password" class="form-control" name="current" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Mật khẩu mới</label>
            <input type="password" class="form-control" name="new" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Xác nhận mật khẩu mới</label>
            <input type="password" class="form-control" name="confirm" required>
          </div>
          <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
          <?php csrf_field(); ?>
        </form>
      </div>
    </div>
  </div>
    <!-- Decorative image removed to simplify the UI -->
</div>
    

<div class="row">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-body">
        <h3 class="card-title mb-1">Chỉnh sửa thông tin cá nhân</h3>
        <!-- Display flash messages if any -->
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
            <label class="form-label">Họ và tên</label>
            <input type="text" class="form-control" name="HoVaTen" value="<?= htmlspecialchars($user['HoVaTen'] ?? '') ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Ngày sinh</label>
            <input type="date" class="form-control" name="NgaySinh" value="<?= htmlspecialchars($user['NgaySinh'] ?? '') ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Giới tính</label>
            <select name="GioiTinh" class="form-select" required>
              <option value="" disabled <?= empty($user['GioiTinh']) ? 'selected' : '' ?>>Chọn</option>
              <option value="Nam" <?= ($user['GioiTinh'] ?? '') === 'Nam' ? 'selected' : '' ?>>Nam</option>
              <option value="Nữ" <?= ($user['GioiTinh'] ?? '') === 'Nữ' ? 'selected' : '' ?>>Nữ</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <input type="text" class="form-control" name="DiaChi" value="<?= htmlspecialchars($user['DiaChi'] ?? '') ?>" required>
          </div>
          <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
          <?php csrf_field(); ?>
        </form>
      </div>
    </div>
  </div>
    <!-- Decorative image removed to simplify the UI -->
</div>
    
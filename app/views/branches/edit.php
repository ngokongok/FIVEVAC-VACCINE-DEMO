<?php
/* app/views/branches/edit.php
 * Allows administrators to edit an existing branch. The branch
 * code is displayed but cannot be changed. Fields for name,
 * address and status can be updated. (UC‑12.2)
 */
?>
<?php
$err  = flash_get('error');
$info = flash_get('info');
echo '<div class="row">';
include __DIR__ . '/../layout/admin_sidebar.php';
?>
<div class="col-lg-9">
  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-body">
          <h3 class="card-title mb-1"><?= htmlspecialchars($title ?? 'Chỉnh sửa chi nhánh') ?></h3>
          <?php
          if ($err) {
              echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($err) . '</div>';
          }
          if ($info) {
              echo '<div class="alert alert-success" role="alert">' . htmlspecialchars($info) . '</div>';
          }
          verify_csrf();
          ?>
          <form method="post" action="#">
            <input type="hidden" name="id" value="<?= htmlspecialchars($branch['MaChiNhanh']) ?>">
            <div class="mb-3">
              <label class="form-label">Mã chi nhánh</label>
              <input type="text" class="form-control" value="<?= htmlspecialchars($branch['MaChiNhanh']) ?>" disabled>
            </div>
            <div class="mb-3">
              <label class="form-label">Tên chi nhánh</label>
              <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($branch['TenChiNhanh']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Địa chỉ</label>
              <textarea class="form-control" name="address" rows="3" required><?= htmlspecialchars($branch['DiaChi']) ?></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Trạng thái hoạt động</label>
              <select name="status" class="form-select">
                <option value="Hoạt động" <?= ($branch['TrangThaiHD'] ?? '') === 'Hoạt động' ? 'selected' : '' ?>>Hoạt động</option>
                <option value="Ngừng hoạt động" <?= ($branch['TrangThaiHD'] ?? '') === 'Ngừng hoạt động' ? 'selected' : '' ?>>Ngừng hoạt động</option>
              </select>
            </div>
            <button class="btn btn-primary">Lưu thay đổi</button>
            <?php csrf_field(); ?>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <!-- Decorative image removed -->
    </div>
  </div>
</div>
<?php echo '</div>'; ?>
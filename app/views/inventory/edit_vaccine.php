<?php
/* app/views/inventory/edit_vaccine.php
 * Allows administrators to edit an existing vaccine's details. The
 * vaccine code is displayed but cannot be changed. On
 * submission, the controller validates and updates the record.
 * (UC‑11.2)
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
          <h3 class="card-title mb-1"><?= htmlspecialchars($title ?? 'Chỉnh sửa vắc xin') ?></h3>
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
            <input type="hidden" name="id" value="<?= htmlspecialchars($vaccine['MaVacXin']) ?>">
            <div class="mb-3">
              <label class="form-label">Mã vắc xin</label>
              <input type="text" class="form-control" value="<?= htmlspecialchars($vaccine['MaVacXin']) ?>" disabled>
            </div>
            <div class="mb-3">
              <label class="form-label">Tên vắc xin</label>
              <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($vaccine['TenVacXin']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Ngày sản xuất (NSX)</label>
              <input type="date" class="form-control" name="nsx" value="<?= htmlspecialchars($vaccine['NSX']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Hạn sử dụng (HSD)</label>
              <input type="date" class="form-control" name="hsd" value="<?= htmlspecialchars($vaccine['HSD']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Giá (VNĐ)</label>
              <input type="number" step="0.01" min="0" class="form-control" name="price" value="<?= htmlspecialchars($vaccine['Gia']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Mô tả</label>
              <textarea class="form-control" name="desc" rows="3"><?= htmlspecialchars($vaccine['Mota']) ?></textarea>
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
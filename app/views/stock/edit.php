<?php
/* app/views/stock/edit.php
 * Allows administrators to adjust the current quantity of a stock
 * detail record. Branch and vaccine cannot be changed; the used
 * quantity is displayed read‑only. Administrators can specify the
 * number of doses being added (Nhập vào) or removed (Xuất ra). The
 * controller ensures the quantity remains non‑negative and not
 * less than the used quantity. (UC 13.2)
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
          <h3 class="card-title mb-1"><?= htmlspecialchars($title ?? 'Chỉnh sửa chi tiết tồn kho') ?></h3>
          <?php if ($err): ?>
            <div class="alert alert-danger" role="alert">
              <?= htmlspecialchars($err) ?>
            </div>
          <?php endif; ?>
          <?php if ($info): ?>
            <div class="alert alert-success" role="alert">
              <?= htmlspecialchars($info) ?>
            </div>
          <?php endif; ?>
          <?php verify_csrf(); ?>
          <form method="post" action="#">
            <input type="hidden" name="id" value="<?= htmlspecialchars($item['MaCTTK']) ?>">
            <div class="mb-3">
              <label class="form-label">Mã CTTK</label>
              <input type="text" class="form-control" value="<?= htmlspecialchars($item['MaCTTK']) ?>" disabled>
            </div>
            <div class="mb-3">
              <label class="form-label">Chi nhánh</label>
              <input type="text" class="form-control" value="<?= htmlspecialchars($item['TenChiNhanh'] . ' (' . $item['MaChiNhanh'] . ')') ?>" disabled>
            </div>
            <div class="mb-3">
              <label class="form-label">Vắc xin</label>
              <input type="text" class="form-control" value="<?= htmlspecialchars($item['TenVacXin'] . ' (' . $item['MaVacXin'] . ')') ?>" disabled>
            </div>
            <div class="mb-3">
              <label class="form-label">Số lượng hiện tại</label>
              <input type="number" class="form-control" value="<?= htmlspecialchars($item['SoLuongHienTai']) ?>" disabled>
            </div>
            <div class="mb-3">
              <label class="form-label">Số lượng đã sử dụng</label>
              <input type="number" class="form-control" value="<?= htmlspecialchars($item['SoLuongDaSuDung']) ?>" disabled>
            </div>
            <div class="mb-3">
              <label class="form-label">Nhập vào</label>
              <input type="number" class="form-control" name="nhap" min="0" step="1" value="0">
            </div>
            <div class="mb-3">
              <label class="form-label">Xuất ra</label>
              <input type="number" class="form-control" name="xuat" min="0" step="1" value="0">
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
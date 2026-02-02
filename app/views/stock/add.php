<?php
/* app/views/stock/add.php
 * Form for administrators to add a new inventory detail record. Users
 * select a branch and a vaccine from dropdowns and specify the initial
 * quantity on hand. The used quantity is set to zero by default in
 * the controller. (UC 13.1)
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
          <h3 class="card-title mb-1"><?= htmlspecialchars($title ?? 'Thêm chi tiết tồn kho') ?></h3>
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
            <div class="mb-3">
              <label class="form-label">Chi nhánh</label>
              <select name="branch" class="form-select" required>
                <option value="">-- Chọn chi nhánh --</option>
                <?php foreach ($branches as $br): ?>
                  <option value="<?= htmlspecialchars($br['MaChiNhanh']) ?>">
                    <?= htmlspecialchars($br['TenChiNhanh'] . ' (' . $br['MaChiNhanh'] . ')') ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Vắc xin</label>
              <select name="vaccine" class="form-select" required>
                <option value="">-- Chọn vắc xin --</option>
                <?php foreach ($vaccines as $vx): ?>
                  <option value="<?= htmlspecialchars($vx['MaVacXin']) ?>">
                    <?= htmlspecialchars($vx['TenVacXin'] . ' (' . $vx['MaVacXin'] . ')') ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Số lượng hiện tại</label>
              <input type="number" class="form-control" name="quantity" min="0" step="1" placeholder="Nhập số lượng" required>
            </div>
            <button class="btn btn-primary">Thêm</button>
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
<?php
/* app/views/inventory/index.php
 * Displays a list of vaccines to the administrator. Each row shows
 * the vaccine code, name, manufacture date, expiry date, price and
 * description. A button above the table allows adding a new
 * vaccine. Edit and delete actions are provided per row. (UC‑11.1)
 */
?>
<?php
$err  = flash_get('error');
$info = flash_get('info');
// Start the row and include the admin sidebar
echo '<div class="row">';
include __DIR__ . '/../layout/admin_sidebar.php';
?>
<div class="col-lg-9">
  <div class="card mb-3">
    <div class="card-body">
      <h3 class="card-title mb-1"><?= htmlspecialchars($title ?? 'Quản lý vắc xin') ?></h3>
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
      <a href="<?= base_url('inventory/add_vaccine') ?>" class="btn btn-primary mb-3">Thêm vắc xin</a>
      <?php if (empty($vaccines)): ?>
        <p>Không có vắc xin nào.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead>
              <tr>
                <th>Mã VX</th>
                <th>Tên vắc xin</th>
                <th>NSX</th>
                <th>HSD</th>
                <th>Giá (VNĐ)</th>
                <th>Mô tả</th>
                <th class="text-center">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($vaccines as $vx): ?>
                <tr>
                  <td><?= htmlspecialchars($vx['MaVacXin']) ?></td>
                  <td><?= htmlspecialchars($vx['TenVacXin']) ?></td>
                  <td><?= htmlspecialchars($vx['NSX']) ?></td>
                  <td><?= htmlspecialchars($vx['HSD']) ?></td>
                  <td><?= number_format($vx['Gia'], 0, ',', '.') ?></td>
                  <td><?= htmlspecialchars($vx['Mota']) ?></td>
                  <td class="text-center">
                    <a class="btn btn-sm btn-outline-primary" href="<?= base_url('inventory/edit_vaccine&id=' . urlencode($vx['MaVacXin'])) ?>">Chỉnh sửa</a>
                    <a class="btn btn-sm btn-outline-danger" href="<?= base_url('inventory/delete_vaccine&id=' . urlencode($vx['MaVacXin'])) ?>">Xóa</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php echo '</div>'; ?>
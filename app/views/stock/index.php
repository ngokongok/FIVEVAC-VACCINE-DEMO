<?php
/* app/views/stock/index.php
 * Displays a list of inventory detail records (chitiettonkho) for
 * administrators. Each row shows the record code, branch name,
 * vaccine name, current quantity and used quantity. An 'Add' button
 * allows administrators to create a new record. (UC 13)
 */
?>
<?php
$err  = flash_get('error');
$info = flash_get('info');
// Layout: sidebar on the left, main content on the right
echo '<div class="row">';
include __DIR__ . '/../layout/admin_sidebar.php';
?>
<div class="col-lg-9">
  <div class="card mb-3">
    <div class="card-body">
      <h3 class="card-title mb-1"><?= htmlspecialchars($title ?? 'Quản lý tồn kho') ?></h3>
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
      <a href="<?= base_url('stock/add') ?>" class="btn btn-primary mb-3">Thêm chi tiết tồn kho</a>
      <?php if (empty($items)): ?>
        <p>Không có dữ liệu tồn kho.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead>
              <tr>
                <th>Mã CTTK</th>
                <th>Chi nhánh</th>
                <th>Vắc xin</th>
                <th>Số lượng hiện tại</th>
                <th>Số lượng đã sử dụng</th>
                <th class="text-center">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($items as $it): ?>
                <tr>
                  <td><?= htmlspecialchars($it['MaCTTK']) ?></td>
                  <td><?= htmlspecialchars($it['TenChiNhanh'] . ' (' . $it['MaChiNhanh'] . ')') ?></td>
                  <td><?= htmlspecialchars($it['TenVacXin'] . ' (' . $it['MaVacXin'] . ')') ?></td>
                  <td><?= htmlspecialchars($it['SoLuongHienTai']) ?></td>
                  <td><?= htmlspecialchars($it['SoLuongDaSuDung']) ?></td>
                  <td class="text-center">
                    <a class="btn btn-sm btn-outline-primary" href="<?= base_url('stock/edit&id=' . urlencode($it['MaCTTK'])) ?>">Chỉnh sửa</a>
                    <a class="btn btn-sm btn-outline-danger" href="<?= base_url('stock/delete&id=' . urlencode($it['MaCTTK'])) ?>">Xóa</a>
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
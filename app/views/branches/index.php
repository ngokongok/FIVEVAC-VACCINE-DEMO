<?php
/* app/views/branches/index.php
 * Displays a list of branches for administrators. Includes a button
 * to add a new branch and provides edit/delete actions for each
 * entry. (UC‑12)
 */
?>
<?php
$err  = flash_get('error');
$info = flash_get('info');
echo '<div class="row">';
include __DIR__ . '/../layout/admin_sidebar.php';
?>
<div class="col-lg-9">
  <div class="card mb-3">
    <div class="card-body">
      <h3 class="card-title mb-1"><?= htmlspecialchars($title ?? 'Quản lý chi nhánh') ?></h3>
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
      <a href="<?= base_url('branches/add') ?>" class="btn btn-primary mb-3">Thêm chi nhánh</a>
      <?php if (empty($branches)): ?>
        <p>Không có chi nhánh nào.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead>
              <tr>
                <th>Mã CN</th>
                <th>Tên chi nhánh</th>
                <th>Địa chỉ</th>
                <th>Trạng thái</th>
                <th class="text-center">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($branches as $br): ?>
                <tr>
                  <td><?= htmlspecialchars($br['MaChiNhanh']) ?></td>
                  <td><?= htmlspecialchars($br['TenChiNhanh']) ?></td>
                  <td><?= htmlspecialchars($br['DiaChi']) ?></td>
                  <td><?= htmlspecialchars($br['TrangThaiHD'] ?? '') ?></td>
                  <td class="text-center">
                    <a class="btn btn-sm btn-outline-primary" href="<?= base_url('branches/edit&id=' . urlencode($br['MaChiNhanh'])) ?>">Chỉnh sửa</a>
                    <a class="btn btn-sm btn-outline-danger" href="<?= base_url('branches/delete&id=' . urlencode($br['MaChiNhanh'])) ?>">Xóa</a>
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
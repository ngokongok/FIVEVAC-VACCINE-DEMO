<?php
/* app/views/stock/delete.php
 * Confirmation page for deleting an inventory detail record. The
 * administrator is informed that deletion is only allowed when
 * there is no remaining or used quantity. The branch and vaccine
 * names are displayed for clarity. (UC 13.3)
 */
?>
<?php
echo '<div class="row">';
include __DIR__ . '/../layout/admin_sidebar.php';
?>
<div class="col-lg-9">
  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-body">
          <h3 class="card-title mb-1"><?= htmlspecialchars($title ?? 'Xóa chi tiết tồn kho') ?></h3>
          <p>Bạn có chắc muốn xóa bản ghi tồn kho này?</p>
          <ul class="mb-3">
            <li>Mã CTTK: <strong><?= htmlspecialchars($item['MaCTTK']) ?></strong></li>
            <li>Chi nhánh: <strong><?= htmlspecialchars($item['TenChiNhanh'] . ' (' . $item['MaChiNhanh'] . ')') ?></strong></li>
            <li>Vắc xin: <strong><?= htmlspecialchars($item['TenVacXin'] . ' (' . $item['MaVacXin'] . ')') ?></strong></li>
          </ul>
          <?php verify_csrf(); ?>
          <form method="post" action="#" class="d-inline">
            <input type="hidden" name="id" value="<?= htmlspecialchars($item['MaCTTK']) ?>">
            <input type="hidden" name="confirm" value="yes">
            <button type="submit" class="btn btn-danger">Xác nhận</button>
            <?php csrf_field(); ?>
          </form>
          <!-- Append cancel=1 to the return link so that the index page can display a cancellation notice -->
          <a href="<?= base_url('stock') . '&cancel=1' ?>" class="btn btn-secondary ms-2">Quay về</a>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <!-- Decorative image removed -->
    </div>
  </div>
</div>
<?php echo '</div>'; ?>
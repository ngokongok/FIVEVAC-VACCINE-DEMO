<?php
/* app/views/inventory/delete_vaccine.php
 * Confirmation prompt for deleting a vaccine. The administrator is
 * asked whether they wish to remove the vaccine record. The
 * controller will handle actual deletion. (UC‑11.3)
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
          <h3 class="card-title mb-1"><?= htmlspecialchars($title ?? 'Xóa vắc xin') ?></h3>
          <p>Bạn có chắc muốn xóa vắc xin này khỏi hệ thống không?</p>
          <?php verify_csrf(); ?>
          <form method="post" action="#" class="d-inline">
            <input type="hidden" name="id" value="<?= htmlspecialchars($code) ?>">
            <input type="hidden" name="confirm" value="yes">
            <button type="submit" class="btn btn-danger">Xác nhận</button>
            <?php csrf_field(); ?>
          </form>
          <!-- Append cancel=1 to the return link so that the index page can display a cancellation notice -->
          <a href="<?= base_url('inventory') . '&cancel=1' ?>" class="btn btn-secondary ms-2">Quay về</a>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <!-- Decorative image removed -->
    </div>
  </div>
</div>
<?php echo '</div>'; ?>
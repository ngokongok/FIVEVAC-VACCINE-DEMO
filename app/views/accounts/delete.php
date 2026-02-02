<?php
/* app/views/accounts/delete.php
 * Confirmation prompt for deleting an account. The administrator is
 * asked whether they wish to remove the account and all associated
 * data. Two options are presented: confirm deletion or cancel and
 * return to the account list. (UC‑10.3)
 */
?>
<?php
// Begin outer row for sidebar and content
echo '<div class="row">';
// Include admin sidebar
include __DIR__ . '/../layout/admin_sidebar.php';
?>
<div class="col-lg-9">
  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-body">
          <h3 class="card-title mb-1"><?= htmlspecialchars($title ?? 'Xóa tài khoản') ?></h3>
          <!-- Removed UC labels for cleaner UI -->
          <p>Bạn có chắc muốn xóa tất cả nội dung liên quan đến tài khoản này không?</p>
          <?php verify_csrf(); ?>
          <form method="post" action="#" class="d-inline">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
            <input type="hidden" name="confirm" value="yes">
            <button type="submit" class="btn btn-danger">Xác nhận</button>
            <?php csrf_field(); ?>
          </form>
          <!-- Append cancel=1 to the return link so that the index page can display a cancellation notice -->
          <a href="<?= base_url('accounts') . '&cancel=1' ?>" class="btn btn-secondary ms-2">Quay về</a>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <!-- Decorative image removed to declutter the confirmation page -->
    </div>
  </div>
</div> <!-- .col-lg-9 -->
<?php
// Close outer row
echo '</div>';
?>
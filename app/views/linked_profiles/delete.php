<?php
/* app/views/linked_profiles/delete.php
 * Confirmation page for removing a linked profile. The user must
 * explicitly confirm deletion via a POST request. Deletion is only
 * allowed if the profile does not belong to the current user.
 */
?>

<?php
$err  = flash_get('error');
$info = flash_get('info');
?>
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

<div class="card">
  <div class="card-body">
    <h3 class="card-title mb-3">Xóa quan hệ liên kết</h3>
    <p>Bạn có chắc chắn muốn xóa liên kết với hồ sơ này không?</p>
    <?php verify_csrf(); ?>
    <form method="post" action="<?= base_url('linked_profiles/delete/' . htmlspecialchars($kh_id ?? '')) ?>">
      <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
      <!-- Include cancel=1 parameter so the index page can display a cancellation notice -->
      <a href="<?= base_url('linked_profiles') . '&cancel=1' ?>" class="btn btn-secondary">Hủy</a>
      <?php csrf_field(); ?>
    </form>
  </div>
</div>
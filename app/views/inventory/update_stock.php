
<?php
// Begin a row for the sidebar and content. Dynamically choose the
// sidebar based on user role: administrators use the admin sidebar,
// staff members use the staff sidebar. (UC‑11)
echo '<div class="row">';
if (current_user_role() === 'admin') {
    include __DIR__ . '/../layout/admin_sidebar.php';
} else {
    include __DIR__ . '/../layout/staff_sidebar.php';
}
?>
<div class="col-lg-9">
  <div class="card mb-3">
    <div class="card-body">
      <?php if($m=flash_get('error')): ?><div class='alert alert-danger'><?= $m ?></div><?php endif; ?>
      <?php if($m=flash_get('success')): ?><div class='alert alert-success'><?= $m ?></div><?php endif; ?>
      <h3 class="card-title mb-1"><?= isset($title) ? $title : 'Cập nhật tồn kho' ?></h3>
      <?php verify_csrf(); ?>
      <!-- Form to update stock levels -->
      <form method="post" action="#">
        <div class="mb-3">
          <label class="form-label">Chi nhánh</label>
          <input type="text" class="form-control" name="branch" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Mã vắc xin</label>
          <input type="text" class="form-control" name="code" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Số lượng (+/-)</label>
          <input type="number" class="form-control" name="qty" required>
        </div>
        <button class="btn btn-primary">Cập nhật</button>
        <?php csrf_field(); ?>
      </form>
    </div>
  </div>
  <!-- Decorative image removed -->
</div>
<?php echo '</div>'; ?>
    

<?php
echo '<div class="row">';
include __DIR__ . '/../layout/staff_sidebar.php';
?>
<div class="col-lg-9">
  <div class="card mb-3">
    <div class="card-body">
      <h3 class="card-title mb-1"><?= isset($title) ? $title : 'Tra cứu đơn hàng POS' ?></h3>
      <?php verify_csrf(); ?>
      <!-- Placeholder search form for POS orders -->
      <form method="post" action="#">
        <div class="mb-3">
          <label class="form-label">Từ khóa / Bộ lọc</label>
          <input type="text" class="form-control" name="q" required>
        </div>
        <button class="btn btn-primary">Tìm kiếm</button>
        <?php csrf_field(); ?>
      </form>
    </div>
  </div>
  <!-- Decorative image removed -->
</div>
<?php echo '</div>'; ?>
    
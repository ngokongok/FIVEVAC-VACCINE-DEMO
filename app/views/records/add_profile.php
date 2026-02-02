
<?php
echo '<div class="row">';
include __DIR__ . '/../layout/staff_sidebar.php';
?>
<div class="col-lg-9">
  <div class="card mb-3">
    <div class="card-body">
      <h3 class="card-title mb-1"><?= isset($title) ? $title : 'Thêm hồ sơ tiêm chủng' ?></h3>
      <?php
      $err  = flash_get('error');
      $info = flash_get('info');
      if ($err) {
          echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($err) . '</div>';
      }
      if ($info) {
          echo '<div class="alert alert-success" role="alert">' . htmlspecialchars($info) . '</div>';
      }
      ?>
      <!-- Form to add a new vaccination profile -->
      <form method="post" action="#">
        <div class="mb-3">
          <label class="form-label">Họ tên</label>
          <input type="text" class="form-control" name="name" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Ngày sinh</label>
          <input type="date" class="form-control" name="dob" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Số điện thoại</label>
          <!-- Require a 10-digit Vietnamese phone starting with 0 -->
          <input type="tel" class="form-control" name="phone" pattern="0\d{9}" title="Số điện thoại phải gồm 10 chữ số và bắt đầu bằng 0" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Giới tính</label>
          <select name="gender" class="form-select">
            <option value="" selected disabled>Chọn giới tính (tuỳ chọn)</option>
            <option value="Nam">Nam</option>
            <option value="Nữ">Nữ</option>
            <option value="Khác">Khác</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Địa chỉ</label>
          <input type="text" class="form-control" name="address" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Bệnh nền (nếu có)</label>
          <input type="text" class="form-control" name="disease">
        </div>
        <button class="btn btn-primary">Tạo hồ sơ</button>
        <?php csrf_field(); ?>
      </form>
    </div>
  </div>
  <!-- Decorative image removed -->
</div>
<?php echo '</div>'; ?>
    
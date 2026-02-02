<?php
/* app/views/accounts/add.php
 * Form for administrators to add a new account. Collects basic
 * personal information (name, date of birth, gender, address), phone
 * number, password and role. The controller will perform validation
 * and insertion into the database on submission. (UC‑10.1)
 */
?>
<?php
$err  = flash_get('error');
$info = flash_get('info');
?>
<?php
// Begin the outer row for the sidebar and main content
echo '<div class="row">';
// Include the admin sidebar navigation
include __DIR__ . '/../layout/admin_sidebar.php';
?>
<div class="col-lg-9">
  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-body">
          <h3 class="card-title mb-1"><?= htmlspecialchars($title ?? 'Thêm tài khoản') ?></h3>
          <!-- Removed UC labels for cleaner UI -->
          <?php
          // Show any flash messages
          if ($err) {
              echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($err) . '</div>';
          }
          if ($info) {
              echo '<div class="alert alert-success" role="alert">' . htmlspecialchars($info) . '</div>';
          }
          // CSRF protection token
          verify_csrf();
          ?>
          <form method="post" action="#">
            <div class="mb-3">
              <label class="form-label">Họ và tên</label>
              <input type="text" class="form-control" name="name" placeholder="Nhập họ và tên" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Ngày sinh</label>
              <input type="date" class="form-control" name="dob" >
            </div>
            <div class="mb-3">
              <label class="form-label">Giới tính</label>
              <select name="gender" class="form-select">
                <option value="" selected>Chọn giới tính (tuỳ chọn)</option>
                <option value="Nam">Nam</option>
                <option value="Nữ">Nữ</option>
                <option value="Khác">Khác</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Địa chỉ</label>
              <input type="text" class="form-control" name="address" placeholder="Nhập địa chỉ" >
            </div>
            <div class="mb-3">
              <label class="form-label">Số điện thoại</label>
              <input type="tel" class="form-control" name="phone" pattern="0\d{9}" title="Số điện thoại phải gồm 10 chữ số và bắt đầu bằng 0" placeholder="Nhập số điện thoại" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Mật khẩu</label>
              <input type="password" class="form-control" name="password" placeholder="Mật khẩu ít nhất 8 ký tự" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Vai trò</label>
              <select name="role" class="form-select" required>
                <option value="" selected disabled>Chọn vai trò</option>
                <option value="admin">Quản trị</option>
                <option value="doctor">Bác sĩ</option>
                <option value="cskh">CSKH</option>
                <option value="member">Khách hàng</option>
              </select>
            </div>
            <button class="btn btn-primary">Tạo tài khoản</button>
            <?php csrf_field(); ?>
          </form>
        </div>
      </div>
    </div>
    <!-- Decorative image removed to declutter the form -->
  </div>
</div> <!-- .col-lg-9 -->
<?php
// Close the outer row
echo '</div>';
?>
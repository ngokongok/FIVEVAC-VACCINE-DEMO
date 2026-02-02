<?php
/* app/views/accounts/edit.php
 * Allows administrators to edit an existing account's phone number and
 * password. The account ID is displayed but cannot be modified. If
 * the password field is left blank, the existing password remains
 * unchanged. (UC‑10.2)
 */
?>
<?php
$err  = flash_get('error');
$info = flash_get('info');
?>
<?php
// Begin outer row for sidebar and main content
echo '<div class="row">';
// Include admin sidebar navigation
include __DIR__ . '/../layout/admin_sidebar.php';
?>
<div class="col-lg-9">
  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-body">
          <h3 class="card-title mb-1"><?= htmlspecialchars($title ?? 'Chỉnh sửa tài khoản') ?></h3>
          <!-- Removed UC labels for cleaner UI -->
          <?php
          if ($err) {
              echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($err) . '</div>';
          }
          if ($info) {
              echo '<div class="alert alert-success" role="alert">' . htmlspecialchars($info) . '</div>';
          }
          // CSRF token for form submission
          verify_csrf();
          ?>
          <form method="post" action="#">
            <input type="hidden" name="id" value="<?= htmlspecialchars($account['MaTK']) ?>">
            <div class="mb-3">
              <label class="form-label">Mã tài khoản</label>
              <input type="text" class="form-control" value="<?= htmlspecialchars($account['MaTK']) ?>" disabled>
            </div>
            <div class="mb-3">
              <label class="form-label">Số điện thoại</label>
              <input type="tel" class="form-control" name="phone" pattern="0\d{9}" title="Số điện thoại phải gồm 10 chữ số và bắt đầu bằng 0" value="<?= htmlspecialchars($account['SDT']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Mật khẩu mới (để trống nếu không đổi)</label>
              <input type="password" class="form-control" name="password" placeholder="Ít nhất 8 ký tự">
            </div>
            <button class="btn btn-primary">Lưu thay đổi</button>
            <?php csrf_field(); ?>
          </form>
        </div>
      </div>
    </div>
    <!-- Decorative image removed to declutter the form -->
  </div>
</div> <!-- .col-lg-9 -->
<?php
// Close outer row
echo '</div>';
?>
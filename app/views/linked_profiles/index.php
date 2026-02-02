<?php
/* app/views/linked_profiles/index.php
 * Management interface for linked vaccination profiles (hồ sơ liên kết).
 *
 * Customers can add new linked profiles by entering a phone number
 * associated with an existing record in the `thongtinkhachhang` table.
 * The system validates the phone, sends an OTP, and requires the
 * customer to enter it. Once verified, the profile is added to the
 * customer's list. Existing links are displayed with options to view
 * details or remove them. The user's own profile is automatically
 * linked and cannot be removed.
 */
?>

<?php
// Retrieve flash messages
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

<!-- Section: Add or verify linked profile -->
<div class="card mb-4">
  <div class="card-body">
    <?php if (!($inOtpStep ?? false)): ?>
      <h3 class="card-title mb-1">Thêm quan hệ liên kết</h3>
      <?php verify_csrf(); ?>
      <form method="post" action="<?= base_url('linked_profiles') ?>">
        <div class="mb-3">
          <label class="form-label">Số điện thoại người cần liên kết</label>
          <input type="text" class="form-control" name="phone" placeholder="Nhập số điện thoại 10 chữ số" value="<?= isset($submitted_phone) ? htmlspecialchars($submitted_phone) : '' ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Gửi mã xác thực</button>
        <?php csrf_field(); ?>
      </form>
    <?php else: ?>
      <h3 class="card-title mb-1">Xác thực liên kết</h3>
      <p class="text-secondary">Nhập mã xác thực đã gửi tới số điện thoại</p>
      <?php verify_csrf(); ?>
      <form method="post" action="<?= base_url('linked_profiles') ?>" id="otp-form">
        <div class="mb-3">
          <label class="form-label">Mã xác thực (6 chữ số)</label>
          <input type="text" class="form-control" name="otp" maxlength="6" pattern="\d{6}" placeholder="Nhập mã OTP" value="<?= isset($submitted_otp) ? htmlspecialchars($submitted_otp) : '' ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Xác thực</button>
        <?php csrf_field(); ?>
      </form>
      <div class="mt-2">
        <small id="otp-timer" class="text-muted">Mã hết hạn trong <span id="countdown"></span></small>
      </div>
      <p class="mt-2 mb-0">
        <a href="<?= base_url('linked_profiles/resend') ?>">Chưa nhận được mã? Gửi lại</a>
        |
        <a href="<?= base_url('linked_profiles/reset') ?>">Nhập lại SĐT</a>
      </p>
      <!-- Countdown timer script similar to auth/verify_otp -->
      <script>
      document.addEventListener('DOMContentLoaded', () => {
        // Only run countdown if expiry exists
        const expiry = <?= isset($_SESSION['link_otp_expiry']) ? $_SESSION['link_otp_expiry'] : 0 ?>;
        const serverNow = <?= time() ?>;
        let timeLeft = Math.max(expiry - serverNow, 0);
        const timerEl = document.getElementById('countdown');
        const formEl = document.getElementById('otp-form');
        if (timerEl) timerEl.textContent = timeLeft.toString();
        const intervalId = setInterval(() => {
          timeLeft--;
          if (timeLeft <= 0) {
            clearInterval(intervalId);
            if (timerEl) timerEl.textContent = '0';
            // Disable form when expired
            if (formEl) {
              formEl.querySelector('input[name="otp"]').setAttribute('disabled', 'disabled');
              formEl.querySelector('button[type="submit"]').setAttribute('disabled', 'disabled');
            }
          } else {
            if (timerEl) timerEl.textContent = timeLeft.toString();
          }
        }, 1000);
      });
      </script>
    <?php endif; ?>
  </div>
</div>

<!-- Section: Existing linked profiles -->
<div class="card">
  <div class="card-body">
    <h3 class="card-title mb-1">Hồ sơ đã liên kết</h3>
    <?php if (empty($linked)): ?>
      <p>Bạn chưa có hồ sơ liên kết nào.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-striped align-middle">
          <thead>
            <tr>
              <th scope="col">Mã hồ sơ</th>
              <th scope="col">Họ tên</th>
              <th scope="col">Ngày sinh</th>
              <th scope="col">Giới tính</th>
              <th scope="col">Địa chỉ</th>
              <th scope="col">Số điện thoại</th>
              <th scope="col" class="text-center">Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Determine the user's own phone to disable deletion of self profile
            $dbtmp = new Database();
            $dbtmp->query("SELECT SDT FROM taikhoan WHERE MaND = :mand LIMIT 1");
            $dbtmp->bind(':mand', current_user_id());
            $selfAcc = $dbtmp->single();
            $selfPhone = $selfAcc['SDT'] ?? null;
            ?>
            <?php foreach ($linked as $row): ?>
              <tr>
                <td><?= htmlspecialchars($row['MaKH']) ?></td>
                <td><?= htmlspecialchars($row['HoTen']) ?></td>
                <td><?= htmlspecialchars($row['NgaySinh']) ?></td>
                <td><?= htmlspecialchars($row['GioiTinh']) ?></td>
                <td><?= htmlspecialchars($row['DiaChi']) ?></td>
                <td><?= htmlspecialchars($row['SDT']) ?></td>
                <td class="text-center">
                  <a class="btn btn-sm btn-outline-primary" href="<?= base_url('linked_profiles/detail/' . $row['MaKH']) ?>">Chi tiết</a>
                  <?php if ($selfPhone && $selfPhone === $row['SDT']): ?>
                    <!-- Disable delete button for self profile -->
                    <button class="btn btn-sm btn-secondary" disabled>Xóa</button>
                  <?php else: ?>
                    <a class="btn btn-sm btn-outline-danger" href="<?= base_url('linked_profiles/delete/' . $row['MaKH']) ?>">Xóa</a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php
/* app/views/accounts/index.php
 * Displays a list of all system accounts to the administrator. A button
 * at the top allows creating a new account. Each row shows details
 * pulled from both the `taikhoan` and `nguoidung` tables and
 * includes actions to edit or delete the account. Flash messages
 * convey success or error states from previous operations. (UC‑10)
 */
?>

<?php
// Retrieve flash messages
$err  = flash_get('error');
$info = flash_get('info');
?>
<?php
// Begin a Bootstrap row to include the admin sidebar on the left.
echo '<div class="row">';
// Include the admin sidebar navigation
include __DIR__ . '/../layout/admin_sidebar.php';
?>
<div class="col-lg-9">
  <div class="card mb-3">
    <div class="card-body">
      <h3 class="card-title mb-1"><?= htmlspecialchars($title ?? 'Quản lý tài khoản') ?></h3>
      <!-- Removed UC labels for cleaner UI -->
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
      <!-- Add account button -->
      <a href="<?= base_url('accounts/add') ?>" class="btn btn-primary mb-3">Thêm tài khoản</a>
      <!-- Accounts table -->
      <?php if (empty($accounts)): ?>
        <p>Không có tài khoản nào trong hệ thống.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead>
              <tr>
                <th scope="col">Mã TK</th>
                <th scope="col">Mã ND</th>
                <th scope="col">Họ và tên</th>
                <th scope="col">Ngày sinh</th>
                <th scope="col">Giới tính</th>
                <th scope="col">Địa chỉ</th>
                <th scope="col">Số điện thoại</th>
                <th scope="col" class="text-center">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($accounts as $acc): ?>
                <tr>
                  <td><?= htmlspecialchars($acc['MaTK']) ?></td>
                  <td><?= htmlspecialchars($acc['MaND']) ?></td>
                  <td><?= htmlspecialchars($acc['HoVaTen']) ?></td>
                  <td><?= htmlspecialchars($acc['NgaySinh']) ?></td>
                  <td><?= htmlspecialchars($acc['GioiTinh']) ?></td>
                  <td><?= htmlspecialchars($acc['DiaChi']) ?></td>
                  <td><?= htmlspecialchars($acc['SDT']) ?></td>
                  <td class="text-center">
                    <a class="btn btn-sm btn-outline-primary" href="<?= base_url('accounts/edit&id=' . urlencode($acc['MaTK'])) ?>">Chỉnh sửa</a>
                    <a class="btn btn-sm btn-outline-danger" href="<?= base_url('accounts/delete&id=' . urlencode($acc['MaTK'])) ?>">Xóa</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div> <!-- .col-lg-9 -->
<?php
// Close the wrapping row
echo '</div>';
?>
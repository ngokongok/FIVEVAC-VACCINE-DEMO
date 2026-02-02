<?php
/* app/views/records/doctor_view_profile.php
 * Detailed view of a vaccination profile for doctors. Doctors can view all
 * personal information and injection history but cannot edit the record.
 * A button is provided to add a new injection. Injection history includes
 * the vaccine code if available.
 */

$err  = flash_get('error');
$info = flash_get('info');

if ($err): ?>
  <div class="alert alert-danger" role="alert">
    <?= htmlspecialchars($err) ?>
  </div>
<?php endif; ?>
<?php if ($info): ?>
  <div class="alert alert-success" role="alert">
    <?= htmlspecialchars($info) ?>
  </div>
<?php endif; ?>

<?php if (!$profile): ?>
  <p>Không tìm thấy hồ sơ.</p>
<?php else: ?>
  <div class="card mb-4">
    <div class="card-body">
      <h3 class="card-title mb-3">Thông tin hồ sơ</h3>
      <dl class="row">
        <dt class="col-sm-3">Mã hồ sơ</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($profile['MaKH']) ?></dd>
        <dt class="col-sm-3">Họ tên</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($profile['HoTen'] ?? '') ?></dd>
        <dt class="col-sm-3">Ngày sinh</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($profile['NgaySinh'] ?? '') ?></dd>
        <dt class="col-sm-3">Giới tính</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($profile['GioiTinh'] ?? '') ?></dd>
        <dt class="col-sm-3">Địa chỉ</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($profile['DiaChi'] ?? '') ?></dd>
        <dt class="col-sm-3">Số điện thoại</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($profile['SDT'] ?? '') ?></dd>
        <dt class="col-sm-3">Bệnh nền</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($profile['BenhNen'] ?? '') ?></dd>
      </dl>
    </div>
  </div>
  <div class="card mb-4">
    <div class="card-body">
      <h3 class="card-title mb-3">Lịch sử tiêm chủng</h3>
      <?php if (empty($injections)): ?>
        <p>Không có dữ liệu tiêm chủng.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead>
              <tr>
                <th scope="col">Mã phiếu tiêm</th>
                <th scope="col">Mã vắc xin</th>
                <th scope="col">Ngày tiêm</th>
                <th scope="col">Giờ tiêm</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($injections as $inj): ?>
                <tr>
                  <td><?= htmlspecialchars($inj['MaPhieuTiem']) ?></td>
                  <td><?= htmlspecialchars($inj['MaVacXin'] ?? '') ?></td>
                  <td><?= htmlspecialchars($inj['NgayTiem'] ?? '') ?></td>
                  <td><?= htmlspecialchars($inj['GioTiem'] ?? '') ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="mb-3 d-flex justify-content-end gap-2">
    <a class="btn btn-primary" href="<?= base_url('records/doctor_add_injection/' . urlencode($profile['MaKH'])) ?>">Thêm phiếu tiêm</a>
    <a class="btn btn-secondary" href="<?= base_url('records/doctor_manage') ?>">Quay lại</a>
  </div>
<?php endif; ?>
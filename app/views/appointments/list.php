<?php
/*
 * View: appointments/list.php
 *
 * Displays a table of appointments with options to view details and
 * request schedule changes. The data is supplied as an array of
 * associative arrays. If no appointments exist, a message is shown.
 */
?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h3 class="card-title mb-1">Lịch hẹn của bạn</h3>
        <p class="text-secondary">Danh sách các lịch hẹn đã đăng ký</p>
        <!-- Flash messages -->
        <?php if($msg = flash_get('error')): ?>
          <div class="alert alert-danger" role="alert">
            <?= $msg ?>
          </div>
        <?php endif; ?>
        <?php if($msg = flash_get('info')): ?>
          <div class="alert alert-success" role="alert">
            <?= $msg ?>
          </div>
        <?php endif; ?>
        <?php if($msg = flash_get('success')): ?>
          <div class="alert alert-success" role="alert">
            <?= $msg ?>
          </div>
        <?php endif; ?>
        <?php if (!empty($appointments)): ?>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th scope="col">Mã lịch hẹn</th>
                  <th scope="col">Vắc xin</th>
                  <th scope="col">Chi nhánh</th>
                  <th scope="col">Ngày giờ hiện tại</th>
                  <th scope="col">Ngày giờ mới</th>
                  <th scope="col">Trạng thái</th>
                  <th scope="col">Hành động</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($appointments as $ap): ?>
                  <tr>
                    <td><?= htmlspecialchars($ap['MaLichHen']) ?></td>
                    <td><?= htmlspecialchars($ap['TenVacXin'] ?? '') ?></td>
                    <td><?= htmlspecialchars($ap['TenChiNhanh'] ?? '') ?></td>
                    <td><?= htmlspecialchars($ap['NgayGio']) ?></td>
                    <td><?= htmlspecialchars($ap['GioMoi'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($ap['TrangThai']) ?></td>
                    <td>
                      <?php
                        // Determine if the appointment can be changed according to business rules
                        $nowTs       = time();
                        $scheduleTs  = strtotime($ap['NgayGio']);
                        $diffAllowed = ($scheduleTs - $nowTs) >= 24 * 3600;
                        $noChangeReq = empty($ap['GioMoi']);
                        $statusOK    = ($ap['TrangThai'] === 'Đã tạo');
                        $canChange   = $diffAllowed && $noChangeReq && $statusOK;
                      ?>
                      <?php if ($canChange): ?>
                        <a href="<?= base_url('appointments/edit/' . $ap['MaLichHen']) ?>" class="btn btn-sm btn-primary">Đổi lịch hẹn</a>
                      <?php else: ?>
                        <span class="btn btn-sm btn-secondary disabled" title="Không thể đổi lịch theo quy định">Đổi lịch hẹn</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <p>Bạn chưa có lịch hẹn nào.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
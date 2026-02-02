<?php
/* app/views/injections/history.php
 * Display the vaccination history (lịch sử tiêm) for the logged in customer.
 * The page shows a table of all injection records associated with the
 * user's personal profile. If no records exist, a friendly message is
 * displayed instead.
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
    <h3 class="card-title mb-3">Lịch sử tiêm chủng</h3>
    <?php if (empty($injections)): ?>
      <p>Không có dữ liệu tiêm chủng.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead>
            <tr>
              <th scope="col">Mã phiếu tiêm</th>
              <th scope="col">Ngày tiêm</th>
              <th scope="col">Giờ tiêm</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($injections as $inj): ?>
              <tr>
                <td><?= htmlspecialchars($inj['MaPhieuTiem']) ?></td>
                <td><?= htmlspecialchars($inj['NgayTiem']) ?></td>
                <td><?= htmlspecialchars($inj['GioTiem']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
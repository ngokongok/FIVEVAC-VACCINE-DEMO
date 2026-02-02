
<?php
// Start a grid row to contain the sidebar and the main content area.
echo '<div class="row">';
include __DIR__ . '/../layout/staff_sidebar.php';
?>
<div class="col-lg-9">
  <!-- Display flash messages -->
  <?php if($m=flash_get('error')): ?><div class='alert alert-danger'><?= $m ?></div><?php endif; ?>
  <?php if($m=flash_get('success')): ?><div class='alert alert-success'><?= $m ?></div><?php endif; ?>
  <div class="card mb-3">
    <div class="card-body">
      <h3 class="card-title mb-1"><?= isset($title) ? $title : 'Phê duyệt đổi lịch hẹn' ?></h3>
      <?php if(empty($requests)): ?>
        <p>Không có yêu cầu đổi lịch nào đang chờ phê duyệt.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead>
              <tr>
                <th>Mã lịch hẹn</th>
                <th>Mã đơn hàng</th>
                <th>Ngày giờ hiện tại</th>
                <th>Ngày giờ mới</th>
                <th>Hành động</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($requests as $item): ?>
              <tr>
                <td><?= htmlspecialchars($item['MaLichHen']) ?></td>
                <td><?= htmlspecialchars($item['MaDHonl']) ?></td>
                <td><?= htmlspecialchars($item['NgayGio']) ?></td>
                <td><?= htmlspecialchars($item['GioMoi']) ?></td>
                <td>
                  <!-- Accept button -->
                  <form method="post" action="" class="d-inline-block me-2">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($item['MaLichHen']) ?>">
                    <input type="hidden" name="decision" value="accept">
                    <?php csrf_field(); ?>
                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Chấp nhận yêu cầu đổi lịch này?');">Chấp nhận</button>
                  </form>
                  <!-- Deny button -->
                  <form method="post" action="" class="d-inline-block">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($item['MaLichHen']) ?>">
                    <input type="hidden" name="decision" value="deny">
                    <?php csrf_field(); ?>
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Từ chối yêu cầu đổi lịch này?');">Từ chối</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <!-- Decorative image removed -->
</div>
<?php
// Close the wrapping row opened above
echo '</div>';
?>
    
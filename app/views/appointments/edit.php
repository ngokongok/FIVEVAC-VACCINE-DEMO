<?php
/*
 * View: appointments/edit.php
 *
 * Allows a user to request a change to a scheduled appointment. The
 * `$appointment` array contains the current appointment details.
 */
?>
<div class="row">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-body">
        <h3 class="card-title mb-1">Yêu cầu đổi lịch hẹn</h3>
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
        <?php if (!empty($appointment)): ?>
          <p><strong>Mã lịch hẹn:</strong> <?= htmlspecialchars($appointment['MaLichHen']) ?></p>
          <p><strong>Ngày giờ hiện tại:</strong> <?= htmlspecialchars($appointment['NgayGio']) ?></p>
          <form method="post" action="">
            <div class="mb-3">
              <label class="form-label">Ngày tiêm mới</label>
              <input type="date" class="form-control" name="date" value="<?= isset($selected_date) ? htmlspecialchars($selected_date) : '' ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Giờ tiêm mới</label>
              <input type="time" class="form-control" name="time" value="<?= isset($selected_time) ? htmlspecialchars($selected_time) : '' ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Gửi yêu cầu</button>
            <?php csrf_field(); ?>
          </form>
        <?php else: ?>
          <p>Lịch hẹn không tồn tại.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
    <!-- Decorative image removed -->
</div>
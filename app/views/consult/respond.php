
<?php
echo '<div class="row">';
include __DIR__ . '/../layout/staff_sidebar.php';
?>
<div class="col-lg-9">
  <!-- Display flash messages -->
  <?php if($m=flash_get('error')): ?><div class='alert alert-danger'><?= $m ?></div><?php endif; ?>
  <?php if($m=flash_get('success')): ?><div class='alert alert-success'><?= $m ?></div><?php endif; ?>
  <?php if(isset($consult)): ?>
    <!-- Response form for a specific consultation -->
    <div class="card mb-3">
      <div class="card-body">
        <h3 class="card-title mb-1">Phản hồi phiếu tư vấn <?= htmlspecialchars($consult['MaTuVan']) ?></h3>
        <p class="text-secondary">Nội dung câu hỏi:</p>
        <p><?= nl2br(htmlspecialchars($consult['NoiDungYeuCau'])) ?></p>
        <?php verify_csrf(); ?>
        <form method="post" action="">
          <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($consult['MaTuVan']) ?>">
          <div class="mb-3">
            <label class="form-label">Nội dung phản hồi</label>
            <textarea class="form-control" name="reply" rows="4" placeholder="Ít nhất 50 ký tự" required></textarea>
          </div>
          <button class="btn btn-primary">Gửi phản hồi</button>
          <?php csrf_field(); ?>
        </form>
      </div>
    </div>
  <?php elseif(isset($consults) && is_array($consults)): ?>
    <!-- List of pending consultations -->
    <div class="card mb-3">
      <div class="card-body">
        <h3 class="card-title mb-1">Danh sách yêu cầu tư vấn</h3>
        <p class="text-secondary">Chọn một yêu cầu để phản hồi</p>
        <?php if(empty($consults)): ?>
          <p>Không có yêu cầu tư vấn nào chờ phản hồi.</p>
        <?php else: ?>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Mã phiếu</th>
                <th>Câu hỏi</th>
                <th>Ngày gửi</th>
                <th>Hành động</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($consults as $item): ?>
              <tr>
                <td><?= htmlspecialchars($item['MaTuVan']) ?></td>
                <td><?= htmlspecialchars($item['short']) ?></td>
                <td><?= htmlspecialchars($item['NgayTao']) ?></td>
                <td><a class="btn btn-sm btn-primary" href="<?= base_url('consult/respond/' . urlencode($item['MaTuVan'])) ?>">Phản hồi</a></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>
  <!-- Decorative image removed -->
</div>
<?php echo '</div>'; ?>
    
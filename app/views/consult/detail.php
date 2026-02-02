<?php
/* app/views/consult/detail.php
 * Detail page for a single consultation request. Shows the full
 * description and any response provided. Only accessible to the
 * consultation owner (customer). UC‑4.3 continuation.
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
    <h3 class="card-title mb-1">Chi tiết tư vấn</h3>
    <?php if (!empty($consult)): ?>
      <dl class="row">
        <dt class="col-sm-3">Mã phiếu</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($consult['MaTuVan'] ?? '') ?></dd>

        <dt class="col-sm-3">Ngày gửi</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($consult['NgayTao'] ?? '') ?></dd>

        <dt class="col-sm-3">Nội dung yêu cầu</dt>
        <dd class="col-sm-9"><pre class="mb-0" style="white-space:pre-wrap; word-break:break-word; font-family:inherit;">
<?= htmlspecialchars($consult['NoiDungYeuCau'] ?? '') ?></pre></dd>

        <dt class="col-sm-3">Trạng thái phản hồi</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($consult['TrangThaiPhanHoi'] ?? '') ?></dd>

        <?php if (($consult['TrangThaiPhanHoi'] ?? '') === 'Đã trả lời'): ?>
          <dt class="col-sm-3">Ngày phản hồi</dt>
          <dd class="col-sm-9"><?= htmlspecialchars($consult['NgayPhanHoi'] ?? '') ?></dd>
          <dt class="col-sm-3">Nội dung phản hồi</dt>
          <dd class="col-sm-9"><pre class="mb-0" style="white-space:pre-wrap; word-break:break-word; font-family:inherit;">
<?= htmlspecialchars($consult['NoiDungPhanHoi'] ?? '') ?></pre></dd>
        <?php endif; ?>
      </dl>
    <?php else: ?>
      <p>Không tìm thấy dữ liệu tư vấn.</p>
    <?php endif; ?>
    <a href="<?= base_url('consult/request') ?>" class="btn btn-secondary">Quay lại</a>
  </div>
</div>
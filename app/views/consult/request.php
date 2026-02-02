<?php
/* app/views/consult/request.php
 * This page allows a customer to submit a new consultation request and
 * view/search their consultation history. It implements the flows for
 * UC‑4.1 (Yêu cầu tư vấn) and UC‑4.3 (Tra cứu lịch sử tư vấn).
 */
?>

<?php
// Display flash messages if present
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


<div class="row">
  <!-- Consultation request form -->
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-body">
        <h3 class="card-title mb-1">Yêu cầu tư vấn</h3>
        
        <?php verify_csrf(); ?>
        <form method="post" action="<?= base_url('consult/request') ?>">
          <div class="mb-3">
            <label class="form-label">Mô tả chi tiết vấn đề</label>
            <textarea class="form-control" name="content" rows="4" placeholder="Nhập mô tả ít nhất 50 ký tự" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Gửi yêu cầu</button>
          <?php csrf_field(); ?>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Consultation history with search -->
<div class="card">
  <div class="card-body">
    <h3 class="card-title mb-1">Lịch sử tư vấn</h3>
    
    <!-- Search/filter form -->
    <form class="row g-3 mb-3" method="get" action="<?= base_url('') ?>">
      <!-- Ensure the url parameter stays set so that the router routes correctly -->
      <input type="hidden" name="url" value="consult/request">
      <div class="col-md-5">
        <input type="text" class="form-control" name="q" placeholder="Tìm kiếm theo mã hoặc nội dung" value="<?= htmlspecialchars($keyword ?? '') ?>">
      </div>
      <div class="col-md-4">
        <select class="form-select" name="status">
          <option value=""<?= ($status ?? '') === '' ? ' selected' : '' ?>>Tất cả trạng thái</option>
          <option value="Chưa trả lời"<?= ($status ?? '') === 'Chưa trả lời' ? ' selected' : '' ?>>Chưa trả lời</option>
          <option value="Đã trả lời"<?= ($status ?? '') === 'Đã trả lời' ? ' selected' : '' ?>>Đã trả lời</option>
        </select>
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-outline-primary w-100">Tìm kiếm</button>
      </div>
    </form>
    <?php if (empty($consults)): ?>
      <?php
        // Show a different message when searching but no results match
        $hasSearch = (!empty($keyword) || !empty($status));
      ?>
      <?php if ($hasSearch): ?>
        <p>Không tìm thấy nội dung bạn đang tìm kiếm.</p>
      <?php else: ?>
        <p>Không có yêu cầu tư vấn nào.</p>
      <?php endif; ?>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th scope="col">Mã phiếu</th>
              <th scope="col">Câu hỏi</th>
              <th scope="col">Trạng thái</th>
              <th scope="col">Ngày gửi</th>
              <th scope="col">Ngày phản hồi</th>
              <!-- New actions column to allow navigation to detail page -->
              <th scope="col">Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($consults as $c): ?>
              <tr>
                <td><?= htmlspecialchars($c['MaTuVan']) ?></td>
                <td><?= htmlspecialchars($c['short']) ?></td>
                <td><?= htmlspecialchars($c['TrangThaiPhanHoi'] ?? '') ?></td>
                <td><?= htmlspecialchars($c['NgayTao'] ?? '') ?></td>
                <td><?= htmlspecialchars($c['NgayPhanHoi'] ?? '') ?></td>
                <td>
                  <!-- Explicit "Chi tiết" link instead of relying on row click -->
                  <a href="<?= base_url('consult/detail/' . $c['MaTuVan']) ?>" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
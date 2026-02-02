<?php
/* app/views/records/manage.php
 * Unified interface for staff to manage vaccination profiles. The page
 * displays a search form for looking up existing records by name and/or
 * phone number and provides a button to add a new record. Search
 * results are not implemented in this basic example but could be
 * populated by the controller when processing a POST request.
 */

echo '<div class="row">';
include __DIR__ . '/../layout/staff_sidebar.php';
?>
<div class="col-lg-9">
  <div class="card mb-3">
    <div class="card-body">
      <h3 class="card-title mb-1"><?= isset($title) ? $title : 'Quản lý hồ sơ tiêm chủng' ?></h3>
      <?php
      // Display flash messages if present
      $err  = flash_get('error');
      $info = flash_get('info');
      if ($err) {
          echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($err) . '</div>';
      }
      if ($info) {
          echo '<div class="alert alert-success" role="alert">' . htmlspecialchars($info) . '</div>';
      }
      ?>
      <!-- Search form for vaccination profiles. Staff can search by name and/or phone number. -->
      <form method="post" action="#">
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label">Họ tên</label>
            <input type="text" class="form-control" name="name" placeholder="Nhập họ tên">
          </div>
          <div class="col-md-6">
            <label class="form-label">Số điện thoại</label>
            <input type="tel" class="form-control" name="phone" placeholder="Nhập số điện thoại">
          </div>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <button class="btn btn-primary">Tra cứu</button>
          <a class="btn btn-success" href="<?= base_url('records/add_profile') ?>">Thêm hồ sơ</a>
        </div>
        <?php csrf_field(); ?>
      </form>
    </div>
  </div>
  <!-- Placeholder for search results. In a complete implementation this
       section would display a table of matching records when the form
       is submitted. -->
  <div id="search-results"></div>
  <?php
    // Determine which heading to display. If a search was performed and results
    // are non-empty, show "Kết quả tra cứu". Otherwise, show the default list
    // heading "Danh sách hồ sơ".
    $listTitle = (isset($searching) && $searching) ? 'Kết quả tra cứu' : 'Danh sách hồ sơ';
  ?>
  <?php if (!empty($results)): ?>
  <div class="card">
    <div class="card-body">
      <h4 class="card-title mb-3"><?= htmlspecialchars($listTitle) ?></h4>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead>
            <tr>
              <th scope="col">Mã hồ sơ</th>
              <th scope="col">Họ tên</th>
              <th scope="col">Ngày sinh</th>
              <th scope="col">Giới tính</th>
              <th scope="col">Số điện thoại</th>
              <th scope="col">Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($results as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['MaKH']) ?></td>
              <td><?= htmlspecialchars($r['HoTen'] ?? '') ?></td>
              <td><?= htmlspecialchars($r['NgaySinh'] ?? '') ?></td>
              <td><?= htmlspecialchars($r['GioiTinh'] ?? '') ?></td>
              <td><?= htmlspecialchars($r['SDT'] ?? '') ?></td>
              <td>
                <a class="btn btn-sm btn-outline-primary" href="<?= base_url('records/view_profile/' . urlencode($r['MaKH'])) ?>">Chi tiết</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?php elseif (isset($searching) && $searching): ?>
  <div class="alert alert-info">Không tìm thấy hồ sơ phù hợp.</div>
  <?php endif; ?>
</div>
<?php echo '</div>'; ?>
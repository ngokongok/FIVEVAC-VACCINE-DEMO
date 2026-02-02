<?php
/* app/views/records/doctor_manage.php
 * Search interface for doctors to look up vaccination profiles (Tra cứu hồ sơ).
 * Doctors can search by name or phone and view profile details but cannot
 * add new profiles. If no search is performed, the full list of profiles
 * is displayed. When a search yields no results, an informational
 * message is shown.
 */

echo '<div class="row">';
// Include the staff sidebar. The sidebar will highlight the current
// navigation item based on the URL.
include __DIR__ . '/../layout/staff_sidebar.php';
?>
<div class="col-lg-9">
  <div class="card mb-3">
    <div class="card-body">
      <h3 class="card-title mb-1">
        <?= isset($title) ? htmlspecialchars($title) : 'Tra cứu hồ sơ' ?>
      </h3>
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
      <!-- Search form for doctors. Allows partial matches on name and phone. -->
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
        </div>
        <?php csrf_field(); ?>
      </form>
    </div>
  </div>
  <!-- Search results table. If not searching, this will show all profiles. -->
  <div id="search-results"></div>
  <?php
    // Determine heading: show "Kết quả tra cứu" when searching, otherwise
    // default to "Danh sách hồ sơ".
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
                <a class="btn btn-sm btn-outline-primary" href="<?= base_url('records/doctor_view_profile/' . urlencode($r['MaKH'])) ?>">Chi tiết</a>
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
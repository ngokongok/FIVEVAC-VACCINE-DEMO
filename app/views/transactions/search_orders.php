<?php
/* app/views/transactions/search_orders.php
 * Unified interface for staff to search and manage orders. Staff can look up
 * both online (donhangonl) and POS (donhangpos) orders by specifying a
 * customer name, phone number and/or order code. The results are displayed
 * below the search form in a sortable table. Each record includes a
 * "Chi tiết" button to view full order information.
 */
echo '<div class="row">';
include __DIR__ . '/../layout/staff_sidebar.php';
?>
<div class="col-lg-9">
  <div class="card mb-3">
    <div class="card-body">
      <h3 class="card-title mb-1"><?= isset($title) ? $title : 'Quản lý đơn hàng' ?></h3>
      <?php
      // Display flash messages if present
      if ($msg = flash_get('error')) {
          echo '<div class="alert alert-danger">' . htmlspecialchars($msg) . '</div>';
      }
      if ($msg = flash_get('success')) {
          echo '<div class="alert alert-success">' . htmlspecialchars($msg) . '</div>';
      }
      ?>
      <?php verify_csrf(); ?>
      <form method="post" action="">
        <div class="row g-3 mb-3">
          <div class="col-md-4">
            <label class="form-label">Khách hàng</label>
            <!-- Use datalist to provide suggestions for customer names -->
            <input list="customerNames" class="form-control" name="name" value="<?= htmlspecialchars($name) ?>" placeholder="Họ và tên">
            <datalist id="customerNames">
              <?php foreach ($customerNames as $n): ?>
              <option value="<?= htmlspecialchars($n['name']) ?>"></option>
              <?php endforeach; ?>
            </datalist>
          </div>
          <div class="col-md-4">
            <label class="form-label">Số điện thoại</label>
            <!-- Datalist for phone number suggestions -->
            <input list="customerPhones" class="form-control" name="phone" value="<?= htmlspecialchars($phone) ?>" placeholder="Số điện thoại">
            <datalist id="customerPhones">
              <?php foreach ($customerPhones as $p): ?>
              <option value="<?= htmlspecialchars($p['SDT']) ?>"></option>
              <?php endforeach; ?>
            </datalist>
          </div>
          <div class="col-md-4">
            <label class="form-label">Mã đơn hàng</label>
            <input type="text" class="form-control" name="code" value="<?= htmlspecialchars($code) ?>" placeholder="Nhập mã ONL/POS">
          </div>
        </div>
        <button class="btn btn-primary">Tra cứu</button>
        <?php csrf_field(); ?>
      </form>
    </div>
  </div>
  <?php
  // Determine title for the results table. Show "Kết quả tra cứu" when
  // searching, otherwise "Danh sách đơn hàng" for initial page load.
  $listTitle = ($searching) ? 'Kết quả tra cứu' : 'Danh sách đơn hàng mới nhất';
  ?>
  <?php if (!empty($orders)): ?>
  <div class="card">
    <div class="card-body">
      <h4 class="card-title mb-3"><?= htmlspecialchars($listTitle) ?></h4>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th scope="col">Mã đơn</th>
              <th scope="col">Loại</th>
              <th scope="col">Khách hàng</th>
              <th scope="col">Số điện thoại</th>
              <th scope="col">Chi nhánh</th>
              <th scope="col">Vắc xin</th>
              <th scope="col">Thanh tiền (VNĐ)</th>
              <th scope="col">Ngày tạo</th>
              <th scope="col">Trạng thái</th>
              <th scope="col">Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $od): ?>
            <tr>
              <td><?= htmlspecialchars($od['MaDH']) ?></td>
              <td><?= htmlspecialchars($od['Loai']) ?></td>
              <td><?= htmlspecialchars($od['KhachHang'] ?? '') ?></td>
              <td><?= htmlspecialchars($od['SDT'] ?? '') ?></td>
              <td><?= htmlspecialchars($od['TenChiNhanh'] ?? '') ?></td>
              <td><?= htmlspecialchars($od['TenVacXin'] ?? '') ?></td>
              <td><?= number_format((float)$od['ThanhTien']) ?></td>
              <td><?= htmlspecialchars($od['NgayTao']) ?></td>
              <td><?= htmlspecialchars($od['TrangThaiDH'] ?? '') ?></td>
              <td>
                <a class="btn btn-sm btn-outline-primary" href="<?= base_url('transactions/order_detail/' . urlencode($od['MaDH'])) ?>">Chi tiết</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?php elseif ($searching): ?>
  <div class="alert alert-info">Không tìm thấy kết quả trùng khớp.</div>
  <?php endif; ?>
</div>
<?php echo '</div>'; ?>
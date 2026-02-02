<?php
/* app/views/transactions/order_detail.php
 * Detailed view for an individual order. Displays comprehensive
 * information about either an online or POS order. A back button
 * allows staff to return to the order search page.
 */
echo '<div class="row">';
include __DIR__ . '/../layout/staff_sidebar.php';
?>
<div class="col-lg-9">
  <div class="card mb-3">
    <div class="card-body">
      <h3 class="card-title mb-1">Chi tiết đơn hàng</h3>
      <?php if (!$detail): ?>
        <div class="alert alert-warning">Không tìm thấy thông tin đơn hàng.</div>
        <a href="<?= base_url('transactions/search_orders') ?>" class="btn btn-secondary">Quay về</a>
      <?php else: ?>
        <table class="table table-bordered">
          <tbody>
            <tr>
              <th scope="row">Mã đơn hàng</th>
              <td><?= htmlspecialchars($detail['MaDH']) ?></td>
            </tr>
            <tr>
              <th scope="row">Loại đơn</th>
              <td><?= htmlspecialchars($detail['Loai']) ?></td>
            </tr>
            <tr>
              <th scope="row">Khách hàng</th>
              <td><?= htmlspecialchars($detail['KhachHang'] ?? '') ?></td>
            </tr>
            <tr>
              <th scope="row">Số điện thoại</th>
              <td><?= htmlspecialchars($detail['SDT'] ?? '') ?></td>
            </tr>
            <tr>
              <th scope="row">Chi nhánh</th>
              <td><?= htmlspecialchars($detail['TenChiNhanh'] ?? '') ?></td>
            </tr>
            <?php if (!empty($detail['DiaChiChiNhanh'])): ?>
            <tr>
              <th scope="row">Địa chỉ chi nhánh</th>
              <td><?= htmlspecialchars($detail['DiaChiChiNhanh']) ?></td>
            </tr>
            <?php endif; ?>
            <tr>
              <th scope="row">Vắc xin</th>
              <td><?= htmlspecialchars($detail['TenVacXin'] ?? '') ?></td>
            </tr>
            <tr>
              <th scope="row">Đơn giá (VNĐ)</th>
              <td><?= number_format((float)($detail['DonGia'] ?? 0)) ?></td>
            </tr>
            <tr>
              <th scope="row">Ngày tạo</th>
              <td><?= htmlspecialchars($detail['NgayTao'] ?? '') ?></td>
            </tr>
            <tr>
              <th scope="row">Thành tiền (VNĐ)</th>
              <td><?= number_format((float)($detail['ThanhTien'] ?? 0)) ?></td>
            </tr>
            <tr>
              <th scope="row">Hình thức thanh toán</th>
              <td><?= htmlspecialchars($detail['HinhThucThanhToan'] ?? '') ?></td>
            </tr>
            <tr>
              <th scope="row">Trạng thái đơn hàng</th>
              <td><?= htmlspecialchars($detail['TrangThaiDH'] ?? '') ?></td>
            </tr>
          </tbody>
        </table>
        <a href="<?= base_url('transactions/search_orders') ?>" class="btn btn-secondary">Quay về</a>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php echo '</div>'; ?>
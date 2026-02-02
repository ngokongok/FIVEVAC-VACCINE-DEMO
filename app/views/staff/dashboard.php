<?php
// Include the staff sidebar. This file prints a nav in a col-lg-3 column.
echo '<div class="row">';
// Include the staff sidebar. This file prints a nav in a col-lg-3 column.
include __DIR__ . '/../layout/staff_sidebar.php';
?>
<div class="col-lg-9">
  <div class="card mb-3">
    <div class="card-body">
      <h3 class="mb-1"><?php echo $title; ?></h3>
      <p class="text-secondary">Tổng quan nhanh các nghiệp vụ trong ngày.</p>
      <div class="row g-3">
        <?php
        // Render dashboard cards based on staff role. Only display the tasks
        // relevant to the current user. Admins use a different dashboard.
        if (!empty($isDoctor)) {
        ?>
        <div class="col-md-6">
          <div class="p-3 border rounded">
            <h5>Phản hồi tư vấn</h5>
            <p>Xem và phản hồi các yêu cầu tư vấn.</p>
            <a class="btn btn-sm btn-primary" href="<?php echo base_url('consult/respond'); ?>">Phản hồi</a>
          </div>
        </div>
        <div class="col-md-6">
          <div class="p-3 border rounded">
            <h5>Quản lý hồ sơ tiêm chủng</h5>
            <p>Tìm kiếm và xem hồ sơ tiêm chủng.</p>
            <a class="btn btn-sm btn-primary" href="<?php echo base_url('records/doctor_manage'); ?>">Truy cập</a>
          </div>
        </div>
        <?php
        } elseif (!empty($isCSKH)) {
        ?>
        <div class="col-md-6">
          <div class="p-3 border rounded">
            <h5>Phê duyệt yêu cầu chỉnh sửa lịch hẹn</h5>
            <p>Kiểm tra và xử lý yêu cầu đổi lịch hẹn từ khách hàng.</p>
            <a class="btn btn-sm btn-primary" href="<?php echo base_url('appointments/approve_change'); ?>">Xử lý ngay</a>
          </div>
        </div>
        <div class="col-md-6">
          <div class="p-3 border rounded">
            <h5>Quản lý đơn hàng</h5>
            <p>Tra cứu và xem chi tiết đơn hàng online và POS.</p>
            <a class="btn btn-sm btn-primary" href="<?php echo base_url('transactions/search_orders'); ?>">Tra cứu</a>
          </div>
        </div>
        <div class="col-md-6">
          <div class="p-3 border rounded mt-3">
            <h5>Tạo đơn hàng POS</h5>
            <p>Tạo hóa đơn tại điểm tiêm cho khách hàng.</p>
            <a class="btn btn-sm btn-primary" href="<?php echo base_url('transactions/create_pos'); ?>">Tạo đơn</a>
          </div>
        </div>
        <?php
        } else {
        ?>
        <!-- Default fallback for other staff roles: no specific cards -->
        <div class="col-12">
          <p class="text-muted">Bạn không có nghiệp vụ nào được phân quyền hiển thị trên trang tổng quan.</p>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <!-- Decorative image removed for cleaner staff layout -->
</div> <!-- .col-lg-9 -->
<?php
// Close the wrapping row opened before including the sidebar. This ensures
// proper Bootstrap grid structure for the sidebar and main content.
echo '</div>'; // end row
?>
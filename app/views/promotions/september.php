<div class="card">
  <div class="card-body">
    <h3 class="card-title">Ưu đãi tháng 9 – Tiêm chủng an toàn, tiết kiệm</h3>
    <p>Đăng ký nhận ưu đãi dành cho khách hàng Fivevac. Số lượng có hạn.</p>
    <form method="post"><?php verify_csrf(); ?>
      <div class="mb-3"><label class="form-label">Họ tên</label><input class="form-control" name="name" required></div>
      <div class="mb-3"><label class="form-label">Số điện thoại</label><input class="form-control" name="phone" required></div>
      <?php csrf_field(); ?>
      <button class="btn btn-primary">Đăng ký</button>
    </form>
  </div>
</div>
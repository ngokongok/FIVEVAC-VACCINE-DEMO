
    <div class="row">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <?php if($m=flash_get('error')): ?><div class='alert alert-danger'><?= $m ?></div><?php endif; ?>
        <h3 class="card-title mb-1">Đăng ký</h3>
            <?php verify_csrf(); ?>
            <form method="post" action="#">
              
        <div class="mb-3"><label class="form-label">Họ và tên</label><input type="text" class="form-control" name="name" placeholder="Họ tên"></div>
        <div class="mb-3">
          <label class="form-label">Số điện thoại</label>
          <input type="tel" class="form-control" name="phone" placeholder="09xxxxxxxx" required>
        </div>
        
        <div class="mb-3">
          <label class="form-label">Mã OTP</label>
          <input type="text" class="form-control" name="otp" placeholder="Nhập mã xác thực" required>
        </div>
        
        <div class="mb-3">
          <label class="form-label">Mật khẩu</label>
          <!-- Provide a generic placeholder; password complexity is enforced server-side -->
          <input type="password" class="form-control" name="password" placeholder="Mật khẩu" required>
        </div>
        
              <button class="btn btn-primary">Tạo tài khoản</button>
            <?php csrf_field(); ?>
            </form>
            <p class="text-muted small">Mỗi số điện thoại chỉ có thể đăng ký một tài khoản. OTP hiệu lực 1 phút.</p>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <img class="img-fluid rounded shadow-sm" alt="decor" src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=800">
      </div>
    </div>
    
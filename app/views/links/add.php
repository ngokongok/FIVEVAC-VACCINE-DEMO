
    <div class="row">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <h3 class="card-title mb-1">Thêm hồ sơ liên kết</h3>
            <?php verify_csrf(); ?>
            <form method="post" action="#">
              
        <div class="mb-3">
          <label class="form-label">Số điện thoại liên kết</label>
          <input type="tel" class="form-control" name="phone" placeholder="" required>
        </div>
        
        <div class="mb-3">
          <label class="form-label">OTP xác nhận</label>
          <input type="text" class="form-control" name="otp" placeholder="" required>
        </div>
        
              <button class="btn btn-primary">Liên kết</button>
            <?php csrf_field(); ?>
            </form>
            
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <!-- Decorative image removed -->
      </div>
    </div>
    

    <div class="row">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <h3 class="card-title mb-1">Thêm phiếu tiêm</h3>
            <?php verify_csrf(); ?>
            <form method="post" action="#">
              
        <div class="mb-3">
          <label class="form-label">Mã hồ sơ</label>
          <input type="text" class="form-control" name="profile_id" placeholder="" required>
        </div>
        
        <div class="mb-3">
          <label class="form-label">Loại vắc xin</label>
          <input type="text" class="form-control" name="vaccine" placeholder="" required>
        </div>
        
        <div class="mb-3">
          <label class="form-label">Ngày tiêm</label>
          <input type="date" class="form-control" name="date" placeholder="" required>
        </div>
        
        <!-- Removed Liều lượng input as requested -->
        
              <button class="btn btn-primary">Lưu phiếu tiêm</button>
            <?php csrf_field(); ?>
            </form>
            
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <!-- Decorative image removed -->
      </div>
    </div>
    
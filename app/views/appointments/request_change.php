
    <div class="row">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <h3 class="card-title mb-1">Yêu cầu chỉnh sửa lịch hẹn</h3>
            <?php verify_csrf(); ?>
            <form method="post" action="#">
              
        <div class="mb-3">
          <label class="form-label">Mã lịch hẹn</label>
          <input type="text" class="form-control" name="apm_id" placeholder="" required>
        </div>
        
        <div class="mb-3">
          <label class="form-label">Ngày tiêm mới</label>
          <input type="date" class="form-control" name="date" placeholder="" required>
        </div>
        
        <div class="mb-3">
          <label class="form-label">Giờ tiêm mới</label>
          <input type="time" class="form-control" name="time" placeholder="" required>
        </div>
        
              <button class="btn btn-primary">Gửi yêu cầu</button>
            <?php csrf_field(); ?>
            </form>
            
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <!-- Decorative image removed -->
      </div>
    </div>
    
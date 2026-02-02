
    <div class="row">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <h3 class="card-title mb-1">Xóa quan hệ liên kết</h3>
            <?php verify_csrf(); ?>
            <form method="post" action="#">
              
        <div class="mb-3">
          <label class="form-label">Mã hồ sơ liên kết</label>
          <input type="text" class="form-control" name="link_id" placeholder="" required>
        </div>
        
              <button class="btn btn-primary">Xóa liên kết</button>
            <?php csrf_field(); ?>
            </form>
            
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <!-- Decorative image removed -->
      </div>
    </div>
    
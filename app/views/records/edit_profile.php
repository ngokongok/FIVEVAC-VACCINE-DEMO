
    <div class="row">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <h3 class="card-title mb-1">Chỉnh sửa thông tin hồ sơ</h3>
            <?php
            // Display flash messages if any
            $err  = flash_get('error');
            $info = flash_get('info');
            if ($err) {
                echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($err) . '</div>';
            }
            if ($info) {
                echo '<div class="alert alert-success" role="alert">' . htmlspecialchars($info) . '</div>';
            }
            // Pre-fill form fields if a profile is provided (edit mode).
            $pfId    = $profile['MaKH']    ?? '';
            $pfName  = $profile['HoTen']    ?? '';
            $pfDob   = $profile['NgaySinh'] ?? '';
            $pfGender= $profile['GioiTinh'] ?? '';
            $pfPhone = $profile['SDT']      ?? '';
            $pfAddr  = $profile['DiaChi']   ?? '';
            $pfDisease = $profile['BenhNen'] ?? '';
            ?>
            <form method="post" action="#">
              <div class="mb-3">
                <label class="form-label">Mã hồ sơ</label>
                <input type="text" class="form-control" name="profile_id" value="<?= htmlspecialchars($pfId) ?>" required readonly>
              </div>

              <div class="mb-3">
                <label class="form-label">Họ tên</label>
                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($pfName) ?>" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Ngày sinh</label>
                <input type="date" class="form-control" name="dob" value="<?= htmlspecialchars($pfDob) ?>" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Giới tính</label>
                <select name="gender" class="form-select" required>
                  <option value="" disabled <?= $pfGender === '' ? 'selected' : '' ?>>Chọn giới tính</option>
                  <option value="Nam" <?= $pfGender === 'Nam' ? 'selected' : '' ?>>Nam</option>
                  <option value="Nữ" <?= $pfGender === 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                  <option value="Khác" <?= $pfGender === 'Khác' ? 'selected' : '' ?>>Khác</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Số điện thoại</label>
                <!-- Require a 10-digit Vietnamese phone starting with 0 -->
                <input type="tel" class="form-control" name="phone" value="<?= htmlspecialchars($pfPhone) ?>" pattern="0\d{9}" title="Số điện thoại phải gồm 10 chữ số và bắt đầu bằng 0" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Địa chỉ</label>
                <input type="text" class="form-control" name="address" value="<?= htmlspecialchars($pfAddr) ?>" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Bệnh nền (nếu có)</label>
                <input type="text" class="form-control" name="disease" value="<?= htmlspecialchars($pfDisease) ?>">
              </div>

              <button class="btn btn-primary">Lưu thay đổi</button>
              <?php csrf_field(); ?>
            </form>
            
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <!-- Decorative image removed -->
      </div>
    </div>
    
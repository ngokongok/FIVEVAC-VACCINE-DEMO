<?php
/* app/views/records/doctor_add_injection.php
 * Form for doctors to add a new injection (phiếu tiêm) to a customer's
 * vaccination record. The injection date and time are auto-filled with
 * the current date/time. Doctors must provide the vaccine code and
 * dosage. The form enforces basic validation through HTML attributes
 * and server-side checks in the controller.
 */

// Display flash messages
$err  = flash_get('error');
$info = flash_get('info');
?>
<div class="row">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-body">
        <h3 class="card-title mb-1">Thêm phiếu tiêm</h3>
        
        <?php
        if ($err) {
            echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($err) . '</div>';
        }
        if ($info) {
            echo '<div class="alert alert-success" role="alert">' . htmlspecialchars($info) . '</div>';
        }
        // This call will echo a hidden input with the CSRF token to protect
        // against cross-site request forgery. A call to verify_csrf() is
        // expected in the controller on POST submissions.
        verify_csrf();
        ?>
        <form method="post" action="#">
          <div class="mb-3">
            <label class="form-label">Mã hồ sơ</label>
            <input type="text" class="form-control" name="profile_id" value="<?= htmlspecialchars($profile_id ?? '') ?>" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">Loại vắc xin</label>
            <input
              type="text"
              class="form-control"
              name="vaccine"
              placeholder="Nhập mã vắc xin (ví dụ VX001)"
              pattern="VX[0-9]{3}"
              title="Mã vắc xin phải bắt đầu bằng VX và gồm 3 chữ số"
              value="<?= htmlspecialchars($vaccine ?? '') ?>"
              required
            >
          </div>
          <div class="mb-3">
            <label class="form-label">Ngày tiêm</label>
            <input type="text" class="form-control" name="date" value="<?= htmlspecialchars($today ?? '') ?>" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">Giờ tiêm</label>
            <input type="text" class="form-control" name="time" value="<?= htmlspecialchars($time ?? '') ?>" readonly>
          </div>
          <!-- The Liều lượng input has been removed per requirements -->
          <button class="btn btn-primary">Lưu phiếu tiêm</button>
          <?php csrf_field(); ?>
        </form>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <!-- Decorative image for aesthetics. This could be replaced with a
         relevant illustration or removed if unnecessary. -->
    <!-- Decorative image removed -->
  </div>
</div>
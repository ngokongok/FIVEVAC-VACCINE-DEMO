
    <div class="row">
      <!-- Vaccine details -->
      <div class="col-lg-5 mb-3">
        <?php if (!empty($vaccine)): ?>
        <div class="card h-100">
          <?php if (!empty($vaccine_image)): ?>
            <img class="card-img-top" src="<?= base_url('assets/images/' . $vaccine_image) ?>" alt="<?= htmlspecialchars($vaccine['TenVacXin'] ?? '') ?>">
          <?php endif; ?>
          <div class="card-body">
            <h5 class="card-title mb-2"><?= htmlspecialchars($vaccine['TenVacXin'] ?? '') ?></h5>
            <?php if (!empty($vaccine['Gia'])): ?>
              <p class="mb-1"><strong>Giá:</strong> <?= number_format($vaccine['Gia'], 0, ',', '.') ?> đ</p>
            <?php endif; ?>
            <?php if (!empty($vaccine['HSD'])): ?>
              <p class="mb-1"><strong>HSD:</strong> <?= htmlspecialchars($vaccine['HSD']) ?></p>
            <?php endif; ?>
            <?php if (!empty($vaccine['NSX'])): ?>
              <p class="mb-1"><strong>NSX:</strong> <?= htmlspecialchars($vaccine['NSX']) ?></p>
            <?php endif; ?>
            <?php if (!empty($vaccine['MoTa'])): ?>
              <p class="mt-2"><?= htmlspecialchars($vaccine['MoTa']) ?></p>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>
      <!-- Order form -->
      <div class="col-lg-7 mb-3">
        <div class="card">
          <div class="card-body">
            <?php if($m=flash_get('error')): ?>
              <div class="alert alert-danger" role="alert">
                <?= $m ?>
              </div>
            <?php endif; ?>
            <!-- Note: success flash messages are not displayed on this page. They
                 are intended for the payment or appointment pages. This avoids
                 showing payment success notifications when revisiting the order form. -->
            <h3 class="card-title mb-1">Tạo đơn hàng ONL</h3>
            <?php verify_csrf(); ?>
            <form method="post" action="#">
              <!-- Hidden vaccine ID -->
              <?php if (!empty($vx_default)): ?>
                <input type="hidden" name="vaccine" value="<?= htmlspecialchars($vx_default) ?>">
              <?php endif; ?>
              <div class="mb-3">
                <label class="form-label">Chi nhánh</label>
                <select class="form-select" name="branch" required>
                  <option value="">Chọn chi nhánh</option>
                  <?php if (!empty($branches)): ?>
                    <?php foreach ($branches as $br): ?>
                      <option value="<?= htmlspecialchars($br['MaChiNhanh']) ?>" <?= (isset($selected_branch) && $selected_branch === $br['MaChiNhanh']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($br['MaChiNhanh']) ?> - <?= htmlspecialchars($br['TenChiNhanh']) ?> - <?= htmlspecialchars($br['DiaChi'] ?? '') ?>
                      </option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Ngày tiêm</label>
                <?php
                  // Set the minimum selectable date to tomorrow to prevent same-day or past appointments
                  $minDate = (new DateTime('now', new DateTimeZone('Asia/Ho_Chi_Minh')))->add(new DateInterval('P1D'))->format('Y-m-d');
                ?>
                <input type="date" class="form-control" name="date" min="<?= $minDate ?>" value="<?= isset($selected_date) && $selected_date !== '' ? htmlspecialchars($selected_date) : '' ?>" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Giờ tiêm</label>
                <!-- Restrict selection between 07:00 and 20:00 -->
                <input type="time" class="form-control" name="time" min="07:00" max="20:00" value="<?= isset($selected_time) && $selected_time !== '' ? htmlspecialchars($selected_time) : '' ?>" required>
              </div>
              <button class="btn btn-primary" type="submit">Xác nhận đơn hàng</button>
              <?php csrf_field(); ?>
            </form>
            <p class="text-muted small mt-2">Một hóa đơn chỉ cho 1 liều vắc xin duy nhất.</p>
          </div>
        </div>
      </div>
    </div>
    
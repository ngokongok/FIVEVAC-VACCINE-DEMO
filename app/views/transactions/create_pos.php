
<?php
echo '<div class="row">';
include __DIR__ . '/../layout/staff_sidebar.php';
?>
<div class="col-lg-9">
  <div class="card mb-3">
    <div class="card-body">
      <?php if($m=flash_get('error')): ?><div class='alert alert-danger'><?= $m ?></div><?php endif; ?>
      <?php if($m=flash_get('success')): ?><div class='alert alert-success'><?= $m ?></div><?php endif; ?>
      <h3 class="card-title mb-1"><?= isset($title) ? $title : 'Tạo đơn hàng POS' ?></h3>
      <?php verify_csrf(); ?>
      <!-- Form to create a POS order with preloaded lists -->
      <form method="post" action="">
        <div class="mb-3">
          <label class="form-label">Khách hàng (Họ tên - SĐT)</label>
          <select name="customer" class="form-select" required>
            <option value="">-- Chọn khách hàng --</option>
            <?php if (!empty($customers)):
              foreach ($customers as $c):
                $selected = ($c['MaKH'] === ($selected_customer ?? '')) ? 'selected' : '';
            ?>
            <option value="<?= htmlspecialchars($c['MaKH']) ?>" <?= $selected ?> data-phone="<?= htmlspecialchars($c['SDT']) ?>" data-name="<?= htmlspecialchars($c['HoTen']) ?>">
              <?= htmlspecialchars($c['HoTen']) ?> (<?= htmlspecialchars($c['SDT']) ?>)
            </option>
            <?php endforeach; endif; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Vắc xin</label>
          <select name="vaccine" class="form-select" id="vaccineSelect" required>
            <option value="">-- Chọn vắc xin --</option>
            <?php if (!empty($vaccines)):
              foreach ($vaccines as $v):
                $selected = ($v['MaVacXin'] === ($selected_vaccine ?? '')) ? 'selected' : '';
            ?>
            <option value="<?= htmlspecialchars($v['MaVacXin']) ?>" <?= $selected ?> data-price="<?= htmlspecialchars($v['Gia']) ?>">
              <?= htmlspecialchars($v['TenVacXin']) ?> (<?= htmlspecialchars($v['MaVacXin']) ?>)
            </option>
            <?php endforeach; endif; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Đơn giá (VNĐ)</label>
          <input type="text" class="form-control" id="priceField" value="" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Chi nhánh</label>
          <select name="branch" class="form-select" required>
            <option value="">-- Chọn chi nhánh --</option>
            <?php if (!empty($branches)):
              foreach ($branches as $b):
                $selected = ($b['MaChiNhanh'] === ($selected_branch ?? '')) ? 'selected' : '';
            ?>
            <option value="<?= htmlspecialchars($b['MaChiNhanh']) ?>" <?= $selected ?>>
              <?= htmlspecialchars($b['TenChiNhanh']) ?> (<?= htmlspecialchars($b['MaChiNhanh']) ?>)
            </option>
            <?php endforeach; endif; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Hình thức thanh toán</label>
          <select name="payment" class="form-select" required>
            <option value="">-- Chọn hình thức --</option>
            <option value="Tiền mặt" <?= (($selected_payment ?? '') === 'Tiền mặt') ? 'selected' : '' ?>>Tiền mặt</option>
            <option value="Chuyển khoản" <?= (($selected_payment ?? '') === 'Chuyển khoản') ? 'selected' : '' ?>>Chuyển khoản</option>
          </select>
        </div>
        <button class="btn btn-primary">Tạo đơn hàng</button>
        <?php csrf_field(); ?>
      </form>
      <p class="text-muted small">Một đơn hàng chỉ chọn 1 liều vắc xin.</p>
      <script>
        // Auto populate price when vaccine is selected
        document.addEventListener('DOMContentLoaded', function() {
          const vaccineSelect = document.getElementById('vaccineSelect');
          const priceField = document.getElementById('priceField');
          function updatePrice() {
            const selectedOption = vaccineSelect.options[vaccineSelect.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || '';
            priceField.value = price ? parseFloat(price).toLocaleString('vi-VN') : '';
          }
          vaccineSelect.addEventListener('change', updatePrice);
          // Initialise on page load
          updatePrice();
        });
      </script>
    </div>
  </div>
  <!-- Decorative image removed -->
</div>
<?php echo '</div>'; ?>
    
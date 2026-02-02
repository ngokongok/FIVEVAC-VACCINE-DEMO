<?php
/* app/views/inventory/add_vaccine.php
 * Form for administrators to add a new vaccine. Collects vaccine
 * name, manufacture date, expiry date, price and description. On
 * submission, the controller validates input and inserts the record
 * into the database. (UC‑11.1)
 */
?>
<?php
$err  = flash_get('error');
$info = flash_get('info');
echo '<div class="row">';
include __DIR__ . '/../layout/admin_sidebar.php';
?>
<div class="col-lg-9">
  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-body">
          <h3 class="card-title mb-1"><?= htmlspecialchars($title ?? 'Thêm vắc xin') ?></h3>
          <?php
          if ($err) {
              echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($err) . '</div>';
          }
          if ($info) {
              echo '<div class="alert alert-success" role="alert">' . htmlspecialchars($info) . '</div>';
          }
          verify_csrf();
          ?>
          <form method="post" action="#">
            <div class="mb-3">
              <label class="form-label">Tên vắc xin</label>
              <input type="text" class="form-control" name="name" placeholder="Nhập tên vắc xin" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Ngày sản xuất (NSX)</label>
              <input type="date" class="form-control" name="nsx" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Hạn sử dụng (HSD)</label>
              <input type="date" class="form-control" name="hsd" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Giá (VNĐ)</label>
              <input type="number" step="0.01" min="0" class="form-control" name="price" placeholder="Nhập giá" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Mô tả</label>
              <textarea class="form-control" name="desc" rows="3" placeholder="Mô tả vắc xin (tuỳ chọn)"></textarea>
            </div>
            <button class="btn btn-primary">Thêm vắc xin</button>
            <?php csrf_field(); ?>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <!-- Decorative image removed -->
    </div>
  </div>
</div>
<?php echo '</div>'; ?>
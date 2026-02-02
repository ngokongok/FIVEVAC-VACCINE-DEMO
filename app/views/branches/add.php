<?php
/* app/views/branches/add.php
 * Form for administrators to add a new branch. Collects branch name,
 * address and status (active/inactive). On submission, the
 * controller validates and inserts the new record. (UC‑12.1)
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
          <h3 class="card-title mb-1"><?= htmlspecialchars($title ?? 'Thêm chi nhánh') ?></h3>
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
              <label class="form-label">Tên chi nhánh</label>
              <input type="text" class="form-control" name="name" placeholder="Nhập tên chi nhánh" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Địa chỉ</label>
              <textarea class="form-control" name="address" rows="3" placeholder="Nhập địa chỉ" required></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Trạng thái hoạt động</label>
              <select name="status" class="form-select">
                <option value="Hoạt động" selected>Hoạt động</option>
                <option value="Ngừng hoạt động">Ngừng hoạt động</option>
              </select>
            </div>
            <button class="btn btn-primary">Thêm chi nhánh</button>
            <?php csrf_field(); ?>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <!-- Decorative image -->
      <!-- Decorative image removed -->
    </div>
  </div>
</div>
<?php echo '</div>'; ?>
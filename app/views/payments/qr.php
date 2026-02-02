
    <div class="row">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <?php if($m=flash_get('error')): ?><div class='alert alert-danger'><?= $m ?></div><?php endif; ?>
            <?php if($m=flash_get('success')): ?><div class='alert alert-success'><?= $m ?></div><?php endif; ?>
            <h3 class="card-title mb-1">Thanh toán</h3>
            <p>Vui lòng quét mã QR bằng ứng dụng ví điện tử để hoàn tất thanh toán.</p>
            <?php verify_csrf(); ?>
            <form id="paymentForm" method="post" action="#">
              <!-- Submit with CSRF token to simulate payment confirmation -->
              <?php csrf_field(); ?>
              <button type="submit" class="btn btn-primary">Tôi đã quét mã</button>
            </form>
            <p class="text-muted small mt-2">
              Mỗi mã QR chỉ có hiệu lực trong 10 phút
              <span id="countdown" class="ms-1 fw-bold"></span>.
            </p>
            <?php
              // Previously a button allowed returning to the order details for editing.
              // This feature has been removed to simplify the workflow and avoid errors.
            ?>
            <!-- Expired message displayed after countdown finishes -->
            <div id="expiredMessage" class="alert alert-danger mt-3" style="display:none;">
              Giao dịch thất bại do quá thời hạn thanh toán.
              <a href="<?= base_url('vaccines') ?>" class="btn btn-sm btn-secondary ms-2">Quay về trang đặt mua</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <img id="qrImage" class="img-fluid rounded shadow-sm" alt="QR code" src="<?= base_url('assets/images/qr_code.png') ?>">
      </div>
    </div>

<!-- Countdown timer script: hides QR and form after 10 minutes -->
<script>
  (function(){
    var remaining = 600; // 600 seconds = 10 minutes
    var countdownEl = document.getElementById('countdown');
    var form = document.getElementById('paymentForm');
    var qr = document.getElementById('qrImage');
    var expiredMsg = document.getElementById('expiredMessage');
    function update(){
      if (remaining <= 0){
        clearInterval(timer);
        if (form) form.style.display = 'none';
        if (qr) qr.style.display = 'none';
        if (expiredMsg) expiredMsg.style.display = 'block';
        return;
      }
      var minutes = Math.floor(remaining / 60);
      var seconds = remaining % 60;
      if (countdownEl) {
        countdownEl.textContent = '(' + String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0') + ')';
      }
      remaining--;
    }
    update();
    var timer = setInterval(update, 1000);
  })();
</script>
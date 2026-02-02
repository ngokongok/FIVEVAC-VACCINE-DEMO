<?php /* app/views/auth/reset_otp.php */ ?>
<div class="row justify-content-center mt-5">
  <div class="col-md-6 col-lg-4">
    <div class="card shadow-sm border-0">
      <div class="card-body p-4 text-center">
        <h3 class="card-title mb-4">Xác thực OTP</h3>
        <?php if ($err = flash_get('error')): ?>
          <div class="alert alert-danger mb-3"><?= $err ?></div>
        <?php endif; ?>
        <?php if ($info = flash_get('info')): ?>
          <div class="alert alert-success mb-3"><?= $info ?></div>
        <?php endif; ?>
        <p>Mã OTP đã được gửi tới số điện thoại <strong><?= htmlspecialchars($_SESSION['reset_phone'] ?? '') ?></strong>.</p>
        <!-- Display countdown timer -->
        <p>Mã có hiệu lực: <span id="timer" class="fw-bold">01:00</span></p>
        <div id="otp-container">
          <form method="post" action="<?= base_url('auth/reset_otp' . (isset($_GET['staff']) ? '&staff=1' : '')) ?>" class="d-flex flex-column align-items-center mb-3">
            <?php csrf_field(); ?>
            <div class="d-flex justify-content-center mb-3">
              <!-- Six small boxes for OTP digits -->
              <?php for ($i = 0; $i < 6; $i++): ?>
                <input type="text" name="digit[]" maxlength="1" class="form-control text-center me-1" style="width: 40px; height: 50px; font-size: 24px;" required>
              <?php endfor; ?>
            </div>
            <button type="submit" class="btn btn-primary">Xác thực</button>
          </form>
        </div>
        <!-- Message to display when OTP is expired -->
        <div id="otp-expired-msg" class="alert alert-warning d-none">Mã OTP đã hết hiệu lực.</div>
        <p class="mb-0">Không nhận được mã? <a href="<?= base_url('auth/send_reset_otp' . (isset($_GET['staff']) ? '&staff=1' : '')) ?>">Gửi lại</a></p>
      </div>
    </div>
  </div>
</div>
<!-- Auto-focus script for OTP boxes and countdown timer script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const inputs = document.querySelectorAll('input[name="digit[]"]');
  if (inputs.length) {
    inputs[0].focus();
    inputs.forEach((input, idx) => {
      input.addEventListener('input', () => {
        if (input.value.length === input.maxLength && idx < inputs.length - 1) {
          inputs[idx + 1].focus();
        }
      });
      input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && input.value === '' && idx > 0) {
          inputs[idx - 1].focus();
        }
      });
    });
  }
  // Countdown timer
  const expiry = <?= isset($_SESSION['reset_expiry']) ? $_SESSION['reset_expiry'] : 0 ?>;
  const serverNow = <?= time() ?>;
  let timeLeft = Math.max(expiry - serverNow, 0);
  function formatTime(sec) {
    const m = Math.floor(sec / 60);
    const s = sec % 60;
    return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
  }
  const timerEl = document.getElementById('timer');
  const otpContainer = document.getElementById('otp-container');
  const expiredMsg = document.getElementById('otp-expired-msg');
  if (timerEl) timerEl.textContent = formatTime(timeLeft);
  const countdown = setInterval(() => {
    timeLeft--;
    if (timeLeft <= 0) {
      clearInterval(countdown);
      if (otpContainer) otpContainer.classList.add('d-none');
      if (expiredMsg) expiredMsg.classList.remove('d-none');
      if (timerEl) timerEl.textContent = '00:00';
    } else {
      if (timerEl) timerEl.textContent = formatTime(timeLeft);
    }
  }, 1000);
});
</script>
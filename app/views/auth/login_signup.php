<?php /* app/views/auth/login_signup.php */ ?>
<!-- Full-height container with subtle primary background for a modern look -->
<div class="py-5" style="background-color: #eaf2ff; min-height: calc(100vh - 64px);">
  <div class="container">
    <?php if ($m = flash_get('error')): ?>
      <div class="row justify-content-center mb-4">
        <div class="col-md-8">
          <div class="alert alert-danger text-center mb-0"><?= $m ?></div>
        </div>
      </div>
    <?php endif; ?>
    <?php if ($m = flash_get('info')): ?>
      <div class="row justify-content-center mb-4">
        <div class="col-md-8">
          <div class="alert alert-success text-center mb-0"><?= $m ?></div>
        </div>
      </div>
    <?php endif; ?>
    <div class="row justify-content-center">
      <!-- Only show the login card on the combined page. Registration now starts on a separate page. -->
      <div class="col-lg-5 col-md-6">
        <div class="card shadow-sm border-0">
          <div class="card-body p-4">
            <h3 class="card-title mb-4 text-center">Đăng nhập</h3>
            <form method="post" action="<?= base_url('auth/login') ?>">
              <?php csrf_field(); ?>
              <div class="mb-3">
                <label class="form-label">Số điện thoại</label>
                <input type="text" class="form-control form-control-lg" name="phone" required placeholder="090xxxxxxxx" value="<?= isset($submitted_phone) ? htmlspecialchars($submitted_phone) : '' ?>">
              </div>
              <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <div class="input-group">
                  <input type="password" class="form-control form-control-lg" name="password" id="loginPassword" required placeholder="Nhập mật khẩu">
                  <button type="button" class="btn btn-outline-secondary" id="togglePassword" tabindex="-1" aria-label="Hiển thị/Ẩn mật khẩu">
                    <!-- Eye open icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="eye-open" viewBox="0 0 16 16">
                      <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.173 8z"></path>
                      <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"></path>
                    </svg>
                    <!-- Eye slash icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="eye-close d-none" viewBox="0 0 16 16">
                      <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"></path>
                      <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"></path>
                      <path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12-.708.708z"></path>
                    </svg>
                  </button>
                </div>
              </div>
              <button class="btn btn-primary w-100 mb-3" type="submit">Đăng nhập</button>
              <p class="text-center mb-0">Chưa có tài khoản? <a href="<?= base_url('auth/register_phone') ?>">Đăng ký</a></p>
              <p class="text-center mt-2"><a href="<?= base_url('auth/reset') ?>">Quên mật khẩu?</a></p>
            </form>
            <!-- Script to toggle password visibility -->
            <script>
            document.addEventListener('DOMContentLoaded', () => {
              const toggle = document.getElementById('togglePassword');
              if (toggle) {
                toggle.addEventListener('click', () => {
                  const pwd = document.getElementById('loginPassword');
                  if (!pwd) return;
                  const type = pwd.getAttribute('type') === 'password' ? 'text' : 'password';
                  pwd.setAttribute('type', type);
                  const eyeOpen = toggle.querySelector('.eye-open');
                  const eyeClose = toggle.querySelector('.eye-close');
                  if (eyeOpen && eyeClose) {
                    eyeOpen.classList.toggle('d-none');
                    eyeClose.classList.toggle('d-none');
                  }
                });
              }
            });
            </script>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
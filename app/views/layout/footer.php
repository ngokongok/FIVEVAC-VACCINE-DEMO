
  </main>
<?php
// Prevent direct access to view files by ensuring the app is loaded through index.php
if (!defined('IN_APP')) {
    http_response_code(404);
    exit;
}
?>
    <footer class="footer border-top py-4 mt-5">
      <div class="container">
        <div class="row">
          <div class="col-md-6 mb-3">
            <h6 class="fw-bold mb-2">Công ty Cổ phần Vaccine Trung Ương 5</h6>
            <p class="mb-1 small">Số đăng ký kinh doanh: 0109685834 do Sở Kế hoạch và Đầu tư Thành phố Hà Nội cấp lần đầu ngày 28 tháng 06 năm 2021</p>
            <p class="small">Liên hệ: 1800 6468 72</p>
          </div>
          <div class="col-md-6 mb-3">
            <h6 class="fw-bold mb-2">Hệ thống Trung tâm tiêm chủng vắc xin Fivevac</h6>
            <p class="mb-1 small">Fivevac Thanh Trì: Km13 đường Ngọc Hồi, Thanh Trì, Hà Nội</p>
            <p class="mb-1 small">Fivevac Bắc Từ Liêm: C38TTa2 KĐT thành phố giao lưu, Đường 23, Cổ Nhuế, Bắc Từ Liêm, Hà Nội</p>
            <p class="mb-2 small">Fivevac Mê Linh: Khu phố, xã Kim Hoa, Mê Linh, Hà Nội</p>
            <p class="small">Hotline: 1800 64 68 72</p>
          </div>
        </div>
        <div class="text-center pt-3 small">© <?= date('Y') ?> Fivevac. All rights reserved.</div>
      </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

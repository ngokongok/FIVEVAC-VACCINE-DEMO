<?php
// app/views/home/home.php – redesigned customer landing page
// Expect variables: $heroTitle, $heroSubtitle, $stats (array with keys branches, orders, staff)
?>

<!-- Hero carousel with interactive slides -->
<div id="heroCarousel" class="carousel slide mb-5" data-bs-ride="carousel" data-bs-interval="3000">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner rounded shadow-sm">
    <!-- Slide 1: primary call to action -->
    <div class="carousel-item active">
      <img src="<?= base_url('assets/images/fivevac2.jpg') ?>" class="d-block w-100" alt="Fivevac vaccination">
      <div class="carousel-caption d-none d-md-block text-start">
        <h2 class="fw-bold"><?= htmlspecialchars($heroTitle) ?></h2>
        <p><?= htmlspecialchars($heroSubtitle) ?></p>
        <a class="btn btn-primary" href="<?= base_url('vaccines') ?>">Đặt vắc xin</a>
      </div>
    </div>
    <!-- Slide 2: consultation promotion -->
    <div class="carousel-item">
      <img src="<?= base_url('assets/images/fivevac3.jpg') ?>" class="d-block w-100" alt="Fivevac consultation">
      <div class="carousel-caption d-none d-md-block text-start">
        <h2 class="fw-bold">Tư vấn và chăm sóc tận tâm</h2>
        <p>Đội ngũ bác sĩ chuyên nghiệp luôn sẵn sàng hỗ trợ.</p>
        <a class="btn btn-primary" href="<?= base_url('consult/request') ?>">Tư vấn ngay</a>
      </div>
    </div>
    <!-- Slide 3: records management promotion -->
    <div class="carousel-item">
      <img src="<?= base_url('assets/images/fivevac4.jpg') ?>" class="d-block w-100" alt="Fivevac records">
      <div class="carousel-caption d-none d-md-block text-start">
        <h2 class="fw-bold">Tra cứu hồ sơ tiêm chủng</h2>
        <p>Quản lý lịch sử tiêm chủng của bạn và gia đình một cách dễ dàng.</p>
        <a class="btn btn-primary" href="<?= base_url('records/search') ?>">Tra cứu hồ sơ</a>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<!-- Mission and statistics section with animated counters -->
<section class="stats-gradient text-white py-5">
  <div class="container text-center">
    <h2 class="fw-bold mb-2">Fivevac - Trung tâm tiêm chủng hàng đầu Việt Nam</h2>
    <p class="mb-4">Cung cấp giải pháp vaccine giúp bảo vệ trẻ em và người lớn. Hoạt động với phương châm "Y Đức - Uy tín - Niềm Tin - Trách Nhiệm".</p>
    <div class="row">
      <div class="col-12 col-md-4 mb-3 mb-md-0">
        <div class="count display-5 fw-bold" data-count="<?= isset($stats['branches']) ? (int)$stats['branches'] : 0 ?>">0</div>
        <div class="label">Chi nhánh</div>
      </div>
      <div class="col-12 col-md-4 mb-3 mb-md-0">
        <div class="count display-5 fw-bold" data-count="<?= isset($stats['orders']) ? (int)$stats['orders'] : 0 ?>">0</div>
        <div class="label">Đơn hàng</div>
      </div>
      <div class="col-12 col-md-4 mb-3 mb-md-0">
        <div class="count display-5 fw-bold" data-count="<?= isset($stats['staff']) ? (int)$stats['staff'] : 0 ?>">0</div>
        <div class="label">Nhân viên</div>
      </div>
    </div>
  </div>
</section>

<!-- Featured news section -->
<section class="py-5 bg-light">
  <div class="container container-narrow">
    <h2 class="fw-bold mb-4">Tin nổi bật</h2>
    <div class="row g-4">
      
      <div class="col-md-6">
        <div class="card h-100 border-0 shadow-sm">
          
          <img src="<?= base_url('assets/images/fivevac2.jpg') ?>" class="card-img-top" alt="Ưu đãi Fivevac">
          <div class="card-body">
            <h5 class="card-title">Fivevac bùng nổ ưu đãi lớn mừng tháng hành động vì trẻ em</h5>
            <p class="card-text">Nhân tháng hành động vì trẻ em, Fivevac triển khai chương trình khuyến mãi đặc biệt dành cho khách hàng tiêm chủng. Ưu đãi hấp dẫn giúp gia đình tiết kiệm chi phí và bảo vệ sức khỏe.</p>
            <a href="https://fivevac.com.vn" target="_blank" class="btn btn-primary">Xem thêm</a>
          </div>
        </div>
      </div>
      <!-- Smaller articles list -->
      <div class="col-md-6 d-flex flex-column gap-3">
        <div class="d-flex border rounded shadow-sm overflow-hidden">
          <img src="<?= base_url('assets/images/vx_hepb.jpg') ?>" alt="Tin vaccine 1" class="img-fluid" style="width: 120px; object-fit: cover;">
          <div class="p-3 flex-grow-1">
            <h6 class="mb-1"><a href="https://vnvc.vn" target="_blank" class="text-decoration-none text-dark">Tổng hợp những điều cần biết về viêm gan B</a></h6>
            <small class="text-muted">Viêm gan B là bệnh truyền nhiễm nguy hiểm nhưng có thể phòng ngừa bằng vắc xin. Tìm hiểu những lưu ý quan trọng khi tiêm chủng.</small>
          </div>
        </div>
        <div class="d-flex border rounded shadow-sm overflow-hidden">
          <img src="<?= base_url('assets/images/vx_pfizer.jpg') ?>" alt="Tin vaccine 2" class="img-fluid" style="width: 120px; object-fit: cover;">
          <div class="p-3 flex-grow-1">
            <h6 class="mb-1"><a href="https://vnvc.vn" target="_blank" class="text-decoration-none text-dark">Fivevac tung siêu ưu đãi tiêm chủng chào hè</a></h6>
            <small class="text-muted">Đón mùa hè sôi động với những ưu đãi tiêm chủng hấp dẫn từ Fivevac. Bảo vệ sức khỏe, nhận ngay quà tặng giá trị.</small>
          </div>
        </div>
        <div class="d-flex border rounded shadow-sm overflow-hidden">
          <img src="<?= base_url('assets/images/vx_flu.jpg') ?>" alt="Tin vaccine 3" class="img-fluid" style="width: 120px; object-fit: cover;">
          <div class="p-3 flex-grow-1">
            <h6 class="mb-1"><a href="https://vnvc.vn" target="_blank" class="text-decoration-none text-dark">Bạn đã hiểu hết về bệnh cúm mùa?</a></h6>
            <small class="text-muted">Cúm mùa có thể gây biến chứng nghiêm trọng. Tìm hiểu tại sao tiêm phòng cúm định kỳ lại quan trọng cho cả gia đình.</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Counter animation script -->
<script>
// Animate counters when the statistics section enters the viewport
document.addEventListener('DOMContentLoaded', function () {
  const section = document.querySelector('.stats-gradient');
  if (!section) return;
  const counters = section.querySelectorAll('.count');
  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        counters.forEach(counter => {
          const target = parseInt(counter.dataset.count);
          let current = 0;
          const increment = Math.max(1, Math.ceil(target / 120));
          const update = () => {
            current += increment;
            if (current >= target) {
              counter.textContent = target + '+';
            } else {
              counter.textContent = current;
              requestAnimationFrame(update);
            }
          };
          update();
        });
        obs.unobserve(entry.target);
      }
    });
  }, { threshold: 0.3 });
  observer.observe(section);
});
</script>

<!-- Testimonials section -->
<section class="my-5">
  <div class="container container-narrow">
    <div class="text-center mb-4">
      <small class="text-primary fw-bold">Đánh giá của khách hàng</small>
      <h2 class="fw-bold mt-2">Mọi người nói gì về Fivevac</h2>
    </div>
    <div class="row g-4">
      <div class="col-12 col-md-4">
        <div class="card h-100 testimonial-card p-4">
          <div class="quote fs-2 text-primary mb-3">“</div>
          <p class="card-text fst-italic">Chất lượng dịch vụ tuyệt vời! Nhân viên Fivevac rất nhiệt tình và hỗ trợ chu đáo trong suốt quá trình tiêm phòng.</p>
          <h6 class="fw-bold mt-4 mb-0">Nguyễn Văn A</h6>
          <small class="text-muted">Phụ huynh</small>
          <div class="rating mt-2">&#9733; &#9733; &#9733; &#9733; &#9733;</div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="card h-100 testimonial-card p-4">
          <div class="quote fs-2 text-primary mb-3">“</div>
          <p class="card-text fst-italic">Tôi cảm thấy yên tâm khi cho con tiêm tại Fivevac. Hệ thống đặt lịch trực tuyến rất tiện lợi và nhanh chóng.</p>
          <h6 class="fw-bold mt-4 mb-0">Trần Thị B</h6>
          <small class="text-muted">Mẹ bầu</small>
          <div class="rating mt-2">&#9733; &#9733; &#9733; &#9733; &#9734;</div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="card h-100 testimonial-card p-4">
          <div class="quote fs-2 text-primary mb-3">“</div>
          <p class="card-text fst-italic">Fivevac luôn cập nhật đầy đủ thông tin và tư vấn tận tình. Tôi rất hài lòng với dịch vụ tại đây.</p>
          <h6 class="fw-bold mt-4 mb-0">Lê Minh C</h6>
          <small class="text-muted">Doanh nhân</small>
          <div class="rating mt-2">&#9733; &#9733; &#9733; &#9733; &#9733;</div>
        </div>
      </div>
    </div>
  </div>
</section>
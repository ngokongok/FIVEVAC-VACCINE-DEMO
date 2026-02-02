<?php
/* app/views/admin/dashboard.php
 * Admin dashboard displaying annual revenue, quarterly revenue per
 * branch, today's appointments and stock levels. The dashboard
 * utilises the admin sidebar on the left and hides the top navbar.
 * Data is provided via the AdminController. (UC‑11)
 */
?>
<?php
// Wrap the sidebar and main content in a Bootstrap row. The sidebar
// occupies the left column (col‑lg‑3) and the main content fills
// the remaining space (col‑lg‑9).
echo '<div class="row">';
// Include the admin sidebar. This file prints a navigation menu.
include __DIR__ . '/../layout/admin_sidebar.php';
?>
<div class="col-lg-9">
  <h3 class="mb-3"><?= htmlspecialchars($title ?? 'Dashboard') ?></h3>
  <!-- Annual revenue chart -->
  <div class="card mb-3">
    <div class="card-body">
      <h5 class="card-title">Doanh thu theo năm</h5>
      <p class="text-secondary">Tổng hợp doanh thu toàn hệ thống theo từng năm.</p>
      <?php if (!empty($annualRevenue)): ?>
        <canvas id="annualChart" height="200"></canvas>
      <?php else: ?>
        <p>Không có dữ liệu doanh thu.</p>
      <?php endif; ?>
    </div>
  </div>
  <!-- Quarterly revenue by branch chart -->
  <div class="card mb-3">
    <div class="card-body">
      <h5 class="card-title">Doanh thu theo quý (năm hiện tại)</h5>
      <p class="text-secondary">Doanh thu của từng chi nhánh theo các quý trong năm nay.</p>
      <?php
      // Prepare branch data for chart: group quarters per branch
      $branchData = [];
      if (!empty($quarterRevenue)) {
          foreach ($quarterRevenue as $qr) {
              $branch = $qr['MaChiNhanh'];
              $quarter = (int)$qr['quy'];
              $val = (float)$qr['tong'];
              if (!isset($branchData[$branch])) {
                  $branchData[$branch] = [1=>0,2=>0,3=>0,4=>0];
              }
              $branchData[$branch][$quarter] = $val;
          }
      }

      // Build datasets with colours for each branch. We predefine a palette
      // of distinct colours and assign them in sequence. These datasets
      // will be JSON‑encoded and used in the Chart.js configuration.
      $quarterDatasets = [];
      if (!empty($branchData)) {
          $palette = [
              '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
              '#FF9F40', '#8E44AD', '#E67E22', '#16A085', '#27AE60',
              '#F39C12', '#D35400', '#2ECC71', '#34495E', '#95A5A6'
          ];
          $i = 0;
          foreach ($branchData as $code => $quarters) {
              $color = $palette[$i % count($palette)];
              $quarterDatasets[] = [
                  'label' => $code,
                  'data' => array_values($quarters),
                  'borderColor' => $color,
                  'backgroundColor' => $color,
                  'fill' => false,
                  'borderWidth' => 2
              ];
              $i++;
          }
      }
      ?>
      <?php if (!empty($branchData)): ?>
        <canvas id="quarterChart" height="200"></canvas>
      <?php else: ?>
        <p>Không có dữ liệu doanh thu theo quý.</p>
      <?php endif; ?>
    </div>
  </div>
  <!-- Stock chart by branch -->
  <div class="card mb-3">
    <div class="card-body">
      <h5 class="card-title">Tồn kho theo chi nhánh</h5>
      <p class="text-secondary">Số lượng vắc xin khả dụng tại mỗi chi nhánh.</p>
      <?php if (!empty($stock)): ?>
        <canvas id="stockChart" height="200"></canvas>
      <?php else: ?>
        <p>Không có dữ liệu tồn kho.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Today's appointments table -->
  <div class="card mb-3">
    <div class="card-body">
      <h5 class="card-title">Lịch hẹn hôm nay</h5>
      <p class="text-secondary">Danh sách lịch hẹn trong ngày hiện tại.</p>
      <?php if (empty($apm)): ?>
        <p>Không có lịch hẹn.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-sm">
            <thead>
              <tr><th>Giờ</th><th>Mã hẹn</th><th>Chi nhánh</th><th>Vắc xin</th><th>Trạng thái</th></tr>
            </thead>
            <tbody>
              <?php foreach ($apm as $r): ?>
                <tr>
                  <td><?= htmlspecialchars($r['giotiem']) ?></td>
                  <td><?= htmlspecialchars($r['id']) ?></td>
                  <td><?= htmlspecialchars($r['machinhanh']) ?></td>
                  <td><?= htmlspecialchars($r['mavacxin']) ?></td>
                  <td><?= htmlspecialchars($r['trangthai']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <!-- Stock by branch table -->
  <!-- Removed the redundant stock table card. -->
</div> <!-- .col-lg-9 -->
<?php
// Close the wrapping row opened above.
echo '</div>';
?>

<?php if (!empty($annualRevenue) || !empty($branchData) || !empty($stock)): ?>
<!-- Include Chart.js from CDN. Chart.js is used to render modern interactive
     charts on the admin dashboard. Loading it conditionally avoids
     unnecessary network requests when there is no data to display. -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Annual revenue chart data: labels are years, data are totals (in VND).
  <?php if (!empty($annualRevenue)): ?>
  const annualLabels = <?= json_encode(array_column($annualRevenue, 'nam')) ?>;
  const annualValues = <?= json_encode(array_map(function($row){ return (float)$row['tong']; }, $annualRevenue)) ?>;
  const annualCtx = document.getElementById('annualChart').getContext('2d');
  new Chart(annualCtx, {
    type: 'bar',
    data: {
      labels: annualLabels,
      datasets: [{
        label: 'Doanh thu (VNĐ)',
        data: annualValues,
        backgroundColor: 'rgba(54, 162, 235, 0.5)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            // Format ticks with thousands separators for readability
            callback: function(value) {
              return value.toLocaleString('vi-VN');
            }
          }
        }
      },
      plugins: {
        legend: { display: false }
      }
    }
  });
  <?php endif; ?>

  // Quarterly revenue by branch chart: each branch is a dataset with four values
  <?php if (!empty($branchData)): ?>
  const quarterLabels = ['Q1', 'Q2', 'Q3', 'Q4'];
  const quarterDatasets = <?= json_encode($quarterDatasets) ?>;
  const quarterCtx = document.getElementById('quarterChart').getContext('2d');
  new Chart(quarterCtx, {
    type: 'line',
    data: {
      labels: quarterLabels,
      datasets: quarterDatasets
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function (value) {
              return value.toLocaleString('vi-VN');
            }
          }
        }
      },
      plugins: {
        legend: {
          position: 'bottom'
        }
      }
    }
  });
  <?php endif; ?>

  // Stock chart: show available vaccine quantity per branch as a horizontal bar chart
  <?php if (!empty($stock)): ?>
  const stockLabels = <?= json_encode(array_column($stock, 'MaChiNhanh')) ?>;
  const stockValues = <?= json_encode(array_map(function($row){ return (int)$row['Khadung']; }, $stock)) ?>;
  const stockCtx = document.getElementById('stockChart').getContext('2d');
  new Chart(stockCtx, {
    type: 'bar',
    data: {
      labels: stockLabels,
      datasets: [{
        label: 'Số lượng khả dụng',
        data: stockValues,
        backgroundColor: 'rgba(255, 99, 132, 0.5)',
        borderColor: 'rgba(255, 99, 132, 1)',
        borderWidth: 1
      }]
    },
    options: {
      indexAxis: 'y', // horizontal bar chart
      responsive: true,
      scales: {
        x: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return value.toLocaleString('vi-VN');
            }
          }
        }
      },
      plugins: {
        legend: { display: false }
      }
    }
  });
  <?php endif; ?>
});
</script>
<?php endif; ?>
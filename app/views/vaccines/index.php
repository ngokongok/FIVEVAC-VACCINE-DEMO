<?php
/*
 * app/views/vaccines/index.php
 *
 * Display a responsive grid of vaccine products. Each product card shows a
 * representative image, the vaccine name, a truncated description and
 * price. Hovering over a card reveals a call-to-action button to order
 * the vaccine. Guests are allowed to browse this page; attempting to
 * purchase will trigger the login flow handled in VaccinesController.
 */
?>

<h3 class="mb-3">Danh sách vắc xin</h3>
<p class="text-secondary">Chọn vắc xin phù hợp và nhấn "Đặt mua" để tiến hành đặt lịch.</p>

<?php
    // Define a mapping between vaccine codes and specific images.  Each
    // vaccine in the catalogue has a corresponding image saved in
    // public/assets/images.  If a vaccine code is not present in this map
    // the application falls back to a small set of generic images.
    $imgMap = [
        'VX001' => 'vx_hepb.jpg',      // Hepatitis B
        'VX002' => 'vx_flu.jpg',       // Influenza (seasonal flu)
        'VX003' => 'vx_mmr.jpg',       // Measles, mumps and rubella
        'VX004' => 'vx_dtp.jpg',       // Diphtheria, tetanus and pertussis (DTP)
        'VX005' => 'vx_hpv.jpg',       // Human papillomavirus (HPV)
        'VX006' => 'vx_pfizer.jpg',    // Pfizer‑BioNTech COVID‑19 vaccine
        // Additional vaccines with dedicated images
        'VX007' => 'vx_astra.jpg',     // AstraZeneca COVID‑19 vaccine
        'VX008' => 'vx_tetanus.jpg',   // Tetanus
        'VX009' => 'vx_polio.jpg',     // Polio
        'VX010' => 'vx_hepa.jpg'       // Hepatitis A
    ];
    // Fallback images for vaccines not listed above
    $imgFiles = ['fivevac2.jpg','fivevac3.jpg','fivevac4.jpg'];
?>

<style>
    /* Product card styles: ensure images cover the card and overlay appears on hover */
    .product-card {
        position: relative;
        overflow: hidden;
        border: 1px solid #eaeaea;
        border-radius: 0.5rem;
        transition: box-shadow 0.2s ease;
    }
    .product-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .product-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
    .product-card .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.5);
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    .product-card:hover .overlay {
        opacity: 1;
    }
</style>

<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php foreach ($vaccines as $v): ?>
        <?php
            // Determine image: if the vaccine code has a specific image
            // defined in $imgMap use it; otherwise fall back to a hashed
            // selection from the generic images.  Hashing ensures the
            // same vaccine always maps to the same generic image.
            $hash = abs(crc32($v['MaVacXin']));
            $img = isset($imgMap[$v['MaVacXin']])
                ? $imgMap[$v['MaVacXin']]
                : $imgFiles[$hash % count($imgFiles)];
            // Truncate the description to a manageable length
            $desc = $v['MoTa'] ?? '';
            $desc = mb_strlen($desc) > 80 ? mb_substr($desc, 0, 77) . '...' : $desc;
            // Format price with thousand separators
            $price = isset($v['Gia']) ? number_format($v['Gia'], 0, ',', '.') . ' đ' : '';
        ?>
        <div class="col">
            <div class="product-card">
                <img src="<?= base_url('assets/images/' . $img) ?>" alt="<?= htmlspecialchars($v['TenVacXin']) ?>">
                <div class="p-3">
                    <h5 class="mb-1">
                        <?= htmlspecialchars($v['TenVacXin']) ?>
                    </h5>
                    <p class="mb-1 text-muted" style="font-size: 0.9rem; min-height: 3rem;">
                        <?= htmlspecialchars($desc) ?>
                    </p>
                    <p class="fw-bold text-primary mb-0">
                        <?= htmlspecialchars($price) ?>
                    </p>
                </div>
                <div class="overlay">
                    <?php if (!empty($v['AvailableQuantity']) && intval($v['AvailableQuantity']) > 0): ?>
                        <a href="<?= base_url('vaccines/order/' . urlencode($v['MaVacXin'])) ?>" class="btn btn-danger">Đặt mua</a>
                    <?php else: ?>
                        <span class="badge bg-secondary fs-6">Hết hàng</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
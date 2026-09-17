<div class="container mt-5">
  <div class="strip-header d-flex justify-content-between flex-wrap align-items-center gap-2 mb-4">
    <h4 class="fs-4 strip-header-title mb-0"><?php echo htmlspecialchars($stripTitle); ?></h4>
    <a href="shop?c=category&p=<?php echo urlencode($stripCategory); ?>" class="strip-header-link">
      View All <i class="fas fa-arrow-right ms-1"></i>
    </a>
  </div>

  <?php if (empty($stripProducts)): ?>
    <p class="text-muted mb-0">No products in this category yet.</p>
  <?php else: ?>
    <div class="row g-3">
      <?php foreach ($stripProducts as $product): ?>
        <div class="col-6 col-md-4 col-lg-3">
          <?php include('product_card.php'); ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

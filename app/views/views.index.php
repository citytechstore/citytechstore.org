<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require_once ('includes/cdn_header.php'); ?>
  <title>
  <?php echo APP_NAME; ?>
  </title>
</head>

<body>
  <?php require_once ('includes/header.php'); ?>

  <!-- Hero carousel + promo tiles -->
  <div class="container mt-4">
    <div class="row g-3">
      <div class="col-lg-8">
        <?php if (!empty($heroSlides)): ?>
          <div id="heroCarousel" class="carousel slide hero-carousel h-100" data-bs-ride="carousel">
            <div class="carousel-indicators">
              <?php foreach ($heroSlides as $slideIndex => $slide): ?>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?php echo $slideIndex; ?>"
                  <?php echo $slideIndex === 0 ? 'class="active" aria-current="true"' : ''; ?>
                  aria-label="Slide <?php echo $slideIndex + 1; ?>"></button>
              <?php endforeach; ?>
            </div>
            <div class="carousel-inner h-100">
              <?php foreach ($heroSlides as $slideIndex => $slide): ?>
                <div class="carousel-item h-100 <?php echo $slideIndex === 0 ? 'active' : ''; ?>">
                  <div class="hero-banner hero-banner-placeholder h-100">
                    <i class="fas <?php echo htmlspecialchars($slide['icon']); ?> hero-banner-placeholder-icon"></i>
                    <div class="hero-banner-content">
                      <span class="hero-badge"><?php echo htmlspecialchars($slide['badge']); ?></span>
                      <h1 class="fs-1"><?php echo htmlspecialchars($slide['title']); ?></h1>
                      <p class="fs-5"><?php echo htmlspecialchars($slide['description']); ?></p>
                      <a href="<?php echo htmlspecialchars($slide['link']); ?>" class="btn hero-shop-btn btn-lg">
                        <?php echo htmlspecialchars($slide['linkText']); ?>
                      </a>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php else: ?>
          <div class="hero-banner hero-banner-placeholder h-100">
            <i class="fas fa-store hero-banner-placeholder-icon"></i>
            <div class="hero-banner-content">
              <h1 class="fs-1">Welcome to <?php echo htmlspecialchars(APP_NAME); ?></h1>
              <p class="fs-5">
                Your trusted destination for quality tech products and accessories.
              </p>
              <a href="shop" class="btn hero-shop-btn btn-lg">Shop Now</a>
            </div>
          </div>
        <?php endif; ?>
      </div>
      <div class="col-lg-4">
        <div class="row g-3 h-100">
          <div class="col-12 col-sm-6 col-lg-12">
            <a href="shop" class="hero-promo-tile">
              <i class="fas fa-store"></i>
              <span>Shop All Products</span>
            </a>
          </div>
          <div class="col-12 col-sm-6 col-lg-12">
            <a href="brands" class="hero-promo-tile">
              <i class="fas fa-tags"></i>
              <span>Shop by Brand</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Category quicklinks: real category data from $homeCategoryStrips, not
       a hardcoded list — a category only appears here once it has products,
       same rule product_strip.php follows for its own empty-state. -->
  <?php $quicklinkStrips = array_filter($homeCategoryStrips, fn($strip) => !empty($strip['products'])); ?>
  <?php if (!empty($quicklinkStrips)): ?>
    <div class="container mt-4">
      <div class="category-quicklinks">
        <?php foreach ($quicklinkStrips as $strip): ?>
          <a href="shop?c=category&p=<?php echo urlencode($strip['category']); ?>" class="category-quicklink">
            <span class="category-quicklink-icon"><i class="fas <?php echo htmlspecialchars($strip['icon']); ?>"></i></span>
            <span class="category-quicklink-label"><?php echo htmlspecialchars($strip['title']); ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>

  <!-- Category strips -->
  <?php foreach ($homeCategoryStrips as $strip): ?>
    <?php
      $stripTitle = $strip['title'];
      $stripCategory = $strip['category'];
      $stripProducts = $strip['products'];
      include('includes/product_strip.php');
    ?>
  <?php endforeach; ?>

  <!-- Shop by Brand -->
  <div class="container mt-5">
    <h4 class="fs-4 mb-4">Shop by Brand</h4>
    <?php if (empty($brandManufacturers)): ?>
      <h4 class="text-muted text-center mt-4 mb-4">No brands available right now.</h4>
    <?php else: ?>
      <div class="row g-3">
        <?php foreach ($brandManufacturers as $brand): ?>
          <div class="col-6 col-md-3 col-lg-2">
            <a href="shop?c=manufacturer&p=<?php echo urlencode($brand['manufacturer']); ?>" class="brand-card">
              <?php echo htmlspecialchars($brand['manufacturer']); ?>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- Trust badges -->
  <div class="container mt-5 mb-5">
    <div class="row g-3">
      <div class="col-6 col-md-3">
        <div class="trust-badge">
          <i class="fas fa-shield-alt trust-badge-icon"></i>
          <div>
            <div class="trust-badge-title">Original Products</div>
            <div class="trust-badge-desc">100% Genuine with Warranty</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="trust-badge">
          <i class="fas fa-truck trust-badge-icon"></i>
          <div>
            <div class="trust-badge-title">Fast Delivery</div>
            <div class="trust-badge-desc">Quick and reliable shipping</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="trust-badge">
          <i class="fas fa-lock trust-badge-icon"></i>
          <div>
            <div class="trust-badge-title">Secure Payment</div>
            <div class="trust-badge-desc">Paystack secured checkout</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="trust-badge">
          <i class="fas fa-award trust-badge-icon"></i>
          <div>
            <div class="trust-badge-title">Warranty</div>
            <div class="trust-badge-desc">Official local tech support</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php require_once ('includes/footer.php'); ?>
</body>
<?php require_once ('includes/cdn_footer.php'); ?>

<script src="assets/js/cart.js"></script>

</html>

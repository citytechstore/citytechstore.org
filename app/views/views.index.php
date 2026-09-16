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

  <!-- Hero banner -->
  <div class="container mt-4">
    <div class="hero-banner"
      <?php if ($heroProduct): ?>
      style="background-image: url('<?php echo htmlspecialchars($heroProduct['product_picture_url']); ?>');"
      <?php endif; ?>
    >
      <div class="hero-banner-content">
        <span class="hero-badge">Now In Stock</span>
        <h1 class="fs-1">
          <?php if ($heroProduct): ?>
            <?php echo htmlspecialchars($heroProduct['name']); ?>
          <?php else: ?>
            Welcome to <?php echo htmlspecialchars(APP_NAME); ?>
          <?php endif; ?>
        </h1>
        <p class="fs-5">
          Experience the peak of premium technology. Available today at <?php echo htmlspecialchars(APP_NAME); ?>,
          your trusted destination for quality tech products and accessories.
        </p>
        <a href="shop" class="btn hero-shop-btn btn-lg">Shop Now</a>
      </div>
    </div>
  </div>

  <!-- Flash Deals -->
  <div class="container mt-5">
    <div class="d-flex justify-content-between flex-wrap align-items-center mb-4 gap-3">
      <h4 class="fs-4 mb-0">Flash Deals</h4>
      <div class="flash-deals-countdown" id="flash-deals-countdown">
        <span class="flash-deals-countdown-box" id="countdown-hours">00</span>
        <span class="flash-deals-countdown-sep">:</span>
        <span class="flash-deals-countdown-box" id="countdown-minutes">00</span>
        <span class="flash-deals-countdown-sep">:</span>
        <span class="flash-deals-countdown-box" id="countdown-seconds">00</span>
      </div>
      <a href="shop" class="text-decoration-none">View All</a>
    </div>

    <?php if (empty($flashDealProducts)): ?>
      <h4 class="text-muted text-center mt-4 mb-4">No deals available right now.</h4>
    <?php else: ?>
      <div class="row g-3">
        <?php foreach ($flashDealProducts as $product): ?>
          <div class="col-6 col-md-4 col-lg-3">
            <?php include('includes/product_card.php'); ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

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

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Cosmetic-only countdown (not tied to real deal expiry logic yet).
    var countdownTarget = new Date(Date.now() + (2 * 60 * 60 * 1000) + (48 * 60 * 1000) + (15 * 1000));

    var hoursEl = document.getElementById('countdown-hours');
    var minutesEl = document.getElementById('countdown-minutes');
    var secondsEl = document.getElementById('countdown-seconds');

    function pad(n) {
      return String(n).padStart(2, '0');
    }

    function tick() {
      var diff = Math.max(0, countdownTarget - Date.now());
      var hours = Math.floor(diff / (1000 * 60 * 60));
      var minutes = Math.floor((diff / (1000 * 60)) % 60);
      var seconds = Math.floor((diff / 1000) % 60);

      hoursEl.textContent = pad(hours);
      minutesEl.textContent = pad(minutes);
      secondsEl.textContent = pad(seconds);

      if (diff > 0) {
        setTimeout(tick, 1000);
      }
    }

    tick();
  });
</script>
<script src="assets/js/cart.js"></script>

</html>

<?php require_once('config/config.php'); ?>
<?php require_once('app/models/lib.php'); ?>
<?php require_once('app/models/Database.php'); ?>
<?php require_once('app/models/Product.php'); ?>
<?php
// header.php is included from many controllers, not all of which load the
// Product model themselves, so it fetches its own nav data here.
$navCategories = $ProductModel->getDistinctCategories();
?>
<?php
// Same base-path stripping as Router::stripBasePath()/cdn_header.php, so the
// active category tab is detected correctly whether the app runs at the
// domain root (live) or in a subfolder (local XAMPP).
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($basePath !== '' && strpos($currentPath, $basePath) === 0) {
    $currentPath = substr($currentPath, strlen($basePath));
}
$currentPath = ($currentPath === '' || $currentPath === false) ? '/' : $currentPath;
if ($currentPath !== '/') {
    $currentPath = rtrim($currentPath, '/');
}
?>

<div class="text-center top-utility-bar py-2 fw-bold small">
  Please, beware of scammers and make sure the url of this site is:
  <i class="fas fa-lock text-warning fs-6"></i>
  <a href="<?php echo getBaseUrl(); ?>" target="_blank" rel="noopener noreferrer"><?php echo getBaseUrl(); ?></a> and our Whatsapp number is
  <a href="<?php echo strtoupper(WA_LINK); ?>" target="_blank" rel="noopener noreferrer"><?php echo strtoupper(WA_NUMBER); ?></a>
</div>

<!-- Compact header (< xl): single row + always-visible full-width search +
     horizontally-scrollable category tabs, per the Konga reference — Konga
     keeps this compact layout through tablet widths (including iPad
     landscape, ~1024px) and only switches to a fully expanded desktop header
     on genuinely wide screens. Utility links that don't fit (Wishlist,
     Login, Help) live in the offcanvas panel; Account and Cart stay pinned
     since they're the two most-used actions. -->
<div class="d-xl-none site-header-mobile sticky-top shadow-sm">
  <div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between py-2">
      <button class="site-mobile-menu-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-label="Open menu">
        <i class="fas fa-bars"></i>
      </button>
      <a class="site-brand-mobile" href="">
        <img src="assets/img/icons/android-chrome-192x192.png" alt="City Tech Logo" width="34">
      </a>
      <div class="site-mobile-icons">
        <a href="customer-login" class="site-mobile-icon" title="Account">
          <i class="fas fa-user"></i>
        </a>
        <a href="cart" class="site-mobile-icon" title="Cart">
          <i class="fas fa-shopping-cart"></i>
        </a>
      </div>
    </div>

    <div class="site-header-search pb-2">
      <form class="d-flex" role="search" onsubmit="return false;">
        <input type="search" class="form-control" placeholder="Search for products..." aria-label="Search" disabled>
        <button class="btn site-search-btn" type="submit" disabled aria-label="Search">
          <i class="fas fa-search"></i>
        </button>
      </form>
    </div>
  </div>

  <nav class="site-nav-mobile-scroll" aria-label="Category Navbar Mobile">
    <a class="site-nav-mobile-link site-nav-mobile-all" href="shop">
      <i class="fas fa-bars"></i> All Categories
    </a>
    <?php foreach ($navCategories as $navCategory): ?>
      <a class="site-nav-mobile-link" href="shop?c=category&p=<?php echo urlencode($navCategory['category']); ?>">
        <?php echo htmlspecialchars($navCategory['category']); ?>
      </a>
    <?php endforeach; ?>
    <a class="site-nav-mobile-link" href="storesection">Store Sections</a>
    <a class="site-nav-mobile-link" href="brands">Brands</a>
  </nav>
</div>

<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
  <div class="mobile-menu-header">
    <a href="" class="site-brand-mobile">
      <img src="assets/img/icons/android-chrome-192x192.png" alt="City Tech Logo" width="30">
      <span class="fw-bold ms-2" id="mobileMenuLabel" style="color: var(--color-primary);"><?php echo strtoupper(APP_NAME); ?></span>
    </a>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body p-0">
    <a href="customer-login" class="mobile-menu-link mobile-menu-link-bold">
      <i class="fas fa-user"></i>
      <span>My Account</span>
      <i class="fas fa-chevron-right mobile-menu-chevron"></i>
    </a>
    <a href="login" class="mobile-menu-link">
      <i class="fas fa-sign-in-alt"></i>
      <span>Login / Signup</span>
    </a>
    <a href="#" class="mobile-menu-link">
      <i class="fas fa-heart"></i>
      <span>Wishlist</span>
    </a>
    <a href="tutorials" class="mobile-menu-link">
      <i class="fas fa-circle-question"></i>
      <span>Help / Tutorials</span>
    </a>
    <a href="visit-our-store" class="mobile-menu-link">
      <i class="fas fa-location-dot"></i>
      <span>Visit Our Store</span>
    </a>

    <div class="mobile-menu-section-label">Our Categories</div>
    <?php foreach ($navCategories as $navCategory): ?>
      <a href="shop?c=category&p=<?php echo urlencode($navCategory['category']); ?>" class="mobile-menu-link">
        <i class="fas fa-tag"></i>
        <span><?php echo htmlspecialchars($navCategory['category']); ?></span>
      </a>
    <?php endforeach; ?>
    <a href="storesection" class="mobile-menu-link">
      <i class="fas fa-store"></i>
      <span>Store Sections</span>
    </a>
    <a href="brands" class="mobile-menu-link">
      <i class="fas fa-tags"></i>
      <span>Brands</span>
    </a>
  </div>
</div>

<!-- Wide desktop header (>= xl, ~1200px+): the fully expanded layout. Always
     shown past its own former lg (992px) collapse point, so the old
     toggler/collapse machinery is dead weight here and has been dropped in
     favor of plain, permanently-expanded markup. -->
<div class="d-none d-xl-block">
  <header class="site-header py-3 sticky-top shadow-sm">
    <div class="container">
      <div class="d-flex align-items-center justify-content-between gap-4">
        <a class="navbar-brand site-brand fw-bold fs-3 mb-0 flex-shrink-0" href="">
          <img src="assets/img/icons/android-chrome-192x192.png" alt="City Tech Logo" width="50px">
          <?php echo strtoupper(APP_NAME); ?>
        </a>

        <div class="d-flex align-items-center gap-3 flex-shrink-0">
          <a href="visit-our-store" class="site-header-textlink">
            <i class="fas fa-location-dot"></i> Visit Our Store
          </a>
        </div>

        <div class="site-header-search flex-grow-1" style="max-width: 480px;">
          <form class="d-flex" role="search" onsubmit="return false;">
            <input type="search" class="form-control" placeholder="Search for products..." aria-label="Search" disabled>
            <button class="btn site-search-btn" type="submit" disabled aria-label="Search">
              <i class="fas fa-search"></i>
            </button>
          </form>
        </div>

        <div class="site-header-icons d-flex align-items-center gap-3 flex-shrink-0">
          <a href="customer-login" class="site-header-icon" title="Account">
            <i class="fas fa-user"></i>
            <span>Account</span>
          </a>
          <a href="#" class="site-header-icon" title="Wishlist (coming soon)">
            <i class="fas fa-heart"></i>
            <span>Wishlist</span>
          </a>
          <a href="login" class="site-header-icon" title="Login">
            <i class="fas fa-sign-in-alt"></i>
            <span>Login</span>
          </a>
          <div class="dropdown">
            <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" class="site-header-icon dropdown-toggle" title="Help">
              <i class="fas fa-circle-question"></i>
              <span>Help</span>
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="tutorials">Tutorials</a></li>
              <!-- <li> <a class="dropdown-item" href="/how-to">How to</a></li> -->
              <!-- <li> <a class="dropdown-item" href="/faqs">FAQs</a></li> -->
            </ul>
          </div>
          <a href="cart" class="site-header-icon" title="Cart">
            <i class="fas fa-shopping-cart"></i>
            <span>Cart</span>
          </a>
        </div>
      </div>
    </div>
  </header>

  <nav class="site-nav px-3" aria-label="Category Navbar">
    <div class="container">
      <ul class="navbar-nav mb-0 flex-row align-items-center gap-3">
        <li class="nav-item dropdown">
          <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
            class="nav-link site-nav-link site-nav-all-categories dropdown-toggle">
            <i class="fas fa-bars"></i> All Categories
          </a>
          <ul class="dropdown-menu">
            <?php if (empty($navCategories)): ?>
              <li><span class="dropdown-item-text text-muted">No categories yet</span></li>
            <?php else: ?>
              <?php foreach ($navCategories as $navCategory): ?>
                <li>
                  <a class="dropdown-item" href="shop?c=category&p=<?php echo urlencode($navCategory['category']); ?>">
                    <?php echo htmlspecialchars($navCategory['category']); ?>
                  </a>
                </li>
              <?php endforeach; ?>
            <?php endif; ?>
          </ul>
        </li>
        <?php foreach ($navCategories as $navCategory): ?>
          <li class="nav-item">
            <a class="nav-link site-nav-link" href="shop?c=category&p=<?php echo urlencode($navCategory['category']); ?>">
              <?php echo htmlspecialchars($navCategory['category']); ?>
            </a>
          </li>
        <?php endforeach; ?>
        <li class="nav-item">
          <a class="nav-link site-nav-link" <?php echo ($currentPath === '/storesection') ? 'aria-current="page"' : ''; ?> href="storesection">Store Sections</a>
        </li>
        <li class="nav-item">
          <a class="nav-link site-nav-link" <?php echo ($currentPath === '/brands') ? 'aria-current="page"' : ''; ?> href="brands">Brands</a>
        </li>
      </ul>
    </div>
  </nav>
</div>
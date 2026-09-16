<?php require_once('config/config.php'); ?>
<?php require_once('app/models/lib.php'); ?>

<div class="text-center top-utility-bar py-2 fw-bold small">
  Please, beware of scammers and make sure the url of this site is:
  <i class="fas fa-lock text-warning fs-6"></i>
  <a href="<?php echo getBaseUrl(); ?>" target="_blank" rel="noopener noreferrer"><?php echo getBaseUrl(); ?></a> and our Whatsapp number is
  <a href="<?php echo strtoupper(WA_LINK); ?>" target="_blank" rel="noopener noreferrer"><?php echo strtoupper(WA_NUMBER); ?></a>
</div>

<header class="site-header py-3 sticky-top shadow-sm">
  <div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
      <a class="navbar-brand site-brand fw-bold fs-3 mb-0" href="">
        <img src="assets/img/icons/android-chrome-192x192.png" alt="City Tech Logo" width="50px">
        <?php echo strtoupper(APP_NAME); ?>
      </a>

      <div class="site-header-search flex-grow-1 order-3 order-md-2 mx-md-4" style="max-width: 480px;">
        <form class="d-flex" role="search" onsubmit="return false;">
          <input type="search" class="form-control" placeholder="Search for products..." aria-label="Search" disabled>
          <button class="btn site-search-btn" type="submit" disabled aria-label="Search">
            <i class="fas fa-search"></i>
          </button>
        </form>
      </div>

      <div class="site-header-icons d-flex align-items-center gap-4 order-2 order-md-3">
        <a href="customer-login" class="site-header-icon" title="Account">
          <i class="fas fa-user"></i>
          <span class="d-none d-lg-inline">Account</span>
        </a>
        <a href="#" class="site-header-icon" title="Wishlist (coming soon)">
          <i class="fas fa-heart"></i>
          <span class="d-none d-lg-inline">Wishlist</span>
        </a>
        <a href="cart" class="site-header-icon" title="Cart">
          <i class="fas fa-shopping-cart"></i>
          <span class="d-none d-lg-inline">Cart</span>
        </a>
      </div>
    </div>
  </div>
</header>

<nav class="navbar navbar-expand-lg site-nav px-3" aria-label="Main Navbar">
  <div class="container-fluid">
    <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse" id="navbarsExample09" style="">
      <ul class="navbar-nav mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link site-nav-link" aria-current="page" href="">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link site-nav-link" aria-current="page" href="list?view=products">Product</a>
        </li>
        <li class="nav-item">
          <a class="nav-link site-nav-link" aria-current="page" href="storesection">Store Sections</a>
        </li>
        <li class="nav-item">
          <a class="nav-link site-nav-link" aria-current="page" href="login">Login</a>
        </li>

        <li class="nav-item dropdown">
          <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" class="nav-link site-nav-link dropdown-toggle">
            Help
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="tutorials">Tutorials</a></li>
            <!-- <li> <a class="dropdown-item" href="/how-to">How to</a></li> -->
            <!-- <li> <a class="dropdown-item" href="/faqs">FAQs</a></li> -->
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
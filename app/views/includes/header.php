<?php require_once('config/config.php'); ?>
<?php require_once('app/models/lib.php'); ?>

<div class="text-center bg-dark text-white py-2 fw-bold leads">
  Please, beware of scammers and make sure the url of this site is:
  <i class="fas fa-lock text-warning fs-6"></i>
  <a href="<?php echo getBaseUrl(); ?>" target="_blank" rel="noopener noreferrer" class="link link-primary"><?php echo getBaseUrl(); ?></a> and our Whatsapp number is
  <a href="<?php echo strtoupper(WA_LINK); ?>" target="_blank" rel="noopener noreferrer" class="link link-primary"><?php echo strtoupper(WA_NUMBER); ?></a>
</div>
<nav class="navbar navbar-expand-lg bg-body-tertiary p-3 sticky-top shadow-sm" aria-label="Main Navbar">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold fs-3" href="" style="color:red;">
      <img src="assets/img/icons/android-chrome-192x192.png" alt="City Tech Logo" width="50px">
      <?php echo strtoupper(APP_NAME); ?>
    </a>
    <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse" id="navbarsExample09" style="">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="list?view=products">Product</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="storesection">Store Sections</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="login">Login</a>
        </li>
       
        <li class="nav-item dropdown">
          <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" class="nav-link dropdown-toggle">
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
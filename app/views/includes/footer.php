<footer class="site-footer pt-5 pb-3 mt-5">
  <div class="container">
    <div class="row gy-4">
      <div class="col-6 col-md-3">
        <a class="navbar-brand d-inline-flex align-items-center mb-3" href="">
          <img src="assets/img/icons/android-chrome-192x192.png" alt="City Tech Logo" width="40px" class="me-2">
          <span class="fw-bold text-white"><?php echo strtoupper(APP_NAME); ?></span>
        </a>
        <p class="small">Your trusted store for quality tech products, parts, and accessories.</p>
        <div class="site-footer-social mt-3">
          <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
          <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <h6>Shop</h6>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="">Home</a></li>
          <li class="mb-2"><a href="list?view=products">Products</a></li>
          <li class="mb-2"><a href="storesection">Store Sections</a></li>
        </ul>
      </div>

      <div class="col-6 col-md-3">
        <h6>Customer Service</h6>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="contact">Contact Us</a></li>
          <li class="mb-2"><a href="tutorials">Tutorials</a></li>
          <li class="mb-2"><a href="howto">How To</a></li>
        </ul>
      </div>

      <div class="col-6 col-md-3">
        <h6>Locations</h6>
        <ul class="list-unstyled small">
          <li class="mb-2"><?php echo htmlspecialchars(STORE_ADDRESS); ?></li>
          <li class="mb-2"><?php echo htmlspecialchars(STORE_OPENING_HOURS); ?></li>
          <li class="mb-2"><a href="tel:<?php echo htmlspecialchars(CONTACT_PHONE); ?>"><?php echo htmlspecialchars(CONTACT_PHONE); ?></a></li>
          <li class="mb-2"><a href="mailto:<?php echo htmlspecialchars(CONTACT_EMAIL); ?>"><?php echo htmlspecialchars(CONTACT_EMAIL); ?></a></li>
          <li class="mb-2"><a href="<?php echo htmlspecialchars(WA_LINK); ?>" target="_blank" rel="noopener noreferrer">WhatsApp: <?php echo htmlspecialchars(WA_NUMBER); ?></a></li>
        </ul>
      </div>
    </div>

    <div class="site-footer-copyright text-center small pt-3 mt-4">
      © <?php echo COPYRIGHT_YEAR; ?> <?php echo APP_NAME; ?>. All rights reserved.
    </div>
  </div>
</footer>
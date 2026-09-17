<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once('includes/cdn_header.php'); ?>
    <title>Visit Our Store - City Tech Store</title>
</head>

<body>
    <?php require_once('includes/header.php'); ?>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h4 class="fs-4 mb-4">Visit Our Store</h4>
                <p>Come see our products in person — here's where to find us.</p>

                <div class="trust-badge mb-3">
                    <i class="fas fa-location-dot trust-badge-icon"></i>
                    <div>
                        <div class="trust-badge-title">Address</div>
                        <div class="trust-badge-desc"><?php echo htmlspecialchars(STORE_ADDRESS); ?></div>
                    </div>
                </div>

                <div class="trust-badge mb-3">
                    <i class="fas fa-clock trust-badge-icon"></i>
                    <div>
                        <div class="trust-badge-title">Opening Hours</div>
                        <div class="trust-badge-desc"><?php echo htmlspecialchars(STORE_OPENING_HOURS); ?></div>
                    </div>
                </div>

                <div class="trust-badge mb-4">
                    <i class="fas fa-phone trust-badge-icon"></i>
                    <div>
                        <div class="trust-badge-title">Call or WhatsApp</div>
                        <div class="trust-badge-desc">
                            <a href="tel:<?php echo htmlspecialchars(CONTACT_PHONE); ?>"><?php echo htmlspecialchars(CONTACT_PHONE); ?></a>
                            &middot;
                            <a href="<?php echo htmlspecialchars(WA_LINK); ?>" target="_blank" rel="noopener noreferrer">WhatsApp: <?php echo htmlspecialchars(WA_NUMBER); ?></a>
                        </div>
                    </div>
                </div>

                <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode(STORE_ADDRESS); ?>"
                    target="_blank" rel="noopener noreferrer" class="btn hero-shop-btn btn-lg">
                    <i class="fas fa-diamond-turn-right"></i> Get Directions
                </a>
            </div>
        </div>
    </div>

    <?php require_once('includes/footer.php'); ?>
    <?php require_once('includes/cdn_footer.php'); ?>

</body>

</html>

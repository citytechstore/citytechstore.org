<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once('includes/cdn_header.php'); ?>
    <title>All Brands - City Tech Store</title>
</head>

<body>
    <?php require_once('includes/header.php'); ?>

    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2">
            <h4 class="fs-4 mb-5">All Brands</h4>
        </div>

        <?php if (empty($brandManufacturers)): ?>
            <h4 class="text-muted text-center mt-5 mb-5">No brands available yet.</h4>
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

    <?php require_once('includes/footer.php'); ?>
    <?php require_once('includes/cdn_footer.php'); ?>

    <script src="assets/js/cart.js"></script>

</body>

</html>

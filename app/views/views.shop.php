<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once('includes/cdn_header.php'); ?>
    <title>Shop - City Tech Store</title>
</head>

<body>
    <?php require_once('includes/header.php'); ?>

    <?php if (!empty($categoryBanner)): ?>
        <div class="container mt-5">
            <div class="hero-banner" style="background-image: url('<?php echo htmlspecialchars($categoryBanner['image_path']); ?>');">
                <div class="hero-banner-content">
                    <h1 class="fs-1"><?php echo htmlspecialchars($categoryBanner['headline']); ?></h1>
                    <?php if (!empty($categoryBanner['subtext'])): ?>
                        <p class="fs-5"><?php echo htmlspecialchars($categoryBanner['subtext']); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($categoryBanner['link_url'])): ?>
                        <a href="<?php echo htmlspecialchars($categoryBanner['link_url']); ?>" class="btn hero-shop-btn btn-lg">Shop Now</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2">
            <h4 class="fs-4 mb-5">Shop Products</h4>
        </div>

        <?php if (empty($shopProducts)): ?>
            <h4 class="text-muted text-center mt-5 mb-5">No products available right now.</h4>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($shopProducts as $product): ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <?php include('includes/product_card.php'); ?>
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

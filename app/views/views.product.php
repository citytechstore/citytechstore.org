<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once('includes/cdn_header.php'); ?>
    <title><?php echo htmlspecialchars($product['name']); ?> - City Tech Store</title>
</head>

<body>
    <?php require_once('includes/header.php'); ?>

    <div class="container mt-5 mb-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Home</a></li>
                <li class="breadcrumb-item">
                    <a href="shop?c=category&p=<?php echo urlencode($product['category']); ?>">
                        <?php echo htmlspecialchars($product['category']); ?>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <?php echo htmlspecialchars($product['name']); ?>
                </li>
            </ol>
        </nav>

        <div class="row g-4 mt-2">
            <div class="col-md-6">
                <div class="product-detail-image-wrap">
                    <img id="product-main-image" src="<?php echo htmlspecialchars($productImages[0]['image_path']); ?>"
                        alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>

                <?php if (count($productImages) > 1): ?>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <?php foreach ($productImages as $index => $img): ?>
                            <img src="<?php echo htmlspecialchars($img['image_path']); ?>"
                                alt="<?php echo htmlspecialchars($product['name']); ?> thumbnail <?php echo $index + 1; ?>"
                                class="product-thumbnail <?php echo $index === 0 ? 'product-thumbnail-active' : ''; ?>"
                                data-full-image="<?php echo htmlspecialchars($img['image_path']); ?>">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <h1 class="fs-3 product-detail-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                <p class="text-muted mb-2">
                    <?php echo htmlspecialchars($product['category']); ?>
                    <?php if (!empty($product['manufacturer'])): ?>
                        &middot; <?php echo htmlspecialchars($product['manufacturer']); ?>
                    <?php endif; ?>
                </p>
                <p class="product-detail-price">&#8358;<?php echo number_format($product['unit_price'], 2); ?></p>

                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="text-muted small">Share with others:</span>
                    <a href="#" id="share-facebook" target="_blank" rel="noopener noreferrer"
                        class="product-share-icon" aria-label="Share on Facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" id="share-twitter" target="_blank" rel="noopener noreferrer"
                        class="product-share-icon" aria-label="Share on X (Twitter)">
                        <i class="fab fa-x-twitter"></i>
                    </a>
                </div>

                <div class="d-flex align-items-center gap-3 mt-4 mb-3">
                    <label for="product-qty" class="mb-0 fw-semibold">Quantity</label>
                    <input type="number" id="product-qty" class="form-control cart-qty-input" style="width: 90px;"
                        min="1" value="1">
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="button" id="product-add-to-cart" class="btn hero-shop-btn"
                        data-product-id="<?php echo (int) $product['id']; ?>">
                        Add to Cart
                    </button>
                    <button type="button" id="product-buy-now" class="btn product-detail-buy-btn"
                        data-product-id="<?php echo (int) $product['id']; ?>">
                        Buy Now
                    </button>
                </div>

                <?php if (!empty($product['description'])): ?>
                    <h6 class="mt-4 mb-2 fw-bold">Description</h6>
                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (!empty($relatedProducts)): ?>
        <?php
            $stripTitle = 'Related Products';
            $stripCategory = $product['category'];
            $stripProducts = $relatedProducts;
            include('includes/product_strip.php');
        ?>
    <?php endif; ?>

    <?php require_once('includes/footer.php'); ?>
    <?php require_once('includes/cdn_footer.php'); ?>

    <script src="assets/js/cart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var qtyInput = document.getElementById('product-qty');

            var mainImage = document.getElementById('product-main-image');
            document.querySelectorAll('.product-thumbnail').forEach(function (thumb) {
                thumb.addEventListener('click', function () {
                    mainImage.src = thumb.getAttribute('data-full-image');
                    document.querySelectorAll('.product-thumbnail').forEach(function (t) {
                        t.classList.remove('product-thumbnail-active');
                    });
                    thumb.classList.add('product-thumbnail-active');
                });
            });

            var pageUrl = encodeURIComponent(window.location.href);
            var productName = encodeURIComponent(<?php echo json_encode($product['name']); ?>);
            document.getElementById('share-facebook').href = 'https://www.facebook.com/sharer/sharer.php?u=' + pageUrl;
            document.getElementById('share-twitter').href = 'https://twitter.com/intent/tweet?url=' + pageUrl + '&text=' + productName;

            function addToCart(productId, quantity) {
                var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                return fetch('cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-Token': csrfToken
                    },
                    body: 'product_id=' + encodeURIComponent(productId) + '&quantity=' + encodeURIComponent(quantity)
                }).then(function (response) { return response.json(); });
            }

            function getQuantity() {
                var quantity = parseInt(qtyInput.value, 10);
                if (!quantity || quantity <= 0) {
                    qtyInput.value = 1;
                    quantity = 1;
                }
                return quantity;
            }

            var addButton = document.getElementById('product-add-to-cart');
            addButton.addEventListener('click', function () {
                var originalText = addButton.textContent;
                addButton.disabled = true;

                addToCart(addButton.getAttribute('data-product-id'), getQuantity())
                    .then(function (data) {
                        if (data.success) {
                            addButton.textContent = 'Added!';
                            setTimeout(function () {
                                addButton.textContent = originalText;
                                addButton.disabled = false;
                            }, 1500);
                        } else {
                            alert(data.message || 'Could not add item to cart.');
                            addButton.disabled = false;
                        }
                    })
                    .catch(function (error) {
                        console.error('Add to cart failed:', error);
                        alert('Something went wrong adding this item to your cart.');
                        addButton.disabled = false;
                    });
            });

            var buyButton = document.getElementById('product-buy-now');
            buyButton.addEventListener('click', function () {
                buyButton.disabled = true;

                addToCart(buyButton.getAttribute('data-product-id'), getQuantity())
                    .then(function (data) {
                        if (data.success) {
                            window.location.href = 'checkout';
                        } else {
                            alert(data.message || 'Could not add item to cart.');
                            buyButton.disabled = false;
                        }
                    })
                    .catch(function (error) {
                        console.error('Buy now failed:', error);
                        alert('Something went wrong processing your order.');
                        buyButton.disabled = false;
                    });
            });
        });
    </script>

</body>

</html>

<div class="card h-100 product-card">
    <a href="product?id=<?php echo (int) $product['id']; ?>" class="text-decoration-none">
        <div class="product-card-image-wrap">
            <img src="<?php echo htmlspecialchars($product['product_picture_url']); ?>"
                alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>
    </a>
    <div class="card-body">
        <a href="product?id=<?php echo (int) $product['id']; ?>" class="text-decoration-none">
            <h6 class="product-card-title"><?php echo htmlspecialchars($product['name']); ?></h6>
        </a>
        <div class="d-flex justify-content-between align-items-center mt-2">
            <span class="product-card-price">&#8358;<?php echo number_format($product['unit_price'], 2); ?></span>
            <button type="button" class="btn product-card-add-btn add-to-cart-btn"
                data-product-id="<?php echo (int) $product['id']; ?>">
                + Add
            </button>
        </div>
    </div>
</div>

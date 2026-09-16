<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once('includes/cdn_header.php'); ?>
    <title>Order Confirmation - City Tech Store</title>
</head>

<body>
    <?php require_once('includes/header.php'); ?>

    <div class="container mt-5 mb-5">
        <?php if ($paymentSuccessful && $order): ?>
            <div class="text-center mb-5">
                <h3 class="text-success">Payment successful, thank you!</h3>
                <p>Your order <strong><?php echo htmlspecialchars($order['order_number']); ?></strong> has been confirmed.</p>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Qty</th>
                            <th scope="col">Price</th>
                            <th scope="col">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderItems as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo htmlspecialchars($item['product_picture_url']); ?>"
                                            alt="<?php echo htmlspecialchars($item['name']); ?>" width="45"
                                            class="me-2" style="height: 45px; object-fit: cover;">
                                        <span><?php echo htmlspecialchars($item['name']); ?></span>
                                    </div>
                                </td>
                                <td><?php echo (int) $item['quantity']; ?></td>
                                <td>&#8358;<?php echo number_format($item['price_at_purchase'], 2); ?></td>
                                <td>&#8358;<?php echo number_format($item['price_at_purchase'] * $item['quantity'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-end">
                <h5>Total: &#8358;<?php echo number_format($order['total'], 2); ?></h5>
            </div>
            <div class="text-center mt-4">
                <a href="shop" class="btn btn-primary">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="text-center mb-5">
                <h3 class="text-danger">Payment was not completed</h3>
                <p><?php echo htmlspecialchars($failureMessage); ?></p>
                <?php if ($order): ?>
                    <p>Order reference: <strong><?php echo htmlspecialchars($order['order_number']); ?></strong></p>
                <?php endif; ?>
                <a href="cart" class="btn btn-primary mt-3">Back to Cart</a>
            </div>
        <?php endif; ?>
    </div>

    <?php require_once('includes/footer.php'); ?>
    <?php require_once('includes/cdn_footer.php'); ?>

</body>

</html>

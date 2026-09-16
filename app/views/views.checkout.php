<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once('includes/cdn_header.php'); ?>
    <title>Checkout - City Tech Store</title>
</head>

<body>
    <?php require_once('includes/header.php'); ?>

    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2">
            <h4 class="fs-4 mb-5">Checkout</h4>
        </div>

        <div class="row g-4">
            <div class="col-md-7">
                <h5 class="mb-3">Delivery Address</h5>
                <?php if (isset($_GET['message']) && $_GET['message'] !== ''): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['message']); ?></div>
                <?php endif; ?>
                <form id="checkout-form" action="checkout/process" method="post">
                    <div class="form-group mb-3">
                        <label for="full_address">Full Address</label>
                        <textarea name="full_address" id="full_address" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="city">City</label>
                        <input type="text" name="city" id="city" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="state">State</label>
                        <input type="text" name="state" id="state" class="form-control" required>
                    </div>
                    <div class="form-group mb-4">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" name="phone_number" id="phone_number" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100" id="continue-to-payment-btn">
                        Continue to Payment
                    </button>
                </form>
            </div>

            <div class="col-md-5">
                <h5 class="mb-3">Order Summary</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): ?>
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
                                    <td>&#8358;<?php echo number_format($item['line_subtotal'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-end">
                    <h5>Total: &#8358;<?php echo number_format($cartTotal, 2); ?></h5>
                </div>
            </div>
        </div>
    </div>

    <?php require_once('includes/footer.php'); ?>
    <?php require_once('includes/cdn_footer.php'); ?>

</body>

</html>

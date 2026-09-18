<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once('includes/cdn_header.php'); ?>
    <title>My Orders - City Tech Store</title>
</head>

<body>
    <?php require_once('includes/header.php'); ?>

    <div class="container mt-5 mb-5">
        <?php
            $statusBadgeClass = [
                'pending' => 'bg-secondary',
                'confirmed' => 'bg-info text-dark',
                'shipped' => 'bg-primary',
                'delivered' => 'bg-success',
                'cancelled' => 'bg-danger',
            ];
        ?>

        <?php if (isset($_GET['id'])): ?>
            <?php if ($order): ?>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Order <?php echo htmlspecialchars($order['order_number']); ?></h3>
                    <a href="my-orders" class="btn btn-outline-secondary btn-sm">Back to My Orders</a>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <h6 class="text-muted">Order Status</h6>
                        <span class="badge <?php echo $statusBadgeClass[$order['status']] ?? 'bg-secondary'; ?>">
                            <?php echo htmlspecialchars(ucfirst($order['status'])); ?>
                        </span>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h6 class="text-muted">Payment Status</h6>
                        <span class="badge <?php echo $order['payment_status'] === 'paid' ? 'bg-success' : 'bg-secondary'; ?>">
                            <?php echo htmlspecialchars(ucfirst($order['payment_status'])); ?>
                        </span>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h6 class="text-muted">Order Date</h6>
                        <span><?php echo htmlspecialchars(date('M j, Y g:i A', strtotime($order['created_at']))); ?></span>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="text-muted">Delivery Address</h6>
                    <p class="mb-0">
                        <?php echo htmlspecialchars($order['address_label']); ?> —
                        <?php echo htmlspecialchars($order['address_full_address']); ?>,
                        <?php echo htmlspecialchars($order['address_city']); ?>,
                        <?php echo htmlspecialchars($order['address_state']); ?>
                    </p>
                    <p class="mb-0"><?php echo htmlspecialchars($order['address_phone_number']); ?></p>
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
            <?php else: ?>
                <div class="text-center mb-5">
                    <h3 class="text-danger">Order not found</h3>
                    <p>We couldn't find that order.</p>
                    <a href="my-orders" class="btn btn-primary mt-3">Back to My Orders</a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <h3 class="mb-4">My Orders</h3>

            <?php if (empty($orders)): ?>
                <div class="text-center mb-5">
                    <p class="text-muted">You haven't placed any orders yet.</p>
                    <a href="shop" class="btn btn-primary mt-3">Start Shopping</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th scope="col">Order Number</th>
                                <th scope="col">Date</th>
                                <th scope="col">Total</th>
                                <th scope="col">Status</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $customerOrder): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($customerOrder['order_number']); ?></td>
                                    <td><?php echo htmlspecialchars(date('M j, Y', strtotime($customerOrder['created_at']))); ?></td>
                                    <td>&#8358;<?php echo number_format($customerOrder['total'], 2); ?></td>
                                    <td>
                                        <span class="badge <?php echo $statusBadgeClass[$customerOrder['status']] ?? 'bg-secondary'; ?>">
                                            <?php echo htmlspecialchars(ucfirst($customerOrder['status'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="my-orders?id=<?php echo (int) $customerOrder['id']; ?>" class="btn btn-outline-primary btn-sm">View Details</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php require_once('includes/footer.php'); ?>
    <?php require_once('includes/cdn_footer.php'); ?>

</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once('includes/cdn_header.php'); ?>
    <title>Your Cart - City Tech Store</title>
</head>

<body>
    <?php require_once('includes/header.php'); ?>

    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2">
            <h4 class="fs-4 mb-5">Your Cart</h4>
        </div>

        <?php if (empty($cartItems)): ?>
            <h4 class="text-muted text-center mt-5 mb-5">Your cart is empty.</h4>
            <div class="text-center">
                <a href="shop" class="btn btn-primary">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle" id="cart-table">
                    <thead>
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Unit Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Subtotal</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                            <tr data-cart-row-id="<?php echo (int) $item['id']; ?>">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo htmlspecialchars($item['product_picture_url']); ?>"
                                            alt="<?php echo htmlspecialchars($item['name']); ?>" width="60"
                                            class="me-3" style="height: 60px; object-fit: cover;">
                                        <span><?php echo htmlspecialchars($item['name']); ?></span>
                                    </div>
                                </td>
                                <td class="item-unit-price" data-unit-price="<?php echo (float) $item['unit_price']; ?>">
                                    &#8358;<?php echo number_format($item['unit_price'], 2); ?>
                                </td>
                                <td>
                                    <input type="number" class="form-control cart-qty-input" style="width: 90px;"
                                        min="1" value="<?php echo (int) $item['quantity']; ?>"
                                        data-cart-item-id="<?php echo (int) $item['id']; ?>">
                                </td>
                                <td class="item-subtotal">
                                    &#8358;<?php echo number_format($item['line_subtotal'], 2); ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-outline-danger btn-sm cart-remove-btn"
                                        data-cart-item-id="<?php echo (int) $item['id']; ?>">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end">
                <div class="text-end">
                    <h5>Total: &#8358;<span id="cart-total"><?php echo number_format($cartTotal, 2); ?></span></h5>
                    <a href="checkout" class="btn btn-primary btn-lg mt-3">Proceed to Checkout</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php require_once('includes/footer.php'); ?>
    <?php require_once('includes/cdn_footer.php'); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function formatNaira(value) {
                return value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            function recalculateTotal() {
                var total = 0;
                document.querySelectorAll('#cart-table tbody tr').forEach(function (row) {
                    var subtotalText = row.querySelector('.item-subtotal').textContent;
                    total += parseFloat(subtotalText.replace(/[^0-9.]/g, '')) || 0;
                });
                var totalEl = document.getElementById('cart-total');
                if (totalEl) {
                    totalEl.textContent = formatNaira(total);
                }
            }

            document.querySelectorAll('.cart-qty-input').forEach(function (input) {
                input.addEventListener('change', function () {
                    var cartItemId = input.getAttribute('data-cart-item-id');
                    var quantity = parseInt(input.value, 10);

                    if (!quantity || quantity <= 0) {
                        alert('Quantity must be a positive number.');
                        input.value = 1;
                        quantity = 1;
                    }

                    input.disabled = true;

                    var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                    fetch('cart/update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-Token': csrfToken
                        },
                        body: 'cart_item_id=' + encodeURIComponent(cartItemId) + '&quantity=' + encodeURIComponent(quantity)
                    })
                        .then(function (response) { return response.json(); })
                        .then(function (data) {
                            input.disabled = false;

                            if (data.success) {
                                var row = input.closest('tr');
                                var unitPrice = parseFloat(row.querySelector('.item-unit-price').getAttribute('data-unit-price')) || 0;
                                row.querySelector('.item-subtotal').textContent = '₦' + formatNaira(unitPrice * quantity);
                                recalculateTotal();
                            } else {
                                alert(data.message || 'Could not update quantity.');
                            }
                        })
                        .catch(function (error) {
                            input.disabled = false;
                            console.error('Update cart failed:', error);
                            alert('Something went wrong updating your cart.');
                        });
                });
            });

            document.querySelectorAll('.cart-remove-btn').forEach(function (button) {
                button.addEventListener('click', function () {
                    var cartItemId = button.getAttribute('data-cart-item-id');

                    button.disabled = true;

                    var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                    fetch('cart/remove', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-Token': csrfToken
                        },
                        body: 'cart_item_id=' + encodeURIComponent(cartItemId)
                    })
                        .then(function (response) { return response.json(); })
                        .then(function (data) {
                            if (data.success) {
                                var row = button.closest('tr');
                                row.parentNode.removeChild(row);
                                recalculateTotal();

                                if (document.querySelectorAll('#cart-table tbody tr').length === 0) {
                                    window.location.reload();
                                }
                            } else {
                                button.disabled = false;
                                alert(data.message || 'Could not remove item.');
                            }
                        })
                        .catch(function (error) {
                            button.disabled = false;
                            console.error('Remove from cart failed:', error);
                            alert('Something went wrong removing this item.');
                        });
                });
            });
        });
    </script>

</body>

</html>

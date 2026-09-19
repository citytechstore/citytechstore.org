<?php
/*
 * Read-only order detail modal. Mirrors the per-order modal rendered
 * inline inside views.manage.php's Orders section (same fields: customer,
 * delivery address, items, totals, status) but factored out so the
 * Customers page can reuse it without duplicating that markup wholesale.
 * No status-update form here — this page is read-only for both admin and
 * worker. The Orders page's own modal is untouched; it could adopt this
 * partial in a future, separately-scoped pass.
 *
 * Expects, in scope: $order (orders.* + address_label/address_full_address/
 * address_city/address_state/address_phone_number, plus customer_first_name/
 * customer_last_name/customer_email/customer_phone_number when available),
 * $items (this order's line items), $statusBadgeClass.
 */
$modalOrderId = (int) $order['id'];
$modalStatusClass = $statusBadgeClass[$order['status']] ?? 'bg-secondary';
?>
<div class="modal fade" id="orderModal<?php echo $modalOrderId; ?>" tabindex="-1" aria-labelledby="orderModalLabel<?php echo $modalOrderId; ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="orderModalLabel<?php echo $modalOrderId; ?>">Order <?php echo htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8'); ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if (isset($order['customer_first_name'])): ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase small">Customer</h6>
                            <p class="mb-1"><?php echo htmlspecialchars($order['customer_first_name'] . ' ' . $order['customer_last_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="mb-1"><?php echo htmlspecialchars($order['customer_email'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="mb-0"><?php echo htmlspecialchars($order['customer_phone_number'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase small">Delivery Address</h6>
                            <p class="mb-1"><?php echo htmlspecialchars($order['address_label'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="mb-1"><?php echo htmlspecialchars($order['address_full_address'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="mb-1"><?php echo htmlspecialchars($order['address_city'] . ', ' . $order['address_state'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="mb-0"><?php echo htmlspecialchars($order['address_phone_number'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="mb-3">
                        <h6 class="text-muted text-uppercase small">Delivery Address</h6>
                        <p class="mb-1"><?php echo htmlspecialchars($order['address_label'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="mb-1"><?php echo htmlspecialchars($order['address_full_address'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="mb-1"><?php echo htmlspecialchars($order['address_city'] . ', ' . $order['address_state'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="mb-0"><?php echo htmlspecialchars($order['address_phone_number'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                <?php endif; ?>

                <h6 class="text-muted text-uppercase small">Items</h6>
                <table class="table table-sm">
                    <thead>
                        <tr><th>Product</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo (int) $item['quantity']; ?></td>
                                <td>&#8358;<?php echo number_format($item['price_at_purchase'], 2); ?></td>
                                <td>&#8358;<?php echo number_format($item['price_at_purchase'] * $item['quantity'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <p class="mb-1"><strong>Subtotal:</strong> &#8358;<?php echo number_format($order['subtotal'], 2); ?></p>
                <p class="mb-1"><strong>Delivery Fee:</strong> &#8358;<?php echo number_format($order['delivery_fee'], 2); ?></p>
                <p class="mb-3"><strong>Total:</strong> &#8358;<?php echo number_format($order['total'], 2); ?></p>

                <h6 class="text-muted text-uppercase small">Status</h6>
                <span class="badge <?php echo $modalStatusClass; ?>"><?php echo htmlspecialchars(ucfirst($order['status']), ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

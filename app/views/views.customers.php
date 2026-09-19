<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once('includes/cdn_header.php'); ?>
    <title>CTS - Customers</title>
</head>

<body>
    <div class="admin-shell">
        <?php require_once('includes/admin_sidebar.php'); ?>

        <div class="admin-main">
            <?php require_once('includes/admin_topbar.php'); ?>

            <main class="admin-content container-fluid">

                <?php if ($customerNotFound): ?>
                    <div class="alert alert-warning">Customer not found.</div>
                <?php elseif ($selectedCustomer): ?>
                    <?php
                        $customerFullName = trim($selectedCustomer['first_name'] . ' ' . $selectedCustomer['last_name']);
                        $customerInitialsText = customerInitials($selectedCustomer['first_name'], $selectedCustomer['last_name']);
                        $signInMethod = $selectedCustomer['uses_google'] ? 'Google' : 'Password';
                        $locationText = ($primaryAddress && !empty($primaryAddress['city']))
                            ? trim($primaryAddress['city'] . ', ' . $primaryAddress['state'], ', ')
                            : '—';
                        $hasPaidOrders = $customerStats['paid_orders_count'] > 0;
                    ?>

                    <h4 class="fs-4 mb-3">Customer / <?php echo htmlspecialchars($customerFullName !== '' ? $customerFullName : 'Unnamed', ENT_QUOTES, 'UTF-8'); ?></h4>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6 col-xl-3">
                            <div class="stat-card">
                                <div class="stat-card-icon stat-card-icon-navy"><i class="fas fa-naira-sign"></i></div>
                                <div>
                                    <div class="stat-card-value">&#8358;<?php echo number_format($customerStats['total_spent'], 2); ?></div>
                                    <div class="stat-card-label">Total Spent (Paid)</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="stat-card">
                                <div class="stat-card-icon"><i class="fas fa-receipt"></i></div>
                                <div>
                                    <div class="stat-card-value"><?php echo number_format($customerStats['orders_count']); ?></div>
                                    <div class="stat-card-label">Orders Placed</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="stat-card">
                                <div class="stat-card-icon stat-card-icon-navy"><i class="fas fa-circle-check"></i></div>
                                <div>
                                    <div class="stat-card-value"><?php echo number_format($customerStats['paid_orders_count']); ?></div>
                                    <div class="stat-card-label">Paid Orders</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="stat-card">
                                <div class="stat-card-icon"><i class="fas fa-chart-simple"></i></div>
                                <div>
                                    <div class="stat-card-value">&#8358;<?php echo number_format($customerStats['avg_order_value'], 2); ?></div>
                                    <div class="stat-card-label">Avg Order Value (Paid)</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4 align-items-stretch">
                        <div class="col-lg-5">
                            <div class="dashboard-panel h-100">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="admin-avatar-initials admin-avatar-initials-lg"><?php echo htmlspecialchars($customerInitialsText, ENT_QUOTES, 'UTF-8'); ?></div>
                                    <div>
                                        <div class="dashboard-panel-title mb-0"><?php echo htmlspecialchars($customerFullName !== '' ? $customerFullName : 'Unnamed', ENT_QUOTES, 'UTF-8'); ?></div>
                                        <div class="text-muted small"><?php echo htmlspecialchars($selectedCustomer['email'], ENT_QUOTES, 'UTF-8'); ?></div>
                                    </div>
                                </div>
                                <table class="table table-sm mb-0">
                                    <tbody>
                                        <tr><th class="text-muted small text-uppercase" scope="row">Phone</th><td><?php echo htmlspecialchars($selectedCustomer['phone_number'] ?? '—', ENT_QUOTES, 'UTF-8'); ?></td></tr>
                                        <tr><th class="text-muted small text-uppercase" scope="row">Location</th><td><?php echo htmlspecialchars($locationText, ENT_QUOTES, 'UTF-8'); ?></td></tr>
                                        <tr><th class="text-muted small text-uppercase" scope="row">Joined</th><td><?php echo htmlspecialchars(date('M j, Y', strtotime($selectedCustomer['created_at'])), ENT_QUOTES, 'UTF-8'); ?></td></tr>
                                        <tr><th class="text-muted small text-uppercase" scope="row">First Order</th><td><?php echo $customerStats['first_order_date'] ? htmlspecialchars(date('M j, Y', strtotime($customerStats['first_order_date'])), ENT_QUOTES, 'UTF-8') : '—'; ?></td></tr>
                                        <tr><th class="text-muted small text-uppercase" scope="row">Latest Order</th><td><?php echo $customerStats['latest_order_date'] ? htmlspecialchars(date('M j, Y', strtotime($customerStats['latest_order_date'])), ENT_QUOTES, 'UTF-8') : '—'; ?></td></tr>
                                        <tr><th class="text-muted small text-uppercase" scope="row">Sign-in Method</th><td><span class="badge bg-light text-dark border"><i class="fa<?php echo $selectedCustomer['uses_google'] ? 'b fa-google' : 's fa-key'; ?> me-1"></i><?php echo htmlspecialchars($signInMethod, ENT_QUOTES, 'UTF-8'); ?></span></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="dashboard-panel h-100">
                                <div class="dashboard-panel-title">Paid Spend (Last 12 Months)</div>
                                <?php if ($hasPaidOrders): ?>
                                    <div class="admin-chart-wrap" style="height: 220px;">
                                        <canvas id="customerSpendChart" height="80"></canvas>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted mb-0">No paid orders yet.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-panel mb-5">
                        <div class="dashboard-panel-title">Orders</div>
                        <?php if (empty($customerOrders)): ?>
                            <p class="text-muted mb-0">No orders yet.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table align-middle mb-0 admin-orders-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Order #</th>
                                            <th scope="col">Products</th>
                                            <th scope="col">Qty</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Total</th>
                                            <th scope="col">Payment</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($customerOrders as $order): ?>
                                            <?php
                                                $rowStatusClass = $statusBadgeClass[$order['status']] ?? 'bg-secondary';
                                                $rowPaymentClass = $paymentBadgeClass[$order['payment_status']] ?? 'bg-secondary';
                                                $rowItems = $customerOrderItemsByOrderId[(int) $order['id']] ?? [];
                                                $rowItemCount = count($rowItems);
                                                $rowThumbItems = array_slice($rowItems, 0, 3);
                                                $rowQty = 0;
                                                foreach ($rowItems as $rowItem) {
                                                    $rowQty += (int) $rowItem['quantity'];
                                                }
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="admin-order-thumb-stack">
                                                            <?php foreach ($rowThumbItems as $rowThumbItem): ?>
                                                                <img src="<?php echo htmlspecialchars($rowThumbItem['product_picture_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                                     alt="" class="admin-order-thumb">
                                                            <?php endforeach; ?>
                                                            <?php if ($rowItemCount > 3): ?>
                                                                <span class="admin-order-thumb-more">+<?php echo (int) ($rowItemCount - 3); ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <span class="text-muted"><?php echo (int) $rowItemCount; ?> item<?php echo $rowItemCount === 1 ? '' : 's'; ?></span>
                                                    </div>
                                                </td>
                                                <td><?php echo (int) $rowQty; ?></td>
                                                <td><?php echo htmlspecialchars(date('M j, Y', strtotime($order['created_at'])), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>&#8358;<?php echo number_format($order['total'], 2); ?></td>
                                                <td><span class="badge <?php echo $rowPaymentClass; ?>"><?php echo htmlspecialchars(ucfirst($order['payment_status']), ENT_QUOTES, 'UTF-8'); ?></span></td>
                                                <td><span class="badge <?php echo $rowStatusClass; ?>"><?php echo htmlspecialchars(ucfirst($order['status']), ENT_QUOTES, 'UTF-8'); ?></span></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#orderModal<?php echo (int) $order['id']; ?>">
                                                        View
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php foreach ($customerOrders as $order): ?>
                        <?php $items = $customerOrderItemsByOrderId[(int) $order['id']] ?? []; ?>
                        <?php require 'includes/order_detail_modal.php'; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

                <h4 class="fs-4 mb-3">All Customers</h4>

                <div class="row g-3 mb-4">
                    <div class="col-md-6 col-xl-3">
                        <div class="stat-card">
                            <div class="stat-card-icon stat-card-icon-navy"><i class="fas fa-users"></i></div>
                            <div>
                                <div class="stat-card-value"><?php echo number_format($totalCustomersCount); ?></div>
                                <div class="stat-card-label">Total Customers</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="stat-card">
                            <div class="stat-card-icon"><i class="fas fa-user-plus"></i></div>
                            <div>
                                <div class="stat-card-value"><?php echo number_format($newCustomersCount); ?></div>
                                <div class="stat-card-label">New (Last 30 Days)</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="stat-card">
                            <div class="stat-card-icon stat-card-icon-navy"><i class="fas fa-receipt"></i></div>
                            <div>
                                <div class="stat-card-value"><?php echo number_format($customersWithOrdersCount); ?></div>
                                <div class="stat-card-label">With Orders</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="stat-card">
                            <div class="stat-card-icon"><i class="fas fa-naira-sign"></i></div>
                            <div>
                                <div class="stat-card-value"><?php echo number_format($customersWithPaidOrdersCount); ?></div>
                                <div class="stat-card-label">With Paid Orders</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-panel mb-3">
                    <input type="search" id="customerSearchInput" class="form-control" style="max-width: 360px;" placeholder="Search name, email or phone&hellip;">
                </div>

                <div class="dashboard-panel">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 admin-orders-table" id="customersTable">
                            <thead>
                                <tr>
                                    <th scope="col">Customer</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Orders</th>
                                    <th scope="col">Total Spent</th>
                                    <th scope="col">Joined</th>
                                    <th scope="col">Sign-in</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($customerListRows as $customerRow): ?>
                                    <?php
                                        $listFullName = trim($customerRow['first_name'] . ' ' . $customerRow['last_name']);
                                        $listInitials = customerInitials($customerRow['first_name'], $customerRow['last_name']);
                                        $listHref = 'customers?id=' . (int) $customerRow['id'];
                                        $listSignIn = $customerRow['uses_google'] ? 'Google' : 'Password';
                                    ?>
                                    <tr class="admin-customer-row" data-href="<?php echo htmlspecialchars($listHref, ENT_QUOTES, 'UTF-8'); ?>">
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="admin-avatar-initials"><?php echo htmlspecialchars($listInitials, ENT_QUOTES, 'UTF-8'); ?></div>
                                                <a href="<?php echo htmlspecialchars($listHref, ENT_QUOTES, 'UTF-8'); ?>" class="text-decoration-none">
                                                    <?php echo htmlspecialchars($listFullName !== '' ? $listFullName : 'Unnamed', ENT_QUOTES, 'UTF-8'); ?>
                                                </a>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($customerRow['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($customerRow['phone_number'] ?? '—', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo (int) $customerRow['orders_count']; ?></td>
                                        <td>&#8358;<?php echo number_format($customerRow['total_spent'], 2); ?></td>
                                        <td><?php echo htmlspecialchars(date('M j, Y', strtotime($customerRow['created_at'])), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($listSignIn, ENT_QUOTES, 'UTF-8'); ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <p id="customersEmptyState" class="text-muted text-center py-4 mb-0 d-none">No matching customers.</p>
                    <?php if (empty($customerListRows)): ?>
                        <p class="text-muted text-center py-4 mb-0">No customers yet.</p>
                    <?php endif; ?>
                </div>

            </main>

            <footer class="admin-footer text-center text-muted small py-3">
                &copy; <?php echo COPYRIGHT_YEAR; ?> <?php echo htmlspecialchars(APP_NAME); ?>. All rights reserved.
            </footer>
        </div>
    </div>

    <?php require_once('includes/cdn_footer.php'); ?>

    <?php if ($selectedCustomer && $hasPaidOrders): ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        (function () {
            const ctx = document.getElementById('customerSpendChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($chartLabels, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
                    datasets: [{
                        label: 'Paid Spend (₦)',
                        data: <?php echo json_encode($chartData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
                        backgroundColor: '#E31E24',
                        borderRadius: 3,
                        maxBarThickness: 24,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return '₦' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        })();
        </script>
    <?php endif; ?>

    <script>
    (function () {
        var searchInput = document.getElementById('customerSearchInput');
        var emptyState = document.getElementById('customersEmptyState');
        var rows = document.querySelectorAll('#customersTable tbody tr');

        rows.forEach(function (row) {
            row.addEventListener('click', function (event) {
                if (event.target.closest('a')) {
                    return;
                }
                var href = row.getAttribute('data-href');
                if (href) {
                    window.location.href = href;
                }
            });
        });

        function applyFilter() {
            var term = searchInput.value.trim().toLowerCase();
            var visibleCount = 0;

            rows.forEach(function (row) {
                var visible = term === '' || row.textContent.toLowerCase().indexOf(term) !== -1;
                row.classList.toggle('d-none', !visible);
                if (visible) {
                    visibleCount++;
                }
            });

            emptyState.classList.toggle('d-none', visibleCount !== 0 || rows.length === 0);
        }

        if (searchInput) {
            searchInput.addEventListener('input', applyFilter);
        }
    })();
    </script>
</body>

</html>

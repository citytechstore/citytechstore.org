<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once ('includes/cdn_header.php'); ?>
    <title><?php echo htmlspecialchars($pageTitle); ?> - City Tech Store</title>
</head>

<body>
    <?php
        $statusBadgeClass = [
            'pending' => 'bg-secondary',
            'confirmed' => 'bg-info text-dark',
            'shipped' => 'bg-primary',
            'delivered' => 'bg-success',
            'cancelled' => 'bg-danger',
        ];

        /**
         * Render a trend badge for a percent-based metric (Revenue, Orders).
         * $trend is null (no badge), or ['type' => 'new'|'flat'|'up'|'down', 'percent' => int].
         */
        $renderPercentTrend = function ($trend) {
            if ($trend === null) {
                return;
            }
            if ($trend['type'] === 'new') {
                echo '<span class="admin-trend-badge admin-trend-badge-new">New</span>';
                return;
            }
            $arrow = $trend['type'] === 'up' ? '&#9650;' : ($trend['type'] === 'down' ? '&#9660;' : '');
            $sign = $trend['percent'] > 0 ? '+' : '';
            echo '<span class="admin-trend-badge admin-trend-badge-' . htmlspecialchars($trend['type']) . '">'
                . $arrow . ' ' . htmlspecialchars($sign . $trend['percent']) . '%</span>';
        };
    ?>

    <div class="admin-shell">
        <?php require_once ('includes/admin_sidebar.php'); ?>

        <div class="admin-main">
            <?php require_once ('includes/admin_topbar.php'); ?>

            <main class="admin-content container-fluid">
                <!-- Stat cards -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6 col-xl-3">
                        <div class="stat-card">
                            <div class="stat-card-icon">
                                <i class="fas fa-naira-sign"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="stat-card-value">&#8358;<?php echo number_format($totalRevenue, 2); ?></div>
                                    <?php $renderPercentTrend($revenueTrend); ?>
                                </div>
                                <div class="stat-card-label">Total Revenue</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="stat-card">
                            <div class="stat-card-icon stat-card-icon-navy">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="stat-card-value"><?php echo number_format($totalOrders); ?></div>
                                    <?php $renderPercentTrend($ordersTrend); ?>
                                </div>
                                <div class="stat-card-label">Total Orders</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="stat-card">
                            <div class="stat-card-icon">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="stat-card-value"><?php echo number_format($totalProducts); ?></div>
                                    <span class="admin-trend-badge admin-trend-badge-flat">+<?php echo (int) $newProductsCount; ?> this period</span>
                                </div>
                                <div class="stat-card-label">Total Products</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="stat-card">
                            <div class="stat-card-icon stat-card-icon-navy">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="stat-card-value"><?php echo number_format($totalCustomers); ?></div>
                                    <span class="admin-trend-badge admin-trend-badge-flat">+<?php echo (int) $newCustomersCount; ?> this period</span>
                                </div>
                                <div class="stat-card-label">Total Customers</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue over time -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <div class="dashboard-panel">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                <div class="dashboard-panel-title mb-0">Revenue</div>
                                <div class="btn-group btn-group-sm admin-chart-toggle" role="group" aria-label="Revenue chart range">
                                    <button type="button" class="btn btn-outline-secondary" data-days="7">7D</button>
                                    <button type="button" class="btn btn-outline-secondary active" data-days="30">30D</button>
                                    <button type="button" class="btn btn-outline-secondary" data-days="90">90D</button>
                                </div>
                            </div>
                            <div class="admin-chart-wrap">
                                <canvas id="revenueChart" height="90"></canvas>
                                <div id="revenueChartStatus" class="admin-chart-status d-none"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4 align-items-stretch">
                    <!-- Bestsellers -->
                    <div class="col-lg-6">
                        <div class="dashboard-panel h-100">
                            <div class="dashboard-panel-title">Bestsellers</div>
                            <?php if (empty($bestsellers)): ?>
                                <p class="text-muted mb-0">No paid orders yet.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">Product</th>
                                                <th scope="col">Units Sold</th>
                                                <th scope="col">Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($bestsellers as $product): ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <img src="<?php echo htmlspecialchars($product['product_picture_url'] ?? ''); ?>"
                                                                 alt="" class="admin-product-thumb">
                                                            <span><?php echo htmlspecialchars($product['name']); ?></span>
                                                        </div>
                                                    </td>
                                                    <td><?php echo (int) $product['units_sold']; ?></td>
                                                    <td>&#8358;<?php echo number_format($product['revenue'], 2); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Recent orders -->
                    <div class="col-lg-6">
                        <div class="dashboard-panel h-100">
                            <div class="d-flex justify-content-between align-items-center dashboard-panel-title mb-3">
                                <span>Recent Orders</span>
                                <a href="manage?type=orders" class="btn btn-sm btn-outline-secondary">View All</a>
                            </div>
                            <?php if (empty($recentOrders)): ?>
                                <p class="text-muted mb-0">No orders yet.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">Order #</th>
                                                <th scope="col">Customer</th>
                                                <th scope="col">Total</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recentOrders as $recentOrder): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($recentOrder['order_number']); ?></td>
                                                    <td><?php echo htmlspecialchars($recentOrder['customer_first_name'] . ' ' . $recentOrder['customer_last_name']); ?></td>
                                                    <td>&#8358;<?php echo number_format($recentOrder['total'], 2); ?></td>
                                                    <td>
                                                        <span class="badge <?php echo $statusBadgeClass[$recentOrder['status']] ?? 'bg-secondary'; ?>">
                                                            <?php echo htmlspecialchars(ucfirst($recentOrder['status'])); ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Needs attention -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <a href="manage?type=orders" class="admin-attention-card text-decoration-none">
                            <div class="stat-card-icon">
                                <i class="fas fa-triangle-exclamation"></i>
                            </div>
                            <div>
                                <div class="admin-attention-value"><?php echo (int) $pendingOrdersCount; ?></div>
                                <div class="stat-card-label">Pending Orders</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="manage?type=products" class="admin-attention-card text-decoration-none">
                            <div class="stat-card-icon stat-card-icon-navy">
                                <i class="fas fa-box"></i>
                            </div>
                            <div>
                                <div class="admin-attention-value"><?php echo (int) $lowStockCount; ?></div>
                                <div class="stat-card-label">Low Stock Products</div>
                                <div class="admin-attention-sub"><?php echo (int) $outOfStockCount; ?> out of stock</div>
                            </div>
                        </a>
                    </div>
                </div>
            </main>

            <footer class="admin-footer text-center text-muted small py-3">
                &copy; <?php echo COPYRIGHT_YEAR; ?> <?php echo htmlspecialchars(APP_NAME); ?>. All rights reserved.
            </footer>
        </div>
    </div>

    <?php require_once ('includes/cdn_footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function () {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            const statusEl = document.getElementById('revenueChartStatus');
            const toggleButtons = document.querySelectorAll('.admin-chart-toggle [data-days]');

            const revenueChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($chartLabels); ?>,
                    datasets: [{
                        label: 'Revenue (₦)',
                        data: <?php echo json_encode($chartData); ?>,
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

            function setStatus(message, isError) {
                if (!message) {
                    statusEl.classList.add('d-none');
                    statusEl.textContent = '';
                    return;
                }
                statusEl.classList.remove('d-none');
                statusEl.classList.toggle('admin-chart-status-error', !!isError);
                statusEl.textContent = message;
            }

            toggleButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    if (button.classList.contains('active')) {
                        return;
                    }

                    toggleButtons.forEach(function (b) { b.classList.remove('active'); });
                    button.classList.add('active');

                    const days = button.getAttribute('data-days');
                    setStatus('Loading…', false);

                    fetch('dashboard/revenue-chart?days=' + encodeURIComponent(days), {
                        headers: { 'Accept': 'application/json' }
                    })
                        .then(function (response) {
                            if (!response.ok) {
                                throw new Error('Request failed');
                            }
                            return response.json();
                        })
                        .then(function (payload) {
                            if (!payload.success) {
                                throw new Error(payload.message || 'Request failed');
                            }
                            revenueChart.data.labels = payload.labels;
                            revenueChart.data.datasets[0].data = payload.data;
                            revenueChart.update();
                            setStatus(null, false);
                        })
                        .catch(function () {
                            setStatus('Couldn’t load chart data. Please try again.', true);
                        });
                });
            });
        })();
    </script>
</body>

</html>

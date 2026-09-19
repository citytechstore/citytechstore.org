<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once ('includes/cdn_header.php'); ?>
    <title><?php echo ucfirst($_SESSION['user_session']['role']); ?> Dashboard - City Tech Store</title>
</head>

<body>
    <?php require_once ('includes/loggedin_header.php'); ?>

    <?php
        $statusBadgeClass = [
            'pending' => 'bg-secondary',
            'confirmed' => 'bg-info text-dark',
            'shipped' => 'bg-primary',
            'delivered' => 'bg-success',
            'cancelled' => 'bg-danger',
        ];
    ?>

    <div class="container-fluid py-4 px-4">
        <h1 class="h3 mb-4"><?php echo ucfirst($_SESSION['user_session']['role']); ?> Dashboard</h1>

        <!-- Stat cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card-icon">
                        <i class="fas fa-naira-sign"></i>
                    </div>
                    <div>
                        <div class="stat-card-value">&#8358;<?php echo number_format($totalRevenue, 2); ?></div>
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
                        <div class="stat-card-value"><?php echo number_format($totalOrders); ?></div>
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
                        <div class="stat-card-value"><?php echo number_format($totalProducts); ?></div>
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
                        <div class="stat-card-value"><?php echo number_format($totalCustomers); ?></div>
                        <div class="stat-card-label">Total Customers</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue over time -->
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="dashboard-panel">
                    <div class="dashboard-panel-title">Revenue — Last <?php echo (int) $revenueDays; ?> Days</div>
                    <canvas id="revenueChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <!-- Bestsellers -->
            <div class="col-lg-6">
                <div class="dashboard-panel">
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
                                            <td><?php echo htmlspecialchars($product['name']); ?></td>
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
                <div class="dashboard-panel">
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
    </div>

    <?php require_once ('includes/footer.php'); ?>
    <?php require_once ('includes/cdn_footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chartLabels); ?>,
                datasets: [{
                    label: 'Revenue (₦)',
                    data: <?php echo json_encode($chartData); ?>,
                    borderColor: '#E31E24',
                    backgroundColor: 'rgba(227, 30, 36, 0.1)',
                    borderWidth: 2,
                    pointRadius: 2,
                    tension: 0.25,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
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
    </script>
</body>

</html>

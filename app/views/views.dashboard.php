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
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item pb-3 pt-3">
                            <a class="nav-link active" aria-current="page" href="dashboard">
                                <i class="fas fa-tachometer-alt fs-5"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item pb-3">
                            <a class="nav-link" href="manage?type=products">
                                <i class="fa fa-box-open fs-5"></i> Products
                            </a>
                        </li>
                        <li class="nav-item pb-3">
                            <a class="nav-link" href="manage?type=sales">
                                <i class="fa fa-dollar-sign fs-5"></i> Sales
                            </a>
                        </li>
                        <?php if($_SESSION['user_session']['role'] == 'admin'): ?>
                        <li class="nav-item pb-3">
                            <a class="nav-link" href="manage?type=users">
                                <i class="fa fa-users fs-5"></i> Users
                            </a>
                        </li>
                        <?php endif; ?>

                        <li class="nav-item pb-3">
                            <a class="nav-link" href="dashboard.php?logout=true">
                                <i class="fa fa-sign-out-alt fs-5"></i> Logout
                            </a>
                        </li>
                    </ul>
                    <div class="position-bottom">
                        <div class="profile">
                            <span class="fw-bold">
                                <img src="<?php echo $_SESSION['user_session']['profile_picture'] ?>"
                                    alt="<?php echo ucfirst($_SESSION['user_session']['role']); ?>'s Profile Picture"
                                    class="img rounded-pill" width="45px" height="45px" />
                                <?php echo $_SESSION['user_session']['firstname'] ?></span>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><?php echo ucfirst($_SESSION['user_session']['role']); ?> Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                        </div>
                    </div>
                </div>

                <!-- Overview Section -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-header"><?php echo APP_NAME; ?> Total Sales</div>
                            <div class="card-body">
                                <h5 class="card-title">₦<?php echo number_format($total_sales_price, 2); ?></h5>
                                <p class="card-text">Product sold this month</p>
                                <p class="card-text">Total sales made: <?php echo $total_sales_count; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-header"><?php echo APP_NAME; ?> Total Products</div>
                            <div class="card-body">
                                <h5 class="card-title">₦<?php echo number_format($total_products_price, 2); ?></h5>
                                <p class="card-text">Products available in the store now.</p>
                                <p class="card-text">Total available products: <?php echo $total_products_count; ?>.</p>
                            </div>
                        </div>
                    </div>
                    <?php if($_SESSION['user_session']['role'] == 'admin'): ?>
                    <div class="col-md-4">
                        <div class="card text-white bg-info mb-3">
                            <div class="card-header"><?php echo APP_NAME; ?> Total Users</div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $total_users; ?></h5>
                                <p class="card-text">Registered users in the system.</p>
                                <p class="card-text">To add new user, <a href="manage?type=users"
                                        class="link text-decoration-none">click here</a></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>

                <!-- Graphs/Charts Section -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <canvas id="salesChart"></canvas>


                        <div class="row mt-5 mb-5">
                            <div class="col-sm-12 text-center">
                                <h4>Manage Products & Sales</h4>
                                <div class="btn-group">
                                    <a href="manage?type=products" class="btn btn-success">Go to Products</a>
                                    <a href="manage?type=sales" class="btn btn-primary">Go to Sales</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <canvas id="productsChart"></canvas>
                    </div>
                </div>

                <!-- users section -->
                <?php if($_SESSION['user_session']['role'] == 'admin'): ?>
                <div class="row mb-5">
                    <div class="col-md-12">
                        <h4 class="mb-3">Users</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">User ID</th>
                                    <th scope="col">Firstname</th>
                                    <th scope="col">Lastname</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Date Created</th>
                                    <th scope="col">Picture</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- <ul class="list-group"> -->
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo $user['id']; ?></td>
                                        <td><?php echo $user['firstname']; ?></td>
                                        <td><?php echo $user['lastname']; ?></td>
                                        <td><?php echo $user['username']; ?></td>
                                        <td><?php echo $user['email']; ?></td>
                                        <td><?php echo $user['phone_number']; ?></td>
                                        <td><?php echo $user['role']; ?></td>
                                        <td><?php echo $user['created_at']; ?></td>
                                        <td>
                                            <img src="<?php echo $user['profile_picture']; ?>"
                                                alt="<?php echo $user['firstname']; ?> not found" width="45px"
                                                hehight="45px" class="rounded-pill">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                                <!-- </ul> -->
                        </table>
                        <a href="manage?type=users" class="btn btn-warning">Manage Users</a>
                    </div>
                </div>
                <?php endif; ?>


                <!-- Latest Activity Section -->
                <!-- <div class="row mb-5">
                    <div class="col-md-12">
                        <h4 class="mb-3">Latest Activities</h4>
                        <ul class="list-group">
                            <?php // foreach ($sales as $sale): ?>
                                <li class="list-group-item">
                                    Sale of <?php //  echo $sale['quantity']; ?> item(s) for
                                    ₦<?php // echo number_format($sale['total_price'], 2); ?>
                                </li>
                            <?php // endforeach; ?>
                        </ul>
                    </div>
                </div> -->

                <!-- Management Sections -->

            </main>
        </div>
    </div>
    <?php require_once ('includes/footer.php'); ?>
    <?php require_once ('includes/cdn_footer.php'); ?>

    <!-- Scripts for charts (if using Chart.js) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Montly Sales',
                    data: <?php echo $sales_data; ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var ctx = document.getElementById('productsChart').getContext('2d');
        var productsChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo $jsLabel; ?>,
                datasets: [{
                    label: 'Total Counts',
                    data: <?php echo $jsLabelCount; ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'City Tech Store Product Categories'
                    }
                }
            }
        });
    </script>
</body>

</html>
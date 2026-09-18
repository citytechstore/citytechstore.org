<?php require_once('config/config.php'); ?>
<?php require_once('app/models/lib.php'); ?>
<div class="text-center bg-dark text-white py-2 fw-bold leads">
    Please, beware of scammers and make sure the url of this site is:
    <i class="fas fa-lock text-warning fs-6"></i>
    <a href="<?php echo getBaseUrl(); ?>" target="_blank" rel="noopener noreferrer" class="link link-primary"><?php echo getBaseUrl(); ?></a> and our Whatsapp number is
    <a href="<?php echo strtoupper(WA_LINK); ?>" target="_blank" rel="noopener noreferrer" class="link link-primary"><?php echo strtoupper(WA_NUMBER); ?></a>
</div>
<nav class="navbar navbar-expand-lg bg-body-tertiary p-3 sticky-top shadow-sm" aria-label="Main Navbar">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold fs-3" href="" style="color:red;">
            <img src="assets/img/icons/android-chrome-192x192.png" alt="City Tech Logo" width="50px">
            <?php echo strtoupper(APP_NAME); ?>
        </a>
        <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse" id="navbarsExample09">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) : ?>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="dashboard">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard</a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="">Home</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="list?view=products">
                        <i class="fas fa-box-open"></i>
                        Products</a>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" class="nav-link dropdown-toggle">
                        <i class="fas fa-cogs"></i>
                        Manage
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="manage?type=products">
                                <i class="fas fa-boxes"></i>
                                Manage Products</a></li>
                        <li> <a class="dropdown-item" href="manage?type=sales">
                                <i class="fas fa-chart-line"></i>
                                Manage Sales</a></li>
                        <li> <a class="dropdown-item" href="manage?type=orders">
                                <i class="fas fa-truck"></i>
                                Manage Orders</a></li>
                        <li> <a class="dropdown-item" href="manage?type=categories">
                                <i class="fas fa-tags"></i>
                                Manage Categories</a></li>
                        <li> <a class="dropdown-item" href="manage?type=category_banners">
                                <i class="fas fa-image"></i>
                                Manage Category Banners</a></li>
                        <li>
                        <li> <a class="dropdown-item" href="storesection">
                                <i class="fa fa-columns"></i>
                                Manage Sections</a></li>
                        <li>
                            <hr href="" class="dropdown-divider" />
                        </li>
                        <li> <a class="dropdown-item" href="stocks">
                                <i class="fas fa-clipboard-list"></i>
                                View Stock Activity</a></li>
                        <?php if (($_SESSION['user_session']['role'] ?? null) === 'admin') : ?>
                            <li>
                                <hr href="" class="dropdown-divider" />
                            </li>
                            <li> <a class="dropdown-item" href="manage?type=users">
                                    <i class="fas fa-users-cog"></i>
                                    Manage Users</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="profile">
                            <i class="fas fa-user"></i>
                            Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard?logout=true"><i class="fas fa-sign-out-alt"></i>
                            Logout</i>
                        </a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login">Login</a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>
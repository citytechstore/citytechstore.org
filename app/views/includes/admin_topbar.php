<?php
$pageTitle = $pageTitle ?? 'Dashboard';
?>
<header class="admin-topbar d-flex align-items-center justify-content-between sticky-top">
    <div class="d-flex align-items-center gap-3">
        <button class="admin-topbar-toggle d-lg-none" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#adminSidebarOffcanvas" aria-controls="adminSidebarOffcanvas" aria-label="Open menu">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="admin-topbar-title mb-0"><?php echo htmlspecialchars($pageTitle); ?></h1>
    </div>
    <div class="dropdown">
        <a href="#" class="admin-topbar-profile dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="<?php echo htmlspecialchars($_SESSION['user_session']['profile_picture'] ?? 'assets/img/defaults/user.jpg'); ?>"
                 alt="" class="admin-topbar-avatar">
            <span class="d-none d-sm-inline"><?php echo htmlspecialchars($_SESSION['user_session']['firstname'] ?? 'Staff'); ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><span class="dropdown-item-text text-muted small"><?php echo htmlspecialchars(ucfirst($_SESSION['user_session']['role'] ?? '')); ?></span></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="dashboard?logout=true"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
        </ul>
    </div>
</header>

<?php
// Same base-path/current-path detection as the storefront header.php, so
// active-state detection works whether the app runs at the domain root or
// in a subfolder (local XAMPP htdocs/citytechstore.org).
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($basePath !== '' && strpos($currentPath, $basePath) === 0) {
    $currentPath = substr($currentPath, strlen($basePath));
}
$currentPath = ($currentPath === '' || $currentPath === false) ? '/' : $currentPath;
if ($currentPath !== '/') {
    $currentPath = rtrim($currentPath, '/');
}
$currentManageType = $_GET['type'] ?? null;

$adminNavLinks = [
    ['label' => 'Dashboard', 'href' => 'dashboard', 'icon' => 'fa-gauge-high', 'match' => '/dashboard'],
    ['label' => 'Orders', 'href' => 'manage?type=orders', 'icon' => 'fa-truck', 'match' => '/manage', 'matchType' => 'orders'],
    ['label' => 'Products', 'href' => 'manage?type=products', 'icon' => 'fa-box-open', 'match' => '/manage', 'matchType' => 'products'],
    ['label' => 'Categories', 'href' => 'manage?type=categories', 'icon' => 'fa-tags', 'match' => '/manage', 'matchType' => 'categories'],
    ['label' => 'Banners', 'href' => 'manage?type=category_banners', 'icon' => 'fa-image', 'match' => '/manage', 'matchType' => 'category_banners'],
];
// Staff link only exists in the array at all for admins — a worker never
// receives this markup, not just a hidden/disabled copy of it.
if (($_SESSION['user_session']['role'] ?? null) === 'admin') {
    $adminNavLinks[] = ['label' => 'Staff', 'href' => 'manage?type=users', 'icon' => 'fa-users-cog', 'match' => '/manage', 'matchType' => 'users'];
}

$isLinkActive = function ($link) use ($currentPath, $currentManageType) {
    if ($currentPath !== $link['match']) {
        return false;
    }
    return !isset($link['matchType']) || $currentManageType === $link['matchType'];
};
?>
<nav class="admin-sidebar d-none d-lg-flex flex-column" aria-label="Admin navigation">
    <a href="dashboard" class="admin-sidebar-brand">CTS</a>
    <?php foreach ($adminNavLinks as $link): ?>
        <a href="<?php echo htmlspecialchars($link['href']); ?>"
           class="admin-sidebar-link<?php echo $isLinkActive($link) ? ' admin-sidebar-link-active' : ''; ?>"
           title="<?php echo htmlspecialchars($link['label']); ?>">
            <i class="fas <?php echo htmlspecialchars($link['icon']); ?>" aria-hidden="true"></i>
            <span class="admin-sidebar-label"><?php echo htmlspecialchars($link['label']); ?></span>
            <span class="visually-hidden"><?php echo htmlspecialchars($link['label']); ?></span>
        </a>
    <?php endforeach; ?>
</nav>

<div class="offcanvas offcanvas-start admin-sidebar-offcanvas d-lg-none" tabindex="-1" id="adminSidebarOffcanvas" aria-labelledby="adminSidebarOffcanvasLabel">
    <div class="offcanvas-header">
        <span class="admin-sidebar-offcanvas-brand" id="adminSidebarOffcanvasLabel">CTS Admin</span>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <?php foreach ($adminNavLinks as $link): ?>
            <a href="<?php echo htmlspecialchars($link['href']); ?>"
               class="admin-sidebar-offcanvas-link<?php echo $isLinkActive($link) ? ' admin-sidebar-offcanvas-link-active' : ''; ?>"
               title="<?php echo htmlspecialchars($link['label']); ?>">
                <i class="fas <?php echo htmlspecialchars($link['icon']); ?>" aria-hidden="true"></i>
                <span><?php echo htmlspecialchars($link['label']); ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>

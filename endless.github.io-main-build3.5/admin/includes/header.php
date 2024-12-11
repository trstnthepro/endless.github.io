<?php
// admin/includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<?php
// At the top of header.php
define('BASE_URL', '/acad276/Endless/endless.github.io-main-build3.5/admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Admin CSS - using absolute path -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/admin.css">
</head>
<body>
<div class="admin-container">
    <!-- Sidebar Navigation -->
    <nav class="admin-sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-infinity"></i>
                <span>Endless Admin</span>
            </div>
        </div>
        <ul class="admin-nav">
            <li>
                <a href="../../admin_dashboard.php" class="nav-link">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="/admin/endless.php" class="nav-link">
                    <i class="fas fa-layer-group"></i>
                    <span>Endless Items</span>
                </a>
            </li>
            <li>
                <a href="/admin/categories.php" class="nav-link disabled">
                    <i class="fas fa-folder"></i>
                    <span>Categories</span>
                </a>
            </li>
            <li>
                <a href="/admin/users.php" class="nav-link disabled">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content Area -->
    <div class="admin-main">
        <header class="admin-top-header">
            <div class="header-search">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search...">
            </div>
            <div class="header-actions">
                <button class="btn-icon">
                    <i class="fas fa-bell"></i>
                </button>
                <div class="user-menu">
                    <img src="https://via.placeholder.com/32" alt="User" class="user-avatar">
                    <div class="user-dropdown">
                        <a href="/admin/profile.php">Profile</a>
                        <a href="/admin/logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </header>
        <main class="admin-content">
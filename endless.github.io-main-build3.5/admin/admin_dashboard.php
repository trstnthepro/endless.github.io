<?php
require_once 'includes/header.php';
require_once 'config.php';
session_start();

?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard</title>
        <link rel="stylesheet" href="/acad276/Endless/endless.github.io-main-build3.5/admin/admin.css">
    </head>
    <body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Welcome to Admin Dashboard</h1>
            <p>Manage your site content and settings</p>
        </div>

        <div class="quick-stats">
            <div class="stat-card">
                <i class="fas fa-cube"></i>
                <div class="stat-content">
                    <h3>Total Items</h3>
                    <p class="stat-number">0</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="fas fa-folder"></i>
                <div class="stat-content">
                    <h3>Categories</h3>
                    <p class="stat-number">0</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <div class="stat-content">
                    <h3>Users</h3>
                    <p class="stat-number">0</p>
                </div>
            </div>
        </div>

        <div class="admin-modules">
            <!-- Endless Artworks Module -->
            <div class="module-card active">
                <div class="card-header">
                    <i class="fas fa-layer-group"></i>
                    <h2>Endless Artworks</h2>
                </div>
                <div class="card-body">
                    <p>Manage your endless artwork collection</p>
                    <div class="card-actions">
                        <a href="modules/artworks/create.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New
                        </a>
                        <a href="modules/artworks/list.php" class="btn btn-secondary">
                            <i class="fas fa-list"></i> View All
                        </a>
                    </div>
                </div>
            </div>

            <!-- Categories Module (Disabled) -->
            <div class="module-card disabled">
                <div class="card-header">
                    <i class="fas fa-folder"></i>
                    <h2>Categories</h2>
                </div>
                <div class="card-body">
                    <p>Manage artist categories</p>
                    <span class="coming-soon">Coming Soon</span>
                </div>
            </div>

            <!-- User behavior Module (Disabled) -->
            <div class="module-card disabled">
                <div class="card-header">
                    <i class="fas fa-users"></i>
                    <h2>Users</h2>
                </div>
                <div class="card-body">
                    <p>Manage user accounts</p>
                    <span class="coming-soon">Coming Soon</span>
                </div>
            </div>

            <!-- Users behavior  -->
            <div class="module-card active">
                <div class="card-header">
                    <i class="fas fa-users"></i>
                    <h2>User Behavior</h2>
                </div>
                <div class="card-body">
                    <p>View User Searches</p>
                    <div class="card-actions">
                        <a href="modules/user_behavior/user_behavior_visuals.php" class="btn btn-primary">
                            <i class="fas fa-list"></i> View
                        </a>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once 'includes/footer.php'; ?>
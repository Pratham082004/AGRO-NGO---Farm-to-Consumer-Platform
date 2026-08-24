<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {	
    header('location:index.php');
    exit;
}

// Fetch Stats
$farmer_cnt = $dbh->query("SELECT COUNT(*) FROM farmerregistration")->fetchColumn();
$buyer_cnt  = $dbh->query("SELECT COUNT(*) FROM buyerregistration")->fetchColumn();
$product_cnt = $dbh->query("SELECT COUNT(*) FROM products")->fetchColumn();
$order_cnt  = $dbh->query("SELECT COUNT(*) FROM orders")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — AgroNGO Admin</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css?v=2">
    <link rel="stylesheet" href="css/admin-modern.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="admin-layout">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="admin-sidebar__brand">
            <div class="admin-sidebar__brand-icon">🌾</div>
            <span class="admin-sidebar__brand-text">AgroNGO Admin</span>
        </div>

        <div class="admin-sidebar__menu">
            <div class="admin-sidebar__label">Main Menu</div>
            <a href="dashboard.php" class="admin-sidebar__link active">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
            <a href="manage-users.php" class="admin-sidebar__link">
                <i class="fas fa-users"></i> Manage Users
            </a>
            <a href="manage-products.php" class="admin-sidebar__link">
                <i class="fas fa-wheat-awn"></i> Manage Products
            </a>
            <a href="manage-orders.php" class="admin-sidebar__link">
                <i class="fas fa-shopping-cart"></i> Manage Orders
            </a>

            <div class="admin-sidebar__label">AI & Automation</div>
            <a href="runml.php" class="admin-sidebar__link">
                <i class="fas fa-robot"></i> AI Expiry Audit
            </a>

            <div class="admin-sidebar__label">Account</div>
            <a href="change-password.php" class="admin-sidebar__link">
                <i class="fas fa-lock"></i> Change Password
            </a>
            <a href="logout.php" class="admin-sidebar__link" style="color: var(--agro-red-500);">
                <i class="fas fa-sign-out-alt"></i> Sign Out
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="admin-main">
        <!-- Top Bar -->
        <header class="admin-topbar">
            <div class="admin-topbar__title">Dashboard Overview</div>
            <div class="admin-topbar__user">
                <div class="admin-topbar__avatar">A</div>
                <div>
                    <div style="font-weight: 600; font-size: 14px; color: #0f172a;"><?php echo htmlspecialchars($_SESSION['alogin']); ?></div>
                    <div style="font-size: 12px; color: #64748b;">System Administrator</div>
                </div>
            </div>
        </header>

        <!-- Content Body -->
        <div class="admin-content">
            
            <!-- Welcome Banner -->
            <div class="agro-card agro-card--glass" style="padding: 24px 32px; background: linear-gradient(135deg, #166534, #15803d); color: white; border: none; margin-bottom: 28px;">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <h2 style="color: white; font-size: 24px; margin-bottom: 6px;">Welcome back, Administrator! 👋</h2>
                        <p style="color: #bbf7d0; font-size: 14px; margin: 0;">Monitor marketplace activity, manage crop listings, and trigger automated AI shelf-life audits.</p>
                    </div>
                    <a href="runml.php" class="agro-btn agro-btn--secondary agro-btn--lg" style="box-shadow: 0 4px 14px rgba(0,0,0,0.2);">
                        <i class="fas fa-robot"></i> Launch AI Expiry Engine
                    </a>
                </div>
            </div>

            <!-- Stat Cards Grid -->
            <div class="agro-grid agro-grid-4" style="margin-bottom: 32px;">
                <!-- Farmers -->
                <div class="agro-stat-card">
                    <div class="agro-stat-card__icon agro-stat-card__icon--green">
                        <i class="fas fa-tractor"></i>
                    </div>
                    <div class="agro-stat-card__value"><?php echo number_format($farmer_cnt); ?></div>
                    <div class="agro-stat-card__label">Registered Farmers</div>
                    <div style="margin-top: 12px;">
                        <a href="manage-users.php" style="font-size: 12px; font-weight: 600; color: var(--agro-green-600);">View Farmers <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Buyers -->
                <div class="agro-stat-card">
                    <div class="agro-stat-card__icon agro-stat-card__icon--blue">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="agro-stat-card__value"><?php echo number_format($buyer_cnt); ?></div>
                    <div class="agro-stat-card__label">Registered Buyers</div>
                    <div style="margin-top: 12px;">
                        <a href="manage-users.php" style="font-size: 12px; font-weight: 600; color: var(--agro-blue-600);">View Buyers <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Products -->
                <div class="agro-stat-card">
                    <div class="agro-stat-card__icon agro-stat-card__icon--amber">
                        <i class="fas fa-wheat-awn"></i>
                    </div>
                    <div class="agro-stat-card__value"><?php echo number_format($product_cnt); ?></div>
                    <div class="agro-stat-card__label">Active Crops Listed</div>
                    <div style="margin-top: 12px;">
                        <a href="manage-products.php" style="font-size: 12px; font-weight: 600; color: var(--agro-amber-600);">Browse Produce <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Orders -->
                <div class="agro-stat-card">
                    <div class="agro-stat-card__icon agro-stat-card__icon--green" style="background: rgba(147, 51, 234, 0.1); color: #9333ea;">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="agro-stat-card__value"><?php echo number_format($order_cnt); ?></div>
                    <div class="agro-stat-card__label">Marketplace Orders</div>
                    <div style="margin-top: 12px;">
                        <a href="manage-orders.php" style="font-size: 12px; font-weight: 600; color: #9333ea;">Track Orders <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Recent Products & Recent Orders Section -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <!-- Recent Products Card -->
                <div class="admin-card">
                    <div class="admin-card__header">
                        <div class="admin-card__title">
                            <i class="fas fa-leaf" style="color: #22c55e;"></i> Recent Produce Listings
                        </div>
                        <a href="manage-products.php" class="agro-btn agro-btn--ghost agro-btn--sm">View All</a>
                    </div>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Expiry</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
$query_p = $dbh->query("SELECT product_title, product_price, product_stock, product_expiry FROM products ORDER BY product_id DESC LIMIT 5");
$rec_products = $query_p->fetchAll(PDO::FETCH_OBJ);
if($query_p->rowCount() > 0) {
    foreach($rec_products as $p) {
?>
                            <tr>
                                <td><strong><?php echo htmlentities($p->product_title); ?></strong></td>
                                <td><span class="admin-badge admin-badge--green">₹<?php echo htmlentities($p->product_price); ?></span></td>
                                <td><?php echo htmlentities($p->product_stock); ?> kg</td>
                                <td><code><?php echo htmlentities($p->product_expiry); ?></code></td>
                            </tr>
<?php } } else { ?>
                            <tr><td colspan="4" class="agro-text-center">No recent produce listings.</td></tr>
<?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- Recent Orders Card -->
                <div class="admin-card">
                    <div class="admin-card__header">
                        <div class="admin-card__title">
                            <i class="fas fa-shopping-cart" style="color: #3b82f6;"></i> Recent Orders
                        </div>
                        <a href="manage-orders.php" class="agro-btn agro-btn--ghost agro-btn--sm">View All</a>
                    </div>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Buyer Phone</th>
                                <th>Total</th>
                                <th>Payment</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
$query_o = $dbh->query("SELECT order_id, buyer_phonenumber, total, payment FROM orders ORDER BY order_id DESC LIMIT 5");
$rec_orders = $query_o->fetchAll(PDO::FETCH_OBJ);
if($query_o->rowCount() > 0) {
    foreach($rec_orders as $o) {
?>
                            <tr>
                                <td><strong>#ORD-<?php echo htmlentities($o->order_id); ?></strong></td>
                                <td><?php echo htmlentities($o->buyer_phonenumber); ?></td>
                                <td><strong>₹<?php echo htmlentities($o->total); ?></strong></td>
                                <td><span class="admin-badge admin-badge--blue"><?php echo htmlentities($o->payment ? $o->payment : 'COD'); ?></span></td>
                            </tr>
<?php } } else { ?>
                            <tr><td colspan="4" class="agro-text-center">No recent orders.</td></tr>
<?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
</div>

</body>
</html>

<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {	
    header('location:index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders — AgroNGO Admin</title>
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
            <a href="dashboard.php" class="admin-sidebar__link">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
            <a href="manage-users.php" class="admin-sidebar__link">
                <i class="fas fa-users"></i> Manage Users
            </a>
            <a href="manage-products.php" class="admin-sidebar__link">
                <i class="fas fa-wheat-awn"></i> Manage Products
            </a>
            <a href="manage-orders.php" class="admin-sidebar__link active">
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
            <div class="admin-topbar__title">Marketplace Transactions & Orders</div>
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

            <!-- Orders Table Card -->
            <div class="admin-card">
                <div class="admin-card__header">
                    <div class="admin-card__title">
                        <i class="fas fa-shopping-cart" style="color: #2563eb;"></i> Order History & Direct Settlements
                    </div>
                    <span class="admin-badge admin-badge--blue">All Placed Orders</span>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Buyer Phone</th>
                            <th>Farmer Phone</th>
                            <th>Product ID</th>
                            <th>Quantity</th>
                            <th>Total Amount</th>
                            <th>Payment</th>
                            <th>Delivery Address</th>
                        </tr>
                    </thead>
                    <tbody>
<?php 
$sql = "SELECT * FROM orders ORDER BY order_id DESC";
$query = $dbh->prepare($sql);
$query->execute();
$orders = $query->fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0) {
    foreach($orders as $o) {
?>
                        <tr>
                            <td><strong>#ORD-<?php echo $o->order_id; ?></strong></td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-user-circle" style="color: #2563eb;"></i>
                                    <span><?php echo htmlentities($o->buyer_phonenumber); ?></span>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-tractor" style="color: #16a34a;"></i>
                                    <span><?php echo htmlentities($o->phonenumber); ?></span>
                                </div>
                            </td>
                            <td><span class="admin-badge admin-badge--amber">Product #<?php echo htmlentities($o->product_id); ?></span></td>
                            <td><strong><?php echo htmlentities($o->qty); ?></strong></td>
                            <td><strong style="color: #16a34a; font-size: 15px;">₹<?php echo htmlentities($o->total); ?></strong></td>
                            <td><span class="admin-badge admin-badge--green"><?php echo htmlentities($o->payment ? $o->payment : 'COD'); ?></span></td>
                            <td><span style="font-size: 12px; color: #64748b;"><?php echo htmlentities($o->address); ?></span></td>
                        </tr>
<?php } } else { ?>
                        <tr><td colspan="8" class="agro-text-center">No orders recorded in database.</td></tr>
<?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </main>
</div>

</body>
</html>

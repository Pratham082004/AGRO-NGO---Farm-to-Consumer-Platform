<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {	
    header('location:index.php');
    exit;
}

// Delete Product Handler
$msg = "";
$error = "";
if(isset($_GET['del'])) {
    $del_id = intval($_GET['del']);
    $sql_del = "DELETE FROM products WHERE product_id = :id";
    $query_del = $dbh->prepare($sql_del);
    $query_del->bindParam(':id', $del_id, PDO::PARAM_INT);
    if($query_del->execute()) {
        $msg = "Produce listing #$del_id has been removed.";
    } else {
        $error = "Failed to remove produce listing.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Produce — AgroNGO Admin</title>
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
            <a href="manage-products.php" class="admin-sidebar__link active">
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
            <div class="admin-topbar__title">Crop & Produce Inventory</div>
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

            <?php if($msg): ?>
                <div class="agro-card" style="padding: 16px 20px; background: #dcfce7; color: #15803d; border-color: #bbf7d0; margin-bottom: 24px; font-weight: 600;">
                    <i class="fas fa-check-circle" style="margin-right: 8px;"></i> <?php echo htmlentities($msg); ?>
                </div>
            <?php endif; ?>

            <?php if($error): ?>
                <div class="agro-card" style="padding: 16px 20px; background: #fee2e2; color: #b91c1c; border-color: #fca5a5; margin-bottom: 24px; font-weight: 600;">
                    <i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i> <?php echo htmlentities($error); ?>
                </div>
            <?php endif; ?>

            <!-- Produce Inventory Card -->
            <div class="admin-card">
                <div class="admin-card__header">
                    <div class="admin-card__title">
                        <i class="fas fa-leaf" style="color: #16a34a;"></i> Active Produce & Crop Listings
                    </div>
                    <span class="admin-badge admin-badge--amber">Live Marketplace Stock</span>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Produce Item</th>
                            <th>Category</th>
                            <th>Price (₹)</th>
                            <th>Stock (kg)</th>
                            <th>Expiry Date</th>
                            <th>Keywords</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
<?php 
$sql = "SELECT * FROM products ORDER BY product_id DESC";
$query = $dbh->prepare($sql);
$query->execute();
$products = $query->fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0) {
    foreach($products as $p) {
        $img_path = !empty($p->product_image) ? "../Admin/product_images/" . $p->product_image : "../Images/Website/noimage.jpg";
?>
                        <tr>
                            <td><strong>#<?php echo $p->product_id; ?></strong></td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <img src="<?php echo htmlentities($img_path); ?>" style="width: 44px; height: 44px; border-radius: 10px; object-fit: cover; border: 1px solid #e2e8f0;" onerror="this.src='../Images/Website/noimage.jpg'">
                                    <div>
                                        <div style="font-weight: 700; color: #0f172a;"><?php echo htmlentities($p->product_title); ?></div>
                                        <div style="font-size: 12px; color: #64748b;">Farmer ID: #<?php echo htmlentities($p->farmer_fk ? $p->farmer_fk : 'N/A'); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="admin-badge admin-badge--blue">Category #<?php echo htmlentities($p->product_cat); ?></span></td>
                            <td><strong style="color: #16a34a; font-size: 15px;">₹<?php echo htmlentities($p->product_price); ?></strong></td>
                            <td><span class="admin-badge admin-badge--amber"><?php echo htmlentities($p->product_stock); ?> kg</span></td>
                            <td><code><?php echo htmlentities($p->product_expiry); ?></code></td>
                            <td><span style="font-size: 12px; color: #64748b;"><?php echo htmlentities($p->product_keywords ? $p->product_keywords : '—'); ?></span></td>
                            <td>
                                <a href="manage-products.php?del=<?php echo $p->product_id; ?>" onclick="return confirm('Delete produce listing #<?php echo $p->product_id; ?>?');" class="agro-btn agro-btn--danger agro-btn--sm">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
<?php } } else { ?>
                        <tr><td colspan="8" class="agro-text-center">No produce items currently listed.</td></tr>
<?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </main>
</div>

</body>
</html>

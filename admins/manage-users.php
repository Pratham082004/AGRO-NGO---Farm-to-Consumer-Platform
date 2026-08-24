<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {	
    header('location:index.php');
    exit;
}

// Delete User Handlers if needed
$msg = "";
$error = "";
if(isset($_GET['del_farmer'])) {
    $fid = intval($_GET['del_farmer']);
    $stmt = $dbh->prepare("DELETE FROM farmerregistration WHERE farmer_id = :id");
    if($stmt->execute([':id' => $fid])) {
        $msg = "Farmer account (ID: $fid) removed.";
    }
}
if(isset($_GET['del_buyer'])) {
    $bid = intval($_GET['del_buyer']);
    $stmt = $dbh->prepare("DELETE FROM buyerregistration WHERE buyer_id = :id");
    if($stmt->execute([':id' => $bid])) {
        $msg = "Buyer account (ID: $bid) removed.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users — AgroNGO Admin</title>
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
            <a href="manage-users.php" class="admin-sidebar__link active">
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
            <div class="admin-topbar__title">Manage Platform Users</div>
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

            <!-- Registered Farmers Card -->
            <div class="admin-card">
                <div class="admin-card__header">
                    <div class="admin-card__title">
                        <i class="fas fa-tractor" style="color: #16a34a;"></i> Registered Farmers
                    </div>
                    <span class="admin-badge admin-badge--green">Active Farmers</span>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Farmer Name</th>
                            <th>Phone Number</th>
                            <th>PAN Number</th>
                            <th>Location</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
<?php 
$sql = "SELECT * FROM farmerregistration ORDER BY farmer_id DESC";
$query = $dbh->prepare($sql);
$query->execute();
$farmers = $query->fetchAll(PDO::FETCH_OBJ);
$cnt = 1;
if($query->rowCount() > 0) {
    foreach($farmers as $f) {
?>
                        <tr>
                            <td><?php echo $cnt; ?></td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #dcfce7; color: #16a34a; font-weight: 700; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                                        <?php echo strtoupper(substr($f->farmer_name, 0, 1)); ?>
                                    </div>
                                    <strong><?php echo htmlentities($f->farmer_name); ?></strong>
                                </div>
                            </td>
                            <td><i class="fas fa-phone" style="font-size: 12px; color: #64748b; margin-right: 4px;"></i> <?php echo htmlentities($f->farmer_phone); ?></td>
                            <td><code><?php echo htmlentities($f->farmer_pan); ?></code></td>
                            <td><span class="admin-badge admin-badge--green"><?php echo htmlentities($f->farmer_district ? $f->farmer_district . ', ' . $f->farmer_state : $f->farmer_state); ?></span></td>
                            <td><?php echo htmlentities($f->farmer_address); ?></td>
                            <td>
                                <a href="manage-users.php?del_farmer=<?php echo $f->farmer_id; ?>" onclick="return confirm('Delete farmer account?');" class="agro-btn agro-btn--danger agro-btn--sm">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
<?php $cnt++; } } else { ?>
                        <tr><td colspan="7" class="agro-text-center">No farmers registered yet.</td></tr>
<?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Registered Buyers Card -->
            <div class="admin-card">
                <div class="admin-card__header">
                    <div class="admin-card__title">
                        <i class="fas fa-shopping-bag" style="color: #2563eb;"></i> Registered Buyers
                    </div>
                    <span class="admin-badge admin-badge--blue">Active Buyers</span>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Buyer Name</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Company</th>
                            <th>PAN Number</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
<?php 
$sql2 = "SELECT * FROM buyerregistration ORDER BY buyer_id DESC";
$query2 = $dbh->prepare($sql2);
$query2->execute();
$buyers = $query2->fetchAll(PDO::FETCH_OBJ);
$cnt2 = 1;
if($query2->rowCount() > 0) {
    foreach($buyers as $b) {
?>
                        <tr>
                            <td><?php echo $cnt2; ?></td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #dbeafe; color: #2563eb; font-weight: 700; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                                        <?php echo strtoupper(substr($b->buyer_name, 0, 1)); ?>
                                    </div>
                                    <strong><?php echo htmlentities($b->buyer_name); ?></strong>
                                </div>
                            </td>
                            <td><i class="fas fa-phone" style="font-size: 12px; color: #64748b; margin-right: 4px;"></i> <?php echo htmlentities($b->buyer_phone); ?></td>
                            <td><?php echo htmlentities($b->buyer_mail); ?></td>
                            <td><?php echo htmlentities($b->buyer_comp); ?></td>
                            <td><code><?php echo htmlentities($b->buyer_pan); ?></code></td>
                            <td><?php echo htmlentities($b->buyer_addr); ?></td>
                            <td>
                                <a href="manage-users.php?del_buyer=<?php echo $b->buyer_id; ?>" onclick="return confirm('Delete buyer account?');" class="agro-btn agro-btn--danger agro-btn--sm">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
<?php $cnt2++; } } else { ?>
                        <tr><td colspan="8" class="agro-text-center">No buyers registered yet.</td></tr>
<?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </main>
</div>

</body>
</html>

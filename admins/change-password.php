<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {	
    header('location:index.php');
    exit;
}

$msg = "";
$error = "";
if(isset($_POST['submit'])) {
    $password = md5($_POST['password']);
    $newpassword = md5($_POST['newpassword']);
    $username = $_SESSION['alogin'];

    $sql = "SELECT Password FROM admin WHERE UserName=:username and Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();

    if($query->rowCount() > 0) {
        $con = "UPDATE admin SET Password=:newpassword WHERE UserName=:username";
        $chngpwd1 = $dbh->prepare($con);
        $chngpwd1->bindParam(':username', $username, PDO::PARAM_STR);
        $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
        $chngpwd1->execute();
        $msg = "Your password has been changed successfully.";
    } else {
        $error = "Current password does not match our records.";	
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password — AgroNGO Admin</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css?v=2">
    <link rel="stylesheet" href="css/admin-modern.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
    function valid() {
        if(document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
            alert("New Password and Confirm Password do not match!");
            document.chngpwd.confirmpassword.focus();
            return false;
        }
        return true;
    }
    </script>
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
            <a href="manage-orders.php" class="admin-sidebar__link">
                <i class="fas fa-shopping-cart"></i> Manage Orders
            </a>

            <div class="admin-sidebar__label">AI & Automation</div>
            <a href="runml.php" class="admin-sidebar__link">
                <i class="fas fa-robot"></i> AI Expiry Audit
            </a>

            <div class="admin-sidebar__label">Account</div>
            <a href="change-password.php" class="admin-sidebar__link active">
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
            <div class="admin-topbar__title">Change Password</div>
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

            <div style="max-width: 540px; margin: 0 auto;">
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

                <div class="admin-card" style="padding: 32px;">
                    <div style="text-align: center; margin-bottom: 24px;">
                        <div style="width: 56px; height: 56px; border-radius: 16px; background: rgba(34, 197, 94, 0.1); color: #16a34a; font-size: 24px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <h2 style="font-size: 20px; font-weight: 700; color: #0f172a;">Update Security Credentials</h2>
                        <p style="font-size: 14px; color: #64748b; margin-top: 4px;">Update your administrator account password</p>
                    </div>

                    <form name="chngpwd" method="post" onSubmit="return valid();">
                        <div class="agro-form-group">
                            <label class="agro-label" for="password">Current Password</label>
                            <input type="password" name="password" id="password" class="agro-input" placeholder="Enter current password" required>
                        </div>

                        <div class="agro-form-group">
                            <label class="agro-label" for="newpassword">New Password</label>
                            <input type="password" name="newpassword" id="newpassword" class="agro-input" placeholder="Enter new password" required>
                        </div>

                        <div class="agro-form-group">
                            <label class="agro-label" for="confirmpassword">Confirm New Password</label>
                            <input type="password" name="confirmpassword" id="confirmpassword" class="agro-input" placeholder="Confirm new password" required>
                        </div>

                        <button type="submit" name="submit" class="agro-btn agro-btn--primary agro-btn--full agro-btn--lg" style="margin-top: 12px;">
                            <i class="fas fa-key"></i> Update Password
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </main>
</div>

</body>
</html>

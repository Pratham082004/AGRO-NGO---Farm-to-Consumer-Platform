<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../Includes/db.php");
include("../Includes/components/navbar.php");
include("../Includes/components/footer.php");

$sessphonenumber = $_SESSION['phonenumber'] ?? null;
$name = ''; $phone = ''; $address = ''; $pan = ''; $bank = ''; $comp = ''; $license = ''; $mail = ''; $user = '';

if ($sessphonenumber) {
    $sql = "select * from buyerregistration where buyer_phone = '$sessphonenumber'";
    $run_query = mysqli_query($con, $sql);
    if ($run_query && $row = mysqli_fetch_array($run_query)) {
        $name = $row['buyer_name'];
        $phone = $row['buyer_phone'];
        $address = $row['buyer_addr'];
        $pan = $row['buyer_pan'];
        $bank = $row['buyer_bank'];
        $comp = $row['buyer_comp'];
        $license = $row['buyer_license'];
        $mail = $row['buyer_mail'];
        $user = $row['buyer_username'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Profile — AgroNGO</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- Shared Navbar -->
    <?php agro_navbar('buyer', 'profile'); ?>

    <!-- Main Content Container -->
    <div class="agro-container agro-section">
        <div style="max-width: 760px; margin: 0 auto;">
            
            <?php if ($sessphonenumber): ?>
                <div class="agro-profile">
                    <!-- Profile Header -->
                    <div class="agro-profile__header" style="background: linear-gradient(135deg, var(--agro-blue-600), var(--agro-blue-800));">
                        <div class="agro-profile__avatar">
                            <?php echo strtoupper(substr($name ? $name : 'B', 0, 1)); ?>
                        </div>
                    </div>

                    <!-- Profile Body -->
                    <div class="agro-profile__body">
                        <div class="agro-flex-between" style="flex-wrap: wrap; gap: var(--space-4); margin-bottom: var(--space-6);">
                            <div>
                                <h1 class="agro-profile__name"><?php echo htmlspecialchars($name); ?></h1>
                                <div class="agro-profile__role">
                                    <i class="fas fa-store" style="color: var(--agro-blue-600); margin-right: 6px;"></i>Registered Buyer / Commercial Customer
                                </div>
                            </div>
                            <div>
                                <a href="BuyerEditProfile.php" class="agro-btn agro-btn--outline">
                                    <i class="fas fa-edit"></i> Edit Profile
                                </a>
                            </div>
                        </div>

                        <!-- Details Grid -->
                        <div class="agro-grid agro-grid-2" style="gap: var(--space-4);">
                            <div class="agro-stat-card" style="padding: var(--space-4);">
                                <div class="agro-label"><i class="fas fa-user-tag" style="margin-right: 6px; color: var(--color-secondary);"></i>Username</div>
                                <div style="font-size: var(--text-base); font-weight: 600; color: var(--text-primary);"><?php echo htmlspecialchars($user); ?></div>
                            </div>

                            <div class="agro-stat-card" style="padding: var(--space-4);">
                                <div class="agro-label"><i class="fas fa-phone-alt" style="margin-right: 6px; color: var(--color-secondary);"></i>Phone Number</div>
                                <div style="font-size: var(--text-base); font-weight: 600; color: var(--text-primary);"><?php echo htmlspecialchars($phone); ?></div>
                            </div>

                            <div class="agro-stat-card" style="padding: var(--space-4);">
                                <div class="agro-label"><i class="fas fa-envelope" style="margin-right: 6px; color: var(--color-secondary);"></i>Email Address</div>
                                <div style="font-size: var(--text-base); font-weight: 600; color: var(--text-primary);"><?php echo htmlspecialchars($mail ? $mail : 'N/A'); ?></div>
                            </div>

                            <div class="agro-stat-card" style="padding: var(--space-4);">
                                <div class="agro-label"><i class="fas fa-building" style="margin-right: 6px; color: var(--color-secondary);"></i>Company Name</div>
                                <div style="font-size: var(--text-base); font-weight: 600; color: var(--text-primary);"><?php echo htmlspecialchars($comp ? $comp : 'N/A'); ?></div>
                            </div>

                            <div class="agro-stat-card" style="padding: var(--space-4);">
                                <div class="agro-label"><i class="fas fa-file-contract" style="margin-right: 6px; color: var(--color-secondary);"></i>FSSAI / Trade License</div>
                                <div style="font-size: var(--text-base); font-weight: 600; color: var(--text-primary);"><?php echo htmlspecialchars($license ? $license : 'N/A'); ?></div>
                            </div>

                            <div class="agro-stat-card" style="padding: var(--space-4);">
                                <div class="agro-label"><i class="fas fa-id-card" style="margin-right: 6px; color: var(--color-secondary);"></i>PAN Number</div>
                                <div style="font-size: var(--text-base); font-weight: 600; color: var(--text-primary);"><?php echo htmlspecialchars($pan ? $pan : 'N/A'); ?></div>
                            </div>

                            <div class="agro-stat-card" style="padding: var(--space-4);">
                                <div class="agro-label"><i class="fas fa-university" style="margin-right: 6px; color: var(--color-secondary);"></i>Bank Account</div>
                                <div style="font-size: var(--text-base); font-weight: 600; color: var(--text-primary);"><?php echo htmlspecialchars($bank ? $bank : 'N/A'); ?></div>
                            </div>
                        </div>

                        <div class="agro-stat-card" style="padding: var(--space-4); margin-top: var(--space-4);">
                            <div class="agro-label"><i class="fas fa-location-dot" style="margin-right: 6px; color: var(--color-secondary);"></i>Delivery Address</div>
                            <div style="font-size: var(--text-base); font-weight: 500; color: var(--text-primary);"><?php echo htmlspecialchars($address); ?></div>
                        </div>

                        <div class="agro-flex" style="gap: var(--space-4); margin-top: var(--space-8);">
                            <a href="BuyerEditProfile.php" class="agro-btn agro-btn--secondary">
                                <i class="fas fa-pen-to-square"></i> Edit Profile Details
                            </a>
                            <a href="BuyerChangePassword.php" class="agro-btn agro-btn--outline">
                                <i class="fas fa-key"></i> Change Password
                            </a>
                            <a href="cartpage.php" class="agro-btn agro-btn--primary" style="margin-left: auto;">
                                <i class="fas fa-cart-shopping"></i> View Cart
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="agro-empty">
                    <div class="agro-empty__icon">🔒</div>
                    <h3 class="agro-empty__title">Authentication Required</h3>
                    <p class="agro-empty__desc">Please sign in to your buyer account to view your profile.</p>
                    <a href="../auth/BuyerLogin.php" class="agro-btn agro-btn--secondary agro-btn--lg">Buyer Login</a>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- Shared Footer -->
    <?php agro_footer('buyer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

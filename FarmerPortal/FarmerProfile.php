<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../Includes/db.php");
include("../Includes/components/navbar.php");
include("../Includes/components/footer.php");

$sessphonenumber = $_SESSION['phonenumber'] ?? null;
$sql = "select * from farmerregistration where farmer_phone = '$sessphonenumber' ";
$run_query = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($run_query)) {
    $name = $row['farmer_name'];
    $phone = $row['farmer_phone'];
    $address = $row['farmer_address'];
    $pan = $row['farmer_pan'];
    $bank = $row['farmer_bank'];
    $state = $row['farmer_state'];
    $district = $row['farmer_district'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Profile — AgroNGO</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- Shared Navbar -->
    <?php agro_navbar('farmer', 'profile'); ?>

    <!-- Main Content -->
    <div class="agro-container agro-section">
        <div style="max-width: 720px; margin: 0 auto;">
            
            <div class="agro-profile">
                <!-- Profile Header -->
                <div class="agro-profile__header">
                    <div class="agro-profile__avatar">
                        <?php echo strtoupper(substr($name, 0, 1)); ?>
                    </div>
                </div>

                <!-- Profile Body -->
                <div class="agro-profile__body">
                    <div class="agro-flex-between" style="flex-wrap: wrap; gap: var(--space-4); margin-bottom: var(--space-6);">
                        <div>
                            <h1 class="agro-profile__name"><?php echo htmlspecialchars($name); ?></h1>
                            <div class="agro-profile__role">
                                <i class="fas fa-seedling" style="color: var(--color-primary); margin-right: 6px;"></i>Registered Farmer
                            </div>
                        </div>
                        <div>
                            <a href="EditProfile.php" class="agro-btn agro-btn--outline">
                                <i class="fas fa-edit"></i> Edit Profile
                            </a>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="agro-grid agro-grid-2" style="gap: var(--space-5);">
                        <div class="agro-stat-card" style="padding: var(--space-4);">
                            <div class="agro-label"><i class="fas fa-phone-alt" style="margin-right: 6px; color: var(--color-primary);"></i>Phone Number</div>
                            <div style="font-size: var(--text-base); font-weight: 600; color: var(--text-primary);"><?php echo htmlspecialchars($phone); ?></div>
                        </div>

                        <div class="agro-stat-card" style="padding: var(--space-4);">
                            <div class="agro-label"><i class="fas fa-globe-americas" style="margin-right: 6px; color: var(--color-primary);"></i>State & District</div>
                            <div style="font-size: var(--text-base); font-weight: 600; color: var(--text-primary);"><?php echo htmlspecialchars($district . ', ' . $state); ?></div>
                        </div>

                        <div class="agro-stat-card" style="padding: var(--space-4);">
                            <div class="agro-label"><i class="fas fa-id-card" style="margin-right: 6px; color: var(--color-primary);"></i>PAN Number</div>
                            <div style="font-size: var(--text-base); font-weight: 600; color: var(--text-primary);"><?php echo htmlspecialchars($pan); ?></div>
                        </div>

                        <div class="agro-stat-card" style="padding: var(--space-4);">
                            <div class="agro-label"><i class="fas fa-university" style="margin-right: 6px; color: var(--color-primary);"></i>Bank Account</div>
                            <div style="font-size: var(--text-base); font-weight: 600; color: var(--text-primary);"><?php echo htmlspecialchars($bank); ?></div>
                        </div>
                    </div>

                    <div class="agro-stat-card" style="padding: var(--space-4); margin-top: var(--space-5);">
                        <div class="agro-label"><i class="fas fa-home" style="margin-right: 6px; color: var(--color-primary);"></i>Present Address</div>
                        <div style="font-size: var(--text-base); font-weight: 500; color: var(--text-primary);"><?php echo htmlspecialchars($address); ?></div>
                    </div>

                    <div class="agro-flex" style="gap: var(--space-4); margin-top: var(--space-8);">
                        <a href="ChangePassword.php" class="agro-btn agro-btn--secondary">
                            <i class="fas fa-key"></i> Change Password
                        </a>
                        <a href="MyProducts.php" class="agro-btn agro-btn--primary">
                            <i class="fas fa-boxes-stacked"></i> Manage My Products
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Shared Footer -->
    <?php agro_footer('farmer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

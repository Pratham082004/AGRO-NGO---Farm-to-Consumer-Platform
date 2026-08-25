<?php
include("../Functions/functions.php");
include("../Includes/components/navbar.php");
include("../Includes/components/footer.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Farmers Directory — AgroNGO</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- Shared Navbar -->
    <?php agro_navbar('buyer', 'farmers'); ?>

    <!-- Page Header -->
    <div class="agro-page-header" style="background: var(--surface-primary); border-bottom: 1px solid var(--gray-200);">
        <div class="agro-container">
            <div class="agro-page-header__breadcrumb">
                <a href="bhome.php">Home</a> / <span>Farmers Directory</span>
            </div>
            <h1 class="agro-page-header__title">Local Farmers Directory</h1>
            <p class="agro-page-header__desc">Connect directly with verified agricultural producers and local growers across India.</p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="agro-container agro-section">
        <?php cart(); ?>

        <?php
        global $con;
        $get_farmers = "select * from farmerregistration order by farmer_id desc";
        $run_farmers = mysqli_query($con, $get_farmers);
        $count = $run_farmers ? mysqli_num_rows($run_farmers) : 0;
        ?>

        <?php if ($count > 0): ?>
            <div class="agro-grid agro-grid-3" style="gap: var(--space-6);">
                <?php
                while ($farmer = mysqli_fetch_array($run_farmers)) {
                    $farmer_id = $farmer['farmer_id'];
                    $farmer_name = $farmer['farmer_name'];
                    $farmer_phone = $farmer['farmer_phone'];
                    $farmer_state = $farmer['farmer_state'];
                    $farmer_district = $farmer['farmer_district'];
                    $farmer_address = $farmer['farmer_address'];
                    $initial = strtoupper(substr($farmer_name, 0, 1));

                    // Count products listed by this farmer
                    $count_query = "select count(*) as total from products where farmer_fk = '$farmer_id'";
                    $count_run = mysqli_query($con, $count_query);
                    $total_products = 0;
                    if ($count_run && $c_row = mysqli_fetch_array($count_run)) {
                        $total_products = $c_row['total'];
                    }
                    ?>
                    <div class="agro-card agro-p-6 agro-text-center">
                        <div class="agro-navbar__avatar" style="width: 72px; height: 72px; font-size: 1.8rem; margin: 0 auto var(--space-4); background: linear-gradient(135deg, var(--agro-green-400), var(--agro-green-600)); color: white; box-shadow: var(--shadow-md);">
                            <?php echo $initial; ?>
                        </div>
                        <h3 style="font-size: var(--text-xl); margin-bottom: var(--space-1);"><?php echo htmlspecialchars($farmer_name); ?></h3>
                        <div style="font-size: var(--text-xs); color: var(--color-primary); font-weight: 700; text-transform: uppercase; margin-bottom: var(--space-3);">
                            <i class="fas fa-check-circle"></i> Verified Producer
                        </div>

                        <div class="agro-flex-center" style="gap: var(--space-2); margin-bottom: var(--space-4); flex-wrap: wrap;">
                            <span class="agro-badge agro-badge--green">
                                <i class="fas fa-location-dot"></i> <?php echo htmlspecialchars($farmer_district . ', ' . $farmer_state); ?>
                            </span>
                            <span class="agro-badge agro-badge--blue">
                                <i class="fas fa-boxes-stacked"></i> <?php echo $total_products; ?> Harvest Listings
                            </span>
                        </div>

                        <div style="font-size: var(--text-xs); color: var(--text-tertiary); margin-bottom: var(--space-6);">
                            <i class="fas fa-house"></i> <?php echo htmlspecialchars($farmer_address); ?>
                        </div>

                        <a href="BuyerPageFarmerProfile.php?farmer_id=<?php echo $farmer_id; ?>" class="agro-btn agro-btn--primary agro-btn--full">
                            <i class="fas fa-user"></i> View Profile & Produce
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php else: ?>
            <div class="agro-empty">
                <div class="agro-empty__icon">🧑‍🌾</div>
                <h3 class="agro-empty__title">No Farmers Registered Yet</h3>
                <p class="agro-empty__desc">Check back soon as local growers register their accounts!</p>
                <a href="bhome.php" class="agro-btn agro-btn--primary agro-btn--lg">Back to Homepage</a>
            </div>
        <?php endif; ?>

    </div>

    <!-- Shared Footer -->
    <?php agro_footer('buyer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

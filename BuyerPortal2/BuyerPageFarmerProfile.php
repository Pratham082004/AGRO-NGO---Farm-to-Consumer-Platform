<?php
include("../Functions/functions.php");
include("../Includes/components/navbar.php");
include("../Includes/components/footer.php");

$farmer_id = isset($_GET['farmer_id']) ? intval($_GET['farmer_id']) : 0;
$farmer_name = "Farmer Profile";
$farmer_phone = "";
$farmer_address = "";
$farmer_state = "";
$farmer_district = "";

if ($farmer_id > 0) {
    $farmer_query = "select * from farmerregistration where farmer_id = '$farmer_id'";
    $run_farmer = mysqli_query($con, $farmer_query);
    if ($run_farmer && $row = mysqli_fetch_array($run_farmer)) {
        $farmer_name = $row['farmer_name'];
        $farmer_phone = $row['farmer_phone'];
        $farmer_address = $row['farmer_address'];
        $farmer_state = $row['farmer_state'];
        $farmer_district = $row['farmer_district'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($farmer_name); ?> — Farmer Profile | AgroNGO</title>
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
                <a href="bhome.php">Home</a> / <a href="farmers.php">Farmers</a> / <span><?php echo htmlspecialchars($farmer_name); ?></span>
            </div>
            <h1 class="agro-page-header__title"><?php echo htmlspecialchars($farmer_name); ?></h1>
            <p class="agro-page-header__desc">Verified agricultural grower producing fresh local harvests.</p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="agro-container agro-section">
        <?php cart(); ?>

        <!-- Farmer Overview Card -->
        <div class="agro-card agro-p-8 agro-mb-8">
            <div class="agro-grid agro-grid-3" style="gap: var(--space-6); align-items: center;">
                
                <div class="agro-text-center">
                    <div class="agro-navbar__avatar" style="width:84px; height:84px; font-size:2.2rem; margin: 0 auto var(--space-3); background: linear-gradient(135deg, var(--agro-green-500), var(--agro-green-700)); color:white;">
                        <?php echo strtoupper(substr($farmer_name, 0, 1)); ?>
                    </div>
                    <h3><?php echo htmlspecialchars($farmer_name); ?></h3>
                    <span class="agro-badge agro-badge--green" style="margin-top: 4px;"><i class="fas fa-check-circle"></i> Verified Grower</span>
                </div>

                <div class="agro-flex" style="flex-direction: column; gap: var(--space-4);">
                    <div class="agro-stat-card" style="padding: var(--space-4);">
                        <div class="agro-label"><i class="fas fa-phone" style="margin-right:6px; color:var(--color-primary);"></i>Phone Number</div>
                        <div style="font-weight:600;"><?php echo htmlspecialchars($farmer_phone ?: 'Contact via Order'); ?></div>
                    </div>

                    <div class="agro-stat-card" style="padding: var(--space-4);">
                        <div class="agro-label"><i class="fas fa-location-dot" style="margin-right:6px; color:var(--color-primary);"></i>Location</div>
                        <div style="font-weight:600;"><?php echo htmlspecialchars($farmer_district . ', ' . $farmer_state); ?></div>
                    </div>
                </div>

                <div class="agro-stat-card" style="padding: var(--space-5);">
                    <div class="agro-label"><i class="fas fa-house" style="margin-right:6px; color:var(--color-primary);"></i>Farm Address</div>
                    <div style="font-weight:500; margin-top:4px; color:var(--text-secondary);"><?php echo htmlspecialchars($farmer_address); ?></div>
                </div>

            </div>
        </div>

        <!-- Produce Section Header -->
        <div class="agro-section-header agro-mb-6">
            <span class="agro-section-header__eyebrow"><i class="fas fa-boxes-stacked"></i> Direct Harvest</span>
            <h2 class="agro-section-header__title">Produce Listed by <?php echo htmlspecialchars($farmer_name); ?></h2>
        </div>

        <!-- Product Grid -->
        <?php
        $get_pro = "select * from products where farmer_fk = '$farmer_id' order by product_id desc";
        $run_pro = mysqli_query($con, $get_pro);
        $count = $run_pro ? mysqli_num_rows($run_pro) : 0;
        ?>

        <?php if ($count > 0): ?>
            <div class="agro-product-grid">
                <?php
                while ($rows = mysqli_fetch_array($run_pro)) {
                    $product_id = $rows['product_id'];
                    $product_title = $rows['product_title'];
                    $product_image = $rows['product_image'];
                    $product_price = $rows['product_price'];
                    $product_delivery = $rows['product_delivery'];
                    $product_type = $rows['product_type'] ?? 'Produce';

                    $delivery_badge = ($product_delivery == "yes") ? "Delivery Available" : "Pickup Only";
                    $badge_class = ($product_delivery == "yes") ? "agro-badge--green" : "agro-badge--amber";
                    $image_src = !empty($product_image) ? "../Admin/product_images/$product_image" : "../Images/Website/noimage.jpg";
                    ?>
                    <div class="agro-product-card">
                        <div class="agro-product-card__image-wrap">
                            <span class="agro-badge <?php echo $badge_class; ?> agro-product-card__badge"><?php echo $delivery_badge; ?></span>
                            <a href="../BuyerPortal2/ProductDetails.php?id=<?php echo $product_id; ?>">
                                <img class="agro-product-card__image" src="<?php echo $image_src; ?>" alt="<?php echo htmlspecialchars($product_title); ?>" onerror="this.src='../Images/Website/noimage.jpg'">
                            </a>
                        </div>
                        <div class="agro-product-card__body">
                            <div class="agro-product-card__category">🌾 <?php echo htmlspecialchars($product_type); ?></div>
                            <h3 class="agro-product-card__name"><?php echo htmlspecialchars($product_title); ?></h3>
                            <div class="agro-product-card__footer agro-mt-4">
                                <div class="agro-product-card__price">
                                    ₹<?php echo htmlspecialchars($product_price); ?> <span class="agro-product-card__price-unit">/ kg</span>
                                </div>
                                <a href="BuyerPageFarmerProfile.php?farmer_id=<?php echo $farmer_id; ?>&add_cart=<?php echo $product_id; ?>" class="agro-btn agro-btn--secondary agro-btn--sm">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php else: ?>
            <div class="agro-empty">
                <div class="agro-empty__icon">🌾</div>
                <h3 class="agro-empty__title">No Produce Currently Listed</h3>
                <p class="agro-empty__desc">This farmer does not have any active crop listings at the moment.</p>
                <a href="farmers.php" class="agro-btn agro-btn--primary agro-btn--lg">Explore Other Farmers</a>
            </div>
        <?php endif; ?>

    </div>

    <!-- Shared Footer -->
    <?php agro_footer('buyer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

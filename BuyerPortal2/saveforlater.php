<?php
include("../Functions/functions.php");
include("../Includes/components/navbar.php");
include("../Includes/components/footer.php");

$save_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($save_id > 0 && isset($_SESSION['phonenumber'])) {
    if (!isset($_SESSION['saved_items'])) {
        $_SESSION['saved_items'] = array();
    }
    if (!in_array($save_id, $_SESSION['saved_items'])) {
        array_push($_SESSION['saved_items'], $save_id);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Items — AgroNGO</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- Shared Navbar -->
    <?php agro_navbar('buyer', 'saved'); ?>

    <!-- Page Header -->
    <div class="agro-page-header" style="background: var(--surface-primary); border-bottom: 1px solid var(--gray-200);">
        <div class="agro-container">
            <div class="agro-page-header__breadcrumb">
                <a href="bhome.php">Home</a> / <span>Saved Items</span>
            </div>
            <h1 class="agro-page-header__title">Saved Harvests</h1>
            <p class="agro-page-header__desc">Your saved produce listings to purchase or monitor later.</p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="agro-container agro-section">
        <?php cart(); ?>

        <?php
        $saved_ids = $_SESSION['saved_items'] ?? array();
        $count = count($saved_ids);
        ?>

        <?php if ($count > 0): ?>
            <div class="agro-product-grid">
                <?php
                foreach ($saved_ids as $pid) {
                    $pid = intval($pid);
                    $get_pro = "select * from products where product_id = '$pid'";
                    $run_pro = mysqli_query($con, $get_pro);
                    if ($run_pro && $rows = mysqli_fetch_array($run_pro)) {
                        $product_id = $rows['product_id'];
                        $product_title = $rows['product_title'];
                        $product_image = $rows['product_image'];
                        $product_price = $rows['product_price'];
                        $product_delivery = $rows['product_delivery'];
                        $farmer_fk = $rows['farmer_fk'];

                        $name = "Local Farmer";
                        $farmer_name_query = "select farmer_name from farmerregistration where farmer_id = '$farmer_fk'";
                        $running_query_name = mysqli_query($con, $farmer_name_query);
                        if ($running_query_name && $farmer_row = mysqli_fetch_array($running_query_name)) {
                            $name = $farmer_row['farmer_name'];
                        }

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
                                <div class="agro-product-card__category">👨‍🌾 <?php echo htmlspecialchars($name); ?></div>
                                <h3 class="agro-product-card__name"><?php echo htmlspecialchars($product_title); ?></h3>
                                <div class="agro-product-card__footer agro-mt-4">
                                    <div class="agro-product-card__price">
                                        ₹<?php echo htmlspecialchars($product_price); ?> <span class="agro-product-card__price-unit">/ kg</span>
                                    </div>
                                    <a href="saveforlater.php?add_cart=<?php echo $product_id; ?>" class="agro-btn agro-btn--secondary agro-btn--sm">
                                        <i class="fas fa-cart-plus"></i> Move to Cart
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php }
                } ?>
            </div>
        <?php else: ?>
            <div class="agro-empty">
                <div class="agro-empty__icon">💚</div>
                <h3 class="agro-empty__title">No Saved Produce Yet</h3>
                <p class="agro-empty__desc">Save your favorite crops while browsing to view them here anytime.</p>
                <a href="bhome.php" class="agro-btn agro-btn--primary agro-btn--lg">Browse Fresh Produce</a>
            </div>
        <?php endif; ?>

    </div>

    <!-- Shared Footer -->
    <?php agro_footer('buyer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

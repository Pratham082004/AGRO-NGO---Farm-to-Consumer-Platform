<?php
include("../Functions/functions.php");
include("../Includes/components/navbar.php");
include("../Includes/components/footer.php");

$type = isset($_GET['type']) ? mysqli_real_escape_string($con, $_GET['type']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produce Categories <?php echo $type ? '— ' . htmlspecialchars($type) : ''; ?> — AgroNGO</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- Shared Navbar -->
    <?php agro_navbar('buyer', 'categories'); ?>

    <!-- Page Header -->
    <div class="agro-page-header" style="background: var(--surface-primary); border-bottom: 1px solid var(--gray-200);">
        <div class="agro-container">
            <div class="agro-page-header__breadcrumb">
                <a href="bhome.php">Home</a> / <span>Categories</span>
            </div>
            <h1 class="agro-page-header__title">
                <?php echo !empty($type) ? htmlspecialchars($type) : 'All Produce Categories'; ?>
            </h1>
            <p class="agro-page-header__desc">Browse farm-fresh fruits, vegetables, and crops directly from local growers.</p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="agro-container agro-section">
        <?php cart(); ?>

        <!-- Category Nav Pills -->
        <div class="agro-pills agro-mb-8 agro-flex-center">
            <a href="Categories.php" class="agro-pill <?php echo empty($type) ? 'active' : ''; ?>">All Produce</a>
            <a href="Categories.php?type=Banana" class="agro-pill <?php echo $type === 'Banana' ? 'active' : ''; ?>">🍌 Bananas</a>
            <a href="Categories.php?type=Mango" class="agro-pill <?php echo $type === 'Mango' ? 'active' : ''; ?>">🥭 Mangoes</a>
            <a href="Categories.php?type=Apple" class="agro-pill <?php echo $type === 'Apple' ? 'active' : ''; ?>">🍎 Apples</a>
            <a href="Categories.php?type=Tomato" class="agro-pill <?php echo $type === 'Tomato' ? 'active' : ''; ?>">🍅 Tomatoes</a>
            <a href="Categories.php?type=Potato" class="agro-pill <?php echo $type === 'Potato' ? 'active' : ''; ?>">🥔 Potatoes</a>
            <a href="Categories.php?type=Onion" class="agro-pill <?php echo $type === 'Onion' ? 'active' : ''; ?>">🧅 Onions</a>
            <a href="Categories.php?type=Wheat" class="agro-pill <?php echo $type === 'Wheat' ? 'active' : ''; ?>">🌾 Wheat</a>
            <a href="Categories.php?type=Rice" class="agro-pill <?php echo $type === 'Rice' ? 'active' : ''; ?>">🌾 Rice</a>
        </div>

        <?php
        if (!empty($type)) {
            $get_pro = "select * from products where product_type = '$type'";
        } else {
            $get_pro = "select * from products order by product_id desc";
        }
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
                                <a href="Categories.php?<?php echo !empty($type) ? 'type=' . urlencode($type) . '&' : ''; ?>add_cart=<?php echo $product_id; ?>" class="agro-btn agro-btn--secondary agro-btn--sm">
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
                <h3 class="agro-empty__title">No Produce Available</h3>
                <p class="agro-empty__desc">No items currently listed in this category. Check back soon for fresh harvests!</p>
                <a href="bhome.php" class="agro-btn agro-btn--primary agro-btn--lg">Back to Homepage</a>
            </div>
        <?php endif; ?>

    </div>

    <!-- Shared Footer -->
    <?php agro_footer('buyer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

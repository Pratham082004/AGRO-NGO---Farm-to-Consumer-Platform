<?php
include("../Functions/functions.php");
include("../Includes/components/navbar.php");
include("../Includes/components/footer.php");

$search_query = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results: <?php echo htmlspecialchars($search_query); ?> — AgroNGO</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- Shared Navbar -->
    <?php agro_navbar('buyer', 'search'); ?>

    <!-- Page Header -->
    <div class="agro-page-header" style="background: var(--surface-primary); border-bottom: 1px solid var(--gray-200);">
        <div class="agro-container">
            <div class="agro-page-header__breadcrumb">
                <a href="bhome.php">Home</a> / <span>Search</span>
            </div>
            <h1 class="agro-page-header__title">Search Results</h1>
            <p class="agro-page-header__desc">
                <?php echo !empty($search_query) ? 'Showing results for "' . htmlspecialchars($search_query) . '"' : 'Browse produce from local farmers'; ?>
            </p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="agro-container agro-section">
        <?php
        cart();
        
        $get_pro = "select * from products where product_keywords like '%$search_query%' or product_title like '%$search_query%' or product_type like '%$search_query%'";
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
                                <a href="SearchResult.php?search=<?php echo urlencode($search_query); ?>&add_cart=<?php echo $product_id; ?>" class="agro-btn agro-btn--secondary agro-btn--sm">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php else: ?>
            <div class="agro-empty">
                <div class="agro-empty__icon">🔍</div>
                <h3 class="agro-empty__title">No Produce Found</h3>
                <p class="agro-empty__desc">No fresh produce matched your search query "<strong><?php echo htmlspecialchars($search_query); ?></strong>". Try searching for another item or category.</p>
                <a href="bhome.php" class="agro-btn agro-btn--primary agro-btn--lg">Back to Homepage</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Shared Footer -->
    <?php agro_footer('buyer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

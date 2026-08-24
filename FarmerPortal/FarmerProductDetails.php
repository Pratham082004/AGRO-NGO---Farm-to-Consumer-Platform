<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../Functions/functions.php");
include("../Includes/db.php");
include("../Includes/components/navbar.php");
include("../Includes/components/footer.php");
require_once __DIR__ . '/../Includes/OllamaAdvisor.php';

$sess_phone_number = $_SESSION['phonenumber'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Product Details — AgroNGO</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- Shared Navbar -->
    <?php agro_navbar('farmer', 'products'); ?>

    <?php
    if (isset($_GET['id'])) {
        global $con;
        $prod_id = intval($_GET['id']);
        $query = "SELECT * FROM products WHERE product_id = $prod_id";
        $run_query = mysqli_query($con, $query);

        if ($run_query && mysqli_num_rows($run_query) > 0) {
            while ($rows = mysqli_fetch_array($run_query)) {
                $product_title = htmlspecialchars($rows['product_title']);
                $product_image = htmlspecialchars($rows['product_image']);
                $product_type = htmlspecialchars($rows['product_type']);
                $product_stock = htmlspecialchars($rows['product_stock']);
                $product_description = htmlspecialchars($rows['product_desc']);
                $product_price = htmlspecialchars($rows['product_price']);
                $product_delivery = $rows['product_delivery'];
                $product_cat = $rows['product_cat'];
                $product_expiry = $rows['product_expiry'] ?? null;
                $storage_condition = htmlspecialchars($rows['storage_condition'] ?? 'Ambient');
                $is_processed = $rows['is_processed'] ?? 0;

                $stock_status = ($product_stock > 0) ? "In Stock ($product_stock kg)" : "Out of Stock";
                $stock_badge_class = ($product_stock > 0) ? "agro-badge--green" : "agro-badge--red";
                $delivery_status = ($product_delivery == "yes") ? "Delivery Provided by Farmer" : "Buyer Self-Pickup";

                // Expiry calculation
                $expiryDays = 3;
                if (!empty($product_expiry)) {
                    if (is_numeric($product_expiry)) {
                        $expiryDays = intval($product_expiry);
                    } else {
                        $expTime = strtotime($product_expiry);
                        if ($expTime) {
                            $diff = ceil(($expTime - time()) / 86400);
                            $expiryDays = max(1, $diff);
                        }
                    }
                }

                // AI Recommendations
                $aiRec = OllamaAdvisor::getRecommendations([
                    'item' => $product_title,
                    'cat' => $product_cat,
                    'expiry' => $expiryDays,
                    'stock' => $product_stock,
                    'price' => $product_price
                ]);
                ?>

                <!-- Page Header -->
                <div class="agro-page-header" style="background: var(--surface-primary); border-bottom: 1px solid var(--gray-200);">
                    <div class="agro-container">
                        <div class="agro-page-header__breadcrumb">
                            <a href="farmerHomepage.php">Home</a> / <a href="MyProducts.php">My Products</a> / <span><?php echo $product_title; ?></span>
                        </div>
                        <h1 class="agro-page-header__title"><?php echo $product_title; ?></h1>
                        <div class="agro-flex" style="gap: var(--space-3); margin-top: var(--space-2); flex-wrap: wrap;">
                            <span class="agro-badge agro-badge--green"><i class="fas fa-seedling"></i> <?php echo $product_type; ?></span>
                            <span class="agro-badge <?php echo $stock_badge_class; ?>"><i class="fas fa-boxes-stacked"></i> <?php echo $stock_status; ?></span>
                            <span class="agro-badge agro-badge--blue"><i class="fas fa-snowflake"></i> <?php echo $storage_condition; ?></span>
                            <?php if ($is_processed): ?>
                                <span class="agro-badge agro-badge--amber"><i class="fas fa-industry"></i> Processed</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Main Content Section -->
                <div class="agro-container agro-section">
                    <div class="agro-grid" style="grid-template-columns: 1fr 1fr 1.2fr; gap: var(--space-8); align-items: start;">
                        
                        <!-- Col 1: Image -->
                        <div class="agro-card agro-p-4">
                            <div class="agro-product-card__image-wrap" style="height: 320px; border-radius: var(--radius-xl); overflow: hidden;">
                                <img src="../Admin/product_images/<?php echo $product_image; ?>" alt="<?php echo $product_title; ?>" class="agro-product-card__image" onerror="this.src='../Images/Website/noimage.jpg'">
                            </div>
                        </div>

                        <!-- Col 2: Price & Actions -->
                        <div class="agro-card agro-p-6">
                            <div class="agro-mb-6">
                                <div style="font-size: var(--text-xs); color: var(--text-tertiary); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Listed Price</div>
                                <div style="font-family: var(--font-display); font-size: var(--text-4xl); font-weight: 800; color: var(--color-primary);">₹<?php echo $product_price; ?> <span style="font-size: var(--text-sm); color: var(--text-tertiary); font-weight: 400;">/ kg</span></div>
                            </div>

                            <div class="agro-stat-card agro-mb-4" style="padding: var(--space-4);">
                                <div class="agro-label"><i class="fas fa-boxes-packing" style="margin-right:6px; color:var(--color-primary);"></i>Inventory Level</div>
                                <div style="font-size: var(--text-lg); font-weight: 700; color: var(--text-primary);"><?php echo $product_stock; ?> kgs available</div>
                            </div>

                            <div class="agro-stat-card agro-mb-6" style="padding: var(--space-4);">
                                <div class="agro-label"><i class="fas fa-truck-fast" style="margin-right:6px; color:var(--color-primary);"></i>Fulfillment Option</div>
                                <div style="font-size: var(--text-sm); font-weight: 600; color: var(--text-primary);"><?php echo $delivery_status; ?></div>
                            </div>

                            <div class="agro-flex" style="gap: var(--space-3); flex-direction: column;">
                                <a href="EditProduct.php?id=<?php echo $prod_id; ?>" class="agro-btn agro-btn--primary agro-btn--full agro-btn--lg">
                                    <i class="fas fa-pen-to-square"></i> Edit Listing Details
                                </a>
                                <a href="Transactions.php" class="agro-btn agro-btn--outline agro-btn--full">
                                    <i class="fas fa-receipt"></i> View Related Sales
                                </a>
                            </div>
                        </div>

                        <!-- Col 3: AI Advisory Widget -->
                        <?php if ($aiRec && $aiRec['status'] === 'success'): ?>
                            <?php
                            $urgencyColor = $aiRec['urgency_color'] ?? 'warning';
                            $urgencyBadge = ($urgencyColor == 'danger') ? 'agro-badge--red' : (($urgencyColor == 'warning') ? 'agro-badge--amber' : 'agro-badge--green');
                            ?>
                            <div class="agro-card agro-p-6" style="background: linear-gradient(135deg, #1f2937, #111827); color: white;">
                                <div class="agro-flex-between agro-mb-4" style="flex-wrap: wrap; gap: 8px;">
                                    <div style="font-size: var(--text-sm); font-weight: 700; color: var(--agro-green-400); display: flex; align-items: center; gap: 8px;">
                                        <i class="fas fa-robot"></i> Ollama AI Buyer Advisory
                                    </div>
                                    <span class="agro-badge <?php echo $urgencyBadge; ?>">
                                        <?php echo $aiRec['urgency_level']; ?> (<?php echo $aiRec['expiry_days']; ?> DAYS LEFT)
                                    </span>
                                </div>

                                <div style="font-size: var(--text-xs); color: var(--gray-400); margin-bottom: 4px;">Recommended Clearance Price</div>
                                <div style="font-size: var(--text-2xl); font-weight: 800; color: var(--agro-green-400); font-family: var(--font-display); margin-bottom: var(--space-4);">
                                    ₹<?php echo $aiRec['recommended_clearance_price']; ?> <span style="font-size: var(--text-sm); font-weight: 400; color: var(--agro-amber-400);">(<?php echo $aiRec['discount_percent']; ?>% off)</span>
                                </div>

                                <div style="background: rgba(255,255,255,0.06); padding: var(--space-4); border-radius: var(--radius-lg); border-left: 4px solid var(--agro-green-500); margin-bottom: var(--space-4);">
                                    <div style="font-size: var(--text-xs); color: var(--agro-green-400); font-weight: 700; margin-bottom: 4px;">Actionable Sales Pitch:</div>
                                    <p style="font-size: var(--text-xs); line-height: 1.5; color: #e5e7eb; margin: 0;"><?php echo htmlspecialchars($aiRec['actionable_pitch']); ?></p>
                                </div>

                                <div style="margin-bottom: var(--space-4);">
                                    <div style="font-size: var(--text-xs); font-weight: 700; color: var(--agro-amber-400); margin-bottom: 8px;">Matched Buyer Channels:</div>
                                    <div style="display: flex; flex-direction: column; gap: 6px;">
                                        <?php foreach ($aiRec['target_buyers'] as $tb): ?>
                                            <div style="background: rgba(0,0,0,0.3); padding: 8px 12px; border-radius: var(--radius-md); display: flex; justify-content: space-between; align-items: center; font-size: var(--text-xs);">
                                                <span><i class="fas fa-store text-success" style="margin-right: 6px;"></i> <?php echo htmlspecialchars($tb['name']); ?></span>
                                                <span style="font-weight: 700; color: var(--agro-green-400);"><?php echo $tb['fit_score']; ?>% Fit</span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <a href="SmartAdvisory.php" class="agro-btn agro-btn--secondary agro-btn--full agro-btn--sm">
                                    <i class="fas fa-wand-magic-sparkles"></i> Open Full AI Simulator
                                </a>
                            </div>
                        <?php endif; ?>

                    </div>

                    <!-- Description Card -->
                    <div class="agro-card agro-p-8 agro-mt-8">
                        <h3 style="margin-bottom: var(--space-4);"><i class="fas fa-circle-info" style="color:var(--color-primary); margin-right:8px;"></i>Produce Description</h3>
                        <p style="font-size: var(--text-base); line-height: var(--leading-relaxed); color: var(--text-secondary); margin: 0;"><?php echo nl2br($product_description); ?></p>
                    </div>
                </div>

                <?php
            }
        } else {
            echo "<div class='agro-container agro-section'><div class='agro-empty'><div class='agro-empty__icon'>🔍</div><h3 class='agro-empty__title'>Product Not Found</h3><p class='agro-empty__desc'>The requested produce item could not be found or has been removed.</p><a href='MyProducts.php' class='agro-btn agro-btn--primary'>Back to My Products</a></div></div>";
        }
    } else {
        echo "<div class='agro-container agro-section'><div class='agro-empty'><div class='agro-empty__icon'>⚠️</div><h3 class='agro-empty__title'>No Item Specified</h3><p class='agro-empty__desc'>Please select a product from your listings.</p><a href='MyProducts.php' class='agro-btn agro-btn--primary'>Back to My Products</a></div></div>";
    }
    ?>

    <!-- Shared Footer -->
    <?php agro_footer('farmer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

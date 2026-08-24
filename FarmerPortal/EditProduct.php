<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../Includes/db.php");
include("../Includes/components/navbar.php");
include("../Includes/components/footer.php");

$sessphonenumber = $_SESSION['phonenumber'] ?? null;

$product_title = "";
$product_cat = "";
$product_type = "";
$product_stock = "";
$product_price = "";
$product_expiry = "";
$product_desc = "";
$product_keywords = "";
$product_delivery = "yes";
$storage_condition = "Ambient";
$is_processed = 0;
$product_image = "";
$id = 0;

if ($sessphonenumber && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $getting_prod = "SELECT * FROM products WHERE product_id = $id";
    $run = mysqli_query($con, $getting_prod);

    if ($details = mysqli_fetch_assoc($run)) {
        $product_title = htmlspecialchars($details['product_title']);
        $product_cat = $details['product_cat'];
        $product_type = htmlspecialchars($details['product_type']);
        $product_stock = htmlspecialchars($details['product_stock']);
        $product_price = htmlspecialchars($details['product_price']);
        $product_expiry = $details['product_expiry'];
        $product_desc = htmlspecialchars($details['product_desc']);
        $product_keywords = htmlspecialchars($details['product_keywords']);
        $product_delivery = $details['product_delivery'];
        $storage_condition = $details['storage_condition'] ?? 'Ambient';
        $is_processed = $details['is_processed'] ?? 0;
        $product_image = $details['product_image'] ?? '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product — Farmer Portal</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- Shared Navbar -->
    <?php agro_navbar('farmer', 'products'); ?>

    <!-- Page Header -->
    <div class="agro-page-header" style="background: var(--surface-primary); border-bottom: 1px solid var(--gray-200);">
        <div class="agro-container">
            <div class="agro-page-header__breadcrumb">
                <a href="farmerHomepage.php">Home</a> / <a href="MyProducts.php">Products</a> / <span>Edit Listing</span>
            </div>
            <h1 class="agro-page-header__title">Edit Produce Listing</h1>
            <p class="agro-page-header__desc">Update pricing, inventory stock, storage details, and produce attributes.</p>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="agro-container agro-section">
        <div style="max-width: 760px; margin: 0 auto;">
            
            <?php if ($sessphonenumber && $id > 0): ?>
                <div class="agro-card agro-p-8">
                    <form name="my-form" action="UpdateProduct.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                        
                        <div class="agro-grid agro-grid-2" style="gap: var(--space-4);">
                            <div class="agro-form-group">
                                <label class="agro-label" for="product_title">
                                    <i class="fas fa-tag" style="margin-right:6px; color:var(--color-primary);"></i>Product Title
                                </label>
                                <input type="text" id="product_title" class="agro-input" name="product_title" value="<?php echo $product_title; ?>" required>
                            </div>

                            <div class="agro-form-group">
                                <label class="agro-label" for="product_stock">
                                    <i class="fas fa-weight-hanging" style="margin-right:6px; color:var(--color-primary);"></i>Stock (in kg)
                                </label>
                                <input type="number" id="product_stock" class="agro-input" name="product_stock" value="<?php echo $product_stock; ?>" min="0" step="any" required>
                            </div>
                        </div>

                        <div class="agro-grid agro-grid-2" style="gap: var(--space-4);">
                            <div class="agro-form-group">
                                <label class="agro-label" for="product_cat">
                                    <i class="fas fa-th-large" style="margin-right:6px; color:var(--color-primary);"></i>Category
                                </label>
                                <select name="product_cat" id="product_cat" class="agro-select" required>
                                    <option value="">Select Category</option>
                                    <?php
                                    $get_cats = "SELECT * FROM categories";
                                    $run_cats = mysqli_query($con, $get_cats);
                                    while ($row_cats = mysqli_fetch_array($run_cats)) {
                                        $cat_id = $row_cats['cat_id'];
                                        $cat_title = $row_cats['cat_title'];
                                        $selected = ($cat_id == $product_cat) ? "selected" : "";
                                        echo "<option value='$cat_id' $selected>$cat_title</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="agro-form-group">
                                <label class="agro-label" for="product_type">
                                    <i class="fas fa-leaf" style="margin-right:6px; color:var(--color-primary);"></i>Produce Type
                                </label>
                                <input type="text" id="product_type" class="agro-input" name="product_type" value="<?php echo $product_type; ?>" placeholder="e.g. Potato, Tomato, Mango" required>
                            </div>
                        </div>

                        <div class="agro-grid agro-grid-2" style="gap: var(--space-4);">
                            <div class="agro-form-group">
                                <label class="agro-label" for="product_expiry">
                                    <i class="fas fa-calendar-alt" style="margin-right:6px; color:var(--color-primary);"></i>Expected Expiry Date
                                </label>
                                <input id="product_expiry" class="agro-input" type="date" name="product_expiry" value="<?php echo $product_expiry; ?>" required>
                            </div>

                            <div class="agro-form-group">
                                <label class="agro-label" for="product_image">
                                    <i class="fas fa-image" style="margin-right:6px; color:var(--color-primary);"></i>Update Photo (Optional)
                                </label>
                                <input id="product_image" class="agro-input" type="file" name="product_image" accept="image/*">
                                <?php if (!empty($product_image)): ?>
                                    <div style="font-size: var(--text-xs); color: var(--text-tertiary); margin-top: 4px;">
                                        Current: <strong><?php echo htmlspecialchars($product_image); ?></strong>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="agro-grid agro-grid-2" style="gap: var(--space-4);">
                            <div class="agro-form-group">
                                <label class="agro-label" for="storage_condition">
                                    <i class="fas fa-snowflake" style="margin-right:6px; color:var(--color-primary);"></i>Storage Condition
                                </label>
                                <select name="storage_condition" id="storage_condition" class="agro-select" required>
                                    <option value="Ambient" <?php echo ($storage_condition === 'Ambient') ? 'selected' : ''; ?>>Ambient / Room Temp</option>
                                    <option value="Cold Storage" <?php echo ($storage_condition === 'Cold Storage') ? 'selected' : ''; ?>>Cold Storage</option>
                                    <option value="Refrigerated" <?php echo ($storage_condition === 'Refrigerated') ? 'selected' : ''; ?>>Refrigerated</option>
                                </select>
                            </div>

                            <div class="agro-form-group">
                                <label class="agro-label" for="is_processed">
                                    <i class="fas fa-industry" style="margin-right:6px; color:var(--color-primary);"></i>Processing State
                                </label>
                                <select name="is_processed" id="is_processed" class="agro-select" required>
                                    <option value="0" <?php echo ($is_processed == 0) ? 'selected' : ''; ?>>Raw Produce (Fresh Harvest)</option>
                                    <option value="1" <?php echo ($is_processed == 1) ? 'selected' : ''; ?>>Processed (Juice, Paste, Dried)</option>
                                </select>
                            </div>
                        </div>

                        <div class="agro-form-group">
                            <label class="agro-label" for="product_price">
                                <i class="fas fa-indian-rupee-sign" style="margin-right:6px; color:var(--color-primary);"></i>Price per kg (₹)
                            </label>
                            <div class="agro-flex" style="gap: var(--space-3);">
                                <input type="number" id="product_price" class="agro-input" name="product_price" value="<?php echo $product_price; ?>" min="0" step="any" required>
                                <button type="button" class="agro-btn agro-btn--secondary" id="suggestPriceBtn">
                                    <i class="fas fa-robot"></i> ML Suggestion
                                </button>
                            </div>
                            <div id="priceSuggestionHelp" style="margin-top: var(--space-2); font-size: var(--text-xs);"></div>
                        </div>

                        <div class="agro-form-group">
                            <label class="agro-label" for="product_desc">
                                <i class="fas fa-align-left" style="margin-right:6px; color:var(--color-primary);"></i>Product Description
                            </label>
                            <textarea name="product_desc" id="product_desc" class="agro-textarea" rows="3" required><?php echo $product_desc; ?></textarea>
                        </div>

                        <div class="agro-grid agro-grid-2" style="gap: var(--space-4);">
                            <div class="agro-form-group">
                                <label class="agro-label" for="product_keywords">
                                    <i class="fas fa-key" style="margin-right:6px; color:var(--color-primary);"></i>Search Keywords
                                </label>
                                <input type="text" id="product_keywords" class="agro-input" name="product_keywords" value="<?php echo $product_keywords; ?>" placeholder="e.g. fresh organic potato" required>
                            </div>

                            <div class="agro-form-group">
                                <label class="agro-label"><i class="fas fa-truck" style="margin-right:6px; color:var(--color-primary);"></i>Delivery Available?</label>
                                <div class="agro-flex" style="gap: var(--space-6); margin-top: var(--space-3);">
                                    <label class="agro-checkbox">
                                        <input type="radio" name="product_delivery" value="yes" <?php echo ($product_delivery === "yes") ? "checked" : ""; ?> /> Yes, I deliver
                                    </label>
                                    <label class="agro-checkbox">
                                        <input type="radio" name="product_delivery" value="no" <?php echo ($product_delivery === "no") ? "checked" : ""; ?> /> Buyer pickups
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="agro-btn agro-btn--primary agro-btn--full agro-btn--lg" name="update_pro" style="margin-top: var(--space-4);">
                            <i class="fas fa-save"></i> Save Changes & Update Listing
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="agro-empty">
                    <div class="agro-empty__icon">🔒</div>
                    <h3 class="agro-empty__title">Authentication / Item Required</h3>
                    <p class="agro-empty__desc">Please log in to your farmer account and specify a valid product to edit.</p>
                    <a href="../auth/FarmerLogin.php" class="agro-btn agro-btn--primary agro-btn--lg">Farmer Login</a>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- Shared Footer -->
    <?php agro_footer('farmer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
    <script>
    document.getElementById('suggestPriceBtn')?.addEventListener('click', function() {
        const stock = document.getElementById('product_stock').value || 500;
        const cat = document.getElementById('product_cat').value || 2;
        const storage = document.getElementById('storage_condition').value;
        const processed = document.getElementById('is_processed').value;
        
        let basePrice = 30;
        if (cat == '3') basePrice += 45;
        if (cat == '2') basePrice += 20;
        if (processed == '1') basePrice += 35;
        if (storage == 'Cold Storage' || storage == 'Refrigerated') basePrice += 15;
        
        const minP = Math.max(10, basePrice - 5);
        const maxP = basePrice + 10;
        
        document.getElementById('product_price').value = basePrice;
        document.getElementById('priceSuggestionHelp').innerHTML = 
            `<span class="agro-badge agro-badge--green"><i class="fas fa-robot"></i> ML Recommendation</span> Suggested Price: <strong>₹${basePrice}</strong> / kg (Range: ₹${minP} – ₹${maxP})`;
    });
    </script>
</body>
</html>

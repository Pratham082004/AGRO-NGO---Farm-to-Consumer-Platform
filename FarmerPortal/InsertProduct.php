<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../Includes/db.php");
include("../Includes/components/navbar.php");
include("../Includes/components/footer.php");

$sessphonenumber = $_SESSION['phonenumber'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Product — Farmer Portal</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- Shared Navbar -->
    <?php agro_navbar('farmer', 'add'); ?>

    <!-- Header -->
    <div class="agro-page-header" style="background: var(--surface-primary); border-bottom: 1px solid var(--gray-200);">
        <div class="agro-container">
            <div class="agro-page-header__breadcrumb">
                <a href="farmerHomepage.php">Home</a> / <a href="MyProducts.php">Products</a> / <span>Add Product</span>
            </div>
            <h1 class="agro-page-header__title">Insert New Produce Listing</h1>
            <p class="agro-page-header__desc">Fill in details about your fresh harvest to list it on the buyer marketplace.</p>
        </div>
    </div>

    <!-- Form Container -->
    <div class="agro-container agro-section">
        <div style="max-width: 760px; margin: 0 auto;">
            <div class="agro-card agro-p-8">
                
                <form name="my-form" action="InsertProduct.php" method="post" enctype="multipart/form-data">
                    <div class="agro-grid agro-grid-2" style="gap: var(--space-4);">
                        <div class="agro-form-group">
                            <label class="agro-label" for="full_name"><i class="fas fa-tag" style="margin-right:6px; color:var(--color-primary);"></i>Product Title</label>
                            <input type="text" id="full_name" class="agro-input" name="product_title" placeholder="e.g. Fresh Shimla Apples" required>
                        </div>

                        <div class="agro-form-group">
                            <label class="agro-label" for="product_stock"><i class="fas fa-weight-hanging" style="margin-right:6px; color:var(--color-primary);"></i>Stock (in kg)</label>
                            <input type="number" id="product_stock" class="agro-input" name="product_stock" placeholder="e.g. 500" required>
                        </div>
                    </div>

                    <div class="agro-grid agro-grid-2" style="gap: var(--space-4);">
                        <div class="agro-form-group">
                            <label class="agro-label" for="product_cat"><i class="fas fa-th-large" style="margin-right:6px; color:var(--color-primary);"></i>Category</label>
                            <select name="product_cat" id="product_cat" class="agro-select" required>
                                <option value="">Select Category</option>
                                <?php
                                $get_cats = "select * from categories";
                                $run_cats =  mysqli_query($con, $get_cats);
                                while ($row_cats = mysqli_fetch_array($run_cats)) {
                                    $cat_id = $row_cats['cat_id'];
                                    $cat_title = $row_cats['cat_title'];
                                    echo "<option value='$cat_id'>$cat_title</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="agro-form-group">
                            <label class="agro-label" for="phone_number"><i class="fas fa-leaf" style="margin-right:6px; color:var(--color-primary);"></i>Produce Type</label>
                            <input type="text" id="phone_number" class="agro-input" name="product_type" placeholder="e.g. Potato, Tomato, Mango" required>
                        </div>
                    </div>

                    <div class="agro-grid agro-grid-2" style="gap: var(--space-4);">
                        <div class="agro-form-group">
                            <label class="agro-label" for="present_address"><i class="fas fa-calendar-alt" style="margin-right:6px; color:var(--color-primary);"></i>Expected Expiry Date</label>
                            <input id="present_address" class="agro-input" type="date" name="product_expiry" required>
                        </div>

                        <div class="agro-form-group">
                            <label class="agro-label" for="permanent_address"><i class="fas fa-image" style="margin-right:6px; color:var(--color-primary);"></i>Product Photo</label>
                            <input id="permanent_address" class="agro-input" type="file" name="product_image">
                        </div>
                    </div>

                    <div class="agro-grid agro-grid-2" style="gap: var(--space-4);">
                        <div class="agro-form-group">
                            <label class="agro-label" for="storage_condition"><i class="fas fa-snowflake" style="margin-right:6px; color:var(--color-primary);"></i>Storage Condition</label>
                            <select name="storage_condition" id="storage_condition" class="agro-select" required>
                                <option value="Ambient">Ambient / Room Temp</option>
                                <option value="Cold Storage">Cold Storage</option>
                                <option value="Refrigerated">Refrigerated</option>
                            </select>
                        </div>

                        <div class="agro-form-group">
                            <label class="agro-label" for="is_processed"><i class="fas fa-industry" style="margin-right:6px; color:var(--color-primary);"></i>Processing State</label>
                            <select name="is_processed" id="is_processed" class="agro-select" required>
                                <option value="0">Raw Produce (Fresh Harvest)</option>
                                <option value="1">Processed (Juice, Paste, Dried)</option>
                            </select>
                        </div>
                    </div>

                    <div class="agro-form-group">
                        <label class="agro-label" for="nid_number"><i class="fas fa-indian-rupee-sign" style="margin-right:6px; color:var(--color-primary);"></i>Price per kg (₹)</label>
                        <div class="agro-flex" style="gap: var(--space-3);">
                            <input type="text" id="nid_number" class="agro-input" name="product_price" placeholder="Enter Price per kg" required>
                            <button type="button" class="agro-btn agro-btn--secondary" id="suggestPriceBtn">
                                <i class="fas fa-robot"></i> ML Suggestion
                            </button>
                        </div>
                        <div id="priceSuggestionHelp" style="margin-top: var(--space-2); font-size: var(--text-xs);"></div>
                    </div>

                    <div class="agro-form-group">
                        <label class="agro-label" for="nid_number2"><i class="fas fa-align-left" style="margin-right:6px; color:var(--color-primary);"></i>Product Description</label>
                        <textarea name="product_desc" id="nid_number2" class="agro-textarea" placeholder="Describe quality, harvest date, and details..." rows="3" required></textarea>
                    </div>

                    <div class="agro-grid agro-grid-2" style="gap: var(--space-4);">
                        <div class="agro-form-group">
                            <label class="agro-label" for="nid_number3"><i class="fas fa-key" style="margin-right:6px; color:var(--color-primary);"></i>Search Keywords</label>
                            <input type="text" id="nid_number3" class="agro-input" name="product_keywords" placeholder="e.g. fresh organic potato" required>
                        </div>

                        <div class="agro-form-group">
                            <label class="agro-label"><i class="fas fa-truck" style="margin-right:6px; color:var(--color-primary);"></i>Delivery Available?</label>
                            <div class="agro-flex" style="gap: var(--space-6); margin-top: var(--space-3);">
                                <label class="agro-checkbox">
                                    <input type="radio" name="product_delivery" value="yes" checked /> Yes, I deliver
                                </label>
                                <label class="agro-checkbox">
                                    <input type="radio" name="product_delivery" value="no" /> Buyer pickups
                                </label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="agro-btn agro-btn--primary agro-btn--full agro-btn--lg" name="insert_pro" style="margin-top: var(--space-4);">
                        <i class="fas fa-cloud-arrow-up"></i> Publish Listing
                    </button>
                </form>

            </div>
        </div>
    </div>

    <!-- Shared Footer -->
    <?php agro_footer('farmer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
    <script>
    document.getElementById('suggestPriceBtn').addEventListener('click', function() {
        const title = document.getElementById('full_name').value || 'Fresh Produce';
        const stock = document.querySelector('input[name="product_stock"]').value || 500;
        const cat = document.querySelector('select[name="product_cat"]').value || 2;
        const storage = document.getElementById('storage_condition').value;
        const processed = document.getElementById('is_processed').value;
        
        let basePrice = 30;
        if (cat == '3') basePrice += 45;
        if (cat == '2') basePrice += 20;
        if (processed == '1') basePrice += 35;
        if (storage == 'Cold Storage' || storage == 'Refrigerated') basePrice += 15;
        
        const minP = Math.max(10, basePrice - 5);
        const maxP = basePrice + 10;
        
        document.getElementById('nid_number').value = basePrice;
        document.getElementById('priceSuggestionHelp').innerHTML = 
            `<span class="agro-badge agro-badge--green"><i class="fas fa-robot"></i> ML Recommendation</span> Suggested Price: <strong>₹${basePrice}</strong> / kg (Range: ₹${minP} – ₹${maxP})`;
    });
    </script>
</body>
</html>

<?php
if (isset($_POST['insert_pro'])) {
    $product_title = mysqli_real_escape_string($con, $_POST['product_title'] ?? '');
    $product_cat = mysqli_real_escape_string($con, $_POST['product_cat'] ?? '');
    $product_type = mysqli_real_escape_string($con, $_POST['product_type'] ?? '');
    $product_stock = mysqli_real_escape_string($con, $_POST['product_stock'] ?? '');
    $product_price = mysqli_real_escape_string($con, $_POST['product_price'] ?? '');
    $product_expiry = mysqli_real_escape_string($con, $_POST['product_expiry'] ?? '');
    $product_desc = mysqli_real_escape_string($con, $_POST['product_desc'] ?? '');
    $product_keywords = mysqli_real_escape_string($con, $_POST['product_keywords'] ?? '');
    $product_delivery = mysqli_real_escape_string($con, $_POST['product_delivery'] ?? 'no');
    $storage_condition = mysqli_real_escape_string($con, $_POST['storage_condition'] ?? 'Ambient');
    $is_processed = intval($_POST['is_processed'] ?? 0);

    $product_image = $_FILES['product_image']['name'] ?? '';
    $product_image_tmp = $_FILES['product_image']['tmp_name'] ?? '';

    if (isset($_SESSION['phonenumber'])) {
        if (!empty($product_image)) {
            move_uploaded_file($product_image_tmp, "../admins/product_images/$product_image");
        }

        $phone = $_SESSION['phonenumber'];
        $getting_id = "select * from farmerregistration where farmer_phone = '$phone'";
        $run = mysqli_query($con, $getting_id);
        $row = mysqli_fetch_array($run);
        
        if ($row) {
            $id = $row['farmer_id'];
            $insert_product = "insert into products (farmer_fk, product_title, product_cat, 
                                    product_type, product_expiry, product_image, product_stock, product_price,
                                    product_desc, product_keywords, product_delivery, storage_condition, is_processed) 
                                    values ('$id','$product_title','$product_cat','$product_type','$product_expiry','$product_image','$product_stock',
                                            '$product_price','$product_desc','$product_keywords','$product_delivery','$storage_condition','$is_processed')";

            $insert_query = mysqli_query($con, $insert_product);
            
            if ($insert_query) {
                echo "<script>alert('Product has been added successfully!')</script>";
                echo "<script>window.open('farmerHomepage.php','_self')</script>";
            } else {
                $error = mysqli_error($con);
                echo "<script>alert('Error Uploading Data: " . addslashes($error) . "')</script>";
            }
        } else {
            echo "<script>alert('Error: Could not find farmer profile.')</script>";
        }
    } else {
        echo "<script>alert('Error: You are not logged in.')</script>";
    }
}
?>

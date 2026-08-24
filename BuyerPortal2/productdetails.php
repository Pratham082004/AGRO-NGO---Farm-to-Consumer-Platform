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
    <title>Product Details — AgroNGO</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- Shared Navbar -->
    <?php agro_navbar('buyer', 'categories'); ?>

    <?php
    if (isset($_GET['id'])) {
        global $con;
        $product_id  = intval($_GET['id']);
        $query = "select * from products where product_id = $product_id";
        $run_query = mysqli_query($con, $query);

        while ($rows = mysqli_fetch_array($run_query)) {
            $farmer_fk = $rows['farmer_fk'];
            $product_title = $rows['product_title'];
            $product_image = $rows['product_image'];
            $product_price = $rows['product_price'];
            $product_stock = $rows['product_stock'];
            $product_type = $rows['product_type'];
            $product_delivery = $rows['product_delivery'];
            $product_desc = $rows['product_desc'];
            $storage_condition = $rows['storage_condition'] ?? 'Ambient';
            $is_processed = $rows['is_processed'] ?? 0;

            $delivery_text = ($product_delivery == "yes") ? "Delivery by Farmer Available" : "Self-Pickup / Courier";

            $querya = "select * from farmerregistration where farmer_id = '$farmer_fk'";
            $runa_query = mysqli_query($con, $querya);

            while ($rows_f = mysqli_fetch_array($runa_query)) {
                $farmer_name = $rows_f['farmer_name'];
                $farmer_phone = $rows_f['farmer_phone'];
                $farmer_address = $rows_f['farmer_address'];
                $farmer_state = $rows_f['farmer_state'];
                $farmer_district = $rows_f['farmer_district'];
                ?>
                
                <!-- Page Header -->
                <div class="agro-page-header" style="background: var(--surface-primary); border-bottom: 1px solid var(--gray-200);">
                    <div class="agro-container">
                        <div class="agro-page-header__breadcrumb">
                            <a href="bhome.php">Home</a> / <a href="Categories.php">Products</a> / <span><?php echo htmlspecialchars($product_title); ?></span>
                        </div>
                        <h1 class="agro-page-header__title"><?php echo htmlspecialchars($product_title); ?></h1>
                        <div class="agro-flex" style="gap: var(--space-3); margin-top: var(--space-2);">
                            <span class="agro-badge agro-badge--green"><i class="fas fa-seedling"></i> <?php echo htmlspecialchars($product_type); ?></span>
                            <span class="agro-badge agro-badge--blue"><i class="fas fa-snowflake"></i> <?php echo htmlspecialchars($storage_condition); ?></span>
                            <?php if ($is_processed): ?>
                                <span class="agro-badge agro-badge--amber"><i class="fas fa-industry"></i> Processed</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Product Details Grid -->
                <div class="agro-container agro-section">
                    <div class="agro-grid" style="grid-template-columns: 1fr 1fr 1fr; gap: var(--space-8); align-items: start;">
                        
                        <!-- Col 1: Image Card -->
                        <div class="agro-card agro-p-4">
                            <div class="agro-product-card__image-wrap" style="height: 320px; border-radius: var(--radius-xl);">
                                <img src="../Admin/product_images/<?php echo htmlspecialchars($product_image); ?>" alt="<?php echo htmlspecialchars($product_title); ?>" class="agro-product-card__image">
                            </div>
                        </div>

                        <!-- Col 2: Pricing & Add to Cart -->
                        <div class="agro-card agro-p-6">
                            <div class="agro-mb-6">
                                <div style="font-size: var(--text-xs); color: var(--text-tertiary); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Price per kg</div>
                                <div style="font-family: var(--font-display); font-size: var(--text-4xl); font-weight: 800; color: var(--color-primary);">₹<?php echo htmlspecialchars($product_price); ?> <span style="font-size: var(--text-sm); color: var(--text-tertiary); font-weight: 400;">/ kg</span></div>
                            </div>

                            <div class="agro-stat-card agro-mb-6" style="padding: var(--space-4);">
                                <div class="agro-label"><i class="fas fa-boxes-stacked" style="margin-right:6px; color:var(--color-primary);"></i>Available Inventory</div>
                                <div style="font-size: var(--text-lg); font-weight: 700; color: var(--text-primary);"><?php echo htmlspecialchars($product_stock); ?> kgs in stock</div>
                            </div>

                            <form action="" method="post" class="agro-mb-6">
                                <div class="agro-form-group">
                                    <label class="agro-label" for="qty"><i class="fas fa-scale-balanced" style="margin-right:6px; color:var(--color-primary);"></i>Select Quantity (kg)</label>
                                    <input type="number" id="qty" name="qty" class="agro-input" value="1" min="1" max="<?php echo htmlspecialchars($product_stock); ?>" required>
                                </div>

                                <div class="agro-flex" style="gap: var(--space-3); flex-direction: column;">
                                    <button type="submit" name="cart" class="agro-btn agro-btn--secondary agro-btn--full agro-btn--lg">
                                        <i class="fas fa-cart-plus"></i> Add to Cart
                                    </button>
                                    <a href="saveforlater.php?id=<?php echo $product_id; ?>" class="agro-btn agro-btn--outline agro-btn--full">
                                        <i class="fas fa-heart"></i> Save For Later
                                    </a>
                                </div>
                            </form>

                            <div class="agro-flex" style="gap: var(--space-3); font-size: var(--text-sm); color: var(--text-secondary); margin-bottom: var(--space-3);">
                                <i class="fas fa-truck" style="color: var(--color-primary); font-size: 1.1rem;"></i>
                                <span><?php echo $delivery_text; ?></span>
                            </div>

                            <div class="agro-flex" style="gap: var(--space-3); font-size: var(--text-sm); color: var(--text-secondary);">
                                <i class="fas fa-location-dot" style="color: var(--color-primary); font-size: 1.1rem;"></i>
                                <span><?php echo htmlspecialchars($farmer_district . ', ' . $farmer_state); ?></span>
                            </div>
                        </div>

                        <!-- Col 3: Farmer Card -->
                        <div class="agro-card agro-p-6" style="background: linear-gradient(135deg, var(--gray-900), var(--gray-800)); color: white;">
                            <div class="agro-text-center agro-mb-6">
                                <div class="agro-navbar__avatar" style="width:64px; height:64px; font-size:1.6rem; margin: 0 auto var(--space-4); background: linear-gradient(135deg, var(--agro-green-400), var(--agro-green-600));">
                                    <?php echo strtoupper(substr($farmer_name, 0, 1)); ?>
                                </div>
                                <h3 style="color: white; font-size: var(--text-xl);"><?php echo htmlspecialchars($farmer_name); ?></h3>
                                <div style="font-size: var(--text-xs); color: var(--agro-green-400); font-weight: 600; text-transform: uppercase; margin-top: 4px;">Verified Producer</div>
                            </div>

                            <div class="agro-divider" style="background: rgba(255,255,255,0.1);"></div>

                            <div style="font-size: var(--text-sm); display: flex; flex-direction: column; gap: var(--space-4); margin-bottom: var(--space-6);">
                                <div>
                                    <div style="color: var(--gray-400); font-size: var(--text-xs);">Contact Phone</div>
                                    <div style="font-weight: 600; color: white;"><?php echo htmlspecialchars($farmer_phone); ?></div>
                                </div>

                                <div>
                                    <div style="color: var(--gray-400); font-size: var(--text-xs);">Farm Location</div>
                                    <div style="font-weight: 500; color: var(--gray-300);"><?php echo htmlspecialchars($farmer_address); ?></div>
                                </div>
                            </div>

                            <a href="BuyerPageFarmerProfile.php?farmer_id=<?php echo $farmer_fk; ?>" class="agro-btn agro-btn--primary agro-btn--full">
                                <i class="fas fa-id-card"></i> View Farmer Profile
                            </a>
                        </div>

                    </div>

                    <!-- Description Section -->
                    <div class="agro-card agro-p-8 agro-mt-8">
                        <h3 style="margin-bottom: var(--space-4);"><i class="fas fa-circle-info" style="color:var(--color-primary); margin-right:8px;"></i>Produce Description</h3>
                        <p style="font-size: var(--text-base); line-height: var(--leading-relaxed); color: var(--text-secondary);"><?php echo nl2br(htmlspecialchars($product_desc)); ?></p>
                    </div>
                </div>

                <?php
            }
        }
    }

    if (isset($_POST['cart'])) {
        $qty = (isset($_POST['qty']) && intval($_POST['qty']) > 0) ? intval($_POST['qty']) : 1;
        global $con;
        if (isset($_SESSION['phonenumber'])) {
            $sess_phone_number = $_SESSION['phonenumber'];

            $check_pro = "select * from cart where phonenumber = '$sess_phone_number' and product_id='$product_id'";
            $run_check = mysqli_query($con, $check_pro);

            if (mysqli_num_rows($run_check) > 0) {
                echo "<script>alert('This product is already in your cart!'); window.location.reload(true);</script>";
            } else {
                $subtotal = $product_price * $qty;
                $insert_pro = "insert into cart (product_id,phonenumber,qty,subtotal) values ('$product_id','$sess_phone_number','$qty','$subtotal')";
                $run_insert_pro = mysqli_query($con, $insert_pro);
                echo "<script>alert('Product added to cart successfully!'); window.location.reload(true);</script>";
            }
        } else {
            echo "<script>alert('Please Login First!');</script>";
        }
    }
    ?>

    <!-- Shared Footer -->
    <?php agro_footer('buyer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

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
    <title>Checkout — AgroNGO</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- Shared Navbar -->
    <?php agro_navbar('buyer', 'checkout'); ?>

    <!-- Header -->
    <div class="agro-page-header" style="background: var(--surface-primary); border-bottom: 1px solid var(--gray-200);">
        <div class="agro-container">
            <div class="agro-page-header__breadcrumb">
                <a href="bhome.php">Home</a> / <a href="cartpage.php">Cart</a> / <span>Checkout</span>
            </div>
            <h1 class="agro-page-header__title">Order Checkout</h1>
            <p class="agro-page-header__desc">Confirm delivery address, fulfillment options, and payment mode.</p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="agro-container agro-section">
        <?php
        $phonenumber = $_SESSION['phonenumber'] ?? '';
        $buyer_addr = '';
        if (!empty($phonenumber)) {
            $get_addr = "select buyer_addr from buyerregistration where buyer_phone='$phonenumber'";
            $run = mysqli_query($con, $get_addr);
            if ($row = mysqli_fetch_array($run)) {
                $buyer_addr = $row['buyer_addr'];
            }
        }
        ?>

        <form action="checkout.php" method="post">
            <div class="agro-grid" style="grid-template-columns: 2fr 1fr; gap: var(--space-8); align-items: start;">
                
                <!-- Left Details Column -->
                <div>
                    <!-- Address Section -->
                    <div class="agro-card agro-p-6 agro-mb-6">
                        <h3 style="margin-bottom: var(--space-4);"><i class="fas fa-location-dot" style="color:var(--color-primary); margin-right:8px;"></i>1. Delivery Address</h3>
                        <div class="agro-form-group">
                            <label class="agro-label" for="address">Shipping Address</label>
                            <textarea class="agro-textarea" id="address" name="address" rows="3" required><?php echo htmlspecialchars($buyer_addr); ?></textarea>
                        </div>
                    </div>

                    <!-- Items Review Section -->
                    <div class="agro-card agro-p-6 agro-mb-6">
                        <h3 style="margin-bottom: var(--space-4);"><i class="fas fa-box-open" style="color:var(--color-primary); margin-right:8px;"></i>2. Review Produce Items</h3>
                        <div class="agro-table-wrapper">
                            <table class="agro-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item Name</th>
                                        <th>Amount (₹)</th>
                                        <th>Fulfillment Method</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    global $con;
                                    $allproducts = array();
                                    $allqty = array();
                                    $allsubtotal = array();
                                    $allphones = array();
                                    $i = 0;

                                    if (isset($_SESSION['phonenumber'])) {
                                        $sess_phone_number = $_SESSION['phonenumber'];
                                        $sel_price = "select * from cart where phonenumber = '$sess_phone_number'";
                                        $run_price = mysqli_query($con, $sel_price);

                                        while ($p_price = mysqli_fetch_array($run_price)) {
                                            $product_id = $p_price['product_id'];
                                            $qty = $p_price['qty'];
                                            $subtotal = $p_price['subtotal'];
                                            array_push($allproducts, $product_id);
                                            array_push($allqty, $qty);
                                            array_push($allsubtotal, $subtotal);

                                            $pro_price = "select * from products where product_id='$product_id'";
                                            $run_pro_price = mysqli_query($con, $pro_price);
                                            while ($pp_price = mysqli_fetch_array($run_pro_price)) {
                                                $product_title = $pp_price['product_title'];
                                                $farmer_fk = $pp_price['farmer_fk'];

                                                $get_phone = "select * from farmerregistration where farmer_id = '$farmer_fk'";
                                                $run_get_phone = mysqli_query($con, $get_phone);
                                                if ($phones = mysqli_fetch_array($run_get_phone)) {
                                                    $phone = $phones['farmer_phone'];
                                                    array_push($allphones, $phone);
                                                }
                                                ?>
                                                <tr>
                                                    <td><?php echo $i + 1; ?></td>
                                                    <td><strong><?php echo htmlspecialchars($product_title); ?></strong></td>
                                                    <td>₹<?php echo htmlspecialchars($subtotal); ?></td>
                                                    <td>
                                                        <select class="agro-select" name="delivery" style="padding: var(--space-2) var(--space-3); font-size: var(--text-xs);">
                                                            <option value="Farmer" selected>Farmer Delivery</option>
                                                            <option value="Buyer">Buyer Self-Pickup</option>
                                                            <option value="Courier">Courier Express</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            $i++;
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Payment Mode Section -->
                    <div class="agro-card agro-p-6">
                        <h3 style="margin-bottom: var(--space-4);"><i class="fas fa-credit-card" style="color:var(--color-primary); margin-right:8px;"></i>3. Select Payment Mode</h3>
                        <div class="agro-flex" style="gap: var(--space-6); flex-wrap: wrap;">
                            <label class="agro-checkbox agro-card agro-p-4" style="flex:1; cursor:pointer;">
                                <input type="radio" name="payment" value="cod" checked required />
                                <div>
                                    <strong>💵 Cash on Delivery (COD)</strong>
                                    <div style="font-size: var(--text-xs); color: var(--text-tertiary);">Pay directly upon produce receipt</div>
                                </div>
                            </label>

                            <label class="agro-checkbox agro-card agro-p-4" style="flex:1; cursor:pointer;">
                                <input type="radio" name="payment" value="paytm" required />
                                <div>
                                    <strong>📱 Paytm / UPI Wallet</strong>
                                    <div style="font-size: var(--text-xs); color: var(--text-tertiary);">Instant digital mobile transfer</div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Right Summary Column -->
                <div class="agro-card agro-p-6" style="position: sticky; top: 90px;">
                    <h3 style="margin-bottom: var(--space-4);">Checkout Summary</h3>
                    <div class="agro-flex-between agro-mb-4">
                        <span style="color: var(--text-secondary);">Subtotal</span>
                        <span>₹<?php echo $_SESSION['grandtotal'] ?? 0; ?></span>
                    </div>
                    <div class="agro-flex-between agro-mb-4">
                        <span style="color: var(--text-secondary);">Shipping</span>
                        <span style="color: var(--color-success); font-weight: 600;">FREE</span>
                    </div>
                    <div class="agro-divider"></div>
                    <div class="agro-flex-between agro-mb-6">
                        <span style="font-weight: 700; font-size: var(--text-lg);">Total Payable</span>
                        <span style="font-weight: 800; font-size: var(--text-2xl); color: var(--color-primary); font-family: var(--font-display);">₹<?php echo $_SESSION['grandtotal'] ?? 0; ?></span>
                    </div>

                    <button type="submit" name="submit" class="agro-btn agro-btn--secondary agro-btn--full agro-btn--lg">
                        <i class="fas fa-check-circle"></i> Place Order Now
                    </button>

                    <div style="margin-top: var(--space-4); text-align: center;">
                        <a href="cartpage.php" class="agro-btn agro-btn--ghost agro-btn--sm">
                            <i class="fas fa-arrow-left"></i> Return to Cart
                        </a>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <!-- Shared Footer -->
    <?php agro_footer('buyer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

<?php
if (isset($_POST['submit'])) {
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $delivery = mysqli_real_escape_string($con, $_POST['delivery']);
    $payment = mysqli_real_escape_string($con, $_POST['payment']);
    $total = $_SESSION['grandtotal'] ?? 0;

    $count = 0;
    while ($count < $i) {
        $product_id = $allproducts[$count];
        $qty = $allqty[$count];
        $total = $allsubtotal[$count];
        $phone = $allphones[$count];
        $query1 = "insert into orders (product_id,qty,address,delivery,phonenumber,total,payment,buyer_phonenumber) 
                   values ('$product_id','$qty','$address','$delivery','$phone','$total','$payment','$sess_phone_number')";
        $run = mysqli_query($con, $query1);
        $count++;
    }
    $clear = "delete from cart where phonenumber = '$sess_phone_number'";
    $run = mysqli_query($con, $clear);
    if ($run) {
        echo "<script>window.open('Success.php','_self')</script>";
    }
}
?>

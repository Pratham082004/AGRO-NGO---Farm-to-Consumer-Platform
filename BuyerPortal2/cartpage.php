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
    <title>Shopping Cart — AgroNGO</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .cart-summary-card {
            background: var(--surface-elevated);
            border-radius: var(--radius-2xl);
            border: 1px solid var(--gray-200);
            padding: var(--space-6);
            position: sticky;
            top: 90px;
        }

        .qty-controls {
            display: flex;
            align-items: center;
            gap: var(--space-2);
            justify-content: center;
        }

        .qty-btn {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-md);
            background: var(--gray-100);
            border: 1px solid var(--gray-300);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            font-weight: 700;
            text-decoration: none !important;
            transition: all var(--duration-fast);
        }

        .qty-btn:hover {
            background: var(--color-primary);
            color: white;
            border-color: var(--color-primary);
        }

        .qty-input {
            width: 50px;
            text-align: center;
            padding: var(--space-1) var(--space-2);
            border-radius: var(--radius-md);
            border: 1px solid var(--gray-300);
            font-weight: 600;
        }
    </style>
</head>
<body>

    <!-- Shared Navbar -->
    <?php agro_navbar('buyer', 'cart'); ?>

    <!-- Header -->
    <div class="agro-page-header" style="background: var(--surface-primary); border-bottom: 1px solid var(--gray-200);">
        <div class="agro-container">
            <div class="agro-page-header__breadcrumb">
                <a href="bhome.php">Home</a> / <span>Cart</span>
            </div>
            <h1 class="agro-page-header__title">Your Shopping Cart</h1>
            <p class="agro-page-header__desc">Review items, adjust quantities, and proceed to secure checkout.</p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="agro-container agro-section">
        <?php if (isset($_SESSION['phonenumber'])): ?>
            <?php
            $total = 0;
            global $con;
            $sess_phone_number = $_SESSION['phonenumber'];
            $sel_price = "select * from cart where phonenumber = '$sess_phone_number'";
            $run_price = mysqli_query($con, $sel_price);
            $item_count = mysqli_num_rows($run_price);
            ?>

            <?php if ($item_count > 0): ?>
                <div class="agro-grid" style="grid-template-columns: 2fr 1fr; gap: var(--space-8); align-items: start;">
                    
                    <!-- Cart Items Table -->
                    <div>
                        <div class="agro-table-wrapper">
                            <table class="agro-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item Name</th>
                                        <th>Price (₹)</th>
                                        <th style="text-align:center;">Quantity</th>
                                        <th>Subtotal</th>
                                        <th style="text-align:center;">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;
                                    while ($p_price = mysqli_fetch_array($run_price)) {
                                        $product_id = $p_price['product_id'];
                                        $_SESSION['qtycart'][$i] = $p_price['qty'];

                                        $pro_price = "select * from products where product_id='$product_id'";
                                        $run_pro_price = mysqli_query($con, $pro_price);
                                        while ($pp_price = mysqli_fetch_array($run_pro_price)) {
                                            $product_title = $pp_price['product_title'];
                                            $product_price = $pp_price['product_price'];
                                            $subtotal = $_SESSION['qtycart'][$i] * $product_price;
                                            $total += $subtotal;

                                            $subquery = "update cart set subtotal = $subtotal where product_id = $product_id";
                                            mysqli_query($con, $subquery);
                                            ?>
                                            <tr>
                                                <td><?php echo $i + 1; ?></td>
                                                <td><strong><?php echo htmlspecialchars($product_title); ?></strong></td>
                                                <td>₹<?php echo htmlspecialchars($product_price); ?></td>
                                                <td>
                                                    <div class="qty-controls">
                                                        <a href="MinusQty.php?id=<?php echo $product_id; ?>" class="qty-btn">-</a>
                                                        <input type="number" class="qty-input" value="<?php echo $_SESSION['qtycart'][$i]; ?>" readonly>
                                                        <a href="AddQty.php?id=<?php echo $product_id; ?>" class="qty-btn">+</a>
                                                    </div>
                                                </td>
                                                <td><strong style="color: var(--color-primary);">₹<?php echo htmlspecialchars($subtotal); ?></strong></td>
                                                <td style="text-align:center;">
                                                    <a href="DeleteProductCart.php?id=<?php echo $product_id; ?>" style="color: var(--agro-red-500); font-size: 1.2rem;" title="Remove Item">
                                                        <i class="fas fa-trash-can"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        $i++;
                                    }
                                    $_SESSION['grandtotal'] = $total;
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="agro-flex-between agro-mt-6">
                            <a href="emptyCart.php" class="agro-btn agro-btn--outline agro-btn--danger">
                                <i class="fas fa-trash"></i> Empty Cart
                            </a>
                            <a href="bhome.php" class="agro-btn agro-btn--ghost">
                                <i class="fas fa-arrow-left"></i> Continue Shopping
                            </a>
                        </div>
                    </div>

                    <!-- Summary Card -->
                    <div class="cart-summary-card">
                        <h3 style="margin-bottom: var(--space-4);">Order Summary</h3>
                        <div class="agro-flex-between agro-mb-4" style="font-size: var(--text-sm); color: var(--text-secondary);">
                            <span>Items Total (<?php echo $item_count; ?>)</span>
                            <span>₹<?php echo $total; ?></span>
                        </div>
                        <div class="agro-flex-between agro-mb-4" style="font-size: var(--text-sm); color: var(--text-secondary);">
                            <span>Estimated Delivery</span>
                            <span style="color: var(--color-success); font-weight: 600;">FREE</span>
                        </div>
                        <div class="agro-divider"></div>
                        <div class="agro-flex-between agro-mb-6">
                            <span style="font-weight: 700; font-size: var(--text-lg);">Grand Total</span>
                            <span style="font-weight: 800; font-size: var(--text-2xl); color: var(--color-primary); font-family: var(--font-display);">₹<?php echo $total; ?></span>
                        </div>

                        <a href="checkout.php" class="agro-btn agro-btn--secondary agro-btn--full agro-btn--lg">
                            Proceed to Checkout <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                </div>
            <?php else: ?>
                <div class="agro-empty">
                    <div class="agro-empty__icon">🛒</div>
                    <h3 class="agro-empty__title">Your Cart is Empty</h3>
                    <p class="agro-empty__desc">Looks like you haven't added any fresh produce to your cart yet.</p>
                    <a href="bhome.php" class="agro-btn agro-btn--primary agro-btn--lg">Browse Fresh Produce</a>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="agro-empty">
                <div class="agro-empty__icon">🔒</div>
                <h3 class="agro-empty__title">Authentication Required</h3>
                <p class="agro-empty__desc">Please sign in to your buyer account to view your cart.</p>
                <a href="../auth/BuyerLogin.php" class="agro-btn agro-btn--secondary agro-btn--lg">Buyer Login</a>
            </div>
        <?php endif; ?>

    </div>

    <!-- Shared Footer -->
    <?php agro_footer('buyer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

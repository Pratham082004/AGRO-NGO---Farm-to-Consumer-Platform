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
    <title>Buyer Orders & Transactions — AgroNGO</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- Shared Navbar -->
    <?php agro_navbar('buyer', 'transactions'); ?>

    <!-- Page Header -->
    <div class="agro-page-header" style="background: var(--surface-primary); border-bottom: 1px solid var(--gray-200);">
        <div class="agro-container">
            <div class="agro-page-header__breadcrumb">
                <a href="bhome.php">Home</a> / <span>Order History</span>
            </div>
            <h1 class="agro-page-header__title">Order Transactions</h1>
            <p class="agro-page-header__desc">Review your direct farmer purchases, delivery modes, and order receipts.</p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="agro-container agro-section">
        
        <?php if (isset($_SESSION['phonenumber'])): ?>
            <div class="agro-table-wrapper">
                <table class="agro-table">
                    <thead>
                        <tr>
                            <th>Farmer Name</th>
                            <th>Farmer Phone</th>
                            <th>Delivery Address</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Delivery Mode</th>
                            <th>Payment</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        global $con;
                        $sess_phone_number = $_SESSION['phonenumber'];
                        $sel_price = "select * from orders where buyer_phonenumber = '$sess_phone_number' order by order_id desc";
                        $run_price = mysqli_query($con, $sel_price);
                        $count = $run_price ? mysqli_num_rows($run_price) : 0;

                        if ($count == 0) {
                            echo "<tr><td colspan='8' class='agro-text-center' style='padding: var(--space-8); color: var(--text-secondary);'>No purchase orders found yet. <a href='bhome.php' style='color: var(--color-primary); font-weight: 600;'>Start shopping</a></td></tr>";
                        }

                        if ($run_price) {
                            while ($p_price = mysqli_fetch_array($run_price)) {
                                $product_id = $p_price['product_id'];
                                $qty = $p_price['qty'];
                                $total = $p_price['total'];
                                $address = $p_price['address'];
                                $delivery = $p_price['delivery'];
                                $payment = $p_price['payment'];

                                $pro_price = "select * from products where product_id='$product_id'";
                                $run_pro_price = mysqli_query($con, $pro_price);
                                while ($pp_price = mysqli_fetch_array($run_pro_price)) {
                                    $product_title = $pp_price['product_title'];
                                    $farmer_id = $pp_price['farmer_fk'];

                                    $query_name = "select * from farmerregistration where farmer_id = '$farmer_id'";
                                    $run_query_name = mysqli_query($con, $query_name);
                                    $farmer_name = "Local Farmer";
                                    $farmer_phone = "N/A";
                                    if ($run_query_name && $names = mysqli_fetch_array($run_query_name)) {
                                        $farmer_name = $names['farmer_name'];
                                        $farmer_phone = $names['farmer_phone'];
                                    }
                                    ?>
                                    <tr>
                                        <td><strong>👨‍🌾 <?php echo htmlspecialchars($farmer_name); ?></strong></td>
                                        <td><?php echo htmlspecialchars($farmer_phone); ?></td>
                                        <td><?php echo htmlspecialchars($address); ?></td>
                                        <td><strong><?php echo htmlspecialchars($product_title); ?></strong></td>
                                        <td><span class="agro-badge agro-badge--green"><?php echo htmlspecialchars($qty); ?> kg</span></td>
                                        <td><span class="agro-badge agro-badge--blue"><?php echo htmlspecialchars(ucfirst($delivery)); ?></span></td>
                                        <td><span class="agro-badge agro-badge--amber"><?php echo htmlspecialchars(strtoupper($payment)); ?></span></td>
                                        <td><strong style="color: var(--color-primary); font-size: var(--text-base);">₹<?php echo htmlspecialchars($total); ?></strong></td>
                                    </tr>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="agro-mt-8 agro-text-center">
                <a href="bhome.php" class="agro-btn agro-btn--primary agro-btn--lg">
                    <i class="fas fa-store"></i> Continue Shopping
                </a>
            </div>

        <?php else: ?>
            <div class="agro-empty">
                <div class="agro-empty__icon">🔒</div>
                <h3 class="agro-empty__title">Authentication Required</h3>
                <p class="agro-empty__desc">Please log in to your buyer account to view your order history.</p>
                <a href="../auth/BuyerLogin.php" class="agro-btn agro-btn--primary agro-btn--lg">Buyer Login</a>
            </div>
        <?php endif; ?>

    </div>

    <!-- Shared Footer -->
    <?php agro_footer('buyer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

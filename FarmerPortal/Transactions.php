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
    <title>Farmer Transactions — AgroNGO</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- Shared Navbar -->
    <?php agro_navbar('farmer', 'transactions'); ?>

    <!-- Header -->
    <div class="agro-page-header" style="background: var(--surface-primary); border-bottom: 1px solid var(--gray-200);">
        <div class="agro-container">
            <div class="agro-page-header__breadcrumb">
                <a href="farmerHomepage.php">Home</a> / <span>Transactions</span>
            </div>
            <h1 class="agro-page-header__title">Transaction History</h1>
            <p class="agro-page-header__desc">Review completed sales, buyer details, and payout receipts.</p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="agro-container agro-section">
        
        <?php if (isset($_SESSION['phonenumber'])): ?>
            <div class="agro-table-wrapper">
                <table class="agro-table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Buyer Name</th>
                            <th>Buyer Phone</th>
                            <th>Delivery Address</th>
                            <th>Quantity</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        global $con;
                        $sess_phone_number = $_SESSION['phonenumber'];
                        $sel_price = "select * from orders where phonenumber = '$sess_phone_number'";
                        $run_price = mysqli_query($con, $sel_price);
                        $count = mysqli_num_rows($run_price);

                        if ($count == 0) {
                            echo "<tr><td colspan='6' class='agro-text-center' style='padding: var(--space-8); color: var(--text-secondary);'>No transactions recorded yet.</td></tr>";
                        }

                        while ($p_price = mysqli_fetch_array($run_price)) {
                            $product_id = $p_price['product_id'];
                            $qty = $p_price['qty'];
                            $total = $p_price['total'];
                            $address = $p_price['address'];
                            $phone = $p_price['buyer_phonenumber'];

                            $pro_price = "select * from products where product_id='$product_id'";
                            $run_pro_price = mysqli_query($con, $pro_price);
                            while ($pp_price = mysqli_fetch_array($run_pro_price)) {
                                $product_title = $pp_price['product_title'];

                                $query_name = "select * from buyerregistration where buyer_phone = '$phone'";
                                $run_query_name = mysqli_query($con, $query_name);
                                $buyer_name = "N/A";
                                if ($names = mysqli_fetch_array($run_query_name)) {
                                    $buyer_name = $names['buyer_name'];
                                }
                                ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($product_title); ?></strong></td>
                                    <td><?php echo htmlspecialchars($buyer_name); ?></td>
                                    <td><?php echo htmlspecialchars($phone); ?></td>
                                    <td><?php echo htmlspecialchars($address); ?></td>
                                    <td><span class="agro-badge agro-badge--green"><?php echo htmlspecialchars($qty); ?> kg</span></td>
                                    <td><strong style="color: var(--color-primary);">₹<?php echo htmlspecialchars($total); ?></strong></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="agro-empty">
                <div class="agro-empty__icon">🔒</div>
                <h3 class="agro-empty__title">Please Login First</h3>
                <p class="agro-empty__desc">Sign in to your farmer account to view transaction history.</p>
                <a href="../auth/FarmerLogin.php" class="agro-btn agro-btn--primary agro-btn--lg">Farmer Login</a>
            </div>
        <?php endif; ?>

    </div>

    <!-- Shared Footer -->
    <?php agro_footer('farmer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

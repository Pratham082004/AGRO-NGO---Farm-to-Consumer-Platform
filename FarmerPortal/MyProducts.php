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
    <title>My Products — Farmer Portal</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- Shared Navbar -->
    <?php agro_navbar('farmer', 'products'); ?>

    <!-- Header -->
    <div class="agro-page-header" style="background: var(--surface-primary); border-bottom: 1px solid var(--gray-200);">
        <div class="agro-container">
            <div class="agro-flex-between" style="flex-wrap: wrap; gap: var(--space-4);">
                <div>
                    <div class="agro-page-header__breadcrumb">
                        <a href="farmerHomepage.php">Home</a> / <span>My Products</span>
                    </div>
                    <h1 class="agro-page-header__title">My Listed Products</h1>
                    <p class="agro-page-header__desc">Manage your existing produce listings, stock levels, and pricing.</p>
                </div>
                <div style="display: flex; gap: var(--space-3); flex-wrap: wrap;">
                    <a href="SmartAdvisory.php" class="agro-btn agro-btn--secondary agro-btn--lg">
                        <i class="fas fa-brain"></i> AI Buyer Advisory
                    </a>
                    <?php if (isset($_SESSION['phonenumber'])): ?>
                        <a href="InsertProduct.php" class="agro-btn agro-btn--primary agro-btn--lg">
                            <i class="fas fa-plus-circle"></i> Add New Product
                        </a>
                    <?php else: ?>
                        <a href="../auth/FarmerLogin.php" class="agro-btn agro-btn--primary agro-btn--lg">
                            <i class="fas fa-sign-in-alt"></i> Login to Add Products
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="agro-container agro-section">
        <?php
        include("../Includes/db.php");
        if (isset($_SESSION['phonenumber'])) {
            $sess_phone_number = $_SESSION['phonenumber'];
            echo "<div class='agro-product-grid'>";
            getFarmerProducts();
            echo "</div>";
        } else {
            echo "<div class='agro-empty'>";
            echo "<div class='agro-empty__icon'>🔒</div>";
            echo "<h3 class='agro-empty__title'>Authentication Required</h3>";
            echo "<p class='agro-empty__desc'>Please sign in to your farmer account to view and manage your products.</p>";
            echo "<a href='../auth/FarmerLogin.php' class='agro-btn agro-btn--primary agro-btn--lg'>Farmer Login</a>";
            echo "</div>";
        }
        ?>
    </div>

    <!-- Shared Footer -->
    <?php agro_footer('farmer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

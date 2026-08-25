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
    <title>Buyer Homepage — AgroNGO Marketplace</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <style>
        .buyer-hero {
            background: linear-gradient(135deg, var(--agro-amber-500) 0%, var(--agro-green-700) 100%);
            border-radius: var(--radius-2xl);
            padding: var(--space-12) var(--space-8);
            color: white;
            margin-bottom: var(--space-8);
            position: relative;
            overflow: hidden;
        }

        .buyer-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 80% 20%, rgba(255,255,255,0.15) 0%, transparent 60%);
        }

        .category-nav-bar {
            display: flex;
            gap: var(--space-3);
            margin-bottom: var(--space-8);
            flex-wrap: wrap;
            justify-content: center;
        }

        .category-nav-btn {
            background: var(--surface-elevated);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-full);
            padding: var(--space-3) var(--space-6);
            font-weight: 600;
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.25s var(--ease-default);
            display: inline-flex;
            align-items: center;
            gap: var(--space-2);
        }

        .category-nav-btn:hover {
            border-color: var(--color-primary);
            color: var(--color-primary);
            background: var(--color-primary-light);
            transform: translateY(-2px);
        }

        .section-divider-heading {
            display: flex;
            align-items: center;
            gap: var(--space-4);
            margin: var(--space-10) 0 var(--space-6);
        }

        .section-divider-heading h2 {
            font-size: var(--text-2xl);
            white-space: nowrap;
        }

        .section-divider-heading::after {
            content: '';
            flex: 1;
            height: 2px;
            background: var(--gray-200);
        }
    </style>
</head>
<body>

    <!-- Shared Navbar -->
    <?php agro_navbar('buyer', 'home'); ?>

    <div class="agro-container agro-mt-6">

        <!-- Hero Promotional Banner -->
        <div class="buyer-hero">
            <div style="max-width: 600px; position: relative; z-index: 1;">
                <span class="agro-badge agro-badge--green agro-mb-4" style="background: rgba(255,255,255,0.2); color: white;">
                    🌾 Direct Farm Fresh
                </span>
                <h1 style="color: white; font-size: var(--text-4xl); margin-bottom: var(--space-3);">
                    Fresh Produce, Direct From Indian Farmers
                </h1>
                <p style="color: rgba(255,255,255,0.9); font-size: var(--text-base); margin-bottom: var(--space-6);">
                    No middlemen. No inflated prices. Get organic fruits, vegetables, and crops delivered straight from local farms.
                </p>

                <!-- Search form -->
                <form action="SearchResult.php" method="get" class="agro-search" style="max-width: 100%;">
                    <span class="agro-search__icon"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="agro-search__input" placeholder="Search for fresh fruits, vegetables, grains..." style="padding-top:14px; padding-bottom:14px; font-size:var(--text-base); background:white;">
                </form>
            </div>
        </div>

        <!-- Category Dropdowns Bar -->
        <div class="category-nav-bar">
            <div class="dropdown">
                <button class="category-nav-btn dropdown-toggle" type="button" id="fruitsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    🍎 Fresh Fruits
                </button>
                <div class="dropdown-menu agro-dropdown__menu" aria-labelledby="fruitsDropdown">
                    <?php getFruits(); ?>
                </div>
            </div>

            <div class="dropdown">
                <button class="category-nav-btn dropdown-toggle" type="button" id="vegDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    🥦 Fresh Vegetables
                </button>
                <div class="dropdown-menu agro-dropdown__menu" aria-labelledby="vegDropdown">
                    <?php getVegetables(); ?>
                </div>
            </div>

            <div class="dropdown">
                <button class="category-nav-btn dropdown-toggle" type="button" id="cropsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    🌾 Crops & Grains
                </button>
                <div class="dropdown-menu agro-dropdown__menu" aria-labelledby="cropsDropdown">
                    <?php getCrops(); ?>
                </div>
            </div>
        </div>

        <!-- Fresh Fruits Section -->
        <div class="section-divider-heading">
            <h2>🍎 Fresh Fruits</h2>
        </div>
        <div class="agro-product-grid">
            <?php getFruitsHomepage(); ?>
        </div>

        <!-- Fresh Vegetables Section -->
        <div class="section-divider-heading">
            <h2>🥦 Fresh Vegetables</h2>
        </div>
        <div class="agro-product-grid">
            <?php getVegetablesHomepage(); ?>
        </div>

        <!-- Best Selling Products Section -->
        <div class="section-divider-heading">
            <h2>⭐ Best Selling Produce Across India</h2>
        </div>
        <div class="agro-product-grid agro-mb-12">
            <?php
            cart();
            getProducts();
            ?>
        </div>

    </div>

    <!-- Shared Footer -->
    <?php agro_footer('buyer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

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
    <title>Farmer Homepage — AgroNGO</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="../portal_files/jquery.min.js.download"></script>
    <script src="../portal_files/popper.min.js.download"></script>
    <script src="../portal_files/bootstrap.min.js.download"></script>
    <style>
        .farmer-dashboard-hero {
            background: linear-gradient(135deg, var(--agro-green-800), var(--gray-900));
            color: white;
            padding: var(--space-12) 0 var(--space-8);
            border-radius: 0 0 var(--radius-2xl) var(--radius-2xl);
            margin-bottom: var(--space-8);
        }

        .farmer-nav-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: var(--space-4);
            margin-bottom: var(--space-8);
        }

        .farmer-nav-card {
            background: var(--surface-elevated);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-xl);
            padding: var(--space-5);
            text-align: center;
            color: var(--text-primary);
            transition: all 0.3s var(--ease-default);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: var(--space-3);
            text-decoration: none !important;
        }

        .farmer-nav-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--color-primary);
            color: var(--color-primary);
        }

        .farmer-nav-card__icon {
            width: 50px;
            height: 50px;
            border-radius: var(--radius-lg);
            background: var(--color-primary-light);
            color: var(--color-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .farmer-nav-card__title {
            font-weight: 600;
            font-size: var(--text-sm);
        }

        .carousel-item img {
            border-radius: var(--radius-xl);
            height: 380px;
            object-fit: cover;
        }

        @media (max-width: 768px) {
            .farmer-nav-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>

    <!-- Shared Navbar -->
    <?php agro_navbar('farmer', 'home'); ?>

    <!-- Farmer Hero Banner -->
    <div class="farmer-dashboard-hero">
        <div class="agro-container">
            <div class="agro-flex-between" style="flex-wrap: wrap; gap: var(--space-4);">
                <div>
                    <h1 style="color: white; font-size: var(--text-3xl); margin-bottom: var(--space-2);">
                        Welcome Back, <?php getFarmerUsername(); ?> 👋
                    </h1>
                    <p style="color: var(--gray-300); font-size: var(--text-base);">
                        Manage your crops, list new produce, and view orders from buyers across India.
                    </p>
                </div>
                <div>
                    <a href="InsertProduct.php" class="agro-btn agro-btn--primary agro-btn--lg">
                        <i class="fas fa-plus-circle"></i> Add New Product
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Container -->
    <div class="agro-container">
        
        <!-- Navigation Shortcut Grid -->
        <div class="farmer-nav-grid">
            <a href="MyProducts.php" class="farmer-nav-card">
                <div class="farmer-nav-card__icon"><i class="fas fa-boxes-stacked"></i></div>
                <span class="farmer-nav-card__title">My Products</span>
            </a>
            <a href="Orders.php" class="farmer-nav-card">
                <div class="farmer-nav-card__icon"><i class="fas fa-shopping-bag"></i></div>
                <span class="farmer-nav-card__title">My Orders</span>
            </a>
            <a href="Transactions.php" class="farmer-nav-card">
                <div class="farmer-nav-card__icon"><i class="fas fa-receipt"></i></div>
                <span class="farmer-nav-card__title">My Transactions</span>
            </a>
            <a href="CallCenter.php" class="farmer-nav-card">
                <div class="farmer-nav-card__icon"><i class="fas fa-headset"></i></div>
                <span class="farmer-nav-card__title">Call Center & SMS</span>
            </a>
        </div>

        <!-- Carousel -->
        <div id="farmerCarousel" class="carousel slide agro-mb-8" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#farmerCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#farmerCarousel" data-slide-to="1"></li>
                <li data-target="#farmerCarousel" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner" style="border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--shadow-md);">
                <div class="carousel-item active">
                    <img class="d-block w-100" src="../Images/Homepage/fruitsbasket.jpg" alt="Fresh Fruits">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="../Images/Website/farm1.jpeg" alt="Farming">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="../Images/Homepage/vegetables.jpg" alt="Fresh Vegetables">
                </div>
            </div>
            <a class="carousel-control-prev" href="#farmerCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#farmerCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>

        <!-- Standout Features -->
        <div class="agro-section-header agro-reveal agro-mt-8">
            <span class="agro-section-header__eyebrow"><i class="fas fa-bolt"></i> Key Tools</span>
            <h2 class="agro-section-header__title">Standout Features for Farmers</h2>
        </div>

        <div class="agro-grid agro-grid-3 agro-mb-8">
            <div class="agro-card agro-reveal agro-delay-1">
                <div class="agro-card__body agro-text-center">
                    <div style="font-size: 3rem; margin-bottom: var(--space-4);">📲</div>
                    <h4 class="agro-card__title">SMS & Portal System</h4>
                    <p class="agro-card__subtitle">Upload, update, and manage your crop inventory seamlessly via website or SMS alert updates.</p>
                </div>
            </div>

            <div class="agro-card agro-reveal agro-delay-2">
                <div class="agro-card__body agro-text-center">
                    <div style="font-size: 3rem; margin-bottom: var(--space-4);">🤝</div>
                    <h4 class="agro-card__title">Direct Buyer Network</h4>
                    <p class="agro-card__subtitle">Sell your agricultural yields directly to verified wholesale and retail buyers without intermediaries.</p>
                </div>
            </div>

            <div class="agro-card agro-reveal agro-delay-3">
                <div class="agro-card__body agro-text-center">
                    <div style="font-size: 3rem; margin-bottom: var(--space-4);">👥</div>
                    <h4 class="agro-card__title">Farmer Group Community</h4>
                    <p class="agro-card__subtitle">Form collaborative farming groups to minimize food waste and optimize logistics across regions.</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Shared Footer -->
    <?php agro_footer('farmer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

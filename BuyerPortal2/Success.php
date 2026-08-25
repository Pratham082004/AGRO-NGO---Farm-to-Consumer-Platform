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
    <title>Order Placed Successfully — AgroNGO</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .success-card {
            max-width: 620px;
            margin: var(--space-8) auto;
            text-align: center;
            padding: var(--space-12) var(--space-8);
            position: relative;
            overflow: hidden;
        }

        .success-card::before {
            content: '';
            position: absolute;
            top: -80px;
            right: -80px;
            width: 240px;
            height: 240px;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.15) 0%, transparent 70%);
            border-radius: var(--radius-full);
            pointer-events: none;
        }

        .success-icon-badge {
            width: 90px;
            height: 90px;
            border-radius: var(--radius-full);
            background: linear-gradient(135deg, var(--agro-green-400), var(--agro-green-600));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.75rem;
            margin: 0 auto var(--space-6);
            box-shadow: 0 10px 30px rgba(34, 197, 94, 0.35);
            animation: agro-pulse 2s infinite;
        }

        .order-badge-row {
            display: flex;
            justify-content: center;
            gap: var(--space-3);
            margin: var(--space-6) 0 var(--space-8);
            flex-wrap: wrap;
        }
    </style>
</head>
<body style="display: flex; flex-direction: column; min-height: 100vh;">

    <!-- Shared Navbar -->
    <?php agro_navbar('buyer', 'checkout'); ?>

    <!-- Main Section -->
    <div class="agro-container agro-section" style="flex: 1; display: flex; align-items: center; justify-content: center;">
        
        <div class="agro-card success-card agro-animate-fadeInUp">
            
            <!-- Success Animated Badge -->
            <div class="success-icon-badge">
                <i class="fas fa-check"></i>
            </div>

            <!-- Title & Message -->
            <h1 style="font-size: var(--text-3xl); margin-bottom: var(--space-3);">
                Order Placed Successfully! 🎉
            </h1>
            <p style="font-size: var(--text-base); color: var(--text-secondary); max-width: 480px; margin: 0 auto var(--space-4);">
                Thank you for your purchase. Your order has been registered and transmitted directly to local farm producers.
            </p>

            <div class="order-badge-row">
                <span class="agro-badge agro-badge--green" style="padding: var(--space-2) var(--space-4); font-size: var(--text-sm);">
                    <i class="fas fa-circle-check"></i> Payment Confirmed
                </span>
                <span class="agro-badge agro-badge--blue" style="padding: var(--space-2) var(--space-4); font-size: var(--text-sm);">
                    <i class="fas fa-truck-ramp-box"></i> Sent to Farmer
                </span>
            </div>

            <!-- Action Buttons -->
            <div class="agro-flex-center" style="gap: var(--space-4); flex-wrap: wrap;">
                <a href="Transaction.php" class="agro-btn agro-btn--primary agro-btn--lg">
                    <i class="fas fa-receipt"></i> View Order History
                </a>
                <a href="bhome.php" class="agro-btn agro-btn--outline agro-btn--lg">
                    <i class="fas fa-store"></i> Continue Shopping
                </a>
            </div>

        </div>

    </div>

    <!-- Shared Footer -->
    <?php agro_footer('buyer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

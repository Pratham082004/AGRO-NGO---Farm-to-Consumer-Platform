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
    <title>Farmer Orders — AgroNGO</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .order-stepper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            margin: var(--space-8) 0;
            padding: var(--space-6);
            background: var(--surface-elevated);
            border-radius: var(--radius-2xl);
            border: 1px solid var(--gray-200);
        }

        .order-stepper::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 10%;
            right: 10%;
            height: 3px;
            background: var(--gray-200);
            z-index: 0;
            transform: translateY(-50%);
        }

        .step-item {
            position: relative;
            z-index: 1;
            text-align: center;
            background: var(--surface-elevated);
            padding: 0 var(--space-4);
        }

        .step-icon {
            width: 54px;
            height: 54px;
            border-radius: var(--radius-full);
            background: var(--gray-100);
            color: var(--gray-500);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin: 0 auto var(--space-2);
            border: 3px solid var(--surface-elevated);
            box-shadow: var(--shadow-sm);
        }

        .step-item.active .step-icon {
            background: var(--color-primary);
            color: white;
            box-shadow: 0 0 0 4px var(--agro-green-100);
        }

        .step-label {
            font-size: var(--text-xs);
            font-weight: 600;
            color: var(--text-secondary);
        }
    </style>
</head>
<body>

    <!-- Shared Navbar -->
    <?php agro_navbar('farmer', 'orders'); ?>

    <!-- Header -->
    <div class="agro-page-header" style="background: var(--surface-primary); border-bottom: 1px solid var(--gray-200);">
        <div class="agro-container">
            <div class="agro-page-header__breadcrumb">
                <a href="farmerHomepage.php">Home</a> / <span>Orders</span>
            </div>
            <h1 class="agro-page-header__title">Active & Past Orders</h1>
            <p class="agro-page-header__desc">Track delivery statuses, buyer details, and fulfillment progress.</p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="agro-container agro-section">
        
        <!-- Order Progress Stepper -->
        <div class="order-stepper">
            <div class="step-item active">
                <div class="step-icon"><i class="fas fa-boxes-packing"></i></div>
                <div class="step-label">Packing</div>
            </div>
            <div class="step-item active">
                <div class="step-icon"><i class="fas fa-truck-fast"></i></div>
                <div class="step-label">Dispatched</div>
            </div>
            <div class="step-item active">
                <div class="step-icon"><i class="fas fa-route"></i></div>
                <div class="step-label">In Transit</div>
            </div>
            <div class="step-item">
                <div class="step-icon"><i class="fas fa-location-dot"></i></div>
                <div class="step-label">Delivered</div>
            </div>
        </div>

        <!-- Sample Order Detail Card -->
        <div class="agro-card agro-p-8 agro-mt-6">
            <div class="agro-flex-between agro-mb-6" style="flex-wrap: wrap; gap: var(--space-3);">
                <div>
                    <span class="agro-badge agro-badge--green"><i class="fas fa-circle-check"></i> Active Order #AG-8942</span>
                    <h3 style="margin-top: var(--space-2);">Order for Fresh Apples (200 kg)</h3>
                </div>
                <div class="agro-text-right">
                    <div style="font-size: var(--text-2xl); font-weight: 800; color: var(--color-primary); font-family: var(--font-display);">₹40,000</div>
                    <span class="agro-badge agro-badge--amber">Cash on Delivery</span>
                </div>
            </div>

            <div class="agro-grid agro-grid-3" style="gap: var(--space-4);">
                <div class="agro-stat-card" style="padding: var(--space-4);">
                    <div class="agro-label"><i class="fas fa-user" style="margin-right:6px; color:var(--color-primary);"></i>Buyer Name</div>
                    <div style="font-weight:600;">Gladina Services</div>
                </div>

                <div class="agro-stat-card" style="padding: var(--space-4);">
                    <div class="agro-label"><i class="fas fa-phone" style="margin-right:6px; color:var(--color-primary);"></i>Buyer Phone</div>
                    <div style="font-weight:600;">+91 98191 04641</div>
                </div>

                <div class="agro-stat-card" style="padding: var(--space-4);">
                    <div class="agro-label"><i class="fas fa-location-dot" style="margin-right:6px; color:var(--color-primary);"></i>Delivery Location</div>
                    <div style="font-weight:600;">Mumbai, Maharashtra</div>
                </div>

                <div class="agro-stat-card" style="padding: var(--space-4);">
                    <div class="agro-label"><i class="fas fa-clock" style="margin-right:6px; color:var(--color-primary);"></i>Est. Delivery Time</div>
                    <div style="font-weight:600;">Approx. 3:00 AM</div>
                </div>

                <div class="agro-stat-card" style="padding: var(--space-4);">
                    <div class="agro-label"><i class="fas fa-calendar" style="margin-right:6px; color:var(--color-primary);"></i>Delivery Date</div>
                    <div style="font-weight:600;">8th December</div>
                </div>

                <div class="agro-stat-card" style="padding: var(--space-4);">
                    <div class="agro-label"><i class="fas fa-truck-ramp-box" style="margin-right:6px; color:var(--color-primary);"></i>Fulfillment Mode</div>
                    <div style="font-weight:600;">Farmer Self-Delivery</div>
                </div>
            </div>
        </div>

    </div>

    <!-- Shared Footer -->
    <?php agro_footer('farmer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

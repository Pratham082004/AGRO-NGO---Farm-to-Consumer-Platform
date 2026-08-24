<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../Functions/functions.php");
include("../Includes/components/navbar.php");
include("../Includes/components/footer.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kisan Call Center & Support — AgroNGO</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- Shared Navbar -->
    <?php agro_navbar('farmer', 'callcenter'); ?>

    <!-- Page Header -->
    <div class="agro-page-header" style="background: var(--surface-primary); border-bottom: 1px solid var(--gray-200);">
        <div class="agro-container">
            <div class="agro-page-header__breadcrumb">
                <a href="farmerHomepage.php">Home</a> / <span>Call Center & Support</span>
            </div>
            <h1 class="agro-page-header__title">Kisan Helpline & Advisory Services</h1>
            <p class="agro-page-header__desc">Direct access to agricultural experts, decay consultation, government schemes, and market pricing assistance.</p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="agro-container agro-section">
        
        <!-- Emergency Toll Free Hero Banner -->
        <div class="agro-card agro-p-8 agro-mb-8" style="background: linear-gradient(135deg, rgba(22, 163, 74, 0.12), rgba(34, 197, 94, 0.05)); border: 1px solid var(--agro-green-300);">
            <div class="agro-flex-between" style="flex-wrap: wrap; gap: var(--space-4);">
                <div>
                    <span class="agro-badge agro-badge--green agro-mb-2"><i class="fas fa-headset"></i> 24x7 Toll-Free Support</span>
                    <h2 style="font-size: var(--text-3xl); font-family: var(--font-display); color: var(--agro-green-800);">National Kisan Call Center</h2>
                    <p style="color: var(--text-secondary); margin-top: 4px;">Speak directly with agricultural officers for crop advice, soil health, and pest management in your local language.</p>
                </div>
                <div>
                    <a href="tel:18001801551" class="agro-btn agro-btn--primary agro-btn--lg" style="font-size: var(--text-xl); font-weight: 800;">
                        <i class="fas fa-phone-volume"></i> 1800-180-1551
                    </a>
                </div>
            </div>
        </div>

        <!-- Helpline Grid -->
        <div class="agro-grid agro-grid-3" style="gap: var(--space-6);">
            
            <!-- Card 1: Agri-Decay & Ollama Advisory -->
            <div class="agro-card agro-p-6">
                <div class="agro-navbar__avatar" style="width: 52px; height: 52px; background: var(--agro-green-100); color: var(--agro-green-700); font-size: 1.4rem; margin-bottom: var(--space-4);">
                    <i class="fas fa-robot"></i>
                </div>
                <h3 style="font-size: var(--text-lg); margin-bottom: var(--space-2);">Crop Decay & Buyer Advisory</h3>
                <p style="font-size: var(--text-sm); color: var(--text-secondary); margin-bottom: var(--space-4);">Get real-time buyer recommendations and clearance pricing for short shelf-life crops.</p>
                <a href="SmartAdvisory.php" class="agro-btn agro-btn--secondary agro-btn--full">
                    <i class="fas fa-brain"></i> Open AI Advisory Engine
                </a>
            </div>

            <!-- Card 2: Weather & Pest SMS Alert -->
            <div class="agro-card agro-p-6">
                <div class="agro-navbar__avatar" style="width: 52px; height: 52px; background: var(--agro-amber-100); color: var(--agro-amber-700); font-size: 1.4rem; margin-bottom: var(--space-4);">
                    <i class="fas fa-cloud-sun-rain"></i>
                </div>
                <h3 style="font-size: var(--text-lg); margin-bottom: var(--space-2);">Weather & Pest SMS Advisory</h3>
                <p style="font-size: var(--text-sm); color: var(--text-secondary); margin-bottom: var(--space-4);">Subscribe to daily SMS alerts regarding regional rain, temperature spikes, and crop disease prevention.</p>
                <button onclick="alert('You are subscribed to SMS weather alerts on your registered phone number!')" class="agro-btn agro-btn--outline agro-btn--full">
                    <i class="fas fa-comment-sms"></i> Toggle SMS Alerts
                </button>
            </div>

            <!-- Card 3: Government Scheme Helpdesk -->
            <div class="agro-card agro-p-6">
                <div class="agro-navbar__avatar" style="width: 52px; height: 52px; background: var(--agro-blue-100); color: var(--agro-blue-700); font-size: 1.4rem; margin-bottom: var(--space-4);">
                    <i class="fas fa-building-columns"></i>
                </div>
                <h3 style="font-size: var(--text-lg); margin-bottom: var(--space-2);">Government Scheme Desk</h3>
                <p style="font-size: var(--text-sm); color: var(--text-secondary); margin-bottom: var(--space-4);">Information regarding PM-KISAN, crop insurance (Fasal Bima Yojana), and cold storage subsidies.</p>
                <a href="https://pmkisan.gov.in" target="_blank" class="agro-btn agro-btn--ghost agro-btn--full">
                    <i class="fas fa-arrow-up-right-from-square"></i> Visit PM-KISAN Portal
                </a>
            </div>

        </div>

    </div>

    <!-- Shared Footer -->
    <?php agro_footer('farmer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

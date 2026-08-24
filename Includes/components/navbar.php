<?php
/**
 * AgroNGO Modern Navbar Component
 * 
 * Usage: include this file and call agro_navbar($portal_type, $active_page)
 * $portal_type: 'farmer' | 'buyer' | 'public' | 'admin'
 * $active_page: string matching one of the nav link names (optional)
 */

function agro_navbar($portal_type = 'public', $active_page = '') {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $is_logged_in = isset($_SESSION['phonenumber']);
    $username = '';
    $initials = 'U';
    
    if ($is_logged_in) {
        $username = isset($_SESSION['username']) ? $_SESSION['username'] : $_SESSION['phonenumber'];
        $initials = strtoupper(substr($username, 0, 1));
    }

    // Dynamically calculate relative base path to project root
    $project_root = realpath(__DIR__ . '/../../');
    $script_filename = $_SERVER['SCRIPT_FILENAME'] ?? '';
    $script_dir = $script_filename ? dirname(realpath($script_filename)) : '';
    
    $base = '';
    if ($script_dir && $project_root && $script_dir !== $project_root) {
        $relative = str_replace('\\', '/', substr($script_dir, strlen($project_root)));
        $relative_clean = trim($relative, '/');
        if (!empty($relative_clean)) {
            $depth = substr_count($relative_clean, '/');
            $base = str_repeat('../', $depth + 1);
        }
    }
?>
<nav class="agro-navbar" id="agro-navbar">
    <div class="agro-navbar__inner">
        <!-- Brand -->
        <a href="<?php echo $base; ?>index.html" class="agro-navbar__brand">
            <div class="agro-navbar__brand-icon">🌾</div>
            <span class="agro-navbar__brand-text">AgroNGO</span>
        </a>

        <!-- Desktop Nav Links -->
        <div class="agro-navbar__links">
            <?php if ($portal_type === 'farmer' && $is_logged_in): ?>
                <a href="farmerHomepage.php" class="agro-navbar__link <?php echo $active_page === 'home' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i>&nbsp; Home
                </a>
                <a href="MyProducts.php" class="agro-navbar__link <?php echo $active_page === 'products' ? 'active' : ''; ?>">
                    <i class="fas fa-box"></i>&nbsp; My Products
                </a>
                <a href="Orders.php" class="agro-navbar__link <?php echo $active_page === 'orders' ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-bag"></i>&nbsp; Orders
                </a>
                <a href="Transactions.php" class="agro-navbar__link <?php echo $active_page === 'transactions' ? 'active' : ''; ?>">
                    <i class="fas fa-receipt"></i>&nbsp; Transactions
                </a>
                <a href="SmartAdvisory.php" class="agro-navbar__link <?php echo $active_page === 'advisory' ? 'active' : ''; ?>">
                    <i class="fas fa-brain"></i>&nbsp; AI Advisory
                </a>
                <a href="InsertProduct.php" class="agro-navbar__link <?php echo $active_page === 'add' ? 'active' : ''; ?>">
                    <i class="fas fa-plus-circle"></i>&nbsp; Add Product
                </a>
            <?php elseif ($portal_type === 'buyer' && $is_logged_in): ?>
                <a href="bhome.php" class="agro-navbar__link <?php echo $active_page === 'home' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i>&nbsp; Home
                </a>
                <a href="Categories.php" class="agro-navbar__link <?php echo $active_page === 'categories' ? 'active' : ''; ?>">
                    <i class="fas fa-th-large"></i>&nbsp; Categories
                </a>
                <a href="farmers.php" class="agro-navbar__link <?php echo $active_page === 'farmers' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i>&nbsp; Farmers
                </a>
                <a href="Transaction.php" class="agro-navbar__link <?php echo $active_page === 'transactions' ? 'active' : ''; ?>">
                    <i class="fas fa-receipt"></i>&nbsp; Orders
                </a>
            <?php elseif ($portal_type === 'public'): ?>
                <a href="<?php echo $base; ?>index.html#features" class="agro-navbar__link">Features</a>
                <a href="<?php echo $base; ?>index.html#user-types" class="agro-navbar__link">Users</a>
                <a href="<?php echo $base; ?>index.html#testimonials" class="agro-navbar__link">Stories</a>
            <?php endif; ?>
        </div>

        <!-- Actions -->
        <div class="agro-navbar__actions">
            <?php if ($portal_type === 'buyer' && $is_logged_in): ?>
                <!-- Search -->
                <form action="SearchResult.php" method="get" class="agro-search" style="max-width:280px;">
                    <span class="agro-search__icon"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="agro-search__input" placeholder="Search produce...">
                </form>

                <!-- Cart -->
                <a href="cartpage.php" class="agro-navbar__cart" title="Cart">
                    <i class="fas fa-shopping-cart" style="font-size:1.2rem;"></i>
                    <?php if (function_exists('totalItems')): ?>
                        <span class="agro-navbar__cart-badge"><?php echo totalItems(); ?></span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>

            <?php if ($is_logged_in): ?>
                <!-- User dropdown -->
                <div class="agro-dropdown">
                    <div class="agro-navbar__avatar" data-dropdown-trigger title="<?php echo htmlspecialchars($username); ?>">
                        <?php echo $initials; ?>
                    </div>
                    <div class="agro-dropdown__menu">
                        <div style="padding: var(--space-3) var(--space-4); border-bottom: 1px solid var(--gray-100); margin-bottom: var(--space-2);">
                            <div style="font-weight: 600; font-size: var(--text-sm); color: var(--text-primary);"><?php echo htmlspecialchars($username); ?></div>
                            <div style="font-size: var(--text-xs); color: var(--text-tertiary);"><?php echo ucfirst($portal_type); ?> Account</div>
                        </div>
                        <?php if ($portal_type === 'farmer'): ?>
                            <a href="FarmerProfile.php" class="agro-dropdown__item"><i class="fas fa-user"></i> Profile</a>
                            <a href="EditProfile.php" class="agro-dropdown__item"><i class="fas fa-edit"></i> Edit Profile</a>
                            <a href="ChangePassword.php" class="agro-dropdown__item"><i class="fas fa-lock"></i> Change Password</a>
                            <a href="CallCenter.php" class="agro-dropdown__item"><i class="fas fa-headset"></i> Support</a>
                        <?php elseif ($portal_type === 'buyer'): ?>
                            <a href="BuyerProfile.php" class="agro-dropdown__item"><i class="fas fa-user"></i> Profile</a>
                            <a href="BuyerEditProfile.php" class="agro-dropdown__item"><i class="fas fa-edit"></i> Edit Profile</a>
                            <a href="saveforlater.php" class="agro-dropdown__item"><i class="fas fa-heart"></i> Saved Items</a>
                            <a href="InsertProduct.php" class="agro-dropdown__item"><i class="fas fa-plus"></i> Share Food</a>
                        <?php endif; ?>
                        <div class="agro-dropdown__divider"></div>
                        <a href="<?php echo $base; ?>Includes/logout.php" class="agro-dropdown__item" style="color: var(--agro-red-500);">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <?php if ($portal_type === 'public'): ?>
                    <a href="<?php echo $base; ?>auth/FarmerLogin.php" class="agro-btn agro-btn--primary agro-btn--sm">Farmer Login</a>
                    <a href="<?php echo $base; ?>auth/BuyerLogin.php" class="agro-btn agro-btn--secondary agro-btn--sm">Buyer Login</a>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Mobile Toggle -->
            <button class="agro-navbar__toggle" aria-label="Menu">
                <span class="agro-navbar__toggle-icon"></span>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="agro-navbar__mobile-menu" id="agro-mobile-menu">
        <?php if ($portal_type === 'farmer' && $is_logged_in): ?>
            <a href="farmerHomepage.php" class="agro-navbar__mobile-link">🏠 Home</a>
            <a href="MyProducts.php" class="agro-navbar__mobile-link">📦 My Products</a>
            <a href="Orders.php" class="agro-navbar__mobile-link">🛒 Orders</a>
            <a href="Transactions.php" class="agro-navbar__mobile-link">📋 Transactions</a>
            <a href="SmartAdvisory.php" class="agro-navbar__mobile-link">🤖 AI Smart Advisory</a>
            <a href="InsertProduct.php" class="agro-navbar__mobile-link">➕ Add Product</a>
            <a href="FarmerProfile.php" class="agro-navbar__mobile-link">👤 Profile</a>
            <a href="CallCenter.php" class="agro-navbar__mobile-link">📞 Support</a>
            <a href="<?php echo $base; ?>Includes/logout.php" class="agro-navbar__mobile-link" style="color: var(--agro-red-500);">🚪 Logout</a>
        <?php elseif ($portal_type === 'buyer' && $is_logged_in): ?>
            <a href="bhome.php" class="agro-navbar__mobile-link">🏠 Home</a>
            <a href="Categories.php" class="agro-navbar__mobile-link">📂 Categories</a>
            <a href="farmers.php" class="agro-navbar__mobile-link">🧑‍🌾 Farmers</a>
            <a href="Transaction.php" class="agro-navbar__mobile-link">📋 Orders</a>
            <a href="cartpage.php" class="agro-navbar__mobile-link">🛒 Cart</a>
            <a href="saveforlater.php" class="agro-navbar__mobile-link">💚 Saved</a>
            <a href="BuyerProfile.php" class="agro-navbar__mobile-link">👤 Profile</a>
            <a href="<?php echo $base; ?>Includes/logout.php" class="agro-navbar__mobile-link" style="color: var(--agro-red-500);">🚪 Logout</a>
        <?php elseif ($portal_type === 'public'): ?>
            <a href="<?php echo $base; ?>index.html#features" class="agro-navbar__mobile-link">✨ Features</a>
            <a href="<?php echo $base; ?>index.html#user-types" class="agro-navbar__mobile-link">👥 Users</a>
            <a href="<?php echo $base; ?>index.html#testimonials" class="agro-navbar__mobile-link">📖 Stories</a>
            <div style="margin-top: var(--space-4); display: flex; flex-direction: column; gap: var(--space-3);">
                <a href="<?php echo $base; ?>auth/FarmerLogin.php" class="agro-btn agro-btn--primary agro-btn--full">Farmer Login</a>
                <a href="<?php echo $base; ?>auth/BuyerLogin.php" class="agro-btn agro-btn--secondary agro-btn--full">Buyer Login</a>
            </div>
        <?php endif; ?>
    </div>
</nav>
<?php
}
?>

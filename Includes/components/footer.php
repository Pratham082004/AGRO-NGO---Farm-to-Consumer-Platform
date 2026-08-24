<?php
/**
 * AgroNGO Modern Footer Component
 * 
 * Usage: include this file and call agro_footer($portal_type)
 * $portal_type: 'farmer' | 'buyer' | 'public'
 */

function agro_footer($portal_type = 'public') {
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
<footer class="agro-footer">
    <div class="agro-container">
        <div class="agro-footer__grid">
            <!-- Brand Column -->
            <div class="agro-footer__brand">
                <div class="agro-footer__brand-name">🌾 AgroNGO</div>
                <p class="agro-footer__desc">
                    Connecting farmers directly to consumers. Eliminating middlemen to ensure fair prices 
                    and reduce food waste across India.
                </p>
                <div class="agro-footer__social">
                    <a href="#" class="agro-footer__social-link" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="agro-footer__social-link" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="agro-footer__social-link" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="agro-footer__social-link" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="agro-footer__title">Quick Links</h4>
                <div class="agro-footer__links">
                    <a href="<?php echo $base; ?>index.html" class="agro-footer__link">Home</a>
                    <a href="<?php echo $base; ?>auth/FarmerLogin.php" class="agro-footer__link">Farmer Portal</a>
                    <a href="<?php echo $base; ?>auth/BuyerLogin.php" class="agro-footer__link">Buyer Portal</a>
                    <a href="<?php echo $base; ?>admins/index.php" class="agro-footer__link">Admin Panel</a>
                </div>
            </div>

            <!-- Resources -->
            <div>
                <h4 class="agro-footer__title">Resources</h4>
                <div class="agro-footer__links">
                    <a href="#" class="agro-footer__link">Help Center</a>
                    <a href="#" class="agro-footer__link">Guidelines</a>
                    <a href="#" class="agro-footer__link">Privacy Policy</a>
                    <a href="#" class="agro-footer__link">Terms of Service</a>
                </div>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="agro-footer__title">Contact Us</h4>
                <div class="agro-footer__links">
                    <span class="agro-footer__link"><i class="fas fa-envelope" style="margin-right:8px;"></i>support@agrongo.com</span>
                    <span class="agro-footer__link"><i class="fas fa-phone" style="margin-right:8px;"></i>+91 98765 43210</span>
                    <span class="agro-footer__link"><i class="fas fa-map-marker-alt" style="margin-right:8px;"></i>India</span>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="agro-footer__bottom">
            <span>&copy; <?php echo date('Y'); ?> AgroNGO — Built for Indian Farmers</span>
            <div class="agro-footer__payment">
                <span style="font-size: var(--text-xs); color: var(--gray-500); margin-right: 8px;">Payments:</span>
                <img src="<?php echo $base; ?>Images/Website/paytm1.jpg" alt="Paytm" style="height: 24px; border-radius: 4px;">
                <img src="<?php echo $base; ?>Images/Website/cod.jpg" alt="COD" style="height: 24px; border-radius: 4px;">
            </div>
        </div>
    </div>
</footer>
<?php
}
?>

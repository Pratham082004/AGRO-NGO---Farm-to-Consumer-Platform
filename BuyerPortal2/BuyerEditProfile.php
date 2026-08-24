<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../Includes/db.php");
include("../Includes/components/navbar.php");
include("../Includes/components/footer.php");

$sessphonenumber = $_SESSION['phonenumber'] ?? null;
$name = ''; $pan = ''; $phone = ''; $address = ''; $account = ''; $comp = ''; $license = ''; $mail = ''; $user = '';

if ($sessphonenumber) {
    $sql = "select * from buyerregistration where buyer_phone = '$sessphonenumber'";
    $run_query = mysqli_query($con, $sql);
    if ($run_query && $row = mysqli_fetch_array($run_query)) {
        $name = $row['buyer_name'];
        $pan = $row['buyer_pan'];
        $phone = $row['buyer_phone'];
        $address = $row['buyer_addr'];
        $account = $row['buyer_bank'];
        $comp = $row['buyer_comp'];
        $license = $row['buyer_license'];
        $mail = $row['buyer_mail'];
        $user = $row['buyer_username'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile — Buyer Portal</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- Shared Navbar -->
    <?php agro_navbar('buyer', 'profile'); ?>

    <!-- Page Header -->
    <div class="agro-page-header" style="background: var(--surface-primary); border-bottom: 1px solid var(--gray-200);">
        <div class="agro-container">
            <div class="agro-page-header__breadcrumb">
                <a href="bhome.php">Home</a> / <a href="BuyerProfile.php">Profile</a> / <span>Edit Profile</span>
            </div>
            <h1 class="agro-page-header__title">Edit Buyer Profile</h1>
            <p class="agro-page-header__desc">Update your business contact details, username, and delivery address.</p>
        </div>
    </div>

    <!-- Main Section -->
    <div class="agro-container agro-section">
        <div style="max-width: 760px; margin: 0 auto;">
            
            <?php if ($sessphonenumber): ?>
                <div class="agro-card agro-p-8">
                    <form action="BuyerEditProfile.php" method="post">
                        
                        <div class="agro-grid agro-grid-2" style="gap: var(--space-4);">
                            <div class="agro-form-group">
                                <label class="agro-label"><i class="fas fa-user" style="margin-right:6px; color:var(--color-secondary);"></i>Buyer Name (Read-only)</label>
                                <input type="text" class="agro-input" value="<?php echo htmlspecialchars($name); ?>" disabled />
                            </div>

                            <div class="agro-form-group">
                                <label class="agro-label"><i class="fas fa-envelope" style="margin-right:6px; color:var(--color-secondary);"></i>Email Address (Read-only)</label>
                                <input type="text" class="agro-input" value="<?php echo htmlspecialchars($mail); ?>" disabled />
                            </div>
                        </div>

                        <div class="agro-grid agro-grid-2" style="gap: var(--space-4);">
                            <div class="agro-form-group">
                                <label class="agro-label"><i class="fas fa-building" style="margin-right:6px; color:var(--color-secondary);"></i>Company Name (Read-only)</label>
                                <input type="text" class="agro-input" value="<?php echo htmlspecialchars($comp); ?>" disabled />
                            </div>

                            <div class="agro-form-group">
                                <label class="agro-label"><i class="fas fa-file-contract" style="margin-right:6px; color:var(--color-secondary);"></i>Trade License (Read-only)</label>
                                <input type="text" class="agro-input" value="<?php echo htmlspecialchars($license); ?>" disabled />
                            </div>
                        </div>

                        <div class="agro-grid agro-grid-2" style="gap: var(--space-4);">
                            <div class="agro-form-group">
                                <label class="agro-label" for="username"><i class="fas fa-user-tag" style="margin-right:6px; color:var(--color-secondary);"></i>Username</label>
                                <input type="text" id="username" name="username" class="agro-input" value="<?php echo htmlspecialchars($user); ?>" required />
                            </div>

                            <div class="agro-form-group">
                                <label class="agro-label" for="phonenumber"><i class="fas fa-phone-alt" style="margin-right:6px; color:var(--color-secondary);"></i>Phone Number</label>
                                <input type="text" id="phonenumber" name="phonenumber" class="agro-input" value="<?php echo htmlspecialchars($phone); ?>" required />
                            </div>
                        </div>

                        <div class="agro-form-group">
                            <label class="agro-label" for="bank"><i class="fas fa-university" style="margin-right:6px; color:var(--color-secondary);"></i>Bank Account Number</label>
                            <input type="text" id="bank" name="bank" class="agro-input" value="<?php echo htmlspecialchars($account); ?>" required />
                        </div>

                        <div class="agro-form-group">
                            <label class="agro-label" for="address"><i class="fas fa-location-dot" style="margin-right:6px; color:var(--color-secondary);"></i>Delivery Address</label>
                            <textarea id="address" name="address" class="agro-textarea" rows="3" required><?php echo htmlspecialchars($address); ?></textarea>
                        </div>

                        <div class="agro-flex-between agro-mt-6" style="flex-wrap: wrap; gap: var(--space-4);">
                            <button type="submit" name="confirm" class="agro-btn agro-btn--secondary agro-btn--lg">
                                <i class="fas fa-check-circle"></i> Save Profile Changes
                            </button>
                            <a href="BuyerChangePassword.php" class="agro-btn agro-btn--outline">
                                <i class="fas fa-key"></i> Change Password
                            </a>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <div class="agro-empty">
                    <div class="agro-empty__icon">🔒</div>
                    <h3 class="agro-empty__title">Authentication Required</h3>
                    <p class="agro-empty__desc">Please sign in to edit your profile details.</p>
                    <a href="../auth/BuyerLogin.php" class="agro-btn agro-btn--secondary agro-btn--lg">Buyer Login</a>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- Shared Footer -->
    <?php agro_footer('buyer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

<?php
if (isset($_POST['confirm'])) {
    $phone = mysqli_real_escape_string($con, $_POST['phonenumber']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $account = mysqli_real_escape_string($con, $_POST['bank']);
    $user = mysqli_real_escape_string($con, $_POST['username']);

    $query = "update buyerregistration 
              set buyer_phone = '$phone', buyer_username = '$user', 
              buyer_addr = '$address', buyer_bank = '$account' 
              where buyer_id in 
              (select buyer_id from buyerregistration 
              where buyer_phone='$sessphonenumber')";

    $run = mysqli_query($con, $query);

    $_SESSION['phonenumber'] = $phone;
    echo "<script>window.open('BuyerProfile.php','_self')</script>";
}
?>

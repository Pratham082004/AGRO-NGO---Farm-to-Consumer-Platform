<?php
include("../Includes/db.php");
include("../Functions/functions.php");
include("../Includes/components/navbar.php");
include("../Includes/components/footer.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password — Buyer — AgroNGO</title>
    <meta name="description" content="Reset your AgroNGO Buyer account password.">
    <link rel="stylesheet" href="../Styles/agronogo-design.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body style="display: flex; flex-direction: column; min-height: 100vh;">

    <!-- Shared Header -->
    <?php agro_navbar('public', 'buyer'); ?>

    <!-- Auth Container -->
    <div class="agro-auth" style="flex: 1; padding: var(--space-12) var(--space-4);">
        <div class="agro-auth__card">
            <div class="agro-auth__logo">
                <div class="agro-auth__logo-icon">🔑</div>
            </div>

            <h1 class="agro-auth__title">Reset Password</h1>
            <p class="agro-auth__subtitle">Enter your details to update your buyer account password</p>

            <form action="BuyerForgotPassword.php" method="post" id="buyer-forgot-form">
                <div class="agro-form-group">
                    <label class="agro-label" for="phonenumber">
                        <i class="fas fa-phone-alt" style="margin-right: 6px;"></i>Phone Number
                    </label>
                    <input type="text" id="phonenumber" class="agro-input" name="phonenumber" placeholder="Registered phone number" required>
                </div>

                <div class="agro-form-group">
                    <label class="agro-label" for="pan">
                        <i class="fas fa-id-card" style="margin-right: 6px;"></i>PAN Number
                    </label>
                    <input type="text" id="pan" class="agro-input" name="pan" placeholder="Your PAN number" required>
                </div>

                <div class="agro-form-group">
                    <label class="agro-label" for="password">
                        <i class="fas fa-lock" style="margin-right: 6px;"></i>New Password
                    </label>
                    <input type="password" id="password" class="agro-input" name="password" placeholder="Enter new password" required>
                </div>

                <div class="agro-form-group">
                    <label class="agro-label" for="confirmpassword">
                        <i class="fas fa-lock" style="margin-right: 6px;"></i>Confirm Password
                    </label>
                    <input type="password" id="confirmpassword" class="agro-input" name="confirmpassword" placeholder="Confirm new password" required>
                </div>

                <button type="submit" name="register" class="agro-btn agro-btn--secondary agro-btn--full agro-btn--lg">
                    <i class="fas fa-key"></i> Update Password
                </button>

                <div class="agro-auth__links" style="justify-content: center; margin-top: var(--space-5);">
                    <a href="BuyerLogin.php"><i class="fas fa-arrow-left" style="margin-right:4px;"></i> Back to Login</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Shared Footer -->
    <?php agro_footer('public'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

<?php
if (isset($_POST['register'])) {
    $phonenumber = mysqli_real_escape_string($con, $_POST['phonenumber']);
    $pan = mysqli_real_escape_string($con, $_POST['pan']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirmpassword = mysqli_real_escape_string($con, $_POST['confirmpassword']);

    $query = "select * from buyerregistration where buyer_phone = '$phonenumber' and buyer_pan = '$pan'";
    $run_query = mysqli_query($con, $query);
    $count_rows = mysqli_num_rows($run_query);

    $ciphering = "AES-128-CTR";
    $iv_length = openssl_cipher_iv_length($ciphering);
    $options = 0;
    $encryption_iv = '2345678910111211';
    $encryption_key = "DE";

    $encryption = openssl_encrypt(
        $password,
        $ciphering,
        $encryption_key,
        $options,
        $encryption_iv
    );

    if (strcmp($password, $confirmpassword) == 0) {
        if ($count_rows != 0) {
            $update_query = "update buyerregistration set buyer_password = '$encryption' 
                                    where buyer_phone = '$phonenumber' and buyer_pan = '$pan' ";

            $run_query = mysqli_query($con, $update_query);
            echo "<script>alert('Password Changed Successfully');</script>";
            echo "<script>window.open('BuyerLogin.php','_self')</script>";
        } else {
            echo "<script>alert('Entered Details Do Not Match Our Records');</script>";
        }
    } else {
        echo "<script>alert('Password and Confirm Password Do Not Match');</script>";
    }
}
?>

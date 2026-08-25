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
    <title>Farmer Login — AgroNGO</title>
    <meta name="description" content="Login to your AgroNGO Farmer account to manage products, track orders, and connect with buyers.">
    <link rel="stylesheet" href="../Styles/agronogo-design.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body style="display: flex; flex-direction: column; min-height: 100vh;">

    <!-- Shared Header -->
    <?php agro_navbar('public', 'farmer'); ?>

    <!-- Auth Container -->
    <div class="agro-auth" style="flex: 1; padding: var(--space-12) var(--space-4);">
        <div class="agro-auth__card">
            <!-- Logo -->
            <div class="agro-auth__logo">
                <div class="agro-auth__logo-icon">🌾</div>
            </div>

            <h1 class="agro-auth__title">Welcome Back, Farmer</h1>
            <p class="agro-auth__subtitle">Sign in to manage your products and orders</p>

            <!-- Login Form -->
            <form name="my-form" action="FarmerLogin.php" method="post" id="farmer-login-form">
                <div class="agro-form-group">
                    <label class="agro-label" for="phone_number">
                        <i class="fas fa-phone-alt" style="margin-right: 6px;"></i>Phone Number
                    </label>
                    <input type="text" id="phone_number" class="agro-input" name="phonenumber" placeholder="Enter your phone number" required>
                </div>

                <div class="agro-form-group">
                    <label class="agro-label" for="password">
                        <i class="fas fa-lock" style="margin-right: 6px;"></i>Password
                    </label>
                    <div style="position: relative;">
                        <input id="password" class="agro-input" type="password" name="password" placeholder="Enter your password" required style="padding-right: 48px;">
                        <button type="button" data-toggle-password="#password" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:1.1rem;color:var(--gray-400);">👁️</button>
                    </div>
                </div>

                <button type="submit" name="login" value="Login" class="agro-btn agro-btn--primary agro-btn--full agro-btn--lg">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>

                <div class="agro-auth__links">
                    <a href="FarmerForgotPassword.php">Forgot password?</a>
                    <a href="FarmerRegister.php">Create account</a>
                </div>

                <div class="agro-auth__divider">or</div>

                <a href="BuyerLogin.php" class="agro-btn agro-btn--outline agro-btn--full" style="color: var(--gray-300); border-color: rgba(255,255,255,0.2);">
                    <i class="fas fa-shopping-bag"></i> Sign in as Buyer instead
                </a>
            </form>
        </div>
    </div>

    <!-- Shared Footer -->
    <?php agro_footer('public'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

<?php
if (isset($_POST['login'])) {

	$phonenumber = mysqli_real_escape_string($con, $_POST['phonenumber']);
	$password = mysqli_real_escape_string($con, $_POST['password']);

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

	$query = "select * from farmerregistration where farmer_phone = '$phonenumber' and farmer_password = '$encryption'";
	$run_query = mysqli_query($con, $query);
	$count_rows = mysqli_num_rows($run_query);
	if ($count_rows == 0) {
		echo "<script>alert('Please Enter Valid Details');</script>";
		echo "<script>window.open('FarmerLogin.php','_self')</script>";
		exit();
	} else {
		while ($row = mysqli_fetch_array($run_query)) {
			$id = $row['farmer_id'];
		}
		$_SESSION['phonenumber'] = $phonenumber;
		echo "<script>window.open('../FarmerPortal/farmerHomepage.php','_self')</script>";
		exit();
	}
}

?>

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
    <title>Buyer Registration — AgroNGO</title>
    <meta name="description" content="Create a new AgroNGO Buyer account to purchase fresh agricultural produce directly from local farmers.">
    <link rel="stylesheet" href="../Styles/agronogo-design.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body style="display: flex; flex-direction: column; min-height: 100vh;">

    <!-- Shared Header -->
    <?php agro_navbar('public', 'buyer'); ?>

    <!-- Auth Container -->
    <div class="agro-auth" style="flex: 1; padding: var(--space-12) var(--space-4);">
        <div class="agro-auth__card" style="max-width: 680px;">
            <div class="agro-auth__logo">
                <div class="agro-auth__logo-icon">🛒</div>
            </div>

            <h1 class="agro-auth__title">Buyer Registration</h1>
            <p class="agro-auth__subtitle">Create an account to buy fresh produce directly from farmers</p>

            <form name="my-form" action="BuyerRegistration.php" method="post" id="buyer-register-form">
                <div class="agro-grid agro-grid-2" style="gap: var(--space-4);">
                    <div class="agro-form-group">
                        <label class="agro-label" for="full_name"><i class="fas fa-user" style="margin-right:6px;"></i>Full Name</label>
                        <input type="text" id="full_name" class="agro-input" name="name" placeholder="Enter Full Name" required>
                    </div>

                    <div class="agro-form-group">
                        <label class="agro-label" for="phone_number"><i class="fas fa-phone-alt" style="margin-right:6px;"></i>Phone Number</label>
                        <input type="text" id="phone_number" class="agro-input" name="phonenumber" placeholder="Phone Number" required>
                    </div>
                </div>

                <div class="agro-grid agro-grid-2" style="gap: var(--space-4);">
                    <div class="agro-form-group">
                        <label class="agro-label" for="email_address"><i class="far fa-envelope" style="margin-right:6px;"></i>E-Mail Address</label>
                        <input type="email" id="email_address" class="agro-input" name="mail" placeholder="E-Mail ID" required>
                    </div>

                    <div class="agro-form-group">
                        <label class="agro-label" for="user_name"><i class="fas fa-user-tag" style="margin-right:6px;"></i>User Name</label>
                        <input type="text" id="user_name" class="agro-input" name="username" placeholder="Choose Username" required>
                    </div>
                </div>

                <div class="agro-form-group">
                    <label class="agro-label" for="present_address"><i class="fas fa-home" style="margin-right:6px;"></i>Present Address</label>
                    <textarea id="present_address" class="agro-textarea" name="address" placeholder="Complete Address" rows="3" required></textarea>
                </div>

                <div class="agro-grid agro-grid-2" style="gap: var(--space-4);">
                    <div class="agro-form-group">
                        <label class="agro-label" for="campany_name"><i class="fas fa-building" style="margin-right:6px;"></i>Company Name</label>
                        <input type="text" id="campany_name" class="agro-input" name="company_name" placeholder="Company Name" required>
                    </div>

                    <div class="agro-form-group">
                        <label class="agro-label" for="lisence"><i class="fas fa-id-badge" style="margin-right:6px;"></i>License No.</label>
                        <input type="text" id="lisence" class="agro-input" name="license" placeholder="License Number" required>
                    </div>
                </div>

                <div class="agro-grid agro-grid-2" style="gap: var(--space-4);">
                    <div class="agro-form-group">
                        <label class="agro-label" for="account1"><i class="fas fa-university" style="margin-right:6px;"></i>Bank Account No.</label>
                        <input type="text" id="account1" class="agro-input" name="account" placeholder="Bank Account Number" required>
                    </div>

                    <div class="agro-form-group">
                        <label class="agro-label" for="account2"><i class="fas fa-id-card" style="margin-right:6px;"></i>PAN Number</label>
                        <input type="text" id="account2" class="agro-input" name="pan" placeholder="PAN Number" required>
                    </div>
                </div>

                <div class="agro-grid agro-grid-2" style="gap: var(--space-4);">
                    <div class="agro-form-group">
                        <label class="agro-label" for="p1"><i class="fas fa-lock" style="margin-right:6px;"></i>Password</label>
                        <input id="p1" class="agro-input" type="password" name="password" placeholder="Create Password" required>
                    </div>

                    <div class="agro-form-group">
                        <label class="agro-label" for="p2"><i class="fas fa-lock" style="margin-right:6px;"></i>Confirm Password</label>
                        <input id="p2" class="agro-input" type="password" name="confirmpassword" placeholder="Confirm Password" required>
                    </div>
                </div>

                <button type="submit" class="agro-btn agro-btn--secondary agro-btn--full agro-btn--lg" name="register" value="Register" style="margin-top: var(--space-4);">
                    <i class="fas fa-user-plus"></i> Register as Buyer
                </button>

                <div class="agro-auth__links" style="justify-content: center; margin-top: var(--space-5);">
                    Already have an account? <a href="BuyerLogin.php" style="margin-left: 6px;">Sign In</a>
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
$ciphering = "AES-128-CTR";
$iv_length = openssl_cipher_iv_length($ciphering);
$options = 0;
$encryption_iv = '2345678910111211';
$encryption_key = "DE";

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $phonenumber = mysqli_real_escape_string($con, $_POST['phonenumber']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $account = mysqli_real_escape_string($con, $_POST['account']);
    $pan = mysqli_real_escape_string($con, $_POST['pan']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirmpassword = mysqli_real_escape_string($con, $_POST['confirmpassword']);
    $mail = mysqli_real_escape_string($con, $_POST['mail']);
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $company_name = mysqli_real_escape_string($con, $_POST['company_name']);
    $license = mysqli_real_escape_string($con, $_POST['license']);

    $encryption = openssl_encrypt(
        $password,
        $ciphering,
        $encryption_key,
        $options,
        $encryption_iv
    );

    if (strcmp($password, $confirmpassword) == 0) {
        $query = "insert into buyerregistration (buyer_name,buyer_phone,
                buyer_email,buyer_username,buyer_password,buyer_addr,
                buyer_comp,buyer_license,buyer_bank,buyer_pan) 
                values ('$name','$phonenumber','$mail','$username',
                '$encryption','$address','$company_name','$license',
                '$account','$pan')";

        $run_register_query = mysqli_query($con, $query);
        echo "<script>alert('Buyer Account created successfully!');</script>";
        echo "<script>window.open('BuyerLogin.php','_self')</script>";
    } else {
        echo "<script>alert('Password and Confirm Password should match');</script>";
    }
}
?>

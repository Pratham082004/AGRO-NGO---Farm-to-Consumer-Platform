<?php
session_start();
include('includes/config.php');
include("../Functions/functions.php");
include("../Includes/components/navbar.php");
include("../Includes/components/footer.php");

if(isset($_POST['login']))
{
    $uname=$_POST['username'];
    $password=md5($_POST['password']);
    $sql ="SELECT UserName,Password FROM admin WHERE UserName=:uname and Password=:password";
    $query= $dbh -> prepare($sql);
    $query-> bindParam(':uname', $uname, PDO::PARAM_STR);
    $query-> bindParam(':password', $password, PDO::PARAM_STR);
    $query-> execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    if($query->rowCount() > 0)
    {
        $_SESSION['alogin']=$_POST['username'];
        echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
    } else {
        echo "<script>alert('Invalid Admin Credentials');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — AgroNGO</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body style="display: flex; flex-direction: column; min-height: 100vh;">

    <!-- Shared Header -->
    <?php agro_navbar('public', 'admin'); ?>

    <!-- Auth Container -->
    <div class="agro-auth" style="flex: 1; padding: var(--space-12) var(--space-4);">
        <div class="agro-auth__card">
            <div class="agro-auth__logo">
                <div class="agro-auth__logo-icon">🛡️</div>
            </div>

            <h1 class="agro-auth__title">Admin Control Portal</h1>
            <p class="agro-auth__subtitle">Sign in to manage users, packages, and platform settings</p>

            <form method="post" id="admin-login-form">
                <div class="agro-form-group">
                    <label class="agro-label" for="username">
                        <i class="fas fa-user-shield" style="margin-right:6px;"></i>Username
                    </label>
                    <input type="text" id="username" class="agro-input" name="username" placeholder="Admin username" required>
                </div>

                <div class="agro-form-group">
                    <label class="agro-label" for="password">
                        <i class="fas fa-lock" style="margin-right:6px;"></i>Password
                    </label>
                    <input type="password" id="password" class="agro-input" name="password" placeholder="Admin password" required>
                </div>

                <button type="submit" name="login" value="Sign In" class="agro-btn agro-btn--primary agro-btn--full agro-btn--lg">
                    <i class="fas fa-right-to-bracket"></i> Sign In to Admin Panel
                </button>

                <div class="agro-auth__links" style="justify-content: center; margin-top: var(--space-5);">
                    <a href="../index.html"><i class="fas fa-arrow-left" style="margin-right:4px;"></i> Return to Homepage</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Shared Footer -->
    <?php agro_footer('public'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

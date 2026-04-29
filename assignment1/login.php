<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login page for Cacti-Succulent Kuching">
    <meta name="keywords" content="login, account, cacti, succulent">
    <meta name="author" content="Rachael, Eleona, Amber">
    
    <title>Login | Cacti-Succulent Kuching</title>
    
    <link rel="stylesheet" href="styles/style.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Montserrat:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
</head>

<body class="login-page">

<!-- HEADER & NAVIGATION -->
<?php include 'header.inc'; ?>
<?php include 'menu.inc'; ?>

    <!-- LOGIN FORM SECTION -->
    <main class="form-main">
        <div class="form-header">
            <h1>Login</h1>
            <p>Access your account securely</p>
        </div>

        <div class="form-card">
            <form action="process_login.php" method="post">

                <fieldset>
                    <legend>Account Details</legend>

                    <div class="form-row">
                        <div class="input-group full-width">
                            <label for="login">Username</label>
                            <input type="text" id="login" name="login" maxlength="10" pattern="[A-Za-z]+" title="Maximum 10 alphabetical characters only" placeholder="Enter your username (letters only)" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group full-width">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" maxlength="25" pattern="[A-Za-z]+" title="Maximum 25 alphabetical characters only" placeholder="Enter your password (letters only)" required>
                        </div>
                    </div>
                </fieldset>

                <div class="button-group">
                    <input type="submit" value="Login">
                    <input type="reset" value="Clear Form">
                </div>

                <div class="form-redirect">
                    <p>Don't have an account? <a href="register.html">Register with us</a></p>
                </div>

            </form>
        </div>
    </main>

<!-- WEBSITE FOOTER & COPYRIGHT -->
<?php include 'footer.inc'; ?>

<!-- BACK TO TOP BUTTON -->
<a href="#" class="back-to-top">▲</a>

</body>
</html>
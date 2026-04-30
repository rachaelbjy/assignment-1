<?php
session_start(); 
$d = isset($_SESSION['reg_data']) ? $_SESSION['reg_data'] : [];
$v_fname = isset($d['fname']) ? htmlspecialchars($d['fname']) : '';
$v_lname = isset($d['lname']) ? htmlspecialchars($d['lname']) : '';
$v_email = isset($d['email']) ? htmlspecialchars($d['email']) : '';
$v_phone = isset($d['phone']) ? htmlspecialchars($d['phone']) : '';
$v_username = isset($d['username']) ? htmlspecialchars($d['username']) : '';
$v_password = isset($d['password']) ? htmlspecialchars($d['password']) : '';
$v_contact = isset($d['contact_pref']) ? $d['contact_pref'] : '';
$v_street = isset($d['street']) ? htmlspecialchars($d['street']) : '';
$v_city = isset($d['city']) ? htmlspecialchars($d['city']) : '';
$v_state = isset($d['state']) ? $d['state'] : '';
$v_postcode = isset($d['postcode']) ? htmlspecialchars($d['postcode']) : '';
$v_terms = isset($d['terms']) ? $d['terms'] : false;

unset($_SESSION['reg_data']); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Registration page for Cacti-Succulent Kuching">
    <meta name="keywords" content="register, user, plant shop, account">
    <meta name="author" content="Rachael, Eleona, Amber">
    
    <title>Register | Cacti-Succulent Kuching</title>
    
    <link rel="stylesheet" href="styles/style.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Montserrat:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
</head>

<body class="register-page">

<?php include 'header.inc'; ?>

    <main class="form-main">
        <div class="form-header">
            <h1>Register</h1>
            <p>Create your account to enjoy our services</p>
        </div>

        <div class="form-card">
            <form action="process_register.php" method="post">

                <fieldset>
                    <legend>Personal Information</legend>
                    <div class="form-row">
                        <div class="input-group">
                            <label for="fname">First Name</label>
                            <input type="text" id="fname" name="fname" value="<?php echo $v_fname; ?>" maxlength="25" pattern="[A-Za-z]+" title="Maximum 25 characters" placeholder="e.g. John" required>
                        </div>
                        <div class="input-group">
                            <label for="lname">Last Name</label>
                            <input type="text" id="lname" name="lname" value="<?php echo $v_lname; ?>" maxlength="25" pattern="[A-Za-z]+" title="Maximum 25 characters" placeholder="e.g. Doe" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="<?php echo $v_email; ?>" placeholder="e.g. hello@example.com" required>
                        </div>
                        <div class="input-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo $v_phone; ?>" maxlength="11" pattern="[0-9]+" title="Digits only" placeholder="e.g. 0128884444" required>
                        </div>
                    </div>

                   <div class="form-row">
                        <div class="input-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" value="<?php echo $v_username; ?>" maxlength="10" pattern="[A-Za-z]+" title="Letters only" placeholder="Username" required>
                        </div>
                        <div class="input-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" value="<?php echo $v_password; ?>" maxlength="25" placeholder="Create a strong password" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group full-width">
                            <label>Preferred Contact</label>
                            <div class="radio-group">
                                <input type="radio" id="pref-email" name="contact_pref" value="email" <?php echo ($v_contact == 'email') ? 'checked' : ''; ?> required>
                                <label for="pref-email" class="inline-label">Email</label>
                                <input type="radio" id="pref-phone" name="contact_pref" value="phone" <?php echo ($v_contact == 'phone') ? 'checked' : ''; ?> required>
                                <label for="pref-phone" class="inline-label">Phone</label>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Address</legend>
                    <div class="form-row">
                        <div class="input-group full-width">
                            <label for="street">Street Address</label>
                            <input type="text" id="street" name="street" value="<?php echo $v_street; ?>" maxlength="40" placeholder="e.g. 123 Plant Street" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group">
                            <label for="city">City/Town</label>
                            <input type="text" id="city" name="city" value="<?php echo $v_city; ?>" maxlength="20" placeholder="e.g. Kuching" required>
                        </div>
                        <div class="input-group">
                            <label for="state">State</label>
                            <select id="state" name="state" required>
                                <option value="" disabled <?php echo ($v_state == '') ? 'selected' : ''; ?>>Select State</option>
                                <?php
                                $states = ['Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Perak', 'Perlis', 'Pulau Pinang', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu', 'Kuala Lumpur', 'Labuan', 'Putrajaya'];
                                foreach ($states as $s) {
                                    $sel = ($v_state == $s) ? 'selected' : '';
                                    echo "<option value=\"$s\" $sel>$s</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group full-width">
                            <label for="postcode">Postcode</label>
                            <input type="text" id="postcode" name="postcode" value="<?php echo $v_postcode; ?>" maxlength="5" pattern="[0-9]{5}" title="Exactly 5 digits" placeholder="e.g. 93000" required>
                        </div>
                    </div>
                </fieldset>

                <div class="form-row checkbox-row">
                    <div class="input-group full-width checkbox-group">
                        <input type="checkbox" id="terms_reg" name="terms_reg" <?php echo $v_terms ? 'checked' : ''; ?> required>
                        <label for="terms_reg" class="inline-label">I agree to the Terms of Service and Privacy Policy.</label>
                    </div>
                </div>

                <div class="button-group">
                    <input type="submit" value="Register">
                    <input type="reset" value="Clear Form">
                </div>
            </form>
        </div>
    </main>

<?php include 'footer.inc'; ?>
<a href="#" class="back-to-top">▲</a>

<?php
if (isset($_GET['error'])) {
    $error_type = $_GET['error'];
    echo "<script>
        window.onload = function() {
            var targetInput = null;
            var errorMsg = '';
            
            if ('$error_type' === 'email_taken') {
                targetInput = document.getElementById('email');
                errorMsg = 'This Email is already registered!';
            } else if ('$error_type' === 'username_taken') {
                targetInput = document.getElementById('username');
                errorMsg = 'This Username is already taken!';
            }
            
            if (targetInput) {
                targetInput.setCustomValidity(''); 
                setTimeout(function() {
                    targetInput.setCustomValidity(errorMsg);
                    targetInput.reportValidity();
                    window.history.replaceState({}, document.title, window.location.pathname);
                }, 100);

                targetInput.addEventListener('input', function() {
                    targetInput.setCustomValidity('');
                });
            }
        };
    </script>";
}
?>
</body>
</html>
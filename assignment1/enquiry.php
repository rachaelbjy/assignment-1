<?php
/* Start session to retrieve previously entered enquiry data if validation fails */
session_start();

/* Retrieve saved enquiry input from session, if available */
$d = isset($_SESSION['enquiry_data']) ? $_SESSION['enquiry_data'] : [];

/* Default logged-in user information values */
$logged_in_fname = "";
$logged_in_lname = "";
$logged_in_email = "";
$logged_in_phone = "";

/* If a normal user is logged in, load saved user information from database */
if (
    isset($_SESSION['user_logged_in']) &&
    $_SESSION['user_logged_in'] === true &&
    isset($_SESSION['username'])
) {
    require_once('settings.php');

    $username = $_SESSION['username'];

    $user_sql = "SELECT fname, lname, email, phone
                 FROM `user`
                 WHERE username = ?
                 LIMIT 1";

    $user_stmt = mysqli_prepare($conn, $user_sql);
    mysqli_stmt_bind_param($user_stmt, "s", $username);
    mysqli_stmt_execute($user_stmt);
    $user_result = mysqli_stmt_get_result($user_stmt);

    if ($user_result && mysqli_num_rows($user_result) > 0) {
        $user_row = mysqli_fetch_assoc($user_result);

        $logged_in_fname = $user_row['fname'];
        $logged_in_lname = $user_row['lname'];
        $logged_in_email = $user_row['email'];
        $logged_in_phone = $user_row['phone'];
    }

    mysqli_stmt_close($user_stmt);
    mysqli_close($conn);
}

/* Load enquiry form values. If validation failed, keep submitted values. Otherwise use logged-in user details. */
$v_fname = isset($d['fname']) ? htmlspecialchars($d['fname']) : htmlspecialchars($logged_in_fname);
$v_lname = isset($d['lname']) ? htmlspecialchars($d['lname']) : htmlspecialchars($logged_in_lname);
$v_email = isset($d['email']) ? htmlspecialchars($d['email']) : htmlspecialchars($logged_in_email);
$v_phone = isset($d['phone']) ? htmlspecialchars($d['phone']) : htmlspecialchars($logged_in_phone);
$v_subject = isset($d['subject']) ? $d['subject'] : '';
$v_comments = isset($d['comments']) ? htmlspecialchars($d['comments']) : '';

/* Clear saved enquiry data after loading it */
unset($_SESSION['enquiry_data']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BASIC PAGE METADATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Enquiry page for Cacti-Succulent Kuching">
    <meta name="keywords" content="cactus, succulent, Kuching, enquiry, contact us">
    <meta name="author" content="Rachael, Eleona, Amber">
    
    <title>Enquiry | Cacti-Succulent Kuching</title>

    <!-- EXTERNAL STYLESHEETS AND ICONS -->
    <link rel="stylesheet" href="styles/style.css?v=adminfix1">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Montserrat:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
</head>

<body class="enquiry-page">

<!-- HEADER & NAVIGATION -->
<?php include 'header.inc'; ?>

    <!-- ENQUIRY FORM SECTION -->
    <main class="form-main">
        <div class="form-header">
            <h1>Enquiry Form</h1>
            <p>Send us your questions and we will get back to you soon</p>
        </div>

        <div class="form-card">
            <!-- CUSTOMER ENQUIRY FORM -->
            <form action="enquiry_process.php" method="post">
                
                <!-- PERSONAL DETAILS FIELDSET -->
                <fieldset>
                    <legend>Personal Details</legend>
                    
                    <!-- CUSTOMER NAME -->
                    <div class="form-row">
                        <div class="input-group">
                            <label for="fname">First Name</label>
                            <input type="text" id="fname" name="fname" value="<?php echo $v_fname; ?>" maxlength="25" pattern="[A-Za-z]+" placeholder="e.g. John" required>
                        </div>

                        <div class="input-group">
                            <label for="lname">Last Name</label>
                            <input type="text" id="lname" name="lname" value="<?php echo $v_lname; ?>" maxlength="25" pattern="[A-Za-z]+" placeholder="e.g. Doe" required>
                        </div>
                    </div>

                    <!-- CUSTOMER CONTACT DETAILS -->
                    <div class="form-row">
                        <div class="input-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="<?php echo $v_email; ?>" placeholder="e.g. hello@example.com" required>
                        </div>

                        <div class="input-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo $v_phone; ?>" maxlength="11" pattern="0[0-9]{9,10}" title="Phone number must start with 0 and contain 10 to 11 digits" placeholder="e.g. 0128884444" required>
                        </div>
                    </div>
                </fieldset>

                <!-- ENQUIRY DETAILS FIELDSET -->
                <fieldset>
                    <legend>Enquiry Details</legend>
                    
                    <!-- ENQUIRY SUBJECT -->
                    <div class="form-row">
                        <div class="input-group full-width">
                            <label for="subject">What can we help you with?</label>
                            <select id="subject" name="subject" required>
                                <option value="" disabled <?php echo ($v_subject == '') ? 'selected' : ''; ?>>Select a Topic</option>
                                
                                <optgroup label="General">
                                    <option value="General Inquiry" <?php echo ($v_subject == 'General Inquiry') ? 'selected' : ''; ?>>General Inquiry</option>
                                    <option value="Feedback & Suggestions" <?php echo ($v_subject == 'Feedback & Suggestions') ? 'selected' : ''; ?>>Feedback & Suggestions</option>
                                </optgroup>

                                <option disabled label="&nbsp;">&nbsp;</option> 

                                <optgroup label="Services">
                                    <option value="The Plant Hospital" <?php echo ($v_subject == 'The Plant Hospital') ? 'selected' : ''; ?>>The Plant Hospital</option>
                                    <option value="Plant Boarding" <?php echo ($v_subject == 'Plant Boarding') ? 'selected' : ''; ?>>Plant Boarding</option>
                                    <option value="Custom Terrariums" <?php echo ($v_subject == 'Custom Terrariums') ? 'selected' : ''; ?>>Custom Terrariums</option>
                                    <option value="Terrarium Workshop" <?php echo ($v_subject == 'Terrarium Workshop') ? 'selected' : ''; ?>>Terrarium Workshop</option>
                                </optgroup>

                                <option disabled label="&nbsp;">&nbsp;</option> 

                                <optgroup label="Event Door Gifts">
                                    <option value="Standard Package" <?php echo ($v_subject == 'Standard Package') ? 'selected' : ''; ?>>Standard Package</option>
                                    <option value="Luxury Package" <?php echo ($v_subject == 'Luxury Package') ? 'selected' : ''; ?>>Luxury Package</option>
                                    <option value="Premium Package" <?php echo ($v_subject == 'Premium Package') ? 'selected' : ''; ?>>Premium Package</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <!-- ENQUIRY MESSAGE -->
                    <div class="form-row">
                        <div class="input-group full-width">
                            <label for="comments">Comments</label>
                            <textarea id="comments" name="comments" rows="5" cols="50" placeholder="Type your message here..." required><?php echo $v_comments; ?></textarea>
                        </div>
                    </div>
                </fieldset>

                <!-- FORM BUTTONS -->
                <div class="button-group">
                    <input type="submit" value="Submit Enquiry">
                    <input type="reset" value="Clear Form">
                </div>
                
            </form>
        </div>
    </main>

<!-- WEBSITE FOOTER & COPYRIGHT -->
<?php include 'footer.inc'; ?>

<!-- BACK TO TOP BUTTON -->
<a href="#" class="back-to-top">▲</a>

<!-- ENQUIRY ERROR MESSAGE SCRIPT -->
<?php
if (isset($_GET['error'])) {
    $error_type = $_GET['error'];
    echo "<script>
        window.onload = function() {
            var targetInput = null;
            var errorMsg = '';

            if ('$error_type' === 'missing_fields') {
                targetInput = document.getElementById('fname');
                errorMsg = 'Please complete all required fields!';
            } else if ('$error_type' === 'invalid_fname') {
                targetInput = document.getElementById('fname');
                errorMsg = 'First name must contain letters only, maximum 25 characters!';
            } else if ('$error_type' === 'invalid_lname') {
                targetInput = document.getElementById('lname');
                errorMsg = 'Last name must contain letters only, maximum 25 characters!';
            } else if ('$error_type' === 'invalid_email') {
                targetInput = document.getElementById('email');
                errorMsg = 'Please enter a valid email address!';
            } else if ('$error_type' === 'invalid_phone') {
                targetInput = document.getElementById('phone');
                errorMsg = 'Phone number must start with 0 and contain 10 to 11 digits!';
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

                targetInput.addEventListener('change', function() {
                    targetInput.setCustomValidity('');
                });
            }
        };
    </script>";
}
?>

</body>
</html>
<?php
/* Start session to retrieve previously entered enquiry data if validation fails */
session_start();

/* Retrieve saved enquiry input from session, if available */
$d = isset($_SESSION['enquiry_data']) ? $_SESSION['enquiry_data'] : [];
$v_fname = isset($d['fname']) ? htmlspecialchars($d['fname']) : '';
$v_lname = isset($d['lname']) ? htmlspecialchars($d['lname']) : '';
$v_email = isset($d['email']) ? htmlspecialchars($d['email']) : '';
$v_phone = isset($d['phone']) ? htmlspecialchars($d['phone']) : '';
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
                                    <option value="general" <?php echo ($v_subject == 'general') ? 'selected' : ''; ?>>General Inquiry</option>
                                    <option value="feedback" <?php echo ($v_subject == 'feedback') ? 'selected' : ''; ?>>Feedback & Suggestions</option>
                                </optgroup>

                                <option disabled label="&nbsp;">&nbsp;</option> 

                                <optgroup label="Services">
                                    <option value="hospital" <?php echo ($v_subject == 'hospital') ? 'selected' : ''; ?>>The Plant Hospital</option>
                                    <option value="boarding" <?php echo ($v_subject == 'boarding') ? 'selected' : ''; ?>>Plant Boarding</option>
                                    <option value="custom-terrarium" <?php echo ($v_subject == 'custom-terrarium') ? 'selected' : ''; ?>>Custom Terrariums</option>
                                    <option value="workshop" <?php echo ($v_subject == 'workshop') ? 'selected' : ''; ?>>Terrarium Workshop</option>
                                </optgroup>

                                <option disabled label="&nbsp;">&nbsp;</option> 

                                <optgroup label="Event Door Gifts">
                                    <option value="gift-standard" <?php echo ($v_subject == 'gift-standard') ? 'selected' : ''; ?>>Standard Package</option>
                                    <option value="gift-luxury" <?php echo ($v_subject == 'gift-luxury') ? 'selected' : ''; ?>>Luxury Package</option>
                                    <option value="gift-premium" <?php echo ($v_subject == 'gift-premium') ? 'selected' : ''; ?>>Premium Package</option>
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
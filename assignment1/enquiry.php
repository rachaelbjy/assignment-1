<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Enquiry page for Cacti-Succulent Kuching">
    <meta name="keywords" content="cactus, succulent, Kuching, enquiry, contact us">
    <meta name="author" content="Rachael, Eleona, Amber">
    
    <title>Enquiry | Cacti-Succulent Kuching</title>
    
    <link rel="stylesheet" href="styles/style.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Montserrat:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
</head>

<body class="enquiry-page">

<!-- HEADER & NAVIGATION -->
<?php include 'header.inc'; ?>
<?php include 'menu.inc'; ?>

    <!-- ENQUIRY FORM SECTION -->
    <main class="form-main">
        <div class="form-header">
            <h1>Enquiry Form</h1>
            <p>Send us your questions and we will get back to you soon</p>
        </div>

        <div class="form-card">
            <form action="process_enquiry.php" method="post">
                
                <fieldset>
                    <legend>Personal Details</legend>
                    
                    <div class="form-row">
                        <div class="input-group">
                            <label for="fname">First Name</label>
                            <input type="text" id="fname" name="fname" maxlength="25" pattern="[A-Za-z]+" placeholder="e.g. John" required>
                        </div>
                        <div class="input-group">
                            <label for="lname">Last Name</label>
                            <input type="text" id="lname" name="lname" maxlength="25" pattern="[A-Za-z]+" placeholder="e.g. Doe" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="e.g. hello@example.com" required>
                        </div>
                        <div class="input-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" maxlength="10" pattern="[0-9]{10}" placeholder="e.g. 0128884444" required>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Enquiry Details</legend>
                    
                    <div class="form-row">
                        <div class="input-group full-width">
                            <label for="subject">What can we help you with?</label>
                            <select id="subject" name="subject" required>
                                <option value="" disabled selected>Select a Topic</option>
                                
                                <optgroup label="General">
                                    <option value="general">General Inquiry</option>
                                    <option value="feedback">Feedback & Suggestions</option>
                                </optgroup>

                                <option disabled label="&nbsp;">&nbsp;</option> 
                                <optgroup label="Services">
                                    <option value="hospital">The Plant Hospital</option>
                                    <option value="boarding">Plant Boarding</option>
                                    <option value="custom-terrarium">Custom Terrariums</option>
                                    <option value="workshop">Terrarium Workshop</option>
                                </optgroup>

                                <option disabled label="&nbsp;">&nbsp;</option> 
                                <optgroup label="Event Door Gifts">
                                    <option value="gift-standard">Standard Package</option>
                                    <option value="gift-luxury">Luxury Package</option>
                                    <option value="gift-premium">Premium Package</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group full-width">
                            <label for="comments">Comments</label>
                            <textarea id="comments" name="comments" rows="5" cols="50" placeholder="Type your message here..." required></textarea>
                        </div>
                    </div>
                </fieldset>

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

</body>
</html>
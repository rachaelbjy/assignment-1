<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Display form for administrator to create a new user account.
*/

/* Start session to check admin login status */
session_start();

/* Redirect to login page if admin is not logged in */
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BASIC PAGE METADATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Add new user account for Cacti-Succulent Kuching">
    <meta name="keywords" content="admin, add user, create user, cacti, succulent">
    <meta name="author" content="Rachael, Eleona, Amber">

    <title>Add User | Cacti-Succulent Kuching</title>

    <!-- EXTERNAL STYLESHEETS AND ICONS -->
    <link rel="stylesheet" href="styles/style.css?v=adminfix1">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Montserrat:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
</head>

<body class="admin-page">

<!-- HEADER & NAVIGATION -->
<?php include 'header.inc'; ?>

    <!-- ADD USER SECTION -->
    <main class="form-main">
        <div class="form-header">
            <h1>Add User</h1>
            <p>Create a new registered user account</p>
        </div>

        <div class="form-card">

            <!-- ADD USER FORM -->
            <form action="add_user_process.php" method="post">
                <fieldset>
                    <legend>Account Details</legend>

                    <!-- PERSONAL INFORMATION -->
                    <div class="form-row">
                        <div class="input-group">
                            <label for="fname">First Name</label>
                            <input type="text" id="fname" name="fname" maxlength="25" pattern="[A-Za-z]+" title="Letters only, maximum 25 characters" required>
                        </div>

                        <div class="input-group">
                            <label for="lname">Last Name</label>
                            <input type="text" id="lname" name="lname" maxlength="25" pattern="[A-Za-z]+" title="Letters only, maximum 25 characters" required>
                        </div>
                    </div>

                    <!-- CONTACT INFORMATION -->
                    <div class="form-row">
                        <div class="input-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" required>
                        </div>

                        <div class="input-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" maxlength="11" title="Enter phone number" required>
                        </div>
                    </div>

                    <!-- ACCOUNT INFORMATION -->
                    <div class="form-row">
                        <div class="input-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" maxlength="10" pattern="[A-Za-z]+" title="Letters only, maximum 10 characters" required>
                        </div>

                        <div class="input-group">
                            <label for="password">Password</label>
                            <input type="text" id="password" name="password" maxlength="25" title="Password must not be empty and must be maximum 25 characters" required>
                        </div>
                    </div>

                    <!-- PREFERRED CONTACT -->
                    <div class="form-row">
                        <div class="input-group full-width">
                            <label>Preferred Contact</label>
                            <div class="radio-group">
                                <input type="radio" id="pref-email" name="contact_pref" value="email" required>
                                <label for="pref-email" class="inline-label">Email</label>

                                <input type="radio" id="pref-phone" name="contact_pref" value="phone" required>
                                <label for="pref-phone" class="inline-label">Phone</label>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <!-- ADDRESS INFORMATION -->
                <fieldset>
                    <legend>Address Information</legend>

                    <div class="form-row">
                        <div class="input-group full-width">
                            <label for="street">Street Address</label>
                            <input type="text" id="street" name="street" maxlength="40" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group">
                            <label for="city">City/Town</label>
                            <input type="text" id="city" name="city" maxlength="20" required>
                        </div>

                        <div class="input-group">
                            <label for="state">State</label>
                            <select id="state" name="state" required>
                                <option value="" disabled selected>Select State</option>
                                <?php
                                $states = ['Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Perak', 'Perlis', 'Pulau Pinang', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu', 'Kuala Lumpur', 'Labuan', 'Putrajaya'];
                                foreach ($states as $s) {
                                    echo "<option value=\"$s\">$s</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group full-width">
                            <label for="postcode">Postcode</label>
                            <input type="text" id="postcode" name="postcode" maxlength="5" pattern="[0-9]{5}" title="Exactly 5 digits" required>
                        </div>
                    </div>
                </fieldset>

                <!-- FORM BUTTONS -->
                <div class="button-group">
                    <input type="submit" value="Add User">
                    <input type="reset" value="Clear Form">
                </div>
            </form>

            <!-- ADMIN NAVIGATION BUTTONS -->
            <div class="admin-action-buttons">
                <a href="manage_users.php" class="admin-action-link">Back to Manage Users</a>
                <a href="admin_dashboard.php" class="admin-action-link">Back to Dashboard</a>
            </div>

        </div>
    </main>

<!-- WEBSITE FOOTER & COPYRIGHT -->
<?php include 'footer.inc'; ?>

<!-- BACK TO TOP BUTTON -->
<a href="#" class="back-to-top">▲</a>

</body>
</html>
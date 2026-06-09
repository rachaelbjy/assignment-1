<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Display edit form for administrator to update selected user account.
*/

/* Start session to check admin login status */
session_start();

/* Redirect to login page if admin is not logged in */
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

/* Connect to the database */
require_once('settings.php');

/* Get selected user ID safely */
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

/* Redirect back if no valid ID is provided */
if ($id <= 0) {
    header("Location: view_register.php");
    exit();
}

/* Retrieve selected user record */
$sql = "SELECT * FROM `user` WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

/* Redirect back if user record is not found */
if (!$row) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: view_register.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BASIC PAGE METADATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Edit registered user account for Cacti-Succulent Kuching">
    <meta name="keywords" content="admin, edit user, manage user, cacti, succulent">
    <meta name="author" content="Rachael, Eleona, Amber">

    <title>Edit User | Cacti-Succulent Kuching</title>

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

    <!-- EDIT USER SECTION -->
    <main class="form-main">
        <div class="form-header">
            <h1>Edit User</h1>
            <p>Update selected registered user account information</p>
        </div>

        <div class="form-card">

            <!-- EDIT USER FORM -->
            <form action="edit_user_process.php" method="post">
                <fieldset>
                    <legend>User ID: <?php echo htmlspecialchars($row['id']); ?></legend>

                    <!-- HIDDEN USER ID -->
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">

                    <!-- PERSONAL INFORMATION -->
                    <div class="form-row">
                        <div class="input-group">
                            <label for="fname">First Name</label>
                            <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($row['fname']); ?>" maxlength="25" pattern="[A-Za-z]+" title="Letters only, maximum 25 characters" required>
                        </div>

                        <div class="input-group">
                            <label for="lname">Last Name</label>
                            <input type="text" id="lname" name="lname" value="<?php echo htmlspecialchars($row['lname']); ?>" maxlength="25" pattern="[A-Za-z]+" title="Letters only, maximum 25 characters" required>
                        </div>
                    </div>

                    <!-- CONTACT INFORMATION -->
                    <div class="form-row">
                        <div class="input-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                        </div>

                        <div class="input-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>" maxlength="11" title="Enter phone number" required>
                        </div>
                    </div>

                    <!-- ACCOUNT INFORMATION -->
                    <div class="form-row">
                        <div class="input-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" maxlength="10" pattern="[A-Za-z]+" title="Letters only, maximum 10 characters" required>
                        </div>

                    <!-- PREFERRED CONTACT -->
                    <div class="form-row">
                        <div class="input-group full-width">
                            <label>Preferred Contact</label>
                            <div class="radio-group">
                                <input type="radio" id="pref-email" name="contact_pref" value="email" <?php echo ($row['contact_pref'] == 'email') ? 'checked' : ''; ?> required>
                                <label for="pref-email" class="inline-label">Email</label>

                                <input type="radio" id="pref-phone" name="contact_pref" value="phone" <?php echo ($row['contact_pref'] == 'phone') ? 'checked' : ''; ?> required>
                                <label for="pref-phone" class="inline-label">Phone</label>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <!-- ADDRESS INFORMATION -->
                <fieldset>
                    <legend>Address Information</legend>

                    <!-- STREET ADDRESS -->
                    <div class="form-row">
                        <div class="input-group full-width">
                            <label for="street">Street Address</label>
                            <input type="text" id="street" name="street" value="<?php echo htmlspecialchars($row['street']); ?>" maxlength="40" required>
                        </div>
                    </div>

                    <!-- CITY AND STATE -->
                    <div class="form-row">
                        <div class="input-group">
                            <label for="city">City/Town</label>
                            <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($row['city']); ?>" maxlength="20" required>
                        </div>

                        <div class="input-group">
                            <label for="state">State</label>
                            <select id="state" name="state" required>
                                <?php
                                /* Generate Malaysian state options */
                                $states = ['Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Perak', 'Perlis', 'Pulau Pinang', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu', 'Kuala Lumpur', 'Labuan', 'Putrajaya'];
                                foreach ($states as $s) {
                                    $sel = ($row['state'] == $s) ? 'selected' : '';
                                    echo "<option value=\"$s\" $sel>$s</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- POSTCODE -->
                    <div class="form-row">
                        <div class="input-group full-width">
                            <label for="postcode">Postcode</label>
                            <input type="text" id="postcode" name="postcode" value="<?php echo htmlspecialchars($row['postcode']); ?>" maxlength="5" pattern="[0-9]{5}" title="Exactly 5 digits" required>
                        </div>
                    </div>
                </fieldset>

                <!-- FORM BUTTONS -->
                <div class="button-group">
                    <input type="submit" value="Update User">
                    <input type="reset" value="Reset Form">
                </div>
            </form>

            <!-- ADMIN NAVIGATION BUTTONS -->
            <div class="admin-action-buttons">
                <a href="view_register.php" class="admin-action-link">Back to Users</a>
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

<?php
/* Close statement and database connection */
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
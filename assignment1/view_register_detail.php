<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Display full registration details for one selected registered user.
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

/* Retrieve one selected user record */
$sql = "SELECT * FROM `user` WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

/* Redirect back if record is not found */
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
    <meta name="description" content="View full registered user details for Cacti-Succulent Kuching">
    <meta name="keywords" content="admin, registration details, users, cacti, succulent">
    <meta name="author" content="Rachael, Eleona, Amber">

    <title>Registration Details | Cacti-Succulent Kuching</title>

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

    <!-- SINGLE REGISTRATION DETAILS SECTION -->
    <main class="form-main">
        <div class="form-header">
            <h1>Registration Details</h1>
            <p>Full information for the selected registered user</p>
        </div>

        <div class="form-card">

            <!-- SELECTED USER DETAILS TABLE -->
            <fieldset>
                <legend>User ID #<?php echo htmlspecialchars($row['id']); ?></legend>

                <div class="admin-table-wrapper">
                    <table class="admin-detail-table">

                        <tr class="admin-section-row">
                            <th colspan="2">Account Information</th>
                        </tr>
                        <tr>
                            <th>User ID</th>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                        </tr>
                        <tr>
                            <th>Registration Date</th>
                            <td><?php echo isset($row['registration_date']) ? htmlspecialchars($row['registration_date']) : "N/A"; ?></td>
                        </tr>

                        <tr class="admin-section-row">
                            <th colspan="2">Personal Information</th>
                        </tr>
                        <tr>
                            <th>First Name</th>
                            <td><?php echo htmlspecialchars($row['fname']); ?></td>
                        </tr>
                        <tr>
                            <th>Last Name</th>
                            <td><?php echo htmlspecialchars($row['lname']); ?></td>
                        </tr>

                        <tr class="admin-section-row">
                            <th colspan="2">Contact Information</th>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        </tr>
                        <tr>
                            <th>Preferred Contact</th>
                            <td><?php echo htmlspecialchars($row['contact_pref']); ?></td>
                        </tr>

                        <tr class="admin-section-row">
                            <th colspan="2">Address Information</th>
                        </tr>
                        <tr>
                            <th>Street Address</th>
                            <td><?php echo htmlspecialchars($row['street']); ?></td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td><?php echo htmlspecialchars($row['city']); ?></td>
                        </tr>
                        <tr>
                            <th>State</th>
                            <td><?php echo htmlspecialchars($row['state']); ?></td>
                        </tr>
                        <tr>
                            <th>Postcode</th>
                            <td><?php echo htmlspecialchars($row['postcode']); ?></td>
                        </tr>

                    </table>
                </div>

                <!-- ADMIN NAVIGATION BUTTONS -->
                <div class="admin-action-buttons">
                    <a href="view_register.php" class="admin-action-link">Back to Users</a>
                    <a href="admin_dashboard.php" class="admin-action-link">Back to Dashboard</a>
                </div>
            </fieldset>

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
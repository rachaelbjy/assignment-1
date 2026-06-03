<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Display form for administrator to update selected enquiry status.
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

/* Get selected enquiry ID safely */
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

/* Redirect back if no valid ID is provided */
if ($id <= 0) {
    header("Location: view_enquiry.php");
    exit();
}

/* Retrieve selected enquiry record */
$sql = "SELECT * FROM `enquiry` WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

/* Redirect back if enquiry record is not found */
if (!$row) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: view_enquiry.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BASIC PAGE METADATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Edit customer enquiry status for Cacti-Succulent Kuching">
    <meta name="keywords" content="admin, edit enquiry status, customer enquiry, cacti, succulent">
    <meta name="author" content="Rachael, Eleona, Amber">

    <title>Edit Enquiry Status | Cacti-Succulent Kuching</title>

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

    <!-- EDIT ENQUIRY STATUS SECTION -->
    <main class="form-main">
        <div class="form-header">
            <h1>Edit Enquiry Status</h1>
            <p>Update the progress status for the selected enquiry</p>
        </div>

        <div class="form-card">

            <!-- ENQUIRY STATUS FORM -->
            <form action="edit_enquiry_status_process.php" method="post">
                <fieldset>
                    <legend>Enquiry ID: <?php echo htmlspecialchars($row['id']); ?></legend>

                    <!-- HIDDEN ENQUIRY ID -->
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">

                    <!-- ENQUIRY SUMMARY TABLE -->
                    <div class="admin-table-wrapper">
                        <table class="admin-detail-table">
                            <tr class="admin-section-row">
                                <th colspan="2">Enquiry Information</th>
                            </tr>
                            <tr>
                                <th>Customer Name</th>
                                <td><?php echo htmlspecialchars($row['fname'] . " " . $row['lname']); ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                            </tr>
                            <tr>
                                <th>Subject</th>
                                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                            </tr>
                            <tr>
                                <th>Current Status</th>
                                <td><?php echo htmlspecialchars($row['enquiry_status']); ?></td>
                            </tr>
                        </table>
                    </div>

                    <!-- ENQUIRY STATUS SELECTION -->
                    <div class="form-row">
                        <div class="input-group full-width">
                            <label for="enquiry_status">Enquiry Status</label>
                            <select id="enquiry_status" name="enquiry_status" required>
                                <?php
                                $statuses = ['New', 'In Progress', 'Resolved'];
                                foreach ($statuses as $status) {
                                    $selected = ($row['enquiry_status'] == $status) ? 'selected' : '';
                                    echo "<option value=\"$status\" $selected>$status</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </fieldset>

                <!-- FORM BUTTONS -->
                <div class="button-group">
                    <input type="submit" value="Update Status">
                    <input type="reset" value="Reset Form">
                </div>
            </form>

            <!-- ADMIN NAVIGATION BUTTONS -->
            <div class="admin-action-buttons">
                <a href="view_enquiry.php" class="admin-action-link">Back to Enquiries</a>
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
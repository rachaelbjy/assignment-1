<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Display full enquiry details for one selected customer enquiry.
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

/* Retrieve one selected enquiry record and match username using email */
$sql = "SELECT e.*, u.username 
        FROM `enquiry` e
        LEFT JOIN `user` u ON e.email = u.email
        WHERE e.id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

/* Redirect back if record is not found */
if (!$row) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: view_enquiry.php");
    exit();
}

/* Fix display if phone number was stored without leading zero */
$display_phone = $row['phone'];
if (!empty($display_phone) && substr($display_phone, 0, 1) !== "0") {
    $display_phone = "0" . $display_phone;
}

/* Check whether enquiry belongs to a registered user */
$is_registered = !empty($row['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BASIC PAGE METADATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="View full customer enquiry details for Cacti-Succulent Kuching">
    <meta name="keywords" content="admin, enquiry details, customer enquiries, cacti, succulent">
    <meta name="author" content="Rachael, Eleona, Amber">

    <title>Enquiry Details | Cacti-Succulent Kuching</title>

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

    <!-- SINGLE ENQUIRY DETAILS SECTION -->
    <main class="form-main">
        <div class="form-header">
            <h1>Enquiry Details</h1>
            <p>Full information for the selected customer enquiry</p>
        </div>

        <div class="form-card">

            <!-- SELECTED ENQUIRY DETAILS TABLE -->
            <fieldset>
                <legend>Enquiry ID: <?php echo htmlspecialchars($row['id']); ?></legend>

                <div class="admin-table-wrapper">
                    <table class="admin-detail-table">

                        <tr class="admin-section-row">
                            <th colspan="2">Enquiry Information</th>
                        </tr>
                        <tr>
                            <th>Enquiry ID</th>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                        </tr>
                        <tr>
                            <th>Subject</th>
                            <td><?php echo htmlspecialchars($row['subject']); ?></td>
                        </tr>
                        <tr>
                            <th>Comments</th>
                            <td><?php echo nl2br(htmlspecialchars($row['comments'])); ?></td>
                        </tr>

                        <tr class="admin-section-row">
                            <th colspan="2">Customer Information</th>
                        </tr>

                        <?php
                        if ($is_registered) {
                            echo "<tr>";
                            echo "<th>Username</th>";
                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                            echo "</tr>";
                        }
                        ?>

                        <tr>
                            <th>First Name</th>
                            <td><?php echo htmlspecialchars($row['fname']); ?></td>
                        </tr>
                        <tr>
                            <th>Last Name</th>
                            <td><?php echo htmlspecialchars($row['lname']); ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td><?php echo htmlspecialchars($display_phone); ?></td>
                        </tr>

                    </table>
                </div>

                <!-- ADMIN NAVIGATION BUTTONS -->
                <div class="admin-action-buttons">
                    <a href="view_enquiry.php" class="admin-action-link">Back to Enquiries</a>
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
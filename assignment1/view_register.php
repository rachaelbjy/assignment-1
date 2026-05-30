<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Display a summary table of registered user records for the administrator.
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

/* Retrieve registration summary records from the user table in ascending order */
$sql = "SELECT id, fname, lname, username, email FROM `user` ORDER BY id ASC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BASIC PAGE METADATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="View registered user summary for Cacti-Succulent Kuching">
    <meta name="keywords" content="admin, registrations, users, cacti, succulent">
    <meta name="author" content="Rachael, Eleona, Amber">

    <title>View Registrations | Cacti-Succulent Kuching</title>

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

    <!-- VIEW REGISTRATION SUMMARY SECTION -->
    <main class="form-main">
        <div class="form-header">
            <h1>View Registrations</h1>
            <p>Review registered users and click view for full details</p>
        </div>

        <div class="form-card">

            <!-- REGISTRATION SUMMARY TABLE -->
            <fieldset>
                <legend>Registration Summary</legend>

                <p>Total registrations: <?php echo ($result) ? mysqli_num_rows($result) : 0; ?></p>

                <div class="admin-table-wrapper">
                    <table class="admin-summary-table">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Details</th>
                        </tr>

                        <?php
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['fname'] . " " . $row['lname']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td><a href='view_register_detail.php?id=" . urlencode($row['id']) . "' class='admin-view-btn'>View</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No registration records found.</td></tr>";
                        }
                        ?>
                    </table>
                </div>

                <!-- ADMIN NAVIGATION BUTTONS -->
                <div class="admin-action-buttons">
                    <a href="admin_dashboard.php" class="admin-action-link">Back to Dashboard</a>
                    <a href="logout.php" class="admin-action-link">Logout</a>
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
/* Close database connection */
mysqli_close($conn);
?>
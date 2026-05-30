<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Display a summary table of customer enquiry records for the administrator.
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

/* Retrieve important enquiry summary records and match registered user using email in ascending order */
$sql = "SELECT e.id, e.fname, e.lname, e.subject, u.username 
        FROM `enquiry` e
        LEFT JOIN `user` u ON e.email = u.email
        ORDER BY e.id ASC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BASIC PAGE METADATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="View customer enquiry summary for Cacti-Succulent Kuching">
    <meta name="keywords" content="admin, enquiries, customer enquiries, cacti, succulent">
    <meta name="author" content="Rachael, Eleona, Amber">

    <title>View Enquiries | Cacti-Succulent Kuching</title>

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

    <!-- VIEW ENQUIRY SUMMARY SECTION -->
    <main class="form-main">
        <div class="form-header">
            <h1>View Enquiries</h1>
            <p>Review customer enquiries and click view for full details</p>
        </div>

        <div class="form-card">

            <!-- ENQUIRY SUMMARY TABLE -->
            <fieldset>
                <legend>Enquiry Summary</legend>

                <p>Total enquiries: <?php echo ($result) ? mysqli_num_rows($result) : 0; ?></p>

                <div class="admin-table-wrapper">
                    <table class="admin-summary-table">
                        <tr>
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Account Status</th>
                            <th>Subject</th>
                            <th>Details</th>
                        </tr>

                        <?php
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $is_registered = !empty($row['username']);
                                $row_class = $is_registered ? "" : " class='admin-unregistered-row'";
                                $status_text = $is_registered ? "Registered" : "Not Registered";
                                $status_class = $is_registered ? "admin-status-registered" : "admin-status-unregistered";

                                echo "<tr" . $row_class . ">";
                                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['fname'] . " " . $row['lname']) . "</td>";
                                echo "<td><span class='admin-status-badge " . $status_class . "'>" . $status_text . "</span></td>";
                                echo "<td>" . htmlspecialchars($row['subject']) . "</td>";
                                echo "<td><a href='view_enquiry_detail.php?id=" . urlencode($row['id']) . "' class='admin-view-btn'>View</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No enquiry records found.</td></tr>";
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
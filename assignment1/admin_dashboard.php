<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Admin dashboard for accessing and analysing user, order and enquiry records.
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

/* Retrieve dashboard summary statistics */
$user_count = 0;
$order_count = 0;
$enquiry_count = 0;
$pending_order_count = 0;

/* Default recent activity values */
$latest_user = "No registered user found";
$latest_order = "No order found";
$latest_enquiry = "No enquiry found";

/* Default order status breakdown values */
$pending_count = 0;
$preparing_count = 0;
$ready_count = 0;
$delivered_count = 0;
$cancelled_count = 0;

/* Default enquiry status breakdown values */
$new_enquiry_count = 0;
$progress_enquiry_count = 0;
$resolved_enquiry_count = 0;

/* Count registered users */
$user_sql = "SELECT COUNT(*) AS total_users FROM `user`";
$user_result = mysqli_query($conn, $user_sql);
if ($user_result) {
    $user_row = mysqli_fetch_assoc($user_result);
    $user_count = $user_row['total_users'];
}

/* Count customer orders */
$order_sql = "SELECT COUNT(*) AS total_orders FROM `order`";
$order_result = mysqli_query($conn, $order_sql);
if ($order_result) {
    $order_row = mysqli_fetch_assoc($order_result);
    $order_count = $order_row['total_orders'];
}

/* Count customer enquiries */
$enquiry_sql = "SELECT COUNT(*) AS total_enquiries FROM `enquiry`";
$enquiry_result = mysqli_query($conn, $enquiry_sql);
if ($enquiry_result) {
    $enquiry_row = mysqli_fetch_assoc($enquiry_result);
    $enquiry_count = $enquiry_row['total_enquiries'];
}

/* Count pending orders */
$pending_sql = "SELECT COUNT(*) AS pending_orders FROM `order` WHERE order_status = 'Pending'";
$pending_result = mysqli_query($conn, $pending_sql);
if ($pending_result) {
    $pending_row = mysqli_fetch_assoc($pending_result);
    $pending_order_count = $pending_row['pending_orders'];
}

/* Retrieve latest registered user */
$latest_user_sql = "SELECT id, fname, lname, username FROM `user` ORDER BY id DESC LIMIT 1";
$latest_user_result = mysqli_query($conn, $latest_user_sql);
if ($latest_user_result && mysqli_num_rows($latest_user_result) > 0) {
    $latest_user_row = mysqli_fetch_assoc($latest_user_result);
    $latest_user = "User ID " . $latest_user_row['id'] . " - " . $latest_user_row['fname'] . " " . $latest_user_row['lname'] . " (" . $latest_user_row['username'] . ")";
}

/* Retrieve latest order */
$latest_order_sql = "SELECT id, name, order_status FROM `order` ORDER BY id DESC LIMIT 1";
$latest_order_result = mysqli_query($conn, $latest_order_sql);
if ($latest_order_result && mysqli_num_rows($latest_order_result) > 0) {
    $latest_order_row = mysqli_fetch_assoc($latest_order_result);
    $latest_order = "Order ID " . $latest_order_row['id'] . " - " . $latest_order_row['name'] . " (" . $latest_order_row['order_status'] . ")";
}

/* Retrieve latest enquiry */
$latest_enquiry_sql = "SELECT id, fname, lname, subject, enquiry_status FROM `enquiry` ORDER BY id DESC LIMIT 1";
$latest_enquiry_result = mysqli_query($conn, $latest_enquiry_sql);
if ($latest_enquiry_result && mysqli_num_rows($latest_enquiry_result) > 0) {
    $latest_enquiry_row = mysqli_fetch_assoc($latest_enquiry_result);
    $latest_enquiry = "Enquiry ID " . $latest_enquiry_row['id'] . " - " . $latest_enquiry_row['fname'] . " " . $latest_enquiry_row['lname'] . " (" . $latest_enquiry_row['enquiry_status'] . ")";
}

/* Count orders by order status */
$order_status_sql = "SELECT order_status, COUNT(*) AS status_total FROM `order` GROUP BY order_status";
$order_status_result = mysqli_query($conn, $order_status_sql);
if ($order_status_result) {
    while ($status_row = mysqli_fetch_assoc($order_status_result)) {
        if ($status_row['order_status'] == "Pending") {
            $pending_count = $status_row['status_total'];
        } else if ($status_row['order_status'] == "Preparing") {
            $preparing_count = $status_row['status_total'];
        } else if ($status_row['order_status'] == "Ready for Pickup") {
            $ready_count = $status_row['status_total'];
        } else if ($status_row['order_status'] == "Delivered") {
            $delivered_count = $status_row['status_total'];
        } else if ($status_row['order_status'] == "Cancelled") {
            $cancelled_count = $status_row['status_total'];
        }
    }
}

/* Count enquiries by enquiry status */
$enquiry_status_sql = "SELECT enquiry_status, COUNT(*) AS status_total FROM `enquiry` GROUP BY enquiry_status";
$enquiry_status_result = mysqli_query($conn, $enquiry_status_sql);
if ($enquiry_status_result) {
    while ($status_row = mysqli_fetch_assoc($enquiry_status_result)) {
        if ($status_row['enquiry_status'] == "New") {
            $new_enquiry_count = $status_row['status_total'];
        } else if ($status_row['enquiry_status'] == "In Progress") {
            $progress_enquiry_count = $status_row['status_total'];
        } else if ($status_row['enquiry_status'] == "Resolved") {
            $resolved_enquiry_count = $status_row['status_total'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BASIC PAGE METADATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Admin dashboard for Cacti-Succulent Kuching">
    <meta name="keywords" content="admin, dashboard, management, cacti, succulent">
    <meta name="author" content="Rachael, Eleona, Amber">

    <title>Admin Dashboard | Cacti-Succulent Kuching</title>

    <!-- EXTERNAL STYLESHEETS AND ICONS -->
    <link rel="stylesheet" href="styles/style.css?v=adminfix4">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Montserrat:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
</head>

<body class="admin-page admin-dashboard-page">

<!-- HEADER & NAVIGATION -->
<?php include 'header.inc'; ?>

    <!-- ADMIN DASHBOARD SECTION -->
    <main class="form-main">
        <div class="form-header">
            <h1>Admin Dashboard</h1>
            <p>Manage website records and customer information</p>
        </div>

        <div class="form-card">

            <!-- DASHBOARD OVERVIEW -->
            <fieldset class="admin-dashboard-fieldset">
                <legend>Dashboard Overview</legend>

                <div class="admin-table-wrapper">
                    <table class="admin-summary-table dashboard-overview-table">
                        <tr>
                            <th>Category</th>
                            <th>Users</th>
                            <th>Orders</th>
                            <th>Enquiries</th>
                        </tr>
                        <tr>
                            <td><strong>Total Records</strong></td>
                            <td><?php echo htmlspecialchars($user_count); ?></td>
                            <td><?php echo htmlspecialchars($order_count); ?></td>
                            <td><?php echo htmlspecialchars($enquiry_count); ?></td>
                        </tr>
                    </table>
                </div>

                <div class="admin-table-wrapper">
                    <table class="admin-summary-table dashboard-overview-table">
                        <tr>
                            <th>Latest User</th>
                            <th>Latest Order</th>
                            <th>Latest Enquiry</th>
                        </tr>
                        <tr>
                            <td><?php echo htmlspecialchars($latest_user); ?></td>
                            <td><?php echo htmlspecialchars($latest_order); ?></td>
                            <td><?php echo htmlspecialchars($latest_enquiry); ?></td>
                        </tr>
                    </table>
                </div>
            </fieldset>

            <!-- STATUS BREAKDOWN -->
            <fieldset class="admin-dashboard-fieldset">
                <legend>Status Breakdown</legend>

                <div class="admin-table-wrapper">
                    <table class="admin-summary-table dashboard-status-table">
                        <tr>
                            <th>Record Type</th>
                            <th>Pending / New</th>
                            <th>Preparing / In Progress</th>
                            <th>Ready for Pickup</th>
                            <th>Delivered / Resolved</th>
                            <th>Cancelled</th>
                        </tr>
                        <tr>
                            <td><strong>Orders</strong></td>
                            <td><?php echo htmlspecialchars($pending_count); ?></td>
                            <td><?php echo htmlspecialchars($preparing_count); ?></td>
                            <td><?php echo htmlspecialchars($ready_count); ?></td>
                            <td><?php echo htmlspecialchars($delivered_count); ?></td>
                            <td><?php echo htmlspecialchars($cancelled_count); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Enquiries</strong></td>
                            <td><?php echo htmlspecialchars($new_enquiry_count); ?></td>
                            <td><?php echo htmlspecialchars($progress_enquiry_count); ?></td>
                            <td>-</td>
                            <td><?php echo htmlspecialchars($resolved_enquiry_count); ?></td>
                            <td>-</td>
                        </tr>
                    </table>
                </div>
            </fieldset>

            <!-- ADMIN MANAGEMENT LINKS -->
            <fieldset class="admin-dashboard-fieldset">
                <legend>Management Panel</legend>

                <div class="admin-dashboard-menu">
                    <a href="view_register.php" class="admin-menu-link">Users</a>
                    <a href="view_order.php" class="admin-menu-link">Orders</a>
                    <a href="view_enquiry.php" class="admin-menu-link">Enquiries</a>
                    <a href="logout.php" class="admin-menu-link">Logout</a>
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
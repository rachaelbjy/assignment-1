<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Display and manage customer order records for the administrator.
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

/* Get selected order status filter */
$filter_status = isset($_GET['status']) ? trim($_GET['status']) : "All";

/* Get order search keyword */
$search_keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : "";

/* Allowed order status filters */
$allowed_statuses = ["All", "Pending", "Preparing", "Ready for Pickup", "Delivered", "Cancelled"];

if (!in_array($filter_status, $allowed_statuses)) {
    $filter_status = "All";
}

/* Prepare keyword for SQL LIKE search */
$search_like = "%" . $search_keyword . "%";

/* Retrieve important order summary records and match registered user using email in ascending order */
if ($filter_status == "All" && $search_keyword == "") {
    $sql = "SELECT o.id, o.name, o.email, o.phone, o.delivery, o.payment, o.date, o.order_status, u.username 
            FROM `order` o
            LEFT JOIN `user` u ON o.email = u.email
            ORDER BY o.id ASC";
    $result = mysqli_query($conn, $sql);

} else if ($filter_status != "All" && $search_keyword == "") {
    $sql = "SELECT o.id, o.name, o.email, o.phone, o.delivery, o.payment, o.date, o.order_status, u.username 
            FROM `order` o
            LEFT JOIN `user` u ON o.email = u.email
            WHERE o.order_status = ?
            ORDER BY o.id ASC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $filter_status);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

} else if ($filter_status == "All" && $search_keyword != "") {
    $sql = "SELECT o.id, o.name, o.email, o.phone, o.delivery, o.payment, o.date, o.order_status, u.username 
            FROM `order` o
            LEFT JOIN `user` u ON o.email = u.email
            WHERE o.name LIKE ?
               OR o.email LIKE ?
               OR o.phone LIKE ?
               OR o.delivery LIKE ?
               OR o.payment LIKE ?
            ORDER BY o.id ASC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $search_like, $search_like, $search_like, $search_like, $search_like);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

} else {
    $sql = "SELECT o.id, o.name, o.email, o.phone, o.delivery, o.payment, o.date, o.order_status, u.username 
            FROM `order` o
            LEFT JOIN `user` u ON o.email = u.email
            WHERE o.order_status = ?
            AND (
                o.name LIKE ?
                OR o.email LIKE ?
                OR o.phone LIKE ?
                OR o.delivery LIKE ?
                OR o.payment LIKE ?
            )
            ORDER BY o.id ASC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss", $filter_status, $search_like, $search_like, $search_like, $search_like, $search_like);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BASIC PAGE METADATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="View and manage customer order records for Cacti-Succulent Kuching">
    <meta name="keywords" content="admin, orders, customer orders, order status, delivery management">
    <meta name="author" content="Rachael, Eleona, Amber">

    <title>View Orders | Cacti-Succulent Kuching</title>

    <!-- EXTERNAL STYLESHEETS AND ICONS -->
    <link rel="stylesheet" href="styles/style.css?v=adminfix5">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Montserrat:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
</head>

<body class="admin-page view-order-page">

<!-- HEADER & NAVIGATION -->
<?php include 'header.inc'; ?>

    <!-- VIEW ORDERS SECTION -->
    <main class="form-main">
        <div class="form-header">
            <h1>View Orders</h1>
            <p>Search, filter, view details, and update order status</p>
        </div>

        <div class="form-card">

            <!-- ORDER MANAGEMENT TABLE -->
            <fieldset class="admin-record-fieldset">
                <legend>Orders</legend>

                <!-- ORDER SEARCH AND STATUS FILTER -->
                <form action="view_order.php" method="get">
                    <div class="form-row">
                        <div class="input-group">
                            <label for="keyword">Search Order</label>
                            <input type="text" id="keyword" name="keyword" value="<?php echo htmlspecialchars($search_keyword); ?>" placeholder="Name, email, phone or payment">
                        </div>

                        <div class="input-group">
                            <label for="status">Filter by Status</label>
                            <select id="status" name="status">
                                <option value="All" <?php echo ($filter_status == "All") ? "selected" : ""; ?>>All</option>
                                <option value="Pending" <?php echo ($filter_status == "Pending") ? "selected" : ""; ?>>Pending</option>
                                <option value="Preparing" <?php echo ($filter_status == "Preparing") ? "selected" : ""; ?>>Preparing</option>
                                <option value="Ready for Pickup" <?php echo ($filter_status == "Ready for Pickup") ? "selected" : ""; ?>>Ready for Pickup</option>
                                <option value="Delivered" <?php echo ($filter_status == "Delivered") ? "selected" : ""; ?>>Delivered</option>
                                <option value="Cancelled" <?php echo ($filter_status == "Cancelled") ? "selected" : ""; ?>>Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <div class="admin-filter-actions">
                        <input type="submit" value="Apply">
                        <a href="view_order.php" class="admin-action-link">Clear</a>
                    </div>
                </form>

                <!-- TOTAL ORDERS -->
                <div class="admin-list-toolbar">
                    <p>Total orders: <?php echo ($result) ? mysqli_num_rows($result) : 0; ?></p>
                </div>

                <!-- ORDER SUMMARY TABLE -->
                <div class="admin-table-wrapper">
                    <table class="admin-summary-table">
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Account</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Update</th>
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
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td><span class='admin-status-badge " . $status_class . "'>" . $status_text . "</span></td>";
                                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['order_status']) . "</td>";
                                echo "<td><a href='edit_order_status.php?id=" . urlencode($row['id']) . "' class='admin-view-btn'>Edit</a></td>";
                                echo "<td><a href='view_order_detail.php?id=" . urlencode($row['id']) . "' class='admin-view-btn'>View</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No order records found.</td></tr>";
                        }
                        ?>
                    </table>
                </div>

                <!-- ADMIN NAVIGATION BUTTONS -->
                <div class="admin-action-buttons">
                    <a href="admin_dashboard.php" class="admin-action-link">Back to Dashboard</a>
                    <a href="export_orders.php" class="admin-action-link">Export CSV</a>
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
/* Close prepared statement if filter or search was used */
if (isset($stmt)) {
    mysqli_stmt_close($stmt);
}

/* Close database connection */
mysqli_close($conn);
?>
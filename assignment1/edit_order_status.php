<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Display form for administrator to update selected order delivery status.
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

/* Get selected order ID safely */
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

/* Redirect back if no valid ID is provided */
if ($id <= 0) {
    header("Location: view_order.php");
    exit();
}

/* Retrieve selected order record */
$sql = "SELECT * FROM `order` WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

/* Redirect back if order record is not found */
if (!$row) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: view_order.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BASIC PAGE METADATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Edit customer order status for Cacti-Succulent Kuching">
    <meta name="keywords" content="admin, edit order status, delivery status, cacti, succulent">
    <meta name="author" content="Rachael, Eleona, Amber">

    <title>Edit Order Status | Cacti-Succulent Kuching</title>

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

    <!-- EDIT ORDER STATUS SECTION -->
    <main class="form-main">
        <div class="form-header">
            <h1>Edit Order Status</h1>
            <p>Update the delivery progress for the selected order</p>
        </div>

        <div class="form-card">

            <!-- ORDER STATUS FORM -->
            <form action="edit_order_status_process.php" method="post">
                <fieldset>
                    <legend>Order ID: <?php echo htmlspecialchars($row['id']); ?></legend>

                    <!-- HIDDEN ORDER ID -->
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">

                    <!-- ORDER SUMMARY TABLE -->
                    <div class="admin-table-wrapper">
                        <table class="admin-detail-table">
                            <tr class="admin-section-row">
                                <th colspan="2">Order Information</th>
                            </tr>
                            <tr>
                                <th>Customer Name</th>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
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
                                <th>Preferred Date</th>
                                <td><?php echo htmlspecialchars($row['date']); ?></td>
                            </tr>
                            <tr>
                                <th>Delivery Method</th>
                                <td><?php echo htmlspecialchars($row['delivery']); ?></td>
                            </tr>
                            <tr>
                                <th>Payment Method</th>
                                <td><?php echo htmlspecialchars($row['payment']); ?></td>
                            </tr>
                            <tr>
                                <th>Current Status</th>
                                <td><?php echo htmlspecialchars($row['order_status']); ?></td>
                            </tr>
                        </table>
                    </div>

                    <!-- ORDER STATUS SELECTION -->
                    <div class="form-row">
                        <div class="input-group full-width">
                            <label for="order_status">Order Status</label>
                            <select id="order_status" name="order_status" required>
                                <?php
                                $statuses = ['Pending', 'Preparing', 'Ready for Pickup', 'Delivered', 'Cancelled'];
                                foreach ($statuses as $status) {
                                    $selected = ($row['order_status'] == $status) ? 'selected' : '';
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
                <a href="view_order.php" class="admin-action-link">Back to Orders</a>
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
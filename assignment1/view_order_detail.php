<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Display full order details for one selected customer order.
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

/* Retrieve one selected order record */
$sql = "SELECT * FROM `order` WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

/* Redirect back if record is not found */
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
    <meta name="description" content="View full customer order details for Cacti-Succulent Kuching">
    <meta name="keywords" content="admin, order details, customer orders, cacti, succulent">
    <meta name="author" content="Rachael, Eleona, Amber">

    <title>Order Details | Cacti-Succulent Kuching</title>

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

    <!-- SINGLE ORDER DETAILS SECTION -->
    <main class="form-main">
        <div class="form-header">
            <h1>Order Details</h1>
            <p>Full information for the selected customer order</p>
        </div>

        <div class="form-card">

            <!-- SELECTED ORDER DETAILS TABLE -->
            <fieldset>
                <legend>Order #<?php echo htmlspecialchars($row['id']); ?></legend>

                <div class="admin-table-wrapper">
                    <table class="admin-detail-table">

                        <tr class="admin-section-row">
                            <th colspan="2">Order Information</th>
                        </tr>
                        <tr>
                            <th>Order ID</th>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                        </tr>
                        <tr>
                            <th>Preferred Date</th>
                            <td><?php echo htmlspecialchars($row['date']); ?></td>
                        </tr>
                        <tr>
                            <th>Preferred Time</th>
                            <td><?php echo htmlspecialchars($row['time']); ?></td>
                        </tr>
                        <tr>
                            <th>Delivery Method</th>
                            <td><?php echo htmlspecialchars($row['delivery']); ?></td>
                        </tr>
                        <tr>
                            <th>Payment Method</th>
                            <td><?php echo htmlspecialchars($row['payment']); ?></td>
                        </tr>

                        <tr class="admin-section-row">
                            <th colspan="2">Product Information</th>
                        </tr>
                        <tr>
                            <th>Item 1</th>
                            <td><?php echo htmlspecialchars($row['product1']); ?> × <?php echo htmlspecialchars($row['quantity1']); ?></td>
                        </tr>

                        <?php if (!empty($row['product2'])) { ?>
                        <tr>
                            <th>Item 2</th>
                            <td><?php echo htmlspecialchars($row['product2']); ?> × <?php echo htmlspecialchars($row['quantity2']); ?></td>
                        </tr>
                        <?php } ?>

                        <?php if (!empty($row['product3'])) { ?>
                        <tr>
                            <th>Item 3</th>
                            <td><?php echo htmlspecialchars($row['product3']); ?> × <?php echo htmlspecialchars($row['quantity3']); ?></td>
                        </tr>
                        <?php } ?>

                        <tr class="admin-section-row">
                            <th colspan="2">Customer Information</th>
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
                            <th>Address</th>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                        </tr>

                    </table>
                </div>

                <!-- ADMIN NAVIGATION BUTTONS -->
                <div class="admin-action-buttons">
                    <a href="view_order.php" class="admin-action-link">Back to Orders</a>
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
<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Admin dashboard for accessing registration, order, enquiry, product and user management pages.
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

/* Create product option table if it does not exist */
$create_option_table_sql = "
    CREATE TABLE IF NOT EXISTS product_option (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        option_name VARCHAR(100) NOT NULL,
        option_price DECIMAL(10,2) NOT NULL,
        option_stock INT NOT NULL DEFAULT 0,
        FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
    )
";

mysqli_query($conn, $create_option_table_sql);

/* Retrieve dashboard summary statistics */
$user_count = 0;
$order_count = 0;
$enquiry_count = 0;
$pending_order_count = 0;

/* Product and service quick overview */
$product_count = 0;
$low_stock_count = 0;
$sold_out_count = 0;

/* Default recent activity values */
$latest_user = "No registered user found";
$latest_order = "No order found";
$latest_enquiry = "No enquiry found";

/* Get stock status by checking product stock or option stock */
function get_dashboard_stock_status($conn, $product_id, $stock_quantity) {
    $options = [];

    $option_sql = "SELECT option_stock
                   FROM product_option
                   WHERE product_id = ?";

    $option_stmt = mysqli_prepare($conn, $option_sql);
    mysqli_stmt_bind_param($option_stmt, "i", $product_id);
    mysqli_stmt_execute($option_stmt);
    $option_result = mysqli_stmt_get_result($option_stmt);

    if ($option_result && mysqli_num_rows($option_result) > 0) {
        while ($option_row = mysqli_fetch_assoc($option_result)) {
            $options[] = $option_row;
        }
    }

    mysqli_stmt_close($option_stmt);

    if (count($options) > 0) {
        $all_sold_out = true;
        $has_low_stock = false;

        foreach ($options as $option) {
            $option_stock = (int)$option['option_stock'];

            if ($option_stock > 0) {
                $all_sold_out = false;
            }

            if ($option_stock > 0 && $option_stock <= 5) {
                $has_low_stock = true;
            }
        }

        if ($all_sold_out) {
            return "sold_out";
        }

        if ($has_low_stock) {
            return "low_stock";
        }

        return "in_stock";
    }

    $stock_quantity = (int)$stock_quantity;

    if ($stock_quantity == 0) {
        return "sold_out";
    } else if ($stock_quantity <= 5) {
        return "low_stock";
    }

    return "in_stock";
}

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

/* Count pending orders for order and delivery management */
$pending_sql = "SELECT COUNT(*) AS pending_orders FROM `order` WHERE order_status = 'Pending'";
$pending_result = mysqli_query($conn, $pending_sql);

if ($pending_result) {
    $pending_row = mysqli_fetch_assoc($pending_result);
    $pending_order_count = $pending_row['pending_orders'];
}

/* Count total products and services */
$product_sql = "SELECT COUNT(*) AS total_products FROM `product`";
$product_result = mysqli_query($conn, $product_sql);

if ($product_result) {
    $product_row = mysqli_fetch_assoc($product_result);
    $product_count = $product_row['total_products'];
}

/* Count low stock and sold out products or services */
$stock_sql = "SELECT id, stock_quantity FROM product";
$stock_result = mysqli_query($conn, $stock_sql);

if ($stock_result) {
    while ($stock_row = mysqli_fetch_assoc($stock_result)) {
        $stock_status = get_dashboard_stock_status($conn, $stock_row['id'], $stock_row['stock_quantity']);

        if ($stock_status == "sold_out") {
            $sold_out_count++;
        } else if ($stock_status == "low_stock") {
            $low_stock_count++;
        }
    }
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
    <link rel="stylesheet" href="styles/style.css?v=admindashboardoptionstock1">
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

            <!-- ADMIN SUMMARY STATISTICS -->
            <fieldset class="admin-dashboard-fieldset">
                <legend>Dashboard Summary</legend>

                <div class="admin-table-wrapper">
                    <table class="admin-summary-table dashboard-overview-table">
                        <tr>
                            <th>Total Users</th>
                            <th>Total Orders</th>
                            <th>Total Enquiries</th>
                            <th>Pending Orders</th>
                        </tr>

                        <tr>
                            <td><?php echo htmlspecialchars($user_count); ?></td>
                            <td><?php echo htmlspecialchars($order_count); ?></td>
                            <td><?php echo htmlspecialchars($enquiry_count); ?></td>
                            <td><?php echo htmlspecialchars($pending_order_count); ?></td>
                        </tr>
                    </table>
                </div>
            </fieldset>

            <!-- PRODUCT AND SERVICE QUICK OVERVIEW -->
            <fieldset class="admin-dashboard-fieldset">
                <legend>Product and Service Overview</legend>

                <div class="admin-table-wrapper">
                    <table class="admin-summary-table dashboard-status-table">
                        <tr>
                            <th>Total Products & Services</th>
                            <th>Low Stock</th>
                            <th>Sold Out / Unavailable</th>
                        </tr>

                        <tr>
                            <td><?php echo htmlspecialchars($product_count); ?></td>

                            <td>
                                <?php
                                if ($low_stock_count > 0) {
                                    echo "<span class='admin-stock-warning'>" . htmlspecialchars($low_stock_count) . " Low</span>";
                                } else {
                                    echo "0";
                                }
                                ?>
                            </td>

                            <td>
                                <?php
                                if ($sold_out_count > 0) {
                                    echo "<span class='admin-stock-soldout'>" . htmlspecialchars($sold_out_count) . " Alert</span>";
                                } else {
                                    echo "0";
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="admin-action-buttons">
                    <a href="view_product.php" class="admin-action-link">Manage Products and Services</a>
                </div>
            </fieldset>

            <!-- RECENT ACTIVITY SUMMARY -->
            <fieldset class="admin-dashboard-fieldset">
                <legend>Recent Activity</legend>

                <div class="admin-table-wrapper">
                    <table class="admin-summary-table dashboard-status-table">
                        <tr>
                            <th>Latest Registered User</th>
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

            <!-- ADMIN MANAGEMENT LINKS -->
            <fieldset class="admin-dashboard-fieldset">
                <legend>Management Panel</legend>

                <div class="admin-dashboard-menu">
                    <a href="view_register.php" class="admin-menu-link">Users</a>
                    <a href="view_order.php" class="admin-menu-link">Orders</a>
                    <a href="view_enquiry.php" class="admin-menu-link">Enquiries</a>
                    <a href="view_product.php" class="admin-menu-link">Products and Services</a>
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
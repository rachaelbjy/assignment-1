<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Display detailed product or service information for administrator.
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

/* Get product or service ID */
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

/* Redirect if ID is invalid */
if ($product_id <= 0) {
    header("Location: view_product.php");
    exit();
}

/* Retrieve selected product or service record */
$sql = "SELECT id, product_name, category, product_options, description, price, image_path, image_source, stock_quantity
        FROM product
        WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

/* Redirect if record does not exist */
if (!$result || mysqli_num_rows($result) == 0) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: view_product.php");
    exit();
}

/* Store product or service details */
$product = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

/* Package services do not use product images */
$is_package_service = ($product['category'] == "Services" && stripos($product['product_name'], "package") !== false);

/* Retrieve separate product options */
$product_options_list = [];

$option_sql = "SELECT id, option_name, option_price, option_stock
               FROM product_option
               WHERE product_id = ?
               ORDER BY id ASC";

$option_stmt = mysqli_prepare($conn, $option_sql);
mysqli_stmt_bind_param($option_stmt, "i", $product_id);
mysqli_stmt_execute($option_stmt);
$option_result = mysqli_stmt_get_result($option_stmt);

if ($option_result && mysqli_num_rows($option_result) > 0) {
    while ($option_row = mysqli_fetch_assoc($option_result)) {
        $product_options_list[] = $option_row;
    }
}

mysqli_stmt_close($option_stmt);

/* If new option table is empty, fall back to old product_options text */
if (count($product_options_list) == 0 && !empty($product['product_options'])) {
    $old_options_array = explode(',', $product['product_options']);

    foreach ($old_options_array as $old_option) {
        $old_option = trim($old_option);

        if ($old_option != "") {
            $old_option_name = "";
            $old_option_price = $product['price'];

            if (strpos($old_option, ':') !== false) {
                list($old_option_name, $old_option_price) = explode(':', $old_option, 2);
                $old_option_name = trim($old_option_name);
                $old_option_price = number_format((float)trim($old_option_price), 2, '.', '');
            } else {
                $old_option_name = $old_option;
                $old_option_price = number_format((float)$product['price'], 2, '.', '');
            }

            $product_options_list[] = [
                "id" => 0,
                "option_name" => $old_option_name,
                "option_price" => $old_option_price,
                "option_stock" => $product['stock_quantity']
            ];
        }
    }
}

$has_options = (count($product_options_list) > 0);

/* Decide normal item stock status */
$normal_status = "In Stock";
$normal_status_class = "admin-stock-normal";

if (!$has_options) {
    $stock_quantity = (int)$product['stock_quantity'];

    if ($stock_quantity == 0) {
        $normal_status = "Sold Out";
        $normal_status_class = "admin-stock-soldout";
    } else if ($stock_quantity <= 5) {
        $normal_status = "Low Stock";
        $normal_status_class = "admin-stock-warning";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BASIC PAGE METADATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Product or service detail page for administrator">
    <meta name="keywords" content="admin, product detail, service detail">
    <meta name="author" content="Rachael, Eleona, Amber">

    <title>Product or Service Details | Cacti-Succulent Kuching</title>

    <!-- EXTERNAL STYLESHEETS AND ICONS -->
    <link rel="stylesheet" href="styles/style.css?v=productdetailoptionstock4">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Montserrat:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
</head>

<body class="admin-page view-product-page">

<!-- HEADER & NAVIGATION -->
<?php include 'header.inc'; ?>

    <!-- PRODUCT OR SERVICE DETAIL SECTION -->
    <main class="form-main">
        <div class="form-header">
            <h1>Product or Service Details</h1>
            <p>View full product, service, price, option and stock information</p>
        </div>

        <div class="form-card">
            <fieldset class="admin-record-fieldset">
                <legend>Selected Record</legend>

                <!-- MAIN DETAIL TABLE -->
                <div class="admin-table-wrapper">
                    <table class="admin-detail-table">
                        <tr class="admin-section-row">
                            <th colspan="2">Main Information</th>
                        </tr>

                        <tr>
                            <th>ID</th>
                            <td><?php echo htmlspecialchars($product['id']); ?></td>
                        </tr>

                        <tr>
                            <th>Name</th>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                        </tr>

                        <tr>
                            <th>Category</th>
                            <td><?php echo htmlspecialchars($product['category']); ?></td>
                        </tr>

                        <?php if (!$has_options) { ?>
                            <tr>
                                <th>Price</th>
                                <td>RM <?php echo htmlspecialchars(number_format((float)$product['price'], 2)); ?></td>
                            </tr>

                            <tr>
                                <th>Stock</th>
                                <td>
                                    <?php
                                    if ($product['stock_quantity'] === null || $product['stock_quantity'] === "") {
                                        echo "-";
                                    } else {
                                        echo htmlspecialchars($product['stock_quantity']);
                                    }
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Stock Status</th>
                                <td><span class="<?php echo htmlspecialchars($normal_status_class); ?>"><?php echo htmlspecialchars($normal_status); ?></span></td>
                            </tr>
                        <?php } ?>

                        <tr>
                            <th>Description</th>
                            <td><?php echo nl2br(htmlspecialchars($product['description'])); ?></td>
                        </tr>

                        <?php if (!$is_package_service) { ?>
                            <tr>
                                <th>Image</th>
                                <td>
                                    <?php if (!empty($product['image_path'])) { ?>
                                        <div class="admin-current-image-box">
                                            <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                                        </div>
                                    <?php } else { ?>
                                        -
                                    <?php } ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Image Source</th>
                                <td>
                                    <?php if (!empty($product['image_source'])) { ?>
                                        <a href="<?php echo htmlspecialchars($product['image_source']); ?>" target="_blank"><?php echo htmlspecialchars($product['image_source']); ?></a>
                                    <?php } else { ?>
                                        -
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>

                <?php if ($has_options) { ?>
                    <!-- OPTION DETAIL TABLE -->
                    <div class="admin-table-wrapper">
                        <table class="admin-summary-table">
                            <tr>
                                <th>No.</th>
                                <th>Option Name</th>
                                <th>Option Price</th>
                                <th>Option Stock</th>
                                <th>Option Status</th>
                            </tr>

                            <?php
                            $option_no = 1;

                            foreach ($product_options_list as $option) {
                                $option_stock = (int)$option['option_stock'];

                                if ($option_stock == 0) {
                                    $option_status = "Sold Out";
                                    $option_status_class = "admin-stock-soldout";
                                } else if ($option_stock <= 5) {
                                    $option_status = "Low Stock";
                                    $option_status_class = "admin-stock-warning";
                                } else {
                                    $option_status = "In Stock";
                                    $option_status_class = "admin-stock-normal";
                                }

                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($option_no) . "</td>";
                                echo "<td>" . htmlspecialchars($option['option_name']) . "</td>";
                                echo "<td>RM " . htmlspecialchars(number_format((float)$option['option_price'], 2)) . "</td>";
                                echo "<td>" . htmlspecialchars($option_stock) . "</td>";
                                echo "<td><span class='" . htmlspecialchars($option_status_class) . "'>" . htmlspecialchars($option_status) . "</span></td>";
                                echo "</tr>";

                                $option_no++;
                            }
                            ?>
                        </table>
                    </div>
                <?php } ?>

                <!-- ADMIN ACTION BUTTONS -->
                <div class="admin-action-buttons">
                    <a href="view_product.php" class="admin-action-link">Back to Products</a>
                    <a href="edit_product.php?id=<?php echo urlencode($product['id']); ?>" class="admin-action-link">Edit</a>
                    <a href="delete_product.php?id=<?php echo urlencode($product['id']); ?>" class="admin-action-link">Delete</a>
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
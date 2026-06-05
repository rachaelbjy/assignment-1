<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Confirm and delete a product or service record.
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

/* If delete confirmation form is submitted */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $confirm_delete = isset($_POST['confirm_delete']) ? $_POST['confirm_delete'] : "";

    if ($product_id > 0 && $confirm_delete == "yes") {
        $sql = "DELETE FROM product WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
    }

    mysqli_close($conn);
    header("Location: view_product.php?deleted=success");
    exit();
}

/* Get product or service ID */
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

/* Redirect if ID is invalid */
if ($product_id <= 0) {
    header("Location: view_product.php");
    exit();
}

/* Retrieve selected product or service record */
$sql = "SELECT id, product_name, category, price, stock_quantity
        FROM product
        WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

/* Redirect if record does not exist */
if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: view_product.php");
    exit();
}

/* Store product or service details */
$product = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BASIC PAGE METADATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Delete product or service page for administrator">
    <meta name="keywords" content="admin, delete product, delete service">
    <meta name="author" content="Rachael, Eleona, Amber">

    <title>Delete Product or Service | Cacti-Succulent Kuching</title>

    <!-- EXTERNAL STYLESHEETS AND ICONS -->
    <link rel="stylesheet" href="styles/style.css?v=productmanage3">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Montserrat:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
</head>

<body class="admin-page view-product-page">

<!-- HEADER & NAVIGATION -->
<?php include 'header.inc'; ?>

    <!-- DELETE PRODUCT AND SERVICE SECTION -->
    <main class="form-main">
        <div class="form-header">
            <h1>Delete Product or Service</h1>
            <p>Confirm before removing this record from the website database</p>
        </div>

        <div class="form-card">
            <fieldset class="admin-record-fieldset">
                <legend>Delete Confirmation</legend>

                <!-- SELECTED RECORD TABLE -->
                <div class="admin-table-wrapper">
                    <table class="admin-detail-table">
                        <tr class="admin-section-row">
                            <th colspan="2">Selected Record</th>
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

                        <tr>
                            <th>Price</th>
                            <td>RM <?php echo htmlspecialchars(number_format($product['price'], 2)); ?></td>
                        </tr>

                        <tr>
                            <th>Stock</th>
                            <td><?php echo htmlspecialchars($product['stock_quantity']); ?></td>
                        </tr>
                    </table>
                </div>

                <!-- DELETE CONFIRMATION FORM -->
                <form action="delete_product.php" method="post">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
                    <input type="hidden" name="confirm_delete" value="yes">

                    <p class="admin-message-bold">Are you sure you want to delete this product or service?</p>

                    <div class="button-group">
                        <input type="submit" value="Delete">
                        <a href="view_product.php" class="admin-action-link">Cancel</a>
                    </div>
                </form>
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
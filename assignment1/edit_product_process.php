<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Process the form for editing an existing product or service.
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

/* Allowed product and service categories */
$allowed_categories = ["Cacti", "Succulents", "Planting Accessories", "Services"];

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

if (!mysqli_query($conn, $create_option_table_sql)) {
    mysqli_close($conn);
    header("Location: view_product.php?error=option_table");
    exit();
}

/* Process form only when submitted using POST */
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    mysqli_close($conn);
    header("Location: view_product.php");
    exit();
}

/* Get form values */
$product_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$product_name = isset($_POST['product_name']) ? trim($_POST['product_name']) : "";
$category = isset($_POST['category']) ? trim($_POST['category']) : "";
$description = isset($_POST['description']) ? trim($_POST['description']) : "";
$price = isset($_POST['price']) ? trim($_POST['price']) : "";
$image_source = isset($_POST['image_source']) ? trim($_POST['image_source']) : "";
$stock_quantity = isset($_POST['stock_quantity']) ? trim($_POST['stock_quantity']) : "";
$current_image_path = isset($_POST['current_image_path']) ? trim($_POST['current_image_path']) : "";

/* Get option arrays */
$option_ids = isset($_POST['option_id']) ? $_POST['option_id'] : [];
$option_names = isset($_POST['option_name']) ? $_POST['option_name'] : [];
$option_prices = isset($_POST['option_price']) ? $_POST['option_price'] : [];
$option_stocks = isset($_POST['option_stock']) ? $_POST['option_stock'] : [];

/* Check required fields */
if (
    $product_id <= 0 ||
    $product_name == "" ||
    $category == "" ||
    $description == "" ||
    $price === ""
) {
    mysqli_close($conn);
    header("Location: view_product.php?error=empty");
    exit();
}

/* Check category */
if (!in_array($category, $allowed_categories)) {
    mysqli_close($conn);
    header("Location: view_product.php?error=category");
    exit();
}

/* Check price */
if (!is_numeric($price) || $price < 0) {
    mysqli_close($conn);
    header("Location: edit_product.php?id=" . $product_id . "&error=price");
    exit();
}

/* Prepare option records */
$valid_options = [];
$product_options_text_array = [];

for ($i = 0; $i < count($option_names); $i++) {
    $option_id = isset($option_ids[$i]) ? intval($option_ids[$i]) : 0;
    $option_name = isset($option_names[$i]) ? trim($option_names[$i]) : "";
    $option_price = isset($option_prices[$i]) ? trim($option_prices[$i]) : "";
    $option_stock = isset($option_stocks[$i]) ? trim($option_stocks[$i]) : "";

    if ($option_name != "" || $option_price != "" || $option_stock != "") {

        /* Check complete option row */
        if ($option_name == "" || $option_price === "" || $option_stock === "") {
            mysqli_close($conn);
            header("Location: edit_product.php?id=" . $product_id . "&error=option_incomplete");
            exit();
        }

        /* Check option price */
        if (!is_numeric($option_price) || $option_price < 0) {
            mysqli_close($conn);
            header("Location: edit_product.php?id=" . $product_id . "&error=option_price");
            exit();
        }

        /* Check option stock */
        if (!ctype_digit($option_stock)) {
            mysqli_close($conn);
            header("Location: edit_product.php?id=" . $product_id . "&error=option_stock");
            exit();
        }

        $option_price = number_format((float)$option_price, 2, '.', '');
        $option_stock = intval($option_stock);

        $valid_options[] = [
            "option_id" => $option_id,
            "option_name" => $option_name,
            "option_price" => $option_price,
            "option_stock" => $option_stock
        ];

        $product_options_text_array[] = $option_name . ":" . $option_price;
    }
}

/* Check stock quantity only for items without options */
if (count($valid_options) == 0) {
    if ($stock_quantity === "" || !ctype_digit($stock_quantity)) {
        mysqli_close($conn);
        header("Location: edit_product.php?id=" . $product_id . "&error=stock");
        exit();
    }
}

/* Build old product_options text for compatibility */
$product_options = implode(", ", $product_options_text_array);

/* If options are used, default price follows the first option and default stock is left blank */
if (count($valid_options) > 0) {
    $price = $valid_options[0]['option_price'];
    $stock_quantity = null;
}

/* Package services do not use product images */
$is_package_service = ($category == "Services" && stripos($product_name, "package") !== false);

if ($is_package_service) {
    $image_path = "";
    $image_source = "";
} else {
    $image_path = $current_image_path;
}

/* Process uploaded image only if admin uploads a new one */
if (!$is_package_service && isset($_FILES['product_image']) && $_FILES['product_image']['error'] == UPLOAD_ERR_OK) {

    /* Prepare upload folder */
    $upload_folder = "images/uploads/";

    if (!is_dir($upload_folder)) {
        mkdir($upload_folder, 0777, true);
    }

    /* Process uploaded image */
    $file_tmp = $_FILES['product_image']['tmp_name'];
    $file_name = basename($_FILES['product_image']['name']);
    $file_size = $_FILES['product_image']['size'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $allowed_extensions = ["jpg", "jpeg", "png", "webp", "gif"];

    if (!in_array($file_ext, $allowed_extensions)) {
        mysqli_close($conn);
        header("Location: edit_product.php?id=" . $product_id . "&error=image_type");
        exit();
    }

    if ($file_size > 5 * 1024 * 1024) {
        mysqli_close($conn);
        header("Location: edit_product.php?id=" . $product_id . "&error=image_size");
        exit();
    }

    $safe_name = preg_replace("/[^a-zA-Z0-9._-]/", "_", pathinfo($file_name, PATHINFO_FILENAME));
    $new_file_name = $safe_name . "_" . time() . "." . $file_ext;
    $new_image_path = $upload_folder . $new_file_name;

    if (!move_uploaded_file($file_tmp, $new_image_path)) {
        mysqli_close($conn);
        header("Location: edit_product.php?id=" . $product_id . "&error=image_upload");
        exit();
    }

    $image_path = $new_image_path;
}

/* Format numeric values */
$price = number_format((float)$price, 2, '.', '');

if ($stock_quantity !== null) {
    $stock_quantity = intval($stock_quantity);
}

/* Start database transaction */
mysqli_begin_transaction($conn);

/* Update product or service record */
$sql = "UPDATE product
        SET product_name = ?,
            category = ?,
            product_options = ?,
            description = ?,
            price = ?,
            image_path = ?,
            image_source = ?,
            stock_quantity = ?
        WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ssssdssii",
    $product_name,
    $category,
    $product_options,
    $description,
    $price,
    $image_path,
    $image_source,
    $stock_quantity,
    $product_id
);

if (!mysqli_stmt_execute($stmt)) {
    mysqli_rollback($conn);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: edit_product.php?id=" . $product_id . "&error=database");
    exit();
}

mysqli_stmt_close($stmt);

/* Delete old option rows and reinsert current option rows */
$delete_option_sql = "DELETE FROM product_option WHERE product_id = ?";
$delete_option_stmt = mysqli_prepare($conn, $delete_option_sql);
mysqli_stmt_bind_param($delete_option_stmt, "i", $product_id);

if (!mysqli_stmt_execute($delete_option_stmt)) {
    mysqli_rollback($conn);
    mysqli_stmt_close($delete_option_stmt);
    mysqli_close($conn);
    header("Location: edit_product.php?id=" . $product_id . "&error=option_delete");
    exit();
}

mysqli_stmt_close($delete_option_stmt);

/* Insert current option rows */
if (count($valid_options) > 0) {
    $insert_option_sql = "INSERT INTO product_option (product_id, option_name, option_price, option_stock)
                          VALUES (?, ?, ?, ?)";

    $insert_option_stmt = mysqli_prepare($conn, $insert_option_sql);

    foreach ($valid_options as $option) {
        $option_name = $option['option_name'];
        $option_price = $option['option_price'];
        $option_stock = $option['option_stock'];

        mysqli_stmt_bind_param(
            $insert_option_stmt,
            "isdi",
            $product_id,
            $option_name,
            $option_price,
            $option_stock
        );

        if (!mysqli_stmt_execute($insert_option_stmt)) {
            mysqli_rollback($conn);
            mysqli_stmt_close($insert_option_stmt);
            mysqli_close($conn);
            header("Location: edit_product.php?id=" . $product_id . "&error=option_insert");
            exit();
        }
    }

    mysqli_stmt_close($insert_option_stmt);
}

/* Commit all changes */
mysqli_commit($conn);

/* Close database connection */
mysqli_close($conn);

/* Redirect back to product management page */
header("Location: view_product.php?updated=success");
exit();
?>
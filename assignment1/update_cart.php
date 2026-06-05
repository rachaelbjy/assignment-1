<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Update item quantity in shopping cart.
*/

/* Start session to access cart */
session_start();

/* Redirect if cart does not exist */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* Only allow POST request */
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: cart.php");
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

/* Get cart key and quantity */
$cart_key = isset($_POST['cart_key']) ? trim($_POST['cart_key']) : "";
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

/* Validate quantity */
if ($quantity < 1) {
    $quantity = 1;
}

/* Limit quantity to prevent unreasonable order amount */
if ($quantity > 20) {
    $quantity = 20;
}

/* Update cart quantity */
if ($cart_key != "" && isset($_SESSION['cart'][$cart_key])) {

    $product_id = isset($_SESSION['cart'][$cart_key]['product_id']) ? intval($_SESSION['cart'][$cart_key]['product_id']) : 0;
    $selected_option = isset($_SESSION['cart'][$cart_key]['selected_option']) ? trim($_SESSION['cart'][$cart_key]['selected_option']) : "";
    $available_stock = isset($_SESSION['cart'][$cart_key]['available_stock']) ? intval($_SESSION['cart'][$cart_key]['available_stock']) : 20;

    /* Get latest stock from database */
    if ($product_id > 0) {

        if ($selected_option != "") {

            /* Check selected option stock */
            $option_sql = "SELECT option_stock
                           FROM product_option
                           WHERE product_id = ?
                           AND option_name = ?
                           LIMIT 1";

            $option_stmt = mysqli_prepare($conn, $option_sql);
            mysqli_stmt_bind_param($option_stmt, "is", $product_id, $selected_option);
            mysqli_stmt_execute($option_stmt);
            $option_result = mysqli_stmt_get_result($option_stmt);

            if ($option_result && mysqli_num_rows($option_result) > 0) {
                $option_row = mysqli_fetch_assoc($option_result);
                $available_stock = (int)$option_row['option_stock'];
            }

            mysqli_stmt_close($option_stmt);

        } else {

            /* Check normal product stock */
            $product_sql = "SELECT stock_quantity
                            FROM product
                            WHERE id = ?
                            LIMIT 1";

            $product_stmt = mysqli_prepare($conn, $product_sql);
            mysqli_stmt_bind_param($product_stmt, "i", $product_id);
            mysqli_stmt_execute($product_stmt);
            $product_result = mysqli_stmt_get_result($product_stmt);

            if ($product_result && mysqli_num_rows($product_result) > 0) {
                $product_row = mysqli_fetch_assoc($product_result);
                $available_stock = (int)$product_row['stock_quantity'];
            }

            mysqli_stmt_close($product_stmt);
        }
    }

    /* If item is no longer available, keep quantity as 1 but show it as limited */
    if ($available_stock <= 0) {
        $quantity = 1;
    } else if ($quantity > $available_stock) {
        $quantity = $available_stock;
    }

    $_SESSION['cart'][$cart_key]['quantity'] = $quantity;
    $_SESSION['cart'][$cart_key]['available_stock'] = $available_stock;
}

/* Close database connection */
mysqli_close($conn);

/* Redirect back to cart */
header("Location: cart.php?updated=success");
exit();
?>
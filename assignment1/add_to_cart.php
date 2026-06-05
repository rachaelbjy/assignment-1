<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Add selected product item into shopping cart session.
*/

/* Start session to store cart data */
session_start();

/* Connect to the database */
require_once('settings.php');

/* Only allow POST request */
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.php");
    exit();
}

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

/* Get submitted product ID, quantity and selected option */
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
$item_option = isset($_POST['item_option']) ? trim($_POST['item_option']) : "";

/* Validate product ID and quantity */
if ($product_id <= 0 || $quantity < 1) {
    header("Location: cart.php?error=invalid_item");
    exit();
}

/* Retrieve selected product from database */
$sql = "SELECT id, product_name, category, product_options, price, image_path, stock_quantity
        FROM product
        WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

/* Redirect if product does not exist */
if (!$result || mysqli_num_rows($result) == 0) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: cart.php?error=item_not_found");
    exit();
}

$product = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

/* Default product details */
$product_name = $product['product_name'];
$category = $product['category'];
$image_path = $product['image_path'];
$selected_option = "";
$selected_price = (float)$product['price'];
$available_stock = (int)$product['stock_quantity'];

/* Check if product has separate option records */
$option_records = [];

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
        $option_records[] = $option_row;
    }
}

mysqli_stmt_close($option_stmt);

/* Validate selected option if separate option records exist */
if (count($option_records) > 0) {
    $option_is_valid = false;

    foreach ($option_records as $option_record) {
        $option_name = trim($option_record['option_name']);
        $option_price = number_format((float)$option_record['option_price'], 2, '.', '');
        $option_stock = (int)$option_record['option_stock'];
        $option_value = strtolower(str_replace(' ', '-', $option_name)) . "|" . $option_price;

        if ($item_option == $option_value) {
            $selected_option = $option_name;
            $selected_price = (float)$option_price;
            $available_stock = $option_stock;
            $option_is_valid = true;
            break;
        }
    }

    if (!$option_is_valid) {
        mysqli_close($conn);
        header("Location: cart.php?error=invalid_option");
        exit();
    }

} else if (!empty($product['product_options'])) {

    /* Fallback for old product_options text if product_option table has no rows */
    $option_is_valid = false;
    $options_array = explode(',', $product['product_options']);

    foreach ($options_array as $option) {
        $option = trim($option);

        if ($option != "") {
            if (strpos($option, ':') !== false) {
                list($option_name, $option_price) = explode(':', $option, 2);
                $option_name = trim($option_name);
                $option_price = number_format((float)trim($option_price), 2, '.', '');

                $option_value = strtolower(str_replace(' ', '-', $option_name)) . "|" . $option_price;

                if ($item_option == $option_value) {
                    $selected_option = $option_name;
                    $selected_price = (float)$option_price;
                    $available_stock = (int)$product['stock_quantity'];
                    $option_is_valid = true;
                    break;
                }
            } else {
                $option_name = trim($option);
                $option_value = strtolower(str_replace(' ', '-', $option_name));

                if ($item_option == $option_value) {
                    $selected_option = $option_name;
                    $selected_price = (float)$product['price'];
                    $available_stock = (int)$product['stock_quantity'];
                    $option_is_valid = true;
                    break;
                }
            }
        }
    }

    if (!$option_is_valid) {
        mysqli_close($conn);
        header("Location: cart.php?error=invalid_option");
        exit();
    }
}

/* Prevent sold out products or options from being added */
if ($available_stock <= 0) {
    mysqli_close($conn);
    header("Location: cart.php?error=sold_out");
    exit();
}

/* Limit requested quantity to available stock */
if ($quantity > $available_stock) {
    $quantity = $available_stock;
}

/* Limit quantity to prevent unreasonable order amount */
if ($quantity > 20) {
    $quantity = 20;
}

/* Create cart if it does not exist */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* Create unique cart key so same product with different option is separate */
$cart_key = $product_id . "_" . strtolower(str_replace(' ', '-', $selected_option));

/* Add or update cart item */
if (isset($_SESSION['cart'][$cart_key])) {
    $_SESSION['cart'][$cart_key]['quantity'] += $quantity;

    if ($_SESSION['cart'][$cart_key]['quantity'] > $available_stock) {
        $_SESSION['cart'][$cart_key]['quantity'] = $available_stock;
    }

    if ($_SESSION['cart'][$cart_key]['quantity'] > 20) {
        $_SESSION['cart'][$cart_key]['quantity'] = 20;
    }

    $_SESSION['cart'][$cart_key]['available_stock'] = $available_stock;

} else {
    $_SESSION['cart'][$cart_key] = [
        'product_id' => $product_id,
        'product_name' => $product_name,
        'category' => $category,
        'selected_option' => $selected_option,
        'price' => $selected_price,
        'image_path' => $image_path,
        'quantity' => $quantity,
        'available_stock' => $available_stock
    ];
}

/* Close database connection */
mysqli_close($conn);

/* Redirect to cart page */
header("Location: cart.php?added=success");
exit();
?>
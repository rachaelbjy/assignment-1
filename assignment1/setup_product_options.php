<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Create product option table and copy existing product options into it.
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

/* Create product_option table if it does not exist */
$create_table_sql = "
    CREATE TABLE IF NOT EXISTS product_option (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        option_name VARCHAR(100) NOT NULL,
        option_price DECIMAL(10,2) NOT NULL,
        option_stock INT NOT NULL DEFAULT 50,
        FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
    )
";

if (!mysqli_query($conn, $create_table_sql)) {
    mysqli_close($conn);
    echo "<script>alert('Database error. Product option table was not created.'); window.location.href = 'view_product.php';</script>";
    exit();
}

/* Retrieve products that already have option data */
$product_sql = "SELECT id, product_options FROM product WHERE product_options IS NOT NULL AND product_options != ''";
$product_result = mysqli_query($conn, $product_sql);

if ($product_result) {
    while ($product = mysqli_fetch_assoc($product_result)) {
        $product_id = (int)$product['id'];
        $options_text = trim($product['product_options']);

        if ($options_text != "") {
            $options_array = explode(',', $options_text);

            foreach ($options_array as $option) {
                $option = trim($option);

                if ($option != "") {
                    $option_name = "";
                    $option_price = 0.00;

                    if (strpos($option, ':') !== false) {
                        list($option_name, $option_price) = explode(':', $option, 2);
                        $option_name = trim($option_name);
                        $option_price = (float)trim($option_price);
                    } else {
                        $option_name = trim($option);
                        $option_price = 0.00;
                    }

                    if ($option_name != "") {

                        /* Check whether this option already exists */
                        $check_sql = "SELECT id FROM product_option WHERE product_id = ? AND option_name = ?";
                        $check_stmt = mysqli_prepare($conn, $check_sql);
                        mysqli_stmt_bind_param($check_stmt, "is", $product_id, $option_name);
                        mysqli_stmt_execute($check_stmt);
                        $check_result = mysqli_stmt_get_result($check_stmt);

                        /* Insert option only if it does not already exist */
                        if (!$check_result || mysqli_num_rows($check_result) == 0) {
                            $insert_sql = "INSERT INTO product_option (product_id, option_name, option_price, option_stock)
                                           VALUES (?, ?, ?, 50)";
                            $insert_stmt = mysqli_prepare($conn, $insert_sql);
                            mysqli_stmt_bind_param($insert_stmt, "isd", $product_id, $option_name, $option_price);
                            mysqli_stmt_execute($insert_stmt);
                            mysqli_stmt_close($insert_stmt);
                        }

                        mysqli_stmt_close($check_stmt);
                    }
                }
            }
        }
    }
}

/* Close database connection */
mysqli_close($conn);

/* Redirect after setup */
echo "<script>alert('Product option stock table has been created successfully. Existing options are copied with stock 50.'); window.location.href = 'view_product.php';</script>";
exit();
?>
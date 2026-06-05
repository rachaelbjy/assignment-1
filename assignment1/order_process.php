<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Process customer order form and store order information in the database.
*/

/* Start session to temporarily store form data and cart data */
session_start();

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

/* Process the form only when it is submitted using POST */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* Sanitize order form inputs */
    $product1 = isset($_POST['product1']) ? mysqli_real_escape_string($conn, trim($_POST['product1'])) : "";
    $quantity1 = isset($_POST['quantity1']) ? (int)$_POST['quantity1'] : 0;

    $product2 = isset($_POST['product2']) ? mysqli_real_escape_string($conn, trim($_POST['product2'])) : "";
    $quantity2 = !empty($_POST['quantity2']) ? (int)$_POST['quantity2'] : 0;

    $product3 = isset($_POST['product3']) ? mysqli_real_escape_string($conn, trim($_POST['product3'])) : "";
    $quantity3 = !empty($_POST['quantity3']) ? (int)$_POST['quantity3'] : 0;

    $delivery = isset($_POST['delivery']) ? mysqli_real_escape_string($conn, trim($_POST['delivery'])) : "";
    $payment = isset($_POST['payment']) ? mysqli_real_escape_string($conn, trim($_POST['payment'])) : "";
    $date = isset($_POST['date']) ? mysqli_real_escape_string($conn, trim($_POST['date'])) : "";
    $time = isset($_POST['time']) ? mysqli_real_escape_string($conn, trim($_POST['time'])) : "";
    $name = isset($_POST['name']) ? mysqli_real_escape_string($conn, trim($_POST['name'])) : "";
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, trim($_POST['email'])) : "";
    $phone = isset($_POST['phone']) ? mysqli_real_escape_string($conn, trim($_POST['phone'])) : "";
    $address = isset($_POST['address']) ? mysqli_real_escape_string($conn, trim($_POST['address'])) : "";
    $terms = isset($_POST['terms']) ? true : false;

    /* Store entered form data in session in case user needs to correct errors */
    $_SESSION['order_data'] = $_POST;

    /* Check cart exists */
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        mysqli_close($conn);
        header("Location: order.php?error=empty_cart");
        exit();
    }

    /* Validate required fields */
    if (
        $product1 == "" || $delivery == "" || $payment == "" || $date == "" ||
        $time == "" || $name == "" || $email == "" || $phone == "" || $address == ""
    ) {
        mysqli_close($conn);
        header("Location: order.php?error=missing_fields");
        exit();
    }

    /* Validate first product quantity */
    if ($quantity1 < 1) {
        mysqli_close($conn);
        header("Location: order.php?error=invalid_quantity");
        exit();
    }

    /* Validate optional product quantities */
    if (($product2 != "" && $quantity2 < 1) || ($product3 != "" && $quantity3 < 1)) {
        mysqli_close($conn);
        header("Location: order.php?error=invalid_quantity");
        exit();
    }

    /* Validate email format */
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        mysqli_close($conn);
        header("Location: order.php?error=invalid_email");
        exit();
    }

    /* Validate phone number format */
    if (!preg_match("/^0[0-9]{9,10}$/", $phone)) {
        mysqli_close($conn);
        header("Location: order.php?error=invalid_phone");
        exit();
    }

    /* Validate selected date is not in the past */
    date_default_timezone_set('Asia/Kuala_Lumpur');
    $today = date("Y-m-d");

    if ($date < $today) {
        mysqli_close($conn);
        header("Location: order.php?error=invalid_date");
        exit();
    }

    /* Validate terms and conditions checkbox */
    if (!$terms) {
        mysqli_close($conn);
        header("Location: order.php?error=terms_required");
        exit();
    }

    /* Start transaction so order and stock update happen together */
    mysqli_begin_transaction($conn);

    /* Check latest stock before saving order */
    foreach ($_SESSION['cart'] as $cart_key => $cart_item) {
        $cart_product_id = isset($cart_item['product_id']) ? intval($cart_item['product_id']) : 0;
        $cart_quantity = isset($cart_item['quantity']) ? intval($cart_item['quantity']) : 0;
        $selected_option = isset($cart_item['selected_option']) ? trim($cart_item['selected_option']) : "";

        if ($cart_product_id <= 0 || $cart_quantity <= 0) {
            mysqli_rollback($conn);
            mysqli_close($conn);
            header("Location: cart.php?error=invalid_cart");
            exit();
        }

        if ($selected_option != "") {

            /* Check option stock */
            $check_option_sql = "SELECT option_stock
                                 FROM product_option
                                 WHERE product_id = ?
                                 AND option_name = ?
                                 LIMIT 1";

            $check_option_stmt = mysqli_prepare($conn, $check_option_sql);
            mysqli_stmt_bind_param($check_option_stmt, "is", $cart_product_id, $selected_option);
            mysqli_stmt_execute($check_option_stmt);
            $check_option_result = mysqli_stmt_get_result($check_option_stmt);

            if (!$check_option_result || mysqli_num_rows($check_option_result) == 0) {
                mysqli_stmt_close($check_option_stmt);
                mysqli_rollback($conn);
                mysqli_close($conn);
                header("Location: cart.php?error=option_not_found");
                exit();
            }

            $check_option_row = mysqli_fetch_assoc($check_option_result);
            $current_option_stock = (int)$check_option_row['option_stock'];

            mysqli_stmt_close($check_option_stmt);

            if ($current_option_stock < $cart_quantity) {
                mysqli_rollback($conn);
                mysqli_close($conn);
                header("Location: cart.php?error=not_enough_stock");
                exit();
            }

        } else {

            /* Check normal product stock */
            $check_product_sql = "SELECT stock_quantity
                                  FROM product
                                  WHERE id = ?
                                  LIMIT 1";

            $check_product_stmt = mysqli_prepare($conn, $check_product_sql);
            mysqli_stmt_bind_param($check_product_stmt, "i", $cart_product_id);
            mysqli_stmt_execute($check_product_stmt);
            $check_product_result = mysqli_stmt_get_result($check_product_stmt);

            if (!$check_product_result || mysqli_num_rows($check_product_result) == 0) {
                mysqli_stmt_close($check_product_stmt);
                mysqli_rollback($conn);
                mysqli_close($conn);
                header("Location: cart.php?error=item_not_found");
                exit();
            }

            $check_product_row = mysqli_fetch_assoc($check_product_result);
            $current_product_stock = (int)$check_product_row['stock_quantity'];

            mysqli_stmt_close($check_product_stmt);

            if ($current_product_stock < $cart_quantity) {
                mysqli_rollback($conn);
                mysqli_close($conn);
                header("Location: cart.php?error=not_enough_stock");
                exit();
            }
        }
    }

    /* Insert validated order data into order table */
    $sql = "INSERT INTO `order` 
            (product1, quantity1, product2, quantity2, product3, quantity3, delivery, payment, date, time, name, email, phone, address) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sisisissssssss",
        $product1,
        $quantity1,
        $product2,
        $quantity2,
        $product3,
        $quantity3,
        $delivery,
        $payment,
        $date,
        $time,
        $name,
        $email,
        $phone,
        $address
    );

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_rollback($conn);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        echo "<script>alert('Database Error. Please try again.'); window.location.href = 'order.php';</script>";
        exit();
    }

    mysqli_stmt_close($stmt);

    /* Reduce stock after order is saved */
    foreach ($_SESSION['cart'] as $cart_key => $cart_item) {
        $cart_product_id = intval($cart_item['product_id']);
        $cart_quantity = intval($cart_item['quantity']);
        $selected_option = isset($cart_item['selected_option']) ? trim($cart_item['selected_option']) : "";

        if ($selected_option != "") {

            /* Reduce selected option stock */
            $update_option_sql = "UPDATE product_option
                                  SET option_stock = option_stock - ?
                                  WHERE product_id = ?
                                  AND option_name = ?
                                  AND option_stock >= ?";

            $update_option_stmt = mysqli_prepare($conn, $update_option_sql);
            mysqli_stmt_bind_param(
                $update_option_stmt,
                "iisi",
                $cart_quantity,
                $cart_product_id,
                $selected_option,
                $cart_quantity
            );

            if (!mysqli_stmt_execute($update_option_stmt)) {
                mysqli_rollback($conn);
                mysqli_stmt_close($update_option_stmt);
                mysqli_close($conn);

                echo "<script>alert('Stock update error. Please try again.'); window.location.href = 'cart.php';</script>";
                exit();
            }

            mysqli_stmt_close($update_option_stmt);

            /* Update main product stock to match the first option stock */
            $sync_stock_sql = "UPDATE product p
                               SET p.stock_quantity = (
                                   SELECT po.option_stock
                                   FROM product_option po
                                   WHERE po.product_id = p.id
                                   ORDER BY po.id ASC
                                   LIMIT 1
                               )
                               WHERE p.id = ?";

            $sync_stock_stmt = mysqli_prepare($conn, $sync_stock_sql);
            mysqli_stmt_bind_param($sync_stock_stmt, "i", $cart_product_id);
            mysqli_stmt_execute($sync_stock_stmt);
            mysqli_stmt_close($sync_stock_stmt);

        } else {

            /* Reduce normal product stock */
            $update_product_sql = "UPDATE product
                                   SET stock_quantity = stock_quantity - ?
                                   WHERE id = ?
                                   AND stock_quantity >= ?";

            $update_product_stmt = mysqli_prepare($conn, $update_product_sql);
            mysqli_stmt_bind_param(
                $update_product_stmt,
                "iii",
                $cart_quantity,
                $cart_product_id,
                $cart_quantity
            );

            if (!mysqli_stmt_execute($update_product_stmt)) {
                mysqli_rollback($conn);
                mysqli_stmt_close($update_product_stmt);
                mysqli_close($conn);

                echo "<script>alert('Stock update error. Please try again.'); window.location.href = 'cart.php';</script>";
                exit();
            }

            mysqli_stmt_close($update_product_stmt);
        }
    }

    /* Commit order and stock updates */
    mysqli_commit($conn);

    /* Clear saved order and cart data */
    unset($_SESSION['order_data']);
    $_SESSION['cart'] = [];

    /* Close database connection */
    mysqli_close($conn);

    echo "<script>alert('Order Submitted Successfully!'); window.location.href = 'index.php';</script>";
    exit();

} else {
    /* Redirect users who access this page directly */
    mysqli_close($conn);
    header("Location: order.php");
    exit();
}
?>
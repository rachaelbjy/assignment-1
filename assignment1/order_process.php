<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Process customer order form and store order information in the database.
*/

/* Start session to temporarily store form data if validation fails */
session_start();

/* Connect to the database */
require_once('settings.php');

/* Process the form only when it is submitted using POST */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* Sanitize order form inputs */
    $product1 = mysqli_real_escape_string($conn, trim($_POST['product1']));
    $quantity1 = isset($_POST['quantity1']) ? (int)$_POST['quantity1'] : 0;

    $product2 = isset($_POST['product2']) ? mysqli_real_escape_string($conn, trim($_POST['product2'])) : "";
    $quantity2 = !empty($_POST['quantity2']) ? (int)$_POST['quantity2'] : 0;

    $product3 = isset($_POST['product3']) ? mysqli_real_escape_string($conn, trim($_POST['product3'])) : "";
    $quantity3 = !empty($_POST['quantity3']) ? (int)$_POST['quantity3'] : 0;

    $delivery = mysqli_real_escape_string($conn, trim($_POST['delivery']));
    $payment = mysqli_real_escape_string($conn, trim($_POST['payment']));
    $date = mysqli_real_escape_string($conn, trim($_POST['date']));
    $time = mysqli_real_escape_string($conn, trim($_POST['time']));
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));
    $terms = isset($_POST['terms']) ? true : false;

    /* Store entered form data in session in case user needs to correct errors */
    $_SESSION['order_data'] = $_POST;

    /* Validate required fields */
    if (
        $product1 == "" || $delivery == "" || $payment == "" || $date == "" ||
        $time == "" || $name == "" || $email == "" || $phone == "" || $address == ""
    ) {
        header("Location: order.php?error=missing_fields");
        exit();
    }

    /* Validate first product quantity */
    if ($quantity1 < 1) {
        header("Location: order.php?error=invalid_quantity");
        exit();
    }

    /* Validate optional product quantities */
    if (($product2 != "" && $quantity2 < 1) || ($product3 != "" && $quantity3 < 1)) {
        header("Location: order.php?error=invalid_quantity");
        exit();
    }

    /* Validate email format */
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: order.php?error=invalid_email");
        exit();
    }

    /* Validate selected date is not in the past */
    date_default_timezone_set('Asia/Kuala_Lumpur');
    $today = date("Y-m-d");

    if ($date < $today) {
        header("Location: order.php?error=invalid_date");
        exit();
    }

    /* Validate terms and conditions checkbox */
    if (!$terms) {
        header("Location: order.php?error=terms_required");
        exit();
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

    /* Display confirmation message after successful order submission */
    if (mysqli_stmt_execute($stmt)) {
        unset($_SESSION['order_data']);
        echo "<script>alert('Order Submitted Successfully!'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Database Error. Please try again.'); window.location.href = 'order.php';</script>";
    }

    /* Close statement and database connection */
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

} else {
    /* Redirect users who access this page directly */
    header("Location: order.php");
    exit();
}
?>
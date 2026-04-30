<?php
session_start(); 
require_once('settings.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $product1 = mysqli_real_escape_string($conn, $_POST['product1']);
    $quantity1 = (int)$_POST['quantity1'];
    
    $product2 = isset($_POST['product2']) ? mysqli_real_escape_string($conn, $_POST['product2']) : "";
    $quantity2 = !empty($_POST['quantity2']) ? (int)$_POST['quantity2'] : 0;
    
    $product3 = isset($_POST['product3']) ? mysqli_real_escape_string($conn, $_POST['product3']) : "";
    $quantity3 = !empty($_POST['quantity3']) ? (int)$_POST['quantity3'] : 0;

    $delivery = mysqli_real_escape_string($conn, $_POST['delivery']);
    $payment = mysqli_real_escape_string($conn, $_POST['payment']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));
    $terms = isset($_POST['terms']) ? true : false;

    date_default_timezone_set('Asia/Kuala_Lumpur');
    $today = date("Y-m-d");
    if ($date < $today) {
        $_SESSION['order_data'] = $_POST;
        header("Location: order.php?error=invalid_date");
        exit();
    }

    $sql = "INSERT INTO `order` (product1, quantity1, product2, quantity2, product3, quantity3, delivery, payment, date, time, name, email, phone, address) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sisisissssssss", 
        $product1, $quantity1, $product2, $quantity2, $product3, $quantity3, 
        $delivery, $payment, $date, $time, $name, $email, $phone, $address);

    if (mysqli_stmt_execute($stmt)) {
        unset($_SESSION['order_data']); 
        echo "<script>alert('Order Submitted Successfully!'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Database Error.'); window.location.href = 'order.php';</script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    header("Location: order.php");
}
?>
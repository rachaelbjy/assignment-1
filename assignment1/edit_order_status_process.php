<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Process updated order delivery status submitted by administrator.
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

/* Process the form only when it is submitted using POST */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* Sanitize submitted data */
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $order_status = mysqli_real_escape_string($conn, trim($_POST['order_status']));

    /* List of allowed order statuses */
    $allowed_statuses = ['Pending', 'Preparing', 'Ready for Pickup', 'Delivered', 'Cancelled'];

    /* Validate submitted ID and status */
    if ($id <= 0 || !in_array($order_status, $allowed_statuses)) {
        header("Location: view_order.php");
        exit();
    }

    /* Update selected order status */
    $sql = "UPDATE `order` SET order_status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $order_status, $id);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        echo "<script>alert('Order status updated successfully.'); window.location.href = 'view_order.php';</script>";
        exit();
    } else {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        echo "<script>alert('Unable to update order status. Please try again.'); window.location.href = 'edit_order_status.php?id=$id';</script>";
        exit();
    }

} else {
    /* Redirect users who access this page directly */
    header("Location: view_order.php");
    exit();
}
?>
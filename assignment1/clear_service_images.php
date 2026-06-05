<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Clear image data for service records because services do not use images.
*/

session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

require_once('settings.php');

$sql = "UPDATE product
        SET image_path = '',
            image_source = ''
        WHERE category = 'Services'";

if (mysqli_query($conn, $sql)) {
    mysqli_close($conn);
    echo "<script>alert('Service image data has been cleared.'); window.location.href = 'view_product.php';</script>";
    exit();
} else {
    mysqli_close($conn);
    echo "<script>alert('Database error. Service image data was not cleared.'); window.location.href = 'view_product.php';</script>";
    exit();
}
?>
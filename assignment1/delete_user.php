<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Delete selected registered user account from the database.
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

/* Get selected user ID safely */
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

/* Redirect back if no valid ID is provided */
if ($id <= 0) {
    header("Location: view_register.php");
    exit();
}

/* Delete selected user account from user table */
$sql = "DELETE FROM `user` WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);

/* Redirect after deletion */
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    echo "<script>alert('User deleted successfully.'); window.location.href = 'view_register.php';</script>";
    exit();
} else {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    echo "<script>alert('Unable to delete user. Please try again.'); window.location.href = 'view_register.php';</script>";
    exit();
}
?>
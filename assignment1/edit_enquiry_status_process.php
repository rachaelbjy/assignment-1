<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Process updated enquiry status submitted by administrator.
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
    $enquiry_status = mysqli_real_escape_string($conn, trim($_POST['enquiry_status']));

    /* List of allowed enquiry statuses */
    $allowed_statuses = ['New', 'In Progress', 'Resolved'];

    /* Validate submitted ID and status */
    if ($id <= 0 || !in_array($enquiry_status, $allowed_statuses)) {
        header("Location: view_enquiry.php");
        exit();
    }

    /* Update selected enquiry status */
    $sql = "UPDATE `enquiry` SET enquiry_status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $enquiry_status, $id);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        echo "<script>alert('Enquiry status updated successfully.'); window.location.href = 'view_enquiry.php';</script>";
        exit();
    } else {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        echo "<script>alert('Unable to update enquiry status. Please try again.'); window.location.href = 'edit_enquiry_status.php?id=$id';</script>";
        exit();
    }

} else {
    /* Redirect users who access this page directly */
    header("Location: view_enquiry.php");
    exit();
}
?>
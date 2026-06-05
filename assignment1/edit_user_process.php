<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Process updated user account information submitted by administrator.
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

    /* Sanitize submitted form data */
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $fname = mysqli_real_escape_string($conn, trim($_POST['fname']));
    $lname = mysqli_real_escape_string($conn, trim($_POST['lname']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));
    $contact_pref = mysqli_real_escape_string($conn, trim($_POST['contact_pref']));
    $street = mysqli_real_escape_string($conn, trim($_POST['street']));
    $city = mysqli_real_escape_string($conn, trim($_POST['city']));
    $state = mysqli_real_escape_string($conn, trim($_POST['state']));
    $postcode = mysqli_real_escape_string($conn, trim($_POST['postcode']));

    /* Redirect back if no valid user ID is submitted */
    if ($id <= 0) {
        header("Location: view_register.php");
        exit();
    }

    /* Validate required fields */
    if (
        $fname == "" || $lname == "" || $email == "" || $phone == "" ||
        $username == "" || $password == "" || $contact_pref == "" ||
        $street == "" || $city == "" || $state == "" || $postcode == ""
    ) {
        header("Location: edit_user.php?id=$id&error=missing_fields");
        exit();
    }

    /* Validate name formats */
    if (!preg_match("/^[A-Za-z]{1,25}$/", $fname) || !preg_match("/^[A-Za-z]{1,25}$/", $lname)) {
        header("Location: edit_user.php?id=$id&error=invalid_name");
        exit();
    }

    /* Validate email format */
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: edit_user.php?id=$id&error=invalid_email");
        exit();
    }

    /* Validate username format */
    if (!preg_match("/^[A-Za-z]{1,10}$/", $username)) {
        header("Location: edit_user.php?id=$id&error=invalid_username");
        exit();
    }

    /* Validate password length */
    if (strlen($password) > 25) {
        header("Location: edit_user.php?id=$id&error=invalid_password");
        exit();
    }

    /* Validate postcode format */
    if (!preg_match("/^[0-9]{5}$/", $postcode)) {
        header("Location: edit_user.php?id=$id&error=invalid_postcode");
        exit();
    }

    /* Check whether updated email or username belongs to another user */
    $check_sql = "SELECT id FROM `user` WHERE (email = ? OR username = ?) AND id != ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "ssi", $email, $username, $id);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        mysqli_stmt_close($check_stmt);
        mysqli_close($conn);
        header("Location: edit_user.php?id=$id&error=duplicate_account");
        exit();
    }

    mysqli_stmt_close($check_stmt);

    /* Update selected user record */
    $update_sql = "UPDATE `user`
                   SET fname = ?, lname = ?, email = ?, phone = ?, username = ?, password = ?, contact_pref = ?, street = ?, city = ?, state = ?, postcode = ?
                   WHERE id = ?";

    $update_stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "sssssssssssi", $fname, $lname, $email, $phone, $username, $password, $contact_pref, $street, $city, $state, $postcode, $id);

    if (mysqli_stmt_execute($update_stmt)) {
        mysqli_stmt_close($update_stmt);
        mysqli_close($conn);
        echo "<script>alert('User updated successfully.'); window.location.href = 'view_register.php';</script>";
        exit();
    } else {
        mysqli_stmt_close($update_stmt);
        mysqli_close($conn);
        echo "<script>alert('Unable to update user. Please try again.'); window.location.href = 'edit_user.php?id=$id';</script>";
        exit();
    }

} else {
    /* Redirect users who access this page directly */
    header("Location: view_register.php");
    exit();
}
?>
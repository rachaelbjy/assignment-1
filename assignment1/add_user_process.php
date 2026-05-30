<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Process new user account created by administrator.
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

    /* Validate required fields */
    if (
        $fname == "" || $lname == "" || $email == "" || $phone == "" ||
        $username == "" || $password == "" || $contact_pref == "" ||
        $street == "" || $city == "" || $state == "" || $postcode == ""
    ) {
        echo "<script>alert('Please complete all required fields.'); window.location.href = 'add_user.php';</script>";
        exit();
    }

    /* Validate name formats */
    if (!preg_match("/^[A-Za-z]{1,25}$/", $fname) || !preg_match("/^[A-Za-z]{1,25}$/", $lname)) {
        echo "<script>alert('Name must contain letters only.'); window.location.href = 'add_user.php';</script>";
        exit();
    }

    /* Validate email format */
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Please enter a valid email address.'); window.location.href = 'add_user.php';</script>";
        exit();
    }

    /* Validate username format */
    if (!preg_match("/^[A-Za-z]{1,10}$/", $username)) {
        echo "<script>alert('Username must contain letters only, maximum 10 characters.'); window.location.href = 'add_user.php';</script>";
        exit();
    }

    /* Validate password length */
    if (strlen($password) > 25) {
        echo "<script>alert('Password must be maximum 25 characters.'); window.location.href = 'add_user.php';</script>";
        exit();
    }

    /* Validate postcode format */
    if (!preg_match("/^[0-9]{5}$/", $postcode)) {
        echo "<script>alert('Postcode must be exactly 5 digits.'); window.location.href = 'add_user.php';</script>";
        exit();
    }

    /* Check whether email or username already exists */
    $check_sql = "SELECT id FROM `user` WHERE email = ? OR username = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "ss", $email, $username);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        mysqli_stmt_close($check_stmt);
        mysqli_close($conn);
        echo "<script>alert('Email or username already exists.'); window.location.href = 'add_user.php';</script>";
        exit();
    }

    mysqli_stmt_close($check_stmt);

    /* Insert new user record */
    $insert_sql = "INSERT INTO `user`
                   (fname, lname, email, phone, username, password, contact_pref, street, city, state, postcode)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $insert_stmt = mysqli_prepare($conn, $insert_sql);
    mysqli_stmt_bind_param($insert_stmt, "sssssssssss", $fname, $lname, $email, $phone, $username, $password, $contact_pref, $street, $city, $state, $postcode);

    if (mysqli_stmt_execute($insert_stmt)) {
        mysqli_stmt_close($insert_stmt);
        mysqli_close($conn);
        echo "<script>alert('User added successfully.'); window.location.href = 'manage_users.php';</script>";
        exit();
    } else {
        mysqli_stmt_close($insert_stmt);
        mysqli_close($conn);
        echo "<script>alert('Unable to add user. Please try again.'); window.location.href = 'add_user.php';</script>";
        exit();
    }

} else {
    /* Redirect users who access this page directly */
    header("Location: manage_users.php");
    exit();
}
?>
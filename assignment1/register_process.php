<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Process customer registration form and store user information in the database.
*/

/* Start session to temporarily store form data if validation fails */
session_start();

/* Connect to the database */
require_once('settings.php');

/* Process the form only when it is submitted using POST */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* Sanitize registration form inputs */
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
    $terms = isset($_POST['terms_reg']) ? true : false;

    /* Store entered form data in session in case user needs to correct errors */
    $_SESSION['reg_data'] = [
        'fname' => $fname,
        'lname' => $lname,
        'email' => $email,
        'phone' => $phone,
        'username' => $username,
        'password' => $password,
        'contact_pref' => $contact_pref,
        'street' => $street,
        'city' => $city,
        'state' => $state,
        'postcode' => $postcode,
        'terms' => $terms
    ];

    /* Validate required fields */
    if (
        $fname == "" || $lname == "" || $email == "" || $phone == "" ||
        $username == "" || $password == "" || $contact_pref == "" ||
        $street == "" || $city == "" || $state == "" || $postcode == ""
    ) {
        header("Location: register.php?error=missing_fields");
        exit();
    }

    /* Validate email format */
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.php?error=invalid_email");
        exit();
    }

    /* Validate postcode format */
    if (!preg_match("/^[0-9]{5}$/", $postcode)) {
        header("Location: register.php?error=invalid_postcode");
        exit();
    }

    /* Validate username format based on the register form requirement */
    if (!preg_match("/^[A-Za-z]{1,10}$/", $username)) {
        header("Location: register.php?error=invalid_username");
        exit();
    }

    /* Validate password length only */
    if (strlen($password) > 25) {
        header("Location: register.php?error=invalid_password");
        exit();
    }

    /* Validate terms and conditions checkbox */
    if (!$terms) {
        header("Location: register.php?error=terms_required");
        exit();
    }

    /* Check whether email or username already exists in user table */
    $check_sql = "SELECT email, username FROM `user` WHERE email = ? OR username = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "ss", $email, $username);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    /* Redirect back if duplicate email or username is found */
    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        mysqli_stmt_bind_result($check_stmt, $db_email, $db_username);
        mysqli_stmt_fetch($check_stmt);

        if (strtolower($db_email ?? '') == strtolower($email)) {
            mysqli_stmt_close($check_stmt);
            mysqli_close($conn);
            header("Location: register.php?error=email_taken");
            exit();
        } else {
            mysqli_stmt_close($check_stmt);
            mysqli_close($conn);
            header("Location: register.php?error=username_taken");
            exit();
        }
    }

    mysqli_stmt_close($check_stmt);

    /* Insert validated registration data into user table */
    $user_sql = "INSERT INTO `user` 
            (fname, lname, email, phone, username, password, contact_pref, street, city, state, postcode) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $user_stmt = mysqli_prepare($conn, $user_sql);
    mysqli_stmt_bind_param($user_stmt, "sssssssssss", $fname, $lname, $email, $phone, $username, $password, $contact_pref, $street, $city, $state, $postcode);

    /* Display confirmation message after successful registration */
    if (mysqli_stmt_execute($user_stmt)) {
        unset($_SESSION['reg_data']);
        mysqli_stmt_close($user_stmt);
        mysqli_close($conn);
        echo "<script>alert('Registration Successful!'); window.location.href = 'login.php';</script>";
        exit();
    } else {
        mysqli_stmt_close($user_stmt);
        mysqli_close($conn);
        echo "<script>alert('Database Error. Please try again.'); window.location.href = 'register.php';</script>";
        exit();
    }

} else {
    /* Redirect users who access this page directly */
    header("Location: register.php");
    exit();
}
?>
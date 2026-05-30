<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Process user and admin login for Cacti-Succulent Kuching website.
*/

/* Start session to store login status */
session_start();

/* Connect to the database */
require_once('settings.php');

/* Process the form only when it is submitted using POST */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* Sanitize login form inputs */
    $username = mysqli_real_escape_string($conn, trim($_POST['login'] ?? '')); 
    $password = mysqli_real_escape_string($conn, trim($_POST['password'] ?? ''));

    /* Check whether the login details belong to an administrator */
    $admin_sql = "SELECT id FROM `admin` WHERE LOWER(username) = LOWER(?) AND LOWER(password) = LOWER(?)";
    $admin_stmt = mysqli_prepare($conn, $admin_sql);
    mysqli_stmt_bind_param($admin_stmt, "ss", $username, $password);
    mysqli_stmt_execute($admin_stmt);
    mysqli_stmt_store_result($admin_stmt);

    /* If admin account is found, create admin session and redirect to admin dashboard */
    if (mysqli_stmt_num_rows($admin_stmt) > 0) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;

        mysqli_stmt_close($admin_stmt);
        mysqli_close($conn);

        echo "<script>alert('Welcome back, Admin!'); window.location.href = 'admin_dashboard.php';</script>";
        exit();
    }

    mysqli_stmt_close($admin_stmt);

    /* If not admin, check whether the login details belong to a registered user */
    $user_sql = "SELECT id, fname FROM `user` WHERE username = ? AND password = ?";
    $user_stmt = mysqli_prepare($conn, $user_sql);
    mysqli_stmt_bind_param($user_stmt, "ss", $username, $password);
    mysqli_stmt_execute($user_stmt);
    mysqli_stmt_store_result($user_stmt);

    /* If user account is found, create user session and redirect to homepage */
    if (mysqli_stmt_num_rows($user_stmt) > 0) {
        $_SESSION['user_logged_in'] = true;
        $_SESSION['username'] = $username;

        mysqli_stmt_close($user_stmt);
        mysqli_close($conn);

        echo "<script>alert('Login Successful!'); window.location.href = 'index.php';</script>";
        exit();
    }

    mysqli_stmt_close($user_stmt);

    /* If login fails, preserve username and redirect back to login page */
    $_SESSION['login_data'] = ['login' => $username];
    header("Location: login.php?error=invalid_credentials");
    exit();

} else {
    /* Redirect users who access this page directly */
    header("Location: login.php");
    exit();
}
?>
<?php
session_start(); 
require_once('settings.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

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

    $check_sql = "SELECT email, username FROM `user` WHERE email = ? OR username = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "ss", $email, $username);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        mysqli_stmt_bind_result($check_stmt, $db_email, $db_username);
        mysqli_stmt_fetch($check_stmt);

        $_SESSION['reg_data'] = [
            'fname' => $fname, 'lname' => $lname, 'email' => $email, 
            'phone' => $phone, 'username' => $username, 'password' => $password,
            'contact_pref' => $contact_pref, 'street' => $street, 
            'city' => $city, 'state' => $state, 'postcode' => $postcode,
            'terms' => $terms
        ];

        if (strtolower($db_email) == strtolower($email)) {
            header("Location: register.php?error=email_taken");
        } else {
            header("Location: register.php?error=username_taken");
        }
        exit();
    }
    mysqli_stmt_close($check_stmt);

    $sql = "INSERT INTO `user` (fname, lname, email, phone, username, password, contact_pref, street, city, state, postcode) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssssss", $fname, $lname, $email, $phone, $username, $password, $contact_pref, $street, $city, $state, $postcode);

    if (mysqli_stmt_execute($stmt)) {
        unset($_SESSION['reg_data']);
        echo "<script>alert('Registration Successful!'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Database Error.'); window.location.href = 'register.php';</script>";
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    header("Location: register.php");
}
?>
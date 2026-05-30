<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Process customer enquiry form and store enquiry information in the database.
*/

/* Start session to temporarily store form data if validation fails */
session_start();

/* Connect to the database */
require_once('settings.php');

/* Process the form only when it is submitted using POST */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* Sanitize enquiry form inputs */
    $fname = mysqli_real_escape_string($conn, trim($_POST['fname']));
    $lname = mysqli_real_escape_string($conn, trim($_POST['lname']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $subject = mysqli_real_escape_string($conn, trim($_POST['subject']));
    $comments = mysqli_real_escape_string($conn, trim($_POST['comments']));

    /* Store entered form data in session in case user needs to correct errors */
    $_SESSION['enquiry_data'] = [
        'fname' => $fname,
        'lname' => $lname,
        'email' => $email,
        'phone' => $phone,
        'subject' => $subject,
        'comments' => $comments
    ];

    /* Validate required fields */
    if (
        $fname == "" || $lname == "" || $email == "" ||
        $phone == "" || $subject == "" || $comments == ""
    ) {
        header("Location: enquiry.php?error=missing_fields");
        exit();
    }

    /* Validate first name format based on the enquiry form requirement */
    if (!preg_match("/^[A-Za-z]{1,25}$/", $fname)) {
        header("Location: enquiry.php?error=invalid_fname");
        exit();
    }

    /* Validate last name format based on the enquiry form requirement */
    if (!preg_match("/^[A-Za-z]{1,25}$/", $lname)) {
        header("Location: enquiry.php?error=invalid_lname");
        exit();
    }

    /* Validate email format */
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: enquiry.php?error=invalid_email");
        exit();
    }

    /* Validate phone number format based on the enquiry form requirement */
    if (!preg_match("/^0[0-9]{9,10}$/", $phone)) {
        header("Location: enquiry.php?error=invalid_phone");
        exit();
    }

    /* Insert validated enquiry data into enquiry table */
    $sql = "INSERT INTO `enquiry`
            (fname, lname, email, phone, subject, comments)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss", $fname, $lname, $email, $phone, $subject, $comments);

    /* Display confirmation message after successful enquiry submission */
    if (mysqli_stmt_execute($stmt)) {
        unset($_SESSION['enquiry_data']);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        echo "<script>alert('Enquiry Submitted Successfully!'); window.location.href = 'index.php';</script>";
        exit();
    } else {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        echo "<script>alert('Database Error. Please try again.'); window.location.href = 'enquiry.php';</script>";
        exit();
    }

} else {
    /* Redirect users who access this page directly */
    header("Location: enquiry.php");
    exit();
}
?>
<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Log out user or administrator and destroy the active session.
*/

/* Start the session before clearing it */
session_start();

/* Remove all session variables */
$_SESSION = [];

/* Destroy the session */
session_destroy();

/* Redirect back to login page */
echo "<script>alert('You have been logged out successfully.'); window.location.href = 'login.php';</script>";
exit();
?>
<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Clear all items from shopping cart.
*/

/* Start session to access cart */
session_start();

/* Clear cart */
$_SESSION['cart'] = [];

/* Redirect back to cart */
header("Location: cart.php?cleared=success");
exit();
?>
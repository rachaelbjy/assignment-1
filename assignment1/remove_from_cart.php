<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Remove one item from shopping cart.
*/

/* Start session to access cart */
session_start();

/* Create cart if it does not exist */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* Get cart item key */
$cart_key = isset($_GET['item']) ? trim($_GET['item']) : "";

/* Remove selected cart item */
if ($cart_key != "" && isset($_SESSION['cart'][$cart_key])) {
    unset($_SESSION['cart'][$cart_key]);
}

/* Redirect back to cart */
header("Location: cart.php?removed=success");
exit();
?>
<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Export customer order records as a CSV file for administrator use.
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

/* Set CSV file headers */
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=orders_export.csv');

/* Open output stream */
$output = fopen('php://output', 'w');

/* Retrieve order records in ascending order */
$sql = "SELECT * FROM `order` ORDER BY id ASC";
$result = mysqli_query($conn, $sql);

/* Export order records */
if ($result) {
    $fields = mysqli_fetch_fields($result);
    $headers = [];

    foreach ($fields as $field) {
        $headers[] = $field->name;
    }

    fputcsv($output, $headers);

    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row);
    }
}

/* Close database connection */
mysqli_close($conn);
exit();
?>
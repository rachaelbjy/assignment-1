<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Export product and service records as a CSV file for administrator use.
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

/* Set CSV download headers */
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=products_services_export.csv');

/* Open output stream */
$output = fopen('php://output', 'w');

/* Write CSV header row */
fputcsv($output, [
    'ID',
    'Product or Service Name',
    'Category',
    'Product Options',
    'Description',
    'Price',
    'Image Path',
    'Stock Quantity'
]);

/* Retrieve product and service records */
$sql = "SELECT id, product_name, category, product_options, description, price, image_path, stock_quantity
        FROM product
        ORDER BY 
            CASE category
                WHEN 'Cacti' THEN 1
                WHEN 'Succulents' THEN 2
                WHEN 'Planting Accessories' THEN 3
                WHEN 'Services' THEN 4
                ELSE 5
            END,
            id ASC";

$result = mysqli_query($conn, $sql);

/* Write product and service records */
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [
            $row['id'],
            $row['product_name'],
            $row['category'],
            $row['product_options'],
            $row['description'],
            $row['price'],
            $row['image_path'],
            $row['stock_quantity']
        ]);
    }
}

/* Close database connection */
mysqli_close($conn);
exit();
?>
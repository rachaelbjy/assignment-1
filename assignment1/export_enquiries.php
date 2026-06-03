<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Export customer enquiry records as a CSV file for administrator use.
*/

session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

require_once('settings.php');

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=enquiries_export.csv');

$output = fopen('php://output', 'w');

$sql = "SELECT * FROM `enquiry` ORDER BY id ASC";
$result = mysqli_query($conn, $sql);

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

mysqli_close($conn);
exit();
?>
<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Establish database connection for Cacti-Succulent Kuching website.
*/

/* Database connection details */
$host = "localhost";
$user = "root";
$pwd = "";
$sql_db = "cacti_kuching_db";

/* Create database connection using mysqli */
$conn = mysqli_connect($host, $user, $pwd, $sql_db);

/* Stop the script if database connection fails */
if (!$conn) {
    die("<p>Database connection failure: " . mysqli_connect_error() . "</p>");
}
?>
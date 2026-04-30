<?php
$host = "localhost";
$user = "root";
$pwd  = "";
$sql_db = "cacti_kuching_db"; 

$conn = @mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("<p>Database connection failure</p>");
}
?>
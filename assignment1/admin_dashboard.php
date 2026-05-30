<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Admin dashboard for accessing registration, order, enquiry and user management pages.
*/

/* Start session to check admin login status */
session_start();

/* Redirect to login page if admin is not logged in */
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BASIC PAGE METADATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Admin dashboard for Cacti-Succulent Kuching">
    <meta name="keywords" content="admin, dashboard, management, cacti, succulent">
    <meta name="author" content="Rachael, Eleona, Amber">

    <title>Admin Dashboard | Cacti-Succulent Kuching</title>

    <!-- EXTERNAL STYLESHEETS AND ICONS -->
    <link rel="stylesheet" href="styles/style.css?v=adminfix1">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Montserrat:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
</head>

<body class="admin-page">

<!-- HEADER & NAVIGATION -->
<?php include 'header.inc'; ?>

    <!-- ADMIN DASHBOARD SECTION -->
    <main class="form-main">
        <div class="form-header">
            <h1>Admin Dashboard</h1>
            <p>Manage website records and customer information</p>
        </div>

        <div class="form-card">

            <!-- ADMIN MANAGEMENT LINKS -->
            <fieldset>
                <legend>Management Panel</legend>

                <div class="admin-dashboard-menu">
                    <a href="view_register.php" class="admin-menu-link">View Registrations</a>
                    <a href="view_order.php" class="admin-menu-link">View Orders</a>
                    <a href="view_enquiry.php" class="admin-menu-link">View Enquiries</a>
                    <a href="manage_users.php" class="admin-menu-link">Manage Users</a>
                    <a href="logout.php" class="admin-menu-link">Logout</a>
                </div>

            </fieldset>

        </div>
    </main>

<!-- WEBSITE FOOTER & COPYRIGHT -->
<?php include 'footer.inc'; ?>

<!-- BACK TO TOP BUTTON -->
<a href="#" class="back-to-top">▲</a>

</body>
</html>
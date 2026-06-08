<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Explain the PHP and MySQL enhancements implemented for Assignment 2.
*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BASIC PAGE METADATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="PHP and MySQL enhancements for Cacti-Succulent Kuching Assignment 2">
    <meta name="keywords" content="enhancement, PHP, MySQL, product management, product search, anti-spam">
    <meta name="author" content="Rachael, Eleona, Amber">

    <title>Enhancement 2 | Cacti-Succulent Kuching</title>

    <!-- EXTERNAL STYLESHEETS AND ICONS -->
    <link rel="stylesheet" href="styles/style.css?v=enhancement2final7">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Montserrat:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
</head>

<body class="profile-page enhancement-page">

<!-- HEADER & NAVIGATION -->
<?php include 'header.inc'; ?>

    <!-- ENHANCEMENT 2 MAIN CONTENT SECTION -->
    <main class="content-wrapper">

        <!-- ENHANCEMENT PAGE HEADER -->
        <div class="enhancements-header">
            <h1>Enhancement 2</h1>
            <p>PHP and MySQL enhancements implemented beyond the basic assignment requirements</p>
        </div>

        <!-- ENHANCEMENT LIST -->
        <div class="enhancements-list">

            <!-- ENHANCEMENT 1: PRODUCT AND SERVICE MANAGEMENT MODULE -->
            <article class="enhancement-row">

                <!-- ENHANCEMENT VIDEO -->
                <figure class="enhancement-figure">
                    <video class="enhancement-video" controls>
                        <source src="videos/enhancement2-product-management.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </figure>

                <div class="enhancement-text">
                    <h2>1. Product and Service Management Module</h2>

                    <p><strong>How it goes beyond the specified requirements:</strong> The basic assignment only requires register, order, enquiry, login, admin viewing pages and user management. This enhancement adds a full product and service management module for the business owner. Products, services, event packages, prices, stock, product options and image details can be managed through protected admin PHP pages instead of being edited manually in the HTML pages.</p>

                    <p><strong>How it was implemented:</strong> The module uses PHP, MySQL and mysqli to add, view, edit and delete product records. Product information is stored in the <code>product</code> table, while different product options are stored in the <code>product_option</code> table. The public product and service pages read the latest product data from the database. Stock status, sold out items, option prices, option stock, image paths and CSV export are also handled through PHP and MySQL.</p>

                    <p><strong>What a programmer needs to do:</strong> A programmer needs to create the product database tables, protect the management pages with admin session checking, validate submitted product data, process image paths, use mysqli commands for database operations, and update the public catalogue pages so they retrieve product information from MySQL.</p>

                    <p><strong>Files involved:</strong> <code>view_product.php</code>, <code>view_product_detail.php</code>, <code>add_product.php</code>, <code>add_product_process.php</code>, <code>edit_product.php</code>, <code>edit_product_process.php</code>, <code>delete_product.php</code>, <code>export_products.php</code>, <code>admin_dashboard.php</code>, <code>product1.php</code>, <code>product2.php</code>, <code>product3.php</code> and <code>service1.php</code>.</p>

                    <p><strong>Database tables used:</strong> <code>product</code> and <code>product_option</code></p>

                    <a href="view_product.php" class="btn-link1">View Product Management</a>
                </div>
            </article>

            <!-- ENHANCEMENT 2: PRODUCT SEARCH AND ANTI-SPAM FEATURE -->
            <article class="enhancement-row">

                <!-- ENHANCEMENT VIDEO -->
                <figure class="enhancement-figure">
                    <video class="enhancement-video" controls>
                        <source src="videos/enhancement2-search-antispam.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </figure>

                <div class="enhancement-text">
                    <h2>2. Product Search Feature and Anti-Spam Enquiry Protection</h2>

                    <p><strong>How it goes beyond the specified requirements:</strong> The basic assignment does not require a catalogue search feature, and the enquiry form only needs to validate and store submitted data. This enhancement improves both usability and security by allowing customers to search products and services, while also preventing repeated enquiry spam from the same email address.</p>

                    <p><strong>How it was implemented:</strong> The search feature uses PHP, MySQL and mysqli prepared statements to search product records based on customer keywords. The search area is reused through <code>product_service_search.inc</code>, and matching results link customers to the correct product or service page. The anti-spam feature checks the <code>enquiry</code> table before saving a new enquiry. If the same email submits too many enquiries within ten minutes, the submission is blocked and the user is asked to wait.</p>

                    <p><strong>What a programmer needs to do:</strong> A programmer needs to collect the search keyword using the GET method, run a safe mysqli keyword search, display matching results using <code>htmlspecialchars()</code>, store enquiry submission time in the database, count recent submissions from the same email, and block repeated submissions when the limit is reached.</p>

                    <p><strong>Files involved:</strong> <code>product_service_search.inc</code>, <code>product1.php</code>, <code>product2.php</code>, <code>product3.php</code>, <code>service1.php</code>, <code>enquiry.php</code>, <code>enquiry_process.php</code>, <code>view_enquiry.php</code> and <code>view_enquiry_detail.php</code>.</p>

                    <p><strong>Database tables used:</strong> <code>product</code> and <code>enquiry</code></p>

                    <a href="product1.php" class="btn-link2">Try Product Search</a>
                    <a href="enquiry.php" class="btn-link3">View Enquiry Form</a>
                </div>
            </article>

        </div>
    </main>

<!-- WEBSITE FOOTER & COPYRIGHT -->
<?php include 'footer.inc'; ?>

<!-- BACK TO TOP BUTTON -->
<a href="#" class="back-to-top">▲</a>

</body>
</html>
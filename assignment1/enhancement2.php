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
    <meta name="keywords" content="enhancement, PHP, MySQL, product management, shopping cart, checkout">
    <meta name="author" content="Rachael, Eleona, Amber">

    <title>Enhancement 2 | Cacti-Succulent Kuching</title>

    <!-- EXTERNAL STYLESHEETS AND ICONS -->
    <link rel="stylesheet" href="styles/style.css?v=enhancement2final3">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
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
                        <source src="videos/enhancement2.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </figure>

                <div class="enhancement-text">
                    <h2>1. Product and Service Management Module</h2>

                    <p><strong>How it goes beyond the basic requirements:</strong> The basic assignment requires register, order, enquiry, login and user management functions. This enhancement adds a complete product and service management module so the administrator can manage the actual products and services displayed on the public website.</p>

                    <p><strong>Main features included:</strong></p>

                    <div class="code-box">
                        Admin can add, view, edit and delete products and services<br>
                        Product and service data is stored in a MySQL product table<br>
                        Public product and service pages read directly from the database<br>
                        Admin can upload and update images for normal products and services<br>
                        Package services can be managed without image upload fields<br>
                        Admin can manage price, stock quantity, description, product options and image source links<br>
                        Items with options can have separate option price and separate option stock<br>
                        Low stock and sold out options are shown clearly in the analysis table<br>
                        Product summary shows option price, option stock and option stock status<br>
                        Sold out products and sold out options are disabled on the public product pages
                    </div>

                    <p><strong>What a programmer needs to do:</strong> A programmer needs to create a product table and a product option table, build protected admin pages, use PHP sessions to restrict access, and use mysqli commands to create, retrieve, update and delete records. The public product and service pages must retrieve data from the same database so that admin changes appear on the real website.</p>

                    <p><strong>Files involved:</strong> <code>view_product.php</code>, <code>view_product_detail.php</code>, <code>add_product.php</code>, <code>add_product_process.php</code>, <code>edit_product.php</code>, <code>edit_product_process.php</code>, <code>delete_product.php</code>, <code>admin_dashboard.php</code>, <code>product1.php</code>, <code>product2.php</code>, <code>product3.php</code> and <code>service1.php</code>.</p>

                    <p><strong>Database tables used:</strong> <code>product</code> and <code>product_option</code></p>

                    <a href="view_product.php" class="btn-link1">View Product Management</a>
                </div>
            </article>

            <!-- ENHANCEMENT 2: SHOPPING CART AND CHECKOUT MANAGEMENT MODULE -->
            <article class="enhancement-row">

                <!-- ENHANCEMENT VIDEO -->
                <figure class="enhancement-figure">
                    <video class="enhancement-video" controls>
                        <source src="videos/enhancement2.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </figure>

                <div class="enhancement-text">
                    <h2>2. Shopping Cart and Checkout Management Module</h2>

                    <p><strong>How it goes beyond the basic requirements:</strong> The basic order form only allows customers to submit order details. This enhancement adds a shopping cart system before checkout so customers can review selected products, options, quantities, subtotals and total price before submitting an order.</p>

                    <p><strong>Main features included:</strong></p>

                    <div class="code-box">
                        Customer can add products to a shopping cart<br>
                        Cart items are stored using PHP sessions<br>
                        Customer can update quantity, remove items and clear the cart<br>
                        Cart shows product image, selected option, price, quantity, subtotal and total<br>
                        Product option prices are supported for items with different choices<br>
                        Product option stock is checked before adding to cart<br>
                        Cart quantity cannot exceed available product or option stock<br>
                        Checkout page shows a clear order summary before submission<br>
                        Order form receives cart items automatically<br>
                        Successful order submission reduces product stock or selected option stock<br>
                        Cart is cleared after a successful order submission
                    </div>

                    <p><strong>What a programmer needs to do:</strong> A programmer needs to create PHP scripts for adding, updating, removing and clearing cart items. The cart data is stored in a session, while product and option stock are checked from MySQL using mysqli. During checkout, the order is saved in the order table and the related product or option stock is updated together.</p>

                    <p><strong>Files involved:</strong> <code>add_to_cart.php</code>, <code>cart.php</code>, <code>update_cart.php</code>, <code>remove_from_cart.php</code>, <code>clear_cart.php</code>, <code>order.php</code>, <code>order_process.php</code>, <code>product1.php</code>, <code>product2.php</code> and <code>product3.php</code>.</p>

                    <p><strong>Database tables used:</strong> <code>product</code>, <code>product_option</code> and <code>order</code></p>

                    <a href="cart.php" class="btn-link2">View Shopping Cart</a>
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
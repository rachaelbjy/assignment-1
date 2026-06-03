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
    <meta name="description" content="PHP and MySQL enhancement report for Cacti-Succulent Kuching Assignment 2">
    <meta name="keywords" content="enhancement, PHP, MySQL, admin dashboard, search, anti-spam, CSV export">
    <meta name="author" content="Rachael, Eleona, Amber">

    <title>Enhancement 2 | Cacti-Succulent Kuching</title>

    <!-- EXTERNAL STYLESHEETS AND ICONS -->
    <link rel="stylesheet" href="styles/style.css?v=adminfix1">
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
            <p>PHP and MySQL enhancements for admin management, customer searching, data export and enquiry protection</p>
        </div>

        <!-- ENHANCEMENT LIST -->
        <div class="enhancements-list">

            <!-- ENHANCEMENT 1: ADMIN MANAGEMENT, ANALYTICS AND EXPORT MODULE -->
            <article class="enhancement-row">
                <figure class="enhancement-figure">
                    <video class="enhancement-video" preload="auto" autoplay loop muted playsinline>
                        <source src="videos/recording-admin-dashboard.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </figure>

                <div class="enhancement-text">
                    <h2>1. Admin Management, Analytics and CSV Export Module</h2>

                    <p><strong>How it goes beyond basic requirements:</strong> The basic requirement asks the administrator to view registration, order and enquiry records. This enhancement improves the admin area into a more practical business management system. The dashboard summarises total users, orders, enquiries and pending orders, displays the latest user, order and enquiry activity, and shows order/enquiry status breakdowns in a compact overview. The Users, Orders and Enquiries pages also include keyword search, filters and CSV export buttons, allowing the business owner to find and download records more efficiently instead of manually checking phpMyAdmin.</p>

                    <p><strong>Main features included:</strong></p>
                    <div class="code-box">
                        Admin Dashboard: summary statistics, latest activity and status breakdown<br>
                        Users page: search users, filter by state, add/view/edit/delete users and export CSV<br>
                        Orders page: search orders, filter by status, update order status and export CSV<br>
                        Enquiries page: search enquiries, filter by status, update enquiry status and export CSV
                    </div>

                    <p><strong>What a programmer needs to do:</strong> A programmer needs to create protected admin-only pages using PHP sessions, retrieve records from MySQL using mysqli, and write SQL queries using <code>COUNT()</code>, <code>GROUP BY</code>, <code>ORDER BY</code>, <code>LIKE</code> and <code>LEFT JOIN</code>. The CSV export feature uses PHP output headers and <code>fputcsv()</code> to generate downloadable spreadsheet-compatible files directly from database results.</p>

                    <p><strong>Files involved:</strong> <code>admin_dashboard.php</code>, <code>view_register.php</code>, <code>view_order.php</code>, <code>view_enquiry.php</code>, <code>view_register_detail.php</code>, <code>view_order_detail.php</code>, <code>view_enquiry_detail.php</code>, <code>edit_user.php</code>, <code>edit_user_process.php</code>, <code>delete_user.php</code>, <code>add_user.php</code>, <code>add_user_process.php</code>, <code>edit_order_status.php</code>, <code>edit_order_status_process.php</code>, <code>edit_enquiry_status.php</code>, <code>edit_enquiry_status_process.php</code>, <code>export_users.php</code>, <code>export_orders.php</code>, <code>export_enquiries.php</code>.</p>

                    <p><strong>Database features used:</strong> <code>user</code>, <code>order</code> and <code>enquiry</code> tables, with additional status fields such as <code>order_status</code> and <code>enquiry_status</code>.</p>

                    <p><strong>Source / Citation:</strong> Self-developed using PHP sessions, mysqli queries and CSV output techniques. Reference: <a href="https://www.php.net/manual/en/function.fputcsv.php" target="_blank">PHP Manual: fputcsv()</a>, <a href="https://www.php.net/manual/en/book.mysqli.php" target="_blank">PHP Manual: MySQLi</a></p>

                    <a href="admin_dashboard.php" class="btn-link1">View Feature</a>
                </div>
            </article>

            <!-- ENHANCEMENT 2: PRODUCT AND SERVICE SEARCH FEATURE -->
            <article class="enhancement-row">
                <figure class="enhancement-figure">
                    <video class="enhancement-video" preload="auto" autoplay loop muted playsinline>
                        <source src="videos/recording-product-search.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </figure>

                <div class="enhancement-text">
                    <h2>2. PHP Product and Service Search Feature</h2>

                    <p><strong>How it goes beyond basic requirements:</strong> The basic website allows users to browse product and service pages manually. This enhancement improves customer usability by adding a PHP-based keyword search bar to product and service pages. When customers type a keyword, matching products or services are displayed as clickable search results. Clicking a result brings the customer directly to the related item on the same page, making the shopping experience faster and more user-friendly.</p>

                    <p><strong>Main features included:</strong></p>
                    <div class="code-box">
                        Customer enters keyword in the product/service search box<br>
                        PHP compares the keyword with product and service names, categories and related keywords<br>
                        Matching results are displayed in a clean dropdown-style result box<br>
                        Customer clicks a result and jumps directly to the matching product or service section
                    </div>

                    <p><strong>What a programmer needs to do:</strong> A programmer needs to create searchable arrays for product and service data, read the customer keyword using <code>$_GET</code>, compare the keyword against the stored values, and output matching results using PHP. Each product or service card also needs an ID so the search result link can jump directly to the correct section using an anchor link.</p>

                    <p><strong>Files involved:</strong> <code>product1.php</code>, <code>product2.php</code>, <code>product3.php</code>, <code>service1.php</code>, and <code>styles/style.css</code>.</p>

                    <p><strong>Why this is useful:</strong> Customers do not need to scroll through long product and service pages. They can search by words related to cactus, succulent, accessories, repotting, consultation or plant care and quickly locate the relevant item.</p>

                    <p><strong>Source / Citation:</strong> Self-developed using PHP GET requests and HTML anchor links. Reference: <a href="https://www.php.net/manual/en/reserved.variables.get.php" target="_blank">PHP Manual: $_GET</a>, <a href="https://developer.mozilla.org/en-US/docs/Web/HTML/Element/a" target="_blank">MDN Web Docs: Anchor element</a></p>

                    <a href="product1.php" class="btn-link2">View Feature</a>
                </div>
            </article>

            <!-- ENHANCEMENT 3: ANTI-SPAM ENQUIRY PROTECTION -->
            <article class="enhancement-row">
                <figure class="enhancement-figure">
                    <video class="enhancement-video" preload="auto" autoplay loop muted playsinline>
                        <source src="videos/recording-antispam.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </figure>

                <div class="enhancement-text">
                    <h2>3. Anti-Spam Enquiry Protection</h2>

                    <p><strong>How it goes beyond basic requirements:</strong> The basic enquiry form stores customer enquiries in the database after validation. This enhancement adds server-side spam protection by limiting repeated submissions from the same email address within a short time period. If the same email submits too many enquiries within 10 minutes, the system blocks the new submission and asks the user to wait before trying again.</p>

                    <p><strong>Main features included:</strong></p>
                    <div class="code-box">
                        The enquiry table stores the submission time using submitted_at<br>
                        PHP checks how many enquiries the same email submitted recently<br>
                        The first three enquiries within 10 minutes are allowed<br>
                        The fourth enquiry within 10 minutes is blocked
                    </div>

                    <p><strong>What a programmer needs to do:</strong> A programmer needs to add a timestamp column to the enquiry table, then use a mysqli prepared statement to count recent enquiries from the same email address. If the recent count is already at the limit, PHP stops the insert operation and displays an error message instead of saving the spam enquiry.</p>

                    <p><strong>Files involved:</strong> <code>enquiry_process.php</code>, <code>enquiry.php</code>, and the MySQL <code>enquiry</code> table.</p>

                    <p><strong>Database change required:</strong></p>
                    <div class="code-box">
                        ALTER TABLE enquiry<br>
                        ADD submitted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
                    </div>

                    <p><strong>Source / Citation:</strong> Self-developed using server-side validation, mysqli prepared statements and timestamp comparison. Reference: <a href="https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php" target="_blank">PHP Manual: MySQLi Prepared Statements</a>, <a href="https://dev.mysql.com/doc/refman/8.0/en/date-and-time-functions.html" target="_blank">MySQL Manual: Date and Time Functions</a></p>

                    <a href="enquiry.php" class="btn-link3">View Feature</a>
                </div>
            </article>

            <!-- ENHANCEMENT 4: ORDER AND ENQUIRY STATUS WORKFLOW -->
            <article class="enhancement-row">
                <figure class="enhancement-figure">
                    <video class="enhancement-video" preload="auto" autoplay loop muted playsinline>
                        <source src="videos/recording-status-management.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </figure>

                <div class="enhancement-text">
                    <h2>4. Order and Enquiry Status Workflow</h2>

                    <p><strong>How it goes beyond basic requirements:</strong> The basic admin pages only display submitted records. This enhancement allows the administrator to actively manage record progress by updating order and enquiry statuses. Orders can be marked as Pending, Preparing, Ready for Pickup, Delivered or Cancelled. Enquiries can be marked as New, In Progress or Resolved. This gives the business owner a simple workflow system for tracking customer requests.</p>

                    <p><strong>Main features included:</strong></p>
                    <div class="code-box">
                        Orders: Pending, Preparing, Ready for Pickup, Delivered, Cancelled<br>
                        Enquiries: New, In Progress, Resolved<br>
                        Admin can edit status from the summary table<br>
                        Dashboard overview counts each status category automatically
                    </div>

                    <p><strong>What a programmer needs to do:</strong> A programmer needs to add status columns in MySQL, display the current status in the summary pages, create edit-status forms, validate allowed status values, and update the selected record using prepared SQL update statements. The dashboard then groups and counts the statuses to help the business owner understand current workload.</p>

                    <p><strong>Files involved:</strong> <code>view_order.php</code>, <code>edit_order_status.php</code>, <code>edit_order_status_process.php</code>, <code>view_enquiry.php</code>, <code>edit_enquiry_status.php</code>, <code>edit_enquiry_status_process.php</code>, and <code>admin_dashboard.php</code>.</p>

                    <p><strong>Database changes required:</strong></p>
                    <div class="code-box">
                        ALTER TABLE `order` ADD order_status VARCHAR(30) NOT NULL DEFAULT 'Pending';<br>
                        ALTER TABLE enquiry ADD enquiry_status VARCHAR(30) NOT NULL DEFAULT 'New';
                    </div>

                    <p><strong>Source / Citation:</strong> Self-developed using MySQL update queries and PHP form processing. Reference: <a href="https://dev.mysql.com/doc/refman/8.0/en/update.html" target="_blank">MySQL Manual: UPDATE Statement</a>, <a href="https://www.php.net/manual/en/mysqli-stmt.bind-param.php" target="_blank">PHP Manual: mysqli_stmt::bind_param</a></p>

                    <a href="view_order.php" class="btn-link4">View Feature</a>
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
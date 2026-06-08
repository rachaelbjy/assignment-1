<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Display and manage customer enquiry records for the administrator.
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

/* Convert old stored subject values into the same full subject names shown in enquiry.php */
function get_enquiry_subject_display($subject) {
    $subject_map = [
        "general" => "General Inquiry",
        "feedback" => "Feedback & Suggestions",
        "hospital" => "The Plant Hospital",
        "boarding" => "Plant Boarding",
        "custom-terrarium" => "Custom Terrariums",
        "workshop" => "Terrarium Workshop",
        "gift-standard" => "Standard Package",
        "gift-luxury" => "Luxury Package",
        "gift-premium" => "Premium Package"
    ];

    return isset($subject_map[$subject]) ? $subject_map[$subject] : $subject;
}

/* Get selected enquiry status filter */
$filter_status = isset($_GET['status']) ? trim($_GET['status']) : "All";

/* Get enquiry search keyword */
$search_keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : "";

/* Allowed enquiry status filters */
$allowed_statuses = ["All", "New", "In Progress", "Resolved"];

if (!in_array($filter_status, $allowed_statuses)) {
    $filter_status = "All";
}

/* Prepare keyword for SQL LIKE search */
$search_like = "%" . $search_keyword . "%";

/* Retrieve important enquiry summary records and match registered user using email in ascending order */
if ($filter_status == "All" && $search_keyword == "") {
    $sql = "SELECT e.id, e.fname, e.lname, e.email, e.subject, e.enquiry_status, u.username 
            FROM `enquiry` e
            LEFT JOIN `user` u ON e.email = u.email
            ORDER BY e.id ASC";
    $result = mysqli_query($conn, $sql);

} else if ($filter_status != "All" && $search_keyword == "") {
    $sql = "SELECT e.id, e.fname, e.lname, e.email, e.subject, e.enquiry_status, u.username 
            FROM `enquiry` e
            LEFT JOIN `user` u ON e.email = u.email
            WHERE e.enquiry_status = ?
            ORDER BY e.id ASC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $filter_status);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

} else if ($filter_status == "All" && $search_keyword != "") {
    $sql = "SELECT e.id, e.fname, e.lname, e.email, e.subject, e.enquiry_status, u.username 
            FROM `enquiry` e
            LEFT JOIN `user` u ON e.email = u.email
            WHERE e.fname LIKE ? 
               OR e.lname LIKE ? 
               OR e.email LIKE ? 
               OR e.subject LIKE ? 
               OR e.comments LIKE ?
            ORDER BY e.id ASC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $search_like, $search_like, $search_like, $search_like, $search_like);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

} else {
    $sql = "SELECT e.id, e.fname, e.lname, e.email, e.subject, e.enquiry_status, u.username 
            FROM `enquiry` e
            LEFT JOIN `user` u ON e.email = u.email
            WHERE e.enquiry_status = ?
            AND (
                e.fname LIKE ? 
                OR e.lname LIKE ? 
                OR e.email LIKE ? 
                OR e.subject LIKE ? 
                OR e.comments LIKE ?
            )
            ORDER BY e.id ASC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss", $filter_status, $search_like, $search_like, $search_like, $search_like, $search_like);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BASIC PAGE METADATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="View and manage customer enquiry records for Cacti-Succulent Kuching">
    <meta name="keywords" content="admin, enquiries, customer enquiries, enquiry status, cacti, succulent">
    <meta name="author" content="Rachael, Eleona, Amber">

    <title>View Enquiries | Cacti-Succulent Kuching</title>

    <!-- EXTERNAL STYLESHEETS AND ICONS -->
    <link rel="stylesheet" href="styles/style.css?v=adminfix2">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Montserrat:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
</head>

<body class="admin-page view-enquiry-page">

<!-- HEADER & NAVIGATION -->
<?php include 'header.inc'; ?>

    <!-- VIEW ENQUIRIES SECTION -->
    <main class="form-main">
        <div class="form-header">
            <h1>View Enquiries</h1>
            <p>Search, filter, view details, and update enquiry status</p>
        </div>

        <div class="form-card">

            <!-- ENQUIRY MANAGEMENT TABLE -->
            <fieldset class="admin-record-fieldset">
                <legend>Enquiries</legend>

                <!-- ENQUIRY SEARCH AND STATUS FILTER -->
                <form action="view_enquiry.php" method="get">
                    <div class="form-row">
                        <div class="input-group">
                            <label for="keyword">Search Enquiry</label>
                            <input type="text" id="keyword" name="keyword" value="<?php echo htmlspecialchars($search_keyword); ?>" placeholder="Name, email, subject or comments">
                        </div>

                        <div class="input-group">
                            <label for="status">Filter by Status</label>
                            <select id="status" name="status">
                                <option value="All" <?php echo ($filter_status == "All") ? "selected" : ""; ?>>All</option>
                                <option value="New" <?php echo ($filter_status == "New") ? "selected" : ""; ?>>New</option>
                                <option value="In Progress" <?php echo ($filter_status == "In Progress") ? "selected" : ""; ?>>In Progress</option>
                                <option value="Resolved" <?php echo ($filter_status == "Resolved") ? "selected" : ""; ?>>Resolved</option>
                            </select>
                        </div>
                    </div>

                    <div class="admin-filter-actions">
                        <input type="submit" value="Apply">
                        <a href="view_enquiry.php" class="admin-action-link">Clear</a>
                    </div>
                </form>

                <!-- TOTAL ENQUIRIES -->
                <div class="admin-list-toolbar">
                    <p>Total enquiries: <?php echo ($result) ? mysqli_num_rows($result) : 0; ?></p>
                </div>

                <!-- ENQUIRY SUMMARY TABLE -->
                <div class="admin-table-wrapper">
                    <table class="admin-summary-table">
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Account</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Update</th>
                            <th>Details</th>
                        </tr>

                        <?php
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $is_registered = !empty($row['username']);
                                $row_class = $is_registered ? "" : " class='admin-unregistered-row'";
                                $status_text = $is_registered ? "Registered" : "Not Registered";
                                $status_class = $is_registered ? "admin-status-registered" : "admin-status-unregistered";

                                echo "<tr" . $row_class . ">";
                                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['fname'] . " " . $row['lname']) . "</td>";
                                echo "<td><span class='admin-status-badge " . $status_class . "'>" . $status_text . "</span></td>";
                                echo "<td>" . htmlspecialchars(get_enquiry_subject_display($row['subject'])) . "</td>";
                                echo "<td>" . htmlspecialchars($row['enquiry_status']) . "</td>";
                                echo "<td><a href='edit_enquiry_status.php?id=" . urlencode($row['id']) . "' class='admin-view-btn'>Edit</a></td>";
                                echo "<td><a href='view_enquiry_detail.php?id=" . urlencode($row['id']) . "' class='admin-view-btn'>View</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No enquiry records found.</td></tr>";
                        }
                        ?>
                    </table>
                </div>

                <!-- ADMIN NAVIGATION BUTTONS -->
                <div class="admin-action-buttons">
                    <a href="admin_dashboard.php" class="admin-action-link">Back to Dashboard</a>
                    <a href="export_enquiries.php" class="admin-action-link">Export CSV</a>
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

<?php
/* Close prepared statement if filter or search was used */
if (isset($stmt)) {
    mysqli_stmt_close($stmt);
}

/* Close database connection */
mysqli_close($conn);
?>
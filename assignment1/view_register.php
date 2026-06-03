<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Display registration records and manage registered users for the administrator.
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

/* Get user search keyword */
$search_keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : "";

/* Get selected state filter */
$filter_state = isset($_GET['state']) ? trim($_GET['state']) : "All";

/* Allowed state filters */
$states = ['All', 'Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Perak', 'Perlis', 'Pulau Pinang', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu', 'Kuala Lumpur', 'Labuan', 'Putrajaya'];

if (!in_array($filter_state, $states)) {
    $filter_state = "All";
}

/* Prepare keyword for SQL LIKE search */
$search_like = "%" . $search_keyword . "%";

/* Retrieve registration and user records from the user table in ascending order */
if ($search_keyword == "" && $filter_state == "All") {
    $sql = "SELECT id, fname, lname, username, email, phone, city, state 
            FROM `user` 
            ORDER BY id ASC";
    $result = mysqli_query($conn, $sql);

} else if ($search_keyword != "" && $filter_state == "All") {
    $sql = "SELECT id, fname, lname, username, email, phone, city, state 
            FROM `user`
            WHERE fname LIKE ?
               OR lname LIKE ?
               OR username LIKE ?
               OR email LIKE ?
               OR phone LIKE ?
               OR city LIKE ?
               OR state LIKE ?
            ORDER BY id ASC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssss", $search_like, $search_like, $search_like, $search_like, $search_like, $search_like, $search_like);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

} else if ($search_keyword == "" && $filter_state != "All") {
    $sql = "SELECT id, fname, lname, username, email, phone, city, state 
            FROM `user`
            WHERE state = ?
            ORDER BY id ASC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $filter_state);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

} else {
    $sql = "SELECT id, fname, lname, username, email, phone, city, state 
            FROM `user`
            WHERE state = ?
            AND (
                fname LIKE ?
                OR lname LIKE ?
                OR username LIKE ?
                OR email LIKE ?
                OR phone LIKE ?
                OR city LIKE ?
                OR state LIKE ?
            )
            ORDER BY id ASC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssss", $filter_state, $search_like, $search_like, $search_like, $search_like, $search_like, $search_like, $search_like);
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
    <meta name="description" content="View registrations and manage registered users for Cacti-Succulent Kuching">
    <meta name="keywords" content="admin, registrations, users, user management, cacti, succulent">
    <meta name="author" content="Rachael, Eleona, Amber">

    <title>View Registrations and Users | Cacti-Succulent Kuching</title>

    <!-- EXTERNAL STYLESHEETS AND ICONS -->
    <link rel="stylesheet" href="styles/style.css?v=adminfix2">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Montserrat:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
</head>

<body class="admin-page view-register-page">

<!-- HEADER & NAVIGATION -->
<?php include 'header.inc'; ?>

    <!-- VIEW REGISTRATIONS AND USERS SECTION -->
    <main class="form-main">
        <div class="form-header">
            <h1>View Registrations and Users</h1>
            <p>View registration records and manage user accounts</p>
        </div>

        <div class="form-card">

            <!-- REGISTRATION AND USER MANAGEMENT TABLE -->
            <fieldset class="admin-user-fieldset">
                <legend>Users</legend>

                <!-- USER SEARCH AND STATE FILTER FORM -->
                <form action="view_register.php" method="get">
                    <div class="form-row">
                        <div class="input-group">
                            <label for="keyword">Search User</label>
                            <input type="text" id="keyword" name="keyword" value="<?php echo htmlspecialchars($search_keyword); ?>" placeholder="Name, email, phone or city">
                        </div>

                        <div class="input-group">
                            <label for="state">Filter by State</label>
                            <select id="state" name="state">
                                <?php
                                foreach ($states as $state_option) {
                                    $selected = ($filter_state == $state_option) ? "selected" : "";
                                    echo "<option value=\"" . htmlspecialchars($state_option) . "\" $selected>" . htmlspecialchars($state_option) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="admin-filter-actions">
                        <input type="submit" value="Apply">
                        <a href="view_register.php" class="admin-action-link">Clear</a>
                    </div>
                </form>

                <!-- TOTAL USERS AND ADD USER BUTTON -->
                <div class="admin-list-toolbar">
                    <p>Total users: <?php echo ($result) ? mysqli_num_rows($result) : 0; ?></p>

                    <div class="admin-action-buttons">
                        <a href="add_user.php" class="admin-action-link">Add New User</a>
                    </div>
                </div>

                <!-- USER SUMMARY TABLE -->
                <div class="admin-table-wrapper">
                    <table class="admin-summary-table">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Details</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>

                        <?php
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td><a href='view_register_detail.php?id=" . urlencode($row['id']) . "' class='admin-view-btn'>View</a></td>";
                                echo "<td><a href='edit_user.php?id=" . urlencode($row['id']) . "' class='admin-view-btn'>Edit</a></td>";
                                echo "<td><a href='delete_user.php?id=" . urlencode($row['id']) . "' class='admin-view-btn' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No user records found.</td></tr>";
                        }
                        ?>
                    </table>
                </div>

                <!-- ADMIN NAVIGATION BUTTONS -->
                <div class="admin-action-buttons">
                    <a href="admin_dashboard.php" class="admin-action-link">Back to Dashboard</a>
                    <a href="export_users.php" class="admin-action-link">Export CSV</a>
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
/* Close prepared statement if search or filter was used */
if (isset($stmt)) {
    mysqli_stmt_close($stmt);
}

/* Close database connection */
mysqli_close($conn);
?>
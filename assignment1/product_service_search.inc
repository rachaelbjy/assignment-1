<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Reusable product and service search section using database records.
*/

/* Connect to the database */
require_once('settings.php');

/* Get current page name */
$current_page = basename($_SERVER['PHP_SELF']);

/* Get search keyword */
$search_keyword = isset($_GET['search']) ? trim($_GET['search']) : "";

/* Convert category into the correct website page */
function get_product_service_page($category) {
    if ($category == "Cacti") {
        return "product1.php";
    } else if ($category == "Succulents") {
        return "product2.php";
    } else if ($category == "Planting Accessories") {
        return "product3.php";
    } else if ($category == "Services") {
        return "service1.php";
    } else {
        return "index.php";
    }
}

/* Create a safe anchor ID based on product or service name */
function get_product_service_anchor($product_name) {
    return strtolower(str_replace(' ', '-', $product_name));
}

/* Search matching product and service records */
$matched_items = [];

if ($search_keyword != "" && strlen($search_keyword) >= 2) {
    $search_like = "%" . $search_keyword . "%";

    $sql = "SELECT product_name, category, product_options, description
            FROM product
            WHERE product_name LIKE ?
               OR category LIKE ?
               OR product_options LIKE ?
               OR description LIKE ?
            ORDER BY 
                CASE category
                    WHEN 'Cacti' THEN 1
                    WHEN 'Succulents' THEN 2
                    WHEN 'Planting Accessories' THEN 3
                    WHEN 'Services' THEN 4
                    ELSE 5
                END,
                product_name ASC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $search_like, $search_like, $search_like, $search_like);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $matched_items[] = $row;
        }
    }
}
?>

<!-- PRODUCT AND SERVICE SEARCH SECTION -->
<section class="site-search-section">
    <form action="<?php echo htmlspecialchars($current_page); ?>" method="get" class="site-search-form">
        <label for="search">Search Products and Services</label>

        <div class="site-search-row">
            <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search_keyword); ?>" placeholder="Search cactus, succulent, pot, soil or service">
            <input type="submit" value="Search">
        </div>
    </form>

    <?php
    if ($search_keyword != "" && strlen($search_keyword) < 2) {
        echo "<div class='site-search-results-dropdown'>";
        echo "<p class='site-search-empty'>Please enter at least 2 characters to search.</p>";
        echo "</div>";

    } else if ($search_keyword != "" && strlen($search_keyword) >= 2) {
        if (count($matched_items) > 0) {
            echo "<div class='site-search-results-dropdown'>";
            echo "<p class='site-search-result-title'>Search results for \"" . htmlspecialchars($search_keyword) . "\"</p>";
            echo "<ul>";

            foreach ($matched_items as $item) {
                $page = get_product_service_page($item["category"]);
                $anchor = get_product_service_anchor($item["product_name"]);

                echo "<li>";
                echo "<a href='" . htmlspecialchars($page) . "#" . htmlspecialchars($anchor) . "'>";
                echo "<span class='site-search-name'>" . htmlspecialchars($item["product_name"]) . "</span>";
                echo "<span class='site-search-category'>" . htmlspecialchars($item["category"]) . "</span>";
                echo "</a>";
                echo "</li>";
            }

            echo "</ul>";
            echo "</div>";
        } else {
            echo "<div class='site-search-results-dropdown'>";
            echo "<p class='site-search-empty'>No matching products or services found.</p>";
            echo "</div>";
        }
    }
    ?>
</section>
<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Display and manage product and service records for the administrator.
*/

session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

require_once('settings.php');

$create_option_table_sql = "
    CREATE TABLE IF NOT EXISTS product_option (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        option_name VARCHAR(100) NOT NULL,
        option_price DECIMAL(10,2) NOT NULL,
        option_stock INT NOT NULL DEFAULT 0,
        FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
    )
";

mysqli_query($conn, $create_option_table_sql);

$filter_category = isset($_GET['category']) ? trim($_GET['category']) : "All";
$search_keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : "";

$allowed_categories = ["All", "Cacti", "Succulents", "Planting Accessories", "Services"];

if (!in_array($filter_category, $allowed_categories)) {
    $filter_category = "All";
}

$search_like = "%" . $search_keyword . "%";

$category_order_sql = "
    CASE category
        WHEN 'Cacti' THEN 1
        WHEN 'Succulents' THEN 2
        WHEN 'Planting Accessories' THEN 3
        WHEN 'Services' THEN 4
        ELSE 5
    END
";

function get_admin_product_options($conn, $product_id) {
    $options = [];

    $sql = "SELECT option_name, option_price, option_stock
            FROM product_option
            WHERE product_id = ?
            ORDER BY id ASC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $options[] = $row;
        }
    }

    mysqli_stmt_close($stmt);

    return $options;
}

function format_admin_price_display($conn, $product_id, $price) {
    $options = get_admin_product_options($conn, $product_id);
    $price_lines = [];

    if (count($options) > 0) {
        foreach ($options as $option) {
            $price_lines[] = htmlspecialchars($option['option_name']) . ": RM " . htmlspecialchars(number_format((float)$option['option_price'], 2));
        }

        return implode("<br>", $price_lines);
    }

    return "RM " . htmlspecialchars(number_format((float)$price, 2));
}

function format_admin_stock_display($conn, $product_id, $stock_quantity) {
    $options = get_admin_product_options($conn, $product_id);
    $stock_lines = [];

    if (count($options) > 0) {
        foreach ($options as $option) {
            $stock_lines[] = htmlspecialchars($option['option_name']) . ": " . htmlspecialchars($option['option_stock']);
        }

        return implode("<br>", $stock_lines);
    }

    if ($stock_quantity === null || $stock_quantity === "") {
        return "-";
    }

    return htmlspecialchars($stock_quantity);
}

/* Format only problematic option stock for analysis table */
function format_admin_problem_stock_display($conn, $product_id, $stock_quantity) {
    $options = get_admin_product_options($conn, $product_id);
    $stock_lines = [];

    if (count($options) > 0) {
        foreach ($options as $option) {
            $option_stock = (int)$option['option_stock'];

            if ($option_stock == 0 || ($option_stock > 0 && $option_stock <= 5)) {
                $stock_lines[] = htmlspecialchars($option['option_name']) . ": " . htmlspecialchars($option_stock);
            }
        }

        if (count($stock_lines) > 0) {
            return implode("<br>", $stock_lines);
        }

        return "-";
    }

    if ($stock_quantity === null || $stock_quantity === "") {
        return "-";
    }

    return htmlspecialchars($stock_quantity);
}

function get_admin_stock_status_data($conn, $product_id, $stock_quantity) {
    $options = get_admin_product_options($conn, $product_id);

    $status_data = [
        "has_options" => false,
        "has_low_stock" => false,
        "has_sold_out" => false,
        "all_sold_out" => false,
        "full_status_html" => "",
        "problem_status_html" => ""
    ];

    if (count($options) > 0) {
        $status_data["has_options"] = true;
        $all_sold_out = true;
        $full_status_lines = [];
        $problem_status_lines = [];

        foreach ($options as $option) {
            $option_name = $option['option_name'];
            $option_stock = (int)$option['option_stock'];

            if ($option_stock > 0) {
                $all_sold_out = false;
            }

            if ($option_stock == 0) {
                $status_data["has_sold_out"] = true;
                $status_text = "Sold Out";
                $status_class = "admin-stock-soldout";
                $is_problem = true;
            } else if ($option_stock <= 5) {
                $status_data["has_low_stock"] = true;
                $status_text = "Low Stock";
                $status_class = "admin-stock-warning";
                $is_problem = true;
            } else {
                $status_text = "In Stock";
                $status_class = "admin-stock-normal";
                $is_problem = false;
            }

            $status_line =
                "<div class='product-option-status-line'>" .
                "<span class='product-option-status-name'>" . htmlspecialchars($option_name) . "</span>" .
                "<span class='" . htmlspecialchars($status_class) . "'>" . htmlspecialchars($status_text) . "</span>" .
                "</div>";

            $full_status_lines[] = $status_line;

            if ($is_problem) {
                $problem_status_lines[] = $status_line;
            }
        }

        $status_data["all_sold_out"] = $all_sold_out;
        $status_data["full_status_html"] = "<div class='product-option-status-list'>" . implode("", $full_status_lines) . "</div>";

        if (count($problem_status_lines) > 0) {
            $status_data["problem_status_html"] = "<div class='product-option-status-list'>" . implode("", $problem_status_lines) . "</div>";
        }

        return $status_data;
    }

    $stock_quantity = (int)$stock_quantity;

    if ($stock_quantity == 0) {
        $status_data["has_sold_out"] = true;
        $status_data["all_sold_out"] = true;
        $status_data["full_status_html"] = "<span class='admin-stock-soldout'>Sold Out</span>";
        $status_data["problem_status_html"] = "<span class='admin-stock-soldout'>Sold Out</span>";
    } else if ($stock_quantity <= 5) {
        $status_data["has_low_stock"] = true;
        $status_data["full_status_html"] = "<span class='admin-stock-warning'>Low Stock</span>";
        $status_data["problem_status_html"] = "<span class='admin-stock-warning'>Low Stock</span>";
    } else {
        $status_data["full_status_html"] = "<span class='admin-stock-normal'>In Stock</span>";
    }

    return $status_data;
}

$total_items = 0;
$total_cacti = 0;
$total_succulents = 0;
$total_accessories = 0;
$total_services = 0;
$low_stock_count = 0;
$sold_out_count = 0;

$total_sql = "SELECT COUNT(*) AS total_items FROM product";
$total_result = mysqli_query($conn, $total_sql);

if ($total_result) {
    $total_row = mysqli_fetch_assoc($total_result);
    $total_items = $total_row['total_items'];
}

$category_sql = "SELECT category, COUNT(*) AS total_category
                 FROM product
                 GROUP BY category";
$category_result = mysqli_query($conn, $category_sql);

if ($category_result) {
    while ($category_row = mysqli_fetch_assoc($category_result)) {
        if ($category_row['category'] == "Cacti") {
            $total_cacti = $category_row['total_category'];
        } else if ($category_row['category'] == "Succulents") {
            $total_succulents = $category_row['total_category'];
        } else if ($category_row['category'] == "Planting Accessories") {
            $total_accessories = $category_row['total_category'];
        } else if ($category_row['category'] == "Services") {
            $total_services = $category_row['total_category'];
        }
    }
}

$stock_status_sql = "SELECT id, stock_quantity FROM product";
$stock_status_result = mysqli_query($conn, $stock_status_sql);

if ($stock_status_result) {
    while ($stock_status_row = mysqli_fetch_assoc($stock_status_result)) {
        $status_data = get_admin_stock_status_data($conn, $stock_status_row['id'], $stock_status_row['stock_quantity']);

        if ($status_data["has_sold_out"]) {
            $sold_out_count++;
        }

        if ($status_data["has_low_stock"]) {
            $low_stock_count++;
        }
    }
}

$low_stock_items = [];

$low_stock_items_sql = "SELECT id, product_name, category, stock_quantity
                        FROM product
                        ORDER BY $category_order_sql, id ASC";
$low_stock_items_result = mysqli_query($conn, $low_stock_items_sql);

if ($low_stock_items_result) {
    while ($low_item = mysqli_fetch_assoc($low_stock_items_result)) {
        $status_data = get_admin_stock_status_data($conn, $low_item['id'], $low_item['stock_quantity']);

        if ($status_data["problem_status_html"] != "") {
            $low_item['problem_status_html'] = $status_data["problem_status_html"];
            $low_item['stock_display'] = format_admin_problem_stock_display($conn, $low_item['id'], $low_item['stock_quantity']);
            $low_stock_items[] = $low_item;
        }
    }
}

if ($filter_category == "All" && $search_keyword == "") {
    $sql = "SELECT id, product_name, category, product_options, price, stock_quantity
            FROM product
            ORDER BY $category_order_sql, id ASC";

    $result = mysqli_query($conn, $sql);

} else if ($filter_category != "All" && $search_keyword == "") {
    $sql = "SELECT id, product_name, category, product_options, price, stock_quantity
            FROM product
            WHERE category = ?
            ORDER BY id ASC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $filter_category);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

} else if ($filter_category == "All" && $search_keyword != "") {
    $sql = "SELECT id, product_name, category, product_options, price, stock_quantity
            FROM product
            WHERE product_name LIKE ?
               OR category LIKE ?
               OR product_options LIKE ?
               OR description LIKE ?
               OR image_path LIKE ?
            ORDER BY $category_order_sql, id ASC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $search_like, $search_like, $search_like, $search_like, $search_like);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

} else {
    $sql = "SELECT id, product_name, category, product_options, price, stock_quantity
            FROM product
            WHERE category = ?
            AND (
                product_name LIKE ?
                OR category LIKE ?
                OR product_options LIKE ?
                OR description LIKE ?
                OR image_path LIKE ?
            )
            ORDER BY id ASC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss", $filter_category, $search_like, $search_like, $search_like, $search_like, $search_like);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}

$total_records = ($result) ? mysqli_num_rows($result) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Admin product and service management page for Cacti-Succulent Kuching">
    <meta name="keywords" content="admin, products, services, management, cacti, succulent">
    <meta name="author" content="Rachael, Eleona, Amber">

    <title>Products and Services | Cacti-Succulent Kuching</title>

    <link rel="stylesheet" href="styles/style.css?v=productmanage11">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Montserrat:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
</head>

<body class="admin-page view-product-page">

<?php include 'header.inc'; ?>

    <main class="form-main">
        <div class="form-header">
            <h1>Products and Services</h1>
            <p>Manage website products, services, prices, stock and categories</p>
        </div>

        <div class="form-card">

            <fieldset class="admin-record-fieldset">
                <legend>Product and Service Analysis</legend>

                <div class="admin-table-wrapper">
                    <table class="admin-summary-table">
                        <tr>
                            <th>Total Items</th>
                            <th>Cacti</th>
                            <th>Succulents</th>
                            <th>Accessories</th>
                            <th>Services</th>
                            <th>Low Stock</th>
                            <th>Sold Out</th>
                        </tr>

                        <tr>
                            <td><?php echo htmlspecialchars($total_items); ?></td>
                            <td><?php echo htmlspecialchars($total_cacti); ?></td>
                            <td><?php echo htmlspecialchars($total_succulents); ?></td>
                            <td><?php echo htmlspecialchars($total_accessories); ?></td>
                            <td><?php echo htmlspecialchars($total_services); ?></td>
                            <td>
                                <?php
                                if ($low_stock_count > 0) {
                                    echo "<span class='admin-stock-warning'>" . htmlspecialchars($low_stock_count) . " Low</span>";
                                } else {
                                    echo "0";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($sold_out_count > 0) {
                                    echo "<span class='admin-stock-soldout'>" . htmlspecialchars($sold_out_count) . " Sold Out</span>";
                                } else {
                                    echo "0";
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="admin-table-wrapper">
                    <table class="admin-summary-table">
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Problem Status</th>
                        </tr>

                        <?php
                        if (count($low_stock_items) > 0) {
                            $low_no = 1;

                            foreach ($low_stock_items as $low_item) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($low_no) . "</td>";
                                echo "<td>" . htmlspecialchars($low_item['product_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($low_item['category']) . "</td>";
                                echo "<td class='admin-option-price-cell'>" . $low_item['stock_display'] . "</td>";
                                echo "<td>" . $low_item['problem_status_html'] . "</td>";
                                echo "</tr>";

                                $low_no++;
                            }
                        } else {
                            echo "<tr><td colspan='5'>No low stock or sold out items.</td></tr>";
                        }
                        ?>
                    </table>
                </div>
            </fieldset>

            <fieldset class="admin-record-fieldset">
                <legend>Product and Service Records</legend>

                <form action="view_product.php" method="get">
                    <div class="form-row">
                        <div class="input-group">
                            <label for="keyword">Search</label>
                            <input type="text" id="keyword" name="keyword" value="<?php echo htmlspecialchars($search_keyword); ?>" placeholder="Name, category or description">
                        </div>

                        <div class="input-group">
                            <label for="category">Category</label>
                            <select id="category" name="category">
                                <?php
                                foreach ($allowed_categories as $category) {
                                    $selected = ($filter_category == $category) ? "selected" : "";
                                    echo "<option value='" . htmlspecialchars($category) . "' " . $selected . ">" . htmlspecialchars($category) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="admin-filter-actions">
                        <input type="submit" value="Apply">
                        <a href="view_product.php" class="admin-action-link">Clear</a>
                    </div>
                </form>

                <div class="admin-list-toolbar">
                    <p>Total Products and Services: <?php echo htmlspecialchars($total_records); ?></p>

                    <div class="admin-action-buttons">
                        <a href="add_product.php" class="admin-action-link">Add New Item</a>
                    </div>
                </div>

                <div class="admin-table-wrapper">
                    <table class="admin-summary-table">
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price / Option Prices</th>
                            <th>Stock / Option Stock</th>
                            <th>Stock Status</th>
                            <th>Action</th>
                        </tr>

                        <?php
                        $display_no = 1;

                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $status_data = get_admin_stock_status_data($conn, $row['id'], $row['stock_quantity']);

                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($display_no) . "</td>";
                                echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                                echo "<td class='admin-option-price-cell'>" . format_admin_price_display($conn, $row['id'], $row['price']) . "</td>";
                                echo "<td class='admin-option-price-cell'>" . format_admin_stock_display($conn, $row['id'], $row['stock_quantity']) . "</td>";
                                echo "<td>" . $status_data['full_status_html'] . "</td>";

                                echo "<td>";
                                echo "<div class='product-table-action-group'>";
                                echo "<a href='view_product_detail.php?id=" . urlencode($row['id']) . "' class='admin-view-btn'>View</a>";
                                echo "<a href='edit_product.php?id=" . urlencode($row['id']) . "' class='admin-view-btn'>Edit</a>";
                                echo "<a href='delete_product.php?id=" . urlencode($row['id']) . "' class='admin-view-btn'>Delete</a>";
                                echo "</div>";
                                echo "</td>";
                                echo "</tr>";

                                $display_no++;
                            }
                        } else {
                            echo "<tr><td colspan='7'>No product or service records found.</td></tr>";
                        }
                        ?>
                    </table>
                </div>

                <div class="admin-action-buttons">
                    <a href="admin_dashboard.php" class="admin-action-link">Back to Dashboard</a>
                    <a href="export_products.php" class="admin-action-link">Export CSV</a>
                </div>
            </fieldset>
        </div>
    </main>

<?php include 'footer.inc'; ?>

<a href="#" class="back-to-top">▲</a>

</body>
</html>

<?php
mysqli_close($conn);
?>
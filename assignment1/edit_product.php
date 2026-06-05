<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Display a form for editing an existing product or service.
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

/* Allowed product and service categories */
$allowed_categories = ["Cacti", "Succulents", "Planting Accessories", "Services"];

/* Create product option table if it does not exist */
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

/* Get product or service ID */
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

/* Redirect if ID is invalid */
if ($product_id <= 0) {
    header("Location: view_product.php");
    exit();
}

/* Retrieve selected product or service record */
$sql = "SELECT id, product_name, category, product_options, description, price, image_path, image_source, stock_quantity
        FROM product
        WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

/* Redirect if record does not exist */
if (!$result || mysqli_num_rows($result) == 0) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: view_product.php");
    exit();
}

/* Store product or service details */
$product = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

/* Package services do not use product images */
$is_package_service = ($product['category'] == "Services" && stripos($product['product_name'], "package") !== false);

/* Retrieve separate product options */
$product_options_list = [];

$option_sql = "SELECT id, option_name, option_price, option_stock
               FROM product_option
               WHERE product_id = ?
               ORDER BY id ASC";

$option_stmt = mysqli_prepare($conn, $option_sql);
mysqli_stmt_bind_param($option_stmt, "i", $product_id);
mysqli_stmt_execute($option_stmt);
$option_result = mysqli_stmt_get_result($option_stmt);

if ($option_result && mysqli_num_rows($option_result) > 0) {
    while ($option_row = mysqli_fetch_assoc($option_result)) {
        $product_options_list[] = $option_row;
    }
}

mysqli_stmt_close($option_stmt);

/* If old product_options exists but product_option table is empty, prepare old options for display */
if (count($product_options_list) == 0 && !empty($product['product_options'])) {
    $old_options_array = explode(',', $product['product_options']);

    foreach ($old_options_array as $old_option) {
        $old_option = trim($old_option);

        if ($old_option != "") {
            $old_option_name = "";
            $old_option_price = $product['price'];

            if (strpos($old_option, ':') !== false) {
                list($old_option_name, $old_option_price) = explode(':', $old_option, 2);
                $old_option_name = trim($old_option_name);
                $old_option_price = number_format((float)trim($old_option_price), 2, '.', '');
            } else {
                $old_option_name = $old_option;
                $old_option_price = number_format((float)$product['price'], 2, '.', '');
            }

            $product_options_list[] = [
                "id" => 0,
                "option_name" => $old_option_name,
                "option_price" => $old_option_price,
                "option_stock" => $product['stock_quantity']
            ];
        }
    }
}

/* Ensure there are at least 5 option rows for admin editing */
while (count($product_options_list) < 5) {
    $product_options_list[] = [
        "id" => 0,
        "option_name" => "",
        "option_price" => "",
        "option_stock" => ""
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BASIC PAGE METADATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Edit product or service page for administrator">
    <meta name="keywords" content="admin, edit product, edit service">
    <meta name="author" content="Rachael, Eleona, Amber">

    <title>Edit Product or Service | Cacti-Succulent Kuching</title>

    <!-- EXTERNAL STYLESHEETS AND ICONS -->
    <link rel="stylesheet" href="styles/style.css?v=productmanage13">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Montserrat:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
</head>

<body class="admin-page view-product-page">

<!-- HEADER & NAVIGATION -->
<?php include 'header.inc'; ?>

    <!-- EDIT PRODUCT AND SERVICE SECTION -->
    <main class="form-main">
        <div class="form-header">
            <h1>Edit Product or Service</h1>
            <p>Update product, service, option prices, image and stock information</p>
        </div>

        <div class="form-card">
            <form action="edit_product_process.php" method="post" enctype="multipart/form-data">
                <fieldset class="admin-record-fieldset">
                    <legend>Edit Product or Service</legend>

                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
                    <input type="hidden" name="current_image_path" value="<?php echo htmlspecialchars($product['image_path']); ?>">
                    <input type="hidden" id="product_options" name="product_options" value="<?php echo htmlspecialchars($product['product_options']); ?>">

                    <div class="form-row">

                        <!-- PRODUCT OR SERVICE NAME -->
                        <div class="input-group">
                            <label for="product_name">Name</label>
                            <input type="text" id="product_name" name="product_name" maxlength="100" required value="<?php echo htmlspecialchars($product['product_name']); ?>">
                        </div>

                        <!-- CATEGORY -->
                        <div class="input-group">
                            <label for="category">Category</label>
                            <select id="category" name="category" required>
                                <?php
                                foreach ($allowed_categories as $category) {
                                    $selected = ($product['category'] == $category) ? "selected" : "";
                                    echo "<option value='" . htmlspecialchars($category) . "' " . $selected . ">" . htmlspecialchars($category) . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- DEFAULT PRICE -->
                        <div class="input-group">
                            <label for="price">Default Price (RM)</label>
                            <input type="number" id="price" name="price" min="0" step="0.01" required value="<?php echo htmlspecialchars($product['price']); ?>">
                            <small class="admin-input-note">For items without options, use this as the selling price.</small>
                        </div>

                        <!-- DEFAULT STOCK QUANTITY -->
                        <div class="input-group">
                            <label for="stock_quantity">Default Stock Quantity</label>
                            <input type="number" id="stock_quantity" name="stock_quantity" min="0" step="1" value="<?php echo htmlspecialchars($product['stock_quantity']); ?>">
                            <small class="admin-input-note">For items without options, use this as the stock quantity.</small>
                        </div>

                        <!-- PRODUCT OPTIONS -->
                        <div class="input-group full-width">
                            <label>Product Options</label>

                            <div class="admin-option-editor">
                                <?php
                                foreach ($product_options_list as $option) {
                                ?>
                                    <div class="admin-option-row">
                                        <input type="hidden" name="option_id[]" value="<?php echo htmlspecialchars($option['id']); ?>">

                                        <input type="text"
                                               name="option_name[]"
                                               class="option-name-input"
                                               maxlength="100"
                                               placeholder="Option name"
                                               value="<?php echo htmlspecialchars($option['option_name']); ?>">

                                        <input type="number"
                                               name="option_price[]"
                                               class="option-price-input"
                                               min="0"
                                               step="0.01"
                                               placeholder="Price"
                                               value="<?php echo htmlspecialchars($option['option_price']); ?>">

                                        <input type="number"
                                               name="option_stock[]"
                                               class="option-stock-input"
                                               min="0"
                                               step="1"
                                               placeholder="Stock"
                                               value="<?php echo htmlspecialchars($option['option_stock']); ?>">
                                    </div>
                                <?php
                                }
                                ?>
                            </div>

                            <small class="admin-input-note">Leave option fields blank if the item has no options.</small>
                        </div>

                        <?php if (!$is_package_service) { ?>

                            <!-- CURRENT IMAGE -->
                            <div class="input-group full-width">
                                <label>Current Image</label>
                                <div class="admin-current-image-box">
                                    <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                                </div>
                                <small class="admin-input-note">Upload a new image below to replace it.</small>
                            </div>

                            <!-- IMAGE UPLOAD -->
                            <div class="input-group full-width">
                                <label for="product_image">Image</label>
                                <input type="file" id="product_image" name="product_image" accept="image/jpeg,image/png,image/webp,image/gif">
                                <small class="admin-input-note">Optional. JPG, PNG, WEBP or GIF.</small>
                            </div>

                            <!-- IMAGE SOURCE -->
                            <div class="input-group full-width">
                                <label for="image_source">Image Source URL</label>
                                <input type="url" id="image_source" name="image_source" maxlength="500" value="<?php echo htmlspecialchars($product['image_source']); ?>" placeholder="https://www.pexels.com/photo/...">
                                <small class="admin-input-note">Optional source link.</small>
                            </div>

                        <?php } ?>

                        <!-- DESCRIPTION -->
                        <div class="input-group full-width">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="6" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                            <small class="admin-input-note">Description only. Source link goes above.</small>
                        </div>
                    </div>

                    <!-- FORM BUTTONS -->
                    <div class="button-group">
                        <input type="submit" value="Save">
                        <input type="reset" value="Reset">
                    </div>

                    <!-- ADMIN ACTION BUTTONS -->
                    <div class="admin-action-buttons">
                        <a href="view_product.php" class="admin-action-link">Back to Products</a>
                    </div>
                </fieldset>
            </form>
        </div>
    </main>

<!-- WEBSITE FOOTER & COPYRIGHT -->
<?php include 'footer.inc'; ?>

<!-- BACK TO TOP BUTTON -->
<a href="#" class="back-to-top">▲</a>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const priceInput = document.getElementById("price");
    const stockInput = document.getElementById("stock_quantity");
    const hiddenOptionsInput = document.getElementById("product_options");
    const optionNameInputs = document.querySelectorAll(".option-name-input");
    const optionPriceInputs = document.querySelectorAll(".option-price-input");
    const optionStockInputs = document.querySelectorAll(".option-stock-input");

    function buildHiddenOptionsText() {
        let optionTextArray = [];

        for (let i = 0; i < optionNameInputs.length; i++) {
            const optionName = optionNameInputs[i].value.trim();
            const optionPrice = parseFloat(optionPriceInputs[i].value);

            if (optionName !== "" && !isNaN(optionPrice) && optionPrice >= 0) {
                optionTextArray.push(optionName + ":" + optionPrice.toFixed(2));
            }
        }

        hiddenOptionsInput.value = optionTextArray.join(", ");
    }

    function updateDefaultValuesFromFirstOption() {
        const firstOptionName = optionNameInputs[0].value.trim();
        const firstOptionPrice = parseFloat(optionPriceInputs[0].value);

        if (firstOptionName !== "" && !isNaN(firstOptionPrice) && firstOptionPrice >= 0) {
            priceInput.value = firstOptionPrice.toFixed(2);
        }

        if (firstOptionName !== "") {
            stockInput.value = "";
        }
    }

    function updateOptionData() {
        buildHiddenOptionsText();
        updateDefaultValuesFromFirstOption();
    }

    optionNameInputs.forEach(function (input) {
        input.addEventListener("input", updateOptionData);
        input.addEventListener("blur", updateOptionData);
    });

    optionPriceInputs.forEach(function (input) {
        input.addEventListener("input", updateOptionData);
        input.addEventListener("blur", updateOptionData);
    });

    optionStockInputs.forEach(function (input) {
        input.addEventListener("input", updateOptionData);
        input.addEventListener("blur", updateOptionData);
    });
});
</script>

</body>
</html>

<?php
/* Close database connection */
mysqli_close($conn);
?>
<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Display a form for adding a new product or service.
*/

/* Start session to check admin login status */
session_start();

/* Redirect to login page if admin is not logged in */
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

/* Allowed product and service categories */
$allowed_categories = ["Cacti", "Succulents", "Planting Accessories", "Services"];

/* Success and error messages */
$success_messages = [
    "success" => "Product or service added successfully."
];

$error_messages = [
    "option_table" => "Product option table could not be prepared.",
    "empty" => "Please fill in all required fields.",
    "category" => "Invalid category selected.",
    "price" => "Please enter a valid price.",
    "stock" => "Please enter a valid stock quantity.",
    "option_incomplete" => "Please complete option name, price and stock together.",
    "option_price" => "Please enter a valid option price.",
    "option_stock" => "Please enter a valid option stock.",
    "image_required" => "Please upload an image for this product or normal service.",
    "image_type" => "Only JPG, JPEG, PNG, WEBP or GIF images are allowed.",
    "image_size" => "Image size must not be more than 5MB.",
    "image_upload" => "Image could not be uploaded. Please check the images/uploads folder.",
    "database" => "Database error. The item was not added.",
    "option_database" => "Option database error. The item was not added."
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BASIC PAGE METADATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Add product or service page for administrator">
    <meta name="keywords" content="admin, add product, add service">
    <meta name="author" content="Rachael, Eleona, Amber">

    <title>Add Product or Service | Cacti-Succulent Kuching</title>

    <!-- EXTERNAL STYLESHEETS AND ICONS -->
    <link rel="stylesheet" href="styles/style.css?v=productmanage16">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Montserrat:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
</head>

<body class="admin-page view-product-page">

<!-- HEADER & NAVIGATION -->
<?php include 'header.inc'; ?>

    <!-- ADD PRODUCT AND SERVICE SECTION -->
    <main class="form-main">
        <div class="form-header">
            <h1>Add Product or Service</h1>
            <p>Add new product, service, option prices, stock and image information</p>
        </div>

        <div class="form-card">

            <!-- SUCCESS AND ERROR MESSAGE -->
            <?php
            if (isset($_GET['added']) && isset($success_messages[$_GET['added']])) {
                echo "<p class='admin-success-message'>" . htmlspecialchars($success_messages[$_GET['added']]) . "</p>";
            }

            if (isset($_GET['error']) && isset($error_messages[$_GET['error']])) {
                echo "<p class='admin-error-message'>" . htmlspecialchars($error_messages[$_GET['error']]) . "</p>";
            }
            ?>

            <form action="add_product_process.php" method="post" enctype="multipart/form-data">
                <fieldset class="admin-record-fieldset">
                    <legend>Add Product or Service</legend>

                    <input type="hidden" id="product_options" name="product_options">

                    <div class="form-row">

                        <!-- PRODUCT OR SERVICE NAME -->
                        <div class="input-group">
                            <label for="product_name">Name</label>
                            <input type="text" id="product_name" name="product_name" maxlength="100" required placeholder="Example: Golden Barrel">
                        </div>

                        <!-- CATEGORY -->
                        <div class="input-group">
                            <label for="category">Category</label>
                            <select id="category" name="category" required>
                                <?php
                                foreach ($allowed_categories as $category) {
                                    echo "<option value='" . htmlspecialchars($category) . "'>" . htmlspecialchars($category) . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- DEFAULT PRICE -->
                        <div class="input-group">
                            <label for="price">Default Price (RM)</label>
                            <input type="number" id="price" name="price" min="0" step="0.01" required placeholder="Example: 25.00">
                            <small class="admin-input-note">For items with options, this follows the first option price.</small>
                        </div>

                        <!-- DEFAULT STOCK QUANTITY -->
                        <div class="input-group">
                            <label for="stock_quantity">Default Stock Quantity</label>
                            <input type="number" id="stock_quantity" name="stock_quantity" min="0" step="1" placeholder="Example: 99">
                            <small class="admin-input-note">For items without options, use this as the stock quantity.</small>
                        </div>

                        <!-- PRODUCT OPTIONS -->
                        <div class="input-group full-width">
                            <label>Product Options</label>

                            <div class="admin-option-editor">

                                <div class="admin-option-row">
                                    <input type="text"
                                           name="option_name[]"
                                           class="option-name-input"
                                           maxlength="100"
                                           placeholder="Option name">

                                    <input type="number"
                                           name="option_price[]"
                                           class="option-price-input"
                                           min="0"
                                           step="0.01"
                                           placeholder="Price">

                                    <input type="number"
                                           name="option_stock[]"
                                           class="option-stock-input"
                                           min="0"
                                           step="1"
                                           placeholder="Stock">
                                </div>

                                <div class="admin-option-row">
                                    <input type="text"
                                           name="option_name[]"
                                           class="option-name-input"
                                           maxlength="100"
                                           placeholder="Option name">

                                    <input type="number"
                                           name="option_price[]"
                                           class="option-price-input"
                                           min="0"
                                           step="0.01"
                                           placeholder="Price">

                                    <input type="number"
                                           name="option_stock[]"
                                           class="option-stock-input"
                                           min="0"
                                           step="1"
                                           placeholder="Stock">
                                </div>

                                <div class="admin-option-row">
                                    <input type="text"
                                           name="option_name[]"
                                           class="option-name-input"
                                           maxlength="100"
                                           placeholder="Option name">

                                    <input type="number"
                                           name="option_price[]"
                                           class="option-price-input"
                                           min="0"
                                           step="0.01"
                                           placeholder="Price">

                                    <input type="number"
                                           name="option_stock[]"
                                           class="option-stock-input"
                                           min="0"
                                           step="1"
                                           placeholder="Stock">
                                </div>

                                <div class="admin-option-row">
                                    <input type="text"
                                           name="option_name[]"
                                           class="option-name-input"
                                           maxlength="100"
                                           placeholder="Optional extra option">

                                    <input type="number"
                                           name="option_price[]"
                                           class="option-price-input"
                                           min="0"
                                           step="0.01"
                                           placeholder="Price">

                                    <input type="number"
                                           name="option_stock[]"
                                           class="option-stock-input"
                                           min="0"
                                           step="1"
                                           placeholder="Stock">
                                </div>

                                <div class="admin-option-row">
                                    <input type="text"
                                           name="option_name[]"
                                           class="option-name-input"
                                           maxlength="100"
                                           placeholder="Optional extra option">

                                    <input type="number"
                                           name="option_price[]"
                                           class="option-price-input"
                                           min="0"
                                           step="0.01"
                                           placeholder="Price">

                                    <input type="number"
                                           name="option_stock[]"
                                           class="option-stock-input"
                                           min="0"
                                           step="1"
                                           placeholder="Stock">
                                </div>

                            </div>

                            <small class="admin-input-note">Leave option fields blank if the item has no options.</small>
                        </div>

                        <!-- IMAGE UPLOAD -->
                        <div class="input-group full-width" id="image_upload_section">
                            <label for="product_image">Image</label>
                            <input type="file" id="product_image" name="product_image" accept="image/jpeg,image/png,image/webp,image/gif" required>
                            <small class="admin-input-note">Required for products and normal services. JPG, PNG, WEBP or GIF.</small>
                        </div>

                        <!-- IMAGE SOURCE -->
                        <div class="input-group full-width" id="image_source_section">
                            <label for="image_source">Image Source URL</label>
                            <input type="url" id="image_source" name="image_source" maxlength="500" placeholder="https://www.pexels.com/photo/...">
                            <small class="admin-input-note">Optional source link.</small>
                        </div>

                        <!-- DESCRIPTION -->
                        <div class="input-group full-width">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="6" required placeholder="Enter product or service description"></textarea>
                            <small class="admin-input-note">Description only. Source link goes above.</small>
                        </div>
                    </div>

                    <!-- FORM BUTTONS -->
                    <div class="button-group">
                        <input type="submit" value="Add Item">
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
    const categoryInput = document.getElementById("category");
    const productNameInput = document.getElementById("product_name");
    const priceInput = document.getElementById("price");
    const stockInput = document.getElementById("stock_quantity");
    const hiddenOptionsInput = document.getElementById("product_options");
    const imageInput = document.getElementById("product_image");
    const imageSection = document.getElementById("image_upload_section");
    const imageSourceSection = document.getElementById("image_source_section");

    const optionNameInputs = document.querySelectorAll(".option-name-input");
    const optionPriceInputs = document.querySelectorAll(".option-price-input");
    const optionStockInputs = document.querySelectorAll(".option-stock-input");

    function isPackageService() {
        return categoryInput.value === "Services" &&
               productNameInput.value.toLowerCase().includes("package");
    }

    function updateImageRequirement() {
        if (isPackageService()) {
            imageSection.hidden = true;
            imageSourceSection.hidden = true;
            imageInput.removeAttribute("required");
            imageInput.value = "";
        } else {
            imageSection.hidden = false;
            imageSourceSection.hidden = false;
            imageInput.setAttribute("required", "required");
        }
    }

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
            stockInput.value = "";
        }
    }

    function updateOptionData() {
        buildHiddenOptionsText();
        updateDefaultValuesFromFirstOption();
    }

    categoryInput.addEventListener("change", updateImageRequirement);
    productNameInput.addEventListener("input", updateImageRequirement);

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

    updateImageRequirement();
});
</script>

</body>
</html>
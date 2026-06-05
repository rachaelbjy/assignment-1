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
    <link rel="stylesheet" href="styles/style.css?v=productmanage7">
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
            <p>Create a new product, service, image, option price or stock record</p>
        </div>

        <div class="form-card">
            <form action="add_product_process.php" method="post" enctype="multipart/form-data">
                <fieldset class="admin-record-fieldset">
                    <legend>New Product or Service</legend>

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
                            <small class="admin-input-note">For products without options, use this as the selling price.</small>
                        </div>

                        <!-- DEFAULT STOCK QUANTITY -->
                        <div class="input-group">
                            <label for="stock_quantity">Default Stock Quantity</label>
                            <input type="number" id="stock_quantity" name="stock_quantity" min="0" step="1" required placeholder="Example: 50">
                            <small class="admin-input-note">For products without options, use this as the stock quantity.</small>
                        </div>

                        <!-- PRODUCT OPTIONS -->
                        <div class="input-group full-width">
                            <label>Product Options</label>

                            <input type="hidden" id="product_options" name="product_options">

                            <div class="admin-option-editor">

                                <div class="admin-option-row">
                                    <input type="text" name="option_name[]" class="option-name-input" maxlength="100" placeholder="Option name, e.g. Small 4&quot;">
                                    <input type="number" name="option_price[]" class="option-price-input" min="0" step="0.01" placeholder="Price">
                                    <input type="number" name="option_stock[]" class="option-stock-input" min="0" step="1" placeholder="Stock">
                                </div>

                                <div class="admin-option-row">
                                    <input type="text" name="option_name[]" class="option-name-input" maxlength="100" placeholder="Option name, e.g. Medium 6&quot;">
                                    <input type="number" name="option_price[]" class="option-price-input" min="0" step="0.01" placeholder="Price">
                                    <input type="number" name="option_stock[]" class="option-stock-input" min="0" step="1" placeholder="Stock">
                                </div>

                                <div class="admin-option-row">
                                    <input type="text" name="option_name[]" class="option-name-input" maxlength="100" placeholder="Option name, e.g. Large 8&quot;">
                                    <input type="number" name="option_price[]" class="option-price-input" min="0" step="0.01" placeholder="Price">
                                    <input type="number" name="option_stock[]" class="option-stock-input" min="0" step="1" placeholder="Stock">
                                </div>

                                <div class="admin-option-row">
                                    <input type="text" name="option_name[]" class="option-name-input" maxlength="100" placeholder="Optional extra option">
                                    <input type="number" name="option_price[]" class="option-price-input" min="0" step="0.01" placeholder="Price">
                                    <input type="number" name="option_stock[]" class="option-stock-input" min="0" step="1" placeholder="Stock">
                                </div>

                                <div class="admin-option-row">
                                    <input type="text" name="option_name[]" class="option-name-input" maxlength="100" placeholder="Optional extra option">
                                    <input type="number" name="option_price[]" class="option-price-input" min="0" step="0.01" placeholder="Price">
                                    <input type="number" name="option_stock[]" class="option-stock-input" min="0" step="1" placeholder="Stock">
                                </div>

                            </div>

                            <small class="admin-input-note">Leave option fields blank if the item has no options.</small>
                        </div>

                        <!-- IMAGE UPLOAD -->
                        <div class="input-group full-width">
                            <label for="product_image">Image</label>
                            <input type="file" id="product_image" name="product_image" accept="image/jpeg,image/png,image/webp,image/gif" required>
                            <small class="admin-input-note">JPG, PNG, WEBP or GIF.</small>
                        </div>

                        <!-- IMAGE SOURCE -->
                        <div class="input-group full-width">
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
        const firstOptionStock = parseInt(optionStockInputs[0].value, 10);

        if (firstOptionName !== "" && !isNaN(firstOptionPrice) && firstOptionPrice >= 0) {
            priceInput.value = firstOptionPrice.toFixed(2);
        }

        if (firstOptionName !== "" && !isNaN(firstOptionStock) && firstOptionStock >= 0) {
            stockInput.value = firstOptionStock;
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
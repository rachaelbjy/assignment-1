<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Display the planting accessories product catalogue using database records.
*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BASIC PAGE METADATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Planting Accessories for desert plants">
    <meta name="keywords" content="soil, pumice, pots, gardening tools">
    <meta name="author" content="Rachael, Eleona, Amber">
    
    <title>Planting Accessories | Cacti-Succulent Kuching</title>
    
    <!-- EXTERNAL STYLESHEETS AND ICONS -->
    <link rel="stylesheet" href="styles/style.css?v=cartproductoptionstock1">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Montserrat:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
</head>

<body class="product-page">

<!-- HEADER & NAVIGATION -->
<?php include 'header.inc'; ?>

    <!-- ENHANCEMENT -->
    <div class="cactus-party">
        <span class="c1">🌵</span><span class="c2">✨</span><span class="c3">🪴</span>
        <span class="c4">🌵</span><span class="c5">🪴</span><span class="c6">✨</span>
        <span class="c7">✨</span><span class="c8">🌵</span><span class="c9">🪴</span>
        <span class="c10">🪴</span><span class="c11">✨</span><span class="c12">🌵</span>
        <span class="c13">🌵</span><span class="c14">🪴</span><span class="c15">✨</span>
    </div>

    <!-- HERO SECTION -->
    <section class="hero-section hero-accessories">
        <div class="hero-content">
            <h1>Planting Accessories</h1>
            <h2>The Right Tools for the Job</h2>
            <p>Premium soils, breathable terracotta, and precision tools. Everything you need to repot, prune, and maintain your desert gems with professional care.</p>
            <div class="hero-divider"></div>
        </div>
    </section>

    <main class="content-wrapper">

        <!-- PRODUCT AND SERVICE SEARCH BAR -->
        <?php include 'product_service_search.inc'; ?>
        
        <!-- PRODUCTS SECTION -->
        <div class="product-grid">
            <?php
            /* Include database connection */
            require_once('settings.php');

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

            /* Query to select only Planting Accessories */
            $sql = "SELECT id, product_name, product_options, description, price, image_path, image_source, stock_quantity
                    FROM product
                    WHERE category = 'Planting Accessories'
                    ORDER BY id ASC";

            $result = mysqli_query($conn, $sql);

            /* Check if there are products in the database */
            if ($result && mysqli_num_rows($result) > 0) {

                /* Loop through each product record */
                while ($row = mysqli_fetch_assoc($result)) {

                    /* Create a URL-safe ID for search jump link */
                    $product_id = strtolower(str_replace(' ', '-', $row['product_name']));

                    /* Retrieve option records for this product */
                    $option_records = [];

                    $option_sql = "SELECT id, option_name, option_price, option_stock
                                   FROM product_option
                                   WHERE product_id = ?
                                   ORDER BY id ASC";

                    $option_stmt = mysqli_prepare($conn, $option_sql);
                    mysqli_stmt_bind_param($option_stmt, "i", $row['id']);
                    mysqli_stmt_execute($option_stmt);
                    $option_result = mysqli_stmt_get_result($option_stmt);

                    if ($option_result && mysqli_num_rows($option_result) > 0) {
                        while ($option_row = mysqli_fetch_assoc($option_result)) {
                            $option_records[] = $option_row;
                        }
                    }

                    mysqli_stmt_close($option_stmt);

                    /* If new option table is empty, fall back to old product_options text */
                    if (count($option_records) == 0 && !empty($row['product_options'])) {
                        $old_options_array = explode(',', $row['product_options']);

                        foreach ($old_options_array as $old_option) {
                            $old_option = trim($old_option);

                            if ($old_option != "") {
                                $old_option_name = "";
                                $old_option_price = $row['price'];

                                if (strpos($old_option, ':') !== false) {
                                    list($old_option_name, $old_option_price) = explode(':', $old_option, 2);
                                    $old_option_name = trim($old_option_name);
                                    $old_option_price = number_format((float)trim($old_option_price), 2, '.', '');
                                } else {
                                    $old_option_name = $old_option;
                                    $old_option_price = number_format((float)$row['price'], 2, '.', '');
                                }

                                $option_records[] = [
                                    "id" => 0,
                                    "option_name" => $old_option_name,
                                    "option_price" => $old_option_price,
                                    "option_stock" => $row['stock_quantity']
                                ];
                            }
                        }
                    }

                    /* Decide displayed price and stock condition */
                    $display_price = number_format((float)$row['price'], 2);
                    $is_sold_out = false;
                    $has_available_option = false;

                    if (count($option_records) > 0) {
                        $first_available_price = "";

                        foreach ($option_records as $option_record) {
                            if ((int)$option_record['option_stock'] > 0) {
                                $has_available_option = true;

                                if ($first_available_price == "") {
                                    $first_available_price = number_format((float)$option_record['option_price'], 2);
                                }
                            }
                        }

                        if ($first_available_price != "") {
                            $display_price = $first_available_price;
                        } else {
                            $display_price = number_format((float)$option_records[0]['option_price'], 2);
                        }

                        $is_sold_out = !$has_available_option;
                    } else {
                        $stock_quantity = (int)$row['stock_quantity'];
                        $is_sold_out = ($stock_quantity <= 0);
                    }
            ?>

                    <article class="cactus-card<?php echo $is_sold_out ? ' sold-out-card' : ''; ?>" id="<?php echo htmlspecialchars($product_id); ?>">
                        <figure>
                            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                            <figcaption>
                                <?php echo htmlspecialchars($row['description']); ?>

                                <?php if (!empty($row['image_source'])) { ?>
                                    <a href="<?php echo htmlspecialchars($row['image_source']); ?>" target="_blank">Source</a>
                                <?php } ?>
                            </figcaption>
                        </figure>

                        <div class="card-content">
                            <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>

                            <!-- SMART PRICE TAG -->
                            <p class="price option-price">RM <?php echo htmlspecialchars($display_price); ?></p>

                            <form action="add_to_cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <input type="hidden" name="quantity" value="1">

                                <!-- PRODUCT OPTION DROPDOWN -->
                                <?php
                                if (count($option_records) > 0) {
                                    echo '<select class="size-selector option-selector" name="item_option">';

                                    foreach ($option_records as $option_record) {
                                        $option_name = trim($option_record['option_name']);
                                        $option_price = number_format((float)$option_record['option_price'], 2, '.', '');
                                        $option_stock = (int)$option_record['option_stock'];
                                        $option_value = strtolower(str_replace(' ', '-', $option_name)) . "|" . $option_price;
                                        $disabled = ($option_stock <= 0) ? "disabled" : "";
                                        $option_label = $option_name;

                                        if ($option_stock <= 0) {
                                            $option_label .= " - Sold Out";
                                        }

                                        echo '<option value="' . htmlspecialchars($option_value) . '" data-price="' . htmlspecialchars($option_price) . '" ' . $disabled . '>' . htmlspecialchars($option_label) . '</option>';
                                    }

                                    echo '</select>';
                                } else if (!empty($row['product_options'])) {
                                    $options_array = explode(',', $row['product_options']);

                                    echo '<select class="size-selector option-selector" name="item_option">';

                                    foreach ($options_array as $option) {
                                        $option = trim($option);

                                        if ($option != "") {
                                            if (strpos($option, ':') !== false) {
                                                list($opt_name, $opt_price) = explode(':', $option, 2);
                                                $opt_name = trim($opt_name);
                                                $opt_price = number_format((float)trim($opt_price), 2);
                                                $hidden_value = htmlspecialchars(strtolower(str_replace(' ', '-', $opt_name)) . '|' . $opt_price);

                                                echo '<option value="' . $hidden_value . '" data-price="' . htmlspecialchars($opt_price) . '">' . htmlspecialchars($opt_name) . '</option>';
                                            } else {
                                                $fallback_price = number_format((float)$row['price'], 2);
                                                echo '<option value="' . htmlspecialchars(strtolower(str_replace(' ', '-', $option))) . '" data-price="' . htmlspecialchars($fallback_price) . '">' . htmlspecialchars($option) . '</option>';
                                            }
                                        }
                                    }

                                    echo '</select>';
                                }
                                ?>

                                <?php if ($is_sold_out) { ?>
                                    <button class="add-to-cart sold-out-button" disabled>Sold Out</button>
                                <?php } else { ?>
                                    <button type="submit" class="add-to-cart">Add to Cart</button>
                                <?php } ?>
                            </form>
                        </div>
                    </article>

            <?php
                }

                mysqli_free_result($result);
            } else {
                echo "<p class='product-empty-message'>We are currently restocking our premium Accessories! Please check back later.</p>";
            }
            ?>
        </div>

        <!-- STEP GUIDE SECTION -->
        <section class="step-guide">
            <h3>How to Repot Safely:</h3>
            <ol>
                <li>Fill the bottom third of your terracotta pot with the premium cactus mix.</li>
                <li>Gently remove the plant from its nursery pot, using tweezers to avoid glochids.</li>
                <li>Place the plant in the center and fill the remaining space with soil.</li>
                <li>Add a layer of river stone top dressing to keep the base dry.</li>
                <li>Wait at least a week before watering to allow roots to heal!</li>
            </ol>
        </section>

        <!-- KNOWLEDGE BASE -->
        <section class="product-knowledge-base">
            <div class="product-main-content">
                <h3>Materials Matter</h3>
                <p>Having the correct tools and soil components is half the battle when caring for xerophytic plants. The wrong pot or a heavily peated soil mix can retain moisture for far too long, leading to irreversible root rot. We highly advocate for using unglazed terracotta and gritty inorganic mixes to ensure your plants thrive.</p>
                <p>Professional precision tools are not just for aesthetics; they serve vital functions. Long tweezers protect your hands from fine glochids and allow you to remove dead leaves securely without disturbing the root system. Clean, sharp shears prevent crushing stems when propagating, minimizing the risk of bacterial infections spreading.</p>
                
                <dl class="modern-dl">
                    <dt>Terracotta</dt>
                    <dd>Unlike plastic, unglazed terracotta is porous. It pulls moisture out of the soil, preventing the dreaded root rot.</dd>
                    
                    <dt>Pumice</dt>
                    <dd>A lightweight volcanic rock that traps air and water in its microscopic pores, keeping soil airy and preventing compaction.</dd>
                    
                    <dt>Precision Tools</dt>
                    <dd>Long tweezers and narrow trowels allow you to weed and repot without damaging the plant's delicate farina or getting spiked by glochids.</dd>
                </dl>
            </div>

            <!-- ASIDE SECTION -->
            <aside class="product-aside">
                <h3>Tool Guide & Tips</h3>
                
                <h4>Maintenance Check</h4>
                <ol>
                    <li>Wipe shears with alcohol.</li>
                    <li>Calibrate moisture meter.</li>
                    <li>Check terracotta for cracks.</li>
                    <li>Sift out degraded soil dust.</li>
                </ol>

                <h4>Tool Lifespan</h4>
                <table>
                    <tr>
                        <th>Equipment</th>
                        <th>Replacement</th>
                    </tr>
                    <tr>
                        <td>Shears</td>
                        <td>Every 2 Years</td>
                    </tr>
                    <tr>
                        <td>Moisture Meter</td>
                        <td>Every 1 Year</td>
                    </tr>
                    <tr>
                        <td>Terracotta</td>
                        <td>Indefinite</td>
                    </tr>
                </table>

                <h4>Troubleshooting</h4>
                <dl>
                    <dt>Tool Rust</dt>
                    <dd>Caused by leaving carbon steel shears wet. Always wipe dry.</dd>
                    
                    <dt>White Crust</dt>
                    <dd>Mineral buildup on clay pots. Soak in diluted vinegar to clean.</dd>
                </dl>
            </aside>
        </section>
    </main>

<!-- WEBSITE FOOTER & COPYRIGHT -->
<?php include 'footer.inc'; ?>

<!-- BACK TO TOP BUTTON -->
<a href="#" class="back-to-top">▲</a>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const selectors = document.querySelectorAll(".option-selector");

    selectors.forEach(function (selector) {
        selector.addEventListener("change", function () {
            const selectedOption = selector.options[selector.selectedIndex];
            const selectedPrice = selectedOption.getAttribute("data-price");

            const card = selector.closest(".cactus-card");
            const priceDisplay = card.querySelector(".option-price");

            if (selectedPrice && priceDisplay) {
                priceDisplay.textContent = "RM " + selectedPrice;
            }
        });
    });
});
</script>

</body>
</html>

<?php
/* Close database connection */
if (isset($conn)) {
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BASIC PAGE METADATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cacti Collection of Cacti-Succulent Kuching">
    <meta name="keywords" content="cacti, cactus, desert plants, buy cactus">
    <meta name="author" content="Rachael, Eleona, Amber">
    
    <title>Cacti | Cacti-Succulent Kuching</title>
    
    <!-- EXTERNAL STYLESHEETS AND ICONS -->
    <link rel="stylesheet" href="styles/style.css?v=product1optionstockfixed1">
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
    <section class="hero-section hero-cacti">
        <div class="hero-content">
            <h1>Cacti Collection</h1>
            <h2>The Desert Gems of Kuching</h2>
            <p>Curated for the modern home, our hand-reared cacti bring the essence of the desert indoors. Enjoy timeless natural beauty with minimal effort.</p>
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

            /* Query to select only Cacti products */
            $sql = "SELECT id, product_name, product_options, description, price, image_path, image_source, stock_quantity
                    FROM product
                    WHERE category = 'Cacti'
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

                    /* Decide displayed price and sold out condition */
                    $display_price = number_format((float)$row['price'], 2);
                    $is_sold_out = false;
                    $has_available_option = false;
                    $first_available_option_value = "";

                    if (count($option_records) > 0) {
                        foreach ($option_records as $option_record) {
                            $option_stock = (int)$option_record['option_stock'];

                            if ($option_stock > 0) {
                                $has_available_option = true;

                                if ($first_available_option_value == "") {
                                    $display_price = number_format((float)$option_record['option_price'], 2);
                                    $option_name_for_value = trim($option_record['option_name']);
                                    $option_price_for_value = number_format((float)$option_record['option_price'], 2, '.', '');
                                    $first_available_option_value = strtolower(str_replace(' ', '-', $option_name_for_value)) . "|" . $option_price_for_value;
                                }
                            }
                        }

                        if (!$has_available_option) {
                            $is_sold_out = true;
                            $display_price = number_format((float)$option_records[0]['option_price'], 2);
                        }

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

                            <p class="price option-price">RM <?php echo htmlspecialchars($display_price); ?></p>

                            <form action="add_to_cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <input type="hidden" name="quantity" value="1">

                                <?php
                                if (count($option_records) > 0) {
                                    echo '<select class="size-selector option-selector" name="item_option">';

                                    foreach ($option_records as $option_record) {
                                        $option_name = trim($option_record['option_name']);
                                        $option_price = number_format((float)$option_record['option_price'], 2, '.', '');
                                        $option_stock = (int)$option_record['option_stock'];
                                        $option_value = strtolower(str_replace(' ', '-', $option_name)) . "|" . $option_price;

                                        $disabled = "";
                                        $selected = "";
                                        $option_label = $option_name;

                                        if ($option_stock <= 0) {
                                            $disabled = "disabled";
                                            $option_label .= " - Sold Out";
                                        }

                                        if ($option_value == $first_available_option_value) {
                                            $selected = "selected";
                                        }

                                        echo '<option value="' . htmlspecialchars($option_value) . '" data-price="' . htmlspecialchars($option_price) . '" ' . $disabled . ' ' . $selected . '>' . htmlspecialchars($option_label) . '</option>';
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
                echo "<p class='product-empty-message'>We are currently restocking our beautiful Cacti! Please check back later.</p>";
            }
            ?>
        </div>

        <!-- KNOWLEDGE BASE -->
        <section class="product-knowledge-base">
            <div class="product-main-content">
                <h3>Botanical Glossary & Care Philosophy</h3>
                <p>Understanding the anatomy of a cactus is the first step to ensuring its longevity. These plants have evolved over millennia to thrive in some of the harshest conditions on Earth. By learning their specific terminology and growth habits, you can replicate their natural habitat right here in Kuching, ensuring they remain robust and healthy year-round.</p>
                <p>It is crucial to remember that while they are hardy, they are not invincible. Overwatering is the most common mistake made by new collectors. Always err on the side of underwatering, providing them with plenty of bright, indirect light, and allowing their soil to dry out completely before the next soak. Proper drainage is absolutely essential to prevent root decay.</p>
                
                <dl class="modern-dl">
                    <dt>Xerophyte</dt>
                    <dd>Species adapted to survive in dry environments with specialized water-storage organs.</dd>

                    <dt>Areole</dt>
                    <dd>The structural cushion on a cactus from which spines, branches, and floral buds emerge.</dd>

                    <dt>Glochid</dt>
                    <dd>Fine, hair-like barbed prickles that protect the plant from herbivores and intense sun.</dd>
                </dl>
            </div>
            
            <!-- ASIDE SECTION -->
            <aside class="product-aside">
                <h3>Care Guide & Tips</h3>

                <h4>Growth Stages</h4>
                <ol>
                    <li>Seedling establishment</li>
                    <li>Vegetative root growth</li>
                    <li>Areole development</li>
                    <li>Maturation and blooming</li>
                </ol>

                <h4>Quick Specs</h4>
                <table>
                    <tr><th>Element</th><th>Requirement</th></tr>
                    <tr><td>Watering</td><td>Once a month</td></tr>
                    <tr><td>Light</td><td>Full, direct sun</td></tr>
                    <tr><td>Soil</td><td>80% Pumice</td></tr>
                </table>

                <h4>Troubleshooting</h4>
                <dl>
                    <dt>Yellowing Base</dt>
                    <dd>Usually a sign of overwatering and early root rot.</dd>

                    <dt>White Patches</dt>
                    <dd>Sunburn from sudden exposure to intense afternoon heat.</dd>
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
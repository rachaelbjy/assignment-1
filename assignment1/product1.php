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
    <link rel="stylesheet" href="styles/style.css?v=cartproduct1">
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

                    /* Check stock quantity */
                    $stock_quantity = (int)$row['stock_quantity'];
                    $is_sold_out = ($stock_quantity <= 0);
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

                            <p class="price">RM <?php echo htmlspecialchars(number_format($row['price'], 2)); ?></p>

                            <form action="add_to_cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <input type="hidden" name="quantity" value="1">

                                <?php
                                if (!empty($row['product_options'])) {
                                    $options_array = explode(',', $row['product_options']);

                                    echo '<select class="size-selector" name="item_option">';

                                    foreach ($options_array as $option) {
                                        $option = trim($option);

                                        if ($option != "") {
                                            if (strpos($option, ':') !== false) {
                                                list($opt_name, $opt_price) = explode(':', $option);
                                                $opt_name = trim($opt_name);
                                                $opt_price = number_format((float)trim($opt_price), 2);
                                                $hidden_value = htmlspecialchars(strtolower(str_replace(' ', '-', $opt_name)) . '|' . $opt_price);

                                                echo '<option value="' . $hidden_value . '">' . htmlspecialchars($opt_name) . '</option>';
                                            } else {
                                                echo '<option value="' . htmlspecialchars(strtolower(str_replace(' ', '-', $option))) . '">' . htmlspecialchars($option) . '</option>';
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

</body>
</html>

<?php
/* Close database connection */
if (isset($conn)) {
    mysqli_close($conn);
}
?>
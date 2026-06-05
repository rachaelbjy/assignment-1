<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Display the succulent product catalogue using database records.
*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BASIC PAGE METADATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Succulent Collection of Cacti-Succulent Kuching">
    <meta name="keywords" content="succulents, echeveria, aloe vera, buy succulents">
    <meta name="author" content="Rachael, Eleona, Amber">
    
    <title>Succulents | Cacti-Succulent Kuching</title>
    
    <!-- EXTERNAL STYLESHEETS AND ICONS -->
    <link rel="stylesheet" href="styles/style.css?v=cartproduct2">
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
    <section class="hero-section hero-succulents">
        <div class="hero-content">
            <h1>Succulent Collection</h1>
            <h2>The Flashy Wonders of Kuching</h2>
            <p>Explore our curated water-storing beauties. From mesmerizing rosettes to trailing vines, they offer a soft, vibrant contrast to their desert cousins.</p>
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

            /* Query to select only Succulent products */
            $sql = "SELECT id, product_name, product_options, description, price, image_path, image_source, stock_quantity
                    FROM product
                    WHERE category = 'Succulents'
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
                echo "<p class='product-empty-message'>We are currently restocking our beautiful Succulents! Please check back later.</p>";
            }
            ?>
        </div>

        <!-- KNOWLEDGE BASE -->
        <section class="product-knowledge-base">
            <div class="product-main-content">
                <h3>Succulent Glossary & Essentials</h3>
                <p>Succulents are incredibly diverse, coming in a multitude of shapes, colors, and sizes. The beauty of these plants lies in their fleshy leaves, which act as reservoirs, storing moisture to withstand prolonged periods of drought. This makes them the ultimate low-maintenance companion for busy urban dwellers who want greenery without the daily hassle.</p>
                <p>In humid climates like Malaysia, ensuring your succulents have proper air circulation and highly porous soil is more critical than watering. Their powdery coating, known as farina, should never be wiped off, as it acts as a natural sunblock. Below is a breakdown of common terms you will encounter as you expand your collection and refine your skills.</p>
                
                <dl class="modern-dl">
                    <dt>Farina</dt>
                    <dd>The powdery, waxy coating on succulent leaves that acts as a natural sunscreen and repels water.</dd>
                    
                    <dt>Rosette</dt>
                    <dd>A circular, petal-like leaf arrangement common in Echeverias, designed to catch water and direct it to the roots.</dd>
                    
                    <dt>Propagation</dt>
                    <dd>The highly rewarding process of creating entirely new succulents from fallen leaves or stem cuttings.</dd>
                </dl>
            </div>

            <!-- ASIDE SECTION -->
            <aside class="product-aside">
                <h3>Care Guide & Tips</h3>
                
                <h4>Propagation Steps</h4>
                <ol>
                    <li>Gently twist a leaf off the stem.</li>
                    <li>Let the wound callous over for 3 days.</li>
                    <li>Place leaf on dry succulent soil.</li>
                    <li>Wait for tiny rosettes to form.</li>
                </ol>

                <h4>Care Matrix</h4>
                <table>
                    <tr>
                        <th>Technique</th>
                        <th>Frequency</th>
                    </tr>
                    <tr>
                        <td>Soak Method</td>
                        <td>Bi-weekly</td>
                    </tr>
                    <tr>
                        <td>Fertilizer</td>
                        <td>Quarterly</td>
                    </tr>
                    <tr>
                        <td>Repotting</td>
                        <td>Yearly</td>
                    </tr>
                </table>

                <h4>Troubleshooting</h4>
                <dl>
                    <dt>Etiolation</dt>
                    <dd>Stretching and losing color due to insufficient sunlight.</dd>
                    
                    <dt>Mushy Leaves</dt>
                    <dd>The plant's cells have burst from absorbing too much water.</dd>
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
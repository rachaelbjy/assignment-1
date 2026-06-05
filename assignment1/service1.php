<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Display the services and workshops offered using database records.
*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BASIC PAGE METADATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Services offered by Cacti-Succulent Kuching">
    <meta name="keywords" content="plants, repotting, terrarium, workshop, Kuching">
    <meta name="author" content="Rachael, Eleona, Amber">
    
    <title>Services | Cacti-Succulent Kuching</title>

    <!-- EXTERNAL STYLESHEETS AND ICONS -->
    <link rel="stylesheet" href="styles/style.css?v=frontendstock1">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Montserrat:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
</head>

<body class="services-page">

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

    <!-- SERVICES HERO SECTION -->
    <section class="hero-section hero-services">
        <div class="hero-content">
            <h1>Our Services</h1>
            <h2>Expert Care & Workshops</h2>
            <p>From rescuing sick succulents to reliable holiday boarding, our experts ensure your desert gems thrive.</p>
            <div class="hero-divider"></div>
        </div>
    </section>

    <!-- SERVICES MAIN CONTENT SECTION -->
    <main class="content-wrapper">

        <!-- PRODUCT AND SERVICE SEARCH BAR -->
        <?php include 'product_service_search.inc'; ?>
        
        <!-- SERVICES CONTENT LAYOUT -->
        <div class="service-layout-wrapper">
        
            <!-- SERVICE CARE GUIDE SIDEBAR -->
            <aside class="service-aside">
                <h3>Care Guide & Glossary</h3>
                
                <!-- TREATMENT PROCESS LIST -->
                <h4>Treatment Process</h4>
                <ol>
                    <li>Initial health assessment</li>
                    <li>Root pruning & treatment</li>
                    <li>Repotting in custom soil</li>
                    <li>Post-care monitoring</li>
                </ol>

                <!-- WATERING SCHEDULE TABLE -->
                <h4>Watering Schedule</h4>
                <table class="aside-table">
                    <tr>
                        <th>Plant Type</th>
                        <th>Frequency</th>
                    </tr>
                    <tr>
                        <td>Cacti</td>
                        <td>Every 3 weeks</td>
                    </tr>
                    <tr>
                        <td>Succulents</td>
                        <td>Every 2 weeks</td>
                    </tr>
                    <tr>
                        <td>Terrariums</td>
                        <td>Once a month</td>
                    </tr>
                </table>

                <!-- SERVICE TERMINOLOGY LIST -->
                <h4>Terminology</h4>
                <dl class="service-dl">
                    <dt>Root Rot</dt>
                    <dd>A severe disease caused by overwatering, leading to decaying plant roots.</dd>
                    
                    <dt>Top Dressing</dt>
                    <dd>Decorative and functional gravel placed on top of soil to prevent rot and deter bugs.</dd>
                </dl>
            </aside>

            <!-- SERVICES LIST -->
            <div class="services-list">
                <?php
                /* Include database connection */
                require_once('settings.php');

                /* Query to select normal services only */
                $sql = "SELECT product_name, product_options, description, price, image_path, image_source, stock_quantity
                        FROM product
                        WHERE category = 'Services'
                        AND product_name NOT IN ('Standard Package', 'Luxury Package', 'Premium Package')
                        ORDER BY id ASC";

                $result = mysqli_query($conn, $sql);

                if ($result && mysqli_num_rows($result) > 0) {

                    /* Counter for alternating service row layout */
                    $counter = 0;

                    while ($row = mysqli_fetch_assoc($result)) {
                        $product_id = strtolower(str_replace(' ', '-', $row['product_name']));
                        $row_class = ($counter % 2 !== 0) ? "service-row reverse" : "service-row";

                        /* Check stock quantity */
                        $stock_quantity = (int)$row['stock_quantity'];
                        $is_sold_out = ($stock_quantity <= 0);

                        if ($is_sold_out) {
                            $row_class .= " sold-out-service";
                        }
                ?>

                        <article class="<?php echo htmlspecialchars($row_class); ?>" id="<?php echo htmlspecialchars($product_id); ?>">
                            <figure>
                                <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                                <figcaption>
                                    <?php echo htmlspecialchars($row['product_name']); ?>

                                    <?php if (!empty($row['image_source'])) { ?>
                                        <a href="<?php echo htmlspecialchars($row['image_source']); ?>" target="_blank">Source</a>
                                    <?php } ?>
                                </figcaption>
                            </figure>

                            <div class="service-text">
                                <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                                
                                <p><?php echo htmlspecialchars($row['description']); ?></p>
                                
                                <div class="price-action-row">
                                    <p class="price">From RM <?php echo htmlspecialchars(number_format($row['price'], 2)); ?></p>

                                    <?php if ($is_sold_out) { ?>
                                        <span class="add-to-cart sold-out-button">Unavailable</span>
                                    <?php } else { ?>
                                        <a href="enquiry.php" class="add-to-cart">Enquire Now</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </article>

                <?php
                        $counter++;
                    }

                    mysqli_free_result($result);
                } else {
                    echo "<p class='service-empty-message'>We are currently updating our service offerings! Please check back later.</p>";
                }
                ?>
            </div>
        </div>

        <!-- EVENT DOOR GIFTS PRICING SECTION -->
        <section class="pricing-section">
            <div class="pricing-header">
                <h3>Event Door Gifts</h3>
                <p>Bulk succulent orders for weddings, corporate events, and parties.</p>
            </div>
            
            <!-- PRICING PACKAGE CARDS -->
            <div class="pricing-grid">
                <?php
                /* Query to select Event Door Gifts packages only */
                $package_sql = "SELECT product_name, product_options, description, price, image_path, image_source, stock_quantity
                                FROM product
                                WHERE category = 'Services'
                                AND product_name IN ('Standard Package', 'Luxury Package', 'Premium Package')
                                ORDER BY 
                                    CASE product_name
                                        WHEN 'Standard Package' THEN 1
                                        WHEN 'Luxury Package' THEN 2
                                        WHEN 'Premium Package' THEN 3
                                        ELSE 4
                                    END";

                $package_result = mysqli_query($conn, $package_sql);

                if ($package_result && mysqli_num_rows($package_result) > 0) {
                    while ($package = mysqli_fetch_assoc($package_result)) {
                        $package_id = strtolower(str_replace(' ', '-', $package['product_name']));
                        $card_class = ($package['product_name'] == 'Luxury Package') ? "pricing-card luxury-card" : "pricing-card";
                        $button_text = ($package['product_name'] == 'Luxury Package') ? "Most Popular" : "Enquire Now";

                        /* Check package stock quantity */
                        $package_stock = (int)$package['stock_quantity'];
                        $package_sold_out = ($package_stock <= 0);

                        if ($package_sold_out) {
                            $card_class .= " sold-out-package";
                        }

                        /* Convert package description into bullet points */
                        $description_parts = preg_split('/\.\s*/', trim($package['description']));
                ?>

                        <div class="<?php echo htmlspecialchars($card_class); ?>" id="<?php echo htmlspecialchars($package_id); ?>">
                            <h4><?php echo htmlspecialchars($package['product_name']); ?></h4>

                            <p class="tier-price">
                                RM <?php echo htmlspecialchars(number_format($package['price'], 2)); ?> <span>/ plant</span>
                            </p>

                            <ul>
                                <?php
                                if (!empty($package['description'])) {
                                    foreach ($description_parts as $part) {
                                        $part = trim($part);

                                        if ($part != "" && strtolower($part) != "event door gift package") {
                                            echo "<li>" . htmlspecialchars($part) . "</li>";
                                        }
                                    }
                                } else {
                                    echo "<li>Package details will be updated soon</li>";
                                }
                                ?>
                            </ul>

                            <?php if ($package_sold_out) { ?>
                                <span class="tier-btn solid-btn sold-out-button">Unavailable</span>
                            <?php } else { ?>
                                <a href="enquiry.php" class="tier-btn solid-btn"><?php echo htmlspecialchars($button_text); ?></a>
                            <?php } ?>
                        </div>

                <?php
                    }

                    mysqli_free_result($package_result);
                } else {
                    echo "<p class='product-empty-message'>Event door gift packages are currently being updated.</p>";
                }
                ?>
            </div>
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
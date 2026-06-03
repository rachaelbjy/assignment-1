<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Reusable PHP search section for product and service pages.
*/

/* Get current page name */
$current_page = basename($_SERVER['PHP_SELF']);

/* Get search keyword */
$search_keyword = isset($_GET['search']) ? trim($_GET['search']) : "";

/* Product and service search data based only on actual website items */
$search_items = [
    /* Cacti - product1.php */
    [
        "name" => "Golden Barrel",
        "category" => "Cacti",
        "keywords" => "golden barrel cactus e grusonii round cactus desert plant",
        "page" => "product1.php",
        "anchor" => "golden-barrel"
    ],
    [
        "name" => "Bunny Ear",
        "category" => "Cacti",
        "keywords" => "bunny ear cactus opuntia microdasys cute cactus",
        "page" => "product1.php",
        "anchor" => "bunny-ear"
    ],
    [
        "name" => "Old Lady",
        "category" => "Cacti",
        "keywords" => "old lady cactus mammillaria hahniana blooming cactus",
        "page" => "product1.php",
        "anchor" => "old-lady"
    ],
    [
        "name" => "Princess of Night",
        "category" => "Cacti",
        "keywords" => "princess of night cactus cereus jamacaru night cactus",
        "page" => "product1.php",
        "anchor" => "princess-of-night"
    ],
    [
        "name" => "Prickly Pear",
        "category" => "Cacti",
        "keywords" => "prickly pear cactus opuntia ficus indica cactus plant",
        "page" => "product1.php",
        "anchor" => "prickly-pear"
    ],
    [
        "name" => "Cardon",
        "category" => "Cacti",
        "keywords" => "cardon cactus pachycereus pringlei tall cactus",
        "page" => "product1.php",
        "anchor" => "cardon"
    ],

    /* Succulents - product2.php */
    [
        "name" => "Echeveria Lola",
        "category" => "Succulents",
        "keywords" => "echeveria lola succulent echeveria laui rosette plant",
        "page" => "product2.php",
        "anchor" => "echeveria-lola"
    ],
    [
        "name" => "Jade Plant",
        "category" => "Succulents",
        "keywords" => "jade plant succulent crassula ovata indoor plant",
        "page" => "product2.php",
        "anchor" => "jade-plant"
    ],
    [
        "name" => "Moonstones",
        "category" => "Succulents",
        "keywords" => "moonstones succulent graptopetalum amethystinum",
        "page" => "product2.php",
        "anchor" => "moonstones"
    ],
    [
        "name" => "Aloe Vera",
        "category" => "Succulents",
        "keywords" => "aloe vera succulent aloe barbadensis medicinal plant",
        "page" => "product2.php",
        "anchor" => "aloe-vera"
    ],
    [
        "name" => "Ghosty",
        "category" => "Succulents",
        "keywords" => "ghosty succulent graptopetalum paraguayense ghost plant",
        "page" => "product2.php",
        "anchor" => "ghosty"
    ],
    [
        "name" => "String of Pearls",
        "category" => "Succulents",
        "keywords" => "string of pearls succulent senecio rowleyanus hanging plant",
        "page" => "product2.php",
        "anchor" => "string-of-pearls"
    ],

    /* Accessories - product3.php */
    [
        "name" => "Artisan Terracotta",
        "category" => "Accessories",
        "keywords" => "artisan terracotta pot clay pot breathable clay accessories",
        "page" => "product3.php",
        "anchor" => "artisan-terracotta"
    ],
    [
        "name" => "Premium Cactus Mix",
        "category" => "Accessories",
        "keywords" => "premium cactus mix soil fast draining soil accessories",
        "page" => "product3.php",
        "anchor" => "premium-cactus-mix"
    ],
    [
        "name" => "Natural Pumice",
        "category" => "Accessories",
        "keywords" => "natural pumice volcanic rock soil drainage accessories",
        "page" => "product3.php",
        "anchor" => "natural-pumice"
    ],
    [
        "name" => "Long-Spout Can",
        "category" => "Accessories",
        "keywords" => "long spout can watering can stainless steel accessories",
        "page" => "product3.php",
        "anchor" => "long-spout-can"
    ],
    [
        "name" => "Planting Tweezers",
        "category" => "Accessories",
        "keywords" => "planting tweezers tools extra long gardening accessories",
        "page" => "product3.php",
        "anchor" => "planting-tweezers"
    ],
    [
        "name" => "Pruning Shears",
        "category" => "Accessories",
        "keywords" => "pruning shears carbon steel cutting tools accessories",
        "page" => "product3.php",
        "anchor" => "pruning-shears"
    ],
    [
        "name" => "Liquid Cactus Food",
        "category" => "Accessories",
        "keywords" => "liquid cactus food fertilizer low nitrogen accessories",
        "page" => "product3.php",
        "anchor" => "liquid-cactus-food"
    ],
    [
        "name" => "River Stone Dressing",
        "category" => "Accessories",
        "keywords" => "river stone dressing decorative stones top dressing accessories",
        "page" => "product3.php",
        "anchor" => "river-stone-dressing"
    ],
    [
        "name" => "Soil Moisture Meter",
        "category" => "Accessories",
        "keywords" => "soil moisture meter root level tech watering tool accessories",
        "page" => "product3.php",
        "anchor" => "soil-moisture-meter"
    ],

    /* Services - service1.php */
    [
        "name" => "The Plant Hospital",
        "category" => "Services",
        "keywords" => "plant hospital professional repotting plant care treatment service",
        "page" => "service1.php",
        "anchor" => "plant-hospital"
    ],
    [
        "name" => "Custom Terrariums",
        "category" => "Services",
        "keywords" => "custom terrariums terrarium cactus succulent service",
        "page" => "service1.php",
        "anchor" => "custom-terrariums"
    ],
    [
        "name" => "Terrarium Workshop",
        "category" => "Services",
        "keywords" => "terrarium workshop class activity service",
        "page" => "service1.php",
        "anchor" => "terrarium-workshop"
    ],
    [
        "name" => "Plant Boarding",
        "category" => "Services",
        "keywords" => "plant boarding plant care temporary care service",
        "page" => "service1.php",
        "anchor" => "plant-boarding"
    ],
    [
        "name" => "Standard Package",
        "category" => "Services",
        "keywords" => "standard package event door gifts service",
        "page" => "service1.php",
        "anchor" => "standard-package"
    ],
    [
        "name" => "Luxury Package",
        "category" => "Services",
        "keywords" => "luxury package event door gifts service",
        "page" => "service1.php",
        "anchor" => "luxury-package"
    ],
    [
        "name" => "Premium Package",
        "category" => "Services",
        "keywords" => "premium package event door gifts service",
        "page" => "service1.php",
        "anchor" => "premium-package"
    ]
];

/* Search matching results */
$matched_items = [];

if ($search_keyword != "" && strlen($search_keyword) >= 2) {
    foreach ($search_items as $item) {
        $search_text = strtolower($item["name"] . " " . $item["category"] . " " . $item["keywords"]);
        $keyword = strtolower($search_keyword);

        if (strpos($search_text, $keyword) !== false) {
            $matched_items[] = $item;
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
                echo "<li>";
                echo "<a href='" . htmlspecialchars($item["page"]) . "#" . htmlspecialchars($item["anchor"]) . "'>";
                echo "<span class='site-search-name'>" . htmlspecialchars($item["name"]) . "</span>";
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
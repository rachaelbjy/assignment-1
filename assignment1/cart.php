<?php
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Display shopping cart items before checkout.
*/

/* Start session to read cart data */
session_start();

/* Create cart if it does not exist */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* Calculate cart total */
$cart_total = 0;

foreach ($_SESSION['cart'] as $cart_item) {
    $cart_total += $cart_item['price'] * $cart_item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BASIC PAGE METADATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Shopping cart page for Cacti-Succulent Kuching">
    <meta name="keywords" content="cart, shopping cart, cacti, succulent">
    <meta name="author" content="Rachael, Eleona, Amber">

    <title>Shopping Cart | Cacti-Succulent Kuching</title>

    <!-- EXTERNAL STYLESHEETS AND ICONS -->
    <link rel="stylesheet" href="styles/style.css?v=cartoptionstock1">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Montserrat:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
</head>

<body class="cart-page">

<!-- HEADER & NAVIGATION -->
<?php include 'header.inc'; ?>

    <!-- CART SECTION -->
    <main class="form-main">
        <div class="form-header">
            <h1>Shopping Cart</h1>
            <p>Review your selected products before checkout</p>
        </div>

        <div class="form-card">
            <fieldset class="admin-record-fieldset">
                <legend>Cart Items</legend>

                <?php if (empty($_SESSION['cart'])) { ?>

                    <!-- EMPTY CART MESSAGE -->
                    <p class="admin-message-bold">Your cart is currently empty.</p>

                    <div class="admin-action-buttons">
                        <a href="product1.php" class="admin-action-link">Continue Shopping</a>
                    </div>

                <?php } else { ?>

                    <!-- CART TABLE -->
                    <div class="admin-table-wrapper">
                        <table class="admin-summary-table cart-table">
                            <tr>
                                <th>Item</th>
                                <th>Option</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>

                            <?php
                            foreach ($_SESSION['cart'] as $cart_key => $cart_item) {
                                $subtotal = $cart_item['price'] * $cart_item['quantity'];
                                $available_stock = isset($cart_item['available_stock']) ? (int)$cart_item['available_stock'] : 20;

                                if ($available_stock < 1) {
                                    $available_stock = 1;
                                }

                                if ($available_stock > 20) {
                                    $max_quantity = 20;
                                } else {
                                    $max_quantity = $available_stock;
                                }
                            ?>

                                <tr>
                                    <td>
                                        <div class="cart-item-info">
                                            <img src="<?php echo htmlspecialchars($cart_item['image_path']); ?>" alt="<?php echo htmlspecialchars($cart_item['product_name']); ?>">
                                            <span><?php echo htmlspecialchars($cart_item['product_name']); ?></span>
                                        </div>
                                    </td>

                                    <td>
                                        <?php
                                        if (!empty($cart_item['selected_option'])) {
                                            echo htmlspecialchars($cart_item['selected_option']);
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </td>

                                    <td>RM <?php echo htmlspecialchars(number_format($cart_item['price'], 2)); ?></td>

                                    <td>
                                        <form action="update_cart.php" method="post" class="cart-quantity-form">
                                            <input type="hidden" name="cart_key" value="<?php echo htmlspecialchars($cart_key); ?>">
                                            <input type="number" name="quantity" min="1" max="<?php echo htmlspecialchars($max_quantity); ?>" value="<?php echo htmlspecialchars($cart_item['quantity']); ?>">
                                            <input type="submit" value="Update">
                                        </form>

                                        <small class="cart-stock-note">
                                            Available: <?php echo htmlspecialchars($available_stock); ?>
                                        </small>
                                    </td>

                                    <td>RM <?php echo htmlspecialchars(number_format($subtotal, 2)); ?></td>

                                    <td>
                                        <a href="remove_from_cart.php?item=<?php echo urlencode($cart_key); ?>" class="admin-view-btn">Remove</a>
                                    </td>
                                </tr>

                            <?php } ?>

                            <tr>
                                <th colspan="4">Total</th>
                                <th colspan="2">RM <?php echo htmlspecialchars(number_format($cart_total, 2)); ?></th>
                            </tr>
                        </table>
                    </div>

                    <!-- CART ACTION BUTTONS -->
                    <div class="admin-action-buttons">
                        <a href="product1.php" class="admin-action-link">Continue Shopping</a>
                        <a href="clear_cart.php" class="admin-action-link">Clear Cart</a>
                        <a href="order.php" class="admin-action-link">Checkout</a>
                    </div>

                <?php } ?>
            </fieldset>
        </div>
    </main>

<!-- WEBSITE FOOTER & COPYRIGHT -->
<?php include 'footer.inc'; ?>

<!-- BACK TO TOP BUTTON -->
<a href="#" class="back-to-top">▲</a>

</body>
</html>
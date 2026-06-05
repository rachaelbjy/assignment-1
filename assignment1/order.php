<?php 
/*
    Author: Rachael, Eleona, Amber
    Student ID: 104402891, 104403014, 104399472
    Purpose: Display order checkout form using shopping cart items.
*/

/* Start session to retrieve cart and previously entered order data */
session_start(); 

/* Create cart if it does not exist */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* Retrieve saved order input from session, if available */
$d = isset($_SESSION['order_data']) ? $_SESSION['order_data'] : [];

$v_delivery = isset($d['delivery']) ? $d['delivery'] : '';
$v_payment = isset($d['payment']) ? $d['payment'] : '';
$v_date = isset($d['date']) ? htmlspecialchars($d['date']) : '';
$v_time = isset($d['time']) ? $d['time'] : '';
$v_name = isset($d['name']) ? htmlspecialchars($d['name']) : '';
$v_email = isset($d['email']) ? htmlspecialchars($d['email']) : '';
$v_phone = isset($d['phone']) ? htmlspecialchars($d['phone']) : '';
$v_address = isset($d['address']) ? htmlspecialchars($d['address']) : '';
$v_terms = isset($d['terms']) ? true : false;

/* Clear saved order data after loading it */
unset($_SESSION['order_data']);

/* Prepare cart items */
$cart_items = array_values($_SESSION['cart']);
$cart_count = count($cart_items);
$cart_total = 0;

foreach ($cart_items as $cart_item) {
    $cart_total += $cart_item['price'] * $cart_item['quantity'];
}

/* Prepare hidden product values for existing order_process.php */
$product1 = "";
$quantity1 = 0;
$product2 = "";
$quantity2 = 0;
$product3 = "";
$quantity3 = 0;

if ($cart_count >= 1) {
    $product1 = $cart_items[0]['product_name'];

    if (!empty($cart_items[0]['selected_option'])) {
        $product1 .= " - " . $cart_items[0]['selected_option'];
    }

    $quantity1 = $cart_items[0]['quantity'];
}

if ($cart_count >= 2) {
    $product2 = $cart_items[1]['product_name'];

    if (!empty($cart_items[1]['selected_option'])) {
        $product2 .= " - " . $cart_items[1]['selected_option'];
    }

    $quantity2 = $cart_items[1]['quantity'];
}

if ($cart_count >= 3) {
    $product3 = $cart_items[2]['product_name'];

    if (!empty($cart_items[2]['selected_option'])) {
        $product3 .= " - " . $cart_items[2]['selected_option'];
    }

    $quantity3 = $cart_items[2]['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BASIC PAGE METADATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Order page for Cacti-Succulent Kuching">
    <meta name="keywords" content="order, buy plants, cactus, succulent, Kuching delivery">
    <meta name="author" content="Rachael, Eleona, Amber">
    
    <title>Order | Cacti-Succulent Kuching</title>

    <!-- EXTERNAL STYLESHEETS AND ICONS -->
    <link rel="stylesheet" href="styles/style.css?v=cartcheckout2">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Montserrat:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
</head>

<body class="order-page">

<!-- HEADER & NAVIGATION -->
<?php include 'header.inc'; ?>

    <!-- ORDER FORM SECTION -->
    <main class="form-main">
        <div class="form-header">
            <h1>Order Form</h1>
            <p>Confirm your cart items and complete your order details</p>
        </div>

        <div class="form-card">

            <?php if ($cart_count == 0) { ?>

                <!-- EMPTY CART MESSAGE -->
                <fieldset>
                    <legend>Order Details</legend>

                    <p class="admin-message-bold">Your cart is currently empty.</p>

                    <div class="admin-action-buttons">
                        <a href="product1.php" class="admin-action-link">Continue Shopping</a>
                        <a href="cart.php" class="admin-action-link">View Cart</a>
                    </div>
                </fieldset>

            <?php } else if ($cart_count > 3) { ?>

                <!-- TOO MANY ITEMS MESSAGE -->
                <fieldset>
                    <legend>Order Details</legend>

                    <p class="admin-message-bold">
                        Your cart has more than 3 different items. Please reduce your cart to 3 different items or fewer before checkout.
                    </p>

                    <div class="admin-action-buttons">
                        <a href="cart.php" class="admin-action-link">Back to Cart</a>
                    </div>
                </fieldset>

            <?php } else { ?>

                <!-- CUSTOMER ORDER FORM -->
                <form action="order_process.php" method="post">

                    <!-- CART SUMMARY FIELDSET -->
                    <fieldset>
                        <legend>Order Summary</legend>

                        <div class="order-summary-list">
                            <?php
                            foreach ($cart_items as $cart_item) {
                                $subtotal = $cart_item['price'] * $cart_item['quantity'];
                            ?>

                                <div class="order-summary-item">
                                    <img src="<?php echo htmlspecialchars($cart_item['image_path']); ?>" alt="<?php echo htmlspecialchars($cart_item['product_name']); ?>">

                                    <div class="order-summary-info">
                                        <h3><?php echo htmlspecialchars($cart_item['product_name']); ?></h3>

                                        <?php if (!empty($cart_item['selected_option'])) { ?>
                                            <p>Option: <?php echo htmlspecialchars($cart_item['selected_option']); ?></p>
                                        <?php } else { ?>
                                            <p>Option: -</p>
                                        <?php } ?>

                                        <p>Price: RM <?php echo htmlspecialchars(number_format($cart_item['price'], 2)); ?></p>
                                        <p>Quantity: <?php echo htmlspecialchars($cart_item['quantity']); ?></p>
                                    </div>

                                    <div class="order-summary-subtotal">
                                        <span>Subtotal</span>
                                        <strong>RM <?php echo htmlspecialchars(number_format($subtotal, 2)); ?></strong>
                                    </div>
                                </div>

                            <?php } ?>
                        </div>

                        <div class="order-total-box">
                            <span>Total</span>
                            <strong>RM <?php echo htmlspecialchars(number_format($cart_total, 2)); ?></strong>
                        </div>

                        <!-- HIDDEN PRODUCT FIELDS FOR EXISTING ORDER PROCESS -->
                        <input type="hidden" name="product1" value="<?php echo htmlspecialchars($product1); ?>">
                        <input type="hidden" name="quantity1" value="<?php echo htmlspecialchars($quantity1); ?>">

                        <input type="hidden" name="product2" value="<?php echo htmlspecialchars($product2); ?>">
                        <input type="hidden" name="quantity2" value="<?php echo htmlspecialchars($quantity2); ?>">

                        <input type="hidden" name="product3" value="<?php echo htmlspecialchars($product3); ?>">
                        <input type="hidden" name="quantity3" value="<?php echo htmlspecialchars($quantity3); ?>">

                        <div class="admin-action-buttons">
                            <a href="cart.php" class="admin-action-link">Edit Cart</a>
                        </div>
                    </fieldset>

                    <!-- DELIVERY AND PAYMENT FIELDSET -->
                    <fieldset>
                        <legend>Delivery and Payment</legend>

                        <!-- DELIVERY AND PAYMENT OPTIONS -->
                        <div class="form-row">
                            <div class="input-group">
                                <label>Delivery Mode</label>
                                <div class="radio-group">
                                    <input type="radio" id="del-pickup" name="delivery" value="pickup" <?php echo ($v_delivery == 'pickup') ? 'checked' : ''; ?> required>
                                    <label for="del-pickup" class="inline-label">Self Pick-up</label>
                                    
                                    <input type="radio" id="del-home" name="delivery" value="delivery" <?php echo ($v_delivery == 'delivery') ? 'checked' : ''; ?> required>
                                    <label for="del-home" class="inline-label">Home Delivery</label>
                                </div>
                            </div>

                            <div class="input-group">
                                <label>Payment Mode</label>
                                <div class="radio-group">
                                    <input type="radio" id="pay-cash" name="payment" value="cash" <?php echo ($v_payment == 'cash') ? 'checked' : ''; ?> required>
                                    <label for="pay-cash" class="inline-label">Cash</label>
                                    
                                    <input type="radio" id="pay-online" name="payment" value="online" <?php echo ($v_payment == 'online') ? 'checked' : ''; ?> required>
                                    <label for="pay-online" class="inline-label">Online Transfer</label>
                                </div>
                            </div>
                        </div>

                        <!-- PREFERRED DATE AND TIME -->
                        <div class="form-row">
                            <div class="input-group">
                                <label for="date">Preferred Date</label>
                                <input type="date" id="date" name="date" value="<?php echo $v_date; ?>" required>
                            </div>

                            <div class="input-group">
                                <label for="time">Preferred Time</label>
                                <select id="time" name="time" required>
                                    <option value="" <?php echo ($v_time == '') ? 'selected' : ''; ?>>Select Time Slot</option>
                                    <option value="morning" <?php echo ($v_time == 'morning') ? 'selected' : ''; ?>>Morning (9AM - 12PM)</option>
                                    <option value="afternoon" <?php echo ($v_time == 'afternoon') ? 'selected' : ''; ?>>Afternoon (1PM - 4PM)</option>
                                    <option value="evening" <?php echo ($v_time == 'evening') ? 'selected' : ''; ?>>Evening (5PM - 8PM)</option>
                                </select>
                            </div>
                        </div>
                    </fieldset>

                    <!-- CUSTOMER INFORMATION FIELDSET -->
                    <fieldset>
                        <legend>Customer Information</legend>
                        
                        <!-- CUSTOMER NAME -->
                        <div class="form-row">
                            <div class="input-group full-width">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" value="<?php echo $v_name; ?>" placeholder="Enter your full name" required>
                            </div>
                        </div>

                        <!-- CUSTOMER CONTACT DETAILS -->
                        <div class="form-row">
                            <div class="input-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" value="<?php echo $v_email; ?>" placeholder="e.g. hello@example.com" required>
                            </div>

                            <div class="input-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" value="<?php echo $v_phone; ?>" maxlength="11" pattern="[0-9]{10,11}" title="Please enter 10 to 11 digit phone number without dashes" placeholder="e.g. 0128884444" required>
                            </div>
                        </div>

                        <!-- CUSTOMER ADDRESS -->
                        <div class="form-row">
                            <div class="input-group full-width">
                                <label for="address">Full Address</label>
                                <textarea id="address" name="address" rows="4" cols="50" placeholder="Enter your delivery or billing address" required><?php echo $v_address; ?></textarea>
                            </div>
                        </div>
                    </fieldset> 
                    
                    <!-- TERMS AND CONDITIONS CHECKBOX -->
                    <div class="form-row checkbox-row">
                        <div class="input-group full-width checkbox-group">
                            <input type="checkbox" id="terms" name="terms" <?php echo $v_terms ? 'checked' : ''; ?> required>
                            <label for="terms" class="inline-label">I agree to the Terms and Conditions and confirm my order details.</label>
                        </div>
                    </div>

                    <!-- FORM BUTTONS -->
                    <div class="button-group">
                        <input type="submit" value="Submit Order">
                        <input type="reset" value="Clear Form">
                    </div>
                    
                </form>

            <?php } ?>

        </div>
    </main>

<!-- WEBSITE FOOTER & COPYRIGHT -->
<?php include 'footer.inc'; ?>

<!-- BACK TO TOP BUTTON -->
<a href="#" class="back-to-top">▲</a>

<!-- ORDER DATE ERROR MESSAGE SCRIPT -->
<?php
if (isset($_GET['error']) && $_GET['error'] === 'invalid_date') {
    echo "<script>
        window.onload = function() {
            var dateInput = document.getElementById('date');
            if (dateInput) {
                dateInput.setCustomValidity(''); 
                setTimeout(function() {
                    dateInput.setCustomValidity('Please select a valid future date!');
                    dateInput.reportValidity();
                    window.history.replaceState({}, document.title, window.location.pathname);
                }, 100);

                dateInput.addEventListener('input', function() {
                    dateInput.setCustomValidity('');
                });
            }
        };
    </script>";
}
?>

</body>
</html>
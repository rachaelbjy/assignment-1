<?php 
session_start(); 
$d = isset($_SESSION['order_data']) ? $_SESSION['order_data'] : [];
$v_product1 = isset($d['product1']) ? $d['product1'] : '';
$v_quantity1 = isset($d['quantity1']) ? htmlspecialchars($d['quantity1']) : '';
$v_product2 = isset($d['product2']) ? $d['product2'] : '';
$v_quantity2 = isset($d['quantity2']) ? htmlspecialchars($d['quantity2']) : '';
$v_product3 = isset($d['product3']) ? $d['product3'] : '';
$v_quantity3 = isset($d['quantity3']) ? htmlspecialchars($d['quantity3']) : '';
$v_delivery = isset($d['delivery']) ? $d['delivery'] : '';
$v_payment = isset($d['payment']) ? $d['payment'] : '';
$v_date = isset($d['date']) ? htmlspecialchars($d['date']) : '';
$v_time = isset($d['time']) ? $d['time'] : '';
$v_name = isset($d['name']) ? htmlspecialchars($d['name']) : '';
$v_email = isset($d['email']) ? htmlspecialchars($d['email']) : '';
$v_phone = isset($d['phone']) ? htmlspecialchars($d['phone']) : '';
$v_address = isset($d['address']) ? htmlspecialchars($d['address']) : '';
$v_terms = isset($d['terms']) ? true : false;

unset($_SESSION['order_data']); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Order page for Cacti-Succulent Kuching">
    <meta name="keywords" content="order, buy plants, cactus, succulent, Kuching delivery">
    <meta name="author" content="Rachael, Eleona, Amber">
    
    <title>Order | Cacti-Succulent Kuching</title>
    
    <link rel="stylesheet" href="styles/style.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Montserrat:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
</head>

<body class="order-page">

<?php include 'header.inc'; ?>

    <main class="form-main">
        <div class="form-header">
            <h1>Order Form</h1>
            <p>Order your favorite plants easily and quickly</p>
        </div>

        <div class="form-card">
            <form action="process_order.php" method="post">
                
                <fieldset>
                    <legend>Order Details</legend>
                    
                    <div class="form-row">
                        <div class="input-group">
                            <label for="product1">Item 1</label>
                            <select id="product1" name="product1" required>
                                <option value="" disabled <?php echo ($v_product1 == '') ? 'selected' : ''; ?>>Choose a Product</option>
                                <optgroup label="1. Cacti Collection">
                                    <option value="golden-barrel" <?php echo ($v_product1 == 'golden-barrel') ? 'selected' : ''; ?>>Golden Barrel</option>
                                    <option value="bunny-ear" <?php echo ($v_product1 == 'bunny-ear') ? 'selected' : ''; ?>>Bunny Ear</option>
                                    <option value="old-lady" <?php echo ($v_product1 == 'old-lady') ? 'selected' : ''; ?>>Old Lady</option>
                                    <option value="princess-night" <?php echo ($v_product1 == 'princess-night') ? 'selected' : ''; ?>>Princess of the Night</option>
                                    <option value="prickly-pear" <?php echo ($v_product1 == 'prickly-pear') ? 'selected' : ''; ?>>Prickly Pear</option>
                                    <option value="cardon" <?php echo ($v_product1 == 'cardon') ? 'selected' : ''; ?>>Cardon</option>
                                </optgroup>
                                <option disabled label="&nbsp;">&nbsp;</option> 
                                <optgroup label="2. Succulent Collection">
                                    <option value="lola" <?php echo ($v_product1 == 'lola') ? 'selected' : ''; ?>>Echeveria Lola</option>
                                    <option value="jade" <?php echo ($v_product1 == 'jade') ? 'selected' : ''; ?>>Jade Plant</option>
                                    <option value="moonstones" <?php echo ($v_product1 == 'moonstones') ? 'selected' : ''; ?>>Moonstones</option>
                                    <option value="aloe" <?php echo ($v_product1 == 'aloe') ? 'selected' : ''; ?>>Aloe Vera</option>
                                    <option value="ghosty" <?php echo ($v_product1 == 'ghosty') ? 'selected' : ''; ?>>Ghosty</option>
                                    <option value="string-of-pearls" <?php echo ($v_product1 == 'string-of-pearls') ? 'selected' : ''; ?>>String of Pearls</option>
                                </optgroup>
                                <option disabled label="&nbsp;">&nbsp;</option> 
                                <optgroup label="3. Planting Accessories">
                                    <option value="terracotta" <?php echo ($v_product1 == 'terracotta') ? 'selected' : ''; ?>>Artisan Terracotta</option>
                                    <option value="cactus-mix" <?php echo ($v_product1 == 'cactus-mix') ? 'selected' : ''; ?>>Premium Cactus Mix</option>
                                    <option value="pumice" <?php echo ($v_product1 == 'pumice') ? 'selected' : ''; ?>>Natural Pumice</option>
                                    <option value="watering-can" <?php echo ($v_product1 == 'watering-can') ? 'selected' : ''; ?>>Long-Spout Can</option>
                                    <option value="tweezers" <?php echo ($v_product1 == 'tweezers') ? 'selected' : ''; ?>>Planting Tweezers</option>
                                    <option value="shears" <?php echo ($v_product1 == 'shears') ? 'selected' : ''; ?>>Pruning Shears</option>
                                    <option value="fertilizer" <?php echo ($v_product1 == 'fertilizer') ? 'selected' : ''; ?>>Liquid Cactus Food</option>
                                    <option value="dressing" <?php echo ($v_product1 == 'dressing') ? 'selected' : ''; ?>>River Stone Dressing</option>
                                    <option value="moisture-meter" <?php echo ($v_product1 == 'moisture-meter') ? 'selected' : ''; ?>>Soil Moisture Meter</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="input-group">
                            <label for="quantity1">Quantity 1</label>
                            <input type="number" id="quantity1" name="quantity1" value="<?php echo $v_quantity1; ?>" min="1" max="20" placeholder="Qty" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group">
                            <label for="product2">Item 2</label>
                            <select id="product2" name="product2">
                                <option value="" <?php echo ($v_product2 == '') ? 'selected' : ''; ?>>Choose a Product</option>
                                <optgroup label="1. Cacti Collection">
                                    <option value="golden-barrel" <?php echo ($v_product2 == 'golden-barrel') ? 'selected' : ''; ?>>Golden Barrel</option>
                                    <option value="bunny-ear" <?php echo ($v_product2 == 'bunny-ear') ? 'selected' : ''; ?>>Bunny Ear</option>
                                    <option value="old-lady" <?php echo ($v_product2 == 'old-lady') ? 'selected' : ''; ?>>Old Lady</option>
                                    <option value="princess-night" <?php echo ($v_product2 == 'princess-night') ? 'selected' : ''; ?>>Princess of the Night</option>
                                    <option value="prickly-pear" <?php echo ($v_product2 == 'prickly-pear') ? 'selected' : ''; ?>>Prickly Pear</option>
                                    <option value="cardon" <?php echo ($v_product2 == 'cardon') ? 'selected' : ''; ?>>Cardon</option>
                                </optgroup>
                                <option disabled label="&nbsp;">&nbsp;</option> 
                                <optgroup label="2. Succulent Collection">
                                    <option value="lola" <?php echo ($v_product2 == 'lola') ? 'selected' : ''; ?>>Echeveria Lola</option>
                                    <option value="jade" <?php echo ($v_product2 == 'jade') ? 'selected' : ''; ?>>Jade Plant</option>
                                    <option value="moonstones" <?php echo ($v_product2 == 'moonstones') ? 'selected' : ''; ?>>Moonstones</option>
                                    <option value="aloe" <?php echo ($v_product2 == 'aloe') ? 'selected' : ''; ?>>Aloe Vera</option>
                                    <option value="ghosty" <?php echo ($v_product2 == 'ghosty') ? 'selected' : ''; ?>>Ghosty</option>
                                    <option value="string-of-pearls" <?php echo ($v_product2 == 'string-of-pearls') ? 'selected' : ''; ?>>String of Pearls</option>
                                </optgroup>
                                <option disabled label="&nbsp;">&nbsp;</option> 
                                <optgroup label="3. Planting Accessories">
                                    <option value="terracotta" <?php echo ($v_product2 == 'terracotta') ? 'selected' : ''; ?>>Artisan Terracotta</option>
                                    <option value="cactus-mix" <?php echo ($v_product2 == 'cactus-mix') ? 'selected' : ''; ?>>Premium Cactus Mix</option>
                                    <option value="pumice" <?php echo ($v_product2 == 'pumice') ? 'selected' : ''; ?>>Natural Pumice</option>
                                    <option value="watering-can" <?php echo ($v_product2 == 'watering-can') ? 'selected' : ''; ?>>Long-Spout Can</option>
                                    <option value="tweezers" <?php echo ($v_product2 == 'tweezers') ? 'selected' : ''; ?>>Planting Tweezers</option>
                                    <option value="shears" <?php echo ($v_product2 == 'shears') ? 'selected' : ''; ?>>Pruning Shears</option>
                                    <option value="fertilizer" <?php echo ($v_product2 == 'fertilizer') ? 'selected' : ''; ?>>Liquid Cactus Food</option>
                                    <option value="dressing" <?php echo ($v_product2 == 'dressing') ? 'selected' : ''; ?>>River Stone Dressing</option>
                                    <option value="moisture-meter" <?php echo ($v_product2 == 'moisture-meter') ? 'selected' : ''; ?>>Soil Moisture Meter</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="input-group">
                            <label for="quantity2">Quantity 2</label>
                            <input type="number" id="quantity2" name="quantity2" value="<?php echo $v_quantity2; ?>" min="1" max="20" placeholder="Qty">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group">
                            <label for="product3">Item 3</label>
                            <select id="product3" name="product3">
                                <option value="" <?php echo ($v_product3 == '') ? 'selected' : ''; ?>>Choose a Product</option>
                                <optgroup label="1. Cacti Collection">
                                    <option value="golden-barrel" <?php echo ($v_product3 == 'golden-barrel') ? 'selected' : ''; ?>>Golden Barrel</option>
                                    <option value="bunny-ear" <?php echo ($v_product3 == 'bunny-ear') ? 'selected' : ''; ?>>Bunny Ear</option>
                                    <option value="old-lady" <?php echo ($v_product3 == 'old-lady') ? 'selected' : ''; ?>>Old Lady</option>
                                    <option value="princess-night" <?php echo ($v_product3 == 'princess-night') ? 'selected' : ''; ?>>Princess of the Night</option>
                                    <option value="prickly-pear" <?php echo ($v_product3 == 'prickly-pear') ? 'selected' : ''; ?>>Prickly Pear</option>
                                    <option value="cardon" <?php echo ($v_product3 == 'cardon') ? 'selected' : ''; ?>>Cardon</option>
                                </optgroup>
                                <option disabled label="&nbsp;">&nbsp;</option> 
                                <optgroup label="2. Succulent Collection">
                                    <option value="lola" <?php echo ($v_product3 == 'lola') ? 'selected' : ''; ?>>Echeveria Lola</option>
                                    <option value="jade" <?php echo ($v_product3 == 'jade') ? 'selected' : ''; ?>>Jade Plant</option>
                                    <option value="moonstones" <?php echo ($v_product3 == 'moonstones') ? 'selected' : ''; ?>>Moonstones</option>
                                    <option value="aloe" <?php echo ($v_product3 == 'aloe') ? 'selected' : ''; ?>>Aloe Vera</option>
                                    <option value="ghosty" <?php echo ($v_product3 == 'ghosty') ? 'selected' : ''; ?>>Ghosty</option>
                                    <option value="string-of-pearls" <?php echo ($v_product3 == 'string-of-pearls') ? 'selected' : ''; ?>>String of Pearls</option>
                                </optgroup>
                                <option disabled label="&nbsp;">&nbsp;</option> 
                                <optgroup label="3. Planting Accessories">
                                    <option value="terracotta" <?php echo ($v_product3 == 'terracotta') ? 'selected' : ''; ?>>Artisan Terracotta</option>
                                    <option value="cactus-mix" <?php echo ($v_product3 == 'cactus-mix') ? 'selected' : ''; ?>>Premium Cactus Mix</option>
                                    <option value="pumice" <?php echo ($v_product3 == 'pumice') ? 'selected' : ''; ?>>Natural Pumice</option>
                                    <option value="watering-can" <?php echo ($v_product3 == 'watering-can') ? 'selected' : ''; ?>>Long-Spout Can</option>
                                    <option value="tweezers" <?php echo ($v_product3 == 'tweezers') ? 'selected' : ''; ?>>Planting Tweezers</option>
                                    <option value="shears" <?php echo ($v_product3 == 'shears') ? 'selected' : ''; ?>>Pruning Shears</option>
                                    <option value="fertilizer" <?php echo ($v_product3 == 'fertilizer') ? 'selected' : ''; ?>>Liquid Cactus Food</option>
                                    <option value="dressing" <?php echo ($v_product3 == 'dressing') ? 'selected' : ''; ?>>River Stone Dressing</option>
                                    <option value="moisture-meter" <?php echo ($v_product3 == 'moisture-meter') ? 'selected' : ''; ?>>Soil Moisture Meter</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="input-group">
                            <label for="quantity3">Quantity 3</label>
                            <input type="number" id="quantity3" name="quantity3" value="<?php echo $v_quantity3; ?>" min="1" max="20" placeholder="Qty">
                        </div>
                    </div>

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

                <fieldset>
                    <legend>Customer Information</legend>
                    
                    <div class="form-row">
                        <div class="input-group full-width">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" value="<?php echo $v_name; ?>" placeholder="Enter your full name" required>
                        </div>
                    </div>

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

                    <div class="form-row">
                        <div class="input-group full-width">
                            <label for="address">Full Address</label>
                            <textarea id="address" name="address" rows="4" cols="50" placeholder="Enter your delivery or billing address" required><?php echo $v_address; ?></textarea>
                        </div>
                    </div>
                </fieldset> 
                
                <div class="form-row checkbox-row">
                    <div class="input-group full-width checkbox-group">
                        <input type="checkbox" id="terms" name="terms" <?php echo $v_terms ? 'checked' : ''; ?> required>
                        <label for="terms" class="inline-label">I agree to the Terms and Conditions and confirm my order details.</label>
                    </div>
                </div>

                <div class="button-group">
                    <input type="submit" value="Submit Order">
                    <input type="reset" value="Clear Form">
                </div>
                
            </form>
        </div>
    </main>

<?php include 'footer.inc'; ?>

<a href="#" class="back-to-top">▲</a>

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
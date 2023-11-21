<?php
session_start();
$isUserLoggedIn = isset($_SESSION['Username']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["payment-method"])) {
        $selectedPaymentMethod = $_POST["payment-method"];

        if ($selectedPaymentMethod == "debit-card") {
            header("Location: Debit.html");
            exit;
        }
    }
}

$itemCount = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $itemCount += $item['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="Logo.png" type="image/icon type">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cart.css">
    <script src="https://kit.fontawesome.com/f3400a1f8d.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Cart</title>
</head>

<body>
    <div class="banner">
        <div class="navbar">
            <div class="title">
                <a href="HomePage.php">Home</a>
                <a href="Product.php">Products</a>
                <a href="myprofile.php">Profile</a>
                <a href="aboutus.php">About Us</a>
                <a href="cart.php">
                    <?php if ($itemCount > 0) { ?>
                        <span class="cart-badge">
                            <?php echo $itemCount; ?>
                        </span>
                    <?php } ?>
                    <i class="fas fa-shopping-cart"></i>
                </a></li>
            </div>
        </div>

        <div class="container">
            <h1>Your Cart</h1>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (empty($_SESSION["cart"])) {
                        echo "<tr><td colspan='5'>Your cart is empty.</td></tr>";
                    } else {
                        $total_price = 0;
                        foreach ($_SESSION["cart"] as $key => $value) {
                            $product_name = $value["name"];
                            $product_qty = $value["quantity"];
                            $product_price = $value["price"];
                            $product_total = $product_qty * $product_price;
                            $total_price += $product_total;
                            ?>
                            <tr>
                                <td>
                                    <?php echo $product_name; ?>
                                </td>
                                <td>
                                    <?php echo $product_qty; ?>
                                </td>
                                <td>RM
                                    <?php echo $product_price; ?>
                                </td>
                                <td>RM
                                    <?php echo $product_total; ?>
                                </td>
                                <td>
                                    <form action="update_cart.php" method="post">
                                        <input type="hidden" name="product_key" value="<?php echo $key; ?>" />
                                        <button type="submit" name="remove_item">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="3">Total</td>
                            <td colspan="2">RM
                                <?php echo $total_price; ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="checkout">
                <h2>Checkout</h2>

                <?php if (!isset($_SESSION['Username'])) { ?>
                    <h2>Please Login or Register First</h2>
                <?php } else { ?>
                    <form action="checkout.php" method="post" id="checkout-form">
                        <label for="address-select">Choose Address</label>
                        <select id="address-select" name="selected-address">
                            <!-- JavaScript will populate this dropdown -->
                        </select>
                        <label for="fullname">Full Name</label>
                        <input type="text" id="fullname" name="fullname" required>
                        <label for="payment-method">Payment Method</label>
                        <select id="payment-method" name="payment-method" required>
                            <option value="cash">Cash On Delivery</option>
                            <option value="debit-card">Debit Card</option>
                            <option value="online-banking">Online Banking</option>
                        </select>

                        <input type="hidden" id="selected-payment-method" name="selected-payment-method">

                        <?php foreach ($_SESSION["cart"] as $key => $value) { ?>
                            <input type="hidden" name="cart[<?php echo $key; ?>][name]" value="<?php echo $value['name']; ?>">
                            <input type="hidden" name="cart[<?php echo $key; ?>][quantity]"
                                value="<?php echo $value['quantity']; ?>">
                            <input type="hidden" name="cart[<?php echo $key; ?>][price]" value="<?php echo $value['price']; ?>">
                        <?php } ?>

                        <button type="submit" id="checkout-btn">Checkout</button>

                        <script>
                            document.getElementById('checkout-form').addEventListener('submit', function () {
                                document.getElementById('selected-payment-method').value = document.getElementById('payment-method').value;
                            });
                        </script>

                        <script>
                            const addressSelect = document.getElementById('address-select');
                            const addressInput = document.getElementById('address');

                            fetch('autofill.php')
                                .then(response => response.json())
                                .then(addresses => {
                                    addresses.forEach(address => {
                                        const option = document.createElement('option');
                                        option.value = address.Address;
                                        option.textContent = address.Address;
                                        addressSelect.appendChild(option);
                                    });

                                    // Add an event listener to the dropdown to autofill the address field
                                    addressSelect.addEventListener('change', function () {
                                        const selectedAddress = this.value;
                                        addressInput.value = selectedAddress;
                                    });
                                });
                        </script>
                    </form>
                <?php } ?>
            </div>
        </div>
        <div class="footer">
            <footer class="footer-distributed">
                <div class="footer-left">
                    <h3>KerepekAbang<span>Boyot</span></h3>

                    <p class="footer-links">
                        <a href="HomePage.php">Home</a>
                        |
                        <a href="aboutus.php">About Us</a>
                        |
                        <a href="<?php echo $isUserLoggedIn ? 'feedback.html' : 'javascript:showLoginPopup()' ?>">Feedback</a>
                    </p>

                    <p class="footer-company-name">Copyright © 2023 <strong>KerepekAbangBoyot</strong> All rights
                        reserved</p>
                </div>

                <div class="footer-center">
                    <div>
                        <i class="fa fa-map-marker"></i>
                        <p><span>Sepang</span>
                            Selangor</p>
                    </div>

                    <div>
                        <i class="fa fa-phone"></i>
                        <p>+60166069321</p>
                    </div>
                    <div>
                        <i class="fa fa-envelope"></i>
                        <p>KerepekAbangBoyot@gmail.com</p>
                    </div>
                </div>
                <div class="footer-right">
                    <p class="footer-company-about">
                        <span>About the Shop</span>
                        <strong>KerepekAbangBoyot</strong> menjalankan operasi memproses, membekal dan menjual
                        makanan-makanan tradisional kepada orang ramai.
                    </p>
                    <div class="footer-icons">
                        <a href="https://www.instagram.com/kepekkita/" target="_blank"><i
                                class="fa fa-instagram"></i></a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</body>
<script>
    function showLoginPopup() {
        alert("Please Login or Register First");
    }
</script>
</html>
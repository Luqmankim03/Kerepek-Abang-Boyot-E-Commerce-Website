<!DOCTYPE html>
<html>

<head>
    <link rel="icon" href="Logo.png" type="image/icon type">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="checkout.css">
    <title>Receipt</title>
</head>

<body>

    <?php
    session_start();

    $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';
    $address = isset($_POST['selected-address']) ? $_POST['selected-address'] : '';
    $payment_method = isset($_POST['payment-method']) ? $_POST['payment-method'] : '';
    $cart = isset($_POST['cart']) ? $_POST['cart'] : array();

    unset($_SESSION['cart']);
    ?>

    <h1>Thank you for your order,
        <?php echo $fullname; ?>!
    </h1>
    <p>Your order will be shipped to:<br><strong>
            <?php echo $address; ?>
        </strong></p>
    <p>You have chosen to pay by <strong>
            <?php echo $payment_method; ?>
        </strong>.</p>

    <h2>Order Summary</h2>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price(RM)</th>
                <th>Total(RM)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart as $item): ?>
                <tr>
                    <td>
                        <?php echo $item['name']; ?>
                    </td>
                    <td>
                        <?php echo $item['quantity']; ?>
                    </td>
                    <td>
                        <?php echo $item['price']; ?>
                    </td>
                    <td>
                        <?php echo $item['quantity'] * $item['price']; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td>
                    <?php
                    $total = array_reduce($cart, function ($accumulator, $item) {
                        return $accumulator + ($item['quantity'] * $item['price']);
                    }, 0);
                    echo $total;
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
    <button onclick="window.print()">Print</button>

    <script>
        setTimeout(function () {
            window.location.href = "HomePage.php";
        }, 20000);
    </script>

</body>

<?php
$host = "localhost:3308";
$user = "root";
$password = "";
$db = "kerepek";

$conn = mysqli_connect($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$total_price = 0;

$stmt = $conn->prepare("INSERT INTO `order` (Name, Address, paymentMethod, Product, Quantity, Total, Status, Timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE())");
$stmt->bind_param('ssssids', $fullname, $address, $payment_method, $product_name, $quantity, $total, $status);

$status = '-';
$timestamp = date('Y-m', strtotime('now'));

foreach ($cart as $item) {
    $product_name = $item['name'];
    $quantity = $item['quantity'];
    $total = $item['quantity'] * $item['price'];

    if ($stmt->execute()) {
        $total_price += $total;
    } else {
        echo "Error inserting order data: " . $conn->error;
    }
}

$stmt->close();
$conn->close();
?>

</html>

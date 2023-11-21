<?php
session_start();

$host = "localhost:3308";
$user = "root";
$password = "";
$db = "kerepek";

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['remove_item'])) {
        $product_key = $_POST['product_key'];

        if (isset($_SESSION['cart'][$product_key])) {
            $quantityToRemove = $_SESSION['cart'][$product_key]['quantity'];

            $sql = "UPDATE products SET productQuantity = productQuantity + ? WHERE productID = ?";
            $stmt = $conn->prepare($sql);

            $stmt->bind_param("ii", $quantityToRemove, $product_key);
            $stmt->execute();

            unset($_SESSION['cart'][$product_key]);

            header('Location: cart.php');
            exit;
        }
    }
}

mysqli_close($conn);
?>

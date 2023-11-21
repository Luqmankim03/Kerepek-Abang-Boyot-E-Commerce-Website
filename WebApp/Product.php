<?php
session_start();

$isUserLoggedIn = isset($_SESSION['Username']);

$host = "localhost:3308";
$user = "root";
$password = "";
$db = "kerepek";

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$product_id = isset($_POST['productID']) ? $_POST['productID'] : '';
$product_name = isset($_POST['productName']) ? $_POST['productName'] : '';
$product_quantity = isset($_POST['productQuantity']) ? $_POST['productQuantity'] : '';
$product_image = isset($_FILES['productImage']['name']) ? $_FILES['productImage']['name'] : '';

if (!empty($product_image)) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["productImage"]["name"]);
    move_uploaded_file($_FILES["productImage"]["tmp_name"], $target_file);
}

if (!empty($product_id) && !empty($product_name) && !empty($product_quantity)) {
    $sql = "UPDATE products SET productName='$product_name', productQuantity=$product_quantity";
    if (!empty($product_image)) {
        $sql .= ", productImage='$product_image'";
    }
    $sql .= " WHERE productID=$product_id";

    if (mysqli_query($conn, $sql)) {
        echo "Product updated successfully";
    } else {
        echo "Error updating product: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="Logo.png" type="image/icon type">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="product.css">
    <script src="https://kit.fontawesome.com/f3400a1f8d.js" crossorigin="anonymous"></script>
    <title>Products</title>
</head>

<body>
    <?php

    if (isset($_GET['add-to-cart'])) {
        $product_id = $_GET['add-to-cart'];
        $product_name = $_GET['name'];
        $product_price = $_GET['price'];
        $product_quantity = $_GET['quantity'];

        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity']++;
        } else {
            $_SESSION['cart'][$product_id] = array(
                'name' => $product_name,
                'price' => $product_price,
                'quantity' => 1
            );
        }

        $new_quantity = intval($product_quantity) - 1;
        $sql = "UPDATE products SET productQuantity=$new_quantity WHERE productID=$product_id";
        mysqli_query($conn, $sql);

        if (mysqli_query($conn, $sql)) {
            // Product has been added to cart
        } else {
            echo "Error updating product quantity: " . mysqli_error($conn);
        }

        mysqli_close($conn);
    }
    ?>
    <?php
    $itemCount = 0;

    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $itemCount += $item['quantity'];
        }
    }
    ?>
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
        <div class="title1">
            <h3>ALL PRODUCTS</h3>
            <form class="search" action="Product.php" method="GET">
                <input type="text" placeholder="Search..." name="search">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>
        <div class="container">
            <?php
            $conn = mysqli_connect("localhost:3308", "root", "", "kerepek");

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            if (isset($_GET['search'])) {
                $search = $_GET['search'];
                $sql = "SELECT * FROM products WHERE productName LIKE '%$search%'";

                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) == 0) {
                    echo "<script>alert('Your search did not match any products.');</script>";
                    echo "<script>window.location.href='Product.php';</script>";
                    exit;
                }
            } else {
                $sql = "SELECT * FROM products";
            }

            $result = mysqli_query($conn, $sql);

            if (!$result) {
                die("Error: " . mysqli_error($conn));
            }

            echo '<div class="container">';

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='card'>";
                echo "<div class='product-image'>";
                echo "<img src='uploads/" . $row['productImage'] . "' alt=''>";
                echo "</div>";
                echo "<div class='Product-info'>";
                echo "<h1>" . $row['productName'] . "</h1>";
                echo "<h1>RM" . $row['productPrice'] . "</h1>";
                echo "</div>";
                echo "<div class='button'>";
                
                if ($isUserLoggedIn) {
                    echo "<a href='product.php?add-to-cart=" . $row['productID'] . "&name=" . urlencode($row['productName']) . "&price=" . $row['productPrice'] . "&quantity=" . $row['productQuantity'] . "'><button onclick='alert(\"" . $row['productName'] . " has been added to cart\")' type='button'>Add to Cart</button></a>";
                } else {
                    echo "<button onclick='showLoginPopup()' type='button'>Add to Cart</button>";
                }
            
                echo "</div>";
                echo "</div>";
            }

            echo '</div>';

            mysqli_close($conn);
            ?>
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
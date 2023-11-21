<?php 
session_start();
$isUserLoggedIn = isset($_SESSION['Username']);
?>


<html>

<head>
    <link rel="icon" href="Logo.png" type="image/icon type">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="homepage.css">
    <script src="https://kit.fontawesome.com/f3400a1f8d.js" crossorigin="anonymous"></script>
    <title>Home</title>
</head>

<body>
    <div class="border">
        <h3><a href="https://youtu.be/t_Z-acI1Byk" target="_blank">User Manual</a></h3>
        <?php
        if (isset($_SESSION['Username'])) {
            echo '<a href="logout.php" id="logout-link"><i class="fa-sharp fa-solid fa-right-from-bracket"></i>Logout</a>';
            echo '<a class="welcome-text">Welcome, ' . $_SESSION['Username'] . '</a>';
        } else {
            echo '<a href="login.php"><i class="fa-solid fa-user"></i>Login</a>';
        }
        ?>
    </div>

    <?php

    $itemCount = 0;

    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $itemCount += $item['quantity'];
        }
    }
    ?>

    <script>
        var logoutLink = document.getElementById('logout-link');
        logoutLink.addEventListener('click', function (event) {
            event.preventDefault();
            var confirmation = confirm("Are you sure you want to logout?");
            if (confirmation) {
                window.location.href = this.href;
            }
        });
    </script>
</body>

<body>
    <div class="banner">
        <div class="navbar">
            <img src="Logo.png" class="logo">
            <ul>
                <li><a href="HomePage.php">Home</a></li>
                <li><a href="Product.php">Products</a></li>
                <li><a href="myprofile.php">Profile</a></li>
                <li><a href="aboutus.php">About Us</a></li>
                <li><a href="cart.php">
                        <?php if ($itemCount > 0) { ?>
                            <span class="cart-badge">
                                <?php echo $itemCount; ?>
                            </span>
                        <?php } ?>
                        <i class="fas fa-shopping-cart"></i>
                    </a></li>
            </ul>
        </div>
        <div class="content">
            <h1>KEREPEK ABANG BOYOT</h1>
            <p>Sekali Ngap Pasti Nak Lagi</p>
        </div>
    </div>
    <div class="banner1">
        <div class="container">
            <div class="wrapper">
                <img src="slide1.png">
                <img src="slide2.png">
                <img src="slide3.png">
                <img src="slide4.png">
            </div>
        </div>
        <h2>PROMOTIONS <i class="fa-solid fa-arrow-right"></i></h2>
    </div>
    <div class="featured-products">
        <h2>Featured Products</h2>
        <a href="Product.php">
            <div class="product-box">
                <img src="Product1.jpg" alt="Product 1">
                <h3>Kerepek</h3>
            </div>
        </a>
        <a href="Product.php">
            <div class="product-box">
                <img src="Product7.jpg" alt="Product 2">
                <h3>Kuih-Muih</h3>
            </div>
        </a>
        <a href="Product.php">
            <div class="product-box">
                <img src="Product8.jpg" alt="Product 3">
                <h3>Popcorn</h3>
            </div>
        </a>
    </div>
    <div class="feedbacks">
        <div class="inner">
            <h1>Feedback</h1>
            <div class="border1"></div>

            <?php
            $host = "localhost:3308";
            $username = "root";
            $password = "";
            $database = "kerepek";

            $conn = mysqli_connect($host, $username, $password, $database);

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql = "SELECT * FROM feedback ORDER BY ID DESC LIMIT 3";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $name = $row['Name'];
                    $email = $row['Email'];
                    $message = $row['Message'];
                    $image = $row['Image'];

                    echo '<div class="customer">';
                    echo '<div class="feedback">';
                    echo "<img src='$image' alt='User Image'>";
                    echo "<div class='name'>$name</div>";
                    echo "<p>$message</p>";
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "No feedback available.";
            }

            mysqli_close($conn);
            ?>
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

            <p class="footer-company-name">Copyright © 2023 <strong>KerepekAbangBoyot</strong> All rights reserved</p>
        </div>

        <div class="footer-center">
            <div>
                <i class="fa fa-map-marker"></i>
                <p><span>Sepang</span> Selangor</p>
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
                <a href="https://www.instagram.com/kepekkita/" target="_blank"><i class="fa fa-instagram"></i></a>
            </div>
        </div>
    </footer>
</div>
</body>
<script>
    function showLoginPopup() {
        alert("Please Login or Register First");
    }
</script>
</html>
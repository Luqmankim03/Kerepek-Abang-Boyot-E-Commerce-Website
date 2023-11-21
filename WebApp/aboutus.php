<?php
session_start();

$itemCount = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $itemCount += $item['quantity'];
    }
}
?>

<html>

<head>
    <title>About Kerepek Abang Boyot</title>
    <link rel="icon" href="Logo.png" type="image/icon type">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/f3400a1f8d.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="aboutus.css">
</head>

<body>
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
</body>

<body>
    <div class="wrapper">
        <div class="row">
            <div class="image-section">
                <img src="Kerepek.jpg">
            </div>
            <div class="content">
                <h1>About Us</h1>
                <h2>Our Shop</h2>
                <p>Perniagaan kami menyediakan pelbagai jenis kerepek dan kuih tradisional melayu yang dicari-cari oleh
                    orang ramai. Gambar di sebelah ini merupakan diantara produk yang kami sediakan kepada pelanggan
                    kami. Diantaranya ialah Popia Simpul, Kerepek Bawang, Kerepek Ubi, Kerepek Pisang dan macam-macam
                    lagi. Anda semua boleh tengok banyak lagi gambar dan promosi-promosi yang kami sediakan di <a
                        href="Product.php">Product Page</a> Kami. Yang kami boleh janjikan adalah "SEKALI NGAP, PASTI
                    NAK LAGI".</p>
            </div>
        </div>
    </div>
</body>

</html>
<?php

$host = "localhost:3308";
$user = "root";
$password = "";
$db = "kerepek";

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['update'])) {
    $product_id = $_POST['productID'];
    $product_name = $_POST['productName'];
    $product_price = $_POST['productPrice'];
    $product_quantity = $_POST['productQuantity'];

    if (!empty($_FILES['productImage']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["productImage"]["name"]);
        move_uploaded_file($_FILES["productImage"]["tmp_name"], $target_file);
        $product_image = basename($_FILES["productImage"]["name"]);
        $sql = "UPDATE products SET productName='$product_name', productPrice=$product_price, productQuantity=$product_quantity, productImage='$product_image' WHERE productID=$product_id";
    } else {
        $sql = "UPDATE products SET productName='$product_name', productPrice=$product_price, productQuantity=$product_quantity WHERE productID=$product_id";
    }

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Product updated successfully.')</script>";
    } else {
        echo "Error updating product: " . mysqli_error($conn);
    }
}

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE productID=$product_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $product_name = $row['productName'];
    $product_price = $row['productPrice'];
    $product_quantity = $row['productQuantity'];
    $product_image = $row['productImage'];
} else {
    header("Location: admin.php");
}

?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="edit.css">
    <title>Edit Product</title>
    <style>
        label {
            display: block;
        }
    </style>
</head>

<body>
    <h1>Edit Product</h1>
    <form method="post" action="editProduct.php" enctype="multipart/form-data">
        <input type="hidden" name="productID" value="<?php echo $product_id; ?>">
        <label for="productName">Product Name:</label>
        <input type="text" name="productName" value="<?php echo $product_name; ?>"><br><br>
        <label for="productPrice">Product Price:</label>
        <input type="text" name="productPrice" value="<?php echo $product_price; ?>"><br><br>
        <label for="productQuantity">Product Quantity:</label>
        <input type="text" name="productQuantity" value="<?php echo $product_quantity; ?>"><br><br>
        <label for="productImage">Product Image:</label>
        <img src="uploads/<?php echo $product_image; ?>" width="195px" height="135px"><br>
        <input type="file" name="productImage"><br><br>
        <input type="submit" name="update" value="Update Product">
    </form>
</body>
<script>
    var submitBtn = document.querySelector('input[type="submit"][name="update"]');

    submitBtn.addEventListener('click', function (event) {
        alert('Item has been updated!');
    });
</script>
</html>
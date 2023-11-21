<?php
session_start();

if (isset($_SESSION['admin'])) {
    $admin_name = $_SESSION['admin'];
} else {
    $admin_name = 'admin';
}

$host = "localhost:3308";
$user = "root";
$password = "";
$db = "kerepek";

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['add_product'])) {
    $product_name = $_POST['productName'];
    $product_price = $_POST['productPrice'];
    $product_quantity = $_POST['productQuantity'];
    $product_image = $_FILES['productImage']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["productImage"]["name"]);
    move_uploaded_file($_FILES["productImage"]["tmp_name"], $target_file);

    $sql = "INSERT INTO products (productName, productPrice, productQuantity, productImage) VALUES ('$product_name', $product_price, $product_quantity, '$product_image')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Product added successfully.')</script>";
        header("Location: admin.php");
    } else {
        echo "Error adding product: " . mysqli_error($conn);
    }
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
        $product_image = $_POST['productImage'];
        $sql = "UPDATE products SET productName='$product_name', productPrice=$product_price, productQuantity=$product_quantity, productImage='$product_image' WHERE productID=$product_id";
    } else {
        $sql = "UPDATE products SET productName='$product_name', productPrice=$product_price, productQuantity=$product_quantity WHERE productID=$product_id";
    }

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Product updated successfully.')</script>";
        header("Location: admin.php");
    } else {
        echo "Error updating product: " . mysqli_error($conn);
    }
}

if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];
    $sql = "DELETE FROM products WHERE productID=$product_id";
    mysqli_query($conn, $sql);
    header("Location: admin.php");
}

$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
    <script src="https://kit.fontawesome.com/f3400a1f8d.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="navbar">
        <div class="admin">
            <a href="userList.php"><i class="fa-solid fa-list-ul"></i></a>
            <b>Admin Page</b>
        </div>
        <div class="welcome">
            <div class="logout"><a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a></div>
            <a href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a>
        </div>
    </div>
    <div class="container">
        <h1>Products</h1>
        <form method="post" action="admin.php" enctype="multipart/form-data">
            <label for="productName">Product Name:</label>
            <input type="text" name="productName" required><br><br>
            <label for="productPrice">Product Price:</label>
            <input type="text" name="productPrice" required><br><br>
            <label for="productQuantity">Product Quantity:</label>
            <input type="text" name="productQuantity" required><br><br>
            <label for="productImage">Product Image:</label>
            <input type="file" name="productImage" required><br><br>
            <input type="submit" name="add_product" value="Add Product">
        </form>
        <h2>Edit Products</h2>
        <table>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Product Price</th>
                <th>Product Quantity</th>
                <th>Product Image</th>
                <th>Edit</th>
                <th>Delete
                </th>
            </tr>
            <?php while ($row = mysqli_fetch_array($result)) { ?>
                <tr>
                    <td>
                        <?php echo $row['productID']; ?>
                    </td>
                    <td>
                        <?php echo $row['productName']; ?>
                    </td>
                    <td>
                        <?php echo $row['productPrice']; ?>
                    </td>
                    <td>
                        <?php echo $row['productQuantity']; ?>
                    </td>
                    <td>
                        <img src="uploads/<?php echo $row['productImage']; ?>" width="195px" height="135px">
                    </td>
                    <td><a href="editProduct.php?id=<?php echo $row['productID']; ?>">Edit</a></td>
                    <td><a href="admin.php?delete=<?php echo $row['productID']; ?>"
                            onclick="return confirm('Are you sure you want to delete this product?')">Delete</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>

</html>
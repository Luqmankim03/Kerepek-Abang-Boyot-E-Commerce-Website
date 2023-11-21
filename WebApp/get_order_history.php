<?php
$host = "localhost:3308";
$user = "root";
$password = "";
$db = "kerepek";

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$username = $_SESSION['Username']; // Assuming this session variable is correctly set

$query = "SELECT Product, Quantity, Total, paymentMethod, Address, Status FROM `order` WHERE Name = '$username'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['Product'] . "</td>";
        echo "<td>" . $row['Quantity'] . "</td>";
        echo "<td>" . $row['Total'] . "</td>";
        echo "<td>" . $row['paymentMethod'] . "</td>";
        echo "<td>" . $row['Address'] . "</td>";
        echo "<td>" . $row['Status'] . "</td>";
        echo "</tr>";
    }
} else {
    echo "No orders found.";
}

mysqli_close($conn);
?>

<?php
session_start();

if (!isset($_SESSION['Username'])) {
    echo "User session not found. Please log in.";
    exit;
}

$username = $_SESSION['Username'];

if (isset($_POST['address'])) {
    $newAddress = $_POST['address'];

    $host = "localhost:3308";
    $user = "root";
    $password = "";
    $db = "kerepek";

    $conn = mysqli_connect($host, $user, $password, $db);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $getUserQuery = "SELECT FullName FROM user WHERE User = '$username'";
    $result = mysqli_query($conn, $getUserQuery);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $fullName = $row['FullName'];

        $insertQuery = "INSERT INTO addresses (User, Address, FullName) VALUES ('$username', '$newAddress', '$fullName')";

        if (mysqli_query($conn, $insertQuery)) {
            echo "Address added successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "User not found in the database.";
    }

    mysqli_close($conn);
} else {
    echo "Invalid request. Please provide an address.";
}
?>

<?php
session_start();

if (!isset($_SESSION['Username'])) {
    echo "User session not found. Please log in.";
    exit;
}

$username = $_SESSION['Username'];

$host = "localhost:3308";
$user = "root";
$password = "";
$db = "kerepek";

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve user's addresses
$getAddressesQuery = "SELECT ID, Address FROM addresses WHERE User = '$username'";
$result = mysqli_query($conn, $getAddressesQuery);

if ($result && mysqli_num_rows($result) > 0) {
    $addresses = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $addresses[] = $row;
    }

    echo json_encode($addresses);
} else {
    echo "User has no addresses in the database.";
}

mysqli_close($conn);
?>

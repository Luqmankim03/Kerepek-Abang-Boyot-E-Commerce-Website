<?php
session_start();

if (!isset($_SESSION['Username'])) {
    echo json_encode(["error" => "User session not found. Please log in."]);
    exit;
}

$username = $_SESSION['Username'];

$host = "localhost:3308";
$user = "root";
$password = "";
$db = "kerepek";

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    echo json_encode(["error" => "Connection failed: " . mysqli_connect_error()]);
    exit;
}

$getUserIDQuery = "SELECT ID FROM user WHERE User = '$username'";
$result = mysqli_query($conn, $getUserIDQuery);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $userId = $row['ID'];

    $getAddressesQuery = "SELECT Address FROM addresses WHERE User = '$username'";
    $addressesResult = mysqli_query($conn, $getAddressesQuery);
    $addresses = [];

    while ($row = mysqli_fetch_assoc($addressesResult)) {
        $addresses[] = $row['Address'];
    }

    echo json_encode($addresses);
} else {
    echo json_encode(["error" => "User not found in the database."]);
}

mysqli_close($conn);
?>

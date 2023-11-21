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

$username = $_SESSION['Username'];
$address = $_POST["address"];

$sql = "DELETE FROM addresses WHERE User = '$username' AND Address = '$address'";

if ($conn->query($sql) === TRUE) {
    echo "Address deleted successfully.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

<?php

if (!isset($_SESSION['Username'])) {
    header("Location: login.php");
    exit;
}

$host = "localhost:3308";
$username = "root";
$password = "";
$database = "kerepek";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_destination = "uploads/" . $image;

    move_uploaded_file($image_tmp, $image_destination);

    $sql = "INSERT INTO feedback (Name, Email, Message, Image) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $message, $image_destination);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);

    // Display the "Thank you" page for 4 seconds
    echo '<script>setTimeout(function() { window.location.href = "feedback.html"; }, 1000);</script>';
}

?>
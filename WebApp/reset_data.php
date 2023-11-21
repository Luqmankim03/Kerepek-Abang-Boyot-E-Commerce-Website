<?php
$host = "localhost:3308";
$username = "root";
$password = "";
$database = "kerepek";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['reset'])) {
    // Reset data in the database
    $resetQuery = "TRUNCATE TABLE `order`"; // Replace with the appropriate query
    $resetResult = mysqli_query($conn, $resetQuery);

    if ($resetResult) {
        $message = "Data reset successfully.";
    } else {
        $message = "Error resetting data: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Reset Data</title>
</head>

<body>
    <script>
        // Display a popup message
        alert("<?php echo $message; ?>");

        // Redirect to the dashboard.php page
        window.location.href = "dashboard.php";
    </script>
</body>

</html>

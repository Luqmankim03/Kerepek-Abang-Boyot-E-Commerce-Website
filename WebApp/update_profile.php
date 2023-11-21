<?php
session_start();

if (!isset($_SESSION['Username'])) {
    header('Location: login.php');
    exit;
}

$host = "localhost:3308";
$user = "root";
$password = "";
$db = "kerepek";

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$username = $_SESSION['Username'];

if (isset($_POST['update_profile'])) {
    $newFullName = $_POST['new_full_name'];
    $newPhoneNumber = $_POST['new_phone_number'];

    $updateMessage = "No changes made to your profile.";
    if (!empty($newFullName) || !empty($newPhoneNumber)) {
        $updateSql = "UPDATE user SET ";

        if (!empty($newFullName)) {
            $updateSql .= "FullName='$newFullName'";
        }
        if (!empty($newPhoneNumber)) {
            if (!empty($newFullName)) {
                $updateSql .= ", ";
            }
            $updateSql .= "PhoneNo='$newPhoneNumber'";
        }

        $updateSql .= " WHERE User='$username'";

        if ($conn->query($updateSql) === TRUE) {
            $updateMessage = "Your profile has been updated.";
        } else {
            $updateMessage = "Error updating user information: " . $conn->error;
        }
    }
    echo "<script>alert('$updateMessage'); window.location.href = 'myprofile.php';</script>";
}

$conn->close();
?>

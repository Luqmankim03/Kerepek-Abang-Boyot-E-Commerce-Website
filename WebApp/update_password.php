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

if (isset($_POST['update_password'])) {
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    $user_sql = "SELECT Pass FROM user WHERE User = ? LIMIT 1";
    $user_stmt = mysqli_prepare($conn, $user_sql);
    mysqli_stmt_bind_param($user_stmt, "s", $username);
    mysqli_stmt_execute($user_stmt);
    $user_result = mysqli_stmt_get_result($user_stmt);

    if (mysqli_num_rows($user_result) == 1) {
        $row = mysqli_fetch_assoc($user_result);
        $hashedPassword = $row['Pass'];

        if (password_verify($oldPassword, $hashedPassword)) {
            if ($newPassword === $confirmPassword) {
                // Passwords match, update the password in the database
                $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                $update_sql = "UPDATE user SET Pass = ? WHERE User = ?";
                $update_stmt = mysqli_prepare($conn, $update_sql);
                mysqli_stmt_bind_param($update_stmt, "ss", $newHashedPassword, $username);
                mysqli_stmt_execute($update_stmt);

                $_SESSION['password_change_message'] = "Password changed successfully.";
            } else {
                $_SESSION['password_change_message'] = "New passwords do not match.";
            }
        } else {
            $_SESSION['password_change_message'] = "Incorrect old password.";
        }
    }

    header("Location: myprofile.php");
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="icon" href="Logo.png" type="image/icon type">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="newpassword.css">
    <title>Reset Password</title>
</head>

<body>
    <form method="POST" action="newpassword.php">
        <h1>New Password</h1>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username">
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Password must be at least 8 characters long and contain at least one digit, one lowercase letter, and one uppercase letter">
        <label for="confirm-password">Confirm New Password:</label>
        <input type="password" id="confirm-password" name="confirm-password">
        <button type="submit" name="submit">Submit</button>
    </form>

    <?php

    $host = "localhost:3308";
    $user = "root";
    $password = "";
    $db = "kerepek";

    $conn = mysqli_connect($host, $user, $password, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_POST['submit'])) {

        $username = $_POST['username'];
        $new_password = $_POST['password'];
        $confirm_password = $_POST['confirm-password'];

        if ($new_password != $confirm_password) {
            echo "<script>alert('New passwords do not match. Please try again.');</script>";
        } else {

            $stmt = $conn->prepare("SELECT * FROM user WHERE User = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $stmt = $conn->prepare("UPDATE user SET Pass = ? WHERE User = ?");
                $stmt->bind_param("ss", $new_password, $username);

                if ($stmt->execute() === TRUE) {
                    echo "<script>
                            if(window.confirm('Password has been changed. Click OK to go to login page.')) {
                                window.location.href = 'login.php';
                            }
                          </script>";
                    exit();
                } else {
                    echo "Error updating password: " . $conn->error;
                }
            } else {
                echo "<script>alert('Username is incorrect. Please try again.');</script>";
            }
        }
    }

    $conn->close();
    ?>

</body>
</html>
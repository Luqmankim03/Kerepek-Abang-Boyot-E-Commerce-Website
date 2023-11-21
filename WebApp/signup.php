<?php
session_start();

$host = "localhost:3308";
$user = "root";
$password = "";
$db = "kerepek";

$conn = mysqli_connect($host, $user, $password, $db);

if (isset($_POST['Username'])) {
  $name = $_POST['FullName'];
  $username = $_POST['Username'];
  $phone = $_POST['Phone'];
  $password = $_POST['Password'];

  $check_username_query = "SELECT * FROM user WHERE User = ?";
  $check_username_stmt = mysqli_prepare($conn, $check_username_query);
  mysqli_stmt_bind_param($check_username_stmt, "s", $username);
  mysqli_stmt_execute($check_username_stmt);
  $check_username_result = mysqli_stmt_get_result($check_username_stmt);

  if (mysqli_num_rows($check_username_result) == 0) {
    $check_phone_query = "SELECT * FROM user WHERE PhoneNo = ?";
    $check_phone_stmt = mysqli_prepare($conn, $check_phone_query);
    mysqli_stmt_bind_param($check_phone_stmt, "s", $phone);
    mysqli_stmt_execute($check_phone_stmt);
    $check_phone_result = mysqli_stmt_get_result($check_phone_stmt);

    if (mysqli_num_rows($check_phone_result) == 0) {
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      $sql = "INSERT INTO user (FullName, User, PhoneNo, Pass) VALUES (?, ?, ?, ?)";
      $stmt = mysqli_prepare($conn, $sql);

      if (!$stmt) {
        die("Error creating statement: " . mysqli_error($conn));
      }

      mysqli_stmt_bind_param($stmt, "ssss", $name, $username, $phone, $hashedPassword);
      mysqli_stmt_execute($stmt);

      if (mysqli_affected_rows($conn) == 1) {
        $_SESSION['Username'] = $username;
        echo "<script>alert('Sign Up Successful!'); window.location.href='login.php';</script>";
        exit();
      } else {
        $error = "Failed to create an account";
      }
    } else {
      echo "<script>alert('Phone Number Already Exist.');</script>";
    }
  } else {
    echo "<script>alert('Duplicate User Found. Please Choose Another Username.');</script>";
  }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <link rel="icon" href="Logo.png" type="image/icon type">
  <meta charset="utf-8">
  <title>Sign Up Page</title>
  <link rel="stylesheet" href="signup.css">
</head>

<body>
  <div class="center">
    <h1>Sign Up</h1>
    <form method="post">
      <div class="txt_field">
        <input type="text" name="FullName" required>
        <span></span>
        <label>Full Name</label>
      </div>
      <div class="txt_field">
        <input type="text" name="Username" required>
        <span></span>
        <label>Username</label>
      </div>
      <div class="txt_field">
        <input type="tel" name="Phone" required pattern="[0-9]{3}-[0-9]{7}" title="Please enter a phone number in the format XXX-XXXXXXX">
        <span></span>
        <label>Phone Number</label>
      </div>
      <div class="txt_field">
        <input type="password" name="Password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+]).{8,}" title="Password must contain at least one number, one uppercase and lowercase letter, one symbol, and be at least 8 characters long">
        <span></span>
        <label>Password</label>
      </div>
      <input type="submit" value="Sign Up">
      <div class="login_link">
        Already have an account? <a href="login.php">Login</a>
      </div>
    </form>
  </div>
</body>
</html>

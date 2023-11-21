<?php
session_start();

$host = "localhost:3308";
$user = "root";
$password = "";
$db = "kerepek";

$conn = mysqli_connect($host, $user, $password, $db);

if (isset($_POST['phone'])) {

    $phone = $_POST['phone'];

    $sql = "SELECT * FROM user WHERE PhoneNo=? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        die("Error preparing statement: " . mysqli_error($conn));
    }
    
    mysqli_stmt_bind_param($stmt, "s", $phone);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) == 1) {
    
        $_SESSION['success'] = "The OTP Code has been sent";
        header("Location: otp.html");
        exit();
    } else {
        echo "<script>alert('Invalid phone number.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <link rel="icon" href="Logo.png" type="image/icon type">
  <meta charset="utf-8">
  <title>Forgot Password</title>
  <link rel="stylesheet" href="forgotpassword.css">
  <style>
    .error {
      color: red;
      margin-top: 5px;
    }
  </style>
</head>

<body>
  <div class="center">
    <h1>Forgot Password</h1>
    <form action="#" method="post">
      <div class="txt_field">
        <input type="text" name="phone" required>
        <span></span>
        <label>Enter Your Phone Number</label>
      </div>
      <input type="submit" value="Submit">
      <?php if (isset($error)) { ?>
        <div class="error"><?php echo $error ?></div>
      <?php } ?>
    </form>
  </div>
</body>
</html>


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

if (isset($_POST['Username'])) {
  $username = $_POST['Username'];
  $enteredPassword = $_POST['Password'];

  // Check if it's an admin login
  $admin_sql = "SELECT adminID, adminName, adminPass, adminEmail FROM admin WHERE adminName=? LIMIT 1";
  $admin_stmt = mysqli_prepare($conn, $admin_sql);

  if (!$admin_stmt) {
    die("Admin statement preparation error: " . mysqli_error($conn));
  }

  mysqli_stmt_bind_param($admin_stmt, "s", $username);
  mysqli_stmt_execute($admin_stmt);
  $admin_result = mysqli_stmt_get_result($admin_stmt);

  if (mysqli_num_rows($admin_result) == 1) {
    $row = mysqli_fetch_assoc($admin_result);
    $adminPassword = $row['adminPass'];

    if ($enteredPassword == $adminPassword) {
      // Admin login successful
      $_SESSION['AdminID'] = $row['adminID'];
      $_SESSION['AdminName'] = $row['adminName'];
      $_SESSION['AdminEmail'] = $row['adminEmail'];

      // Redirect to admin page directly
      header("Location: admin.php");
      exit();
    }
  }

  // Check if it's a regular user login
  $user_sql = "SELECT ID, User, Pass FROM user WHERE User=? LIMIT 1";
  $user_stmt = mysqli_prepare($conn, $user_sql);

  if (!$user_stmt) {
    die("User statement preparation error: " . mysqli_error($conn));
  }

  mysqli_stmt_bind_param($user_stmt, "s", $username);
  mysqli_stmt_execute($user_stmt);
  $user_result = mysqli_stmt_get_result($user_stmt);

  if (mysqli_num_rows($user_result) == 1) {
    $row = mysqli_fetch_assoc($user_result);
    $hashedPassword = $row['Pass'];

    if (password_verify($enteredPassword, $hashedPassword)) {
      // User login successful
      $_SESSION['ID'] = $row['ID'];
      $_SESSION['Username'] = $username;

      // Redirect to loading page
      header("Location: loading.html");
      exit();
    }
  }

  $error = "Incorrect password or username";
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <link rel="icon" href="Logo.png" type="image/icon type">
  <meta charset="utf-8">
  <title>Login Page</title>
  <link rel="stylesheet" href="login.css">
</head>

<body>
  <div class="center">
    <h1>Login</h1>
    <form action="#" method="post">
      <div class="txt_field">
        <input type="text" name="Username" required>
        <span></span>
        <label>Username</label>
      </div>
      <div class="txt_field">
        <input type="password" name="Password" required>
        <span></span>
        <label>Password</label>
      </div>
      <div class="pass"><a href="forgotpassword.php">Forgot Password?</a></div>
      <input type="submit" value="Login">
      <div class="signup_link">
        Don't have an account? <a href="signup.php">Sign Up</a>
      </div>
    </form>
  </div>
</body>

</html>

<?php if (isset($error)) { ?>
  <script>
    alert('<?php echo $error ?>');
  </script>
<?php } ?>
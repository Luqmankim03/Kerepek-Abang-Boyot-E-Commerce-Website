<!DOCTYPE html>
<html>

<head>
    <title>Listing</title>
    <link rel="stylesheet" href="userList.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container">
        <h1 class="title">User List</h1>
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

        if (isset($_POST['delete'])) {
            $delete_id = $_POST['delete'];
            $sql = "DELETE FROM user WHERE id='$delete_id'";
            if (mysqli_query($conn, $sql)) {
                // Delete Successful
            } else {
                echo "Error deleting user: " . mysqli_error($conn);
            }
        }

        $status_update_message = "";

        if (isset($_POST['order_id']) && isset($_POST['new_status'])) {
            $order_id = $_POST['order_id'];
            $new_status = $_POST['new_status'];
            $sql = "UPDATE `order` SET Status = '$new_status' WHERE orderID='$order_id'";
            if (mysqli_query($conn, $sql)) {
                $status_update_message = "Order Status has been updated";
            } else {
                echo "Error updating order status: " . mysqli_error($conn);
            }
        }

        $sql = "SELECT ID, FullName, User, PhoneNo FROM user";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<table><tr><th>ID</th><th>Full Name</th><th>User</th><th>Phone Number</th><th>Action</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>" . $row["ID"] . "</td><td>" . $row["FullName"] . "</td><td>" . $row["User"] . "</td><td>" . $row["PhoneNo"] . "</td><td><form method='post'><button type='submit' name='delete' value='" . $row['ID'] . "'>Delete</button></form></td></tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='no-results'>No users found.</div>";
        }

        $sql = "SELECT * FROM `order`";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<h1 class='title'>Customer Order</h1>";
            echo "<table><tr><th>Order ID</th><th>Name</th><th>Address</th><th>Payment Method</th><th>Product</th><th>Quantity</th><th>Total</th><th>Status</th><th>Action</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                $current_status = $row["Status"];
                $isCompleted = ($current_status === 'Delivered');
                echo "<tr><td>" . $row["orderID"] . "</td><td>" . $row["Name"] . "</td><td>" . $row["Address"] . "</td><td>" . $row["paymentMethod"] . "</td><td>" . $row["Product"] . "</td><td>" . $row["Quantity"] . "</td><td>" . $row["Total"] . "</td><td>";
                echo "<form method='post'>";
                echo "<input type='hidden' name='order_id' value='" . $row['orderID'] . "'>";
                echo "<select name='new_status' onchange='this.form.submit()'>";
                echo "<option value='-' " . ($current_status === '-' ? 'selected' : '') . ">-</option>";
                echo "<option value='Processing' " . ($current_status === 'Processing' ? 'selected' : '') . ">Processing</option>";
                echo "<option value='Shipped' " . ($current_status === 'Shipped' ? 'selected' : '') . ">Shipped</option>";
                echo "<option value='Delivered' " . ($current_status === 'Delivered' ? 'selected' : '') . ">Delivered</option>";
                echo "</select>";
                echo "</form>";
                echo "</td><td><form method='post'><input type='hidden' name='order_id' value='" . $row['orderID'] . "'>";
                if (!$isCompleted) {
                    echo "<button type='submit' name='done'>Done?</button>";
                } else {
                    echo "<button type='submit' name='completed' class='completed'>Completed</button>";
                }
                echo "</form></td></tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='no-results'>No orders found.</div>";
        }

        mysqli_close($conn);
        ?>

        <?php if (!empty($status_update_message)) { ?>
            <script>
                alert("<?php echo $status_update_message; ?>");
            </script>
        <?php } ?>

        <a href="admin.php" class="back-btn"><i class="fas fa-chevron-left"></i> Back</a>
    </div>
</body>

</html>

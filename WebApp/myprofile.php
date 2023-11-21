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

$sql = "SELECT FullName, User, PhoneNo FROM user WHERE User = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $full_name = $row['FullName'];
        $username = $row['User'];
        $phone_number = $row['PhoneNo'];
    }
} else {
    // Handle the case where the user's data is not found in the database
    // You can redirect or display an error message
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="icon" href="Logo.png" type="image/icon type">
    <link rel="stylesheet" href="myprofile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container light-style flex-grow-1 container-p-y">
        <a href="HomePage.php" class="btn btn-primary" style="position: absolute; top: 20px; right: 85px;">Back to
            Homepage</a>
        <h4 class="font-weight-bold py-3 mb-4">
            Profile Settings
        </h4>
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-light">
                <div class="col-md-3 pt-0">
                    <div class="list-group list-group-flush account-settings-links">
                        <a class="list-group-item list-group-item-action active" data-toggle="list"
                            href="#account-general">General</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-change-password">Password</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-saved-places">Saved Places</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-order-history">Order History</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="account-general">
                            <div class="card-body">
                                <form method="post" action="update_profile.php">
                                    <div class="form-group">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" class="form-control" name="new_full_name"
                                            data-original-value="<?php echo $full_name; ?>"
                                            value="<?php echo $full_name; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Username</label>
                                        <input type="text" class="form-control mb-1"
                                            data-original-value="<?php echo $username; ?>"
                                            value="<?php echo $username; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" name="new_phone_number" required
                                            pattern="\d{3}-\d{7}"
                                            title="Please enter a phone number in the format XXX-XXXXXXX"
                                            data-original-value="<?php echo $phone_number; ?>"
                                            value="<?php echo $phone_number; ?>">
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="update_profile">Save
                                        Changes</button>
                                </form>
                            </div>
                        </div>
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                const inputFields = document.querySelectorAll("input");
                                inputFields.forEach((input) => {
                                    if (input.getAttribute("readonly") === null) {
                                        const originalValue = input.getAttribute("data-original-value");
                                        input.addEventListener("focus", function () {
                                            this.value = '';
                                        });
                                        input.addEventListener("blur", function () {
                                            if (this.value === '') {
                                                this.value = originalValue;
                                            }
                                        });
                                    }
                                });
                            });
                        </script>
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                const phoneInput = document.querySelector('[name="new_phone_number"]');
                                const errorMessage = document.createElement('span');
                                errorMessage.textContent = "Please enter a phone number in the format XXX-XXXXXXX";
                                errorMessage.style.color = 'red';
                                errorMessage.style.display = 'none';

                                phoneInput.parentNode.appendChild(errorMessage);

                                function updateErrorMessage() {
                                    const isValid = phoneInput.checkValidity();
                                    errorMessage.style.display = isValid ? 'none' : 'block';
                                }

                                phoneInput.addEventListener('input', updateErrorMessage);
                                phoneInput.addEventListener('focus', updateErrorMessage);
                                phoneInput.addEventListener('blur', updateErrorMessage);
                            });
                        </script>

                        <div class="tab-pane fade" id="account-change-password">
                            <div class="card-body">
                                <form method="post" action="update_password.php">
                                    <div class="form-group">
                                        <label class="form-label">Old Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="old_password"
                                                id="old_password" required placeholder="Enter your old password">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    onclick="togglePassword('old_password')">
                                                    <i id="old_password_icon" class="far fa-eye-slash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">New Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="new_password"
                                                id="new_password" required placeholder="Enter your new password"
                                                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                                                title="Password must be at least 8 characters long and include at least one lowercase letter, one uppercase letter, and one digit.">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    onclick="togglePassword('new_password')">
                                                    <i id="new_password_icon" class="far fa-eye-slash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Confirm New Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="confirm_password"
                                                id="confirm_password" required placeholder="Confirm your new password"
                                                title="Enter the same password as above.">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    onclick="togglePassword('confirm_password')">
                                                    <i id="confirm_password_icon" class="far fa-eye-slash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="update_password"
                                        onclick="return validatePasswords()">Change Password</button>
                                </form>
                            </div>
                        </div>

                        <script>
                            function togglePassword(inputId) {
                                const passwordInput = document.getElementById(inputId);
                                const passwordIcon = document.getElementById(inputId + '_icon');
                                const currentType = passwordInput.type;

                                passwordInput.type = currentType === 'password' ? 'text' : 'password';
                                passwordIcon.className = currentType === 'password' ? 'far fa-eye' : 'far fa-eye-slash';
                            }
                        </script>

                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                const passwordChangeMessage = "<?php echo isset($_SESSION['password_change_message']) ? $_SESSION['password_change_message'] : '' ?>";
                                if (passwordChangeMessage) {
                                    alert(passwordChangeMessage);
                                    <?php unset($_SESSION['password_change_message']); // Clear the message ?>
                                }
                            });

                            function validatePasswords() {
                                const newPassword = document.querySelector('input[name="new_password"]').value;
                                const confirmNewPassword = document.querySelector('input[name="confirm_password"]').value;

                                if (newPassword !== confirmNewPassword) {
                                    alert("New password and confirm password must match.");
                                    return false;
                                }

                                return true;
                            }
                        </script>
                        <div class="tab-pane fade" id="account-saved-places">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label">Add a Saved Place</label>
                                    <input type="text" class="form-control" id="new-address"
                                        placeholder="Enter your address">
                                    <button type="button" class="btn btn-primary" id="add-address">Add Address</button>
                                </div>

                                <hr>

                                <div class="form-group">
                                    <label class="form-label">Saved Places</label>
                                    <ul id="saved-places-list">
                                        <!-- List of saved places will be populated here -->
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                const savedPlacesList = document.getElementById("saved-places-list");
                                const newAddressInput = document.getElementById("new-address");

                                document.getElementById("add-address").addEventListener("click", function () {
                                    const newAddress = newAddressInput.value;
                                    if (newAddress.trim() !== "") {
                                        const listItem = document.createElement("li");
                                        listItem.textContent = newAddress;
                                        const deleteButton = document.createElement("button");
                                        deleteButton.textContent = "Delete";
                                        deleteButton.style.marginLeft = "10px"; // Add margin-left
                                        listItem.appendChild(deleteButton);

                                        savedPlacesList.appendChild(listItem);
                                        newAddressInput.value = "";

                                        insertAddressIntoDatabase("save_address.php", {
                                            user: "Username",
                                            address: newAddress
                                        });

                                        // Add an event listener for the delete button
                                        deleteButton.addEventListener("click", function () {
                                            // Remove the address from the front-end
                                            savedPlacesList.removeChild(listItem);
                                            // Delete the address from the database
                                            deleteAddressFromDatabase("delete_address.php", {
                                                user: "Username",
                                                address: newAddress
                                            });
                                        });
                                    }
                                });
                            });

                            function insertAddressIntoDatabase(url, data) {
                                const xhr = new XMLHttpRequest();
                                xhr.open("POST", url, true);
                                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                xhr.onreadystatechange = function () {
                                    if (xhr.readyState === 4 && xhr.status === 200) {
                                        console.log(xhr.responseText);
                                    }
                                };
                                const params = Object.keys(data)
                                    .map(key => `${key}=${encodeURIComponent(data[key])}`)
                                    .join("&");
                                xhr.send(params);
                            }

                            function deleteAddressFromDatabase(url, data) {
                                const xhr = new XMLHttpRequest();
                                xhr.open("POST", url, true);
                                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                xhr.onreadystatechange = function () {
                                    if (xhr.readyState === 4 && xhr.status === 200) {
                                        console.log(xhr.responseText);
                                    }
                                };
                                const params = Object.keys(data)
                                    .map(key => `${key}=${encodeURIComponent(data[key])}`)
                                    .join("&");
                                xhr.send(params);
                            }

                            function retrieveAndDisplaySavedAddresses() {
                                const username = "Username";
                                fetch("get_saved_addresses.php?username=" + username)
                                    .then(response => response.json())
                                    .then(data => {
                                        const savedPlacesList = document.getElementById("saved-places-list");

                                        data.forEach((address, index) => {
                                            const listItem = document.createElement("li");
                                            listItem.textContent = address;
                                            const deleteButton = document.createElement("button");
                                            deleteButton.textContent = "Delete";
                                            deleteButton.style.marginLeft = "10px";
                                            listItem.appendChild(deleteButton);

                                            savedPlacesList.appendChild(listItem);

                                            // Add an event listener for the delete button
                                            deleteButton.addEventListener("click", function () {
                                                // Remove the address from the front-end
                                                savedPlacesList.removeChild(listItem);
                                                // Delete the address from the database
                                                deleteAddressFromDatabase("delete_address.php", {
                                                    user: "Username",
                                                    address: address
                                                });
                                            });
                                        });
                                    })
                                    .catch(error => console.error("Error fetching saved addresses: " + error));
                            }

                            retrieveAndDisplaySavedAddresses();
                        </script>
                        <div class="tab-pane fade" id="account-order-history">
                            <table class="order-history-table">
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Payment Method</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                </tr>
                                <?php
                                $host = "localhost:3308";
                                $user = "root";
                                $password = "";
                                $db = "kerepek";

                                $conn = mysqli_connect($host, $user, $password, $db);

                                if (!$conn) {
                                    die("Connection failed: " . mysqli_connect_error());
                                }

                                $username = $_SESSION['Username'];

                                $userQuery = "SELECT FullName FROM user WHERE User = '$username'";
                                $userResult = mysqli_query($conn, $userQuery);

                                if (mysqli_num_rows($userResult) > 0) {
                                    $userRow = mysqli_fetch_assoc($userResult);
                                    $fullName = $userRow['FullName'];

                                    // Query to retrieve order history using the Full Name
                                    $orderQuery = "SELECT Product, Quantity, Total, paymentMethod, Address, Status FROM `order` WHERE Name = '$fullName'";
                                    $orderResult = mysqli_query($conn, $orderQuery);

                                    if (mysqli_num_rows($orderResult) > 0) {
                                        while ($row = mysqli_fetch_assoc($orderResult)) {
                                            echo "<tr>";
                                            echo "<td>" . $row['Product'] . "</td>";
                                            echo "<td>" . $row['Quantity'] . "</td>";
                                            echo "<td>" . $row['Total'] . "</td>";
                                            echo "<td>" . $row['paymentMethod'] . "</td>";
                                            echo "<td>" . $row['Address'] . "</td>";
                                            echo "<td>" . $row['Status'] . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo '<tr><td colspan="6"><span style="color: red;">No Orders Found.</span></td></tr>';
                                    }
                                } else {
                                    echo "Full Name Not Found.";
                                }

                                mysqli_close($conn);
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
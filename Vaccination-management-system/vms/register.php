<?php
$host = 'localhost';
$db = 'vms_db';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $password = trim($_POST["password"]);
    $role = trim($_POST["role"]); // User chooses role (user/hospital/admin)

    // Validation
    if (empty($username) || empty($full_name) || empty($email) || empty($phone) || empty($address) || empty($password) || empty($role)) {
        $error = "All fields are required!";
    } else {
        // Check if username or email exists
        $checkQuery = "SELECT * FROM users WHERE username=? OR email=?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $checkResult = $stmt->get_result();

        if ($checkResult->num_rows > 0) {
            $error = "Username or Email already exists!";
        } else {
            // Hash password using password_hash() for secure storage
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Default status is active
            $status = "active";

            // Insert user into database
            $insertQuery = "INSERT INTO users (username, full_name, email, phone, address, password, role, status, created_at) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ssssssss", $username, $full_name, $email, $phone, $address, $hashed_password, $role, $status);

            if ($stmt->execute()) {
                header("Location: login.php?success=1");
                exit();
            } else {
                $error = "Error: " . $stmt->error;
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <h3>Sign Up</h3>
            <?php if ($error): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <form action="register.php" method="post">
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required>
                </div>

                <div class="field input">
                    <label for="full_name">Full Name</label>
                    <input type="text" name="full_name" id="full_name" required>
                </div>

                <div class="field input">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>
                </div>

                <div class="field input">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" id="phone" required>
                </div>

                <div class="field input">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" required>
                </div>

                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <div class="field input">
                    <label for="role">Role</label>
                    <select name="role" id="role" required>
                        <option value="user">User</option>
                        <option value="hospital">Hospital</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="field">
                    <input type="submit" class="btn" value="Register">
                </div>

                <div class="links">
                    Already a member? <a href="login.php">Sign In</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<?php
session_start();

// Database connection
$host = 'localhost';
$db = 'vms_db';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Forgot Password Notification
if (isset($_POST['notify_admin'])) {
    $username = $_POST['username'] ?? 'Unknown User';

    // Store notification in the database
    $message = "Password reset request from user: $username";
    $stmt = $conn->prepare("INSERT INTO notifications (message, created_at) VALUES (?, NOW())");
    $stmt->bind_param("s", $message);
    $stmt->execute();

    // Show a pop-up notification
    echo "<script>alert('Notification sent to admin successfully!');</script>";
}

// Login Logic
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password!";
    } else {
        $query = "SELECT user_id, role, username, password FROM users WHERE username=? AND status='active'";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                session_unset();
                session_destroy();
                session_start();

                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['username'] = $user['username'];

                switch ($user['role']) {
                    case 'admin':
                        header("Location: /admin/admin-dashboard/index.php");
                        exit();
                    case 'hospital':
                        header("Location: /admin/Hospital-dashboard/dashboard.php");
                        exit();
                    case 'patient':
                        header("Location: /admin/user_web/index.php");
                        exit();
                    default:
                        $error = "Unauthorized access!";
                }
            } else {
                $error = "Invalid username or password!";
            }
        } else {
            $error = "Invalid username or password!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <div class="container">
        <div class="box form-box">
            <h3>Login</h3>

            <?php if (!empty($error)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <form action="login.php" method="post">
                <!-- Username -->
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" autocomplete="off" required>
                </div>

                <!-- Password -->
                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>

                <!-- Forgot Password -->
                <div class="forgot-link">
                    <button type="submit" name="notify_admin" class="btn-link">Forgot Password?</button>
                </div>

                <!-- Submit -->
                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Login">
                </div>

                <!-- Register Link -->
                <div class="links">
                    Don't have an account? <a href="register.php">Sign Up Now</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
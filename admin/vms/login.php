<?php
session_start(); // Start session

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$db = 'vms_db';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate input
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password!";
    } else {
        // Prepare and execute query securely
        $query = "SELECT user_id, role, username, password FROM users WHERE username=? AND status='active'";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Use password_verify() to compare entered password with stored hashed password
            if (password_verify($password, $user['password'])) {
                // Reset session before login to avoid session issues
                session_unset();
                session_destroy();
                session_start();

                // Set session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['username'] = $user['username'];

                // Redirect based on role
                switch ($user['role']) {
                    case 'admin':
                        header("Location: /admin/admin-dash/index.php");
                        exit();
                    case 'hospital':
                        header("Location: /admin/E-Project-mahdi/dashboard.php");
                        exit();
                    case 'patient':
                        header("Location: /admin/r_web/index.php");
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
    <!-- Custom CSS -->
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
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>

                <div class="forgot-link">
                    <a href="forgot-pass.php">Forgot Password?</a>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Login">
                </div>

                <div class="links">
                    Don't have an account? <a href="register.php">Sign Up Now</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<?php
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vms_db";

// Initialize variables
$error = "";
$success = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']) && $_POST['submit'] == "Confirm") {
    // Sanitize and validate email
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Create database connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if email exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Generate OTP
            $otp = random_int(100000, 999999);
            $otp_expiry = date("Y-m-d H:i:s", time() + 600); // 10 minutes expiry

            // Store OTP in database
            $stmt = $conn->prepare("UPDATE users SET otp = ?, otp_expiry = ? WHERE email = ?");
            $stmt->bind_param("sss", $otp, $otp_expiry, $email);
            $stmt->execute();

            // Send OTP via email
            $to = $email;
            $subject = "Password Reset OTP";
            $message = "Your OTP for password reset is: $otp\nThis OTP is valid for 10 minutes.";
            $headers = "From: your_email@example.com";

            if (mail($to, $subject, $message, $headers)) {
                $_SESSION['reset_email'] = $email;
                header("Location: reset_password.php");
                exit();
            } else {
                $error = "Failed to send OTP!";
            }
        } else {
            $error = "Email not found!";
        }

        $conn->close();
    } else {
        $error = "Invalid email address!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <!-- Custom CSS link -->
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <!-- Main form container -->
    <div class="container">
        <div class="box form-box">
            <h6>Forgot your password?</h6>
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" autocomplete="off" required>
                </div>
                <!-- Submit and back buttons -->
                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Confirm">
                    <a href="login.php" class="btn">Back</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
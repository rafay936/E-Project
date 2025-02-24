<?php
session_start();

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection details
$host = 'localhost';
$db   = 'vms_db';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// (Optional) message to display success/error
$message = "";

// Fetch hospitals for dropdown
$hospitals_result = $conn->query("SELECT hospital_id, hospital_name FROM hospitals ORDER BY hospital_name ASC");

// Handle form submission for new appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_appointment') {
    $patient_name     = $_POST['patient_name']     ?? '';
    $hospital_id      = $_POST['hospital_id']      ?? '';
    $appointment_date = $_POST['appointment_date'] ?? '';
    $appointment_time = $_POST['appointment_time'] ?? '';
    $reason           = $_POST['reason']           ?? '';

    if (!empty($patient_name) && !empty($appointment_date) && !empty($appointment_time)) {
        $stmt = $conn->prepare("
            INSERT INTO appointments (patient_name, hospital_id, appointment_date, appointment_time, reason)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param('sisss', $patient_name, $hospital_id, $appointment_date, $appointment_time, $reason);
        if ($stmt->execute()) {
            $message = "<p class='success'>New appointment added successfully!</p>";
        } else {
            $message = "<p class='error'>Error adding appointment: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        $message = "<p class='error'>Please fill out the required fields.</p>";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Schedule Appointment</title>
    <link rel="stylesheet" href="web.css">
    <style>
      body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }
      /* Navbar (Light Purple) */
      .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background: #6C63FF; /* Changed from green to light purple */
      }
      .navbar .logo img {
        height: 40px;
      }
      .nav-links {
        list-style: none;
        display: flex;
      }
      .nav-links li {
        margin: 0 10px;
      }
      .nav-links a {
        text-decoration: none;
        color: white;
        font-weight: bold;
      }

      /* Appointment form container */
      .appointment-form {
        max-width: 600px;
        margin: 30px auto;
        padding: 20px;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
      }
      .appointment-form h3 {
        margin-bottom: 20px;
        text-align: center;
      }
      .appointment-form label {
        display: block;
        margin: 15px 0 5px;
        font-weight: bold;
      }
      .appointment-form input,
      .appointment-form select,
      .appointment-form textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
      }
      .appointment-form button {
        background: #6C63FF; /* changed from #4CAF50 */
        color: #fff;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        transition: background 0.3s, transform 0.2s;
      }
      .appointment-form button:hover {
        background: #5a52e0; /* changed hover from #45a049 to a darker purple */
        transform: scale(1.03);
      }

      /* Footer (Light Purple) */
      .footer {
        background: #6C63FF; /* changed from green to light purple */
        color: white;
        text-align: center;
        padding: 20px;
        margin-top: 30px;
      }
      .footer-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
      }
      .footer-section {
        flex: 1;
        margin: 10px;
      }
      .footer-section h3 {
        margin-bottom: 10px;
      }
      .footer-section ul {
        list-style: none;
        padding: 0;
      }
      .footer-section ul li {
        margin: 5px 0;
      }
      .footer-section ul li a {
        color: white;
        text-decoration: none;
      }
      .footer-bottom {
        margin-top: 20px;
        border-top: 1px solid #ddd;
        padding-top: 10px;
      }

      /* Success/error messages */
      .success {
        color: green;
        text-align: center;
        margin-top: 10px;
      }
      .error {
        color: red;
        text-align: center;
        margin-top: 10px;
      }
    </style>
</head>
<body>
    <!-- Light Purple Navbar -->
    <nav class="navbar">
        <div class="logo">
            <img src="https://cdn-icons-png.flaticon.com/512/4386/4386905.png" alt="Vaccination Logo">
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="schedule.php">Schedule</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>

    <!-- Display success/error message -->
    <?php if (!empty($message)) echo $message; ?>

    <!-- Appointment Form -->
    <div class="appointment-form">
        <h3>Create a New Appointment</h3>
        <form method="POST">
            <input type="hidden" name="action" value="add_appointment">
            
            <label for="patient_name">Patient Name <span style="color:red;">*</span></label>
            <input type="text" id="patient_name" name="patient_name" required>

            <label for="hospital_id">Hospital (optional)</label>
            <select id="hospital_id" name="hospital_id">
                <option value="">-- Select Hospital --</option>
                <?php if ($hospitals_result && $hospitals_result->num_rows > 0): ?>
                  <?php while($hrow = $hospitals_result->fetch_assoc()): ?>
                    <option value="<?php echo $hrow['hospital_id']; ?>">
                      <?php echo htmlspecialchars($hrow['hospital_name']); ?>
                    </option>
                  <?php endwhile; ?>
                <?php endif; ?>
            </select>

            <label for="appointment_date">Appointment Date <span style="color:red;">*</span></label>
            <input type="date" id="appointment_date" name="appointment_date" required>

            <label for="appointment_time">Appointment Time <span style="color:red;">*</span></label>
            <input type="time" id="appointment_time" name="appointment_time" required>

            <label for="reason">Reason / Notes</label>
            <textarea id="reason" name="reason" rows="3"></textarea>

            <button type="submit">Add Appointment</button>
        </form>
    </div>

    <!-- Light Purple Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section logo-section">
                <img src="logo.png" alt="Footer Logo" class="footer-logo">
                <p>Your trusted partner in vaccination and healthcare services.</p>
                <div class="contact-info">
                    <p>Email: info@vaccineportal.com</p>
                    <p>Phone: +1 234 567 890</p>
                    <p>Address: 123 Health Street</p>
                </div>
            </div>
            <div class="footer-links">
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#schedule">Schedule</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#faq">FAQ</a></li>
                        <li><a href="#blog">Blog</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Services</h3>
                    <ul>
                        <li><a href="#vaccines">Vaccines</a></li>
                        <li><a href="#consultation">Medical Consultation</a></li>
                        <li><a href="#certificates">Certificates</a></li>
                        <li><a href="#support">24/7 Support</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Follow Us</h3>
                    <div class="social-links">
                        <a href="#">Facebook</a>
                        <a href="#">Twitter</a>
                        <a href="#">Instagram</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Vaccination Portal. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

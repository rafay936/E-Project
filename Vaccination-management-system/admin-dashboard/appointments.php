<?php
session_start();

// Authentication Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /login.php'); 
    exit;
}

// Database connection (using mysqli)
$host = 'localhost';
$db   = 'vms_db';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

/* 
   1) Create the appointments table if it doesn't already exist.
      Adjust column definitions as needed for your real use case.
*/
$conn->query("
  CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_name VARCHAR(255) NOT NULL,
    hospital_id INT DEFAULT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    reason TEXT,
    status ENUM('Scheduled','Completed','Canceled') DEFAULT 'Scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  )
");

/* 
   2) Handle form submission to add a new appointment.
*/
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_appointment') {
    $patient_name = $_POST['patient_name'] ?? '';
    $hospital_id  = $_POST['hospital_id'] ?? '';
    $appointment_date = $_POST['appointment_date'] ?? '';
    $appointment_time = $_POST['appointment_time'] ?? '';
    $reason       = $_POST['reason'] ?? '';

    // Basic validation (optional)
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

/*
   3) Handle "Complete" or "Cancel" actions via GET parameters
      e.g., appointments.php?action=complete&id=2
*/
if (isset($_GET['action'], $_GET['id'])) {
    $action = $_GET['action'];
    $id     = (int) $_GET['id'];

    if ($action === 'complete') {
        $conn->query("UPDATE appointments SET status='Completed' WHERE id=$id");
    } elseif ($action === 'cancel') {
        $conn->query("UPDATE appointments SET status='Canceled' WHERE id=$id");
    }
}

/*
   4) Fetch all appointments for display.
*/
$result = $conn->query("SELECT * FROM appointments ORDER BY appointment_date ASC, appointment_time ASC");

/*
   5) Optionally, fetch hospitals to populate a dropdown for hospital selection
*/
$hospitals_result = $conn->query("SELECT hospital_id, hospital_name FROM hospitals ORDER BY hospital_name ASC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Appointments - Admin Panel</title>
  <!-- Optional: Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    /* Reset margins and padding so we can control layout fully */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      background-color: #f4f4f9;
      font-family: Arial, sans-serif;
      color: #333;
    }
    /* Main wrapper that spans full width */
    .page-wrapper {
      display: flex;
      width: 100%;
      min-height: 100vh; /* fill the vertical space */
    }
    /* Sidebar pinned to the left */
    .sidebar {
      width: 250px;
      background: #800080;
      color: #fff;
      padding: 20px;
    }
    .sidebar .logo {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 20px;
    }
    .nav-links {
      list-style: none;
      margin: 0;
      padding: 0;
    }
    .nav-links li {
      margin-bottom: 10px;
    }
    .nav-links a {
      color: #fff;
      text-decoration: none;
      display: block;
      padding: 10px;
      border-radius: 4px;
      transition: background 0.3s, transform 0.2s;
    }
    .nav-links a:hover {
      background: #9932CC;
    }
    .nav-links a.active {
      background: #DA70D6;
      color: #000;
      font-weight: bold;
    }
    /* Main content to the right */
    .main-content {
      flex: 1; /* takes remaining horizontal space */
      display: flex;
      flex-direction: column; /* so top navbar is at top, content below */
    }
    /* Top Navbar */
    .top-navbar {
      background: #800080;
      color: #fff;
      padding: 10px 20px;
      border-bottom: 1px solid #5e005e;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .top-navbar h2 {
      margin: 0;
    }
    /* Page Content */
    .content {
      padding: 20px;
      flex: 1; /* let content expand */
    }
    /* Footer */
    .footer {
      background: #800080;
      color: #fff;
      text-align: center;
      padding: 10px;
    }
    /* Table & Buttons */
    table {
      width: 95%;
      margin: 20px auto;
      border-collapse: collapse;
      background: #fff;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      border-radius: 5px;
    }
    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: left;
    }
    th {
      background: #800080;
      color: #fff;
    }
    tr:hover {
      background: #f1f1f1;
      transition: background 0.3s;
    }
    .success { color: green; text-align: center; }
    .error { color: red; text-align: center; }
    a.action-btn {
      display: inline-block;
      margin: 5px;
      padding: 8px 12px;
      background: #800080;
      color: #fff;
      text-decoration: none;
      border-radius: 5px;
      transition: background 0.3s, transform 0.2s;
      font-size: 0.9rem;
    }
    a.action-btn:hover {
      background: #9932CC;
      transform: scale(1.05);
    }
    .disabled {
      pointer-events: none;
      opacity: 0.6;
      background-color: #ccc;
      padding: 8px 12px;
      border-radius: 5px;
      font-size: 0.9rem;
    }
    /* Appointment Form Styles */
    .appointment-form {
      background: #fff;
      width: 90%;
      max-width: 600px;
      margin: 20px auto;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .appointment-form h3 {
      margin-bottom: 15px;
      color: #800080;
      text-align: center;
    }
    .appointment-form label {
      display: block;
      margin: 10px 0 5px;
      color: #800080;
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
      background: #800080;
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 0.95rem;
      transition: background 0.3s, transform 0.2s;
    }
    .appointment-form button:hover {
      background: #9932CC;
      transform: scale(1.05);
    }
  </style>
</head>
<body>

<div class="page-wrapper">
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="logo">Admin Panel</div>
    <ul class="nav-links">
         <li><a href="index.php" ><i class="fe fe-home"></i> Dashboard</a></li>
         <li><a href="hospitals.php"><i class="fe fe-users"></i> Hospitals</a></li>
         <li><a href="appointments.php" class="active"><i class="fe fe-star-o"></i> Appointments</a></li>
         <li><a href="patients.php"><i class="fe fe-user"></i> Patients</a></li>
         <li><a href="vaccines.php"><i class="fe fe-layout"></i> Vaccines</a></li>
         <li><a href="Vaccine Requests.php"><i class="fe fe-document"></i> Vaccime Req</a></li>
         <li><a href="/admin/admin-dashboard/certificate.php?cert_id=2" ><i class="fe fe-document"></i>certificate</a></li>
      </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Top Navbar -->
    <nav class="top-navbar">
      <h2>Appointments</h2>
      <div class="nav-controls">
        <div class="dropdown">
          <div class="dropdown-menu">
            <a class="dropdown-item" href="/admin/logout.php">Logout</a>
          </div>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <div class="content">
      <?php if (!empty($message)) echo $message; ?>

      <!-- Appointment Form -->
      <!-- <div class="appointment-form">
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
      </div> -->

      <!-- Appointments Table -->
      <h1 style="background: #800080; color: #fff; padding: 10px; text-align:center; margin-top:30px;">
        Current Appointments
      </h1>
      <table>
        <tr>
          <th>ID</th>
          <th>Patient Name</th>
          <th>Hospital ID</th>
          <th>Date</th>
          <th>Time</th>
          <th>Reason</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
            <td><?php echo htmlspecialchars($row['hospital_id'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
            <td><?php echo htmlspecialchars($row['appointment_time']); ?></td>
            <td><?php echo htmlspecialchars($row['reason']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td>
              <?php if ($row['status'] === 'Scheduled'): ?>
                <a href="appointments.php?action=complete&id=<?php echo $row['id']; ?>" class="action-btn">Complete</a>
                <a href="appointments.php?action=cancel&id=<?php echo $row['id']; ?>" class="action-btn">Cancel</a>
              <?php else: ?>
                <span class="disabled"><?php echo $row['status']; ?></span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </table>
    </div>

    <!-- Footer -->
    <footer class="footer">
      &copy; <?php echo date("Y"); ?> Hospital Vaccination Admin
    </footer>
  </div>
</div>

<!-- Optional Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>

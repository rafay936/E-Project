<?php
session_start();

// Authentication Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /login.php'); // Adjust to your login path
    exit;
}

// Database connection (using mysqli)
$host = 'localhost';
$db = 'vms_db';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Handle approval/rejection of vaccine requests
$message = "";
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $request_id = $_GET['id'];
    if ($action === 'approve') {
        $update_status_query = "UPDATE vaccine_requests SET status = 'Approved' WHERE id = ?";
    } elseif ($action === 'reject') {
        $update_status_query = "UPDATE vaccine_requests SET status = 'Rejected' WHERE id = ?";
    }
    if (isset($update_status_query)) {
        $stmt = $conn->prepare($update_status_query);
        $stmt->bind_param('i', $request_id);
        if ($stmt->execute()) {
            $message = "<p class='success'>Request $action successfully!</p>";
        } else {
            $message = "<p class='error'>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
}

// Fetch available vaccines for the dropdown (for future use)
$inventory_query = "SELECT * FROM vaccines";
$inventory_result = $conn->query($inventory_query);

// Fetch vaccine requests from the database (for display and management)
$requests_query = "SELECT * FROM vaccine_requests ORDER BY request_date DESC";
$requests_result = $conn->query($requests_query);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hospital Vaccination Admin - Vaccines</title>
  <link rel="stylesheet" href="style.css">
  <!-- Include Bootstrap CSS -->
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
      width: 90%;
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
      padding: 10px;
      background: #800080;
      color: #fff;
      text-decoration: none;
      border-radius: 5px;
      transition: background 0.3s, transform 0.2s;
    }
    a.action-btn:hover {
      background: #9932CC;
      transform: scale(1.1);
    }
    .disabled {
      pointer-events: none;
      opacity: 0.6;
      background-color: #ccc;
      padding: 10px;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="logo">Admin Panel</div>
      <ul class="nav-links">
         <li><a href="index.php"><i class="fe fe-home"></i> Dashboard</a></li>
         <li><a href="hospitals.php"><i class="fe fe-users"></i> Hospitals</a></li>
         <li><a href="appointments.php"><i class="fe fe-star-o"></i> Appointments</a></li>
         <li><a href="patients.php"><i class="fe fe-user"></i> Patients</a></li>
         <li><a href="vaccines.php"  class="active"><i class="fe fe-layout"></i> Vaccines</a></li>
         <li><a href="Vaccine Requests.php"><i class="fe fe-document"></i> Vaccime Req</a></li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Top Navbar -->
      <nav class="top-navbar">
        <h2>Vaccines</h2>
        <div class="nav-controls">
          <div class="dropdown">
            <div class="dropdown-menu">
              <a class="dropdown-item" href="/admin/logout.php">Logout</a>
            </div>
          </div>
        </div>
      </nav>

      <!-- Content Area -->
      <div class="content">
        <?php if (!empty($message)) echo $message; ?>
        <h1>Vaccine Requests</h1>
        <table class="report-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Request Date</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($request = $requests_result->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($request['id']); ?></td>
              <td><?php echo htmlspecialchars($request['request_date']); ?></td>
              <td><?php echo htmlspecialchars($request['status']); ?></td>
              <td>
                <?php if ($request['status'] != 'Approved'): ?>
                  <a href="vaccines.php?action=approve&id=<?php echo $request['id']; ?>" class="btn btn-sm" style="background:#800080; color:#fff;">Approve</a>
                <?php endif; ?>
                <?php if ($request['status'] != 'Rejected'): ?>
                  <a href="vaccines.php?action=reject&id=<?php echo $request['id']; ?>" class="btn btn-sm" style="background:#800080; color:#fff;">Reject</a>
                <?php endif; ?>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>

        <!-- Vaccine Inventory Section -->
        <h2>Vaccine Inventory</h2>
        <table class="report-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Vaccine Name</th>
              <th>Quantity</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($vaccine = $inventory_result->fetch_assoc()): ?>
            <tr>
              <!-- Replace 'vaccine_id' with your actual primary key name if different -->
              <td><?php echo htmlspecialchars($vaccine['vaccine_id'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($vaccine['vaccine_name']); ?></td>
              <td><?php echo htmlspecialchars($vaccine['quantity']); ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

      <!-- Footer -->
      <footer class="footer">
        <!-- Footer Content (if any) -->
      </footer>
    </div>
  </div>
  
  <!-- Scripts: jQuery and Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>

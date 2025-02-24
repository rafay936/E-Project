<?php
session_start();

// Authentication Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin/vms/login.php'); 
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

// Handle approval/rejection of vaccine requests
$message = "";
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $request_id = (int)$_GET['id'];

    $check_query = "SELECT status FROM vaccine_requests WHERE id = $request_id";
    $check_result = $conn->query($check_query);
    $row = $check_result->fetch_assoc();

    if ($row && $row['status'] === 'Pending') {
        $status = ($action === 'approve') ? 'Approved' : 'Rejected';
        $update_query = "UPDATE vaccine_requests SET status = '$status' WHERE id = $request_id";

        if ($conn->query($update_query) === TRUE) {
            $message = "<p class='success'>Request $status successfully!</p>";
        } else {
            $message = "<p class='error'>Error updating status: " . $conn->error . "</p>";
        }
    }
}

// Fetch only Pending requests for the main table
$pending_query = "SELECT * FROM vaccine_requests WHERE status = 'Pending' ORDER BY request_date DESC";
$pending_result = $conn->query($pending_query);

// Fetch Approved and Rejected requests for the history dropdown
$approved_query = "SELECT * FROM vaccine_requests WHERE status = 'Approved' ORDER BY request_date DESC";
$approved_result = $conn->query($approved_query);

$rejected_query = "SELECT * FROM vaccine_requests WHERE status = 'Rejected' ORDER BY request_date DESC";
$rejected_result = $conn->query($rejected_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Vaccine Requests - Admin Panel</title>
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
    /* Dropdown for history */
    .dropdown {
      position: relative;
      display: inline-block;
    }
    .dropdown-toggle {
      background: #9932CC;
      color: #fff;
      padding: 8px 12px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 0.9rem;
      transition: background 0.3s;
    }
    .dropdown-toggle:hover {
      background: #DA70D6;
      color: #000;
    }
    .dropdown-menu {
      display: none;
      position: absolute;
      background-color: #fff;
      min-width: 100px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
      padding: 5px 0;
      z-index: 99;
      border-radius: 4px;
      right: 0; /* aligns to the right of the button */
    }
    .dropdown-menu a {
      color: #333;
      padding: 8px 12px;
      text-decoration: none;
      display: block;
      transition: background 0.3s;
    }
    .dropdown-menu a:hover {
      background: #f1f1f1;
    }
    .dropdown:hover .dropdown-menu {
      display: block;
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

    /* Hide approved/rejected tables by default */
    #approvedSection, #rejectedSection {
      display: none;
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
         <li><a href="appointments.php"><i class="fe fe-star-o"></i> Appointments</a></li>
         <li><a href="patients.php" ><i class="fe fe-user"></i> Patients</a></li>
         <li><a href="vaccines.php"><i class="fe fe-layout"></i> Vaccines</a></li>
         <li><a href="Vaccine Requests.php" class="active"><i class="fe fe-document"></i> Vaccime Req</a></li>
         <li><a href="/admin/admin-dash/certificate.php?cert_id=2" ><i class="fe fe-document"></i>certificate</a></li>
      </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Top Navbar -->
    <nav class="top-navbar">
      <h2>Vaccine Requests</h2>
      <div class="nav-controls">
        <!-- History Dropdown -->
        <div class="dropdown">
          <button class="dropdown-toggle">History</button>
          <div class="dropdown-menu">
            <a href="#" onclick="toggleApproved(); return false;">Approved</a>
            <a href="#" onclick="toggleRejected(); return false;">Rejected</a>
          </div>
        </div>
        <!-- Logout or other controls -->
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
      <h1 style="background: #800080; color: #fff; padding: 10px; text-align:center;">
        Pending Vaccine Requests
      </h1>

      <!-- Pending Requests Table -->
      <table>
        <tr>
          <th>Requester Name</th>
          <th>Vaccine</th>
          <th>Quantity</th>
          <th>Request Date</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
        <?php while ($row = $pending_result->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['requester_name']); ?></td>
          <td><?php echo htmlspecialchars($row['vaccine_name']); ?></td>
          <td><?php echo htmlspecialchars($row['quantity']); ?></td>
          <td><?php echo htmlspecialchars($row['request_date']); ?></td>
          <td><?php echo htmlspecialchars($row['status']); ?></td>
          <td>
            <a href="?action=approve&id=<?php echo $row['id']; ?>" class="action-btn">Approve</a>
            <a href="?action=reject&id=<?php echo $row['id']; ?>" class="action-btn">Reject</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </table>

      <!-- Approved Requests (Hidden by default) -->
      <div id="approvedSection">
        <h1 style="background: #800080; color: #fff; padding: 10px; text-align:center; margin-top:30px;">
          Approved Requests
        </h1>
        <table>
          <tr>
            <th>Requester Name</th>
            <th>Vaccine</th>
            <th>Quantity</th>
            <th>Request Date</th>
            <th>Status</th>
          </tr>
          <?php while ($row = $approved_result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['requester_name']); ?></td>
            <td><?php echo htmlspecialchars($row['vaccine_name']); ?></td>
            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
            <td><?php echo htmlspecialchars($row['request_date']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
          </tr>
          <?php endwhile; ?>
        </table>
      </div>

      <!-- Rejected Requests (Hidden by default) -->
      <div id="rejectedSection">
        <h1 style="background: #800080; color: #fff; padding: 10px; text-align:center; margin-top:30px;">
          Rejected Requests
        </h1>
        <table>
          <tr>
            <th>Requester Name</th>
            <th>Vaccine</th>
            <th>Quantity</th>
            <th>Request Date</th>
            <th>Status</th>
          </tr>
          <?php while ($row = $rejected_result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['requester_name']); ?></td>
            <td><?php echo htmlspecialchars($row['vaccine_name']); ?></td>
            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
            <td><?php echo htmlspecialchars($row['request_date']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
          </tr>
          <?php endwhile; ?>
        </table>
      </div>
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
<script>
  function toggleApproved() {
    const approved = document.getElementById('approvedSection');
    approved.style.display = (approved.style.display === 'none' || approved.style.display === '') 
      ? 'block' 
      : 'none';
  }
  function toggleRejected() {
    const rejected = document.getElementById('rejectedSection');
    rejected.style.display = (rejected.style.display === 'none' || rejected.style.display === '') 
      ? 'block' 
      : 'none';
  }
</script>
</body>
</html>
<?php
$conn->close();
?>

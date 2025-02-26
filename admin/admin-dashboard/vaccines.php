<?php
session_start();

// Authentication Check – only allow admin users
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header('Location: admin/vms/login.php');
  exit;
}

// Database connection using mysqli
$host = 'localhost';
$db = 'vms_db';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die('Connection failed: ' . $conn->connect_error);
}

$message = "";

// If the edit form is submitted, update the vaccine record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
  $vaccine_id = intval($_POST['vaccine_id']);
  $vaccine_name = $conn->real_escape_string(trim($_POST['vaccine_name']));
  $vaccine_type = $conn->real_escape_string(trim($_POST['vaccine_type']));
  $quantity = intval($_POST['quantity']);

  if ($vaccine_id > 0 && !empty($vaccine_name)) {
    $update_query = "UPDATE vaccines SET vaccine_name = ?, vaccine_type = ?, quantity = ? WHERE vaccine_id = ?";
    $stmt = $conn->prepare($update_query);
    if ($stmt) {
      $stmt->bind_param("ssii", $vaccine_name, $vaccine_type, $quantity, $vaccine_id);
      if ($stmt->execute()) {
        $message = "<p class='success'>Vaccine updated successfully!</p>";
      } else {
        $message = "<p class='error'>Update failed: " . $stmt->error . "</p>";
      }
      $stmt->close();
    } else {
      $message = "<p class='error'>Prepare failed: " . $conn->error . "</p>";
    }
  } else {
    $message = "<p class='error'>Invalid vaccine data.</p>";
  }
}

// Fetch vaccine inventory
$inventory_query = "SELECT * FROM vaccines";
$inventory_result = $conn->query($inventory_query);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hospital Vaccination Admin - Vaccines</title>
  <link rel="stylesheet" href="style.css">
  <!-- Optional Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    /* Basic Reset */
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

    /* Container & Layout */
    .container-wrapper {
      display: flex;
      width: 100%;
      min-height: 100vh;
    }

    .sidebar {
      width: 250px;
      background: #800080;
      color: #fff;
      padding: 20px;
      min-height: 100vh;
    }

    .sidebar .logo {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .nav-links {
      list-style: none;
      padding: 0;
      margin: 0;
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

    .nav-links a:hover,
    .nav-links a.active {
      background: #DA70D6;
      color: #000;
    }

    .main-content {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .top-navbar {
      background: #800080;
      color: #fff;
      padding: 10px 20px;
      border-bottom: 1px solid #5e005e;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .content {
      padding: 20px;
      flex: 1;
    }

    /* Inventory Table */
    table {
      width: 90%;
      margin: 20px auto;
      border-collapse: collapse;
      background: #fff;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      border-radius: 5px;
    }

    th,
    td {
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

    /* Action Button */
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

    /* In-Page Edit Form */
    .edit-form {
      max-width: 600px;
      margin: 30px auto;
      padding: 20px;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 5px;
      display: none;
    }

    .edit-form h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #800080;
    }

    .edit-form .form-group {
      margin-bottom: 15px;
    }

    .edit-form label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    .edit-form input[type="text"],
    .edit-form select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    .edit-form button {
      background: #800080;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 4px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .edit-form button:hover {
      background: #9932CC;
    }

    /* Footer */
    .footer {
      background: #800080;
      color: #fff;
      text-align: center;
      padding: 15px;
      margin-top: auto;
    }

    /* Message styles */
    .success {
      color: green;
      text-align: center;
      margin: 10px 0;
    }

    .error {
      color: red;
      text-align: center;
      margin: 10px 0;
    }
  </style>
  <script>
    // This function shows the edit form and populates it with the vaccine data from the selected row.
    function editVaccine(vaccineId, vaccineName, vaccineType, quantity) {
      document.getElementById('editForm').style.display = 'block';
      document.getElementById('vaccine_id').value = vaccineId;
      document.getElementById('vaccine_name').value = vaccineName;
      document.getElementById('vaccine_type').value = vaccineType;
      document.getElementById('quantity').value = quantity;
      window.scrollTo(0, document.body.scrollHeight);
    }
    // Optional: Function to hide the edit form
    function hideEditForm() {
      document.getElementById('editForm').style.display = 'none';
    }
  </script>
</head>

<body>
  <div class="container-wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="logo">Admin Panel</div>
      <ul class="nav-links">
        <li><a href="index.php"><i class="fe fe-home"></i> Dashboard</a></li>
        <li><a href="hospitals.php"><i class="fe fe-users"></i> Hospitals</a></li>
        <li><a href="appointments.php"><i class="fe fe-star-o"></i> Appointments</a></li>
        <li><a href="patients.php"><i class="fe fe-user"></i> Patients</a></li>
        <li><a href="vaccines.php" class="active"><i class="fe fe-layout"></i> Vaccines</a></li>
        <li><a href="Vaccine Requests.php"><i class="fe fe-document"></i> Vaccime Req</a></li>
        <li><a href="/admin/admin-dashboard/certificate.php?cert_id=2"><i class="fe fe-document"></i>certificate</a>
        </li>
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
        <?php if (!empty($message))
          echo $message; ?>
        <h1>Vaccine Inventory</h1>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Vaccine Name</th>
              <th>Type</th>
              <th>Quantity</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($vaccine = $inventory_result->fetch_assoc()): ?>
              <tr>
                <td><?php echo htmlspecialchars($vaccine['vaccine_id'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($vaccine['vaccine_name']); ?></td>
                <td><?php echo htmlspecialchars($vaccine['vaccine_type']); ?></td>
                <td><?php echo htmlspecialchars($vaccine['quantity']); ?></td>
                <td>
                  <a href="javascript:void(0);" class="action-btn"
                    onclick="editVaccine('<?php echo $vaccine['vaccine_id']; ?>','<?php echo htmlspecialchars($vaccine['vaccine_name'], ENT_QUOTES); ?>','<?php echo htmlspecialchars($vaccine['vaccine_type'], ENT_QUOTES); ?>','<?php echo $vaccine['quantity']; ?>')">Edit</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>

        <!-- In-Page Edit Form (hidden by default) -->
        <div id="editForm" class="edit-form">
          <h2>Edit Vaccine</h2>
          <form action="vaccines.php" method="post">
            <input type="hidden" name="vaccine_id" id="vaccine_id">
            <div class="form-group">
              <label for="vaccine_name">Vaccine Name:</label>
              <input type="text" name="vaccine_name" id="vaccine_name" required>
            </div>
            <div class="form-group">
              <label for="vaccine_type">Vaccine Type:</label>
              <input type="text" name="vaccine_type" id="vaccine_type">
            </div>
            <div class="form-group">
              <label for="quantity">Quantity:</label>
              <input type="text" name="quantity" id="quantity" required>
            </div>
            <div class="form-group" style="text-align: center;">
              <button type="submit" name="update">Update Vaccine</button>
              <button type="button" onclick="hideEditForm()" style="margin-left:10px;">Cancel</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Footer -->
      <footer class="footer">
        &copy; <?php echo date("Y"); ?> Vaccination Management System - Admin Panel
      </footer>
    </div>
  </div>

  <!-- Scripts: jQuery and Bootstrap JS (optional) -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>
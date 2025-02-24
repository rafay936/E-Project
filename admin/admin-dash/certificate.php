<?php
session_start();

// Authentication Check
if (!isset($_SESSION['role'])) {
    header("Location: admin/vms/login.php");
    exit();
}

// Database Connection
$conn = new mysqli('localhost', 'root', '', 'vms_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Certificate Data
$cert_id = isset($_GET['cert_id']) ? intval($_GET['cert_id']) : 0;
$certificate = [];

if ($cert_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM certificates WHERE certificate_id = ?");
    $stmt->bind_param("i", $cert_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $certificate = $result->fetch_assoc();
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Vaccination Certificate</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f9;
      padding: 0;
      margin: 0;
      display: flex;
    }
    .sidebar {
      width: 250px;
      background: #800080;
      color: #fff;
      min-height: 100vh;
      padding: 20px;
    }
    .sidebar .logo {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 20px;
    }
    .nav-links {
      list-style: none;
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
      transition: background 0.3s;
    }
    .nav-links a:hover {
      background: #9932CC;
    }
    .main-content {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }
    .top-navbar {
      background: #800080;
      padding: 10px 20px;
      color: #fff;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .certificate-box {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      max-width: 600px;
      margin: 40px auto;
      text-align: center;
    }
    .certificate-box h1 {
      color: #6C63FF;
      margin-bottom: 20px;
    }
    .certificate-box p {
      font-size: 1.1rem;
      margin: 10px 0;
    }
    .download-btn {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background: #6C63FF;
      color: #fff;
      text-decoration: none;
      border-radius: 4px;
      transition: background 0.3s;
    }
    .download-btn:hover {
      background: #5a52e0;
    }
    footer {
      background: #800080;
      color: #fff;
      text-align: center;
      padding: 10px;
      margin-top: auto;
    }
  </style>
</head>
<body>

<div class="sidebar">
  <div class="logo">Admin Panel</div>
  <ul class="nav-links">
         <li><a href="index.php" ><i class="fe fe-home"></i> Dashboard</a></li>
         <li><a href="hospitals.php"><i class="fe fe-users"></i> Hospitals</a></li>
         <li><a href="appointments.php"><i class="fe fe-star-o"></i> Appointments</a></li>
         <li><a href="patients.php"><i class="fe fe-user"></i> Patients</a></li>
         <li><a href="vaccines.php"><i class="fe fe-layout"></i> Vaccines</a></li>
         <li><a href="Vaccine Requests.php"><i class="fe fe-document"></i> Vaccime Req</a></li>
         <li><a href="/admin/admin-dash/certificate.php?cert_id=2" class="active"><i class="fe fe-document"></i>certificate</a></li>
      </ul>
</div>

<div class="main-content">
  <nav class="top-navbar">
    <h2>Vaccination Certificate</h2>
  </nav>

  <?php if (!empty($certificate)): ?>
    <div class="certificate-box">
      <h1>Vaccination Certificate</h1>
      <p><strong>Name:</strong> <?= htmlspecialchars($certificate['full_name']) ?></p>
      <p><strong>Vaccine:</strong> <?= htmlspecialchars($certificate['vaccine']) ?></p>
      <p><strong>Date:</strong> <?= htmlspecialchars($certificate['vaccination_date']) ?></p>
      <p><strong>Certificate No.:</strong> <?= htmlspecialchars($certificate['certificate_number']) ?></p>
      <a href="certificate_pdf.php?cert_id=<?= $cert_id ?>" class="download-btn">Download PDF</a>
    </div>
  <?php else: ?>
    <div class="certificate-box">
      <p>Certificate not found!</p>
    </div>
  <?php endif; ?>

  <footer>
    &copy; <?= date("Y") ?> Vaccination Management System
  </footer>
</div>

</body>
</html>

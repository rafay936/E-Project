<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin/vms/login.php");
    exit();
}

function getConnection() {
    $host = 'localhost';
    $db   = 'vms_db';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
         PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
         PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
         return new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
         die("Connection failed: " . $e->getMessage());
    }
}

$pdo = getConnection();

// Fetch patients (users with role 'patient')
$stmt = $pdo->query("SELECT user_id, full_name, email, phone, address, created_at FROM users WHERE role = 'patient'");
$patients = $stmt->fetchAll();

// Fetch notifications for patients (joined with user data)
$stmt2 = $pdo->query("SELECT n.notification_id, n.message, n.status, n.created_at, u.full_name 
                       FROM notifications n 
                       JOIN users u ON n.user_id = u.user_id 
                       WHERE u.role = 'patient' 
                       ORDER BY n.created_at DESC");
$notifications = $stmt2->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Panel - Patients</title>
   <link rel="stylesheet" href="style.css">
   <style>
      /* Dark Purple Admin Panel Theme */
      * { margin: 0; padding: 0; box-sizing: border-box; }
      body { font-family: Arial, sans-serif; background-color: #f4f4f9; color: #333; }
      .container { display: flex; }
      .sidebar {
         width: 250px;
         background: #800080;
         color: #fff;
         min-height: 100vh;
         padding: 20px;
      }
      .sidebar .logo { font-size: 24px; font-weight: bold; margin-bottom: 20px; }
      .nav-links { list-style: none; padding: 0; }
      .nav-links li { margin-bottom: 10px; }
      .nav-links a {
         color: #fff;
         text-decoration: none;
         display: block;
         padding: 10px;
         border-radius: 4px;
         transition: background 0.3s;
      }
      .nav-links a:hover, .nav-links a.active { background: #DA70D6; }
      .main-content { flex-grow: 1; display: flex; flex-direction: column; }
      .top-navbar {
         background: #800080;
         padding: 10px 20px;
         border-bottom: 1px solid #5e005e;
         display: flex;
         justify-content: space-between;
         align-items: center;
         color: #fff;
      }
      .content { padding: 20px; width: 100%; max-width: 1200px; margin: 0 auto; }
      h1, h2 { text-align: center; color: #800080; margin-bottom: 20px; }
      table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
      table th, table td { padding: 12px; border: 1px solid #ddd; text-align: left; }
      table th { background: #800080; color: #fff; }
      tr:hover { background: #f1f1f1; }
      .notification-box { margin-bottom: 30px; }
      .notification-box h3 { color: #800080; margin-bottom: 10px; }
      .notification { padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 10px; }
      .notification.unread { background: #e6e6fa; }
      .footer {
         background: #6C63FF;
         color: #fff;
         text-align: center;
         padding: 15px;
         margin-top: auto;
      }
   </style>
</head>
<body>
   <div class="container">
      <!-- Sidebar Navigation -->
      <div class="sidebar">
         <div class="logo">Admin Panel</div>
         <ul class="nav-links">
         <li><a href="index.php" ><i class="fe fe-home"></i> Dashboard</a></li>
         <li><a href="hospitals.php"><i class="fe fe-users"></i> Hospitals</a></li>
         <li><a href="appointments.php"><i class="fe fe-star-o"></i> Appointments</a></li>
         <li><a href="patients.php" class="active"  ><i class="fe fe-user"></i> Patients</a></li>
         <li><a href="vaccines.php"><i class="fe fe-layout"></i> Vaccines</a></li>
         <li><a href="Vaccine Requests.php"><i class="fe fe-document"></i> Vaccime Req</a></li>
         <li><a href="/admin/admin-dash/certificate.php?cert_id=2" ><i class="fe fe-document"></i>certificate</a></li>
      </ul>
      </div>

      <!-- Main Content -->
      <div class="main-content">
         <nav class="top-navbar">
            <h2>Patients</h2>
            <a href="logout.php" style="color: white;">Logout</a>
         </nav>

         <div class="content">
            <h1>Patients List</h1>

            <!-- Notification Section for Patients -->
            <?php if (!empty($notifications)): ?>
              <div class="notification-box">
                <h3>Patient Notifications</h3>
                <?php foreach ($notifications as $notif): ?>
                   <div class="notification <?php echo ($notif['status'] == 'unread') ? 'unread' : ''; ?>">
                     <p><?php echo htmlspecialchars($notif['message']); ?></p>
                     <small><?php echo htmlspecialchars($notif['full_name']); ?> - <?php echo htmlspecialchars($notif['created_at']); ?></small>
                   </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

            <!-- Patients Table -->
            <?php if (!empty($patients)): ?>
              <table>
                 <thead>
                    <tr>
                       <th>User ID</th>
                       <th>Full Name</th>
                       <th>Email</th>
                       <th>Phone</th>
                       <th>Address</th>
                       <th>Registered On</th>
                    </tr>
                 </thead>
                 <tbody>
                    <?php foreach ($patients as $patient): ?>
                       <tr>
                          <td><?php echo htmlspecialchars($patient['user_id']); ?></td>
                          <td><?php echo htmlspecialchars($patient['full_name']); ?></td>
                          <td><?php echo htmlspecialchars($patient['email']); ?></td>
                          <td><?php echo htmlspecialchars($patient['phone']); ?></td>
                          <td><?php echo htmlspecialchars($patient['address']); ?></td>
                          <td><?php echo htmlspecialchars($patient['created_at']); ?></td>
                       </tr>
                    <?php endforeach; ?>
                 </tbody>
              </table>
            <?php else: ?>
              <p style="text-align: center;">No patients found.</p>
            <?php endif; ?>
         </div>

         <footer class="footer">
            &copy; <?php echo date("Y"); ?> Vaccination Management System - Admin Panel
         </footer>
      </div>
   </div>
</body>
</html>

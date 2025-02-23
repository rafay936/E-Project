<?php
session_start();

// Authentication Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /login.php'); // Adjust to your login path
    exit;
}

// Global Database Connection using PDO (default MySQL port)
function getConnection() {
    $host = 'localhost';
    $db   = 'vms_db'; // Your database name
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hospital Vaccination Admin - Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <!-- Inline styles for layout and page-specific styling (Purple Theme) -->
  <style>
    /* Common Layout */
    .container { display: flex; }
    .main-content { flex-grow: 1; }
    .content { padding: 20px; }
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
    .nav-links a:hover { background: #9932CC; }
    .nav-links a.active { background: #DA70D6; color: #fff; }
    .menu-toggle { display: none; }
    .overlay { display: none; }
    .top-navbar { 
        background: #800080; 
        padding: 10px 20px; 
        border-bottom: 1px solid #5e005e; 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        color: #fff; 
    }
    
    /* Dashboard Styles */
    .dashboard { padding: 20px; }
    .dashboard-metrics { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
        gap: 20px; 
        margin-bottom: 40px; 
    }
    .dashboard-metrics .card { 
        background: #fff; 
        padding: 20px; 
        border-radius: 8px; 
        text-align: center; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
    }
    .dashboard-metrics .card h3 { 
        margin-bottom: 10px; 
        font-size: 1.2rem; 
    }
    .dashboard-metrics .card p { 
        font-size: 2rem; 
        margin: 0; 
    }
    .dashboard-charts { 
        background: #fff; 
        padding: 20px; 
        border-radius: 8px; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
    }
    
    /* Vaccination Report & Placeholder Page Styles */
    body { 
        font-family: Arial, sans-serif; 
        background-color: #f4f4f9; 
        color: #333; 
    }
    h1, h2 { 
        text-align: center; 
        color: #800080; 
    }
    form.vaccination-form { 
        background: #fff; 
        margin: 20px auto; 
        padding: 20px; 
        border: 1px solid #ddd; 
        border-radius: 5px; 
        max-width: 500px; 
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); 
    }
    form.vaccination-form label { 
        display: block; 
        margin: 10px 0 5px; 
    }
    form.vaccination-form input,
    form.vaccination-form select,
    form.vaccination-form button { 
        width: 100%; 
        padding: 10px; 
        margin: 5px 0; 
        border: 1px solid #ddd; 
        border-radius: 5px; 
    }
    form.vaccination-form button { 
        background-color: #28a745; 
        color: #fff; 
        border: none; 
        cursor: pointer; 
        transition: background 0.3s, transform 0.2s; 
    }
    form.vaccination-form button:hover { 
        background-color: #218838; 
        transform: scale(1.05); 
    }
    .vaccination-messages p { text-align: center; }
    table.report-table { 
        width: 100%; 
        border-collapse: collapse; 
        margin: 20px auto; 
        background: #fff; 
    }
    table.report-table th, table.report-table td { 
        padding: 10px; 
        border: 1px solid #ddd; 
        text-align: left; 
    }
    table.report-table th { 
        background-color: #800080; 
        color: #fff; 
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="logo">Admin Panel</div>
      <ul class="nav-links">
         <li><a href="index.php" class="active"><i class="fe fe-home"></i> Dashboard</a></li>
         <li><a href="hospitals.php"><i class="fe fe-users"></i> Hospitals</a></li>
         <li><a href="appointments.php"><i class="fe fe-star-o"></i> Appointments</a></li>
         <li><a href="patients.php"><i class="fe fe-user"></i> Patients</a></li>
         <li><a href="vaccines.php"><i class="fe fe-layout"></i> Vaccines</a></li>
         <li><a href="Vaccine Requests.php"><i class="fe fe-document"></i> Vaccime Req</a></li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Top Navbar -->
      <nav class="top-navbar">
        <h2>Dashboard</h2>
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
        <?php
            // --- Dashboard Content ---
            try {
                $conn = getConnection();
                $stmt = $conn->query("SELECT COUNT(*) AS total_hospitals FROM hospitals");
                $hospitalsData = $stmt->fetch();
                $stmt = $conn->query("SELECT COUNT(*) AS total_beds FROM beds");
                $bedsData = $stmt->fetch();
                $stmt = $conn->query("SELECT COUNT(*) AS total_vaccine_requests FROM vaccine_requests");
                $vaccineRequestsData = $stmt->fetch();
                $stmt = $conn->query("SELECT COUNT(*) AS total_users FROM users");
                $usersData = $stmt->fetch();
            } catch (PDOException $e) {
                die("Error fetching data: " . $e->getMessage());
            }
        ?>
        <div class="dashboard">
          <div class="dashboard-metrics">
            <div class="card">
              <h3>Total Hospitals</h3>
              <p><?php echo $hospitalsData['total_hospitals']; ?></p>
            </div>
            <div class="card">
              <h3>Total Beds</h3>
              <p><?php echo $bedsData['total_beds']; ?></p>
            </div>
            <div class="card">
              <h3>Vaccine Requests</h3>
              <p><?php echo $vaccineRequestsData['total_vaccine_requests']; ?></p>
            </div>
            <div class="card">
              <h3>Total Users</h3>
              <p><?php echo $usersData['total_users']; ?></p>
            </div>
          </div>
          <div class="dashboard-charts">
            <canvas id="myChart"></canvas>
          </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
          const ctx = document.getElementById('myChart').getContext('2d');
          const myChart = new Chart(ctx, {
              type: 'bar',
              data: {
                  labels: ['Hospitals', 'Beds', 'Vaccine Requests', 'Users'],
                  datasets: [{
                      label: 'Overview',
                      data: [
                          <?php echo $hospitalsData['total_hospitals']; ?>,
                          <?php echo $bedsData['total_beds']; ?>,
                          <?php echo $vaccineRequestsData['total_vaccine_requests']; ?>,
                          <?php echo $usersData['total_users']; ?>
                      ],
                      backgroundColor: ['#8A2BE2', '#BA55D3', '#DA70D6', '#EE82EE']
                  }]
              },
              options: {
                  responsive: true,
                  scales: {
                      y: { beginAtZero: true }
                  }
              }
          });
        </script>
      </div>

      <!-- Footer -->
      <footer class="footer">
        <!-- Footer Content -->
      </footer>
    </div>
  </div>

  <!-- Scripts -->
  <script src="assets/js/jquery-3.2.1.min.js"></script>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script>
    function toggleSidebar() {
      const sidebar = document.querySelector('.sidebar');
      const overlay = document.querySelector('.overlay');
      sidebar.classList.toggle('active');
      overlay.style.display = sidebar.classList.contains('active') ? 'block' : 'none';
    }
    document.addEventListener('click', function(event) {
      if(window.innerWidth <=768 && !event.target.closest('.sidebar') && !event.target.closest('.menu-toggle')) {
         toggleSidebar();
      }
    });
  </script>
</body>
</html>

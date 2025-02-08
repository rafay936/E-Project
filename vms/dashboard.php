<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>


    <div class="menu-btn">☰</div>

    <!-- Side Navbar -->
    <nav class="sidenav">
        <div class="sidenav-header">
            <h2>🏥 Hospital Dashboard</h2>
            <div class="close-btn"><i class="fas fa-close"></i></div>
        </div>
        <ul class="nav-menu">
            <a href="dashboard.php">
                <li><i class="fas fa-home"></i>Dashboard</li>
            </a>
            <a href="vaccine.php">
                <li><i class="fas fa-syringe"></i>Vaccines</li>
            </a>
            <a href="beds.php">
                <li><i class="fas fa-bed"></i>Beds</li>
            </a>
            <a href="notification.php">
                <li><i class="fas fa-bell"></i>Notifications</li>
            </a>
            <a href="profile.php">
                <li><i class="fas fa-user"></i>Profile</li>
            </a>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="main">
        <nav class="breadcrumb">
            <ol>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ol>
        </nav>
        <!-- Cards -->
        <div class="cards-container">
            <div class="card">
                <i class="fas fa-bed" style="background: #3498db; color: white;"></i>
                <div class="card-content">
                    <h3>Beds Occupied</h3>
                    <p>150/200</p>
                </div>
            </div>
            <div class="card">
                <i class="fas fa-shield-virus" style="background: #2ecc71; color: white;"></i>
                <div class="card-content">
                    <h3>Vaccinated Patients</h3>
                    <p>1,250</p>
                </div>
            </div>
            <div class="card">
                <i class="fas fa-syringe" style="background: #e74c3c; color: white;"></i>
                <div class="card-content">
                    <h3>Vaccine Availability</h3>
                    <p>50 doses</p>
                </div>
            </div>
        </div>

        </div>

        <!-- Chart -->
        <div class="chart-container">
            <canvas id="flowChart"></canvas>
        </div>

        <!-- Table -->
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Ward</th>
                    <th>Beds Available</th>
                    <th>Occupancy</th>
                    <th>Vaccine Stock</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td> General Ward</td>
                    <td>50</td>
                    <td>75%</td>
                    <td>1,200</td>
                </tr>
                <tr>
                    <td>ICU</td>
                    <td>15</td>
                    <td>90%</td>
                    <td>800</td>
                </tr>
                <tr>
                    <td>Pediatrics</td>
                    <td>30</td>
                    <td>60%</td>
                    <td>1,500</td>
                </tr>
            </tbody>
        </table>

        <!-- Footer -->
        <footer>
            &copy;Copy |All Rights Reserved VMS
        </footer>
    </main>

 
    <script src="script.js"></script>
</body>

</html>
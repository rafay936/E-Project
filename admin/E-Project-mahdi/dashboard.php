<?php
session_start(); // Start the session

// Redirect to login if the user is not logged in or is not a hospital
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hospital') {
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$db = 'vms_db';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Fetch hospital data based on user_id
$user_id = $_SESSION['user_id'];
$hospital_query = "SELECT * FROM hospitals WHERE user_id = $user_id";
$hospital_result = mysqli_query($conn, $hospital_query);

if (mysqli_num_rows($hospital_result) > 0) {
    $hospital = mysqli_fetch_assoc($hospital_result);
    $hospital_id = $hospital['hospital_id'];
} else {
    die("No hospital found for user ID: $user_id. Please contact the administrator.");
}

// Fetch bed data
$beds_query = "SELECT * FROM beds WHERE hospital_id = $hospital_id";
$beds_result = mysqli_query($conn, $beds_query);

// Debugging: Check if beds are fetched
if (mysqli_num_rows($beds_result) === 0) {
    echo "No beds found for hospital ID: $hospital_id";
}

// Fetch vaccine data
$vaccines_query = "SELECT * FROM vaccines WHERE hospital_id = $hospital_id";
$vaccines_result = mysqli_query($conn, $vaccines_query);

// Debugging: Check if vaccines are fetched
if (mysqli_num_rows($vaccines_result) === 0) {
    echo "No vaccines found for hospital ID: $hospital_id";
}

// Fetch vaccination records
$vaccination_query = "SELECT COUNT(*) as vaccinated_patients FROM vaccination_records WHERE hospital_id = $hospital_id";
$vaccination_result = mysqli_query($conn, $vaccination_query);
$vaccination_data = mysqli_fetch_assoc($vaccination_result);

// Fetch notifications
$notifications_query = "SELECT * FROM notifications WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 5";
$notifications_result = mysqli_query($conn, $notifications_query);

// Reset the beds result pointer to reuse in the table
mysqli_data_seek($beds_result, 0);

// Reset the vaccines result pointer (if needed)
// mysqli_data_seek($vaccines_result, 0);
?>

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
            <a href="\admin\vms\login.php">
                <li><i class="fas fa-sign-out"></i>Logout</li>
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
                    <p><?php echo mysqli_num_rows($beds_result); ?> beds</p>
                </div>
            </div>
            <div class="card">
                <i class="fas fa-shield-virus" style="background: #2ecc71; color: white;"></i>
                <div class="card-content">
                    <h3>Vaccinated Patients</h3>
                    <p><?php echo $vaccination_data['vaccinated_patients']; ?></p>
                </div>
            </div>
            <div class="card">
                <i class="fas fa-syringe" style="background: #e74c3c; color: white;"></i>
                <div class="card-content">
                    <h3>Vaccine Availability</h3>
                    <p><?php echo mysqli_num_rows($vaccines_result); ?> doses</p>
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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through beds and vaccines together
                mysqli_data_seek($beds_result, 0); // Reset beds pointer again
                mysqli_data_seek($vaccines_result, 0); // Reset vaccines pointer

                $vaccines = mysqli_fetch_all($vaccines_result, MYSQLI_ASSOC);
                $vaccine_index = 0;

                while ($bed = mysqli_fetch_assoc($beds_result)) {
                    $vaccine_stock = isset($vaccines[$vaccine_index]) ? $vaccines[$vaccine_index]['stock_count'] : 0;
                    echo "<tr>
                            <td>{$bed['bed_type']}</td>
                            <td>{$bed['bed_count']}</td>
                            <td>75%</td> <!-- Replace with dynamic occupancy later -->
                            <td>$vaccine_stock</td>
                            <td>";
                    if ($vaccine_stock < 25) {
                        echo "<button onclick=\"requestVaccine()\">Request Vaccine from Admin</button>";
                    }
                    echo "</td>
                          </tr>";
                    $vaccine_index++;
                }
                ?>
            </tbody>
        </table>

        <!-- Notifications -->
        <div class="notifications-container">
            <h3>Notifications</h3>
            <ul>
                <?php
                while ($notification = mysqli_fetch_assoc($notifications_result)) {
                    echo "<li>{$notification['message']} <span class='notification-time'>{$notification['created_at']}</span></li>";
                }
                ?>
            </ul>
        </div>

        <!-- Footer -->
        <footer>
            &copy;Copy |All Rights Reserved VMS
        </footer>
    </main>

    <script>
        // Toggle Sidebar
        const menuBtn = document.querySelector(".menu-btn");
        const sidenav = document.querySelector(".sidenav");
        const closeBtn = document.querySelector(".close-btn");

        menuBtn.addEventListener("click", () => sidenav.classList.toggle("active"));
        closeBtn.addEventListener("click", () => sidenav.classList.remove("active"));

        // Close sidebar when clicking outside on mobile
        document.addEventListener("click", (e) => {
            if (
                window.innerWidth <= 768 &&
                !sidenav.contains(e.target) &&
                !menuBtn.contains(e.target)
            ) {
                sidenav.classList.remove("active");
            }
        });

        // Chart Implementation
        const ctx = document.getElementById("flowChart").getContext("2d");
        new Chart(ctx, {
            type: "line",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                datasets: [
                    {
                        label: "Hospital Occupancy",
                        data: [65, 59, 80, 81, 56, 55], // Replace with dynamic data
                        borderColor: "#3498db",
                        tension: 0.4,
                    },
                    {
                        label: "Vaccine Availability",
                        data: [28, 48, 40, 19, 86, 27], // Replace with dynamic data
                        borderColor: "#2ecc71",
                        tension: 0.4,
                    },
                    {
                        label: "Beds Availability",
                        data: [35, 25, 45, 65, 30, 70], // Replace with dynamic data
                        borderColor: "#e74c3c",
                        tension: 0.4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            },
        });

        // Request Vaccine Function
        function requestVaccine() {
            // You can add an AJAX call here to send a request to the server
            alert("Vaccine request sent to admin.");
            // Example AJAX call:
            // fetch('request_vaccine.php', {
            //     method: 'POST',
            //     headers: {
            //         'Content-Type': 'application/json'
            //     },
            //     body: JSON.stringify({ hospital_id: <?php echo $hospital_id; ?> })
            // }).then(response => response.json())
            //   .then(data => {
            //       if (data.success) {
            //           alert("Vaccine request sent successfully.");
            //       } else {
            //           alert("Failed to send vaccine request.");
            //       }
            //   });
        }
    </script>
</body>
</html>
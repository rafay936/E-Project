<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$db   = 'vms_db';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Process appointment request form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id      = $_SESSION['user_id'];
    $request_type = $conn->real_escape_string($_POST['request_type']); // "vaccine" or "bed"
    $hospital_id  = $conn->real_escape_string($_POST['hospital_id']);
    
    if ($request_type === 'vaccine') {
        $vaccine_id = $conn->real_escape_string($_POST['vaccine_id']);
        $insert_query = "INSERT INTO requests (user_id, hospital_id, vaccine_id, bed_id, status, request_type) 
                         VALUES ('$user_id', '$hospital_id', '$vaccine_id', NULL, 'pending', '$request_type')";
    } elseif ($request_type === 'bed') {
        $bed_id = $conn->real_escape_string($_POST['bed_id']);
        $insert_query = "INSERT INTO requests (user_id, hospital_id, vaccine_id, bed_id, status, request_type) 
                         VALUES ('$user_id', '$hospital_id', NULL, '$bed_id', 'pending', '$request_type')";
    }

    if (isset($insert_query) && $conn->query($insert_query)) {
        // Get last inserted request ID
        $request_id = $conn->insert_id;

        // Fetch the hospital's user ID (so the hospital receives the notification)
        $hospital_user_query = "SELECT user_id FROM hospitals WHERE hospital_id = '$hospital_id'";
        $hospital_user_result = $conn->query($hospital_user_query);

        if ($hospital_user_result && $hospital_user_result->num_rows > 0) {
            $hospital_user = $hospital_user_result->fetch_assoc();
            $hospital_user_id = $hospital_user['user_id'];

            // Insert notification for the hospital
            $notification_query = "INSERT INTO notifications (user_id, message, status, created_at) 
                                   VALUES ('$hospital_user_id', 'New appointment request (ID: $request_id) pending approval.', 'unread', NOW())";
            $conn->query($notification_query);
        }

        $message = "Appointment scheduled successfully! Your appointment is pending approval.";
    } else {
        $message = "Error scheduling appointment: " . $conn->error;
    }
}

// Fetch available hospitals
$hospitals = [];
$resultHospitals = $conn->query("SELECT hospital_id, hospital_name FROM hospitals");
if ($resultHospitals) {
    while ($row = $resultHospitals->fetch_assoc()) {
        $hospitals[] = $row;
    }
}

// Fetch available vaccines
$vaccines = [];
$resultVaccines = $conn->query("SELECT vaccine_id, vaccine_name FROM vaccines");
if ($resultVaccines) {
    while ($row = $resultVaccines->fetch_assoc()) {
        $vaccines[] = $row;
    }
}

// Fetch available beds
$beds = [];
$resultBeds = $conn->query("SELECT bed_id, bed_type FROM beds");
if ($resultBeds) {
    while ($row = $resultBeds->fetch_assoc()) {
        $beds[] = $row;
    }
}

// Fetch all appointment requests
$requests = [];
$query = "SELECT r.request_id, r.request_type, r.status, r.requested_at,
                 h.hospital_name,
                 v.vaccine_name,
                 b.bed_type
          FROM requests r
          LEFT JOIN hospitals h ON r.hospital_id = h.hospital_id
          LEFT JOIN vaccines v ON r.vaccine_id = v.vaccine_id
          LEFT JOIN beds b ON r.bed_id = b.bed_id
          ORDER BY r.requested_at DESC";
$resultRequests = $conn->query($query);
if ($resultRequests) {
    while ($row = $resultRequests->fetch_assoc()) {
        $requests[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Appointment</title>
    <link rel="stylesheet" href="web.css">
</head>
<body>
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
    
    <!-- Notification Popup -->
    <?php if (!empty($message)): ?>
        <div class="notification" id="notification">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <script>
            document.getElementById('notification').style.display = 'block';
            setTimeout(function(){
                document.getElementById('notification').style.display = 'none';
            }, 5000);
        </script>
    <?php endif; ?>
    
    <section class="schedule">
        <h1>Book Your Appointment</h1>
        <form method="post" action="schedule.php">
            <label for="request_type">Request Type:</label>
            <select id="request_type" name="request_type" required>
                <option value="">--Choose Request Type--</option>
                <option value="vaccine">Vaccine</option>
                <option value="bed">Bed</option>
            </select>
            
            <label for="hospital_id">Select Hospital:</label>
            <select id="hospital_id" name="hospital_id" required>
                <option value="">--Choose Hospital--</option>
                <?php foreach ($hospitals as $h): ?>
                    <option value="<?php echo htmlspecialchars($h['hospital_id']); ?>">
                        <?php echo htmlspecialchars($h['hospital_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <button type="submit">Schedule Appointment</button>
        </form>
    </section>
    
    <section class="appointments">
        <h2>Scheduled Appointments</h2>
        <table>
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Request Type</th>
                    <th>Hospital</th>
                    <th>Status</th>
                    <th>Requested At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($requests)): ?>
                    <?php foreach ($requests as $req): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($req['request_id']); ?></td>
                            <td><?php echo htmlspecialchars($req['request_type']); ?></td>
                            <td><?php echo htmlspecialchars($req['hospital_name']); ?></td>
                            <td><?php echo htmlspecialchars($req['status']); ?></td>
                            <td><?php echo htmlspecialchars($req['requested_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No appointments scheduled.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <script src="script.js"></script>
</body>
</html>

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// If the role is not 'hospital', redirect to a valid dashboard or error page
if ($_SESSION['role'] !== 'hospital') {
    header("Location: dashboard.php"); // Or another valid page
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

// Fetch hospital data
$user_id = $_SESSION['user_id'];
$hospital_result = mysqli_query($conn, "SELECT * FROM hospitals WHERE user_id = $user_id");
if (mysqli_num_rows($hospital_result) > 0) {
    $hospital = mysqli_fetch_assoc($hospital_result);
    $hospital_id = $hospital['hospital_id'];
} else {
    die("Hospital not found.");
}

// Handle CRUD Operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_bed'])) {
        $bed_type = $_POST['bed_type'];
        $bed_count = $_POST['bed_count'];
        $stmt = $conn->prepare("INSERT INTO beds (hospital_id, bed_type, bed_count) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $hospital_id, $bed_type, $bed_count);
        $stmt->execute();
    } elseif (isset($_POST['update_bed'])) {
        $bed_id = $_POST['bed_id'];
        $bed_type = $_POST['bed_type'];
        $bed_count = $_POST['bed_count'];
        $stmt = $conn->prepare("UPDATE beds SET bed_type=?, bed_count=? WHERE bed_id=?");
        $stmt->bind_param("sii", $bed_type, $bed_count, $bed_id);
        $stmt->execute();
    } elseif (isset($_POST['delete_bed'])) {
        $bed_id = $_POST['bed_id'];
        $stmt = $conn->prepare("DELETE FROM beds WHERE bed_id=?");
        $stmt->bind_param("i", $bed_id);
        $stmt->execute();
    }
    header("Location: beds.php");
    exit();
}

// Fetch beds data
$beds_result = mysqli_query($conn, "SELECT * FROM beds WHERE hospital_id = $hospital_id");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital beds page</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">

    <style>
        .status {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .vaccinated {
            background-color: rgba(40, 167, 70, 0.84);
            color: white;
        }

        .pending {
            background-color: rgba(255, 234, 7, 0.8);
            color: black;
        }

        table {
            width: 90%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: rgba(88, 80, 207, 0.669);
            color: white;
        }

        tr:hover {
            background: #f1f1f1;
        }

        @media screen (max-width: 600px) {
            .num {
                display: wrap;
            }

            ;

            table {
                width: 100%;
                padding: 0
            }

            ;
        }
    </style>
</head>

<body>
    <div class="menu-btn">☰</div>

    <!-- Side Navbar -->
    <nav class="sidenav">
        <div class="sidenav-header">
            <h2>🏥 Hospital Dashboard</h2>
            <div class="close-btn">×</div>
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
        <div class="container">
            <h1>Bed Availability</h1>

            <div class="button-container">
                <div class="row-one">
                    <button class="btn" id="addBedBtn">Add Bed</button>
                </div>
            </div>

            <!-- Modal for Add/Edit Bed -->
            <div class="modal-overlay" id="bedModal">
                <div class="modal-content">
                    <form id="bedForm" method="POST">
                        <input type="hidden" id="bedId" name="bed_id">
                        <div class="form-group">
                            <label>Bed Type</label>
                            <input type="text" id="bedType" name="bed_type" required>
                        </div>
                        <div class="form-group">
                            <label>Bed Count</label>
                            <input type="number" id="bedCount" name="bed_count" required>
                        </div>
                        <button type="submit" class="btn" id="submitBedBtn">Submit</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="container">
            <table>
                <thead>
                    <tr>
                        <th>Bed ID</th>
                        <th>Bed Type</th>
                        <th>Bed Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($bed = mysqli_fetch_assoc($beds_result)) {
                        echo "<tr>
                                <td>{$bed['bed_id']}</td>
                                <td>{$bed['bed_type']}</td>
                                <td>{$bed['bed_count']}</td>
                                <td>
                                    <button class='btn editBtn' data-id='{$bed['bed_id']}' data-type='{$bed['bed_type']}' data-count='{$bed['bed_count']}'>Edit</button>
                                    <form method='POST' style='display:inline;'>
                                        <input type='hidden' name='bed_id' value='{$bed['bed_id']}'>
                                        <button type='submit' name='delete_bed' class='btn deleteBtn'>Delete</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Footer -->
            <footer>
                <p>&copy;Copy |All Rights Reserved VMS</p>
            </footer>
    </main>

    <script>
        // Modal Handling
        const addBedBtn = document.getElementById('addBedBtn');
        const bedModal = document.getElementById('bedModal');
        const bedForm = document.getElementById('bedForm');
        const bedIdInput = document.getElementById('bedId');
        const bedTypeInput = document.getElementById('bedType');
        const bedCountInput = document.getElementById('bedCount');
        const submitBedBtn = document.getElementById('submitBedBtn');

        addBedBtn.addEventListener('click', () => {
            bedIdInput.value = '';
            bedTypeInput.value = '';
            bedCountInput.value = '';
            bedModal.style.display = 'flex';
        });

        bedModal.addEventListener('click', (e) => {
            if (e.target === bedModal) {
                bedModal.style.display = 'none';
            }
        });

        // Edit Button Handling
        document.querySelectorAll('.editBtn').forEach(button => {
            button.addEventListener('click', () => {
                bedIdInput.value = button.getAttribute('data-id');
                bedTypeInput.value = button.getAttribute('data-type');
                bedCountInput.value = button.getAttribute('data-count');
                bedModal.style.display = 'flex';
            });
        });

        // Form Submission
        bedForm.addEventListener('submit', (e) => {
            if (bedIdInput.value) {
                bedForm.action = 'beds.php';
                bedForm.method = 'POST';
                bedForm.innerHTML += '<input type="hidden" name="update_bed" value="1">';
            } else {
                bedForm.action = 'beds.php';
                bedForm.method = 'POST';
                bedForm.innerHTML += '<input type="hidden" name="add_bed" value="1">';
            }
        });

        // In the JavaScript section
bedForm.addEventListener('submit', (e) => {
    if (bedIdInput.value) {
        // Add hidden input for update action
        const updateInput = document.createElement('input');
        updateInput.type = 'hidden';
        updateInput.name = 'update_bed';
        updateInput.value = '1';
        bedForm.appendChild(updateInput);
    } else {
        // Add hidden input for add action
        const addInput = document.createElement('input');
        addInput.type = 'hidden';
        addInput.name = 'add_bed';
        addInput.value = '1';
        bedForm.appendChild(addInput);
    }
});
    </script>

    <script src="script.js"></script>
</body>

</html>
<?php
session_start();

// Authentication Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /login.php'); // Adjust to your login path
    exit;
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

$conn = getConnection();

// Fetch all hospitals – assume primary key column is named "hospital_id"
$stmt = $conn->query("SELECT * FROM hospitals");
$hospitals = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hospital Vaccination Admin - Hospitals</title>
  <link rel="stylesheet" href="style.css">
  <!-- Include Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
    .sidebar .logo { font-size: 24px; font-weight: bold; margin-bottom: 20px; }
    .nav-links { list-style: none; padding: 0; }
    .nav-links li { margin-bottom: 10px; }
    .nav-links a { 
      color: #fff; text-decoration: none; display: block; padding: 10px; border-radius: 4px; 
      transition: background 0.3s; 
    }
    .nav-links a:hover { background: #9932CC; }
    .nav-links a.active { background: #DA70D6; color: #fff; }
    .top-navbar { background: #800080; padding: 10px 20px; border-bottom: 1px solid #5e005e; display: flex; justify-content: space-between; align-items: center; color: #fff; }
    
    /* Table & Button Styles */
    table.report-table { width: 100%; border-collapse: collapse; margin: 20px auto; background: #fff; }
    table.report-table th, table.report-table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
    table.report-table th { background-color: #800080; color: #fff; }
    
    /* Custom Button Theme */
    .btn-theme {
      background-color: #800080;
      color: #fff;
      border: none;
      padding: 5px 10px;
      border-radius: 4px;
      font-size: 0.9rem;
      margin-top: 5px;
    }
    .btn-theme:hover {
      background-color: #9932CC;
      color: #fff;
    }
    
    body { font-family: Arial, sans-serif; background-color: #f4f4f9; color: #333; }
    h1, h2 { text-align: center; color: #800080; }
  </style>
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="logo">Admin Panel</div>
      <ul class="nav-links">
         <li><a href="index.php"><i class="fe fe-home"></i> Dashboard</a></li>
         <li><a href="hospitals.php" class="active"><i class="fe fe-users"></i> Hospitals</a></li>
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
        <h2>Hospitals</h2>
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
        <h1>Hospitals</h1>
        <table class="report-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Hospital Name</th>
              <th>Address</th>
              <th>Available Beds</th>
              <th>Available Vaccines</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($hospitals as $hospital): ?>
            <?php
              // Use the hospital's primary key (adjust if your column name is different)
              $hospital_id = $hospital['hospital_id'];
              
              // Fetch available beds for the current hospital
              $stmtBeds = $conn->prepare("SELECT COUNT(*) AS available_beds FROM beds WHERE hospital_id = ?");
              $stmtBeds->execute([$hospital_id]);
              $bedsData = $stmtBeds->fetch();
              $availableBeds = $bedsData ? $bedsData['available_beds'] : 0;
              
              // Fetch available vaccines for the current hospital
              $stmtVaccines = $conn->prepare("SELECT SUM(quantity) AS available_vaccines FROM vaccines WHERE hospital_id = ?");
              $stmtVaccines->execute([$hospital_id]);
              $vaccineData = $stmtVaccines->fetch();
              $availableVaccines = $vaccineData && $vaccineData['available_vaccines'] !== null ? $vaccineData['available_vaccines'] : 0;
            ?>
            <tr>
              <td><?php echo htmlspecialchars($hospital_id); ?></td>
              <td><?php echo htmlspecialchars($hospital['hospital_name']); ?></td>
              <td><?php echo htmlspecialchars($hospital['address']); ?></td>
              <td>
                <?php echo $availableBeds; ?>
                <?php if($availableBeds < 15): ?>
                  <br>
                  <button class="btn btn-sm btn-theme" onclick="openAssignBedsModal('<?php echo $hospital_id; ?>')">Add/Assign Beds</button>
                <?php endif; ?>
              </td>
              <td>
                <?php echo $availableVaccines; ?>
                <?php if($availableVaccines < 15): ?>
                  <br>
                  <button class="btn btn-sm btn-theme" onclick="openAssignVaccinesModal('<?php echo $hospital_id; ?>')">Add/Assign Vaccines</button>
                <?php endif; ?>
              </td>
              <td>
                <button class="btn btn-sm btn-theme" onclick="openEditModal('<?php echo $hospital_id; ?>', '<?php echo addslashes($hospital['hospital_name']); ?>', '<?php echo addslashes($hospital['address']); ?>')">Edit</button>
                <a href="delete_hospital.php?hospital_id=<?php echo $hospital_id; ?>" class="btn btn-sm btn-theme" onclick="return confirm('Are you sure you want to delete this hospital?');">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Footer -->
      <footer class="footer">
        <!-- Footer Content -->
      </footer>
    </div>
  </div>

  <!-- Edit Hospital Modal -->
  <div class="modal fade" id="editHospitalModal" tabindex="-1" role="dialog" aria-labelledby="editHospitalModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="process_edit_hospital.php" method="post" id="editHospitalForm">
        <div class="modal-content">
          <div class="modal-header" style="background-color:#800080; color:#fff;">
            <h5 class="modal-title" id="editHospitalModalLabel">Edit Hospital</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="hospital_id" id="editHospitalId">
            <div class="form-group">
              <label for="editHospitalName">Hospital Name</label>
              <input type="text" class="form-control" name="hospital_name" id="editHospitalName" required>
            </div>
            <div class="form-group">
              <label for="editHospitalAddress">Address</label>
              <input type="text" class="form-control" name="address" id="editHospitalAddress" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-theme" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-theme">Save Changes</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Assign Beds Modal -->
  <div class="modal fade" id="assignBedsModal" tabindex="-1" role="dialog" aria-labelledby="assignBedsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="process_assign_beds.php" method="post" id="assignBedsForm">
        <div class="modal-content">
          <div class="modal-header" style="background-color:#800080; color:#fff;">
            <h5 class="modal-title" id="assignBedsModalLabel">Add/Assign Beds</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
               <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="hospital_id" id="assignBedsHospitalId">
            <div class="form-group">
              <label for="bedsToAdd">Number of Beds to Add</label>
              <input type="number" class="form-control" name="beds_to_add" id="bedsToAdd" min="1" required>
            </div>
          </div>
          <div class="modal-footer">
             <button type="button" class="btn btn-theme" data-dismiss="modal">Cancel</button>
             <button type="submit" class="btn btn-theme">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Assign Vaccines Modal -->
  <div class="modal fade" id="assignVaccinesModal" tabindex="-1" role="dialog" aria-labelledby="assignVaccinesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="process_assign_vaccines.php" method="post" id="assignVaccinesForm">
        <div class="modal-content">
          <div class="modal-header" style="background-color:#800080; color:#fff;">
            <h5 class="modal-title" id="assignVaccinesModalLabel">Add/Assign Vaccines</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
               <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="hospital_id" id="assignVaccinesHospitalId">
            <div class="form-group">
              <label for="vaccinesToAdd">Number of Vaccines to Add</label>
              <input type="number" class="form-control" name="vaccines_to_add" id="vaccinesToAdd" min="1" required>
            </div>
          </div>
          <div class="modal-footer">
             <button type="button" class="btn btn-theme" data-dismiss="modal">Cancel</button>
             <button type="submit" class="btn btn-theme">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Scripts: jQuery and Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
  <script>
    function openEditModal(hospitalId, hospitalName, address) {
        $('#editHospitalId').val(hospitalId);
        $('#editHospitalName').val(hospitalName);
        $('#editHospitalAddress').val(address);
        $('#editHospitalModal').modal('show');
    }
    function openAssignBedsModal(hospitalId) {
        $('#assignBedsHospitalId').val(hospitalId);
        $('#assignBedsModal').modal('show');
    }
    function openAssignVaccinesModal(hospitalId) {
        $('#assignVaccinesHospitalId').val(hospitalId);
        $('#assignVaccinesModal').modal('show');
    }
  </script>
</body>
</html>

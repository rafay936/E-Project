<?php
session_start();

$host = 'localhost';
$db   = 'vms_db';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
}

// Retrieve user role from session
// Make sure you store the user's role in the session when they log in.
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// Process POST requests based on the "action" field.
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action === 'request') {
        // Process vaccine request
        if (isset($_POST['vaccine_name'], $_POST['quantity'])) {
            $vaccine_id = isset($_POST['vaccine_id']) ? intval($_POST['vaccine_id']) : 0;
            $vaccine_name = $conn->real_escape_string($_POST['vaccine_name']);
            $quantity = intval($_POST['quantity']);
            
            // Insert into vaccine_requests table
            $stmt = $conn->prepare("INSERT INTO vaccine_requests (requester_name, vaccine_name, quantity, request_date, status) VALUES (?, ?, ?, CURDATE(), 'Pending')");
            $requester_name = "Hospital ID " . ($_SESSION['hospital_id'] ?? 'Unknown'); 
            $stmt->bind_param("ssi", $requester_name, $vaccine_name, $quantity);
            
            if ($stmt->execute()) {
               // Insert notification for admin using user_id = 5
               $notification_message = "New request for $quantity doses of $vaccine_name.";
               $stmt2 = $conn->prepare("INSERT INTO notifications (user_id, message, status) VALUES (5, ?, 'unread')");
               $stmt2->bind_param("s", $notification_message);
               $stmt2->execute();
               $stmt2->close();
               echo "Request Sent Successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
        $conn->close();
        exit();
        
    } elseif ($action === 'edit') {
        // Process editing a vaccine record
        if (isset($_POST['vaccine_id'], $_POST['vaccine_name'], $_POST['vaccine_type'], $_POST['available_stock'])) {
            $vaccine_id = intval($_POST['vaccine_id']);
            $vaccine_name = $conn->real_escape_string($_POST['vaccine_name']);
            $vaccine_type = $conn->real_escape_string($_POST['vaccine_type']);
            // Note: Change available_stock to stock_count as per your table structure.
            $stock_count = intval($_POST['available_stock']);
            
            $stmt = $conn->prepare("UPDATE vaccines SET vaccine_name=?, vaccine_type=?, stock_count=?, updated_at=NOW() WHERE vaccine_id=?");
            $stmt->bind_param("ssii", $vaccine_name, $vaccine_type, $stock_count, $vaccine_id);
            if ($stmt->execute()) {
                echo "Vaccine Updated Successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
        $conn->close();
        exit();
        
    } elseif ($action === 'delete') {
        // Process deletion of a vaccine record (admin only)
        // If you want to remove delete functionality for everyone, remove this entire block.
        if ($user_role === 'admin') {
            if (isset($_POST['vaccine_id'])) {
                $vaccine_id = intval($_POST['vaccine_id']);
                $stmt = $conn->prepare("DELETE FROM vaccines WHERE vaccine_id=?");
                $stmt->bind_param("i", $vaccine_id);
                if ($stmt->execute()) {
                    echo "Vaccine Deleted Successfully!";
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
            }
        } else {
            echo "Error: Only admins can delete vaccines.";
        }
        $conn->close();
        exit();
    }
}

// Fetch vaccine inventory data from the vaccines table for display
$result = $conn->query("SELECT * FROM vaccines ORDER BY vaccine_id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hospital Vaccine Page</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <style>
    /* Basic CSS for the modals */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }
    .modal-content {
        background: white;
        padding: 20px;
        border-radius: 8px;
        width: 300px;
    }
    .close-btn {
        cursor: pointer;
        float: right;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }
    th {
        background-color: #4CAF50;
        color: white;
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
          <a href="dashboard.php"><li><i class="fas fa-home"></i>Dashboard</li></a>
          <a href="vaccine.php"><li><i class="fas fa-syringe"></i>Vaccines</li></a>
          <a href="beds.php"><li><i class="fas fa-bed"></i>Beds</li></a>
          <a href="notification.php"><li><i class="fas fa-bell"></i>Notifications</li></a>
          <a href="profile.php"><li><i class="fas fa-user"></i>Profile</li></a>
      </ul>
  </nav>
  <!-- Main Content -->
  <main class="main">
      <nav class="breadcrumb">
          <ol>
              <li><a href="dashboard.php">Dashboard</a></li>
              <li><a href="vaccine.php">Vaccines</a></li>
          </ol>
      </nav>
      <div class="container">
          <h1>Vaccines</h1>
          <div class="button-container">
              <div class="row-one">
                  <select class="btn" id="vaccineType">
                      <option value="">Select Vaccine Type</option>
                      <option value="covishield">Covishield</option>
                      <option value="covaxin">Covaxin</option>
                      <option value="sputnik">Sputnik V</option>
                  </select>
                  <button class="btn" id="injectBtn">Inject</button>
              </div>
          </div>
          <!-- Vaccine Inventory Table -->
          <table>
              <thead>
                  <tr>
                      <th>#ID</th>
                      <th>Vaccine Name</th>
                      <th>Type</th>
                      <th>Stock Count</th>
                      <th>Hospital ID</th>
                      <th>Created At</th>
                      <th>Updated At</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                  <?php 
                  if ($result && $result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                          echo "<tr>";
                          echo "<td>" . $row['vaccine_id'] . "</td>";
                          echo "<td>" . $row['vaccine_name'] . "</td>";
                          echo "<td>" . $row['vaccine_type'] . "</td>";
                          echo "<td>" . $row['stock_count'] . "</td>";
                          echo "<td>" . $row['hospital_id'] . "</td>";
                          echo "<td>" . $row['created_at'] . "</td>";
                          echo "<td>" . $row['updated_at'] . "</td>";
                          
                          // Show edit button for all, but delete button only if user is admin
                          echo "<td>";
                          echo "<button class='btn editBtn' 
                                  data-id='" . $row['vaccine_id'] . "' 
                                  data-name='" . $row['vaccine_name'] . "' 
                                  data-type='" . $row['vaccine_type'] . "' 
                                  data-stock='" . $row['stock_count'] . "'>
                                  Edit
                                </button>";
                          
                          // If you want to remove the delete button for everyone, remove this IF block entirely
                          if ($user_role === 'admin') {
                              echo "<button class='btn deleteBtn' data-id='" . $row['vaccine_id'] . "'>Delete</button>";
                          }
                          
                          echo "</td>";
                          echo "</tr>";
                      }
                  } else {
                      echo "<tr><td colspan='8'>No records found.</td></tr>";
                  }
                  $conn->close();
                  ?>
              </tbody>
          </table>
          <!-- Request Form Modal (for Vaccine Requests) -->
          <div class="modal-overlay" id="requestForm">
              <div class="modal-content">
                  <form id="requestVaccineForm">
                      <span class="close-btn" onclick="closeRequestForm()"><i class="fas fa-close"></i></span>
                      <h2>Request More Vaccines</h2>
                      <input type="hidden" name="vaccine_id" id="request_vaccine_id">
                      <div class="form-group">
                          <label>Vaccine Name:</label>
                          <input type="text" name="vaccine_name" id="request_vaccine_name" readonly>
                      </div>
                      <div class="form-group">
                          <label>Quantity Needed:</label>
                          <input type="number" name="quantity" required min="1">
                      </div>
                      <input type="hidden" name="action" value="request">
                      <button type="submit" class="btn">Send Request</button>
                  </form>
              </div>
          </div>
          <!-- Edit Vaccine Modal -->
          <div class="modal-overlay" id="editForm">
              <div class="modal-content">
                  <form id="editVaccineForm">
                      <span class="close-btn" onclick="closeEditForm()"><i class="fas fa-close"></i></span>
                      <h2>Edit Vaccine</h2>
                      <input type="hidden" name="vaccine_id" id="edit_vaccine_id">
                      <div class="form-group">
                          <label>Vaccine Name:</label>
                          <input type="text" name="vaccine_name" id="edit_vaccine_name" required>
                      </div>
                      <div class="form-group">
                          <label>Vaccine Type:</label>
                          <input type="text" name="vaccine_type" id="edit_vaccine_type" required>
                      </div>
                      <div class="form-group">
                          <label>Stock Count:</label>
                          <input type="number" name="available_stock" id="edit_available_stock" required min="0">
                      </div>
                      <input type="hidden" name="action" value="edit">
                      <button type="submit" class="btn">Update Vaccine</button>
                  </form>
              </div>
          </div>
      </div>
      <footer>
          <p>&copy; Copy | All Rights Reserved VMS</p>
      </footer>
  </main>
  <script>
      // Open the Request Form when injectBtn is clicked
      document.getElementById('injectBtn').addEventListener('click', function() {
          const vaccineTypeSelect = document.getElementById('vaccineType');
          const selectedVaccine = vaccineTypeSelect.value;
          if (!selectedVaccine) {
              alert("Please select a vaccine type.");
              return;
          }
          document.getElementById('request_vaccine_id').value = ''; // Set if you have an ID
          document.getElementById('request_vaccine_name').value = selectedVaccine;
          document.getElementById('requestForm').style.display = 'flex';
      });
      // Close Request Form
      function closeRequestForm() {
          document.getElementById('requestForm').style.display = 'none';
      }
      // Handle Vaccine Request Form Submission
      document.getElementById('requestVaccineForm').addEventListener('submit', function(e) {
          e.preventDefault();
          const formData = new FormData(this);
          fetch('vaccine.php', {
              method: 'POST',
              body: formData
          })
          .then(response => response.text())
          .then(data => {
              alert(data);
              closeRequestForm();
              location.reload();
          })
          .catch(error => {
              alert('Error sending request: ' + error);
          });
      });
      // Open Edit Form when edit button is clicked
      document.querySelectorAll('.editBtn').forEach(button => {
          button.addEventListener('click', function() {
              document.getElementById('edit_vaccine_id').value = this.dataset.id;
              document.getElementById('edit_vaccine_name').value = this.dataset.name;
              document.getElementById('edit_vaccine_type').value = this.dataset.type;
              document.getElementById('edit_available_stock').value = this.dataset.stock;
              document.getElementById('editForm').style.display = 'flex';
          });
      });
      // Close Edit Form
      function closeEditForm() {
          document.getElementById('editForm').style.display = 'none';
      }
      // Handle Edit Vaccine Form Submission
      document.getElementById('editVaccineForm').addEventListener('submit', function(e) {
          e.preventDefault();
          const formData = new FormData(this);
          fetch('vaccine.php', {
              method: 'POST',
              body: formData
          })
          .then(response => response.text())
          .then(data => {
              alert(data);
              closeEditForm();
              location.reload();
          })
          .catch(error => {
              alert('Error updating vaccine: ' + error);
          });
      });
      // Handle Delete Vaccine (admin only)
      document.querySelectorAll('.deleteBtn').forEach(button => {
          button.addEventListener('click', function() {
              if (confirm("Are you sure you want to delete this vaccine?")) {
                  const vaccineId = this.dataset.id;
                  const formData = new FormData();
                  formData.append('vaccine_id', vaccineId);
                  formData.append('action', 'delete');
                  fetch('vaccine.php', {
                      method: 'POST',
                      body: formData
                  })
                  .then(response => response.text())
                  .then(data => {
                      alert(data);
                      location.reload();
                  })
                  .catch(error => {
                      alert('Error deleting vaccine: ' + error);
                  });
              }
          });
      });
  </script>
</body>
</html>

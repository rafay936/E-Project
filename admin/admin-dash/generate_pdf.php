<?php
session_start();

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

if (!isset($_GET['request_id'])) {
    die("Invalid request.");
}

$request_id = intval($_GET['request_id']);

// Fetch request details
$query = "SELECT r.request_id, r.request_type, r.status, r.requested_at, r.approved_at, r.rejected_at,
                 h.hospital_name, v.vaccine_name, b.bed_type
          FROM requests r
          LEFT JOIN hospitals h ON r.hospital_id = h.hospital_id
          LEFT JOIN vaccines v ON r.vaccine_id = v.vaccine_id
          LEFT JOIN beds b ON r.bed_id = b.bed_id
          WHERE r.request_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("No record found for request ID " . htmlspecialchars($request_id));
}

$record = $result->fetch_assoc();
$conn->close();

// Prevent any output before headers
ob_start();

// Generate plain text report
$reportContent = "Appointment Report\n";
$reportContent .= "==================\n";
$reportContent .= "Request ID: " . $record['request_id'] . "\n";
$reportContent .= "Request Type: " . $record['request_type'] . "\n";
$reportContent .= "Hospital: " . $record['hospital_name'] . "\n";
$reportContent .= "Vaccine / Bed: " . ($record['request_type'] === 'vaccine' ? $record['vaccine_name'] : $record['bed_type']) . "\n";
$reportContent .= "Status: " . $record['status'] . "\n";
$reportContent .= "Requested At: " . $record['requested_at'] . "\n";
if (!empty($record['approved_at'])) {
    $reportContent .= "Approved At: " . $record['approved_at'] . "\n";
}
if (!empty($record['rejected_at'])) {
    $reportContent .= "Rejected At: " . $record['rejected_at'] . "\n";
}

// Clear buffer before sending headers
ob_clean();

// Send headers to force download
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="report_request_' . $record['request_id'] . '.txt"');
header('Content-Length: ' . strlen($reportContent));
echo $reportContent;
exit();

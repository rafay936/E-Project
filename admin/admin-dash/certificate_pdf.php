<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection using mysqli
$host = 'localhost';
$db   = 'vms_db';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get and validate certificate ID from GET
$cert_id = isset($_GET['cert_id']) ? intval($_GET['cert_id']) : 0;
if ($cert_id <= 0) {
    die("Invalid Certificate ID!");
}

// Use a query that fetches the columns we need. Note: we alias patient_name to full_name.
$query = "SELECT patient_name AS full_name, vaccine, vaccination_date, certificate_number FROM certificates WHERE certificate_id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $cert_id);
$stmt->execute();
$result = $stmt->get_result();
$certificate = $result->fetch_assoc();

if (!$certificate) {
    die("Certificate not found! Cert ID: " . $cert_id);
}

$stmt->close();
$conn->close();

// Define FPDF_FONTPATH if not already defined
if (!defined('FPDF_FONTPATH')) {
    // Change the path below if your "font" folder is located elsewhere.
    define('FPDF_FONTPATH', __DIR__ . '/font/');
}

// Include FPDF only once
require_once('fpdf.php');

// Define a PDF class that extends FPDF
if (!class_exists('PDF')) {
    class PDF extends FPDF {
        function Header() {
            $this->SetFont('Arial', 'B', 16);
            $this->Cell(0, 10, 'Vaccination Certificate', 0, 1, 'C');
            $this->Ln(10);
        }
        
        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Hospital Vaccination Admin - ' . date("Y"), 0, 0, 'C');
        }
    }
}

// Create PDF and output certificate details
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

$pdf->Cell(0, 10, 'Name: ' . $certificate['full_name'], 0, 1);
$pdf->Cell(0, 10, 'Vaccine: ' . $certificate['vaccine'], 0, 1);
$pdf->Cell(0, 10, 'Date of Vaccination: ' . $certificate['vaccination_date'], 0, 1);
$pdf->Cell(0, 10, 'Certificate Number: ' . $certificate['certificate_number'], 0, 1);

$pdf->Output();
exit();
?>

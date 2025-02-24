<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /login.php');
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hospital_id   = $_POST['hospital_id'] ?? null;
    $hospital_name = $_POST['hospital_name'] ?? '';
    $address       = $_POST['address'] ?? '';
    
    if ($hospital_id) {
        $conn = getConnection();
        $stmt = $conn->prepare("UPDATE hospitals SET hospital_name = ?, address = ? WHERE hospital_id = ?");
        $stmt->execute([$hospital_name, $address, $hospital_id]);
        header("Location: hospitals.php");
        exit;
    } else {
        die("Invalid hospital ID.");
    }
} else {
    die("Invalid request method.");
}
?>

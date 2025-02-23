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
    $hospital_id     = $_POST['hospital_id'] ?? null;
    $vaccines_to_add = $_POST['vaccines_to_add'] ?? 0;
    
    if ($hospital_id && $vaccines_to_add > 0) {
        $conn = getConnection();
        // Attempt to update an existing vaccine record.
        $stmt = $conn->prepare("UPDATE vaccines SET quantity = quantity + ? WHERE hospital_id = ?");
        $stmt->execute([$vaccines_to_add, $hospital_id]);
        
        // If no record was updated, insert a new one.
        if ($stmt->rowCount() == 0) {
            $stmt = $conn->prepare("INSERT INTO vaccines (hospital_id, quantity) VALUES (?, ?)");
            $stmt->execute([$hospital_id, $vaccines_to_add]);
        }
        header("Location: hospitals.php");
        exit;
    } else {
        die("Invalid input.");
    }
} else {
    die("Invalid request method.");
}
?>

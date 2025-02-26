<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /login.php');
    exit;
}

function getConnection()
{
    $host = 'localhost';
    $db = 'vms_db';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        return new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hospital_id = $_POST['hospital_id'] ?? null;
    $beds_to_add = $_POST['beds_to_add'] ?? 0;

    if ($hospital_id && $beds_to_add > 0) {
        $conn = getConnection();
        $stmt = $conn->prepare("INSERT INTO beds (hospital_id) VALUES (?)");
        for ($i = 0; $i < $beds_to_add; $i++) {
            $stmt->execute([$hospital_id]);
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
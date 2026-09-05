<?php
// pages/conn.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure session contains tenant DB configuration
if (!isset($_SESSION['tenant_db'])) {
    header("Location: ../index.php");
    exit();
}

$db_config = $_SESSION['tenant_db'];

// Instantiate dynamic connection using session credentials
$conn = mysqli_connect(
    $db_config['host'],
    $db_config['user'],
    $db_config['pass'],
    $db_config['name']
);

if (!$conn) {
    die("Database Connection Error: " . mysqli_connect_error());
}

// Helper SQL injection prevention function for legacy pages
if (!function_exists('prevent_sql_injection')) {
    function prevent_sql_injection($data) {
        global $conn;
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return mysqli_real_escape_string($conn, $data);
    }
}
?>
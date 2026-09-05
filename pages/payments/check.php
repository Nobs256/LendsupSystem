<?php
session_start();
header('Content-Type: application/json');

$context = $_SESSION['subscription_payment_context'] ?? null;
$ref = trim($_GET['ref'] ?? '');
if (!$context || empty($context['tenant_db']) || $ref === '') {
    http_response_code(400);
    echo json_encode(['status' => 'failed']);
    exit();
}

$db = $context['tenant_db'];
$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name']);
if (!$conn) {
    http_response_code(500);
    echo json_encode(['status' => 'failed']);
    exit();
}

$ref_sql = mysqli_real_escape_string($conn, $ref);
$query = mysqli_query($conn, "SELECT status FROM subscription_payments WHERE internal_reference='$ref_sql' LIMIT 1");
$row = $query ? mysqli_fetch_assoc($query) : null;
mysqli_close($conn);
echo json_encode(['status' => $row['status'] ?? 'failed']);

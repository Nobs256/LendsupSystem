<?php

require_once __DIR__ . '/conn.php';
header('Content-Type: application/json');

$ref = trim($_GET['ref'] ?? '');
if ($ref === '') {
    http_response_code(400);
    echo json_encode(['status' => 'failed']);
    exit;
}

$ref = mysqli_real_escape_string($conn, $ref);
$user_id = (int)($_SESSION['user_id'] ?? 0);
$boss_id = (int)($_SESSION['boss_id'] ?? 0);

if ($user_id === 0 || $boss_id === 0) {
    http_response_code(401);
    echo json_encode(['status' => 'failed']);
    exit;
}

$q = mysqli_query(
    $conn,
    "SELECT status
     FROM relworx_payments
     WHERE internal_reference='$ref' AND user_id='$user_id' AND boss_id='$boss_id'
     LIMIT 1"
);

$row = $q ? mysqli_fetch_assoc($q) : null;

echo json_encode([
    'status' => $row['status'] ?? 'pending',
    'found' => $row !== null
]);
<?php
require_once __DIR__ . '/../../conn_master.php';

$payload = json_decode(file_get_contents('php://input'), true);
$customer_reference = trim($payload['customer_reference'] ?? '');
$internal_reference = trim($payload['internal_reference'] ?? '');
$status = trim($payload['status'] ?? '');

if ($status === '' || !preg_match('/^QALDSS?_T([0-9]+)_SUB_/', $customer_reference, $matches)) {
    http_response_code(400);
    exit();
}

$tenant_id = (int)$matches[1];
$tenant_result = mysqli_query($master_conn, "SELECT db_host, db_user, db_pass, db_name FROM tenants WHERE tenant_id=$tenant_id AND status=1 LIMIT 1");
$tenant = $tenant_result ? mysqli_fetch_assoc($tenant_result) : null;
if (!$tenant) {
    http_response_code(404);
    exit();
}

$conn = mysqli_connect($tenant['db_host'], $tenant['db_user'], $tenant['db_pass'], $tenant['db_name']);
if (!$conn) {
    http_response_code(500);
    exit();
}

$internal_sql = mysqli_real_escape_string($conn, $internal_reference);
$reference_sql = mysqli_real_escape_string($conn, $customer_reference);
$payment_result = mysqli_query($conn, "SELECT * FROM subscription_payments WHERE internal_reference='$internal_sql' OR reference='$reference_sql' LIMIT 1");
$payment = $payment_result ? mysqli_fetch_assoc($payment_result) : null;
if (!$payment) {
    mysqli_close($conn);
    echo 'OK';
    exit();
}

if ($payment['status'] === 'success' || $payment['status'] === 'failed') {
    mysqli_close($conn);
    echo 'OK';
    exit();
}

if (!in_array(strtolower($status), ['success', 'successful', 'completed', 'complete', 'paid'], true)) {
    mysqli_query($conn, "UPDATE subscription_payments SET status='failed', updated_at=NOW() WHERE internal_reference='$internal_sql'");
    mysqli_close($conn);
    echo 'OK';
    exit();
}

mysqli_begin_transaction($conn);
$updated = mysqli_query($conn, "UPDATE subscription_payments SET status='success', updated_at=NOW() WHERE internal_reference='$internal_sql' AND status='pending'");
$claimed = $updated && mysqli_affected_rows($conn) === 1;

$current_month = date('m');
$current_year = date('Y');
$existing_result = mysqli_query($conn, "SELECT pay_id FROM payments WHERE user_id=" . (int)$payment['user_id'] . " AND boss_id=" . (int)$payment['boss_id'] . " AND paid=1 AND MONTH(pay_date)=$current_month AND YEAR(pay_date)=$current_year LIMIT 1");

if ($claimed && $existing_result && mysqli_num_rows($existing_result) === 0) {
    $inserted = mysqli_query($conn, "INSERT INTO payments (user_id, boss_id, amount, pay_date, paid) VALUES (" . (int)$payment['user_id'] . ", " . (int)$payment['boss_id'] . ", " . (float)$payment['amount'] . ", CURDATE(), 1)");
} else {
    $inserted = true;
}

if ($claimed && $inserted) {
    mysqli_commit($conn);
} else {
    mysqli_rollback($conn);
}

mysqli_close($conn);
echo 'OK';

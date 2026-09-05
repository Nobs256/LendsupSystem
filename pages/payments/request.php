<?php
session_start();
require_once __DIR__ . '/../config/relworx.php';
require_once __DIR__ . '/../includes/Relworx.php';

$context = $_SESSION['subscription_payment_context'] ?? null;
$return_to = (isset($_GET['return']) && $_GET['return'] === 'index') ? 'index' : 'home';
$phone_input = trim($_GET['phone'] ?? '');
$amount_input = trim($_GET['amount'] ?? '');

if (!$context || empty($context['tenant_db']) || empty($context['tenant_id']) || !preg_match('/^0[37][0-9]{8}$/', $phone_input) || !ctype_digit($amount_input) || (int) $amount_input < 500) {
    exit('Invalid payment request.');
}

$phone = '+256' . substr($phone_input, 1);
$amount = (int) $amount_input;
$tenant_db = $context['tenant_db'];
$conn = mysqli_connect($tenant_db['host'], $tenant_db['user'], $tenant_db['pass'], $tenant_db['name']);
if (!$conn) {
    exit('Tenant database connection failed.');
}

$reference = SYSTEM_CODE . '_T' . (int)$context['tenant_id'] . '_SUB_' . time() . '_' . mt_rand(1000, 9999);
$user_id = (int)$context['user_id'];
$boss_id = (int)$context['boss_id'];
$phone_sql = mysqli_real_escape_string($conn, $phone_input);
$reference_sql = mysqli_real_escape_string($conn, $reference);

$pending_insert = mysqli_query($conn, "INSERT INTO subscription_payments
    (user_id, boss_id, phone, amount, reference, internal_reference, status)
    VALUES ($user_id, $boss_id, '$phone_sql', $amount, '$reference_sql', '$reference_sql', 'pending')");

if (!$pending_insert) {
    mysqli_close($conn);
    exit('Payment request could not be recorded.');
}

$result = Relworx::requestPayment($phone, $amount, $reference);

if (empty($result['success']) || $result['success'] !== true || empty($result['internal_reference'])) {
    mysqli_query($conn, "UPDATE subscription_payments SET status='failed', updated_at=NOW() WHERE reference='$reference_sql' AND status='pending'");
    mysqli_close($conn);
    exit('Unable to initiate payment: ' . htmlspecialchars($result['message'] ?? 'Please try again.', ENT_QUOTES, 'UTF-8'));
}

$internal_reference = mysqli_real_escape_string($conn, $result['internal_reference']);
$insert = mysqli_query($conn, "UPDATE subscription_payments
    SET internal_reference='$internal_reference'
    WHERE reference='$reference_sql'");
mysqli_close($conn);

if (!$insert) {
    exit('Payment request could not be recorded.');
}

header('Location: subscription.php?ref=' . urlencode($result['internal_reference']) . '&return=' . urlencode($return_to));
exit();

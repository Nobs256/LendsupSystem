<?php
include_once('conn.php');
include_once('sms_guard.php');

date_default_timezone_set('Africa/Kampala');
$conn->query("SET time_zone = '+03:00'");

$sms_cost = 35;
$user_id = isset($user_id) ? (int) $user_id : 0;
$boss_id = isset($boss_id) ? (int) $boss_id : 0;
$client_id = isset($client_id) ? (int) $client_id : 0;

if ($user_id <= 0 || $boss_id <= 0 || $client_id <= 0) {
    return;
}

if (!sms_user_uses_sms($conn, $user_id, $boss_id, $sms_usernames)) {
    return;
}

if (!sms_credit_available($conn, $user_id, $boss_id, $sms_cost, $sms_usernames)) {
    reject_payment_without_sms();
    return;
}

// Resolve the client from the tenant and use the user's assigned branch.
$client_stmt = $conn->prepare(
    "SELECT c.phone, c.firstname, c.lastname, u.branch
     FROM clients c
     INNER JOIN new_users u ON u.user_id = c.users_id AND u.boss_id = c.bosses_id
     WHERE c.client_id = ?
       AND c.users_id = ?
       AND c.bosses_id = ?
       AND u.user_id = ?
       AND u.boss_id = ?
     LIMIT 1"
);

if (!$client_stmt) {
    return;
}

$client_stmt->bind_param('iiiii', $client_id, $user_id, $boss_id, $user_id, $boss_id);
$client_stmt->execute();
$client_stmt->store_result();

if ($client_stmt->num_rows !== 1) {
    $client_stmt->close();
    return;
}

$client_stmt->bind_result($client_phone, $client_firstname, $client_lastname, $client_branch);
$client_stmt->fetch();
$client_stmt->close();

// Reserve one SMS atomically so concurrent payments cannot overspend the balance.
$credit_stmt = $conn->prepare(
    "UPDATE total_msg
     SET total = CAST(total AS SIGNED) - ?
     WHERE user_id = ? AND boss_id = ? AND CAST(total AS SIGNED) >= ?"
);

if (!$credit_stmt) {
    return;
}

$credit_stmt->bind_param('iiii', $sms_cost, $user_id, $boss_id, $sms_cost);
$credit_stmt->execute();
$credit_reserved = $credit_stmt->affected_rows === 1;
$credit_stmt->close();

if (!$credit_reserved) {
    reject_payment_without_sms();
    return;
}

$payment_date = isset($b_date) ? $b_date : date('Y-m-d');
$amount = isset($amo) ? $amo : 0;
$remaining_balance = isset($balance) ? $balance : 0;
$client_name = trim($client_firstname . ' ' . $client_lastname);
$message = date('d-m-Y', strtotime($payment_date)) . " - Hello " . strtoupper($client_name) .
    ", You have Paid UGX $amount to LENDSUP ($client_branch). Balance is UGX $remaining_balance. Thank You.";

$data = [
    'user' => 'abelk',
    'password' => '0777842873',
    'sender' => 'QuickAcct',
    'message' => $message,
    'reciever' => $client_phone
];

$response = false;
$error = '';

if (function_exists('curl_init')) {
    $curl = curl_init('https://bluesmsuganda.com/sms-engine.php');
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);
    curl_setopt($curl, CURLOPT_TCP_KEEPALIVE, 1);

    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);
} else {
    $error = 'PHP cURL extension is unavailable';
}

$status = !$error && trim((string) $response) === '1701' ? 'sent' : 'failed';
$response_text = $error ? "CURL Error: $error" : trim((string) $response);

// A failed provider call does not consume the tenant's SMS credit.
if ($status !== 'sent') {
    $refund_stmt = $conn->prepare(
        "UPDATE total_msg SET total = CAST(total AS SIGNED) + ? WHERE user_id = ? AND boss_id = ?"
    );
    if ($refund_stmt) {
        $refund_stmt->bind_param('iii', $sms_cost, $user_id, $boss_id);
        $refund_stmt->execute();
        $refund_stmt->close();
    }
}

// sms_queue is optional for older tenant databases.
$queue_table = $conn->query(
    "SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'sms_queue' LIMIT 1"
);

if ($queue_table && $queue_table->num_rows === 1) {
    $queue_stmt = $conn->prepare(
        "INSERT INTO sms_queue (user_id, boss_id, phone_number, message, status, response, created_at, sent_at)
         VALUES (?, ?, ?, ?, ?, ?, NOW(), IF(? = 'sent', NOW(), NULL))"
    );
    if ($queue_stmt) {
        $queue_stmt->bind_param(
            'iisssss',
            $user_id,
            $boss_id,
            $client_phone,
            $message,
            $status,
            $response_text,
            $status
        );
        $queue_stmt->execute();
        $queue_stmt->close();
    }
}
?>
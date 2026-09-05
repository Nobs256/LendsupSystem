<?php

require_once __DIR__ . '/../conn_master.php';

$payload = json_decode(
    file_get_contents("php://input"),
    true
);

file_put_contents(
    __DIR__ . '/relworx.log',
    date('Y-m-d H:i:s').' | '.
    json_encode($payload).PHP_EOL,
    FILE_APPEND
);

$callback_reference = trim((string) (
    $payload['customer_reference'] ??
    $payload['internal_reference'] ??
    $payload['reference'] ?? ''
));
$callback_status = strtolower(trim((string) ($payload['status'] ?? '')));

if($callback_reference === '' || $callback_status === ''){
    http_response_code(400);
    echo 'Invalid callback';
    exit;
}

$reference_parts = explode('_', $callback_reference);
$tenant_part = $reference_parts[1] ?? '';
$tenant_id = (int) ltrim($tenant_part, 'Tt');
if ($tenant_id === 0) {
    http_response_code(400);
    echo 'Missing tenant';
    exit;
}

$tenant_stmt = mysqli_prepare($master_conn, "
    SELECT db_host, db_user, db_pass, db_name
    FROM tenants
    WHERE tenant_id = ? AND status = 1
    LIMIT 1
");
$tenant = null;
if ($tenant_stmt) {
    mysqli_stmt_bind_param($tenant_stmt, 'i', $tenant_id);
    mysqli_stmt_execute($tenant_stmt);
    $tenant_result = mysqli_stmt_get_result($tenant_stmt);
    $tenant = mysqli_fetch_assoc($tenant_result);
    mysqli_stmt_close($tenant_stmt);
}

if (!$tenant) {
    http_response_code(404);
    echo 'Unknown tenant';
    exit;
}

$conn = @mysqli_connect($tenant['db_host'], $tenant['db_user'], $tenant['db_pass'], $tenant['db_name']);
if (!$conn) {
    http_response_code(500);
    echo 'Tenant database error';
    exit;
}

$is_subscription = isset($reference_parts[2]) && strtoupper($reference_parts[2]) === 'SUB';

if ($is_subscription) {
    $subscription_stmt = mysqli_prepare($conn, "
        SELECT id, user_id, boss_id, amount, status
        FROM subscription_payments
        WHERE internal_reference = ? OR reference = ?
        ORDER BY id DESC
        LIMIT 1
    ");

    if ($subscription_stmt) {
        mysqli_stmt_bind_param($subscription_stmt, 'ss', $callback_reference, $callback_reference);
        mysqli_stmt_execute($subscription_stmt);
        $subscription_result = mysqli_stmt_get_result($subscription_stmt);
        $subscription = mysqli_fetch_assoc($subscription_result);
        mysqli_stmt_close($subscription_stmt);

        if ($subscription && $subscription['status'] === 'pending') {
            $failed_statuses = ['failed', 'failure', 'cancelled', 'canceled', 'declined', 'rejected', 'expired'];
            $success_statuses = ['success', 'successful', 'completed', 'complete', 'paid'];

            if (in_array($callback_status, $failed_statuses, true)) {
                $subscription_update = mysqli_prepare($conn, "
                    UPDATE subscription_payments
                    SET status = 'failed', updated_at = NOW()
                    WHERE id = ? AND status = 'pending'
                ");
                if ($subscription_update) {
                    mysqli_stmt_bind_param($subscription_update, 'i', $subscription['id']);
                    mysqli_stmt_execute($subscription_update);
                    mysqli_stmt_close($subscription_update);
                }
            } elseif (in_array($callback_status, $success_statuses, true)) {
                $subscription_update = mysqli_prepare($conn, "
                    UPDATE subscription_payments
                    SET status = 'success', updated_at = NOW()
                    WHERE id = ? AND status = 'pending'
                ");
                $claimed = false;
                if ($subscription_update) {
                    mysqli_stmt_bind_param($subscription_update, 'i', $subscription['id']);
                    mysqli_stmt_execute($subscription_update);
                    $claimed = mysqli_stmt_affected_rows($subscription_update) === 1;
                    mysqli_stmt_close($subscription_update);
                }

                if ($claimed) {
                    $current_month = (int) date('m');
                    $current_year = (int) date('Y');
                    $existing_payment = mysqli_prepare($conn, "
                        SELECT pay_id
                        FROM payments
                        WHERE user_id = ? AND boss_id = ? AND paid = 1
                          AND MONTH(pay_date) = ? AND YEAR(pay_date) = ?
                        LIMIT 1
                    ");
                    $already_paid = false;
                    if ($existing_payment) {
                        mysqli_stmt_bind_param($existing_payment, 'iiii', $subscription['user_id'], $subscription['boss_id'], $current_month, $current_year);
                        mysqli_stmt_execute($existing_payment);
                        mysqli_stmt_store_result($existing_payment);
                        $already_paid = mysqli_stmt_num_rows($existing_payment) > 0;
                        mysqli_stmt_close($existing_payment);
                    }

                    $credit_success = true;
                    if (!$already_paid) {
                        $payment_insert = mysqli_prepare($conn, "
                            INSERT INTO payments (user_id, boss_id, amount, pay_date, paid)
                            VALUES (?, ?, ?, CURDATE(), 1)
                        ");
                        if ($payment_insert) {
                            mysqli_stmt_bind_param($payment_insert, 'iid', $subscription['user_id'], $subscription['boss_id'], $subscription['amount']);
                            $credit_success = mysqli_stmt_execute($payment_insert);
                            mysqli_stmt_close($payment_insert);
                        } else {
                            $credit_success = false;
                        }
                    }

                    if (!$credit_success) {
                        $subscription_reset = mysqli_prepare($conn, "
                            UPDATE subscription_payments
                            SET status = 'pending', updated_at = NOW()
                            WHERE id = ? AND status = 'success'
                        ");
                        if ($subscription_reset) {
                            mysqli_stmt_bind_param($subscription_reset, 'i', $subscription['id']);
                            mysqli_stmt_execute($subscription_reset);
                            mysqli_stmt_close($subscription_reset);
                        }
                    }
                }
            }
        }
    }

    mysqli_close($conn);
    echo 'OK';
    exit;
}

$payment_stmt = mysqli_prepare($conn, "
    SELECT id, user_id, boss_id, amount, sms_units, status
    FROM relworx_payments
    WHERE internal_reference = ? OR reference = ?
    ORDER BY id DESC
    LIMIT 1
");

if (!$payment_stmt) {
    http_response_code(500);
    echo 'Database error';
    exit;
}

mysqli_stmt_bind_param($payment_stmt, 'ss', $callback_reference, $callback_reference);
mysqli_stmt_execute($payment_stmt);
$payment_result = mysqli_stmt_get_result($payment_stmt);
$payment = mysqli_fetch_assoc($payment_result);
mysqli_stmt_close($payment_stmt);

if(!$payment){
    exit('OK');
}

if (in_array($callback_status, ['failed', 'failure', 'cancelled', 'canceled', 'declined', 'rejected', 'expired'], true)) {
    $failed_stmt = mysqli_prepare($conn, "
        UPDATE relworx_payments
        SET status = 'failed', updated_at = NOW()
        WHERE id = ? AND status = 'pending'
    ");
    if ($failed_stmt) {
        mysqli_stmt_bind_param($failed_stmt, 'i', $payment['id']);
        mysqli_stmt_execute($failed_stmt);
        mysqli_stmt_close($failed_stmt);
    }
    exit('OK');
}

if (!in_array($callback_status, ['success', 'successful', 'completed', 'complete', 'paid'], true) || $payment['status'] == 'success') {
    exit('OK');
}

$date = date('Y-m-d');

$user_id = (int) $payment['user_id'];
$boss_id = (int) $payment['boss_id'];
$sms_units = (int) $payment['sms_units'];

$insert_stmt = mysqli_prepare($conn, "
    INSERT INTO sms_add (user_id, boss_id, sms_date, sms_amount)
    VALUES (?, ?, ?, ?)
");
$inserted = false;
if ($insert_stmt) {
    mysqli_stmt_bind_param($insert_stmt, 'iisi', $user_id, $boss_id, $date, $sms_units);
    $inserted = mysqli_stmt_execute($insert_stmt);
    mysqli_stmt_close($insert_stmt);
}

$total_updated = false;
$total_select_stmt = mysqli_prepare($conn, "
    SELECT msg_id, total
    FROM total_msg
    WHERE user_id = ? AND boss_id = ?
    ORDER BY msg_id DESC
    LIMIT 1
");
if ($total_select_stmt) {
    mysqli_stmt_bind_param($total_select_stmt, 'ii', $user_id, $boss_id);
    mysqli_stmt_execute($total_select_stmt);
    $total_result = mysqli_stmt_get_result($total_select_stmt);
    $total_row = mysqli_fetch_assoc($total_result);
    mysqli_stmt_close($total_select_stmt);

    if ($total_row) {
        $new_total = (int) $total_row['total'] + $sms_units;
        $total_update_stmt = mysqli_prepare($conn, "UPDATE total_msg SET total = ? WHERE msg_id = ?");
        if ($total_update_stmt) {
            mysqli_stmt_bind_param($total_update_stmt, 'ii', $new_total, $total_row['msg_id']);
            $total_updated = mysqli_stmt_execute($total_update_stmt);
            mysqli_stmt_close($total_update_stmt);
        }
    } else {
        $total_insert_stmt = mysqli_prepare($conn, "
            INSERT INTO total_msg (user_id, boss_id, total)
            VALUES (?, ?, ?)
        ");
        if ($total_insert_stmt) {
            mysqli_stmt_bind_param($total_insert_stmt, 'iii', $user_id, $boss_id, $sms_units);
            $total_updated = mysqli_stmt_execute($total_insert_stmt);
            mysqli_stmt_close($total_insert_stmt);
        }
    }
}

$status_stmt = mysqli_prepare($conn, "
    UPDATE relworx_payments
    SET status = 'success', updated_at = NOW()
    WHERE id = ? AND status = 'pending'
");
$status_updated = false;
if ($status_stmt) {
    mysqli_stmt_bind_param($status_stmt, 'i', $payment['id']);
    $status_updated = mysqli_stmt_execute($status_stmt);
    mysqli_stmt_close($status_stmt);
}

if ($inserted && $total_updated && $status_updated) {
    echo 'OK';
    exit;
}

echo "OK";
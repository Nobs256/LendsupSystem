<?php

// Only users in this list must have SMS credits and send payment SMS messages.
$sms_usernames = [
    'iganga'
];

if (!function_exists('sms_user_uses_sms')) {
    function sms_user_uses_sms($conn, $user_id, $boss_id, $sms_usernames)
    {
        $stmt = $conn->prepare(
            "SELECT username FROM new_users WHERE user_id = ? AND boss_id = ? LIMIT 1"
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('ii', $user_id, $boss_id);
        $stmt->execute();
        $stmt->bind_result($username);
        $found = $stmt->fetch();
        $stmt->close();

        return $found && in_array($username, $sms_usernames, true);
    }
}

if (!function_exists('sms_credit_available')) {
    function sms_credit_available($conn, $user_id, $boss_id, $sms_cost, $sms_usernames)
    {
        if (!sms_user_uses_sms($conn, $user_id, $boss_id, $sms_usernames)) {
            return true;
        }

        $stmt = $conn->prepare(
            "SELECT COALESCE(SUM(CAST(total AS SIGNED)), 0)
             FROM total_msg
             WHERE user_id = ? AND boss_id = ?"
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('ii', $user_id, $boss_id);
        $stmt->execute();
        $stmt->bind_result($total_sms);
        $stmt->fetch();
        $stmt->close();

        return (int) $total_sms >= $sms_cost;
    }
}

if (!function_exists('reject_payment_without_sms')) {
    function reject_payment_without_sms()
    {
        echo "<script>alert('You cannot make payments without SMS credits. Please buy SMS credits first.');</script>";
        echo "<div style='background-color:red; border-radius:5px; color:white; padding:10px; margin:25px; width:700px'>";
        echo "You cannot make payments without SMS credits. <a href='buy_sms.php' style='color:white; text-decoration:underline'>Click here to buy SMS</a>.";
        echo "</div>";
    }
}

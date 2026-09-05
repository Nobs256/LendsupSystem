<?php
session_start();

$payment_context = $_SESSION['subscription_payment_context'] ?? null;
if (!$payment_context || empty($payment_context['tenant_db'])) {
    header('Location: ../../index.php');
    exit();
}

$reference = isset($_GET['ref']) ? trim($_GET['ref']) : '';
$return_to = (isset($_GET['return']) && $_GET['return'] === 'index') ? 'index' : 'home';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$amount = isset($_POST['amount']) ? trim($_POST['amount']) : '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!preg_match('/^0[37][0-9]{8}$/', $phone)) {
        $error = 'Enter a valid Ugandan mobile number, for example 0771234567.';
    } elseif (!ctype_digit($amount) || (int) $amount < 500) {
        $error = 'The minimum subscription payment is UGX 500 for testing.';
    } else {
        $_SESSION['subscription_payment_context']['return_to'] = $return_to;
        header('Location: request.php?return=' . urlencode($return_to) . '&phone=' . urlencode($phone) . '&amount=' . urlencode($amount));
        exit();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>System Subscription Payment</title>
    <link rel="stylesheet" href="../css/bulma.min.css">
</head>
<body>
<section class="section">
    <div class="container" style="max-width:500px">
        <?php if ($reference): ?>
            <div class="box has-text-centered">
                <h1 class="title is-4">Approve Payment</h1>
                <p>Approve the UGX 50,000 payment prompt on your phone.</p>
                <p id="status" class="mt-4">Waiting for payment confirmation...</p>
            </div>
            <script>
                const statusUrl = 'check.php?ref=<?php echo rawurlencode($reference); ?>';
                const returnUrl = <?php echo json_encode($return_to === 'index' ? '../../index.php' : '../user_homepage.php'); ?>;
                function checkPayment() {
                    fetch(statusUrl + '&t=' + Date.now())
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                document.getElementById('status').textContent = 'Payment confirmed. Access restored.';
                                clearInterval(paymentPoll);
                                setTimeout(() => { window.location = returnUrl; }, 1200);
                            } else if (data.status === 'failed') {
                                document.getElementById('status').textContent = 'Payment failed. Please try again.';
                                clearInterval(paymentPoll);
                            }
                        });
                }
                const paymentPoll = setInterval(checkPayment, 5000);
                checkPayment();
            </script>
        <?php else: ?>
            <div class="box">
                <div class="notification is-warning">
                    <strong>You Haven't Paid for your system.</strong><br>
                    Deposit money on your Mobile Money account, enter that phone number below, enter the amount you have been paying, and press <br><strong>Make Payment</strong>.<br>
                    Then enter your Mobile Money PIN on your phone and wait for the system to open and log in again.
                </div>
                <?php if ($error): ?><div class="notification is-danger"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
                <form method="post">
                    <div class="field">
                        <label class="label" for="phone">Mobile Money phone number</label>
                        <div class="control">
                            <input class="input" id="phone" name="phone" type="tel" maxlength="10" pattern="0[37][0-9]{8}" required>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label" for="amount">Amount (UGX)</label>
                        <div class="control">
                            <input class="input" id="amount" name="amount" type="number" min="500" step="1" required>
                        </div>
                    </div>
                    <button class="button is-success is-fullwidth" type="submit">Make Payment</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</section>
</body>
</html>

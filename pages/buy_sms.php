<?php
ob_start();
include('header_user.php');

if (isset($_POST['buy_sms'])) {
    require_once __DIR__ . '/config/relworx.php';
    require_once __DIR__ . '/includes/Relworx.php';

    $amount_raw = $_POST['amount'] ?? '';
    $amount_ugx = (float) str_replace(',', '', $amount_raw);
    $sms_phone = trim($_POST['phone'] ?? '');

    if (!preg_match('/^0[37][0-9]{8}$/', $sms_phone)) {
        $purchase_error = 'Enter a valid Ugandan mobile number.';
    } elseif ($amount_ugx < 500) {
        $purchase_error = 'Minimum purchase amount is UGX 500';
    } else {
        $phone = '+256' . substr($sms_phone, 1);
        $sms_count = (int) floor($amount_ugx);
        $tenant_id = (int) ($_SESSION['tenant_id'] ?? 0);

        if ($tenant_id === 0 && !empty($db_config['name'])) {
            require_once __DIR__ . '/../conn_master.php';
            $tenant_lookup_stmt = mysqli_prepare($master_conn, "
                SELECT tenant_id
                FROM tenants
                WHERE db_name = ? AND status = 1
                LIMIT 1
            ");
            if ($tenant_lookup_stmt) {
                mysqli_stmt_bind_param($tenant_lookup_stmt, 's', $db_config['name']);
                mysqli_stmt_execute($tenant_lookup_stmt);
                $tenant_lookup_result = mysqli_stmt_get_result($tenant_lookup_stmt);
                $tenant_lookup = mysqli_fetch_assoc($tenant_lookup_result);
                $tenant_id = (int) ($tenant_lookup['tenant_id'] ?? 0);
                mysqli_stmt_close($tenant_lookup_stmt);
            }
        }

        if ($tenant_id === 0) {
            $purchase_error = 'Your tenant could not be identified. Please log out and log in again before buying SMS.';
        } else {
            $_SESSION['tenant_id'] = $tenant_id;
            $reference = Relworx::generateReference($tenant_id);
            $res = Relworx::requestPayment($phone, $amount_ugx, $reference);

            if (!empty($res['success']) && $res['success'] === true && !empty($res['internal_reference'])) {
                $internal_reference = mysqli_real_escape_string($conn, $res['internal_reference']);
                $reference_sql = mysqli_real_escape_string($conn, $reference);
                $insert = mysqli_query($conn, "
                    INSERT INTO relworx_payments
                    (user_id, boss_id, phone, amount, sms_units, reference, internal_reference, status)
                    VALUES
                    ('$user_id', '$boss_id', '$sms_phone', '$amount_ugx', '$sms_count', '$reference_sql', '$internal_reference', 'pending')
                ");

                if ($insert) {
                    ob_end_clean();
                    header('Location: wait_payment.php?ref=' . rawurlencode($res['internal_reference']));
                    exit;
                }

                $purchase_error = 'Payment was initiated, but could not be recorded locally. Contact support before retrying.';
            } else {
                $purchase_error = $res['message'] ?? 'Unable to initiate payment';
            }
        }
    }
}
?>
<script type="text/javascript">
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (!(charCode >= 48 && charCode <= 57 || charCode == 8 || charCode == 46))
            return false;
        return true;
    }

    function separator() {
        var input = document.getElementById("amount");
        var val = input.value.replace(/,/g, "");
        if (!isNaN(val) && val.length > 0) {
            input.value = Number(val).toLocaleString();
        }
    }
</script>

<main class="column main" style="background-color:#EAEAEA;">
    <p align="center"><?php include('summary.php') ?></p>

    <div id="main_heading">
        <b>BUY SMS CREDITS</b>
    </div>

    <div id="main_container" style="height: auto;">
        <div id="main_body">
            <br>
            <div class="columns is-centered">
                <div class="column is-5">
                    <div class="box shadow-sm">
                        <h3 class="title is-5" style="color: #006F37;">Buy SMS Form</h3>
                        <p class="subtitle is-7">Enter details to load SMS credits via Mobile Money.</p>
                        <hr>

                        <form method="post">
                            <!-- Form will point to an API handler later -->
                            <div class="field">
                                <label class="label">Enter Phone Number</label>
                                <div class="control has-icons-left">
                                    <input class="input is-success" type="tel" name="phone" maxlength="10" style="border: 1px solid #006F37" required>
                                    <span class="icon is-small is-left">
                                        <i class="fa fa-phone"></i>
                                    </span>
                                </div>
                                <p class="help">This number will receive a prompt for the Mobile money PIN.</p>
                            </div>

                            <div class="field">
                                <label class="label">Enter Amount(UGX)</label>
                                <div class="control has-icons-left">
                                    <input class="input is-success" type="text" id="amount" name="amount" onkeyup="separator()" onkeypress="return isNumberKey(event)" style="border: 1px solid #006F37" required>
                                    <span class="icon is-small is-left">
                                        <i class="fa fa-money"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="field mt-5">
                                <div class="control">
                                    <button type="submit" name="buy_sms" class="button is-success is-fullwidth" style="background-color:#006F37; color: white; font-weight: bold;">
                                        BUY
                                    </button>
                                </div>
                            </div>
                        </form>
                        <?php if (isset($purchase_error)): ?>
                            <div class="notification is-danger"><?php echo htmlspecialchars($purchase_error, ENT_QUOTES, 'UTF-8'); ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="column is-5">
                    <div class="box has-background-white-ter shadow-sm">
                        <h3 class="title is-5 has-text-info">Information</h3>
                        <hr>
                        <table class="table is-fullwidth" style="background: transparent;">
                            <tr>
                                <td><strong>Current SMS Balance:</strong></td>
                                <td class="has-text-right"><span class="tag is-info is-medium"><?php echo number_format($total_msg); ?></span></td>
                            </tr>
                            
                        </table>
                        <div class="notification" style="background-color: white; border: 1px solid #ccc;">
                            <h4 class="title is-6" style="color: black; font-weight: bold;">How it works:</h4>
                            <ol class="ml-4 is-size-7" style="line-height: 1.6; color: black; font-weight: bold;">
                                <li>Enter the Mobile Money number.</li>
                                <li>Enter the amount.</li>
                                <li>Click 'BUY'.</li>
                                <li>Keep your phone active; you will receive a prompt.</li>
                                <li>Enter your MOM PIN to authorize.</li>
                            </ol>
                            <p class="mt-3 is-size-7" style="color: black; font-weight: bold;"><i>SMS credits are applied to your account instantly after successful authorization.</i></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box shadow-sm mt-5">
                <h3 class="title is-5" style="color: #006F37;">Recent Purchase History</h3>
                <hr>
                <div class="table-container">
                    <table class="table is-fullwidth is-striped is-hoverable">
                        <thead>
                            <tr style="background-color: #f5f5f5;">
                                <th>No.</th>
                                <th>Purchase Date</th>
                                <th class="has-text-right">Amount Paid (UGX)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $h_count = 0;
                            $history = mysqli_query($conn, "SELECT * FROM sms_add WHERE user_id='$user_id' AND boss_id='$boss_id' ORDER BY sms_id DESC LIMIT 10");
                            if(mysqli_num_rows($history) > 0) {
                                while($h_row = mysqli_fetch_assoc($history)) {
                                    $h_count++;
                                    $h_date = date("d-m-Y", strtotime($h_row['sms_date']));
                                    $h_amount = (float)$h_row['sms_amount'];
                                    echo "<tr>
                                            <td>$h_count</td>
                                            <td>$h_date</td>
                                            <td class='has-text-right'>" . number_format($h_amount) . "</td>
                                            
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='has-text-centered has-text-grey'><i>No recent SMS purchases recorded.</i></td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>
</main>
</body>
</html>
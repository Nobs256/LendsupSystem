<?php
$s = "";
include('header_user.php');

// Handle the Return Loan Action via POST from the Modal
if (isset($_POST['confirm_return'])) {
    $client_id = mysqli_real_escape_string($conn, $_POST['client_id']);
    $selected_date = mysqli_real_escape_string($conn, $_POST['selected_date']);
    $curr_date = date('Y-m-d');
    $sent_date = date("Y-m-d", strtotime($selected_date));

    // Check last sent report date (same restriction as user_connector.php)
    $result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * from sent_msgs where user_id='$user_id' 
        and boss_id='$boss_id' order by ts_id DESC Limit 1"));
    $msg_date = $result ? $result['msg_date'] : '';

    // Prevent future dates (Backend fallback)
    if ($sent_date > $curr_date) {
        $s = "<div style='background-color:red; border-radius:5px; color:white; height:auto; margin-left:25px; padding:7px; width: 700px'>
                Error! Select Correct date. You have selected a date above today!
                <a href='return_loan.php' style='color:white; float:right;'>X</a>
              </div>";
    }
    else if ($msg_date && $sent_date <= $msg_date) {
        $s = "<div style='background-color:red; border-radius:5px; color:white; height:auto; margin-left:25px; padding:7px; width: 700px'>
                You Have Already Sent Report. Not Allowed to make this Transaction
                <a href='user_homepage.php' style='color:white; float:right;'>X</a>
              </div>";
    }
    else {
            // Fetch current loan details restricted to current user/branch
            $loan_check = mysqli_query($conn, "SELECT * FROM clients_with_loan WHERE clientsid='$client_id' AND bosseseid='$boss_id' AND userseid='$user_id' LIMIT 1");
            
            if (mysqli_num_rows($loan_check) > 0) {
                $loan_data = mysqli_fetch_assoc($loan_check);
                $amount_returned = $loan_data['amount_given'];
                $loan_no = $loan_data['loan_no'];

                // Ensure no payments have been made on this loan in this branch
                $pay_check = mysqli_query($conn, "SELECT * FROM loan_pay WHERE clients_id='$client_id' AND loanNo='$loan_no' AND bossese_id='$boss_id' AND userse_id='$user_id'");
                
                if (mysqli_num_rows($pay_check) > 0) {
                    $s = "<div style='background-color:red; border-radius:5px; color:white; height:auto; margin-left:25px; padding:7px; width: 700px'>
                            Error: This loan has already received payments and cannot be returned.
                            <a href='return_loan.php' style='color:white; float:right;'>X</a>
                          </div>";
                } else {
                    // Fetch client name for the transaction record
                    $c_res = mysqli_fetch_assoc(mysqli_query($conn, "SELECT firstname, lastname FROM clients WHERE client_id='$client_id'"));
                    $c_name = strtoupper($c_res['firstname'] . " " . $c_res['lastname']);

                    // Fetch processing fee (reg_fee) from loans table
                    $fee_query = mysqli_query($conn, "SELECT reg_fee FROM loans WHERE cliente_id='$client_id' AND bossese_id='$boss_id' AND userse_id='$user_id' ORDER BY loan_id DESC LIMIT 1");
                    $processing_fee = 0;
                    if (mysqli_num_rows($fee_query) > 0) {
                        $fee_data = mysqli_fetch_assoc($fee_query);
                        $processing_fee = $fee_data['reg_fee'];
                    }

                    // Calculate actual cash in securely on backend
                    $actual_cash_in = $amount_returned - $processing_fee;

                    // Record returned loan entry using selected date
                    mysqli_query($conn, "INSERT INTO loan_returned (client_id, user_id, boss_id, date, amount_returned) 
                                        VALUES ('$client_id', '$user_id', '$boss_id', '$selected_date', '$actual_cash_in')");

                    // Remove active loan
                    mysqli_query($conn, "DELETE FROM clients_with_loan WHERE clientsid='$client_id' AND bosseseid='$boss_id' AND userseid='$user_id'");

                    // Mark as completed
                    mysqli_query($conn, "UPDATE completed_loan SET completed = '1', e_date='$selected_date' 
                                        WHERE clientcpid='$client_id' AND loans_no='$loan_no' AND bosscpid='$boss_id' AND userscpid='$user_id'");

                    // Record cashin transaction
                    $trans_name = "Loan Returned: " . $c_name;
                    mysqli_query($conn, "INSERT INTO transcations (user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
                                         VALUES ('$user_id', '$boss_id', '$client_id', '$selected_date', '$trans_name', 'Cash_in', '$actual_cash_in', '0', '0')");

                    $s = "<div style='background-color:#006F37; border-radius:5px; color:white; height:auto; margin-left:25px; padding:7px; width: 600px'>
                            Loan successfully returned for date: $selected_date.<br>
                            <b>Original Amount:</b> " . number_format($amount_returned) . "<br>
                            <b>Processing Fee Deducted:</b> " . number_format($processing_fee) . "<br>
                            <b>Cash In Logged:</b> " . number_format($actual_cash_in) . "
                            <a href='return_loan.php' style='color:white; float:right; font-weight:bold;'>X</a>
                          </div>";
                }
            } else {
                $s = "<div style='background-color:red; border-radius:5px; color:white; height:auto; margin-left:25px; padding:7px; width: 500px'>
                        Error: No active loan found assigned to your account/branch for this client.
                        <a href='return_loan.php' style='color:white; float:right;'>X</a>
                      </div>";
            }
    }
}
?>
<style type="text/css">
    tr:nth-child(even) { background-color: #D9FFD9; }
    th, td { text-align: left; padding-left: 10px; }

    /* Modal Styles */
    .modal-overlay {
        display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.6); z-index: 999;
    }
    .modal-content {
        display: none; position: fixed; top: 25%; left: 35%; width: 30%;
        background: #fff; border: 2px solid #006F37; border-radius: 8px;
        padding: 20px; z-index: 1000; box-shadow: 0px 4px 15px rgba(0,0,0,0.3);
    }
    .modal-content h3 { margin-top: 0; color: #006F37; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
    .modal-content label { font-weight: bold; display: block; margin-top: 15px; }
    .modal-content input[type="text"], .modal-content input[type="date"] {
        width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;
    }
    .modal-readonly { background-color: #f5f5f5; border: 1px solid #ccc; cursor: not-allowed; }
    .btn-group { margin-top: 20px; text-align: right; }
    .btn-group button { padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; color: white; }
    .btn-confirm { background-color: #006F37; }
    .btn-cancel { background-color: red; }
</style>

<main class="column main" style="background-color:#EAEAEA;">
    <p align="center"> <?php include ('summary.php') ?> </p>

    <div id="main_heading"> <b>Return Client Loan (Cancel Active Loan)</b> </div>

    <div id="main_container">
        <div id="main_body">
            <br>
            <?php echo $s; ?>
            <br>
            <table border="0" width="70%">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <table style="width:730px;" border="0">
                        <tr>
                            <td width="150px">Enter Client Name:</td>
                            <td width="400px">
                                <input type="text" name="search_term" class="input is-success" style="width:320px; border: 1px solid #006F37" placeholder="Name or Phone Number" required>
                                <button type="submit" name="search" class="button is-primary" style="background-color:#006F37">
                                    &nbsp;&nbsp;&nbsp;Search&nbsp;&nbsp;&nbsp;
                                </button>
                            </td>
                        </tr>
                    </table>
                </form>
            </table>
            <br>

            <?php
            if (isset($_POST['search'])) {
                $q = mysqli_real_escape_string($conn, $_POST['search_term']);
                $j = 0;

                $search_query = mysqli_query($conn, "SELECT c.client_id, c.firstname, c.lastname, c.phone, c.b_location, cwl.amount_given, cwl.debt, cwl.loan_no
                                                      FROM clients c
                                                      JOIN clients_with_loan cwl ON c.client_id = cwl.clientsid
                                                      WHERE (c.firstname LIKE '%$q%' OR c.lastname LIKE '%$q%' OR c.phone LIKE '%$q%') 
                                                      AND cwl.bosseseid = '$boss_id'
                                                      AND cwl.userseid = '$user_id'
                                                      AND NOT EXISTS (SELECT 1 FROM loan_pay lp WHERE lp.loanNo = cwl.loan_no AND lp.clients_id = c.client_id AND lp.bossese_id = '$boss_id' AND lp.userse_id = '$user_id')
                                                      ORDER BY c.firstname");

                if (mysqli_num_rows($search_query) > 0) {
                    echo "<table class='table table-responsive table-hover' width='100%'>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Names</th>
                                    <th>Phone</th>
                                    <th>Location</th>
                                    <th>Principal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>";

                    while ($row = mysqli_fetch_assoc($search_query)) {
                        $j++;
                        $client_id = $row['client_id'];
                        $name = strtoupper($row['firstname'] . " " . $row['lastname']);
                        $amount_given = $row['amount_given'];
                        
                        // Fetch processing fee for UI calculation
                        $fee_query = mysqli_query($conn, "SELECT reg_fee FROM loans WHERE cliente_id='$client_id' AND bossese_id='$boss_id' AND userse_id='$user_id' ORDER BY loan_id DESC LIMIT 1");
                        $processing_fee = 0;
                        if (mysqli_num_rows($fee_query) > 0) {
                            $fee_data = mysqli_fetch_assoc($fee_query);
                            $processing_fee = $fee_data['reg_fee'];
                        }
                        
                        echo "<tr>
                                <td>$j</td>
                                <td>$name</td>
                                <td>" . $row['phone'] . "</td>
                                <td>" . strtoupper($row['b_location']) . "</td>
                                <td>" . number_format($amount_given) . "</td>
                                <td>
                                    <button type='button' 
                                            onclick=\"openReturnModal('$client_id', '$name', '$amount_given', '$processing_fee')\" 
                                            style='color:red; font-weight:bold; cursor:pointer; background:none; border:none;'>
                                        RETURN LOAN
                                    </button>
                                </td>
                              </tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<p><font color=red>No active loans assigned to your branch matching that name or phone number.</font></p>";
                }
            }
            ?>
        </div>
    </div>
    <?php include('footer.php'); ?>
</main>

<!-- Hidden Modal Element -->
<div class="modal-overlay" id="modalOverlay"></div>
<div class="modal-content" id="returnModal">
    <h3>Return Client Loan</h3>
    <form method="post" action="">
        <input type="hidden" name="client_id" id="modal_client_id">
        
        <label>Client Name:</label>
        <div id="modal_client_name" style="color: #555; padding: 5px 0;"></div>
        
        <label>Select Return Date:</label>
        <input type="date" name="selected_date" id="modal_selected_date" max="<?php echo date('Y-m-d'); ?>" required>
        
        <label>Return Amount (Principal - Processing Fee):</label>
        <input type="text" id="modal_amount_display" class="modal-readonly" readonly>
        
        <div class="btn-group">
            <button type="button" class="btn-cancel" onclick="closeReturnModal()">Cancel</button>
            <button type="submit" name="confirm_return" class="btn-confirm">Confirm Return</button>
        </div>
    </form>
</div>

<!-- JavaScript to handle Modal Logic -->
<script>
    function openReturnModal(clientId, clientName, principal, fee) {
        document.getElementById('modalOverlay').style.display = 'block';
        document.getElementById('returnModal').style.display = 'block';
        
        document.getElementById('modal_client_id').value = clientId;
        document.getElementById('modal_client_name').innerText = clientName;
        
        // Ensure inputs are treated as numbers
        let amount = parseFloat(principal) || 0;
        let processingFee = parseFloat(fee) || 0;
        let actualReturn = amount - processingFee;
        
        // Format with commas for display
        document.getElementById('modal_amount_display').value = actualReturn.toLocaleString('en-US');
    }

    function closeReturnModal() {
        document.getElementById('modalOverlay').style.display = 'none';
        document.getElementById('returnModal').style.display = 'none';
    }
</script>

</body>
</html>
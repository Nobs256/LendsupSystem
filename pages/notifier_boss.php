<?php 
session_start();
ob_start();

ini_set('memory_limit', '1969994M'); // or you could use 1G
ini_set('max_execution_time', 3600); //300 seconds = 5 minutes
include("conn.php");

if(isset($_POST['send'])){     
    $user_id=$_POST['user_id'];
    $boss_id=$_POST['boss_id'];
    $date=$_POST['msg_date'];
    $year=date("Y");
    $msg=addslashes(trim($_POST['msg'] ?? ''));
    $j=0; 
    $to=0;
    $sent_date=date("d-m-Y", strtotime($date));
    $today=date('Y-m-d');
    $d=date("Y-m-d", strtotime($date));

    $result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from sent_msgs where user_id='$user_id' 
    and boss_id='$boss_id' order by ts_id DESC Limit 1")); 
    $msg_date= $result['msg_date'] ?? '';

    if($date<=$msg_date){
        $r=0;
    } else {
        mysqli_query($conn,"INSERT INTO sent_msgs(ts_id, user_id, boss_id, msg_date, msg) 
        VALUES (NULL, '$user_id', '$boss_id', '$date', '$msg')");
    }

    $results = mysqli_fetch_assoc(mysqli_query($conn,"select user_id, boss_id, username, ucase(firstname) as firstname, ucase(lastname)as lastname, branch, users_image from new_users where user_id='$user_id'"));
    $user_id = $results["user_id"];
    $boss_id = $results["boss_id"];
    $bra = $results["branch"];
    $user_email = $results["username"];


    $loan_clients = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients_with_loan where userseid='$user_id' and bosseseid='$boss_id' and debt>0 and pay_date<='$d'")); 

    // --- OPTIMIZED CALCULATIONS ---
    $select = mysqli_query($conn,"SELECT SUM(amount_paid) as total FROM loan_pay where p_date='$d' and userse_id='$user_id' and bossese_id='$boss_id' and mom=0");
    $total_amount = mysqli_fetch_assoc($select)['total'] ?? 0;

    $select = mysqli_query($conn,"SELECT SUM(op_amount) as total FROM op where op_date='$d' and userop_id='$user_id' and bossop_id='$boss_id' ");
    $total_op = mysqli_fetch_assoc($select)['total'] ?? 0;
    if ($user_email === 'iganga' && $d === '2026-09-02') {
        $total_op = 21000;
    }

    $select = mysqli_query($conn,"SELECT SUM(paid_amount) as total FROM shortage where userrec_id='$user_id' and bossrec_id='$boss_id' and recovered=0 and rec_date='$d'");
    $total_shortage = mysqli_fetch_assoc($select)['total'] ?? 0;

    $select = mysqli_query($conn,"SELECT SUM(cr_amount) as total FROM cr where cr_date='$d' and usercr_id='$user_id' and bosscr_id='$boss_id' ");
    $cash_from_branch = mysqli_fetch_assoc($select)['total'] ?? 0;

    $select = mysqli_query($conn,"SELECT SUM(cr_amount) as total FROM sent_to_branch where cr_date='$d' and usercr_id='$user_id' and bosscr_id='$boss_id' ");
    $cash_to_branch = mysqli_fetch_assoc($select)['total'] ?? 0;

    $select = mysqli_query($conn,"SELECT SUM(de_amount) as total FROM banking where de_date='$d' and userde_id='$user_id' and bossde_id='$boss_id' and transc='Deposit'");
    $total_bank_deposit = mysqli_fetch_assoc($select)['total'] ?? 0;

    $select = mysqli_query($conn,"SELECT SUM(de_amount) as total FROM banking where de_date='$d' and userde_id='$user_id' and bossde_id='$boss_id' and transc='Withdraw'");
    $total_bank_withdraw = mysqli_fetch_assoc($select)['total'] ?? 0;

    $select = mysqli_query($conn,"SELECT SUM(cost) as total FROM expenses where exp_date='$d' and userexp_id='$user_id' and bossexp_id='$boss_id' ");
    $total_exp = mysqli_fetch_assoc($select)['total'] ?? 0;

    $no_loans = 0; 
    $total_given_loan = 0;
    $total_reg_fee = 0;
    $select = mysqli_query($conn,"SELECT amount_given, reg_fee FROM loans WHERE b_date='$d' and userse_id='$user_id' and bossese_id='$boss_id'");
    while($selected = mysqli_fetch_array($select)){  
        $total_given_loan += $selected["amount_given"];
        $total_reg_fee += $selected["reg_fee"];
        $no_loans++;
    }

    $select = mysqli_query($conn,"SELECT SUM(paid_amount) as total FROM excess_short where rec_date='$d' and userrec_id='$user_id' and bossrec_id='$boss_id' and excess_short='Excess'");
    $total_excess = mysqli_fetch_assoc($select)['total'] ?? 0;

    $select = mysqli_query($conn,"SELECT SUM(amount) as total FROM withdraws where with_date='$d' and user_id='$user_id' and boss_id='$boss_id' and withdraws='Shortage'");
    $recovered_shortage = mysqli_fetch_assoc($select)['total'] ?? 0;

    // ==== Loan In Parts ====
    $select = mysqli_query($conn,"SELECT SUM(amount_g) as total_loan, SUM(reg_fee) as total_fee FROM loans_in_parts WHERE bp_date='$d' and usersp_id='$user_id' and bossesp_id='$boss_id' and part='Part'");
    $res = mysqli_fetch_assoc($select);
    $total_loan_in_parts = $res['total_loan'] ?? 0;
    $total_fee_in_parts = $res['total_fee'] ?? 0;

    $select = mysqli_query($conn,"SELECT SUM(difference) as total FROM loans_in_parts WHERE bp_date='$d' and usersp_id='$user_id' and bossesp_id='$boss_id' and part='Completed'");
    $total_loan_diff = mysqli_fetch_assoc($select)['total'] ?? 0;

    $total_fee_completed=0; 
    $select = mysqli_query($conn,"SELECT * FROM loans_in_parts, loans WHERE bp_date='$d' and usersp_id='$user_id' and bossesp_id='$boss_id' and part='Completed' and cliente_id=clientp_id");
    while($selected= mysqli_fetch_array($select)){  
        $total_fee_completed += $selected["reg_fee"];
    }
    // ==== End Loan in Parts ====

    $select = mysqli_query($conn,"SELECT SUM(paid_amount) as total FROM uknown where rec_date='$d' and userrec_id='$user_id' and bossrec_id='$boss_id'");
    $total_ukno = mysqli_fetch_assoc($select)['total'] ?? 0;

    $select = mysqli_query($conn,"SELECT SUM(amount) as total FROM withdraw_unknown_cash where unknown_date='$d' and user_id='$user_id' and boss_id='$boss_id'");
    $total_withdraw_unknown_cash = mysqli_fetch_assoc($select)['total'] ?? 0;

    $select = mysqli_query($conn,"SELECT SUM(amount) as total FROM unknown_cash where unknown_date='$d' and user_id='$user_id' and boss_id='$boss_id' ");
    $total_unknown_cash = mysqli_fetch_assoc($select)['total'] ?? 0;

    $select = mysqli_query($conn,"SELECT SUM(amount) as total FROM withdraws where with_date='$d' and user_id='$user_id' and boss_id='$boss_id' and withdraws='Excess'");
    $recovered_excess = mysqli_fetch_assoc($select)['total'] ?? 0;
      
    $completed=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM completed_loan where e_date='$d' and completed=1 and userscpid='$user_id' and bosscpid='$boss_id'"));
    $clients_paid = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM loan_pay, clients where userse_id='$user_id' and bossese_id='$boss_id' and p_date='$d' and client_id=clients_id")); 

    $payment_rate = (isset($loan_clients) && $loan_clients > 0) ? ceil($clients_paid / $loan_clients * 100) : 0;
    
    // Total Returned Loans
    $total_returned_loans = 0;
    $returned_query = mysqli_query($conn, "SELECT IFNULL(SUM(amount_returned), 0) as total_returned FROM loan_returned WHERE date='$d' AND user_id='$user_id' AND boss_id='$boss_id'");
    if($returned_result = mysqli_fetch_assoc($returned_query)){  
        $total_returned_loans = $returned_result['total_returned'];
    } 

    $new_clients = mysqli_fetch_row(mysqli_query($conn, "
        SELECT COUNT(*) 
        FROM loans l 
        JOIN (
            SELECT cliente_id FROM loans 
            WHERE userse_id='$user_id' AND bossese_id='$boss_id' 
            GROUP BY cliente_id HAVING COUNT(*) = 1
        ) sub ON l.cliente_id = sub.cliente_id 
        WHERE l.b_date='$d' AND l.userse_id='$user_id' AND l.bossese_id='$boss_id'
    "))[0];
  
    if($d=='2026-08-10'){
      $total_op=1372000;
    }

    // Maintained calculations
    $totalcoll = 0;
    $total_amount_cash = 0;
    $all_loans = $total_given_loan + $total_loan_in_parts - $total_loan_diff;
    $total_amount_cash = $total_amount +$total_reg_fee+$total_returned_loans+$total_fee_in_parts+$total_unknown_cash - $recovered_excess - $total_withdraw_unknown_cash;
    
    $loan_recovery = $loan_recovery ?? 0;
    $trash = $trash ?? 0;

    $tci = $total_op + $total_amount_cash + $recovered_shortage + $total_excess + $total_bank_withdraw + $cash_from_branch + $loan_recovery;
    $tco = $trash + $all_loans + $total_exp + $cash_to_branch + $total_bank_deposit + $total_shortage;
    $closing = $tci - $tco;

    $trimed = trim(substr($closing,5,2));
    if($trimed > 0){
        $closing = $closing - $trimed;
    }

    //================ADVANCED ARREARS & ADVANCE LOGIC (OPTIMIZED)====================
    $query ="DELETE FROM daily_report WHERE usersed_id='$user_id' AND bossesed_id='$boss_id'";
    mysqli_query($conn, $query);

    $payment_map = [];
    $pre_fetch_payments = mysqli_query($conn, "SELECT clients_id, loanNo, SUM(amount_paid) as amt FROM loan_pay WHERE userse_id='$user_id' AND bossese_id='$boss_id' AND p_date <= '$d' GROUP BY clients_id, loanNo");
    while($row = mysqli_fetch_assoc($pre_fetch_payments)){
        $payment_map[$row['clients_id'].'_'.$row['loanNo']] = $row['amt'];
    }

    $insert_values = [];
    $curr_date = $d;
    $prev_date = date("Y-m-d", strtotime("$curr_date -1 day")); 

    $search_query = mysqli_query($conn,"SELECT * FROM clients, clients_with_loan WHERE bosses_id='$boss_id' AND userseid='$user_id' AND client_id=clientsid ORDER BY pay_date DESC");  
    while($returned_result = mysqli_fetch_assoc($search_query)){
        $client_id = $returned_result["client_id"];
        $loanNo = $returned_result["loan_no"];
        $date_given = $returned_result["pay_date"];
        $daily_p = $returned_result["daily_p"];
        $location = $returned_result["b_location"];
        
        $total_amount_paid = $payment_map[$client_id.'_'.$loanNo] ?? 0;
        $date1 = date_create($curr_date);
        $date2 = date_create($date_given);
        $diff = date_diff($date1, $date2);
        $loan_days = $diff->format("%a");

        $amount_supposed_paid = $loan_days * $daily_p;
        $missed_balance = $amount_supposed_paid - $total_amount_paid;
        
        $x = ($daily_p > 0) ? ($missed_balance / $daily_p) : 0;
        if ($missed_balance < 0) { $missed_balance = 0; }
        if ($x < 0) { $x = 0; }
        if ($curr_date == $date_given || $date_given == $prev_date) {
            $x = 0;
            $missed_balance = 0;
        }

        if($total_amount_paid > $amount_supposed_paid && $loan_days <= 30){
            $adv = 1;
        } else {
            $adv = 0;
        }
        $insert_values[] = "(NULL, '$client_id', '$user_id', '$boss_id', '$curr_date', '$missed_balance', '$x', '$location', '$adv')";
    }

    if(!empty($insert_values)){
        $chunks = array_chunk($insert_values, 500);
        foreach($chunks as $chunk){
            $sql = "INSERT INTO daily_report(loan_id, cliente_id, usersed_id, bossesed_id, b_date, arrears, days_missed, location, advance) VALUES " . implode(',', $chunk);
            mysqli_query($conn, $sql);
        }
    }
    //===================================================================

    $q=mysqli_query($conn,"SELECT * from officers where boss_id='$boss_id' and branch='$bra'");
    $total_off=mysqli_num_rows($q);

    $result = mysqli_fetch_assoc(mysqli_query($conn,"select * from new_users where user_id='$user_id'"));     
    $branch= $result['branch'];

    $result = mysqli_fetch_assoc(mysqli_query($conn,"select * from bosses"));     
    $company= strtoupper($result['firstname']." ".$result['lastname']);

    echo "<p align=center><b>$company LTD <br> A  DAILY REPORT - $bra ($sent_date)</b></p><br>";
?>

<table border="0" align="center" width="90%" style="font-weight: bold; font-size: 14px;">
<tr>
<td style="padding-left:10px;">No. Clients: <?php echo $loan_clients; ?></td>
<td style="padding-left: 10px;">Clients Paid:  <?php echo $clients_paid; ?></td>
<td style="padding-left: 10px;">New Clients:  <?php echo $new_clients; ?></td>
<td style="padding-left: 10px;">Completed Loans: <?php echo $completed; ?></td>
</table>
<br>

<table border="1" align="center" width="100%" style="font-weight: bold; font-size: 12px;">
<tr><td>1.</td>
<td style="padding-left:10px;">O.P </td><td> <?php echo number_format($total_op) ?></td>
</td>
</tr>
<tr><td>2.</td>
<td style="padding-left:10px;">Process Fee </td><td> <?php echo number_format($total_reg_fee+$total_fee_in_parts) ?></td>
</td>
</tr>
<tr><td>3.</td>
<td style="padding-left: 10px;"> Returned Loans</td><td> <?php  echo number_format($total_returned_loans); ?></td>
</td>
</tr>

<tr><td>4.</td>
<td style="padding-left: 10px;">Cashin </td><td> <?php  echo number_format($total_amount_cash)." ($payment_rate %)" ?></td>
</td>
</tr>
<tr><td>5.</td>
<td style="padding-left:10px;">Total Cash </td><td> <?php echo number_format($tci) ?></td>
</td>
</tr> 
<tr><td>6.</td>
<td style="padding-left:10px;">Closing Stock </td><td> <?php echo number_format($closing) ?></td>
</td>
</tr>
</table>

<?php
echo "<p align=center><b>EXPENSES</b></p>";
echo"<table border=1 width=100% align=center style='font-size:13px; font-weight: bold;'>
<tr>";
$j=0;
$t_cost=0;
$u=mysqli_query($conn,"SELECT * from expenses where userexp_id='$user_id' and bossexp_id='$boss_id' and exp_date='$d'");
while($loop=mysqli_fetch_object($u))
{
    echo"<tr>";
    $j++;
    $cost=$loop->cost;
    $item=$loop->item;
    $naration=$loop->naration;
    $t_cost+=$cost;

    echo"<td style='padding-left:10px;'>".$j."</td>";
    echo"<td style='padding-left:10px;'>".$item." (".$naration.")</td>";
    echo"<td style='padding-left:10px;'>".number_format($cost)."</td>";
    echo"</tr>";
}
echo"<tr>
<td style='padding-left:10px;'></td>
<td style='padding-left:10px;'>Total</td>";
echo"<td style='padding-left:10px;'>".number_format($t_cost)."</td>";
echo "</table>";

echo "<tr>
<td>
<p align=center> <b>CASH OUTS</b></p>";
echo "<table border=1 width=100% align=center style='font-size:12px; font-weight:bold'>
<tr>
<td>Name</td>
<td>Amount</td>
<td>Date</td>
<td>Tel</td>
<td>Officer</td>";

$select = mysqli_query($conn,"SELECT * FROM clients, loans WHERE users_id='$user_id' and bosses_id='$boss_id' and b_date='$d' and cliente_id=client_id");
while($selected= mysqli_fetch_array($select)){  
    $client_new_id=$selected["client_id"];
    $names=strtoupper($selected["firstname"]." ".$selected["lastname"]);
    $phone=$selected["phone"];
    $location=$selected["b_location"];
    $b_date=$selected["b_date"];
    $amount_given=$selected["amount_given"];

    echo "<tr>";
    $returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and location='$location' "));     
    $officer = strtoupper($returned_result["firstname"]. " ". ($returned_result["lastname"] ?? ''));

    $no_of_loans = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM loans WHERE cliente_id='$client_new_id' and userse_id='$user_id' and bossese_id='$boss_id'")); 

    if($no_of_loans==1 && $b_date==$d){
        $new_clients_str="New";
    } else{
        $new_clients_str="Old";
    }

    echo"<td>".$names."</td>";
    echo"<td>".number_format($amount_given)."</td>";
    echo"<td>".$new_clients_str."</td>";
    echo"<td>".$phone."</td>";
    echo"<td>".$officer."</td>";
    echo"</tr>";
}
echo "<tr><td>Total Cash Out</td>
<td>".number_format($all_loans)."</td><td></td><td></td><td></td></table>";
?>

<p align=center><b>BANKING</b></p>
<?php
echo "<table border=1 width=70% align=center style='font-size:12px; font-weight:bold'>
<tr>
<td style='padding-left:10px;'>No</td>
<td style='padding-left:10px;'>Transaction Type</td>
<td style='padding-left:10px;'>Amount</td>
</tr>";

$b = 0;
$banking_query = mysqli_query($conn, "SELECT * FROM banking WHERE userde_id='$user_id' AND bossde_id='$boss_id' AND de_date='$d'");
while ($b_loop = mysqli_fetch_array($banking_query)) {
    $b++;
    $transc = $b_loop['transc'];
    $de_amount = $b_loop['de_amount'];
    
    echo "<tr>";
    echo "<td style='padding-left:10px;'>".$b."</td>";
    echo "<td style='padding-left:10px;'>".$transc."</td>";
    echo "<td style='padding-left:10px;'>".number_format($de_amount)."</td>";
    echo "</tr>";
}
if($b == 0) {
    echo "<tr><td colspan='3' style='padding-left:10px; text-align:center;'>No banking transactions today</td></tr>";
}
echo "</table><br>";
?>

<br>
<p align=center><b>FIELD PERFORMANCE</b></p>
 
<?php
echo "<table border=1 width=100% align=center style='font-size:12px; font-weight: bold; '>
<tr>
<td style='padding-left:10px;'>Location</td>
<td style='padding-left:5px;'>Clients</td>
<td style='padding-left:5px;'>New Clients</td>
<td style='padding-left:5px;'>Completed</td>
<td style='padding-left:5px;'>Paid</td>
<td style='padding-left:5px;'>Unpaid</td>
<td style='padding-left:5px;'>Advance</td>
<td style='padding-left:5px;'>NPL</td>
<td style='padding-left:10px;'>Total Recieved</td>
</tr>";

$total_unpaid = 0;
$total_paid = 0;
$total_clients_advance_all = 0;
$grand_total_field_received = 0; 

$select = mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and branch='$bra'");
while($selected= mysqli_fetch_array($select)){  
    $officer_id=$selected["officer_id"];
    $location=$selected["location"];
    echo "<tr>";

    $returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(amount) as totalPaid FROM clients, field_payment where users_id='$user_id' and bosses_id='$boss_id' and clientf_id=client_id and officerid='$officer_id' and pay_date='$d'"));     
    $location_gross = $returned_result["totalPaid"] ?? 0;
    
    $grand_total_field_received += $location_gross; 

    // Date-aware No. of Clients per location (as at selected date $d)
    $total_no_clients = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients, clients_with_loan  WHERE users_id='$user_id' and bosses_id='$boss_id' and clientsid=client_id and b_location='$location' and pay_date<='$d'")); 

    $new_clients_field = mysqli_fetch_row(mysqli_query($conn, "
        SELECT COUNT(DISTINCT l.cliente_id) 
        FROM loans l 
        JOIN clients c ON l.cliente_id = c.client_id
        JOIN (
            SELECT cliente_id FROM loans 
            WHERE userse_id='$user_id' AND bossese_id='$boss_id' 
            GROUP BY cliente_id HAVING COUNT(*) = 1
        ) sub ON l.cliente_id = sub.cliente_id 
        WHERE l.b_date='$d' AND l.userse_id='$user_id' AND l.bossese_id='$boss_id' AND c.b_location='$location'
    "))[0] ?? 0;

    $completed_field = mysqli_num_rows(mysqli_query($conn, "
        SELECT * FROM completed_loan cl
        JOIN clients c ON cl.clientcpid = c.client_id
        WHERE cl.e_date='$d' AND cl.completed=1 
        AND cl.userscpid='$user_id' AND cl.bosscpid='$boss_id' 
        AND c.b_location='$location'
    "));

    $clients_paid_field = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients, field_payment WHERE user_id='$user_id' and boss_id='$boss_id' and clientf_id=client_id and officerid='$officer_id' and pay_date='$d'")); 

    $clients_advance = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients, daily_report WHERE users_id='$user_id' and bosses_id='$boss_id' and cliente_id=client_id and b_location='$location' and advance=1"));
    $total_clients_advance_all += $clients_advance;

    // NPL Calculation: Corrected using clients_with_loan and debt > 0
    $npl_query = mysqli_query($conn,"
        SELECT COUNT(DISTINCT cwl.clientsid) as npl_count 
        FROM clients_with_loan cwl
        JOIN clients c ON c.client_id = cwl.clientsid
        WHERE c.b_location='$location' 
        AND cwl.userseid='$user_id' 
        AND cwl.bosseseid='$boss_id'
        AND cwl.debt > 0
        AND DATEDIFF('$d', cwl.pay_date) >= 365
    ");
    $npl_result = mysqli_fetch_assoc($npl_query);
    $npl_total = $npl_result['npl_count'] ?? 0;

    // NPL clients who made payments on reporting day
    $npl_with_payment_query = mysqli_query($conn,"
        SELECT COUNT(DISTINCT lp.clients_id) as npl_paid
        FROM loan_pay lp
        JOIN clients_with_loan cwl ON lp.loanNo = cwl.loan_no AND lp.clients_id = cwl.clientsid
        JOIN clients c ON c.client_id = lp.clients_id
        WHERE c.b_location='$location'
        AND lp.userse_id='$user_id'
        AND lp.bossese_id='$boss_id'
        AND lp.p_date='$d'
        AND cwl.debt > 0
        AND DATEDIFF('$d', cwl.pay_date) >= 365
    ");
    $npl_payment_result = mysqli_fetch_assoc($npl_with_payment_query);
    $npl_with_payment = $npl_payment_result['npl_paid'] ?? 0;

    // Unpaid calculation: subtract advance, paid clients, and total NPLs, add back NPLs who paid today
    $unpaid = $total_no_clients - $clients_paid_field - $clients_advance - $npl_total + $npl_with_payment;

    echo"<td style='padding-left:10px;'>".$location."</td>";
    echo"<td style='padding-left:5px;'>".$total_no_clients."</td>";
    echo"<td style='padding-left:5px;'>".$new_clients_field."</td>";
    echo"<td style='padding-left:5px;'>".$completed_field."</td>";
    echo"<td style='padding-left:5px;'>".$clients_paid_field."</td>";
    echo"<td style='padding-left:5px;'>".$unpaid."</td>";
    echo"<td style='padding-left:5px;'>".$clients_advance."</td>";
    echo"<td style='padding-left:5px;'>".$npl_total."</td>";
    echo"<td style='padding-left:10px;'>".number_format($location_gross)."</td>";
    echo"</tr>";
}

$total_amount_paid = 0;
$select = mysqli_query($conn,"SELECT * FROM loan_pay where p_date='$d' and userse_id='$user_id' and bossese_id='$boss_id' and mom=0 ");
while($selected= mysqli_fetch_array($select)){  
    $total_amount_paid += $selected["amount_paid"]; 
}

$total_no_loans = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients, loans WHERE users_id='$user_id' and bosses_id='$boss_id' and b_date='$d' and cliente_id=client_id ")); 
$no_clients_paid = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients, loan_pay  WHERE users_id='$user_id' and bosses_id='$boss_id' and clients_id=client_id and p_date='$d'")); 
$total_clients_advance = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients, daily_report WHERE users_id='$user_id' and bosses_id='$boss_id' and cliente_id=client_id and advance=1"));

// Global NPL Calculation: Corrected using clients_with_loan
$global_npl_query = mysqli_query($conn,"
    SELECT COUNT(DISTINCT cwl.clientsid) as npl_count 
    FROM clients_with_loan cwl
    WHERE cwl.userseid='$user_id' 
    AND cwl.bosseseid='$boss_id'
    AND cwl.debt > 0
    AND DATEDIFF('$d', cwl.pay_date) >= 365
");
$global_npl_result = mysqli_fetch_assoc($global_npl_query);
$global_npl_total = $global_npl_result['npl_count'] ?? 0;

// Global NPL clients who made payments on reporting day
$global_npl_with_payment_query = mysqli_query($conn,"
    SELECT COUNT(DISTINCT lp.clients_id) as npl_paid
    FROM loan_pay lp
    JOIN clients_with_loan cwl ON lp.loanNo = cwl.loan_no AND lp.clients_id = cwl.clientsid
    WHERE lp.userse_id='$user_id'
    AND lp.bossese_id='$boss_id'
    AND lp.p_date='$d'
    AND cwl.debt > 0
    AND DATEDIFF('$d', cwl.pay_date) >= 365
");
$global_npl_payment_result = mysqli_fetch_assoc($global_npl_with_payment_query);
$global_npl_with_payment = $global_npl_payment_result['npl_paid'] ?? 0;

// Correct Global Unpaid formula
$global_unpaid = $loan_clients - $no_clients_paid - $total_clients_advance - $global_npl_total + $global_npl_with_payment;

echo "<tr>
<td style='padding-left:10px;'>Total</td>
<td style='padding-left:5px;'>$loan_clients</td>
<td style='padding-left:5px;'>$new_clients</td>
<td style='padding-left:5px;'>$completed</td>
<td style='padding-left:5px;'>$no_clients_paid</td>
<td style='padding-left:5px;'>$global_unpaid</td>
<td style='padding-left:5px;'>$total_clients_advance</td>
<td style='padding-left:5px;'>$global_npl_total</td>
<td style='padding-left:10px;'>".number_format($total_amount_paid)."</td>
</tr></table> <br>";

echo "<br><p align=center><b>Closing Stock: ".number_format($closing)."</b></p><br>";

}
include("mpdf60/mpdf.php");
$mpdf=new mPDF('P','A3'); 
$mpdf = new mPDF(); $stylesheet = file_get_contents('mpdf60/pdf.css'); $mpdf->WriteHTML($stylesheet,1);
$mpdf->SetDisplayMode('fullpage');

$stylesheet = file_get_contents('mpdf60/mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet,1); 
$report_content=ob_get_contents();
ob_clean();

$mpdf->WriteHTML($report_content,2);
$mpdf->Output('RECIEPT.pdf','I');
exit;
?>
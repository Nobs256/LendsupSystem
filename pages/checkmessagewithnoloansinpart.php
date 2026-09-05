<?php include('header_user.php'); ?>
<main class="column main" style="background-color:#EAEAEA;"> 
<?php include ('summary.php') ?>
<div id="main_container">
<div id="main_heading" style="margin-left:10px">
 
<table border="0">
<tr><td>
<b>DAILY REPORT  </b> 
</td><td>
<font color="#E4E4E4">----------------------------------------</font>
</td><td>
<form  method="post"> 
Previous Days, Select Date:  
<input type="date" name="date" style="width:150px; height:25px; border: 1px solid #006F37" 
required> 
<button type="submit" name="daily_report" class="button is-primary" 
style="background-color:#006F37; color:white; font-size:12px; border:1px solid #006F37; height:25px; ">
&nbsp;OK&nbsp;</button></form> 
 
</td></tr></table></div>
<br>
 
<div id="main_body"> 
<?php
if(isset($_POST['daily_report'])){
    $date = $_POST['date'];
    $d = date("Y-m-d", strtotime($date));
    $tomorow = date("Y-m-d", strtotime("$d +1 day"));
}
else{
    $d = date('Y-m-d');
    $d = date("Y-m-d", strtotime("$d -1 day"));
    $tomorow = date("Y-m-d", strtotime("$d +1 day"));
    $date = $d;
}

// Ensure base user variables are mapped
$results = mysqli_fetch_assoc(mysqli_query($conn,"select user_id, boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, branch, users_image from new_users where user_id='$user_id'"));
$user_id = $results["user_id"];
$boss_id = $results["boss_id"];
$bra = $results["branch"];

$loan_clients = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients_with_loan where userseid='$user_id' and bosseseid='$boss_id' and debt>0")); 

// --- OPTIMIZED CALCULATIONS ---
$select = mysqli_query($conn,"SELECT SUM(amount_paid) as total FROM loan_pay where p_date='$d' and userse_id='$user_id' and bossese_id='$boss_id' and mom=0");
$result = mysqli_fetch_assoc($select);
$total_amount = $result['total'] ?? 0;

$select = mysqli_query($conn,"SELECT SUM(op_amount) as total FROM op where op_date='$d' and userop_id='$user_id' and bossop_id='$boss_id' ");
$result = mysqli_fetch_assoc($select);
$total_op = $result['total'] ?? 0;

$select = mysqli_query($conn,"SELECT SUM(cr_amount) as total FROM cr where cr_date='$d' and usercr_id='$user_id' and bosscr_id='$boss_id' ");
$result = mysqli_fetch_assoc($select);
$cash_from_branch = $result['total'] ?? 0;

$select = mysqli_query($conn,"SELECT SUM(cr_amount) as total FROM sent_to_branch where cr_date='$d' and usercr_id='$user_id' and bosscr_id='$boss_id' ");
$result = mysqli_fetch_assoc($select);
$cash_to_branch = $result['total'] ?? 0;

$select = mysqli_query($conn,"SELECT SUM(de_amount) as total FROM banking where de_date='$d' and userde_id='$user_id' and bossde_id='$boss_id' and transc='Deposit'");
$result = mysqli_fetch_assoc($select);
$total_bank_deposit = $result['total'] ?? 0;

$select = mysqli_query($conn,"SELECT SUM(de_amount) as total FROM banking where de_date='$d' and userde_id='$user_id' and bossde_id='$boss_id' and transc='Withdraw'");
$result = mysqli_fetch_assoc($select);
$total_bank_withdraw = $result['total'] ?? 0;

$select = mysqli_query($conn,"SELECT SUM(cost) as total FROM expenses where exp_date='$d' and userexp_id='$user_id' and bossexp_id='$boss_id' ");
$result = mysqli_fetch_assoc($select);
$total_exp = $result['total'] ?? 0;

$no_loans = 0; 
$total_given_loan = 0;
$total_reg_fee = 0;
$select = mysqli_query($conn,"SELECT amount_given, reg_fee FROM loans WHERE b_date='$d' and userse_id='$user_id' and bossese_id='$boss_id'");
while($selected = mysqli_fetch_array($select)){  
    $total_given_loan += $selected["amount_given"];
    $total_reg_fee += $selected["reg_fee"];
    $no_loans++;
}

$select = mysqli_query($conn,"SELECT SUM(amount) as unknown_total FROM unknown_cash where unknown_date='$d' and user_id='$user_id' and boss_id='$boss_id' ");
$result = mysqli_fetch_assoc($select);
$total_unknown_cash = $result['unknown_total'] ?? 0;

$select = mysqli_query($conn,"SELECT SUM(amount) as withdraw_total FROM withdraw_unknown_cash where unknown_date='$d' and user_id='$user_id' and boss_id='$boss_id'");
$result = mysqli_fetch_assoc($select);
$total_withdraw_unknown_cash = $result['withdraw_total'] ?? 0;

// Derived standard values
$total_amount_cash = $total_amount + $total_unknown_cash - $total_withdraw_unknown_cash;
$cash_in = $total_amount_cash + $total_reg_fee; 
$total_cash = $cash_in + $total_op + $total_bank_withdraw;
$cs = $total_cash - $total_given_loan - $total_exp - $total_bank_deposit;

$completed = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM completed_loan where e_date='$d' and completed=1 and userscpid='$user_id' and bosscpid='$boss_id'"));
$clients_paid = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM loan_pay, clients where userse_id='$user_id' and bossese_id='$boss_id' and p_date='$d' and client_id=clients_id")); 

$payment_rate = (isset($loan_clients) && $loan_clients > 0) ? ceil($clients_paid / $loan_clients * 100) : 0;

// Optimized New Clients Query
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


//================ADVANCED ARREARS & ADVANCE LOGIC (OPTIMIZED)====================
// 1. Clear records to rebuild for this specific user/boss configuration
$query ="DELETE FROM daily_report WHERE usersed_id='$user_id' AND bossesed_id='$boss_id'";
mysqli_query($conn, $query);

// 2. PRE-FETCH all payment sums for this user/boss up to report date to prevent N+1 query lag
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
    
    // Look up the pre-fetched sum instead of running a new query
    $total_amount_paid = $payment_map[$client_id.'_'.$loanNo] ?? 0;

    $date1 = date_create($curr_date);
    $date2 = date_create($date_given);
    $diff = date_diff($date1, $date2);
    $loan_days = $diff->format("%a");

    // Amount supposed to have paid by the report date
    $amount_supposed_paid = $loan_days * $daily_p;
    $missed_balance = $amount_supposed_paid - $total_amount_paid;
    
    $x = ($daily_p > 0) ? ($missed_balance / $daily_p) : 0;
    
    if ($missed_balance < 0) { $missed_balance = 0; }
    if ($x < 0) { $x = 0; }
    if ($curr_date == $date_given || $date_given == $prev_date) {
        $x = 0;
        $missed_balance = 0;
    }

    // ADVANCE LOGIC
    if($total_amount_paid > $amount_supposed_paid && $loan_days <= 30){
        $adv = 1;
    } else {
        $adv = 0;
    }

    // Push to array for bulk insert
    $insert_values[] = "(NULL, '$client_id', '$user_id', '$boss_id', '$curr_date', '$missed_balance', '$x', '$location', '$adv')";
}

// 3. BULK INSERT to eliminate database lag
if(!empty($insert_values)){
    $chunks = array_chunk($insert_values, 500); // Insert 500 rows at a time
    foreach($chunks as $chunk){
        $sql = "INSERT INTO daily_report(loan_id, cliente_id, usersed_id, bossesed_id, b_date, arrears, days_missed, location, advance) VALUES " . implode(',', $chunk);
        mysqli_query($conn, $sql);
    }
}
//===================================================================


//branch & company variables
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from new_users where user_id='$user_id'"));     
$branchs= $result['branch'];

$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from bosses"));     
$company= strtoupper($result['firstname']." ".$result['lastname']);

echo "<p align=center><b>$company LTD <br> A  DAILY REPORT - $branchs (".date("d-m-Y", strtotime($d)).")</b><br>
<font color=red>Click Send Report and take a Photo and send it to Boss on Whats app</font></p>";
?>

<table border="0" align="center" width="60%" style="font-weight: bold; font-size: 15px;">
<tr>
<td style="padding-left:10px;">No Of Clients: <?php echo $loan_clients ?? 0; ?></td>
<td style="padding-left: 10px;">No Of Clients Paid:  <?php echo $clients_paid; ?></td>
<td style="padding-left: 10px;">New Clients:  <?php echo $new_clients; ?></td>
<td style="padding-left: 10px;">Completed Loans: <?php echo $completed; ?></td>
</table>
<br>

<table border="1" align="center" width="40%" style="font-weight: bold; font-size: 15px;">
<tr>
<td style="padding-left:10px;">1.</td>
<td style="padding-left:10px; width: 50% ;">O.P</td><td  style="padding-left: 10px;"><?php echo number_format($total_op) ?></td>
</tr>
<tr><td>2.</td>
<td style="padding-left: 10px;">Process Fee</td><td style="padding-left: 10px;"><?php echo number_format($total_reg_fee); ?></td>
</tr>
<tr><td>3.</td>
<td style="padding-left: 10px;">Cashin</td><td style="padding-left: 10px;"><?php  echo number_format($cash_in)." ($payment_rate %)" ?></td></tr>
<tr><td>4.</td>
<td style="padding-left: 10px;">Total Cash</td><td style="padding-left: 10px;"><?php echo number_format($total_cash); ?></td></tr>
<tr><td>5.</td>
<td style="padding-left: 10px;">Closing Stock</td><td style="padding-left: 10px;"><?php echo number_format($cs); ?></td></tr>
</tr>
</table><br>

<?php
echo "<p align=center><b>EXPENSES</b></p>";
echo"<table border=1 width=40% align=center style='font-size:13px; font-weight: bold;'>
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
echo "</table> <br>";

echo "<p align=center><b>BANKING</b></p>";
echo"<table border=1 width=40% align=center style='font-size:13px; font-weight: bold;'>
<tr>
<th>&nbsp;&nbsp;No</th>
<th>&nbsp;&nbsp;Bank</th>
<th>&nbsp;&nbsp;Trans.</th>
<th>&nbsp;&nbsp;Amount</th>
</tr>";
$j=0;
$u=mysqli_query($conn,"SELECT * from banking where userde_id='$user_id' and bossde_id='$boss_id' and de_date='$d'");
while($loop=mysqli_fetch_object($u))
{
    $j++;
    $bank=$loop->bank_name;
    $trans=$loop->transc;
    $amount=$loop->de_amount;
    echo"<tr>
    <td style='padding-left:10px;'>".$j."</td>
    <td style='padding-left:10px;'>".$bank."</td>
    <td style='padding-left:10px;'>".$trans."</td>
    <td style='padding-left:10px;'>".number_format($amount)."</td>
    </tr>";
}
echo "</table> <br>";
?>

<p align=center><b>CASH OUTS</b></p>
<?php
echo "<table border=1 width=70% align=center style='font-size:13px; font-weight:bold'>
<tr>
<td>&nbsp;&nbsp;No</td>
<td>&nbsp;&nbsp;Name</td>
<td>&nbsp;&nbsp;Amount</td>
<td>&nbsp;&nbsp;Date</td>
<td>&nbsp;&nbsp;Tel</td>
<td>&nbsp;&nbsp;Officer</td>";

$total_loans=0;
$k=0;
$select = mysqli_query($conn,"SELECT * FROM clients, loans WHERE users_id='$user_id' and bosses_id='$boss_id' and b_date='$d' and cliente_id=client_id");
while($selected= mysqli_fetch_array($select)){  
    $client_new_id=$selected["client_id"];
    $names=strtoupper($selected["firstname"]." ".$selected["lastname"]);
    $phone=$selected["phone"];
    $location=$selected["b_location"];
    $b_date=$selected["b_date"];
    $amount_given=$selected["amount_given"];
    $total_loans+=$amount_given;
    $k++;
    echo "<tr>";

    //officer
    $returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and location='$location' and branch='$bra' and active=1 "));     
    $officer = strtoupper($returned_result["firstname"]. " ". ($returned_result["lastname"] ?? ""));

    $no_of_loans = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM loans WHERE cliente_id='$client_new_id' and userse_id='$user_id' and bossese_id='$boss_id'")); 

    if($no_of_loans==1 && $b_date==$d){
        $new_client_status="New";
    } else {
        $new_client_status="Old";
    }
    echo"<td style='padding-left:5px;'>".$k."</td>";
    echo"<td style='padding-left:10px;'>".$names."</td>";
    echo"<td style='padding-left:10px;'>".number_format($amount_given)."</td>";
    echo"<td style='padding-left:10px;'>".$new_client_status."</td>";
    echo"<td style='padding-left:10px;'>".$phone."</td>";
    echo"<td style='padding-left:10px;'>".$officer."</td>";
    echo"</tr>";
}
echo "<tr>";
echo"<td style='padding-left:10px;'>Total</td>";
echo"<td style='padding-left:10px;'></td>";
echo"<td style='padding-left:10px;'>".number_format($total_loans)."</td>";
echo"<td style='padding-left:10px;'></td>";
echo"<td style='padding-left:10px;'></td>";
echo"<td style='padding-left:10px;'></td>";
echo "</tr></table><br>";
?>

<p align=center><b>FIELD PERFORMANCE</b></p>
<?php
echo "<table border=1 width=100% align=center style='font-size:12px; font-weight: bold; '>
<tr>
<td style='padding-left:5px;'>Location</td>
<td style='padding-left:5px;'>Clients</td>
<td style='padding-left:5px;'>New Clients</td>
<td style='padding-left:5px;'>Completed</td>
<td style='padding-left:5px;'>Paid</td>
<td style='padding-left:5px;'>Unpaid</td>
<td style='padding-left:5px;'>Advance</td>
<td style='padding-left:5px;'>Total Recieved</td>
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

    // Total amount paid
    $returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(amount) as totalPaid FROM clients, field_payment WHERE user_id='$user_id' and boss_id='$boss_id' and clientf_id=client_id and officerid='$officer_id' and pay_date='$d'"));     
    $location_total_paid = $returned_result["totalPaid"] ?? 0;

    // Total unknown Cash
    $returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(amount) as totalPaid FROM unknown_cash where unknown_date='$d' and user_id='$user_id' and boss_id='$boss_id' and officer='$officer_id'"));     
    $total_unknown_cash_field = $returned_result["totalPaid"] ?? 0;

    $location_gross = $location_total_paid + $total_unknown_cash_field;
    
    // Running grand total
    $grand_total_field_received += $location_gross; 

    // Total no of clients
    $total_no_clients = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients, clients_with_loan WHERE users_id='$user_id' and bosses_id='$boss_id' and clientsid=client_id and b_location='$location'")); 

    // --- NEW METRIC: New Clients per field officer ---
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

    // --- NEW METRIC: Completed Loans per field officer ---
    $completed_field = mysqli_num_rows(mysqli_query($conn, "
        SELECT * FROM completed_loan cl
        JOIN clients c ON cl.clientcpid = c.client_id
        WHERE cl.e_date='$d' AND cl.completed=1 
        AND cl.userscpid='$user_id' AND cl.bosscpid='$boss_id' 
        AND c.b_location='$location'
    "));

    // Clients paid
    $clients_paid_field = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients, field_payment WHERE users_id='$user_id' and bosses_id='$boss_id' and clientf_id=client_id and officerid='$officer_id' and pay_date='$d'")); 

    // Clients Advanced mapping 
    $clients_advance = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients, daily_report WHERE users_id='$user_id' and bosses_id='$boss_id' and cliente_id=client_id and b_location='$location' and advance=1"));
    $total_clients_advance_all += $clients_advance;

    // Unpaid logic factoring in those in advance
    $unpaid = $total_no_clients - $clients_paid_field - $clients_advance;

    // Output Location Row
    echo"<td style='padding-left:5px;'>".$location."</td>";
    echo"<td style='padding-left:5px;'>".$total_no_clients."</td>";
    echo"<td style='padding-left:5px;'>".$new_clients_field."</td>";
    echo"<td style='padding-left:5px;'>".$completed_field."</td>";
    echo"<td style='padding-left:5px;'>".$clients_paid_field."</td>";
    echo"<td style='padding-left:5px;'>".$unpaid."</td>";
    echo"<td style='padding-left:5px;'>".$clients_advance."</td>";
    echo"<td style='padding-left:5px;'>".number_format($location_gross)."</td>";
    echo"</tr>";
}

// Global aggregates for the bottom row
$total_no_loans = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients, loans WHERE users_id='$user_id' and bosses_id='$boss_id' and b_date='$d' and cliente_id=client_id ")); 
$no_clients_paid = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients, loan_pay WHERE users_id='$user_id' and bosses_id='$boss_id' and clients_id=client_id and p_date='$d'")); 
$total_clients_advance = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients, daily_report WHERE users_id='$user_id' and bosses_id='$boss_id' and cliente_id=client_id and advance=1"));

$global_unpaid = (isset($loan_clients) ? $loan_clients : 0) - $no_clients_paid - $total_clients_advance;

// Render Bottom Total Row with Global Values (Global variables already declared higher in your scripts)
echo "<tr>
<td style='padding-left:5px;'>Total</td>
<td style='padding-left:5px;'>".($loan_clients ?? 0)."</td>
<td style='padding-left:5px;'>$new_clients</td>
<td style='padding-left:5px;'>$completed</td>
<td style='padding-left:5px;'>$no_clients_paid</td>
<td style='padding-left:5px;'>$global_unpaid</td>
<td style='padding-left:5px;'>$total_clients_advance</td>
<td style='padding-left:5px;'>".number_format($grand_total_field_received)."</td> 
</tr></table> <br>";


echo "<br><p align=center><b>CASH AT HAND: ".number_format($cs)."</b></p><br>";
?>

<table border=0 align=center width=60% align=center style='font-size:13px; font-weight: bold;'>
<tr>
<td>
<p align="center">
<form method="POST" action="notifier_boss.php" target="_blank">

<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
<input type="hidden" name="boss_id" value="<?php echo $boss_id;?>">
<input type="hidden" name="msg" value="<?php echo $msg ?? '';?>">
<input type="hidden" name="msg_date" value="<?php echo $d;?>">
 
<button type="submit" name="send" class="button is-default" 
style="border: 1px solid; border-radius:4px; color:white; background-color: #006F37;">
Send Report</button>             
</form>
</td>
<td>
<a href="user_homepage.php" class="button is-default" 
style="border: 1px solid; border-radius:4px; color:white; margin-left:0px; background-color: #006F37;">
 BACK</a>
</p>
</td>
</tr>
</table>
</div>
</div>
 
</main>
</body> 
</html>
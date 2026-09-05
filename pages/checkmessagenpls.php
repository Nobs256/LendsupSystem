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
    $d = $date;
    $tomorow = date("Y-m-d", strtotime("$d +1 day"));
} else {
    $d = date('Y-m-d');
    $d = date("Y-m-d", strtotime("$d -1 day"));
    $tomorow  = date("Y-m-d", strtotime("$d +1 day"));
    $date = $d;
}

$results = mysqli_fetch_assoc(mysqli_query($conn,"select user_id, boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, branch, users_image from new_users where user_id='$user_id'"));
$user_id = $results["user_id"];
$boss_id = $results["boss_id"];
$bra = $results["branch"];

$loan_clients = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients_with_loan where userseid='$user_id' and bosseseid='$boss_id' and debt>0")); 

// Optimized Summary Data Collection using SQL aggregate functions
$sum_query = mysqli_query($conn, "
    SELECT 
        IFNULL((SELECT SUM(amount_paid) FROM loan_pay WHERE p_date='$d' AND userse_id='$user_id' AND bossese_id='$boss_id' AND mom=0), 0) as total_amount,
        IFNULL((SELECT SUM(op_amount) FROM op WHERE op_date='$d' AND userop_id='$user_id' AND bossop_id='$boss_id'), 0) as total_op,
        IFNULL((SELECT SUM(paid_amount) FROM shortage WHERE userrec_id='$user_id' AND bossrec_id='$boss_id' AND recovered=0 AND rec_date='$d'), 0) as total_shortage,
        IFNULL((SELECT SUM(cr_amount) FROM cr WHERE cr_date='$d' AND usercr_id='$user_id' AND bosscr_id='$boss_id'), 0) as cash_from_branch,
        IFNULL((SELECT SUM(cr_amount) FROM sent_to_branch WHERE cr_date='$d' AND usercr_id='$user_id' AND bosscr_id='$boss_id'), 0) as cash_to_branch,
        IFNULL((SELECT SUM(de_amount) FROM banking WHERE de_date='$d' AND userde_id='$user_id' AND bossde_id='$boss_id' AND transc='Deposit'), 0) as total_bank_deposit,
        IFNULL((SELECT SUM(de_amount) FROM banking WHERE de_date='$d' AND userde_id='$user_id' AND bossde_id='$boss_id' AND transc='Withdraw'), 0) as total_bank_withdraw,
        IFNULL((SELECT SUM(cost) FROM expenses WHERE exp_date='$d' AND userexp_id='$user_id' AND bossexp_id='$boss_id'), 0) as total_exp,
        IFNULL((SELECT SUM(paid_amount) FROM uknown WHERE rec_date='$d' AND userrec_id='$user_id' AND bossrec_id='$boss_id'), 0) as total_ukno,
        IFNULL((SELECT SUM(paid_amount) FROM excess_short WHERE rec_date='$d' AND userrec_id='$user_id' AND bossrec_id='$boss_id' AND excess_short='Excess'), 0) as total_excess,
        IFNULL((SELECT SUM(amount) FROM withdraws WHERE with_date='$d' AND user_id='$user_id' AND boss_id='$boss_id' AND withdraws='Shortage'), 0) as recovered_shortage,
        IFNULL((SELECT SUM(amount) FROM withdraws WHERE with_date='$d' AND user_id='$user_id' AND boss_id='$boss_id' AND withdraws='Excess'), 0) as recovered_excess,
        IFNULL((SELECT SUM(amount) FROM unknown_cash WHERE unknown_date='$d' AND user_id='$user_id' AND boss_id='$boss_id'), 0) as total_unknown_cash,
        IFNULL((SELECT SUM(amount) FROM withdraw_unknown_cash WHERE unknown_date='$d' AND user_id='$user_id' AND boss_id='$boss_id'), 0) as total_withdraw_unknown_cash,
        IFNULL((SELECT SUM(amount_returned) FROM loan_returned WHERE date='$d' AND user_id='$user_id' AND boss_id='$boss_id'), 0) as total_returned_loans,
        IFNULL((SELECT SUM(amount) FROM trash WHERE pay_date='$d' AND user_id='$user_id' AND boss_id='$boss_id'), 0) as trash
");
$sums = mysqli_fetch_assoc($sum_query);
foreach($sums as $key => $val) { $$key = $val; }

// Aggregated Loan Stats
$loan_stats = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(loan_id) as no_loans, IFNULL(SUM(amount_given), 0) as total_given_loan, IFNULL(SUM(reg_fee), 0) as total_reg_fee 
    FROM loans 
    WHERE b_date='$d' AND userse_id='$user_id' AND bossese_id='$boss_id'
"));
$no_loans = $loan_stats['no_loans'];
$total_given_loan = $loan_stats['total_given_loan'];
$total_reg_fee = $loan_stats['total_reg_fee'];

// Aggregated Loans In Parts Stats
$lip_stats = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT 
        IFNULL(SUM(CASE WHEN part='Part' THEN amount_g ELSE 0 END), 0) as total_loan_in_parts,
        IFNULL(SUM(CASE WHEN part='Part' THEN reg_fee ELSE 0 END), 0) as total_fee_in_parts,
        IFNULL(SUM(CASE WHEN part='Completed' THEN difference ELSE 0 END), 0) as total_loan_diff,
        COUNT(CASE WHEN part='Part' THEN loan_id END) as no_of_loans_inparts
    FROM loans_in_parts 
    WHERE bp_date='$d' AND usersp_id='$user_id' AND bossesp_id='$boss_id'
"));
foreach($lip_stats as $key => $val) { $$key = $val; }

// completed Loans
$completed = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM completed_loan where e_date='$d' and completed=1 and userscpid='$user_id' and bosscpid='$boss_id'"));

// clients paid
$clients_paid = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM loan_pay, clients where userse_id='$user_id' and bossese_id='$boss_id' and p_date='$d' and client_id=clients_id")); 

// payment Rate
$payment_rate = ($loan_clients > 0) ? ceil($clients_paid / $loan_clients * 100) : 0;

// Optimized New Clients count
$new_clients = 0;
$nc_q = mysqli_query($conn, "
    SELECT cliente_id, COUNT(loan_id) as c, MAX(b_date) as last_date
    FROM loans 
    WHERE userse_id='$user_id' AND bossese_id='$boss_id' 
    GROUP BY cliente_id
");
while($r = mysqli_fetch_assoc($nc_q)) {
    if($r['c'] == 1 && $r['last_date'] == $d) {
        $new_clients++;
    }
}

// ======================== CALCULATIONS ========================
$all_loans = $total_given_loan + $total_loan_in_parts - $total_loan_diff;
$total_amount_cash = $total_amount - $recovered_excess - $total_withdraw_unknown_cash; 

$tci = $total_op + $total_amount_cash + $total_reg_fee + $total_fee_in_parts + $total_returned_loans + $recovered_shortage + $total_excess + $total_bank_withdraw + $cash_from_branch + $total_unknown_cash;

$tco = $trash + $all_loans + $total_exp + $cash_to_branch + $total_bank_deposit + $total_shortage;
$totalcoll = $total_amount_cash;

// Cash at Hand
$closing = $tci - $tco;

$trimed = trim(substr($closing,5,2));
if($trimed > 0){
    $closing = $closing - $trimed;
}

if($d == '2025-11-28' && $user_id == 1){
    $total_op = 207000;
}

// company Info
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from bosses"));     
$company = strtoupper($result['firstname']." ".$result['lastname']);

echo "<p align=center><b>$company LTD <br> A  DAILY REPORT - $bra (".date("d-m-Y", strtotime($d)).")</b><br>
<font color=red>Click Send Report and take a Photo and send it to Boss on WhatsApp</font></p>";
?>

<table border="0" align="center" width="60%" style="font-weight: bold; font-size: 15px;">
<tr>
<td style="padding-left:10px;">No Of Clients: <?php echo $loan_clients; ?></td>
<td style="padding-left: 10px;">No Of Clients Paid:  <?php echo $clients_paid; ?></td>
<td style="padding-left: 10px;">New Clients:  <?php echo $new_clients; ?></td>
<td style="padding-left: 10px;">Completed Loans: <?php echo $completed; ?></td>
</table>
<br>

<table border="1" align="center" width="40%" style="font-weight: bold; font-size: 15px;">
<tr>
<td style="padding-left:10px; width: 50% ;">O.P</td><td  style="padding-left: 10px;"><?php echo number_format($total_op) ?></td>
</tr><tr>
<td style="padding-left: 10px;">Process Fee</td><td style="padding-left: 10px;"><?php echo number_format($total_reg_fee+$total_fee_in_parts); ?></td>
</tr><tr>
<td style="padding-left: 10px;">Returned Loans</td><td style="padding-left: 10px;"><?php echo number_format($total_returned_loans); ?></td>
</tr><tr>
<td style="padding-left: 10px;">Cashin</td><td style="padding-left: 10px;"><?php  echo number_format($total_amount_cash)." ($payment_rate %)" ?></td>
</tr><tr>
<td style="padding-left: 10px;">Total Cash</td><td style="padding-left: 10px;"><?php echo number_format($tci); ?></td></tr>
<tr>
<td style="padding-left: 10px;">Closing Stock</td><td style="padding-left: 10px;"><?php echo number_format($closing); ?></td></tr>
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

// Safe Optimized Cash Outs
$officers_map = [];
$off_q = mysqli_query($conn, "SELECT location, firstname, lastname FROM officers WHERE boss_id='$boss_id' AND branch='$bra' AND active=1");
while($off = mysqli_fetch_assoc($off_q)) {
    $officers_map[$off['location']] = strtoupper($off['firstname'] . ' ' . $off['lastname']);
}

$cash_out_query = mysqli_query($conn, "
    SELECT c.firstname, c.lastname, c.phone, c.b_location, l.amount_given, l.b_date, l.cliente_id,
           (SELECT COUNT(*) FROM loans WHERE cliente_id = c.client_id AND userse_id = '$user_id' AND bossese_id='$boss_id') as total_loan_count
    FROM loans l
    JOIN clients c ON l.cliente_id = c.client_id
    WHERE l.userse_id = '$user_id' AND l.bossese_id = '$boss_id' AND l.b_date = '$d'
");

$k = 0;
while($selected = mysqli_fetch_assoc($cash_out_query)){  
    $k++;
    $names = strtoupper($selected["firstname"]." ".$selected["lastname"]);
    $officer = $officers_map[$selected['b_location']] ?? '';
    $type = ($selected['total_loan_count'] == 1) ? "New" : "Old";
    
    echo "<tr>
        <td style='padding-left:5px;'>$k</td>
        <td style='padding-left:10px;'>$names</td>
        <td style='padding-left:10px;'>".number_format($selected["amount_given"])."</td>
        <td style='padding-left:10px;'>$type</td>
        <td style='padding-left:10px;'>".$selected["phone"]."</td>
        <td style='padding-left:10px;'>$officer</td>
    </tr>";
}

echo "<tr>";
echo"<td style='padding-left:10px;'>Total</td>";
echo"<td style='padding-left:10px;'></td>";
echo"<td style='padding-left:10px;'>".number_format($total_given_loan)."</td>";
echo"<td style='padding-left:10px;'></td>";
echo"<td style='padding-left:10px;'></td>";
echo"<td style='padding-left:10px;'></td>";

echo "</tr></table>";
echo "<br>";
?>
<p align=center><b>FIELD PERFORMANCE</b></p>
 
<?php
echo "<table border=1 width=70% align=center style='font-size:12px; font-weight: bold; '>
<tr>
<td style='padding-left:10px;'>Location</td>
<td style='padding-left:10px;'>Clients</td>
<td style='padding-left:10px;'>Paid</td>
<td style='padding-left:10px;'>Unpaid</td>
<td style='padding-left:10px;'>NPL's</td>
<td style='padding-left:10px;'>Total Recieved</td>";

$perf_data = [];
$off_res = mysqli_query($conn, "SELECT officer_id, firstname, lastname, location FROM officers WHERE boss_id='$boss_id' AND branch='$bra'");
while($o = mysqli_fetch_assoc($off_res)) {
    $perf_data[$o['officer_id']] = [
        'location' => $o['location'],
        'total_paid' => 0,
        'clients_paid' => 0,
        'npl_clients' => 0
    ];
}

// 1. Group Field Payments (Count DISTINCT clients to prevent double-counting if they paid twice today)
$pay_q = mysqli_query($conn, "
    SELECT officerid, SUM(amount) as tp, COUNT(DISTINCT clientf_id) as cp 
    FROM field_payment fp
    JOIN clients c ON fp.clientf_id = c.client_id
    WHERE fp.user_id='$user_id' AND fp.boss_id='$boss_id' AND fp.pay_date='$d'
    GROUP BY officerid
");
while($p = mysqli_fetch_assoc($pay_q)) {
    if (isset($perf_data[$p['officerid']])) {
        $perf_data[$p['officerid']]['total_paid'] = $p['tp'];
        $perf_data[$p['officerid']]['clients_paid'] = $p['cp'];
    }
}

// 2. Group Total Clients by Location (Count DISTINCT clients and ONLY those with Debt > 0)
$client_q = mysqli_query($conn, "
    SELECT c.b_location, COUNT(DISTINCT c.client_id) as tc
    FROM clients c
    JOIN clients_with_loan cwl ON c.client_id = cwl.clientsid
    WHERE c.users_id='$user_id' AND c.bosses_id='$boss_id' AND cwl.debt > 0
    GROUP BY c.b_location
");
$loc_clients = [];
while($c = mysqli_fetch_assoc($client_q)) {
    $loc_clients[$c['b_location']] = $c['tc'];
}

// 3. True Defaulters Query (Strictly clients with debt>0 and last payment >= 1 year from the REPORT DATE)
$npl_query = mysqli_query($conn, "
    SELECT 
        c.b_location AS branch_location, 
        COUNT(DISTINCT cwl.clientsid) as npl_clients_count
    FROM clients_with_loan cwl
    JOIN clients c ON cwl.clientsid = c.client_id
    LEFT JOIN (
        SELECT loan_id, MAX(p_date) as last_payment
        FROM loan_pay
        WHERE p_date <= '$d'
        GROUP BY loan_id
    ) lp ON cwl.loan_id = lp.loan_id
    WHERE cwl.userseid='$user_id' 
      AND cwl.bosseseid='$boss_id' 
      AND cwl.debt > 0
      AND (
          lp.last_payment <= DATE_SUB('$d', INTERVAL 1 YEAR)
          OR (lp.last_payment IS NULL AND cwl.pay_date <= DATE_SUB('$d', INTERVAL 1 YEAR))
      )
    GROUP BY c.b_location
");

$npl_clients_by_location = [];
while ($npl_row = mysqli_fetch_assoc($npl_query)) {
    $npl_clients_by_location[$npl_row['branch_location']] = $npl_row['npl_clients_count'];
}

$total_npl_clients_overall = 0;
foreach($perf_data as $oid => $data) {
    $location = $data['location'];
    $total_no_clients = $loc_clients[$location] ?? 0;
    $officer_clients_paid = $data['clients_paid'];
    $npl_clients_in_loc = $npl_clients_by_location[$location] ?? 0;
    
    // Unpaid is now strictly Total Clients - Paid Clients (NPLs are NOT subtracted)
    $unpaid = max(0, $total_no_clients - $officer_clients_paid);
    
    $total_npl_clients_overall += $npl_clients_in_loc;
    
    echo "<tr>
        <td style='padding-left:10px;'>$location</td>
        <td style='padding-left:10px;'>$total_no_clients</td>
        <td style='padding-left:10px;'>$officer_clients_paid</td>
        <td style='padding-left:10px;'>$unpaid</td>
        <td style='padding-left:10px;'>".$npl_clients_in_loc."</td>
        <td style='padding-left:10px;'>".number_format($data['total_paid'])."</td>
    </tr>";
}

$total_unpaid = max(0, $loan_clients - $clients_paid);

echo "<tr>
<td style='padding-left:10px;'>Total</td>
<td style='padding-left:10px;'>$loan_clients</td>
<td style='padding-left:10px;'>$clients_paid</td>
<td style='padding-left:10px;'>$total_unpaid</td>
<td style='padding-left:10px;'>$total_npl_clients_overall</td>
<td style='padding-left:10px;'>".number_format($total_amount)."</td>
</tr><tr>";
?>
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
<?php
include('header_user.php');
//Shortage
$total_shortage=0;
$row_shortage = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(paid_amount), 0) AS total FROM shortage WHERE userrec_id='$user_id' AND bossrec_id='$boss_id' AND recovered=0"));
$total_shortage = $row_shortage['total'];

$display_shortage="";
if($total_shortage>0){
$display_shortage="<font color=red>Shortage Made: ". number_format($total_shortage)."&nbsp;&nbsp;&nbsp;&nbsp;</font>";
}
?>
 <style>
 tr:nth-child(even) {
background-color: #D9FFD9;
}

th, td {
text-align: left;
padding-left: 4px;
 
}
</style>
<main class="column main" style="background-color:#EAEAEA;"> 
<?php include ('summary.php') ?>
<div id="main_container">
<div id="main_heading" style="margin-left:10px">
 
<table border="0">
<tr><td>
<b>DAILY REPORT <?php echo $display_shortage;?> </b> 
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
<div id="main_body" style="border: 0px solid #006F37; color:black"> 
<?php
if(isset($_POST['daily_report'])){
$date = $_POST['date'];
$d=date("Y-m-d", strtotime($date));
$tomorow = date("Y-m-d", strtotime("$d +1 day"));
}
else{
$d=date('Y-m-d');
$tomorow  = date("Y-m-d", strtotime("$d +1 day"));
$date=$d;
}

$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(amount_paid), 0) AS total FROM loan_pay WHERE p_date='$d' AND userse_id='$user_id' AND bossese_id='$boss_id' AND mom=0"));
$total_amount = $row['total'];

//total op
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(op_amount), 0) AS total FROM op WHERE op_date='$d' AND userop_id='$user_id' AND bossop_id='$boss_id'"));
$total_op = $row['total'];
if ($user_email === 'iganga' && $d === '2026-09-02') {
    $total_op = 21000;
}

//total op_mom
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(op_amount), 0) AS total FROM op_mom WHERE op_date='$d' AND userop_id='$user_id' AND bossop_id='$boss_id'"));
$total_op_mom = $row['total'];

//Cr from another branch
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(cr_amount), 0) AS total FROM cr WHERE cr_date='$d' AND usercr_id='$user_id' AND bosscr_id='$boss_id'"));
$cash_from_branch = $row['total'];

//Money sent to another branch
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(cr_amount), 0) AS total FROM sent_to_branch WHERE cr_date='$d' AND usercr_id='$user_id' AND bosscr_id='$boss_id'"));
$cash_to_branch = $row['total'];

//deposited
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(de_amount), 0) AS total FROM banking WHERE de_date='$d' AND userde_id='$user_id' AND bossde_id='$boss_id' AND transc='Deposit'"));
$total_bank_deposit = $row['total'];

//Money Withdrawn
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(de_amount), 0) AS total FROM banking WHERE de_date='$d' AND userde_id='$user_id' AND bossde_id='$boss_id' AND transc='Withdraw'"));
$total_bank_withdraw = $row['total'];

//Expense
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(cost), 0) AS total FROM expenses WHERE exp_date='$d' AND userexp_id='$user_id' AND bossexp_id='$boss_id'"));
$total_exp = $row['total'];

//Loans & Reg Fee
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS no_loans, IFNULL(SUM(amount_given), 0) AS total_given_loan, IFNULL(SUM(reg_fee), 0) AS total_reg_fee FROM loans WHERE b_date='$d' AND userse_id='$user_id' AND bossese_id='$boss_id'"));
$no_loans = $row['no_loans'];
$total_given_loan = $row['total_given_loan'];
$total_reg_fee = $row['total_reg_fee'];

//Uknown
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(paid_amount), 0) AS total FROM uknown WHERE rec_date='$d' AND userrec_id='$user_id' AND bossrec_id='$boss_id'"));
$total_ukno = $row['total'];

//withdraw Unknown Cash
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(amount), 0) AS total FROM withdraw_unknown_cash WHERE unknown_date='$d' AND user_id='$user_id' AND boss_id='$boss_id'"));
$total_withdraw_unknown_cash = $row['total'];

//Excess
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(paid_amount), 0) AS total FROM excess_short WHERE rec_date='$d' AND userrec_id='$user_id' AND bossrec_id='$boss_id' AND excess_short='Excess'"));
$total_excess = $row['total'];

//MOM WithDrawa
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(amount), 0) AS total FROM withdraws WHERE with_date='$d' AND user_id='$user_id' AND boss_id='$boss_id' AND withdraws='MOM'"));
$total_mom_withdraws = $row['total'];

//shortage recovered
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(amount), 0) AS total FROM withdraws WHERE with_date='$d' AND user_id='$user_id' AND boss_id='$boss_id' AND withdraws='Shortage'"));
$recovered_shortage = $row['total'];

//Unkown Recovered
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(amount), 0) AS total FROM withdraws WHERE with_date='$d' AND user_id='$user_id' AND boss_id='$boss_id' AND withdraws='Unknown'"));
$recovered_unknown = $row['total'];

//Withdrawn Excess
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(amount), 0) AS total FROM withdraws WHERE with_date='$d' AND user_id='$user_id' AND boss_id='$boss_id' AND withdraws='Excess'"));
$recovered_excess = $row['total'];

//Withdrawn Defaulters Money
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(amount), 0) AS total FROM withdraws WHERE with_date='$d' AND user_id='$user_id' AND boss_id='$boss_id' AND withdraws='Defaulters'"));
$withdraw_defaulters = $row['total'];

//cash
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(amount), 0) AS total FROM cash WHERE pay_date='$d' AND user_id='$user_id' AND boss_id='$boss_id'"));
$cassh = $row['total'];

//Savings
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(amount), 0) AS total FROM savings WHERE save_date='$d' AND user_id='$user_id' AND boss_id='$boss_id'"));
$total_savings = $row['total'];

//withdrAWS
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(amount), 0) AS total FROM withdraw WHERE with_date='$d' AND user_id='$user_id' AND boss_id='$boss_id'"));
$total_withdraws = $row['total'];

//Total deposite using MOM
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(amount_paid), 0) AS total FROM loan_pay WHERE p_date='$d' AND userse_id='$user_id' AND bossese_id='$boss_id' AND mom=1"));
$total_amount_m = $row['total'];
$total_amount_mom = ($total_amount_m + $total_ukno) - $recovered_unknown;

//trash
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(amount), 0) AS total FROM trash WHERE pay_date='$d' AND user_id='$user_id' AND boss_id='$boss_id'"));
$trash = $row['total'];

//Mom Charges Balance 
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(amount), 0) AS total FROM mom_balance WHERE ch_date='$d' AND userch_id='$user_id' AND bossch_id='$boss_id'"));
$mom_balance = $row['total'];

//total unknown Cash
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(amount), 0) AS total FROM unknown_cash WHERE unknown_date='$d' AND user_id='$user_id' AND boss_id='$boss_id'"));
$total_unknown_cash = $row['total'];

//withdraw Unknown Cash (Duplicated logic kept for exact original structure)
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(amount), 0) AS total FROM withdraw_unknown_cash WHERE unknown_date='$d' AND user_id='$user_id' AND boss_id='$boss_id'"));
$total_withdraw_unknown_cash = $row['total'];

//====Loan In Parts
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(amount_g), 0) AS total_loan_in_parts, IFNULL(SUM(reg_fee), 0) AS total_fee_in_parts FROM loans_in_parts WHERE bp_date='$d' AND usersp_id='$user_id' AND bossesp_id='$boss_id' AND part='Part'"));
$total_loan_in_parts = $row['total_loan_in_parts'];
$total_fee_in_parts = $row['total_fee_in_parts'];

$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(difference), 0) AS total FROM loans_in_parts WHERE bp_date='$d' AND usersp_id='$user_id' AND bossesp_id='$boss_id' AND part='Completed'"));
$total_loan_diff = $row['total'];

$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(reg_fee), 0) AS total FROM loans_in_parts, loans WHERE bp_date='$d' AND usersp_id='$user_id' AND bossesp_id='$boss_id' AND part='Completed' AND cliente_id=clientp_id"));
$total_fee_completed = $row['total'];

// Total Returned Loans
$total_returned_loans = 0;
$returned_query = mysqli_query($conn, "SELECT IFNULL(SUM(amount_returned), 0) as total_returned FROM loan_returned WHERE date='$d' AND user_id='$user_id' AND boss_id='$boss_id'");
if($returned_result = mysqli_fetch_assoc($returned_query)){  
    $total_returned_loans = $returned_result['total_returned'];
}

$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM loans_in_parts WHERE bp_date='$d' AND usersp_id='$user_id' AND bossesp_id='$boss_id' AND part='Part'"));
$no_of_loans_inparts = $row['total'];
//====end in parts

$display_mom_balance="";
$display_unknown="";
$display_reco_short="";
$display_reco_excess="";
$display_defaulters="";
$display_excess="";
$display_reco_unknown="";
$display_cash_from_branch="";
$display_cash_to_branch="";
$display_unknown_cash="";
$display_withdraw_unknown_cash="";

if($mom_balance>0){
$display_mom_balance="<font color=red>Mom Charges Balance: ". number_format($mom_balance)."&nbsp;&nbsp;&nbsp;&nbsp;</font>";
}

if($cash_from_branch>0){
$display_cash_from_branch="<font color=red>Payables: ". number_format($cash_from_branch)."&nbsp;&nbsp;&nbsp;&nbsp;</font>";
}
if($cash_to_branch>0){
$display_cash_to_branch="<font color=red>Recievables: ". number_format($cash_to_branch)."&nbsp;&nbsp;&nbsp;&nbsp;</font>";
}

if($recovered_unknown>0){
$display_reco_unknown="<font color=red>Source Known: ". number_format($recovered_unknown)."&nbsp;&nbsp;&nbsp;&nbsp;</font>";
}

if($total_ukno>0){
$display_unknown="<font color=red>Uknown Source: ". number_format($total_ukno)."&nbsp;&nbsp;&nbsp;&nbsp;</font>";
}

if($recovered_shortage>0){
$display_reco_short="<font color=red>Shortage Recovered: ". number_format($recovered_shortage)."&nbsp;&nbsp;&nbsp;&nbsp;</font>";
}

if($recovered_excess>0){
$display_reco_excess="<font color=red>Excess Withdrawn: ". number_format($recovered_excess)."&nbsp;&nbsp;&nbsp;&nbsp;</font>";
}

if($withdraw_defaulters>0){
$display_defaulters="<font color=red>Refundable: ". number_format($withdraw_defaulters)."&nbsp;&nbsp;&nbsp;&nbsp;</font>";
}
//excess
if($total_excess>0){
$display_excess="<font color=red>Excess: ". number_format($total_excess)."&nbsp;&nbsp;&nbsp;&nbsp;</font>";
}

//unknown cash
if($total_unknown_cash>0){
$display_unknown_cash="<font color=red> Unknown Cash: ". number_format($total_unknown_cash)."&nbsp;&nbsp;&nbsp;&nbsp;</font>";
}

//withdraw unknown cash
if($total_withdraw_unknown_cash>0){
$display_withdraw_unknown_cash="<font color=red> Withdraw Unknown Cash: ". number_format($total_withdraw_unknown_cash)."&nbsp;&nbsp;&nbsp;&nbsp;</font>";
}

$total_amount_cash=0;
$total_amount_cash=$total_amount+$total_unknown_cash-$total_withdraw_unknown_cash;

//completed Loans
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM completed_loan WHERE e_date='$d' AND completed=1 AND userscpid='$user_id' AND bosscpid='$boss_id'"));
$completed = $row['total'];

//clients paid
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM loan_pay, clients WHERE userse_id='$user_id' AND bossese_id='$boss_id' AND p_date='$d' AND client_id=clients_id"));
$clients_paid = $row['total'];

//payment Rate
$payment_rate=ceil($clients_paid/$loan_clients*100);

//New Clients
$nc_query = mysqli_query($conn, "SELECT cliente_id FROM loans WHERE userse_id='$user_id' AND bossese_id='$boss_id' GROUP BY cliente_id HAVING COUNT(loan_id)=1 AND MAX(b_date)='$d'");
$new_clients = mysqli_num_rows($nc_query);

if($d=='2026-08-10'){
  $total_op=1372000;
}

//clients paid (Duplicated logic kept for exact original structure)
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM loan_pay, clients WHERE userse_id='$user_id' AND bossese_id='$boss_id' AND p_date='$d' AND client_id=clients_id"));
$clients_paid = $row['total'];

$search_query= mysqli_query($conn,"SELECT client_id, users_id, bosses_id, 
ucase(firstname) as firstname,ucase(lastname) as lastname,  phone, business, b_location, p_date, amount_paid, balance
FROM clients, loan_pay  where p_date='$d' and users_id='$user_id' and bosses_id='$boss_id' and client_id=clients_id order by firstname");

$all_loans=$total_given_loan+$total_loan_in_parts-$total_loan_diff;
$cash_in= $total_amount_cash+$total_reg_fee+$total_fee_in_parts+$total_returned_loans;
$total_cash=$cash_in+$total_op+$total_bank_withdraw;

$cs=$total_cash-$all_loans-$total_exp-$total_bank_deposit;

?>  
<div>
<?php 
echo"<table width=100% border=0><tr><td>";
echo "<p align=center><font size=3>Date: <b> ".date("d-m-Y", strtotime($d))."</b>&nbsp;&nbsp;&nbsp;";  
echo "</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>
<form method='post' action='print_daily_report.php' target='_blank'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='msg_date' value='$date'>
 
<button type='submit' name=send style='border: 1px solid #7C7C7C; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:80px'>
&nbsp;Print&nbsp;</button></form>
</td></tr></table>
<br>";
?>

<table border="0" align="center" width="100%" style="font-weight: bold; font-size: 15px;">
<tr>
<td style="padding-left: 10px;">No. Of Clients Paid:  <?php echo $clients_paid; ?></td>
<td style="padding-left:10px;">Total Amount Paid: <?php echo number_format($total_amount_cash) ." ($payment_rate %)" ?></td>
<td style="padding-left:10px;">Total Loan Given: <?php echo number_format($all_loans); ?></td>
<td style="padding-left: 10px;">Total Process Fee:  <?php echo number_format($total_reg_fee+$total_fee_in_parts); ?></td>
</tr>
<tr>
<td style="padding-left: 10px;">Banking:  <?php echo number_format($total_bank_deposit); ?></td>
<td style="padding-left: 10px;">Cash Recieved: <?php echo number_format($total_bank_withdraw); ?></td>
<td style="padding-left: 10px;">Total Expenses: <?php echo number_format($total_exp); ?></td>
<td style="padding-left: 10px;">
Total Returned Loans: <?php echo number_format($total_returned_loans); ?>
<td style="padding-left: 10px;">
<?php
echo "&nbsp;"
.$display_unknown_cash."&nbsp;&nbsp;&nbsp;&nbsp;"
.$display_excess."&nbsp;&nbsp;&nbsp;&nbsp;"
.$display_reco_short." &nbsp;&nbsp;&nbsp;&nbsp;"
.$display_reco_excess." &nbsp;&nbsp;&nbsp;&nbsp;"
.$display_withdraw_unknown_cash."&nbsp;&nbsp;&nbsp;&nbsp;";
?>
</td>
</table>
<br>

<table border="1" align="center" width="40%" style="font-weight: bold; font-size: 15px;">
<tr>
<td style="padding-left:10px;">O.P</td><td style="padding-left: 10px;"><?php echo number_format($total_op) ?></td></tr>
<td style="padding-left: 10px;">Cash In</td><td style="padding-left: 10px;"><?php  echo number_format($total_amount_cash+$total_reg_fee); ?></td></tr>
<td style="padding-left: 10px;">Total Cash</td><td style="padding-left: 10px;"><?php echo number_format($total_cash); ?></td></tr>
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

?>
<p align=center><b>CASH OUTS</b></p>
 
<?php

echo "<table border=1 width=70% align=center style='font-size:13px; font-weight:bold'>
<tr>
<td>&nbsp;&nbsp;Name</td>
<td>&nbsp;&nbsp;Amount</td>
<td>&nbsp;&nbsp;Date</td>
<td>&nbsp;&nbsp;Tel</td>
<td>&nbsp;&nbsp;Officer</td>";
//New Clients
$new_clients=0; 
$total_loans=0;
$select = mysqli_query($conn,"SELECT * FROM clients, loans WHERE users_id='$user_id' and bosses_id='$boss_id' 
and b_date='$d' and cliente_id=client_id");
while($selected= mysqli_fetch_array($select)){  
$client_new_id=$selected["client_id"];
$names=strtoupper($selected["firstname"]." ".$selected["lastname"]);
$phone=$selected["phone"];
$location=$selected["b_location"];
$b_date=$selected["b_date"];
$amount_given=$selected["amount_given"];
$total_loans+=$amount_given;
echo "<tr>";

$hup=mysqli_query($conn,"SELECT * FROM loans_in_parts WHERE bp_date='$d' AND clientp_id='$client_id' and usersp_id='$user_id' and bossesp_id='$boss_id'");
if($now=mysqli_fetch_array($hup)){
    $amount_g=$now["amount_g"];
}

//officer
$returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and location='$location' and branch='$bra' and active=1 "));     
$officer = strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);

$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM loans WHERE cliente_id='$client_new_id' and userse_id='$user_id' and bossese_id='$boss_id'"));
$no_of_loans = $row['total'];

if($no_of_loans==1 && $b_date==$d){
$new_clients="New";
}
else{
$new_clients="Old";
}

echo"<td style='padding-left:10px;'>".$names."</td>";
echo"<td style='padding-left:10px;'>".number_format($amount_given)."</td>";
echo"<td style='padding-left:10px;'>".$new_clients."</td>";
echo"<td style='padding-left:10px;'>".$phone."</td>";
echo"<td style='padding-left:10px;'>".$officer."</td>";
echo"</tr>";

}
//====in Parts
$select = mysqli_query($conn,"SELECT * FROM clients, loans_in_parts WHERE usersp_id='$user_id' and bossesp_id='$boss_id' 
and bp_date='$d' and clientp_id=client_id");
while($selected= mysqli_fetch_array($select)){  
$client_new_id=$selected["clientp_id"];
$names=strtoupper($selected["firstname"]." ".$selected["lastname"]);
$phone=$selected["phone"];
$location=$selected["b_location"];
$b_date=$selected["bp_date"];
$amount_given=$selected["amount_g"];
$total_loans+=$amount_given;
echo "<tr>";
 
//officer
$returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and location='$location' and branch='$bra' and active=1 "));     
$officer = strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);

$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM loans WHERE cliente_id='$client_new_id' and userse_id='$user_id' and bossese_id='$boss_id'"));
$no_of_loans = $row['total']; 

if($no_of_loans==1 && $b_date==$d){
$new_clients="New";
}
else{
$new_clients="Old";
}

echo"<td style='padding-left:10px;'>".$names."</td>";
echo"<td style='padding-left:10px;'>".number_format($amount_given)."</td>";
echo"<td style='padding-left:10px;'>".$new_clients."</td>";
echo"<td style='padding-left:10px;'>".$phone."</td>";
echo"<td style='padding-left:10px;'>".$officer."</td>";
echo"</tr>";
}
echo "<tr>";
echo"<td style='padding-left:10px;'>Total</td>";
echo"<td style='padding-left:10px;'>".number_format($all_loans)."</td>";
echo"<td style='padding-left:10px;'></td>";
echo"<td style='padding-left:10px;'></td>";
echo"<td style='padding-left:10px;'></td>";

echo "</tr></table>";

echo "<br>";
?>
<p align=center><b>FIELD PERFORMANCE</b></p>
 
<?php
echo "<table border=1 width=60% align=center style='font-size:13px; font-weight: bold; '>
<tr>
<td style='padding-left:10px;'>Location</td>
<td style='padding-left:10px;'>No. of Clients</td>
<td style='padding-left:10px;'>Paid</td>
<td style='padding-left:10px;'>Unpaid</td>
<td style='padding-left:10px;'>Amount Recieved</td>
</tr>";
 
//New Clients
$new_clients=0; 
$unpaid=0;
$field_total_amount = 0; // NEW: Running total for the Field Performance column

$curr_month=date("m");
$pre_month=date("m", strtotime("$curr_month -2 month"));

$select = mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and branch='$bra'");
while($selected= mysqli_fetch_array($select)){  
    $officer_id=$selected["officer_id"];
    $names=strtoupper($selected["firstname"]." ".$selected["lastname"]);
    $phone=$selected["phone"];
    $location=$selected["location"];
    echo "<tr>";

    //total amount paid per officer (Renamed variable to $officer_paid)
    $returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(amount), 0) as totalPaid
     FROM clients, field_payment where users_id='$user_id' and bosses_id='$boss_id' and clientf_id=client_id and officerid='$officer_id' and pay_date='$d'"));     
    $officer_paid = $returned_result["totalPaid"];

    //total unknown Cash per officer (Renamed variable to $officer_unknown_cash)
    $returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(amount), 0) as totalPaid
     FROM unknown_cash where unknown_date='$d' and user_id='$user_id' and boss_id='$boss_id' and officer='$officer_id'"));     
    $officer_unknown_cash = $returned_result["totalPaid"];
    
    // Calculate row total and add to running total
    $officer_row_total = $officer_paid + $officer_unknown_cash;
    $field_total_amount += $officer_row_total;

    //total no of clients
    $row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM clients, clients_with_loan WHERE users_id='$user_id' and bosses_id='$boss_id' and clientsid=client_id and b_location='$location'"));
    $total_no_clients = $row['total'];

    //total no of loans
    $row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM clients, loans WHERE users_id='$user_id' and bosses_id='$boss_id' and b_date='$d' and cliente_id=client_id and b_location='$location'"));
    $total_no_loans = $row['total'];
     
    //clients paid
    $row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM clients, field_payment WHERE users_id='$user_id' and bosses_id='$boss_id' and clientf_id=client_id and officerid='$officer_id' and pay_date='$d'"));
    $clients_paid = $row['total'];

    //clients Advanced
    $row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM clients, daily_report WHERE users_id='$user_id' and bosses_id='$boss_id' and cliente_id=client_id and b_location='$location' and advance=1"));
    $clients_advance = $row['total'];

    $row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(DISTINCT clients_id) AS total FROM loan_pay, daily_report WHERE usersed_id='$user_id' and bossesed_id='$boss_id' and p_date='$d' and cliente_id=clients_id and location='$location' and advance=1"));
    $clients_with_advance_paid = $row['total'];

    $clients_with_advance_un_paid=$clients_advance-$clients_with_advance_paid;
    $unpaid=$total_no_clients-$clients_paid;

    echo"<td style='padding-left:10px;'>".$location."</td>";
    echo"<td style='padding-left:10px;'>".$total_no_clients."</td>";
    echo"<td style='padding-left:10px;'>".$clients_paid."</td>";
    echo"<td style='padding-left:10px;'>".$unpaid."</td>";
    echo"<td style='padding-left:10px;'>".number_format($officer_row_total)."</td>";
    echo"</tr>";
}

//total no of loans globally
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM clients, loans WHERE users_id='$user_id' and bosses_id='$boss_id' and b_date='$d' and cliente_id=client_id"));
$total_no_loans = $row['total'];

$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM loan_pay, clients where userse_id='$user_id' and bossese_id='$boss_id' and p_date='$d' and client_id=clients_id"));
$no_clients_paid  = $row['total'];

//Total clients Advanced
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM clients, daily_report WHERE users_id='$user_id' and bosses_id='$boss_id' and cliente_id=client_id and advance=1"));
$total_clients_advance = $row['total'];

$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(DISTINCT clients_id) AS total FROM loan_pay, daily_report WHERE usersed_id='$user_id' and bossesed_id='$boss_id' and p_date='$d' and cliente_id=clients_id and advance=1"));
$clients_with_advance_paid = $row['total'];

$clients_with_advance_un_paid=$total_clients_advance-$clients_with_advance_paid;
$total_unpaid=$loan_clients-$no_clients_paid-$total_no_loans-$clients_with_advance_un_paid;

// Output the final accumulated field total
echo "<tr>
<td style='padding-left:10px;'>Total</td>
<td style='padding-left:10px;'>$loan_clients</td>
<td style='padding-left:10px;'>$no_clients_paid</td>
<td style='padding-left:10px;'>$total_unpaid</td>
<td style='padding-left:10px;'>".number_format($field_total_amount)."</td>
</tr>
</table> <br>";

echo "<br><p align=center><b>CASH AT HAND: ".number_format($closing)."</b></p><br>";


$run = mysqli_query($conn, "SELECT op_id FROM op WHERE userop_id='$user_id' AND bossop_id='$boss_id' AND op_date='$tomorow'") or die("Could DB");
$op_tomoro = mysqli_num_rows($run);

if ($op_tomoro==0){ 

mysqli_query($conn,"INSERT INTO op(op_id, userop_id, bossop_id, op_date, op_amount) 
VALUES (NULL, '$user_id', '$boss_id', '$tomorow', '$cs')");

}
else{
$query ="UPDATE op set op_amount='$cs' where op_date='$tomorow' and userop_id='$user_id' and bossop_id='$boss_id'";
$execute = mysqli_query($conn, $query);
}
//OP for MOM
$run = mysqli_query($conn, "SELECT op_id FROM op_mom WHERE userop_id='$user_id' AND bossop_id='$boss_id' AND op_date='$tomorow'") or die("Could DB");
$op_tomoro = mysqli_num_rows($run);

if ($op_tomoro==0){ 

mysqli_query($conn,"INSERT INTO op_mom(op_id, userop_id, bossop_id, op_date, op_amount) 
VALUES (NULL, '$user_id', '$boss_id', '$tomorow', '$cs_mom')");

}
else{
$query ="UPDATE op_mom set op_amount='$cs_mom' where op_date='$tomorow' and userop_id='$user_id' and bossop_id='$boss_id'";
$execute = mysqli_query($conn, $query);
}
 
?>  

</div>
</div>
</main>
</body> 
</html>
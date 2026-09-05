<?php
include('header_officer.php');
//Shortage
$total_shortage=0;
$select = mysqli_query($conn,"SELECT * FROM shortage where 
userrec_id='$user_id' and bossrec_id='$boss_id' and recovered=0");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["paid_amount"]; 
$total_shortage+=$amount;
}

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
<?php include ('summary_officer.php') ?>
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
$total_amount_mom=0;
$total_amount=0;
$total_balance=0;
$total_op=0;
$total_de=0;
$total_exp=0;
$amount_ch=0;
$total_ukno=0;
$total_savings=0;
$total_withdraws=0;
$excess=0;
$shortage=0;
$total_paid_loan=0;
$total_given_loan=0;
$total_reg_fee=0;
$tci=0;
$cs=0;
$total_op_mom=0;

$select = mysqli_query($conn,"SELECT * FROM loan_pay where  p_date='$d'
and userse_id='$user_id' and bossese_id='$boss_id' and mom=0");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_paid"]; 
$total_amount+=$amount;
}


//total op
$total_op=0;
$select = mysqli_query($conn,"SELECT * FROM op where  op_date='$d'
and userop_id='$user_id' and bossop_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$op_amount= $selected["op_amount"]; 
$total_op+=$op_amount;
}

//total op_mom
$select = mysqli_query($conn,"SELECT * FROM op_mom where  op_date='$d'
and userop_id='$user_id' and bossop_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$op_amount= $selected["op_amount"]; 
$total_op_mom+=$op_amount;
}

//Cr from another branch
$cash_from_branch=0;
$select = mysqli_query($conn,"SELECT * FROM cr where  cr_date='$d'
and usercr_id='$user_id' and bosscr_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$cr_amount= $selected["cr_amount"]; 
$cash_from_branch+=$cr_amount;
}

//Money sent to another branch
$cash_to_branch=0;
$select = mysqli_query($conn,"SELECT * FROM sent_to_branch where  cr_date='$d'
and usercr_id='$user_id' and bosscr_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$cr_amount= $selected["cr_amount"]; 
$cash_to_branch+=$cr_amount;
}

//deposited
$total_bank_deposit=0;
$select = mysqli_query($conn,"SELECT * FROM banking where  de_date='$d'
and userde_id='$user_id' and bossde_id='$boss_id' and transc='Deposit'");
while($selected= mysqli_fetch_array($select)){  
$de_amount= $selected["de_amount"]; 
$total_bank_deposit+=$de_amount;
}

//Money Withdrawn
$total_bank_withdraw=0;
$select = mysqli_query($conn,"SELECT * FROM banking where  de_date='$d'
and userde_id='$user_id' and bossde_id='$boss_id' and transc='Withdraw'");
while($selected= mysqli_fetch_array($select)){  
$de_amount= $selected["de_amount"]; 
$total_bank_withdraw+=$de_amount;
}

//Expense
$select = mysqli_query($conn,"SELECT * FROM expenses where  exp_date='$d'
and userexp_id='$user_id' and bossexp_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$ex_amount= $selected["cost"]; 
$total_exp+=$ex_amount;
}

$no_loans=0; 
$select = mysqli_query($conn,"SELECT * FROM loans WHERE b_date='$d' 
and userse_id='$user_id' and bossese_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$given_loan=$selected["amount_given"];
$total_given_loan+=$given_loan;
$no_loans++;
}
 
//reg fee
$select = mysqli_query($conn,"SELECT * FROM loans WHERE b_date='$d' 
 and userse_id='$user_id' and bossese_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$reg_fee=$selected["reg_fee"];
$total_reg_fee+=$reg_fee;
} 

//Uknown
$select = mysqli_query($conn,"SELECT * FROM uknown where  rec_date='$d'
and userrec_id='$user_id' and bossrec_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$ex_amount= $selected["paid_amount"]; 
$total_ukno+=$ex_amount;
}

//withdraw Unknown Cash
$total_withdraw_unknown_cash=0;
$select = mysqli_query($conn,"SELECT * FROM withdraw_unknown_cash where  unknown_date='$d'
and user_id='$user_id' and boss_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$total_withdraw_unknown_cash+=$amount;
}

//Excess
$total_excess=0;
$select = mysqli_query($conn,"SELECT * FROM excess_short where  rec_date='$d'
and userrec_id='$user_id' and bossrec_id='$boss_id' and excess_short='Excess'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["paid_amount"]; 
$total_excess+=$amount;
}

//MOM WithDrawa
$total_mom_withdraws=0;
$select = mysqli_query($conn,"SELECT * FROM withdraws where  with_date='$d'
and user_id='$user_id' and boss_id='$boss_id' and withdraws='MOM'");
while($selected= mysqli_fetch_array($select)){  
$amount1= $selected["amount"]; 
$total_mom_withdraws+=$amount1;
}

//shortage recovered
$recovered_shortage=0;
$select = mysqli_query($conn,"SELECT * FROM withdraws where  with_date='$d'
and user_id='$user_id' and boss_id='$boss_id' and withdraws='Shortage'");
while($selected= mysqli_fetch_array($select)){  
$amount1= $selected["amount"]; 
$recovered_shortage+=$amount1;
}

//Unkown Recovered
$recovered_unknown=0;

$select = mysqli_query($conn,"SELECT * FROM withdraws where  with_date='$d'
and user_id='$user_id' and boss_id='$boss_id' and withdraws='Unknown'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$recovered_unknown+=$amount;
}

//Withdrawn Excess
$recovered_excess=0;

$select = mysqli_query($conn,"SELECT * FROM withdraws where  with_date='$d'
and user_id='$user_id' and boss_id='$boss_id' and withdraws='Excess'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$recovered_excess+=$amount;
}

//Withdrawn Defaulters Money
$withdraw_defaulters=0;

$select = mysqli_query($conn,"SELECT * FROM withdraws where  with_date='$d'
and user_id='$user_id' and boss_id='$boss_id' and withdraws='Defaulters'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$withdraw_defaulters+=$amount;
}
//cash
$cassh=0;

$select = mysqli_query($conn,"SELECT * FROM cash where  pay_date='$d'
and user_id='$user_id' and boss_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$cassh+=$amount;
}

//Savings
$select = mysqli_query($conn,"SELECT * FROM savings where  save_date='$d'
and user_id='$user_id' and boss_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$total_savings+=$amount;
}

//withdrAWS
$select = mysqli_query($conn,"SELECT * FROM withdraw where  with_date='$d'
and user_id='$user_id' and boss_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$total_withdraws+=$amount;
}

//Total deposite using MOM
$total_amount_m=0;
$select = mysqli_query($conn,"SELECT * FROM loan_pay where  p_date='$d'
and userse_id='$user_id' and bossese_id='$boss_id' and mom=1 ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_paid"]; 
$total_amount_m+=$amount;
}
$total_amount_mom=($total_amount_m+$total_ukno)-$recovered_unknown;

//trash
$trash=0;

$select = mysqli_query($conn,"SELECT * FROM trash where  pay_date='$d'
and user_id='$user_id' and boss_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$trash+=$amount;
}

//Mom Charges Balance 
$mom_balance=0;
$amount=0;
$select = mysqli_query($conn,"SELECT * FROM mom_balance where  ch_date='$d'
and userch_id='$user_id' and bossch_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$mom_balance+=$amount;
}

//total unknown Cash
$total_unknown_cash=0;
$select = mysqli_query($conn,"SELECT * FROM unknown_cash where unknown_date='$d'
and user_id='$user_id' and boss_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$total_unknown_cash+=$amount;
}

//withdraw Unknown Cash
$total_withdraw_unknown_cash=0;
$select = mysqli_query($conn,"SELECT * FROM withdraw_unknown_cash where  unknown_date='$d'
and user_id='$user_id' and boss_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$total_withdraw_unknown_cash+=$amount;
}

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
$completed=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM completed_loan where e_date='$d' 
and completed=1 and userscpid='$user_id' and bosscpid='$boss_id'"));

//clients paid
$clients_paid = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM loan_pay, clients where 
userse_id='$user_id' and bossese_id='$boss_id' and p_date='$d' and client_id=clients_id")); 

//payment Rate
$payment_rate=ceil($clients_paid/$loan_clients*100);

//New Clients
$new_clients=0; 
$select = mysqli_query($conn,"SELECT * FROM clients WHERE users_id='$user_id' and bosses_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$client_new_id=$selected["client_id"];

$hup=mysqli_query($conn,"SELECT * FROM loans WHERE b_date='$d' AND cliente_id='$client_new_id' and userse_id='$user_id' and bossese_id='$boss_id'");
$now=mysqli_fetch_array($hup);
$b_date=$now["b_date"];

$no_of_loans = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM loans WHERE cliente_id='$client_new_id' and userse_id='$user_id' and bossese_id='$boss_id'")); 

if($no_of_loans==1 && $b_date==$d){
$new_clients++;
}
}
//clients paid
$clients_paid = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM loan_pay, clients where 
userse_id='$user_id' and bossese_id='$boss_id' and p_date='$d' and client_id=clients_id")); 

$search_query= mysqli_query($conn,"SELECT client_id, users_id, bosses_id, 
ucase(firstname) as firstname,ucase(lastname) as lastname,  phone, business, b_location, p_date, amount_paid, balance
FROM clients, loan_pay  where p_date='$d' and users_id='$user_id' and bosses_id='$boss_id' and client_id=clients_id order by firstname");

$cash_in= $total_amount_cash+$total_reg_fee;
$total_cash=$cash_in+$total_op+$total_bank_withdraw;

$cs=$total_cash-$total_given_loan-$total_exp-$total_bank_deposit;
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

<table border="0" align="center" width="100%"  style="font-weight: bold; font-size: 15px;">
<tr>
<td style="padding-left: 10px;">Clients Paid:  <?php echo $clients_paid; ?></td>
<td style="padding-left:10px;">Total  Paid: <?php echo number_format($total_amount_cash) ." ($payment_rate %)" ?></td>
<td style="padding-left:10px;">Loan Given: <?php echo number_format($total_given_loan); ?></td>
<td style="padding-left: 10px;">Process Fee:  <?php echo number_format($total_reg_fee); ?></td>
</tr>
<tr>
<td style="padding-left: 10px;">Banking:  <?php echo number_format($total_bank_deposit); ?></td>
<td style="padding-left: 10px;">Cash Recieved: <?php echo number_format($total_bank_withdraw); ?></td>
<td style="padding-left: 10px;">Total Expenses: <?php echo number_format($total_exp); ?></td>
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

//officer
$returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and location='$location' and branch='$bra' and active=1 "));     
$officer = strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);


$no_of_loans = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM loans WHERE cliente_id='$client_new_id' and userse_id='$user_id' and bossese_id='$boss_id'")); 

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
echo"<td style='padding-left:10px;'>".number_format($total_loans)."</td>";
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
 
";
//New Clients
$new_clients=0; 
$unpaid=0;
$total_paid=0;
$curr_month=date("m");
$pre_month=date("m", strtotime("$curr_month -2 month"));

$select = mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and branch='$bra'");
while($selected= mysqli_fetch_array($select)){  
$officer_id=$selected["officer_id"];
$names=strtoupper($selected["firstname"]." ".$selected["lastname"]);
$phone=$selected["phone"];
$location=$selected["location"];
echo "<tr>";

//total amount paid
$returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(amount) as totalPaid
 FROM clients, field_payment WHERE user_id='$user_id' and boss_id='$boss_id' and clientf_id=client_id 
 and officerid='$officer_id' and pay_date='$d'"));     
$total_paid = $returned_result["totalPaid"];

//total unknown Cash
$returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(amount) as totalPaid
 FROM unknown_cash where unknown_date='$d' and user_id='$user_id' and boss_id='$boss_id' and officer='$officer_id'"));     
$total_unknown_cash = $returned_result["totalPaid"];

//total no of clients
$total_no_clients = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients, clients_with_loan  WHERE users_id='$user_id' and bosses_id='$boss_id' and clientsid=client_id and b_location='$location'")); 

//total no of loans
$total_no_loans = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients, loans WHERE users_id='$user_id' 
	and bosses_id='$boss_id' and b_date='$d' and cliente_id=client_id and b_location='$location'")); 

//clients paid
$clients_paid = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients, field_payment  WHERE 
users_id='$user_id' and bosses_id='$boss_id' and clientf_id=client_id and officerid='$officer_id' and pay_date='$d'")); 

//clients Advanced
$clients_advance= mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients, daily_report  WHERE users_id='$user_id' and bosses_id='$boss_id' 
and cliente_id=client_id and b_location='$location' and advance=1"));

$clients_with_advance_paid= mysqli_num_rows(mysqli_query($conn,"SELECT DISTINCT clients_id FROM loan_pay, daily_report  WHERE 
usersed_id='$user_id' and bossesed_id='$boss_id' and p_date='$d' and cliente_id=clients_id and location='$location'
 and advance=1"));

$clients_with_advance_un_paid=$clients_advance-$clients_with_advance_paid;
$unpaid=$total_no_clients-$clients_paid;

echo"<td style='padding-left:10px;'>".$location."</td>";
echo"<td style='padding-left:10px;'>".$total_no_clients."</td>";
echo"<td style='padding-left:10px;'>".$clients_paid."</td>";
echo"<td style='padding-left:10px;'>".$unpaid."</td>";
echo"<td style='padding-left:10px;'>".number_format($total_paid+$total_unknown_cash)."</td>";
echo"</tr>";
}
//total no of loans
$total_no_loans = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients, loans WHERE users_id='$user_id' 
	and bosses_id='$boss_id' and b_date='$d' and cliente_id=client_id ")); 

//clients paid
$no_clients_paid = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients, loan_pay  WHERE 
users_id='$user_id' and bosses_id='$boss_id' and clients_id=client_id and p_date='$d'")); 

//Total clients Advanced
$total_clients_advance= mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients, daily_report  WHERE 
users_id='$user_id' and bosses_id='$boss_id' and cliente_id=client_id and advance=1"));

$clients_with_advance_paid= mysqli_num_rows(mysqli_query($conn,"SELECT DISTINCT clients_id FROM loan_pay, daily_report  WHERE 
usersed_id='$user_id' and bossesed_id='$boss_id' and p_date='$d' and cliente_id=clients_id and advance=1"));


$clients_with_advance_un_paid=$total_clients_advance-$clients_with_advance_paid;
$total_unpaid=$loan_clients-$no_clients_paid-$total_no_loans-$clients_with_advance_un_paid;
echo "<tr>
<td style='padding-left:10px;'>Total</td>
<td style='padding-left:10px;'>$loan_clients</td>
<td style='padding-left:10px;'>$no_clients_paid</td>
<td style='padding-left:10px;'>$total_unpaid</td>
<td style='padding-left:10px;'>".number_format($total_amount_cash)."</td>
</table> <br>";

echo "<br><p align=center><b>CASH AT HAND: ".number_format($closing)."</b></p><br>";


$op_tomoro ="SELECT * from op where userop_id='$user_id' and bossop_id='$boss_id' and op_date='$tomorow'";
$run = mysqli_query($conn, $op_tomoro) or die("Could DB");
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
$op_tomoro ="SELECT * from op_mom where userop_id='$user_id' and bossop_id='$boss_id' and op_date='$tomorow'";
$run = mysqli_query($conn, $op_tomoro) or die("Could DB");
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
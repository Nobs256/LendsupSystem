<?php 
session_start();
ob_start();

ini_set('memory_limit', ' 1969994M'); // or you could use 1G
ini_set('max_execution_time', 3600); //300 seconds = 5 minutes
include("conn.php");

if(isset($_POST['send'])){     
$user_id=$_POST['user_id'];
$boss_id=$_POST['boss_id'];
$date=$_POST['msg_date'];
$year=date("Y");
$msg=addslashes(trim($_POST['msg']));
$j=0; 
$to=0;
$sent_date=date("d-m-Y", strtotime($date));
$today=date('Y-m-d');
$d=date("Y-m-d", strtotime($date));


$total_amount_mom=0;
$total_amount=0;
$total_balance=0;
$total_op=0;
$total_de=0;
$total_exp=0;
$amount_ch=0;
$total_ukno=0;
$excess=0;
$shortage=0;
$total_paid_loan=0;
$total_given_loan=0;
$total_reg_fee=0;
$tci=0;
$cs=0;
$total_op_mom=0;

// Date-aware No. of Clients (as at selected date $d)
$loan_clients = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients_with_loan where 
userseid='$user_id' and bosseseid='$boss_id' and debt>0 and pay_date<='$d'")); 

$results = mysqli_fetch_assoc(mysqli_query($conn,"select user_id, boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, branch, 
users_image from new_users where user_id='$user_id'"));
$user_id = $results["user_id"];
$boss_id = $results["boss_id"];
$bra = $results["branch"];


//paid cash
$select = mysqli_query($conn,"SELECT * FROM loan_pay where  p_date='$d'
and userse_id='$user_id' and bossese_id='$boss_id' and mom=0 ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_paid"]; 
$total_amount+=$amount;
}

//total op
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

$total_shortage=0;
$select = mysqli_query($conn,"SELECT * FROM shortage where 
userrec_id='$user_id' and bossrec_id='$boss_id' and recovered=0 and rec_date='$d'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["paid_amount"]; 
$total_shortage+=$amount;
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
$total_ukno=0;
$select = mysqli_query($conn,"SELECT * FROM uknown where  rec_date='$d'
and userrec_id='$user_id' and bossrec_id='$boss_id' and known=0 ");
while($selected= mysqli_fetch_array($select)){  
$ex_amount= $selected["paid_amount"]; 
$total_ukno+=$ex_amount;
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
//Total deposited using MOM
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
$select = mysqli_query($conn,"SELECT * FROM mom_balance where ch_date='$d'
and userch_id='$user_id' and bossch_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$mom_balance+=$amount;
}

//Loan Recovery 
$loan_recovery=0;
$amount=0;
$select = mysqli_query($conn,"SELECT * FROM loan_recovery where  recovery_date='$d'
and user_id='$user_id' and boss_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$loan_recovery+=$amount;
}

//Fuel withdraw 
$fuelw=0;
$amount=0;
$select = mysqli_query($conn,"SELECT * FROM withdraws where  with_date='$d'
and user_id='$user_id' and boss_id='$boss_id' and withdraws='fuel'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$fuelw+=$amount;
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

//Closing Stock Mom
$cs_mom=0;
$cs_mom=($total_op_mom+$total_amount_mom)-$total_mom_withdraws;


//total loan collections
$totalcoll=0;
$total_amount_cash=0;

$total_amount_cash=$total_amount-$recovered_excess-$total_withdraw_unknown_cash;

$tci=$total_op+$total_amount_cash+$total_mom_withdraws+$total_reg_fee+
$recovered_shortage+$total_excess+$total_bank_withdraw+$cash_from_branch+$mom_balance+$loan_recovery+$total_unknown_cash;

$tco=$trash+$total_given_loan+$total_exp+$withdraw_defaulters+$cash_to_branch+
$total_bank_deposit+$total_shortage;

$totalcoll=$total_amount_cash+$total_amount_mom; 
//Cash at Hand
$closing=$tci-$tco;

$trimed=trim(substr($closing,5,2));
if($trimed>0){
$closing=$closing-$trimed;
}

$total_days=0;
$j=0;
$missed_balance=0 ;
$remaining_day=0 ;
 

//================Advanced Arrears ====================
///===================== ========== ===================

$total=0;
$query ="DELETE from daily_report where usersed_id='$user_id' and bossesed_id='$boss_id'";
$execute = mysqli_query($conn, $query);

$search_query= mysqli_query($conn,"SELECT * FROM clients, clients_with_loan where bosses_id='$boss_id' and userseid='$user_id' and client_id=clientsid  order by pay_date Desc");  
while($returned_result = mysqli_fetch_assoc($search_query)){

$client_id=$returned_result["client_id"];
$name = strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);
$phone=$returned_result["phone"];
$user_id  = $returned_result["users_id"];
$loanNo = $returned_result["loan_no"];
$date_given= $returned_result["pay_date"];
$amount_given = $returned_result["amount_given"];
$daily_p = $returned_result["daily_p"];
$location=$returned_result["b_location"];
$int=$returned_result["interest"];
$debt=$returned_result["debt"];
$date_given_loan =date("d-m-Y", strtotime($date_given));

$interest=$amount_given*$int/100;
$total_amount=$amount_given+$interest; 
$curr_date=date('Y-m-d');
$prev_date = date("Y-m-d", strtotime("$curr_date -1 day")); 
//starting date                                              
$first_date = date("Y-m-d", strtotime("$date_given +1 day"));
$end_date = date("Y-m-d", strtotime("$date_given +30 day"));

$pre_yr=date("Y",strtotime($date_given));
$curr_yr=date("Y",strtotime($curr_date));
//First date
$x=0;
$not_paid_at_all=0; 
//No of days
$risk_date=date("Y-m-d", strtotime("$end_date +1 day"));
$defaulter_date=date("Y-m-d", strtotime("$end_date +60 day"));

if($risk_date<$prev_date){  
$x=-1;
$missed_balance=$debt;
}
if($defaulter_date<$prev_date){  
$x=-1;
$missed_balance=$debt;
}

$total_amount_paid=0;
$amount_supposed_paid=0;
$y=0;
//amount paid
$select = mysqli_query($conn,"SELECT * FROM loan_pay where  
clients_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loanNo' ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_paid"]; 
$total_amount_paid+=$amount;
}
//days
$date1=date_create("$curr_date");
$date2=date_create("$date_given");
$diff=date_diff($date1,$date2);
$y=$diff->format("%a");


if($risk_date<=$prev_date){  
$x=30;
$missed_balance=$debt;
 }
if($defaulter_date<$prev_date){  
$x=91;
$missed_balance=$debt;
 }

if($risk_date>$prev_date && $defaulter_date>$prev_date ){
$amount_supposed_paid=$y*$daily_p;
$missed_balance=$amount_supposed_paid-$total_amount_paid;
$x=$missed_balance/$daily_p;
}

if($missed_balance<0){  
$missed_balance=0;
 }
 
if($date_given==$prev_date){
 $x=0;
 $missed_balance=0;  
}
  
if($curr_date==$date_given){ 
 $x=0;                     
 $missed_balance=0; 
}

if($x<0){
 $x=0;
 }

if($risk_date<$curr_date and $x<0){  
$x=30;
$missed_balance=$debt;
 }

 if($missed_balance<$daily_p && $x==0){  
$missed_balance=0;
 }
//advance 
$curr_date=date('Y-m-d');
$date1=date_create("$curr_date");
$date2=date_create("$date_given");
$diff=date_diff($date1,$date2);
$loan_days=$diff->format("%a");

//amount supposed to have paid
$amount_supposed_paid=$loan_days*$daily_p;

$advanced=$total_amount_paid-$amount_supposed_paid;

if($total_amount_paid>$amount_supposed_paid && $y<=30){
$adv=1;
}
else{
$adv=0;
}

$curr_date=date('Y-m-d');
mysqli_query($conn,"INSERT INTO daily_report(loan_id, cliente_id, usersed_id, bossesed_id, b_date, arrears, days_missed, location, advance) 
VALUES (NULL, '$client_id', '$user_id', '$boss_id', '$curr_date', '$missed_balance', '$x', '$location', '$adv')");

}

//fetch data back
$q=mysqli_query($conn,"SELECT * from officers where boss_id='$boss_id' and branch='$bra'");
$total_off=mysqli_num_rows($q);

//branch
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from new_users where user_id='$user_id'"));     
$branch= $result['branch'];

 //company
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
<td style="padding-left:10px;">Process Fee </td><td> <?php echo number_format($total_reg_fee) ?></td>
</td>
</tr>
<tr><td>3.</td>
<td style="padding-left: 10px;">Cashin </td><td> <?php  echo number_format($totalcoll)." ($payment_rate %)" ?></td>
</td>
</tr>
<tr><td>4.</td>
<td style="padding-left:10px;">Total Cash </td><td> <?php echo number_format($totalcoll+$total_reg_fee+$total_op) ?></td>
</td>
</tr> 
<tr><td>5.</td>
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
//New Clients
$new_clients=0; 
$select = mysqli_query($conn,"SELECT * FROM clients, loans WHERE users_id='$user_id' and bosses_id='$boss_id' and b_date='$d' and cliente_id=client_id");
while($selected= mysqli_fetch_array($select)){  
$client_new_id=$selected["client_id"];
$names=strtoupper($selected["firstname"]." ".$selected["lastname"]);
$phone=$selected["phone"];
$location=$selected["b_location"];
$b_date=$selected["b_date"];
$amount_given=$selected["amount_given"];

echo "<tr>";

//officer
$returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and location='$location' "));     
$officer = strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);


$no_of_loans = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM loans WHERE cliente_id='$client_new_id' and userse_id='$user_id' and bossese_id='$boss_id'")); 

if($no_of_loans==1 && $b_date==$d){
$new_clients="New";
}
else{
$new_clients="Old";
}

echo"<td>".$names."</td>";
echo"<td>".number_format($amount_given)."</td>";
echo"<td>".$new_clients."</td>";
echo"<td>".$phone."</td>";
echo"<td>".$officer."</td>";
echo"</tr>";
}

echo "<tr><td>Total Cash Out</td>
<td>".number_format($total_given_loan)."</td><td></td><td></td><td></td></table>";
 ?>

 <br>
  
<p align=center><b>FIELD PERFORMANCE</b></p>
 
<?php
echo "<table border=1 width=100% align=center style='font-size:12px; font-weight: bold; '>
<tr>
<td style='padding-left:10px;'>Location</td>
<td style='padding-left:10px;'>Clients</td>
<td style='padding-left:10px;'>Paid</td>
<td style='padding-left:10px;'>Unpaid</td>
<td style='padding-left:10px;'>Advance</td>
<td style='padding-left:10px;'>Total Recieved</td>
 
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

//total no of clients (Date-aware, as at selected date $d)
$total_no_clients = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients, clients_with_loan  WHERE users_id='$user_id' and bosses_id='$boss_id' and clientsid=client_id and b_location='$location' and pay_date<='$d'")); 
//no of Loans

$no_of_loan = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM loans, clients WHERE cliente_id=client_id and b_date='$d' and userse_id='$user_id' and bossese_id='$boss_id' and b_location='$location'")); 
//clients paid

$clients_paid = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients, field_payment WHERE user_id='$user_id' and boss_id='$boss_id' and clientf_id=client_id 
 and officerid='$officer_id' and pay_date='$d'")); 

//clients Advanced
$clients_advance= mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients, daily_report  WHERE users_id='$user_id' and bosses_id='$boss_id' and cliente_id=client_id and b_location='$location'
 and advance=1"));

 $clients_with_advance_paid= mysqli_num_rows(mysqli_query($conn,"SELECT DISTINCT clients_id FROM loan_pay, daily_report  WHERE 
usersed_id='$user_id' and bossesed_id='$boss_id' and p_date='$d' and cliente_id=clients_id and location='$location'
 and advance=1"));

$clients_with_advance_un_paid=$clients_advance-$clients_with_advance_paid;
$unpaid=$total_no_clients-$clients_paid-$total_no_loans;

$unpaid=$total_no_clients-$clients_paid-$no_of_loan-$clients_with_advance_un_paid;

echo"<td style='padding-left:10px;'>".$location."</td>";
echo"<td style='padding-left:10px;'>".$total_no_clients."</td>";
echo"<td style='padding-left:10px;'>".$clients_paid."</td>";
echo"<td style='padding-left:10px;'>".$unpaid."</td>";
echo"<td style='padding-left:10px;'>".$clients_advance."</td>";
echo"<td style='padding-left:10px;'>".number_format($total_paid)."</td>";
echo"</tr>";

}

//paid cash
$total_amount_paid=0;
$select = mysqli_query($conn,"SELECT * FROM loan_pay where  p_date='$d'
and userse_id='$user_id' and bossese_id='$boss_id' and mom=0 ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_paid"]; 
$total_amount_paid+=$amount;
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
<td style='padding-left:10px;'>$total_clients_advance</td>
<td style='padding-left:10px;'>".number_format($total_amount_paid)."</td>
</table> <br>";

echo "<br><p align=center><b>Closing Stock: ".number_format($closing)."</b></p><br>";

}
include("mpdf60/mpdf.php");
$mpdf=new mPDF('P','A3'); 
$mpdf = new mPDF(); $stylesheet = file_get_contents('mpdf60/pdf.css'); $mpdf->WriteHTML($stylesheet,1);
$mpdf->SetDisplayMode('fullpage');
//$mpdf->AddPage("L");

// LOAD a stylesheet
$stylesheet = file_get_contents('mpdf60/mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this is css/style only and no body/html/text
$report_content=ob_get_contents();
ob_clean();

$mpdf->WriteHTML($report_content,2);
$mpdf->Output('RECIEPT.pdf','I');
exit;
?>
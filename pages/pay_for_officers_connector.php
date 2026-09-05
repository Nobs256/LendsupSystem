<?php
include('conn.php');
include_once('sms_guard.php');
  //--------------------
 if (isset($_POST['field_pay'])) {
  
$boss_id=$_POST['boss_id'];
$user_id=$_POST['user_id'];
$client_id=$_POST['client_id'];
$officer_id=$_POST['officer_id'];
$b_date=$_POST['b_date'];
$report=$_POST['report'];
$loan_no=$_POST['loan_no'];
$amo=str_replace(",","",$_POST['amo']);
$mom=0;
$reg_fee=0;
$numb=0;
$paid_mom=0;
$printed=0;
$curr_date=date('Y-m-d');
$pre_date = $b_date;
$sent_date=date("Y-m-d", strtotime($b_date));
$withdraw=0;
$pyt_method='Office';
$intrest_paid=0;

if (!sms_credit_available($conn, (int) $user_id, (int) $boss_id, 35, $sms_usernames)) {
  reject_payment_without_sms();
  exit;
}

function countExist($conn,$table,$key)
{
$k=mysqli_query($conn,"SELECT * FROM $table WHERE $key");
$rows=mysqli_num_rows($k);
return $rows;
}

$transc_type="Cash_in";
$hup=mysqli_query($conn,"SELECT * FROM clients WHERE  client_id='$client_id' and users_id='$user_id' and bosses_id='$boss_id'");
$now=mysqli_fetch_array($hup);
$names=$now["firstname"]." ".$now["lastname"];
$number=$now["phone"];

//check whether the be4 date is the database
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id' order by p_date desc limit 1"));     
$entered_prev_date = $result['p_date'];
// POST the Balanace
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients_with_loan where clientsid='$client_id'"));     
$debt= $result['debt'];
$given_date=$result['pay_date'];
$balance=$debt-$amo;
$end_date=date('Y-m-d', strtotime("$given_date +30 day"));

$check=countExist($conn,"loan_pay","p_date='$b_date' AND clients_id='$client_id' AND loanNo='$loan_no' and mom=0");
 
date_default_timezone_set("Africa/Nairobi");
$time=date("H:i:s");
$dateTime = new DateTime($time);
$dateTime=$dateTime->format('H:i:s');

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from new_users where user_id='$user_id' 
and boss_id='$boss_id'")); 
$bra= $result['branch'];


if($amo>$debt){
header("Location: pay_for_officers.php?officer_id=$officer_id&pay_date=$b_date&greater_balance");
}

else if($report==1){
header("Location: pay_for_officers.php?officer_id=$officer_id&pay_date=$b_date&reportsent");
}
   
else if($report==2){
header("Location: pay_for_officers.php?officer_id=$officer_id&pay_date=$b_date&reportnotsent");
}
 
else if($sent_date>$curr_date){
header("Location: pay_for_officers.php?officer_id=$officer_id&pay_date=$b_date&reportsent");
}
else if($check==0)
{

if($balance==0){
$query ="update completed_loan set e_date='$b_date', completed=1 where loans_no=$loan_no and clientcpid='$client_id' and userscpid='$user_id' and bosscpid='$boss_id'";
$execute = mysqli_query($conn, $query);
}

mysqli_query($conn,"INSERT INTO loan_pay(loan_id, loanNo, clients_id, userse_id, bossese_id, p_date, amount_paid, balance, mom, intrest_paid) 
VALUES (NULL, '$loan_no', '$client_id',  '$user_id', '$boss_id', '$b_date', '$amo', '$balance', '$mom', '$intrest_paid')");

mysqli_query($conn,"INSERT INTO loan_pay_daily(loan_id, loanNo, clients_id, userse_id, bossese_id, p_date, amount_paid, balance, mom, intrest_paid) 
VALUES (NULL, '$loan_no', '$client_id',  '$user_id', '$boss_id', '$b_date', '$amo', '$balance', '$mom', '$intrest_paid')");
 

mysqli_query($conn,"INSERT INTO receipts(receipt_id, userrec_id, bossrec_id, clientrec_id, loan_norec_id, rec_date, paid_amount, balancerec, printed) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$loan_no', '$b_date', '$amo', '$balance', '$printed')");
//===update the loans
$query ="UPDATE clients_with_loan set debt='$balance' where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'";
$execute = mysqli_query($conn, $query);

mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id',  '$b_date', '$names',  '$transc_type', '$amo', '$reg_fee', '$mom')");

mysqli_query($conn,"INSERT INTO field_payment(ts_id, user_id, boss_id, clientf_id, pay_date, officerid, amount, mom, pyt_method) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id',  '$b_date', '$officer_id', '$amo', '$mom', '$pyt_method')");


//this is where the sms_api.php is included in the system
$amount_msg=0;
include('sms_api.php');


header("Location: pay_for_officers.php?officer_id=$officer_id&pay_date=$b_date&success");
          
} 
else {
header("Location: pay_for_officers.php?officer_id=$officer_id&paid&loan_no=$loan_no&client_id=$client_id&pay_date=$b_date&amount=$amo");
}

}

//FIELD PAYMENT MOM
//==============Mobile MONEy PAYMENT FIELD++++++

if(isset($_GET['mom'])){
$boss_id=$_GET['boss_id'];
$user_id=$_GET['user_id'];
$client_id=$_GET['client_id'];
$officer_id=$_GET['officer_id'];
$numb=$_GET['numb'];
$date=$_GET['p_date'];
$amo=str_replace(",","",$_GET['amo']);
$paid=str_replace(",","",$_GET['amount_paid']);
$amo=$paid;
$mom=1;
$printed=0;
$curr_date=date('Y-m-d');
$pre_date = date('Y-m-d', strtotime("$curr_date -1 day"));
$reg_fee=0;

$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients_with_loan where clientsid='$client_id'"));     
$debt= $result['debt'];
$loan_no= $result['loan_no'];
$balance=$debt-$paid;
$withdraw=0;

function countExist($conn,$table,$key)
{
$k=mysqli_query($conn,"SELECT * FROM $table WHERE $key");
$rows=mysqli_num_rows($k);
return $rows;
}

$check=countExist($conn,"loan_pay","p_date='$date' AND clients_id='$client_id' and mom=1");
$check2=countExist($conn,"total_mom","phone='$numb' AND user_id='$user_id' ");


if($paid>$debt){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 500px'>The amount entered is greater than balance
<a href='pay_for_field_clients_mom.php?client_id=$client_id&officer_id=$officer_id' style='color:white; margin-left:100px;''>X</a></div>";
}

else if($paid!=$amo){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 500px'>The amount paid is not equal to amount sent
<a href='pay_for_field_clients_mom.php?client_id=$client_id&officer_id=$officer_id' style='color:white; margin-left:100px;''>X</a></div>";
}


else if($check==0)
{
if($check2==0)
{
mysqli_query($conn,"INSERT INTO total_mom(ts_id, phone, user_id, boss_id, total) 
VALUES (NULL, '$numb',  '$user_id', '$boss_id', '$amo')");
}
else{
$total=0;
$total_saved=0;

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM total_mom  where phone='$numb' and user_id='$user_id'
and boss_id='$boss_id'"));
$total = $results["total"];
$total_saved=$total+$amo;

$query ="UPDATE total_mom set total='$total_saved' where phone='$numb' and user_id='$user_id' 
and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);
}

if($balance==0){
$query ="update completed_loan set e_date='$date', completed=1 where loans_no=$loan_no and clientcpid='$client_id' and userscpid='$user_id' and bosscpid='$boss_id'";
$execute = mysqli_query($conn, $query);
}
//Record transaction
$transc_type="Cash_in";
$hup=mysqli_query($conn,"SELECT * FROM clients WHERE  client_id='$client_id' and users_id='$user_id' and bosses_id='$boss_id'");
$now=mysqli_fetch_array($hup);
$names=$now["firstname"]." ".$now["lastname"];
$number=$now["phone"];

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from new_users where user_id='$user_id' 
and boss_id='$boss_id'")); 
$bra= $result['branch'];

 //====enter data in mobile money table
mysqli_query($conn,"INSERT INTO mobile(mobile_id, clientmo_id, usermo_id, bossmo_id, amount_mo, mom_date, phone_sent) 
VALUES (NULL, '$client_id', '$user_id', '$boss_id',  '$amo', '$date', '$numb')");

 //====enter data in pay loan table
mysqli_query($conn,"INSERT INTO loan_pay(loan_id, loanNo, clients_id, userse_id, bossese_id, p_date, amount_paid, balance, mom) 
VALUES (NULL, '$loan_no', '$client_id',  '$user_id', '$boss_id', '$date', '$paid', '$balance', '$mom')");

mysqli_query($conn,"INSERT INTO loan_pay_daily(loan_id, loanNo, clients_id, userse_id, bossese_id, p_date, amount_paid, balance, mom) 
VALUES (NULL, '$loan_no', '$client_id',  '$user_id', '$boss_id', '$date', '$paid', '$balance', '$mom')");

mysqli_query($conn,"INSERT INTO loan_pay_cash(loan_id, loanNo, clients_id, userse_id, bossese_id, p_date, amount_paid, balance, mom) 
VALUES (NULL, '$loan_no', '$client_id',  '$user_id', '$boss_id', '$date', '$paid', '$balance', '$mom')");

 //====enter data in mom Number table
mysqli_query($conn,"INSERT INTO mobile_numbers(mobile_id, usermo_id, bossmo_id, phone, mom_date, names, amount_mo, withdraw, balance) 
VALUES (NULL, '$user_id', '$boss_id', '$numb', '$date', '$names', '$amo', '$withdraw', '$total_saved')");

mysqli_query($conn,"INSERT INTO receipts(receipt_id, userrec_id, bossrec_id, clientrec_id, loan_norec_id, rec_date, paid_amount, balancerec, printed) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$loan_no', '$date', '$paid', '$balance', '$printed')");
//===update the loans
$query ="UPDATE clients_with_loan set debt='$balance' where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'";
$execute = mysqli_query($conn, $query);

$mom=1;
mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id',  '$date', '$names',  '$transc_type', '$amo', '$reg_fee', '$mom')");

mysqli_query($conn,"INSERT INTO field_payment(ts_id, user_id, boss_id, clientf_id, pay_date, officerid, amount, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id',  '$date', '$officer_id', '$amo', '$mom')");

echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:5px; width: 600px'>
 <font size='4'>Data Saved Successfully!</font> 
 <a href='pay_for_field_clients_mom.php?client_id=$client_id&officer_id=$officer_id&pay_date=$date' style='color:white; margin-left:100px;''>X</a>
 
</div>"; 
} 

else
{
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 800px'> 
 
<table border='0'>
<tr>
<td width='500px'><font size='4'>The client has already Paid!&nbsp;&nbsp; Do want to pay More?</font></td>
<td> 
<form method='post' action='pay_for_officers_connector.php'>
<input type=hidden name='client_id' value='$client_id'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='loan_no' value='$loan_no'>
<input type=hidden name='pay_date' value='$date'>
<input type=hidden name='paid' value='$amo'>
<input type=hidden name='amount' value='$paid'>
<input type=hidden name='officer_id' value='$officer_id'>
<input type=hidden name='numb' value='$numb'>
<input type=hidden name='mom' value='$mom'>
<button type='submit' name=pay_loan_connector2 class='button is-default' 
style='padding-top:0px; border: 0px solid #006F37; border-radius:4px; height:15px; color:#006F37; background-color:red; color:white'>
&nbsp; Yes &nbsp;</button>
</form>
</td>
<td>
<a href='search_client_pay_loan.php?reload=1' style='color:white; margin-left:100px;''>No</a>
</td></tr></table>

</div>";
}
}

//=====================PAY field MOM More than Once
if(isset($_POST['pay_loan_connector2'])){  
$user_id=$_POST['user_id'];
$boss_id=$_POST['boss_id'];
$client_id=$_POST['client_id'];
$b_date=$_POST['pay_date'];
$loan_no=$_POST['loan_no'];
$officer_id=$_POST['officer_id'];
$amo= $_POST['amount'];
$amount_sent= $_POST['paid'];
$numb= $_POST['numb'];
$printed=0; 
$day_total=0;
$total=0;
$mom=1;

function countExist($conn,$table,$key)
{
$k=mysqli_query($conn,"SELECT * FROM $table WHERE $key");
$rows=mysqli_num_rows($k);
return $rows;
}
$check=countExist($conn,"field_payment"," user_id='$user_id' AND officerid='$officer_id'
AND pay_date='$b_date' AND clientf_id='$client_id'");

$transc_type="Cash_in";
$hup=mysqli_query($conn,"SELECT * FROM clients WHERE  client_id='$client_id' and users_id='$user_id' and bosses_id='$boss_id'");
$now=mysqli_fetch_array($hup);
$names=$now["firstname"]." ".$now["lastname"];
$number=$now["phone"];

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from new_users where user_id='$user_id' 
and boss_id='$boss_id'")); 
$bra= $result['branch'];
 
//=================More Paying with MOM 
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients_with_loan where clientsid='$client_id'"));     
$debt= $result['debt'];
$balance=$debt-$amo;

if($amo>$debt){
header("Location: pay_for_field_clients_mom.php?balance&officer_id=$officer_id&client_id=$client_id");    
}

else{
if($balance==0){
$query ="update completed_loan set e_date='$b_date', completed=1 where loans_no=$loan_no and clientcpid='$client_id' and userscpid='$user_id' and bosscpid='$boss_id'";
$execute = mysqli_query($conn, $query);
}

$transc_type="Cash_in";
$hup=mysqli_query($conn,"SELECT * FROM clients WHERE  client_id='$client_id' and users_id='$user_id' and bosses_id='$boss_id'");
$now=mysqli_fetch_array($hup);
$names=$now["firstname"]." ".$now["lastname"];
$number=$now["number"];

$search_query= mysqli_query($conn,"SELECT * FROM loan_pay  where  loanNo='$loan_no' and 
clients_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id' and p_date='$b_date' and mom=1");
while($returned_result = mysqli_fetch_assoc($search_query)){
 
$amount_paid=$returned_result["amount_paid"];
$total=$total+$amount_paid;
}

$search_query= mysqli_query($conn,"SELECT * FROM mobile where clientmo_id='$client_id' and usermo_id='$user_id' and bossmo_id='$boss_id' and mom_date='$b_date' ");
while($returned_result = mysqli_fetch_assoc($search_query)){
 
$amount_mo=$returned_result["amount_mo"];
$total_amount_mo+=$amount_mo;
}

$day_total=$total+$amo; 
$day_mobile=$total_amount_mo+$amount_sent;

$total=0;
$total_saved=0;
$mom=1;

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM total_mom  where phone='$numb' and user_id='$user_id'
and boss_id='$boss_id'"));
$total = $results["total"];
$total_saved=$total+$amount_sent;

$query ="UPDATE total_mom set total='$total_saved' where phone='$numb' and user_id='$user_id' 
and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);

$query ="UPDATE mobile set amount_mo='$day_mobile'  where clientmo_id='$client_id' and usermo_id='$user_id' and bossmo_id='$boss_id' and mom_date='$b_date' ";
$execute = mysqli_query($conn, $query);

$query ="UPDATE loan_pay set balance='$balance', amount_paid='$day_total'  where clients_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loan_no' and p_date='$b_date' ";
$execute = mysqli_query($conn, $query);

$query ="UPDATE loan_pay_daily set balance='$balance', amount_paid='$day_total'  where clients_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loan_no' and p_date='$b_date' ";
$execute = mysqli_query($conn, $query);

$query ="UPDATE receipts set balancerec='$balance', paid_amount='$day_total'  where clientrec_id='$client_id' and userrec_id='$user_id' and bossrec_id='$boss_id' and loan_norec_id='$loan_no' and rec_date='$b_date' ";
$execute = mysqli_query($conn, $query);

if($check==1){
$query ="UPDATE field_payment set amount='$day_total'  where clientf_id='$client_id' and user_id='$user_id' and boss_id='$boss_id' and officerid='$officer_id' and pay_date='$b_date' ";
$execute = mysqli_query($conn, $query);
 }
 else{
 mysqli_query($conn,"INSERT INTO field_payment(ts_id, user_id, boss_id, clientf_id, pay_date, officerid, amount, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id',  '$b_date', '$officer_id', '$amo', '$mom')");

 }
//===update the loans
$query ="update clients_with_loan set debt='$balance' where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'";
$execute = mysqli_query($conn, $query);

mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id',  '$b_date', '$names',  '$transc_type', '$amo', '$reg_fee', '$mom')");

 //====enter data in mom Number table
$withdraw=0;
mysqli_query($conn,"INSERT INTO mobile_numbers(mobile_id, usermo_id, bossmo_id, phone, mom_date, names, amount_mo, withdraw, balance) 
VALUES (NULL, '$user_id', '$boss_id', '$numb', '$b_date', '$names', '$amo', '$withdraw', '$total_saved')");
/*
//msg
$amount_msg=0;
$date=date("d-m-Y", strtotime($b_date));
$msg="$date -  Hello  ".strtoupper($names). ", You have Paid UGX $amo to MUSHA-Mengo
 Balance is UGX $balance. Thank You.";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://bluesmsuganda.com/api-sub.php");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch,
CURLOPT_POSTFIELDS,"user=abelk&password=0777842873&sender=".urlencode("QuickAcounts")."&message=".urlencode($msg)."&reciever=$number");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$hamza = curl_exec ($ch);
curl_close ($ch); 

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from total_msg where user_id='$user_id' 
and boss_id='$boss_id'")); 
$total= $result['total'];
$new_total=$total-35;

$query ="UPDATE total_msg set total='$new_total' where user_id='$user_id' and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);
*/

header("Location: pay_for_field_clients_mom.php?success&officer_id=$officer_id&client_id=$client_id");    

}
                         
}
 

?>
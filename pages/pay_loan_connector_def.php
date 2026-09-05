<?php
include('conn.php');
//-------------------------------
 
$boss_id=$_GET['boss_id'];
$user_id=$_GET['user_id'];
$client_id=$_GET['client_id'];
$b_date=$_GET['b_date'];
$loan_no=$_GET['loan_no'];
$amo=str_replace(",","",$_GET['amo']);
$mom=0;
$paid_mom=0;
$reg_fee=0;
$printed=0;
$curr_date=date('Y-m-d');
$pre_date = date('Y-m-d', strtotime("$curr_date -1 day"));
$sent_date=date("Y-m-d", strtotime($b_date));
$paid_unknown=0;

//clients already paid
$ko=mysqli_query($conn,"SELECT * FROM loan_pay WHERE p_date='$b_date' 
AND clients_id='$client_id' AND loanNo='$loan_no' and mom=0");
$guess=mysqli_fetch_object($ko);
$checked=$guess->amount_paid;
 
date_default_timezone_set("Africa/Nairobi");
$time=date("H:i:s");
$dateTime = new DateTime($time);
$dateTime=$dateTime->format('H:i:s');


$transc_type="Cash_in";
$hup=mysqli_query($conn,"SELECT * FROM clients_def WHERE  client_id='$client_id' and users_id='$user_id' and bosses_id='$boss_id'");
$now=mysqli_fetch_array($hup);
$names=$now["firstname"]." ".$now["lastname"];
$number=$now["phone"];
$b_location=$now["b_location"];

$returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and location='$b_location'"));    
$officer_id  = $returned_result["officer_id"];

//messsage_date
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from sent_msgs where user_id='$user_id' 
and boss_id='$boss_id' order by ts_id DESC Limit 1")); 
$msg_date= $result['msg_date'];

//check whether the be4 date is the database
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id' order by p_date desc limit 1"));     
$entered_prev_date = $result['p_date'];

// Get the Balanace
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients_with_loan_def where clientsid='$client_id'"));     
$debt= $result['debt'];
$intrest= $result['interest'];
$given_date=$result['pay_date'];
$intrest_paid=ceil($intrest/100*$amo);
$balance=$debt-$amo;
$end_date=date('Y-m-d', strtotime("$given_date +30 day"));

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from total_msg where user_id='$user_id' 
and boss_id='$boss_id'")); 
$total_msg= $result['total'];

if($amo>$debt){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 500px'>The amount entered is greater than balance
<a href='pay_loan.php?client_id=$client_id' style='color:white; margin-left:100px;''>X</a></div>";
}
 

else if($sent_date>$curr_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>Error! Select Correct date. You are have selected a date above todate!
<a href='pay_loan.php?client_id=$client_id' style='color:white; margin-left:100px;''>X</a></div>";
}

/*else if($total_msg<=0){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>You Cannot do the Payment with out SMS. To buy sms send money to 0777842873
<a href='user_homepage.php' style='color:white; margin-left:70px;''>X</a></div>";
}*/


else if($b_date<=$msg_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>You Have Already Sent Report. Not Allowed to make this Payment
<a href='user_homepage.php' style='color:white; margin-left:70px;''>X</a></div>";
}

else if($checked>0)
{
 echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 800px'> 
 
<table border='0'>
<tr>
<td width='500px'><font size='4'>The client has already Paid!&nbsp;&nbsp; Do want to pay More?</font></td>
<td> 
<form method='post' action='user_connector.php'>
<input type=hidden name='client_id' value='$client_id'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='loan_no' value='$loan_no'>
<input type=hidden name='pay_date' value='$b_date'>
<input type=hidden name='amount' value='$amo'>
<input type=hidden name='mom' value='$mom'>
 
<input type=hidden name='paid' value='$paid_mom'>
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
else
{

if($balance==0){
$query ="update completed_loan_def set e_date='$b_date', completed=1 where loans_no=$loan_no and clientcpid='$client_id' and userscpid='$user_id' and bosscpid='$boss_id'";
$execute = mysqli_query($conn, $query);

mysqli_query($conn,"DELETE FROM clients_with_fines where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id'");
}

//remaining days
$d1 = new DateTime("$curr_date 00:00:00");
$d2 = new DateTime("$end_date 00:00:00");
$interval = $d2->diff($d1);
$remaining_day= $interval->d; 

mysqli_query($conn,"INSERT INTO loan_pay(loan_id, loanNo, clients_id, userse_id, bossese_id, p_date, amount_paid, balance, mom, intrest_paid) 
VALUES (NULL, '$loan_no', '$client_id',  '$user_id', '$boss_id', '$b_date', '$amo', '$balance', '$mom', '$intrest_paid')");

mysqli_query($conn,"INSERT INTO loan_pay_daily(loan_id, loanNo, clients_id, userse_id, bossese_id, p_date, amount_paid, balance, mom, intrest_paid) 
VALUES (NULL, '$loan_no', '$client_id',  '$user_id', '$boss_id', '$b_date', '$amo', '$balance', '$mom', '$intrest_paid')");

mysqli_query($conn,"INSERT INTO receipts(receipt_id, userrec_id, bossrec_id, clientrec_id, loan_norec_id, rec_date, paid_amount, balancerec, printed) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$loan_no', '$b_date', '$amo', '$balance', '$printed')");

mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id',  '$b_date', '$names',  '$transc_type', '$amo', '$reg_fee', '$mom')");

mysqli_query($conn,"INSERT INTO field_payment(ts_id, user_id, boss_id, clientf_id, pay_date, officerid, amount, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id',  '$b_date', '$officer_id', '$amo', '$mom')");
 
 //===update the loans
$query ="update clients_with_loan_def set debt='$balance' where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'";
$execute = mysqli_query($conn, $query);

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from receipts 
where clientrec_id='$client_id' and userrec_id='$user_id' and  bossrec_id='$boss_id' 
and rec_date='$b_date' and loan_norec_id='$loan_no' order by loan_norec_id DESC limit 1"));     
$receipt_id = $result['receipt_id'];


$search_query= mysqli_query($conn,"SELECT * FROM clients_def, clients_with_loan_def where users_id='$user_id' and bosses_id='$boss_id' 
and client_id=clientsid  and client_id='$client_id' order by pay_date");
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id=$returned_result["client_id"];
$f=$returned_result["firstname"];
$l=$returned_result["lastname"];
$name = strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);
$phone  = $returned_result["phone"];
$loc  = $returned_result["b_location"];
$date_given= $returned_result["pay_date"];
$amount_given = $returned_result["amount_given"];
$debt  = $returned_result["debt"];
$loanNo = $returned_result["loan_no"];
$daily_p = $returned_result["daily_p"];
$sex = $returned_result["sex"];
$days = 30;
$loan_type='Daily';
$interest=20;
$completed=0;
$reg_fee=0;
$nid_client=0;
$security="xxxxxxxxxxxx";
$dob=2000;
$marital="Single";
$nid=0;
$place_r="xxxxxx";
$business="xxxxxxxxx";

$curr_date=date('Y-m-d');
$prev_date = date("Y-m-d", strtotime("$curr_date -1 day")); 

mysqli_query($conn,"INSERT INTO clients(client_id, users_id, bosses_id, firstname, lastname, sex, dob, marital, nid, phone,  place_r,  business, b_location) 
VALUES (NULL,  '$user_id', '$boss_id', '$f', '$l', '$sex', '$dob', '$marital',  '$nid', '$phone',  '$place_r',  '$business', '$loc')");

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM clients where  users_id='$user_id' and bosses_id='$boss_id'  order by client_id desc limit 1"));     
$new_client_id=$result['client_id'];

$teacher_query ="update clients set  client_id='$client_id' where client_id='$new_client_id' ";
$execute = mysqli_query($conn,$teacher_query);

mysqli_query($conn,"INSERT INTO loans(loan_id, cliente_id, userse_id, bossese_id, b_date, amount_given, reg_fee, security, nid_client) 
VALUES (NULL, '$client_id', '$user_id', '$boss_id', '$date_given', '$amount_given', '$reg_fee', '$security', '$nid_client')");

mysqli_query($conn,"INSERT INTO clients_with_loan(loan_id, loan_no, clientsid, userseid, bosseseid, pay_date, amount_given, daily_p, debt, interest, days, loan_type) 
VALUES (NULL, '$loanNo', '$client_id', '$user_id', '$boss_id', '$date_given', '$amount_given', '$daily_p', '$debt', '$interest', '$days', '$loan_type')");

mysqli_query($conn,"INSERT INTO completed_loan(loan_id, loans_no, clientcpid, userscpid, bosscpid, pay_date, e_date, amount_given, completed, intrests) 
VALUES (NULL, '$loanNo', '$client_id',  '$user_id', '$boss_id', '$date_given', '$date_given', '$amount_given', '$completed', '$interest')");

mysqli_query($conn,"DELETE FROM clients_def WHERE client_id='$client_id'");
mysqli_query($conn,"DELETE FROM loans_def WHERE cliente_id='$client_id'");	
mysqli_query($conn,"DELETE FROM clients_with_loan_def WHERE clientsid='$client_id'");
mysqli_query($conn,"DELETE FROM completed_loan_def WHERE clientcpid='$client_id'");
}
 
echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:5px; width: 940px'>
<table border='0' width='900px'>
<tr>
<td width='220px'><font size='4'>Loan is Successfully Paid!</td><td>Balance is $balance &nbsp;&nbsp;&nbsp;</font> </td>
<td> 
<form method='post' action='payment_receipt.php' target='_blank'>
<input type=hidden name='client_id' value='$client_id'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='loan_no' value='$loan_no'>
<input type=hidden name='receipt_id' value='$receipt_id'>
<input type=hidden name='pay_date' value='$b_date'>
<button type='submit' name=receipt class='button is-default' 
style='padding-top:1px; border: 1px solid #006F37; border-radius:4px; color:#006F37'>
&nbsp;Print Receipt &nbsp;</button>
</form>
</td>
<td>
<a href='search_client_pay_loan.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</td></tr></table>
</div>";           
} 

?>
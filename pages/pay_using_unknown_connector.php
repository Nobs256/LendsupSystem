<?php
include('conn.php');
//-------------------------------
$unknown_id=$_GET['unknown_id'];
$boss_id=$_GET['boss_id'];
$user_id=$_GET['user_id'];
$client_id=$_GET['client_id'];
$b_date=$_GET['b_date'];
$loan_no=$_GET['loan_no'];
$amo=str_replace(",","",$_GET['amo']);
$numb=$_GET['tel'];
$mom=1;
$paid_mom=0;
$printed=0;
$curr_date=date('Y-m-d');
$pre_date = date('Y-m-d', strtotime("$curr_date -1 day"));
$sent_date=date("Y-m-d", strtotime($b_date));
$type="Unknown";
$paid_unknown=1;
$reg_fee=0;

date_default_timezone_set("Africa/Nairobi");
$time=date("H:i:s");
$dateTime = new DateTime($time);
$dateTime=$dateTime->format('H:i:s');

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
//check whether the be4 date is the database
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id' order by p_date desc limit 1"));     
$entered_prev_date = $result['p_date'];
// Get the Balanace
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients_with_loan where clientsid='$client_id'"));     
$debt= $result['debt'];
$given_date=$result['pay_date'];
$balance=$debt-$amo;
$end_date=date('Y-m-d', strtotime("$given_date +30 day"));

$check=countExist($conn,"loan_pay","p_date='$b_date' AND clients_id='$client_id' AND loanNo='$loan_no' and mom=0 ");

if($amo>$debt){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 500px'>The amount entered is greater than balance
<a href='pay_loan_using_unknown.php?client_id=$client_id&unknown_id=$unknown_id' style='color:white; margin-left:100px;''>X</a></div>";
}
else if($sent_date<$pre_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width:  700px'>Error! Select Correct date. You Must Current Date 
<a href='pay_loan_using_unknown.php?client_id=$client_id&unknown_id=$unknown_id' style='color:white; margin-left:100px;''>X</a></div>";
}
else if($sent_date>$curr_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>Error! Select Correct date. You are have selected a date above todate!
<a href='pay_loan_using_unknown.php?client_id=$client_id&unknown_id=$unknown_id' style='color:white; margin-left:100px;''>X</a></div>";
}
else if($sent_date<$curr_date && $dateTime>'10:00:00'){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width:  700px'>Error! Select Correct date. You are beyond time of Yesterday's Payment 
<a href='pay_loan_using_unknown.php?client_id=$client_id&unknown_id=$unknown_id' style='color:white; margin-left:100px;''>X</a></div>";
}
else if($entered_prev_date>$sent_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>Error! The Date Entered is less than the Date Last Paid!!
<a href='pay_loan_using_unknown.php?client_id=$client_id&unknown_id=$unknown_id' style='color:white; margin-left:100px;''>X</a></div>";
}

else if($check==0)
{
 

$query ="UPDATE uknown set known=1, known_date='$b_date' where uknown_id='$unknown_id'";
$execute = mysqli_query($conn, $query);
 
if($balance==0){
$query ="update completed_loan set e_date='$b_date', completed=1 where loans_no=$loan_no and clientcpid='$client_id' and userscpid='$user_id' and bosscpid='$boss_id'";
$execute = mysqli_query($conn, $query);

mysqli_query($conn,"DELETE FROM clients_with_fines where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id'");
}
//remaining days
$d1 = new DateTime("$curr_date 00:00:00");
$d2 = new DateTime("$end_date 00:00:00");
$interval = $d2->diff($d1);
$remaining_day= $interval->d; 

mysqli_query($conn,"INSERT INTO withdraws(with_id, withdraws, user_id, boss_id, with_date, amount, phone) 
VALUES (NULL, '$type', '$user_id', '$boss_id', '$b_date', '$amo', '$numb')");

mysqli_query($conn,"INSERT INTO loan_pay(loan_id, loanNo, clients_id, userse_id, bossese_id, p_date, amount_paid, balance, mom) 
VALUES (NULL, '$loan_no', '$client_id',  '$user_id', '$boss_id', '$b_date', '$amo', '$balance', '$mom')");

mysqli_query($conn,"INSERT INTO receipts(receipt_id, userrec_id, bossrec_id, clientrec_id, loan_norec_id, rec_date, paid_amount, balancerec, printed) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$loan_no', '$b_date', '$amo', '$balance', '$printed')");
//===update the loans
$query ="update clients_with_loan set debt='$balance' where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'";
$execute = mysqli_query($conn, $query);

mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id',  '$b_date', '$names',  '$transc_type', '$amo', '$reg_fee', '$paid_unknown')");

 
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
<input type=hidden name='pay_date' value='$b_date'>
<button type='submit' name=receipt class='button is-default' 
style='padding-top:1px; border: 1px solid #006F37; border-radius:4px; color:#006F37'>
&nbsp;Print Receipt &nbsp;</button>
</form>
</td>
<td>
<a href='user_homepage.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</td></tr></table>
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
<form method='post' action='user_connector.php'>
<input type=hidden name='client_id' value='$client_id'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='loan_no' value='$loan_no'>
<input type=hidden name='pay_date' value='$b_date'>
<input type=hidden name='amount' value='$amo'>
<input type=hidden name='mom' value='$mom'>
<input type=hidden name='numb' value='$numb'>
<input type=hidden name='paid' value='$paid_mom'>
<input type=hidden name='unknown_id' value='$unknown_id'>
<button type='submit' name=pay_loan_more_unknown class='button is-default' 
style='padding-top:0px; border: 0px solid #006F37; border-radius:4px; height:15px; color:#006F37; background-color:red; color:white'>
&nbsp; Yes &nbsp;</button>
</form>
</td>
<td>
<a href='user_homepage.php?reload=1' style='color:white; margin-left:100px;''>No</a>
</td></tr></table>
</div>";
}
?>
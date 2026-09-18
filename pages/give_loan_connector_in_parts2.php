<?php
include('conn.php');
//--------------------Completing Loan in Parts==========
$boss_id=$_GET['boss_id'];
$user_id=$_GET['user_id'];
$client_id=$_GET['client_id'];
$b_date=$_GET['b_date'];
$amo=str_replace(",","",$_GET['amo']);
$reg_fee=str_replace(",","",$_GET['reg_fee']);
$security=$_GET['security'];
$g_names1=$_GET['g_names1'];
$g_phone1=$_GET['g_phone1'];
$part=$_GET['part'];
$type="Cash_out";
$mom=0;

$loan_type="Daily";
$payment_days=30;
$int=20;

$completed=0; 
$curr_date=date('Y-m-d');
$sent_date=date("Y-m-d", strtotime($b_date));
$pre_date = date('Y-m-d', strtotime("$curr_date -1 day"));

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

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from sent_msgs where user_id='$user_id' 
and boss_id='$boss_id' order by ts_id DESC Limit 1")); 
$msg_date= $result['msg_date'];

$check=countExist($conn,"loans","b_date='$b_date' AND cliente_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id'  ");

if($sent_date>$curr_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>Error! You Selected a Wrong Date
<a href='give_loan.php?client_id=$client_id' style='color:white; margin-left:100px;''>X</a></div>";
}


else if($msg_date && $sent_date<=$msg_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>You Have Already Sent Report. Not Allowed to Enter this Loan
<a href='user_homepage.php' style='color:white; margin-left:70px;''>X</a></div>";
}

else if($check==0)
{
 //client name
$hup=mysqli_query($conn,"SELECT * FROM clients WHERE  client_id='$client_id' and users_id='$user_id' and bosses_id='$boss_id'");
$now=mysqli_fetch_array($hup);
$names=$now["firstname"]." ".$now["lastname"];

$loan_no = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM loans where cliente_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id'"));
$loan_no=$loan_no+1;

$total_given=0;
$select = mysqli_query($conn,"SELECT * FROM loans_in_parts where usersp_id='$user_id' and bossesp_id='$boss_id' and loan_no='$loan_no' and clientp_id='$client_id'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_g"]; 
$total_given+=$amount;
}
$total_given+=$amo;
$interest=20/100*$total_given;
$total=$total_given+$interest;
$daily_p=$total/30;

$diff=$total_given-$amo;

mysqli_query($conn,"INSERT INTO loans(loan_id, cliente_id, userse_id, bossese_id, b_date, amount_given, security, nid_client, reg_fee) 
VALUES (NULL, '$client_id', '$user_id', '$boss_id', '$b_date', '$total_given', '$security', '$nid_client', '$reg_fee')");

mysqli_query($conn,"INSERT INTO guarantor(guarantor_id, clientg_id, usersg_id, bossesg_id, g_date,  g_names1, g_phone1) 
VALUES (NULL, '$client_id', '$user_id', '$boss_id', '$b_date', '$g_names1', '$g_phone1')");

mysqli_query($conn,"INSERT INTO clients_with_loan(loan_id, loan_no, clientsid, userseid, bosseseid, pay_date, amount_given, daily_p, debt, interest, days, loan_type) 
VALUES (NULL, '$loan_no', '$client_id', '$user_id', '$boss_id', '$b_date', '$total_given', '$daily_p', '$total', '$int', '$payment_days', '$loan_type')");

mysqli_query($conn,"INSERT INTO completed_loan(loan_id, loans_no, clientcpid, userscpid, bosscpid, pay_date, e_date, amount_given, completed, intrests) 
VALUES (NULL, '$loan_no', '$client_id',  '$user_id', '$boss_id', '$b_date', '$b_date', '$total_given', '$completed', '$int')");

mysqli_query($conn,"INSERT INTO loans_in_parts(loan_id, loan_no, clientp_id, usersp_id, bossesp_id, bp_date, amount_g, part, reg_fee, difference) 
VALUES (NULL, '$loan_no', '$client_id',  '$user_id', '$boss_id', '$b_date', '$amo', '$part', '$reg_fee', '$diff')");

mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id',  '$b_date', '$names',  '$type', '$amo', '$reg_fee', '$mom')");

mysqli_query($conn,"DELETE FROM demand WHERE clientde_id='$client_id'");

echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>Data Saved Successfully
<a href='user_homepage.php' style='color:white; margin-left:100px;''>X</a></div>";

}
else
{
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>The Loan is already given!
<a href='search_client_give_loan_in_parts.php?reload=1' style='color:white; margin-left:100px;''>X</a></div>";
}
?>
<?php
include('conn.php');

 //--------------------
$boss_id=$_GET['boss_id'];
$user_id=$_GET['user_id'];
$client_id=$_GET['client_id'];
$b_date=$_GET['b_date'];
$amo=str_replace(",","",$_GET['amo']);
$reg_fee=str_replace(",","",$_GET['reg_fee']);
$part=$_GET['part'];
$diff=0;

$type="Cash_out";
$mom=0;
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

$loan_no = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM loans where cliente_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id'"));
$loan_no=$loan_no+1;

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

mysqli_query($conn,"INSERT INTO loans_in_parts(loan_id, loan_no, clientp_id, usersp_id, bossesp_id, bp_date, amount_g, part, reg_fee, difference) 
VALUES (NULL, '$loan_no', '$client_id',  '$user_id', '$boss_id', '$b_date', '$amo', '$part', '$reg_fee', '$diff')");

mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id',  '$b_date', '$names',  '$type', '$amo', '$reg_fee', '$mom')");

mysqli_query($conn,"DELETE FROM demand WHERE clientde_id='$client_id'");

echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>Data Saved Successfully!
<a href='user_homepage.php' style='color:white; margin-left:100px;''>X</a></div>";
}
else
{
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>The Loan is already given!
<a href='search_client_give_loan_in_parts.php?reload=1' style='color:white; margin-left:100px;''>X</a></div>";
}



?>
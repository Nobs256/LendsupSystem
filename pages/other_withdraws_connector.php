<?php
//====================OTHER=================
//-===============WITHDRAWS==================
include("conn.php"); 

if(isset($_GET['others'])){
$boss_id=$_GET['boss_id'];
$user_id=$_GET['user_id'];
$withdraw=$_GET['withdraw'];
$date=$_GET['with_date'];
$amo=str_replace(",","",$_GET['amo']);

$curr_date=date('Y-m-d');
$sent_date=date("Y-m-d", strtotime($date));

function countExist($conn,$table,$key)
{
$k=mysqli_query($conn,"SELECT * FROM $table WHERE $key");
$rows=mysqli_num_rows($k);
return $rows;
}
//check the with saving balance
$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM total_savings  where clientts_id='$withdraw' 
and user_id='$user_id' and boss_id='$boss_id'"));
$total = $results["total"];

$check=countExist($conn,"withdraws","with_date=0 AND withdraws='$withdraw' AND user_id='$user_id'");

if($amo>$total && $withdraw=="Excess"){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 900px'>
The amount entered is greater than Total Savings, the possible amount to withdraw
is $total
<a href='withdraws.php' style='color:white; margin-left:100px;''>X</a></div>";
}

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from sent_msgs where user_id='$user_id' 
and boss_id='$boss_id' order by ts_id DESC Limit 1")); 
$msg_date= $result['msg_date'];

if($sent_date>$curr_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>Error! Select Correct date. You are have selected a date above todate!
<a href='add_deposit.php' style='color:white; margin-left:100px;''>X</a></div>";
}

else if($sent_date<=$msg_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>You Have Already Sent Report. Not Allowed to make this transcation
<a href='user_homepage.php' style='color:white; margin-left:70px;''>X</a></div>";
}
else if($check==0)
{
mysqli_query($conn,"INSERT INTO withdraws(with_id, withdraws, user_id, boss_id, with_date, amount) 
VALUES (NULL, '$withdraw',  '$user_id', '$boss_id', '$date', '$amo')");


$names="Excess Withdraws";
$transc_type="Cash_in";
$client_id=0;
$reg_fee=0;
$mom=0;
mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$date', '$names',  '$transc_type', '$amo', '$reg_fee', '$mom')");

//update total savings table

$balance=$total-$amo;

$query ="UPDATE total_savings set total='$balance' where clientts_id='$withdraw' and user_id='$user_id' 
and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);

echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>Data Saved Successfully!
<a href='search_client_withdraw.php' style='color:white; margin-left:100px;''>X</a></div>";
}

else{
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 1000px'> 
 
<table border='0' width='100%''>
<tr>
<td width='650px'><font size='4'>You have have already withdrawn $withdraw today!&nbsp;&nbsp; 
Do want to withdraw More?</font></td>
<td> 
<form method='post' action='user_connector.php'>
<input type=hidden name='withdraw' value='$withdraw'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='with_date' value='$date'>
<input type=hidden name='amount' value='$amo'>
<button type='submit' name=withdraws_more class='button is-default' 
style='padding-top:0px; border: 0px solid #006F37; border-radius:4px; height:15px; color:#006F37; background-color:red; color:white'>
&nbsp; Yes &nbsp;</button>
</form>
</td>
<td>
<a href='withdraws.php?reload=1' style='color:white; margin-left:100px;''>No</a>
</td></tr></table>

</div>";
}
}
?>
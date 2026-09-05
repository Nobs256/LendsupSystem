<?php
include('conn.php');
if(isset($_GET['mom_withdraw'])){
$boss_id=$_GET['boss_id'];
$user_id=$_GET['user_id'];
$numb=$_GET['numb'];
$date=$_GET['with_date'];
$amo=str_replace(",","",$_GET['amo']);
$withdraw="MOM";
$amount=0;
$client_id=0;
$reg_fee=0;
$mom=0;
$curr_date=date('Y-m-d');
function countExist($conn,$table,$key)
{
$k=mysqli_query($conn,"SELECT * FROM $table WHERE $key");
$rows=mysqli_num_rows($k);
return $rows;
}
//check the with saving balance
$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM total_mom  where phone='$numb' 
and user_id='$user_id' and boss_id='$boss_id'"));
$total = $results["total"];

$check=countExist($conn,"withdraws","with_date='$date' AND withdraws='$withdraw' AND user_id='$user_id' and phone='$numb'");

if($amo>$total){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 900px'>
The amount entered is greater than Total Savings, the possible amount to withdraw
is $total
<a href='mom_withdraws.php' style='color:white; margin-left:100px;''>X</a></div>";
}
else if($date!=$curr_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>
Error! Enter the Current Date.
<a href='mom_withdraws.php' style='color:white; margin-left:100px;''>X</a></div>";
}
else if($check==0)
{
mysqli_query($conn,"INSERT INTO withdraws(with_id, withdraws, user_id, boss_id, with_date, amount, phone) 
VALUES (NULL, '$withdraw',  '$user_id', '$boss_id', '$date', '$amo', '$numb')");

//update total savings table

$balance=$total-$amo;

$query ="UPDATE total_mom set total='$balance' where phone='$numb' and user_id='$user_id' 
and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);

$names="MOM withdraws";
$transc_type="Cash_in";

mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id',  '$date', '$names',  '$transc_type', '$amo', '$reg_fee', '$mom')");


 //====enter data in mom Number table
mysqli_query($conn,"INSERT INTO mobile_numbers(mobile_id, usermo_id, bossmo_id, phone, mom_date, names, amount_mo, withdraw, balance) 
VALUES (NULL, '$user_id', '$boss_id', '$numb', '$date', '$names', '$amount', '$amo', '$balance')");

echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>Data Saved Successfully!
<a href='user_homepage.php' style='color:white; margin-left:100px;''>X</a></div>";
}

else{
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 1000px'> 
 
<table border='0' width='100%''>
<tr>
<td width='650px'><font size='4'>You have have already withdrawn on $numb today!&nbsp;&nbsp; 
Do want to withdraw More?</font></td>
<td> 
<form method='post' action='user_connector.php'>
<input type=hidden name='numb' value='$numb'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='with_date' value='$date'>
<input type=hidden name='amount' value='$amo'>
<button type='submit' name=mom_withdraws_more class='button is-default' 
style='padding-top:0px; border: 0px solid #006F37; border-radius:4px; height:15px; color:#006F37; background-color:red; color:white'>
&nbsp; Yes &nbsp;</button>
</form>
</td>
<td>
<a href='mom_withdraws.php?reload=1' style='color:white; margin-left:100px;''>No</a>
</td></tr></table>

</div>";
}
}
?>
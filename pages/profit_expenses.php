<?php
include('header_user.php');
?>
 <style>
 tr:nth-child(even) {
background-color: #D9FFD9;
}

th, td {
text-align: left;
padding-left: 10px;
 
}
</style>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> 
<?php include ('summary.php') ;
?>

<div id="main_container">
<div id="main_body">   
 
<br>
<?php
//get the expenses details
if(isset($_POST['expenses']))
{   
$exp_id = $_POST['exp_id'];
$month = $_POST['month'];
$year = $_POST['year'];

echo "<table width=80% border=1>
<thead>
<tr>
<th colspan=3>
<p align=left>Expenses Details</p>
</th>
 
<th>
<form method='post' action=profit_user.php>
<input type=hidden name='months' value='$month'>
<input type=hidden name='year' value='$year'>
 
<button type='submit' name='monthly_report' style='border: 1px solid green; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:160px'>
&nbsp;BACK &nbsp;</button></form>
</th> 

</tr>
</thead>
<tbody>
<tr>
<td width=16%>Date</td>
<td width=30%>Item</td>
<td width=10%>Cost</td>
<td width=30%>Naration</td>
</tr>";
$total_exp=0;
$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM expenses_list where exp_id='$exp_id' "));
$item=$results['item'];
 
$select = mysqli_query($conn, "SELECT * FROM expenses where 
MONTH(exp_date)='$month' and  userexp_id='$user_id' and bossexp_id='$boss_id' and item='$item' and YEAR(exp_date)='$year' order by exp_date");
while($selected= mysqli_fetch_array($select)){
$item= $selected["item"]; 
$exp_id= $selected["exp_id"];
$item= $selected["item"]; 
$exp_date= $selected["exp_date"]; 
$cost= $selected["cost"]; 
$naration= $selected["naration"]; 
$total_exp+=$cost;


echo "<tr>";

echo"<td>".date("d-m-Y", strtotime($exp_date))."</td>
<td> $item</td>
<td> $cost</td>
<td> $naration</td>
</tr>";
}
echo "<tr>
<td></td>
<td><b>Total</b></td>
<td><b>".number_format($total_exp)."</b></td>
<td>
<form method='post' action=profit_user.php>
<input type=hidden name='months' value='$month'>
<input type=hidden name='year' value='$year'>
 
<button type='submit' name='monthly_report' style='border: 1px solid green; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:160px'>
&nbsp;BACK &nbsp;</button></form>
</td>
</td></tr></table>";
}
//=============feess
if(isset($_POST['fee']))
{   
 
$month = $_POST['month'];
$year = $_POST['year'];
$total_fee=0;

echo "<table width=80% border=1>
<thead>
<tr>
<th colspan=3>
<p align=left>PROCESING FEE DETAILS</p>
</th>
 
<th>
<form method='post' action=profit_user.php>
<input type=hidden name='months' value='$month'>
<input type=hidden name='year' value='$year'>
 
<button type='submit' name='monthly_report' style='border: 1px solid green; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:160px'>
&nbsp;BACK &nbsp;</button></form>
</th> 

</tr>
</thead>
<tbody>
<tr>
<td width=16%>Date</td>
<td width=30%>Amount Given</td>
<td width=10%>Process. Fee</td>
<td width=10%></td>
</tr>";
 
$select = mysqli_query($conn, "SELECT * FROM loans where  MONTH(b_date)='$month' and YEAR(b_date)='$year' and userse_id='$user_id' and bossese_id='$boss_id' order by b_date");
while($selected= mysqli_fetch_array($select)){
 
$fee= $selected["reg_fee"]; 
$loan_date= $selected["b_date"]; 
$amount_given= $selected["amount_given"]; 
$total_fee+=$fee;
echo "<tr>";
echo"<td>".date("d-m-Y", strtotime($loan_date))."</td>
<td> ".number_format($amount_given)."</td>
<td> ".number_format($fee)."</td>
<td> </td>
</tr>";
}
echo "<tr>
<td></td>
<td><b>Total</b></td>
<td><b>".number_format($total_fee)."</b></td>
<td>
<form method='post' action=profit_user.php>
<input type=hidden name='months' value='$month'>
<input type=hidden name='year' value='$year'>
 
<button type='submit' name='monthly_report' style='border: 1px solid green; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:160px'>
&nbsp;BACK &nbsp;</button></form>
</td>
</td></tr></table>";
}

//==================INTEREST
if(isset($_POST['interest']))
{   
 
$month = $_POST['month'];
$year = $_POST['year'];
$interest=0;
$total_interest=0;
echo "<table width=60% border=1 align=center>
<thead>
<tr>
<th colspan=3>
<p align=left>INTEREST DETAILS</p>
</th>
 
<th>
<form method='post' action=profit_user.php>
<input type=hidden name='months' value='$month'>
<input type=hidden name='year' value='$year'>
 
<button type='submit' name='monthly_report' style='border: 1px solid green; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:160px'>
&nbsp;BACK &nbsp;</button></form>
</th> 

</tr>
</thead>
<tbody>
<tr>
<td width=16%>Date</td>
<td width=30%>Amount Paid</td>
<td width=10%>Interest</td>
<td width=10%></td>
</tr>";
 
$select = mysqli_query($conn, "SELECT * FROM loan_pay where  MONTH(p_date)='$month' and YEAR(p_date)='$year' and userse_id='$user_id' and bossese_id='$boss_id' order by p_date");
while($selected= mysqli_fetch_array($select)){
 
$loan_date= $selected["p_date"]; 
$amount_paid= $selected["amount_paid"]; 
$interest=$amount_paid*0.2;
$total_interest+=$interest;
  
echo "<tr>";
echo"<td>".date("d-m-Y", strtotime($loan_date))."</td>
<td> ".number_format($amount_paid)."</td>
<td> ".number_format($interest)."</td>
<td> </td>
</tr>";
}

echo "<tr>
<td></td>
<td><b>Total</b></td>
<td><b>".number_format($total_interest)."</b></td>
<td>
<form method='post' action=profit_user.php>
<input type=hidden name='months' value='$month'>
<input type=hidden name='year' value='$year'>
 
<button type='submit' name='monthly_report' style='border: 1px solid green; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:160px'>
&nbsp;BACK &nbsp;</button></form>
</td>
</td></tr></table>";
}

//==============Renewed Amount

if(isset($_POST['fine']))
{   
$month = $_POST['month'];
$year = $_POST['year'];
$fine=0;
$total_fine=0;
echo "<table width=80% border=1 align=center>
<thead>
<tr>
<th colspan=3>
<p align=left>INTEREST FROM RENEWED LOANS</p>
</th>
 
<th>
<form method='post' action=profit_user.php>
<input type=hidden name='months' value='$month'>
<input type=hidden name='year' value='$year'>
 
<button type='submit' name='monthly_report' style='border: 1px solid green; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:160px'>
&nbsp;BACK &nbsp;</button></form>
</th> 

</tr>
</thead>
<tbody>
<tr>
<td width=10%>Date</td>
<td width=30%>Client's Name</td>
<td width=20%>Amount Renewed</td>
 <td width=10%></td>
</tr>";
//Total fines in this month
$select = mysqli_query($conn, "SELECT * FROM loan_fines, clients where  MONTH(pay_date)='$month' and YEAR(pay_date)='$year' and user_id='$user_id' and boss_id='$boss_id' and client_id=clients_id order by pay_date");
while($selected= mysqli_fetch_array($select)){ 
$date= $selected["pay_date"];
$name=strtoupper($selected["firstname"]." ".$selected["lastname"]);
$fine= $selected["amount"];
$total_fine+=$fine;

  
echo "<tr>";
echo"<td>".date("d-m-Y", strtotime($date))."</td>";
echo "<td>$name</td>";
echo "<td> ".number_format($fine)."</td>
<td> </td>
</tr>";
}

echo "<tr>
<td></td>
<td><b>Total</b></td>
<td><b>".number_format($total_fine)."</b></td>
<td>
<form method='post' action=profit_user.php>
<input type=hidden name='months' value='$month'>
<input type=hidden name='year' value='$year'>
 
<button type='submit' name='monthly_report' style='border: 1px solid green; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:160px'>
&nbsp;BACK &nbsp;</button></form>
</td>
</td></tr></table>";
}
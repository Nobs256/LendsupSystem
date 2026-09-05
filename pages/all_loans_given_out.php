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
<?php include ('summary.php') ;?>

<div id="main_heading"> 
<table><tr><td><b>All Loans Given Out</b> </td><td><font color="#EAEAEA">----------------------------------</font></td>
<td>
<?php
echo "
<form method='post' action='all_loans_given_out.php'>
From:<input type=date name=date1 required>
To:<input type=date name=date2 required>
<button type='submit' name=all_loans style='padding-top:1px; border: 1px solid #006F37; border-radius:4px; color:#006F37'>
&nbsp;OK &nbsp;</button></form>";
?>   
</td>
<td>
<?php
echo"
<form method='post' action='print_all_loans.php' target='_blank'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
 
 
<button type='submit' name=all_loans style='border: 1px solid #7C7C7C; border-radius:3px; font-size:17px; 
color: white; background-color:green; height:30px; width:230px'>
&nbsp;Print All Loans Given &nbsp;</button></form> 	
 
";
?>
</td>
</tr></table>

</div>     
 
<div id="main_container">
<div id="main_body">   
    
<br>  
<?php
 $mon="";
if(isset($_POST['all_loans'])){
$date1 = $_POST['date1'];
$date2 = $_POST['date2'];

$total_amounts=0;
$total_amount=0;
//Total Loans in this month
$select = mysqli_query($conn,"SELECT * FROM loans where  b_date>='$date1' and b_date<='$date2' AND userse_id='$user_id' and bossese_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_given"]; 
$total_amount+=$amount;
$total_amounts=number_format($total_amount);
}
 
echo "<table width=100% border=0 style='font-size:14px' align=center><tr><td>
<p align=left><font size=4>LOANS GIVEN OUT BETWEEN <b>".date('d-m-Y', strtotime($date1))."</b>
 AND <b>".date('d-m-Y', strtotime($date2))."</b>
</font><br><br>
</td>
<td>
<form method='post' action='print_all_loans_given.php' target='_blank'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='date1' value='$date1'>
<input type=hidden name='date2' value='$date2'>
<button type='submit' name=daily_report style='border: 1px solid #7C7C7C; margin-left:200px; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:170px'>
&nbsp;Print Report&nbsp;</button></form>
</td></tr></table>";

$j=0;
$search_query= mysqli_query($conn,"SELECT client_id, ucase(firstname) as firstname, ucase(lastname) as lastname, phone, amount_given, pay_date, completed
FROM clients, completed_loan  where users_id='$user_id' and bosses_id='$boss_id' and client_id=clientcpid and  
pay_date>='$date1' and pay_date<='$date2' order by pay_date");
 
echo "<table width=80% border=1 align=center>
<thead>
<tr>
<th>No</th>
<th>Date Given Loan</th>
<th>Client's Name</th>
<th>Phone</th>
<th>Amount Given</th>
<th>Balance</th> 
<th>Status</th>
</tr>
</thead>
<tbody>";
$total_given=0;
$total_interest=0;
$total_paid=0;
while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;

$client_id=$returned_result["client_id"];
$firstname = $returned_result["firstname"]. " ". $returned_result["lastname"];
$amount  = $returned_result["amount_given"];
$completed  = $returned_result["completed"];
$date = $returned_result["pay_date"];
$d=date("d-m-Y", strtotime($date));
$phone=$returned_result["phone"];

$interest = 20/100*$amount;
$paid=$amount+$interest;
$total=number_format($amount+$interest);
$amount_given=number_format($amount);
$interests=number_format($interest);

$total_given+=$amount;
$total_interest+=$interest;
$total_paid+=$paid;

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from clients_with_loan where clientsid='$client_id'"));     
$debt= $result['debt'];


if($completed==1){
$status="Completed";
$debt=0;
}
else{
$status="Running";
}
 
echo "<tr style='font-size:13px'>
<td> $j  </td>
<td> $d  </td>
<td> $firstname  </td>
<td> $phone  </td>
<td> $amount_given</td>
<td> $debt</td>
<td> $status</td>";
}

echo "</tr>
 <tr><td></td><td></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td></td><td></td><td></td><td></td></tr>
 <tr><td></td><td></td><td><b>TOTAL</td><td><b>".number_format($total_given)."</b></td><td><b>".number_format($total_interest)."</b></td><td><b>".number_format($total_paid)."</td><td></td></tr>";
echo "</tbody></table>";
mysqli_close($conn);
 

}
else{
 
 echo"Select  Dates 
<hr></hr>
";
}
echo"
</div>
</div>
 "; 
include('footer.php');  
?>
</div>
</main>
</body> 
</html>
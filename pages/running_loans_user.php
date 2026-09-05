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
<table border="0"><tr><td><b>All Loans Running</b> </td><td>
<font color="#EAEAEA">-------------------------=-------------------------------
	------------------------------------------------------------</font></td>
<td> 
<?php
echo "
<form method='post' action='print_running_loans.php' target='_blank'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='branch' value='$branch'>
 
<button type='submit' name=running style='border: 1px solid #7C7C7C; border-radius:3px; font-size:17px; 
color: white; background-color:green; height:30px; width:100px'>
&nbsp;Print &nbsp;</button></form>";
?>

</td>
 
</tr></table>

</div>     
 
<div id="main_container">
<div id="main_body">   
    
<br>  
<?php
 $mon="";
 
 

$total_amounts=0;
$total_amount=0;
 
$j=0;
echo "<table width=80% border=1 align=center>
<thead>
<tr>
<th>No</th>
<th>Date</th>
<th>Client's Name</th>
<th>Amount Given</th>
<th>Interest</th>
<th>Total Amount</th> 
<th>Balance</th> 
</tr>
</thead>
<tbody>";
$total_given=0;
$total_interest=0;
$total_paid=0;
$total=0;
$balances=0;
$search_query= mysqli_query($conn,"SELECT  *
FROM clients, clients_with_loan where bosses_id='$boss_id' and users_id='$user_id' and client_id=clientsid order by pay_date Desc");  
while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;
$client_id=$returned_result["client_id"];
$name = strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);
$phone=$returned_result["phone"];
$user_id  = $returned_result["users_id"];
$loanNo = $returned_result["loan_no"];
$date_given= $returned_result["pay_date"];
$amount_given = $returned_result["amount_given"];
$daily_p = $returned_result["daily_p"];
$debt=$returned_result["debt"];
$date_given_loan =date("d-m-Y", strtotime($date_given));

$interest = 20/100*$amount_given;
$paid=$amount_given+$interest;
$total=$amount_given+$interest;
$amount_given=$amount_given;
$interests=$interest;

$total_given+=$amount_given;
$total_interest+=$interest;
$total_paid+=$paid;
$balances+=$debt;
  
echo "<tr style='font-size:13px'>
<td> $j  </td>
<td> $date_given </td>
<td> $name  </td>
<td> $amount_given</td>
<td> $interests</td>
<td> $total</td>
<td> $debt</td>";
}

echo "</tr>
 <tr><td></td><td></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td></td><td></td><td></td><td></td></tr>
 <tr><td></td><td></td><td><b>TOTAL</td><td><b>".number_format($total_given)."</b></td><td><b>".number_format($total_interest)."</b></td><td><b>".number_format($total_paid)."</td><td><b>".number_format($balances)."</td></tr>";
echo "</tbody></table>";
mysqli_close($conn);
 
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
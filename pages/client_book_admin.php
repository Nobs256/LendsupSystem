<?php
include('header.php');
?>
<style type="text/css">
 <style>
 tr:nth-child(even) {
background-color: #D9FFD9;
}


</style>

 
</style>
<main class="column main" style="background-color:#EAEAEA;"> 
<?php include ('summary_admin.php');
if(isset($_GET['client_id'])){
$client_id = $_GET['client_id'];


$result = mysqli_fetch_assoc(mysqli_query($conn,"select ucase(firstname) as firstname, ucase(lastname)as lastname, phone, users_id 
from clients where client_id='$client_id'"));     
$name= $result['firstname']." ".$result['lastname'];
$phone = $result['phone'];
$user_id = $result['users_id'];

echo"
<div id='main_heading'> 
<b>LOAN REPAYMENT STATEMENT (CLIENT'S BOOK) FOR:</b> $name
</div>
<br> 
<div id='main_container'> 
<div id='main_body'> 
  
<table border=1 width=100%>
<tr>
<th width=10%>Loan No</th>
<th width=40%><table width=100% border=0><tr><th>Date Given</th><th>Amount Given</th><th>Interest</th><th>Total Amount</th></tr></table></th>
<th>

<table border=0 width=80%>
<tr>
<th width=30%>Date</th>
<th width=30%>Amount Paid</th>
<th>Balance</th>
</tr></table>
</th>
</tr>";

$loan_no=0;
$interest=0;
$interests=0;
$total_amount=0;
$amount_given=0;
$amounts_given=0;
$no_loans = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM loans where cliente_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id'"));
 
for ($i=1; $i<=$no_loans; $i++) { 
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from completed_loan where clientcpid='$client_id' and userscpid='$user_id' and bosscpid='$boss_id' and loans_no='$i' "));     
$amount_given = $result['amount_given'];
$date= $result['pay_date'];
$interest=20/100*$amount_given;
$total_amount=number_format($amount_given+$interest);
$interests=number_format($interest);
$amounts_given=number_format($amount_given);
$date_given=date("d-m-Y", strtotime($date));

//starting date
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$i' order by p_date "));     
$date = $result['p_date'];
//No of days
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$i' order by p_date desc limit 1"));     
$last_date = $result['p_date'];

echo "<tr>
<td><font size=2>$i</font></td>
<td width=40%><table border=0 width=100%><tr><td width=20%>$date_given</td>
 <td>&nbsp;&nbsp;&nbsp;&nbsp;$amounts_given</td> 
 <td>&nbsp;&nbsp;&nbsp;&nbsp;$interests</td> 
 <td>$total_amount</td>
 </tr></table>
</td>
<td>
<table border=1 width=80%>";
$j=1;
while($j>0){ 
	
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$i' and p_date='$date'"));     

 
$d=date("d-m-Y", strtotime($date));
$amount = number_format($result["amount_paid"]);
$balance = number_format($result["balance"]);

echo "
<tr>
<td width=30%><font size=2> $d</font></td>             
<td width=30%><font size=2> $amount</font></td>
<td><font size=2> $balance</font></td>
"; 
echo "</tr>";
if($date==$last_date){
break;
}
$j++;
$date = date("Y-m-d", strtotime("$date +1 day"));
} 

//fine date



$fines ="SELECT * FROM fines where clientfine_id='$client_id'
and userfine_id='$user_id' and bossfine_id='$boss_id' and loan_no_fine='$i'";
$run = mysqli_query($conn, $fines) or die("Could DB");
$fines = mysqli_num_rows($run);
if ($fines==1){

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM fines where clientfine_id='$client_id'
and userfine_id='$user_id' and bossfine_id='$boss_id' and loan_no_fine='$i'"));     
$last_fine_date = $result['fine_date'];
$paid_amount_f = $result['paid_amount'];
//last amount
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM clients_with_loan where 
clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'"));     
$debt = $result['debt'];

$dd=date("d-m-Y", strtotime($last_fine_date));
$fine_and_balance=number_format($debt);
echo "<tr>
<td width=30%><font size=2><b> $dd</b></font></td>             
<td width=30%><font size=2><b> fined:$paid_amount_f </b></font></td>
<td><font size=2><b>Debt: $debt</b></font></td>"; 
echo "</tr>";
}

echo "</table>";
echo "</td></tr>";
 
}
echo "</tbody></table>";
}
?>  
 
</div>
</div>
 
<?php include('footer.php'); ?>
</div>
</main>
</script>
</body> 
</html>
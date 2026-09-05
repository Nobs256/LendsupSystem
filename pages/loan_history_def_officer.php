<?php
include('header_officer.php');
?>
<style type="text/css">
table {
border-collapse: collapse;
border-spacing: 0;
 
border: 0px solid black;
font-size: 15px;
 
}

th, td {
text-align: left;
padding: 2px;
padding-top: 2px;
}

 
</style>
<main class="column main" style="background-color:#EAEAEA;"> 
<?php include ('summary_officer.php');
if(isset($_GET['client_id'])){
$client_id = $_GET['client_id'];

$result = mysqli_fetch_assoc(mysqli_query($conn,"select ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
from clients_def where client_id='$client_id'"));     
$name= $result['firstname']." ".$result['lastname'];
$phone = $result['phone'];
echo"<br><br>
<div id='main_heading'> 
<table><tr><td><b>CLIENT'S BOOK FOR:</b> $name</td><td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Print Mini-Statement</td><td>  <p align=right>  
<form method='post' action='mini_statement.php' target='_blank'>
<input type=hidden name='client_id' value='$client_id'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>

<input type=date name=date1 required>
<input type=date name=date2 required>
<button type='submit' name=receipt style='padding-top:1px; border: 1px solid #006F37; border-radius:4px; color:#006F37'>
&nbsp;OK &nbsp;</button></form>
</p></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href=view_clients.php><font color=green><b>Search Another Client</b></font></a>
</td></tr></table>
</div>
<br> 
<div id='main_container' style='height:420px'> 
<div id='main_body' style='height:400px'> 
  
<table border=1 width=80%>
<tr>
<td width=20%><b>Loan No</b></td>
<td width=20%><b>Date Given</b></td>
<td width=20%><b>Amount Given</b></td>
<td>

<table border=1 width=100%>
<tr>
<td width=30%><b>Date</b></td>
<td width=40%><b>Amount Paid</b></td>
<td><b>Balance</b></td>
</tr></table>
</td>
</tr>";

$loan_no=0;
$paid_amount_f =0;
$no_loans = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM loans_def where cliente_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id'"));
 
 
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from clients_with_loan_def where clientsid='$client_id'"));     
$date_given=$result['pay_date'];
$amount_given=$result['amount_given'];
$i=$result['loan_no'];

$date_given =date("d-m-Y", strtotime($result['pay_date']));
 
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
<td><font size=2>$date_given</font></td>
<td><font size=2>$amount_given</font></td>
<td>
<table border=1 width=100%>";
$j=1;
while($j>0){ 
//with cash
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$i' and p_date='$date' and mom=0")); 
$amount_cash = $result["amount_paid"];
$balance1 =$result["balance"];
 
//with mom
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$i' and p_date='$date' and mom=1")); 
$amount_mom =  $result["amount_paid"];
$balance2 = $result["balance"];
 
//balance
if($balance2<$balance1 and $balance2>0){
$balance=$balance2;
}
else if($balance1<$balance2 and $balance1>0){
$balance=$balance1;
}
else if($balance1==0){
$balance=$balance2;
}
else{
$balance=$balance1;
}

$d=date("d-m-Y", strtotime($date));
$amount=$amount_mom+$amount_cash;

echo "
<tr>
<td width=30%><font size=2> $d</font></td>             
<td width=40%><font size=2>".number_format($amount)."</font></td>
<td><font size=2>".number_format($balance)."</font></td>
"; 
echo "</tr>";
if($date==$last_date){
break;
}
$j++;
$date = date("Y-m-d", strtotime("$date +1 day"));
} 
//fine date

$fines ="SELECT * FROM loan_fines where clients_id='$client_id'
and user_id='$user_id' and boss_id='$boss_id' and loan_id='$i'";
$run = mysqli_query($conn, $fines) or die("Could DB");
$fines = mysqli_num_rows($run);
if ($fines>0){

$renewed_clients = mysqli_query($conn,"SELECT * from loan_fines where clients_id='$client_id'
and user_id='$user_id' and boss_id='$boss_id' and loan_id='$i'");
while($result= mysqli_fetch_array($renewed_clients)){
$last_fine_date = $result['pay_date'];
$paid_amount_f = $result['amount'];
$fine_type = $result['fine_type'];
$dd=date("d-m-Y", strtotime($last_fine_date));


echo "<tr>
<td width=30%><font size=2><b> $dd</b></font></td>             
<td width=30%><font size=2><b> $fine_type :".number_format($paid_amount_f)." </b></font></td>
<td><font size=2><b></b></font></td>"; 
echo "</tr>";
 }
 if ($fines>0){
 $result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM clients_with_loan where 
clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'"));     
$debt = $result['debt'];
 echo "<tr>
<td width=30%><font size=2><b> </b></font></td>             
<td width=30%><font size=2><b> Current Debt:</b></font></td>
<td><font size=2><b>".number_format($debt)."</b></font></td>"; 
echo "</tr>";
}
}

echo "</table>";
echo "</td></tr>";
 
}
echo "</tbody></table>";
 
?>  
 
</div>
</div>
 
<?php include('footer.php'); ?>
</div>
</main>
</script>
</body> 
</html>
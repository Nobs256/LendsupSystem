<?php
include('header_user.php');
$heading="LOAN PAYMENT <br>Search a Client to Pay Loan</b>";
 ?>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> <?php include ('summary.php') ?>
<div id="main_heading"> <b>
<?php echo $heading; ?>
</div>

<div id="main_container" style="height:420px">
<div id="main_body" style="height:400px">   
<br>
<br>                               
<table border="0" width="70%">
<form  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
 <table style="width:730px;" border="0">
<tr><td width="150px">
Enter First Name: 
</td><td width="400px">
<input type="text" name="firstname" class="input is-success" style="width:320px; border: 1px solid #006F37" required >
<button type="submit" name="submit" class="button is-primary" style="background-color:#006F37">
&nbsp;&nbsp;&nbsp;Search&nbsp;&nbsp;&nbsp;</button><div>
</form></td> </tr></table>    
<br>  
<?php
if(isset($_POST['firstname'])){
$q = $_POST['firstname'];
$j=0;
$search_query= mysqli_query($conn,"SELECT clients.client_id, clients.firstname, clients.lastname,
 clients.phone, clients.b_location, clients_with_loan.pay_date, clients_with_loan.amount_given,
 clients_with_loan.daily_p, clients_with_loan.debt
 FROM clients
 INNER JOIN clients_with_loan ON clients.client_id=clients_with_loan.clientsid
 WHERE clients.firstname LIKE '%$q%' AND clients.users_id='$user_id'
 AND clients.bosses_id='$boss_id' AND clients_with_loan.userseid='$user_id'
 AND clients_with_loan.bosseseid='$boss_id' AND clients_with_loan.debt>0");
  
echo "<table  class='table table-responsive table-hover' width='100%'>
<thead>
<tr>
<th width=20%>Names</th>
<th>Phone</th>
<th>Location</th>
<th>Amount Given</th>
<th>Total Amount</th>
<th>Daily Payment</th>
<th>Balance</th>
<th>Action</th>
</tr>
</thead>
<tbody>";

while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;
$client_id=$returned_result["client_id"];
$firstname = strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);
$phone  = $returned_result["phone"];
$date  = $returned_result["pay_date"];
$pay_date=date("d-m-Y", strtotime($date));
$b_location = strtoupper($returned_result["b_location"]);
$debt =number_format($returned_result["debt"]);
$amount_given  = $returned_result["amount_given"];
$daily_p  = number_format($returned_result["daily_p"]);
$interest=20/100*$amount_given;
$total=number_format($amount_given+$interest);
$amount=number_format($amount_given);

  
echo "<tr>
<td><font size=2> $firstname  </font></td>
<td><font size=2> $phone</font></td>
<td><font size=2> $b_location</font></td>
<td><font size=2> $amount</font></td>
<td><font size=2> $total</font></td>
<td><font size=2> $daily_p</font></td>
<td><font size=2> $debt</font></td>
<td><a href='pay_loan.php?client_id=$client_id'>Pay Loan</a>
</td>";
}

echo "</tr>";
}
echo "</tbody></table>";
mysqli_close($conn);
 
?>

</div>
</div>
 
  
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>
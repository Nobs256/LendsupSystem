<?php
include('header_user.php');
$heading="ENTER BALANCE <br>Search a Client to Enter Balance</b>";
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
$select_client = mysqli_query($conn,"SELECT * FROM clients where firstname='$q'");
$search_query= mysqli_query($conn,"SELECT * FROM clients, clients_with_loan where firstname Like '%$q%' and users_id='$user_id'
 and bosses_id='$boss_id' and debt>0 and client_id=clientsid");
  
echo "<table  class='table table-responsive table-hover' width='100%'>
<thead>
<tr>
<th width=20%>Names</th>
<th>Phone</th>
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
<td><font size=2> $amount</font></td>
<td><font size=2> $total</font></td>
<td><font size=2> $daily_p</font></td>
<td><font size=2> $debt</font></td>
<td><a href='pay_loan_entry2.php?client_id=$client_id'>Pay Loan</a>
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
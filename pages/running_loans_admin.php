<?php
include('header.php');
?>
<style type="text/css">
 

th, td {
text-align: left;
padding: 4px;
padding-top: 4px;
}

tr:nth-child(even) {
background-color: #D9FFD9;
}
 
</style>
<main class="column main" style="background-color:#EAEAEA;"> 
<?php include ('summary_admin.php') ?>
 
<div id="main_heading"> <b>Clients with Loans</b>
 
</div>     

<br> 
<div id="main_container"> 
<div id="main_body"> 
<?php

 $curr_date=date("Y-m-d");

$search_query= mysqli_query($conn,"SELECT client_id, users_id, bosses_id, 
ucase(firstname) as firstname,ucase(lastname) as lastname,  phone, business, b_location,
amount_given, daily_p, debt, pay_date
FROM clients, clients_with_loan  where bosses_id='$boss_id' and debt>0 
and client_id=clientsid order by firstname");
?>  
<div class="table-responsive">
<table id="example" class="table table-striped table-bordered second" border="1" style="width:100%">            
 
<thead>
<tr><th>No</th>
<th>Date</th>
<th>Name</th>
<th>Phone</th>
<th>Amount Given</th>
<th>Total Amount</th>
<th>Daily Payment</th>
<th>Balance</th>
<th>Days Spent</th>
</tr></thead><tbody>

<?php
$j=0;
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id=$returned_result["client_id"];
$name = $returned_result["firstname"]." ".$returned_result["lastname"]; 
$phone = $returned_result["phone"];                
$business = $returned_result["business"];
$pay_date = $returned_result["pay_date"];
$b_location = $returned_result["b_location"];
$amount = $returned_result["amount_given"];
$interest = 20/100*$amount;
$total=number_format($amount+$interest);
$daily_p = number_format($returned_result["daily_p"]);
$debt =number_format($returned_result["debt"]);
$amount_given=number_format($amount);

$d=date("d-m-Y", strtotime($pay_date)); 
 
$j++;

echo "<tr>
<td><font size=2> $j </font></td>
<td><font size=2> $d </font></td>
<td><font size=2> $name </font></td>
<td><font size=2> $phone </font></td>                  
<td><font size=2> $amount_given</font></td>
<td><font size=2> $total</font></td>
<td><font size=2> $daily_p</font></td>
<td><font size=2> $debt</font></td>
<td><font size=2>";
$x=0;
while($x>=0){

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay where clients_id='$client_id'
and bossese_id='$boss_id' and p_date='$pay_date' order by p_date "));     
$amount_paid=$result['amount_paid'];

if($amount_paid!=0){
$x++;
}
if($pay_date==$curr_date){
break;
}
$pay_date = date("Y-m-d", strtotime("$pay_date +1 day"));
} 
echo "$x </font></td>"; 
echo "</tr>";
}
echo "</tbody></table>";
?>  
</div>
</div>
</div>
<div> 
<?php include('footer.php'); ?>
</div>
</main>
<script type="text/javascript">
$(document).ready(function()
{
$("#client_info").modal("show");
$("#client_update").modal("show");
$("#tr_sms").modal("show");
 
});


</script>
</body> 
</html>
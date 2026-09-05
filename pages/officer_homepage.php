<?php
$s="";
include('header_officer.php');
$curr_date=date('Y-m-d'); 
?>
<style type="text/css">
table {
border-collapse: collapse;
border-spacing: 0;
width: 100%;
border: 1px solid #D9FFD9 ;
}

th, td {
text-align: left;
padding: 4px;
padding-top: 4px;
}

tr:nth-child(even) {
background-color: #D9FFD9;
}
form {
border-collapse: collapse;
}

input, select {
width:340px; height:33px; font-size:12px; border: 2px solid green; border-radius: 4px;
}
</style>
<main class="column main" style="background-color:#EAEAEA;"> 
<?php include ('summary_officer.php') ?> 
 
 
<div id="main_heading"> <b>Clients with Loans (<?php echo $loan_clients; ?>)</b> 
<a style="border: 1px solid #006F37; border-radius:4px; font-size:14px; color:#006F37; height:30px; margin-left:700px; background-color:#006F37; color:white"
 class="button is-default" href="user_homepage_def_off.php">
&nbsp;Defaulters&nbsp;</a>
   
	<?php echo $s; ?> 
	 <?php echo $not_loans; ?> 
	 <?php echo $in_with_loans; ?> 
	<?php echo $not_with_loans; ?>
	<?php echo $warning; ?> 
 
</div>     
 
<div id="main_container"> 
<div id="main_body"> 
<?php
 
$search_query= mysqli_query($conn,"SELECT client_id, users_id, bosses_id, 
ucase(firstname) as firstname,ucase(lastname) as lastname,  phone, business, b_location,
amount_given, daily_p, debt, interest, days
FROM clients, clients_with_loan  where bosses_id='$boss_id' and debt>0 
and client_id=clientsid order by firstname");
?>  
<div class="table-responsive">
<table id="example" class="table table-striped table-bordered second" style="width:100%">            
 
<thead>
<tr>
<th width="4%">No</th>
<th width="18%">Name</th>
<th width="7%">Phone</th>
<th width="14%">Amount Given</th>
<th width="14%">Total Amount</th>
<th width="15%">Daily Payment</th>
<th width="8%">Balance</th>
 
<th width="7%">Client Book</th>
</tr></thead><tbody>

<?php
$j=0;
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id=$returned_result["client_id"];
$name = $returned_result["firstname"]." ".$returned_result["lastname"]; 
$phone = $returned_result["phone"];                
$business = $returned_result["business"];
$b_location = $returned_result["b_location"];
$amount = $returned_result["amount_given"];
$days= $returned_result["days"];
$int=$returned_result["interest"];
$interest = $int/100*$amount;
$total=number_format($amount+$interest);
$daily_p = number_format($returned_result["daily_p"]);
$debt =number_format($returned_result["debt"]);
$amount_given=number_format($amount);

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from new_users where username='$user_email'"));
$user_id = $results["user_id"];
$boss_id = $results["boss_id"];

$j++;

echo "<tr>
<td><font size=2> $client_id </font></td>
<td><font size=2> $name </font></td>
<td><font size=2> $phone </font></td>                  
<td><font size=2> $amount_given</font></td>
<td><font size=2> $total</font></td>
<td><font size=2> $daily_p</font></td>
<td><font size=2> $debt</font></td>
";
?>
 
 
<td>
<a style="border: 1px solid #006F37; border-radius:1px; font-size:10px; color:#006F37; height:20px" 
class="button is-default" href="loan_history_officer.php?client_id=<?php echo $client_id;?>">&nbsp;
Client Book&nbsp;</a>

</td>
<?php
echo "</tr>";

}
echo "</tbody></table>";
?>  

</div>
</div>
</div>
</div> 
<?php include('footer.php');  
?>
</div>
</main>
</body> 
</html>
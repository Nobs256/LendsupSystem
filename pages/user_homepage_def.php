<?php
$s="";
include('header_user.php');
if(isset($_GET['message'])){
$s = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:0px; padding:7px; font-weight:normal; width: 460px'>
<font color=white>A Report is successfully Sent !!</font>
<a href='user_homepage.php?reload=1' style='color:white; margin-left:120px;''>X</a>
</div>";
}


//============NOTIFICATIONS OF LOAN ENTERING
$curr_date=date('Y-m-d'); 
$in_loans="";
$not_loans="";

$search_query= mysqli_query($conn,"SELECT  * FROM clients_with_loan
where bosseseid='$boss_id' and userseid='$user_id' and pay_date='$curr_date'");  
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id=$returned_result["clientsid"];
$amount_given = $returned_result["amount_given"];
 
$given_clients=mysqli_query($conn,"SELECT * FROM loans WHERE b_date='$curr_date' AND
 cliente_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id'");
$now=mysqli_fetch_array($given_clients);
$given_loan=$now["amount_given"];
$loan_id=$now["loan_id"];
 
$given_clients=mysqli_query($conn,"SELECT * FROM transcations WHERE transc_date='$curr_date' AND
 clientr_id='$client_id' and user_id='$user_id' and boss_id='$boss_id' and transc_type='Cash_out'");
$now=mysqli_fetch_array($given_clients);
$given_loan_trans=$now["transc_amount"];

if($given_loan>0 && $amount_given>0 && $given_loan_trans>0){
$in_loans='';
}
else{
$clientsname=mysqli_query($conn,"SELECT * FROM clients WHERE client_id='$client_id' 
and users_id='$user_id' and bosses_id='$boss_id'");
$now2=mysqli_fetch_array($clientsname);
$names=strtoupper($now2["firstname"]." ".$now2["lastname"]);

$not_loans="<font size=3 color=red> A Loan Given to $names is not Correctly entered,</font>
 <a href='delete_wrong_loan.php?delete=$client_id'><font size=3 color=red>
 <u>Delete and Enter Again</u></font></a>";
}
}

$curr_date=date('Y-m-d'); 
$in_with_loans="";
$not_with_loans="";

$search_query= mysqli_query($conn,"SELECT  * FROM loans WHERE b_date='$curr_date' AND
userse_id='$user_id' and bossese_id='$boss_id'");  
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id=$returned_result["cliente_id"];
$amount_given = $returned_result["amount_given"];
 
$given_clients=mysqli_query($conn,"SELECT * FROM clients_with_loan
where bosseseid='$boss_id' and userseid='$user_id' and pay_date='$curr_date'");
$now=mysqli_fetch_array($given_clients);
$given_loan=$now["amount_given"];
 
 
$given_clients=mysqli_query($conn,"SELECT * FROM transcations WHERE transc_date='$curr_date' AND
 clientr_id='$client_id' and user_id='$user_id' and boss_id='$boss_id' and transc_type='Cash_out'");
$now=mysqli_fetch_array($given_clients);
$given_loan_trans=$now["transc_amount"];

if($given_loan>0 && $amount_given>0 && $given_loan_trans>0){
$in_with_loans='';
}
else{
$clientsname=mysqli_query($conn,"SELECT * FROM clients WHERE client_id='$client_id' 
and users_id='$user_id' and bosses_id='$boss_id'");
$now2=mysqli_fetch_array($clientsname);
$names=strtoupper($now2["firstname"]." ".$now2["lastname"]);

$not_with_loans="<font size=3 color=red> A Loan Given to $names is not Correctly entered,</font>
 <a href='delete_wrong_loan.php?delete=$client_id'><font size=3 color=red>
 <u>Delete and Enter Again</u></font></a>";
}
}



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
<?php include ('summary.php') ?> 
 
 
<div id="main_heading"> <b>DEFAULTERS </b> 
<a style="border: 1px solid #006F37; border-radius:4px; font-size:14px; color:#006F37; height:30px; margin-left:700px; background-color:#006F37; color:white"
 class="button is-default" href="user_homepage.php">
&nbsp;Back to Active Clients &nbsp;</a>
   
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
FROM clients_def, clients_with_loan_def  where users_id='$user_id' and bosses_id='$boss_id' and debt>0 
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
<th width="7%">Pay Loan</th>
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
<a style="border: 1px solid #006F37; border-radius:1px; font-size:10px; color:#006F37; height:25px" 
class="button is-default" href="pay_loan_def.php?client_id=<?php echo $client_id;?>">&nbsp;
Pay Loan&nbsp;</a>
</td>
<td>
<a style="border: 1px solid #006F37; border-radius:1px; font-size:10px; color:#006F37; height:20px" 
class="button is-default" href="loan_history_def.php?client_id=<?php echo $client_id;?>">&nbsp;
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
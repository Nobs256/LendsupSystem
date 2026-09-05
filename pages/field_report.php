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
<p align="center"> <?php include ('summary.php') ?>
 
<div id="main_heading"> 
	<br><br>
<table border="0" width="100%">
<tr><td>
<b>Field Officer Payment Report</b>
</td>
<td>
<font color="#E4E4E4">---------- ---</font>
</td>
<td widith="40%">
<form  method="post"> 
Select Date: 
<input type="date" name="p_date" style="width:190px; height:35px; border: 1px solid #006F37" required> 
</td><td>
<div class="select is-success">  
<select  name="officer_id"  style="width:180px; border: 1px solid #006F37; height:35px" required> 
<option>Field Officer:</option>
<?php
$comb = mysqli_query($conn,"SELECT * from officers where boss_id='$boss_id' and active=1 and branch='$bra'");

while($select_comb = mysqli_fetch_array($comb)){
$officer_id=$select_comb['officer_id'];
$officers_name=$select_comb['firstname']." ".$select_comb['lastname'];
 
echo "<option value= $officer_id>".strtoupper($officers_name)." </option>";
 
}
echo" 
</select></div>";
?>
<button type="submit" name="not_paid" class="button is-primary" 
style="background-color:#006F37; color:white; font-size:12px; border:1px solid #006F37; height:35px; margin-left:4px ">
&nbsp;OK&nbsp;</button></form> 
 
</td>
<td>
<?php
echo"
<form method='post' action='clients_not_paid_all.php'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='branch' value='$bra'>
<button type='submit' name=notpaid style='border: 1px solid #7C7C7C; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:180px; margin-left:2px'>
&nbsp;View All Clients &nbsp;</button></form>";?>
</td>
</tr></table>
</div>
<br>  
</div>     
 
<div id="main_container">
<div id="main_body">   
    
<br>  
<?php
if(isset($_POST['not_paid'])){       
$date = $_POST['p_date'];  
$officer_id = $_POST['officer_id'];
$payed_date=date("Y-m-d", strtotime($date));
$d=date("d-m-Y", strtotime($date));
$mom=0;

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from new_users where boss_id='$boss_id' and active=1 and category='User' and user_id='$user_id'"));
$branch=strtoupper($results["branch"]);

$returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and officer_id='$officer_id' and branch='$branch'"));     
$name = strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);
$location  = $returned_result["location"];

$total_clients = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients, clients_with_loan where 
users_id='$user_id' and bosses_id='$boss_id' and b_location='$location' and clientsid=client_id")); 


$j=0;
$no=0;
$total_amount_paid=0;
$search_query= mysqli_query($conn,"SELECT * FROM clients, field_payment where user_id='$user_id' and boss_id='$boss_id' and client_id=clientf_id and officerid ='$officer_id' and pay_date='$payed_date' order by firstname");  
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id=$returned_result["clientf_id"];
$firstname = strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);
$amount_paid = $returned_result["amount"];
$total_amount_paid+=$amount_paid;
$no++;
}

$unpaid=$total_clients-$no;

//total unknown Cash
$total_unknown_cash=0;
$select = mysqli_query($conn,"SELECT * FROM unknown_cash where unknown_date='$payed_date' and user_id='$user_id' and boss_id='$boss_id' and officer='$officer_id'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$total_unknown_cash+=$amount;
}

echo"
<table border=0 width=100%>
<tr><td><p align=center><b> $name</b> Date: <b> $d </b></td>";
echo"<td> Total Clients:  <b>".$total_clients."</b>   Paid: <b>".$no."</b>Unpaid:<b>".$unpaid."</b> </td>
<td>Unknown: <b>".number_format($total_unknown_cash)."</b> </td>
<td>Total Amount:<b> ".number_format($total_amount_paid+$total_unknown_cash)."
</td>
 </tr>
 </table>
<br>";
echo "<table width=50% border=1 style='font-size:14px' align='center'>
<thead>
<tr>
<th width=6%>No</th>
<th width=18%>Names</th>
<th width=10%>Amount Paid</th>
</tr>
</thead>
<tbody>";
$j=0;
$total_amount_paid=0;
$search_query= mysqli_query($conn,"SELECT * 
FROM clients, field_payment where user_id='$user_id' and boss_id='$boss_id' and client_id=clientf_id and officerid ='$officer_id' and pay_date='$payed_date' order by firstname");
  
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id=$returned_result["clientf_id"];
$firstname = strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);
$amount_paid = $returned_result["amount"];

$total_amount_paid+=$amount_paid;
$j++;
echo "<tr style='font-size:12px'>
<td> $j </td>
<td> $firstname  </td>
<td>".number_format($amount_paid)."</td>";
}

echo "</tr>
<td></td><td>No of Clients:<b>".$j."</b></td><td>Total Paid: <b>".number_format($total_amount_paid)."</b></td>";
echo "</tr></tbody></table>";
mysqli_close($conn);
 
 }
 else{
 echo" <font size=5>Select   Field Officer and Date</font>
<hr></hr>
";
}
echo"
</div>
</div>
</div>
</div>
<div>"; 
  
?>
</div>
</main>
</body> 
</html>
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
<table border="0">
<tr><td>
<b>Clients Paid using MOM</b>
</td>
<td>
<font color="#E4E4E4">-------------------------------------------------------</font>
</td><td>
<form  method="post"> 
Select Date: 
<input type="date" name="p_date" style="width:190px; height:35px; border: 1px solid #006F37" required> 
<button type="submit" name="paid" class="button is-primary" 
style="background-color:#006F37; color:white; font-size:12px; 
border:1px solid #006F37; height:35px; margin-left:4px ">
&nbsp;OK&nbsp;</button></form> 
 
</td></tr></table></div>
     
 
<div id="main_container" style="height:420px">
<div id="main_body" style="height:390px">   
    
<br>  
<?php
$j=0;
$total_amount_paid=0;
$total_amount=0;
if(isset($_POST['paid'])){       
$date = $_POST['p_date']; 
}
else{
$date=date('Y-m-d');
}

$payed_date=date("Y-m-d", strtotime($date));
$d=date("d-m-Y", strtotime($date));

$select = mysqli_query($conn,"SELECT * FROM loan_pay where  p_date='$payed_date'
and userse_id='$user_id' and bossese_id='$boss_id' and mom=1");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_paid"]; 
$total_amount+=$amount;
}


 
echo "<table width=80% border=0 style='font-size:14px' align=center><tr><td>
<p align=left><font size=4>CLIENTS PAID USING MOM <b> $d</font><br><br>
</td>
<td>
<form method='post' action='print_clients_paid_with_mom.php' target='_blank'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='date' value='$date'>
<button type='submit' name=daily_report style='border: 1px solid #7C7C7C; margin-left:200px; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:200px'>
&nbsp;Print Report&nbsp;</button></form>
</td></tr></table>
";
 
echo "<table width=80% border=1 style='font-size:14px' align=center>
<thead>
<tr>
<th>No</th>
<th>Name</th>
<th>Phone</th>
<th>Amount Sent</th>
<th>Amout Paid</th>
<th>Sent On</th> 
 
</tr>
</thead>
<tbody>";


$j=0;
$closing=0;
$closing=$total_op;
$search_query= mysqli_query($conn,"SELECT * FROM mobile, clients where users_id='$user_id' and bosses_id='$boss_id' and 
mom_date='$payed_date' and client_id=clientmo_id order by phone_sent");
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id = $returned_result["client_id"]; 
$names = $returned_result["firstname"]." ".$returned_result["lastname"];
$phone = $returned_result["phone"];
$amount  = $returned_result["amount_mo"];

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from loan_pay where clients_id='$client_id' 
and p_date='$payed_date' and mom=1"));
$amount_paid = $results["amount_paid"]; 

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from mobile where clientmo_id='$client_id' and  usermo_id='$user_id'
 and bossmo_id='$boss_id' and mom_date='$payed_date' "));
$sent_on = $results["phone_sent"]; 
 
$j++;
echo "<tr style='font-size:13px'>";
 
echo"
<td> $j </font></td>
<td>".strtoupper($names)."</td>
<td>$phone</td>
<td>".number_format($amount)."</td>
<td>".number_format($amount_paid)."</td>
<td>$sent_on</td>";
echo "</tr>";

 
}

echo "<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>";

echo "<tr><td></td><td></td><td><b></td><td><b>TOTAL MOM:</td><td><b>".number_format($total_amount)."</b></td><td></td></tr>";
echo "</tbody></table><br> ";
mysqli_close($conn);
 
 
echo"
</div>

 
</div>";
include('footer.php');
?>
 </div>
</main>
</body> 
</html>
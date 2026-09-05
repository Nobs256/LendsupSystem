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
<b>Defaulters who Have Not Paid</b>
</td>
<td>
<font color="#E4E4E4">---------- ---</font>
</td>
<td widith="40%">
<form  method="post"> 
Select Date: 
<input type="date" name="p_date" style="width:190px; height:35px; border: 1px solid #006F37" required> 

<button type="submit" name="not_paid" class="button is-primary" 
style="background-color:#006F37; color:white; font-size:12px; border:1px solid #006F37; height:35px; margin-left:4px ">
&nbsp;OK&nbsp;</button></form> 
 
</td>
<td>
<?php
echo"
<form method='post' action='clients_not_paid.php'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='branch' value='$bra'>
<button type='submit' name=notpaid style='border: 1px solid #7C7C7C; border-radius:3px; font-size:17px; 
color: white; background-color:#7C7C7C; height:40px; width:150px; margin-left:2px'>
&nbsp;Active Clients&nbsp;</button></form>";?>
</td>
</tr></table>
</div>
<br>  
</div>     
 
<div id="main_container">
<div id="main_body" style="width: 1140px; ">   
    
<br>  
<?php
if(isset($_POST['not_paid'])){       
$date = $_POST['p_date'];  

$payed_date=date("Y-m-d", strtotime($date));
$d=date("d-m-Y", strtotime($date));

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from new_users where boss_id='$boss_id' and active=1 and category='User' and user_id='$user_id'"));
$branch=strtoupper($results["branch"]);
 
$j=0;
 
echo "<table><tr><td><p align=center><font size=4><b>$branch BRANCH </b><br>
   Clients not Paid on <b> $d </b> </font><br><br></td>";
echo"<td>
<form method='post' action='print_not_paid.php' target='_blank'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='branch' value='$branch'>
<input type=hidden name='payed_date' value='$date'>

<button type='submit' name=notpaid style='border: 1px solid #7C7C7C; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:150px; margin-left:300px'>
&nbsp;Print Report &nbsp;</button></form></td></tr></table>";

$query ="DELETE from clients_not_paid where user_id='$user_id' and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);

$j=0;
$search_query= mysqli_query($conn,"SELECT * FROM clients_def, clients_with_loan_def where users_id='$user_id' and bosses_id='$boss_id' 
and client_id=clientsid  order by pay_date");
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id=$returned_result["client_id"];
$name = strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);
$phone  = $returned_result["phone"];
$loc  = $returned_result["b_location"];
$date_given= $returned_result["pay_date"];
$amount_given = $returned_result["amount_given"];
$debt  = $returned_result["debt"];
$loanNo = $returned_result["loan_no"];
$daily_p = $returned_result["daily_p"];
$nid = $returned_result["nid"];
 
$missed_balance=0;
$x=100;
$nid=0;

$curr_date=date('Y-m-d');
$prev_date = date("Y-m-d", strtotime("$curr_date -1 day")); 
//starting date                                              
$first_date = date("Y-m-d", strtotime("$date_given +1 day"));
$end_date = date("Y-m-d", strtotime("$date_given +30 day"));

$pre_yr=date("Y",strtotime($date_given));
$curr_yr=date("Y",strtotime($curr_date));
//First date
$x=0;
$not_paid_at_all=0;
 
//No of days
$risk_date=date("Y-m-d", strtotime("$end_date +1 day"));
$defaulter_date=date("Y-m-d", strtotime("$end_date +60 day"));

//last date of payment
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loanNo' order by p_date desc limit 1"));     
$amount_paid2=$result['amount_paid'];
$last_date=$result['p_date'];

$date4=date_create("$last_date");
$date5=date_create("$curr_date");
$diff2=date_diff($date4,$date5);
$days=$diff2->format("%a");
 
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loanNo' and p_date='$payed_date'")); 
$amount_paid3=$result['amount_paid'];

if($amount_paid3==0){

mysqli_query($conn,"INSERT INTO clients_not_paid(ts_id, client_id, user_id, boss_id, names, phone, amount_given, 
date_given, last_date_paid, last_amount, balance, days_missed, arears, total_days, ids)
VALUES (NULL, '$client_id', '$user_id', '$boss_id', '$name',  '$phone', '$amount_given', '$date_given', 
'$last_date', '$amount_paid2', '$debt', '$days', '$missed_balance', '$x', '$nid')");

$amount_paid2=0;

}
}
echo "<table width=100% border=1 style='font-size:14px'>
<thead>
<tr>
<th width=3%>No</th>
<th width=15%>Names</th>
<th width=7%>Phone</th>
<th width=6%>Loan Given</th>
<th width=8%>Date Given</th>
<th width=8%>Last Date Paid</th>
<th width=6%>Last Amt Paid</th>
<th width=6%>Balance</th>
<th width=5%>Days Missed</th>

</tr>
</thead>
<tbody>";

$search_query= mysqli_query($conn,"SELECT * FROM clients_not_paid 
where user_id='$user_id' and boss_id='$boss_id' order by days_missed desc");
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id=$returned_result["client_id"];
$names=$returned_result["names"];
$phone = $returned_result["phone"];
$date_given  = $returned_result["date_given"];
$amount_given  = $returned_result["amount_given"];
$last_date_paid = $returned_result["last_date_paid"];
$balance = $returned_result["balance"];
$arears = $returned_result["arears"];
$total_days = $returned_result["total_days"];
$last_amount = $returned_result["last_amount"];
$days_missed = $returned_result["days_missed"]; 
$nid = $returned_result["ids"]; 
$daily_pay=(($amount_given*0.2)+$amount_given)/30;
$missed_amount=$days_missed*$daily_pay*0.2;
$missed_arears=$daily_pay*0.1*$total_days;
$j++;

 
echo "<tr style='font-size:12px'>
<td> $j </td>
<td> $names  </td>
<td> $phone</td>
<td> $amount_given</td>
<td> $date_given</td>
<td> $last_date_paid</td>
<td> $last_amount</td>
<td> $balance</td>
<td> $days_missed</td>";
echo "</tr>";
}

echo "</tbody></table>";
echo "<br><font size=4><b>Total Number of Clients Not Paid is $j<br>";

}
else{
echo" <font size=5>Select Field Officer and Date</font>
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
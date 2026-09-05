<?php
include('header_officer.php');
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
<p align="center"> <?php include ('summary_officer.php') ?>
 
<div id="main_heading"> 
<table border="0" width="100%">
<tr><td>
<b>Clients who Have Not Paid</b>
</td>
<td>
<font color="#E4E4E4">----- ---</font>
</td>
<td widith="40%">
<form  method="post"> 
Select Date: 
<input type="date" name="p_date" style="width:180px; height:35px; border: 1px solid #006F37" required> 
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
 
</tr></table>
</div>
<br>  
</div>     
 
<div id="main_container">
<div id="main_body" style="width: 1100px; ">   
    
<br>  
<?php
if(isset($_POST['not_paid'])){       
$date = $_POST['p_date'];  
$officer_id = $_POST['officer_id'];
$payed_date=date("Y-m-d", strtotime($date));
$d=date("d-m-Y", strtotime($date));

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from new_users where boss_id='$boss_id' and active=1 and category='User' and user_id='$user_id'"));
$branch=strtoupper($results["branch"]);

$returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and officer_id='$officer_id' and branch='$branch'"));     
$name = strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);
$location  = $returned_result["location"];
$j=0;
 
echo "<table><tr><td><p align=center><font size=4><b>$branch BRANCH $name</b><br>
   Clients not Paid on <b> $d </b> </font><br><br></td>";
echo"<td>
<form method='post' action='print_not_paid.php' target='_blank'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='branch' value='$branch'>
<input type=hidden name='officer_id' value='$officer_id'> 
<input type=hidden name='payed_date' value='$date'>

<button type='submit' name=notpaid style='border: 1px solid #7C7C7C; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:150px; margin-left:300px'>
&nbsp;Print Report &nbsp;</button></form></td></tr></table>";

$query ="DELETE from clients_not_paid where user_id='$user_id' and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);

$j=0;
$search_query= mysqli_query($conn,"SELECT * FROM clients, clients_with_loan where users_id='$user_id' and bosses_id='$boss_id' 
and client_id=clientsid and b_location ='$location' order by pay_date");
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

if($risk_date<$prev_date){  
$x=-1;
$missed_balance=$debt;
 }
if($defaulter_date<$prev_date){  
$x=-1;
$missed_balance=$debt;
 }

$total_amount_paid=0;
$amount_supposed_paid=0;
$y=0;

//amount paid
$select = mysqli_query($conn,"SELECT * FROM loan_pay where  
clients_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loanNo' ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_paid"]; 
$total_amount_paid+=$amount;
}

//days
$date1=date_create("$prev_date");
$date2=date_create("$date_given");
$diff=date_diff($date1,$date2);
$y=$diff->format("%a");

if($risk_date<=$prev_date){  
$x=30;
$missed_balance=$debt;
 }
if($defaulter_date<$prev_date){  
$x=91;
$missed_balance=$debt;
 }

if($risk_date>$prev_date && $defaulter_date>$prev_date ){
$amount_supposed_paid=$y*$daily_p;
$missed_balance=$amount_supposed_paid-$total_amount_paid;
$x=$missed_balance/$daily_p;
}

if($missed_balance<0){  
$missed_balance=0;
 }
 
if($date_given==$prev_date){
 $x=0;
 $missed_balance=0;  
}
  
if($curr_date==$date_given){ 
 $x=0;                     
 $missed_balance=0; 
}

if($x<0){
 $x=0;
 }

if($risk_date<$curr_date and $x<0){  
$x=30;
$missed_balance=$debt;
 }

 if($missed_balance<$daily_p && $x==0){  
$missed_balance=0;
 }
 
//end of arreaas


//last date of payment
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loanNo' order by p_date desc limit 1"));     
$amount_paid2=$result['amount_paid'];
$last_date=$result['p_date'];
 
//Total Amount Paid
$total_amount_paid=0;
$select = mysqli_query($conn,"SELECT * FROM loan_pay where  
clients_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loanNo'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_paid"]; 
$total_amount_paid+=$amount;
}

//Amount supposed to be paid
$amount_supposed_paid=0;

$date1=date_create("$prev_date");
$date2=date_create("$date_given");
$diff=date_diff($date1,$date2);
$y=$diff->format("%a");

$amount_supposed_paid=$y*$daily_p;
//Earears calculated
$arears=0;
$arears=$amount_supposed_paid-$total_amount_paid;

//days Missed
$date4=date_create("$last_date");
$date5=date_create("$curr_date");
$diff2=date_diff($date4,$date5);
$days=$diff2->format("%a");

$cliets_not_paid ="select* from loan_pay where clients_id='$client_id' and userse_id='$user_id' and  bossese_id='$boss_id' and p_date='$payed_date'";
$run = mysqli_query($conn, $cliets_not_paid) or die("Could DB");
$cliets_not_paid = mysqli_num_rows($run);

if($last_date=="1970-01-01"){
$last_date="1970-01-01";
//days Missed
$date4=date_create("$date_given");
$date5=date_create("$curr_date");
$diff2=date_diff($date4,$date5);
$days=$diff2->format("%a");
}

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
echo "<table width=100% border=1 style='font-size:14px' align=center>
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
<th width=6%>Arears</th>
<th width=10%>Total Days</th>
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

$month=ceil($total_days/30);

if($nid==1){
$id="Has ID";
}
else{
$id="<font color=red><b>No ID</b></font>";
}

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from loan_fines
where clients_id='$client_id' and user_id='$user_id' and boss_id='$boss_id' and fine_type='Fined' order by pay_date DESC limit 1 "));
$fined_date = $results["pay_date"];
$fined_amount = $results["amount"];

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from loan_fines
where clients_id='$client_id' and user_id='$user_id' and boss_id='$boss_id' and fine_type='Fined_Arears' order by pay_date DESC limit 1 "));
$fine_arear_date = $results["pay_date"];
$fined_amount = $results["amount"];

$date4=date_create("$fine_date");
$date5=date_create("$curr_date");
$diff2=date_diff($date4,$date5);
$days_after_fine=$diff2->format("%a");

if($last_date_paid=="1970-01-01"){
$days_missed="<font color=red><b>$days_missed Not Paid</b></font>";	
}

echo "<tr style='font-size:12px'>
<td> $j </td>
<td> $names  </td>
<td> $phone</td>
<td> $amount_given</td>
<td> $date_given</td>
<td> $last_date_paid</td>
<td> $last_amount</td>
<td> $balance</td>
<td> $days_missed</td>
<td> $arears</td>";

if($total_days>=30 && $total_days<61){
echo "<td> AT RISK </td>";
}

else if($total_days>61){
echo "<td> $month Mths </td>";
}

else{
echo "<td> $total_days </td>";
}
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
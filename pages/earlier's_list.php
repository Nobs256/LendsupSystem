<?php
include('header_user.php');
?>
<style type="text/css">
 tr:nth-child(even) {
background-color: #D9FFD9;
}

th, td {
text-align: left;
padding-left: 10px;
 
}
</style>
<main class="column main" style="background-color:#EAEAEA;"> 
<?php include ('summary.php');
 
echo"
<div id='main_heading'> 
<table border=0 style='margin-left:1px; width:100%'>
<tr><td>
<b>Aging Analysis</b> 
</td><td>
<font color='#E4E4E4'>--------</font>
</td><td>
<form  method='post'> 
Select Field Officer:                    
 
<select  name='officer_id'  style='width:200px; border: 1px solid #006F37; height:25px' required> 
<option></option>
 ";
$comb = mysqli_query($conn,"SELECT * from officers where boss_id='$boss_id' and active=1 and branch='$bra'");

while($select_comb = mysqli_fetch_array($comb)){
$officerid=$select_comb['officer_id'];
$officers_name=$select_comb['firstname']." ".$select_comb['lastname'];
$phone=$select_comb['phone'];
 
echo "<option value= $officerid> $officers_name</option>";
 
}
echo" 
</select> 
<button type='submit' name='officers_analysis' class='button is-primary' 
style='background-color:#006F37; color:white; font-size:12px; border:1px solid #006F37; height:25px; '>
&nbsp;OK&nbsp;</button></form> 
 
</td>";
//general 
echo"<td width=20%>
<form method='post' action='print_general.php' target='_blank'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='branch' value='$branch'>
 
<button type='submit' name=notpaid style='border: 1px solid #7C7C7C; border-radius:3px; font-size:17px; 
color: white; background-color:green; height:30px; width:200px'>
&nbsp;Print General Portifolios &nbsp;</button></form>

</td>

<td width=15%>
<form method='post' action='print_defaulters.php' target='_blank'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='branch' value='$branch'>
 
<button type='submit' name=notpaid style='border: 1px solid #7C7C7C; border-radius:3px; font-size:17px; 
color: white; background-color:blue; height:30px; width:150px'>
&nbsp;Print Defaulters &nbsp;</button></form>

</td>
</tr></table>
</div>
 
<div id='main_container' style='height:440px'>
<div id='main_body' style='height:400px'> ";

if(isset($_POST['officers_analysis'])){
$off = $_POST['officer_id'];

$total_days=0;
$j=0;
$missed_balance=0 ;
$remaining_day=0 ;
$total_amount=0;
echo "<br>";
$returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and officer_id='$off' and  branch='$bra' "));     
$name = $returned_result["firstname"]. " ". $returned_result["lastname"];
$location  = $returned_result["location"];
$branch  = $returned_result["branch"];
$phone  = $returned_result["phone"];

echo"<table border=0 width=100%><tr>
<td width=40%>
Loan Aging Analysis Statement for <br><b>$phone  $branch  Branch ($location)</b><br><br></td>";

echo "<td width=20%>
<form method='post' action='print_earliers_list.php' target='_blank'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='branch' value='$branch'>
<input type=hidden name='officer_id' value='$off'> 

<button type='submit' name=notpaid style='border: 1px solid #7C7C7C; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:220px'>
&nbsp;Print Officers' Portifolios &nbsp;</button></form></td>
</tr></table>";
 
$query ="DELETE from portifolios where user_id='$user_id' and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);

$total=0;
$search_query= mysqli_query($conn,"SELECT  *
FROM clients, clients_with_loan where bosses_id='$boss_id' and client_id=clientsid and   b_location='$location' order by pay_date Desc");  
while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;
$client_id=$returned_result["client_id"];
$name = strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);
$phone=$returned_result["phone"];
$user_id  = $returned_result["users_id"];
$loanNo = $returned_result["loan_no"];
$date_given= $returned_result["pay_date"];
$amount_given = $returned_result["amount_given"];
$daily_p = $returned_result["daily_p"];
$debt=$returned_result["debt"];
$date_given_loan =date("d-m-Y", strtotime($date_given));

$interest=$amount_given*20/100;
$total_amount=$amount_given+$interest; 
$curr_date=date('Y-m-d');
$prev_date = date("Y-m-d", strtotime("$curr_date -1 day")); 
//starting date                                              --nyesiga david
$first_date = date("Y-m-d", strtotime("$date_given +1 day"));
$end_date = date("Y-m-d", strtotime("$date_given +30 day"));

$pre_yr=date("Y",strtotime($date_given));
$curr_yr=date("Y",strtotime($curr_date));
//First date
$x=0;
$not_paid_at_all=0;

 
//No of days
$risk_date=date("Y-m-d", strtotime("$end_date +1 day"));
$defaulter_date=date("Y-m-d", strtotime("$end_date +30 day"));

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


if($risk_date<$prev_date){  
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
$phoneg=0;
$curr_date=date('Y-m-d');

mysqli_query($conn,"INSERT INTO portifolios(ts_id, user_id, boss_id, client_id, date_curr, names, phone, date_given, due_date, 
amount_given, interest, balance, arreas, days_missed, loan_no) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$curr_date', '$name',  '$phone', '$date_given', '$end_date',
'$amount_given', '$interest', '$debt', '$missed_balance', '$x',  '$loanNo')");

}

//fetch data back

echo"  
<table border=1 width=100% style='font-size:12px'>
<tr>
<th width=5%>No</th>
<th width=16%>Client's Name</th>
<th width=9%>Client's Contact</th>
<th width=10%>Date Given Loan</th>
<th width=10%>Due Date</th>
<th width=8%>Amount Given</th>
<th width=8%>Interest</th>
<th width=8%>Balance</th>
<th width=8%>Total Arrears </th>
<th width=10%>Days Missed</th>
 
</tr>";

$j=0;
$active=0;
$at_risk=0;
$defaulters=0;
$fined=0;
$total_active=0;
$total_risk=0;

$search_query= mysqli_query($conn,"SELECT * FROM portifolios where user_id='$user_id' and boss_id='$boss_id' 
and date_curr='$curr_date' order by days_missed");
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id=$returned_result["client_id"];
$names=$returned_result["names"];
$phone = $returned_result["phone"];
$date_given  = $returned_result["date_given"];
$due_date  = $returned_result["due_date"];
$amount_given  = $returned_result["amount_given"];
$interest = $returned_result["interest"];
$balance = $returned_result["balance"];
$arreas = $returned_result["arreas"];
$days_missed = $returned_result["days_missed"];
$loan_no = $returned_result["loan_no"];
$j++;
echo "<tr style='font-size:10px'>";
if($days_missed>=30 && $days_missed<91){
$at_risk++;
$total_risk+=$amount_given;
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM clients_with_fines where clients_id='$client_id'
and user_id='$user_id' and boss_id='$boss_id' and loan_id='$loan_no'"));     
$fine= $result['amount'];
if($fine>0){
$fine++;
$risk="<b>Fined<b>";
}
else{
$risk="<b>AT RISK</b>";
}
echo"
<td> $j </font></td>
<td>".strtoupper($names)."</td>
<td> $phone $phoneg </td>
<td>".date("d-m-Y", strtotime($date_given))."</td>
<td> ".date("d-m-Y", strtotime($due_date))." </td> 
<td>".number_format($amount_given)."</td>
<td>".number_format($interest)."</td>
<td>".number_format($balance)."</td>
<td>".number_format($arreas)."</td>
<td>$risk</td>";
echo "</tr>";
}
else if($days_missed<30){
$active++;
$total_active+=$amount_given;
echo"
<td> $j </font></td>
<td>".strtoupper($names)."</td>
<td> $phone $phoneg </td>
<td>".date("d-m-Y", strtotime($date_given))."</td>
<td> ".date("d-m-Y", strtotime($due_date))." </td> 
<td>".number_format($amount_given)."</td>
<td>".number_format($interest)."</td>
<td>".number_format($balance)."</td>
<td>".number_format($arreas)."</td>
<td>$days_missed  DAYS</td>";
echo "</tr>";
}
else{
$defaulters++;
echo"
<td> $j </font></td>
<td>".strtoupper($names)."</td>
<td> $phone </td>
<td>".date("d-m-Y", strtotime($date_given))."</td>
<td> ".date("d-m-Y", strtotime($due_date))." </td> 
<td>".number_format($amount_given)."</td>
<td>".number_format($interest)."</td>
<td>".number_format($balance)."</td>
<td>".number_format($arreas)."</td>
<td><b>DEFAULTERS</b></td>";
echo "</tr>";
}
}
echo "
<tr><td></td><td>Active:$active</td><td></td><td>Risk:$at_risk</td><td></td><td></td><td></td><td></td><td></td><td>Deflt:$defaulters</td></tr>
</tbody></table>"; 
}

else{
$off="";

echo "Select Field Officer";
} 
?>  
 
</div>
</div>
 

<?php include('footer.php'); ?>
</div>
</main>
 
</body> 
</html>
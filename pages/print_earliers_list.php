<?php 
session_start();
ob_start();

ini_set('memory_limit', ' 1969994M'); // or you could use 1G
ini_set('max_execution_time', 3600); //300 seconds = 5 minutes
include("conn.php");

if(isset($_POST['notpaid'])){       
$officer_id = $_POST['officer_id'];
$user_id = $_POST['user_id']; 
$boss_id = $_POST['boss_id'];  
$branch = $_POST['branch']; 
$today=date('d-m-Y'); 
 

$total_days=0;
$j=0;
$missed_balance=0 ;
$remaining_day=0 ;
$total_amount=0;
 

$returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and officer_id='$officer_id' and branch='$branch' "));     
$name = $returned_result["firstname"]. " ". $returned_result["lastname"];
$location  = $returned_result["location"];
$off_phone  = $returned_result["phone"];
 
 //company
$result = mysqli_fetch_assoc(mysqli_query($conn,"select boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
from bosses where boss_id='$boss_id'"));     
$company= $result['firstname'];

echo "<p align=center><b>$company LTD <br>LOAN AGING ANALYSIS STATEMENT ".strtoupper($branch)." BARANCH ($today)<br>$location-$off_phone</b></p><br>";

$query ="DELETE from portifolios where user_id='$user_id' and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);

$total=0;
$search_query= mysqli_query($conn,"SELECT  *
FROM clients, clients_with_loan where bosses_id='$boss_id' and userseid='$user_id' and client_id=clientsid and   b_location='$location'
 order by pay_date Desc");  
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
if($pre_yr<$curr_yr){  
$x=120;
$missed_balance=$debt;
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
  
 
 if($missed_balance>$debt){  
$missed_balance=$debt;
  $x=30;
 }
  
$phoneg=0;
$gname=0;
$curr_date=date('Y-m-d');
  

$hup=mysqli_query($conn,"SELECT * FROM guarantor where clientg_id='$client_id' and bossesg_id='$boss_id' and usersg_id='$user_id' and g_date='$date_given'");
$now=mysqli_fetch_array($hup);
$phoneg=$now["g_phone1"];
$gname=$now["g_names1"];
  
if($phoneg==0){
 $phoneg="0000000000";
}
  
 if($gname==""){
   $gname="xxxxxxxxxxxxxxx";
 }

mysqli_query($conn,"INSERT INTO portifolios(ts_id, user_id, boss_id, clientf_id, date_curr, names, phone, date_given, due_date, 
amount_given, interest, balance, arreas, days_missed, loan_no, phoneg, gname) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$curr_date', '$name',  '$phone', '$date_given', '$end_date',
'$amount_given', '$interest', '$debt', '$missed_balance', '$x',  '$loanNo', '$phoneg', '$gname')");
}
//fetch data back

echo"  
<table border=1 width=100% style='font-size:9px'>
<tr>
<th width=5%>No</th>
<th width=16%>Client's Name</th>
<th width=8%>Guarantor</th>
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
$total_arrears=0;

$search_query= mysqli_query($conn,"SELECT * FROM portifolios where user_id='$user_id' and boss_id='$boss_id' 
and date_curr='$curr_date' order by days_missed");
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id=$returned_result["clientf_id"];
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
$phoneg = $returned_result["phoneg"];
$gname = $returned_result["gname"];
$j++;
echo "<tr style='font-size:7px'>";
if($days_missed>29 && $days_missed<60){
$at_risk++;
$total_risk+=$balance;
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM clients_with_fines where clients_id='$client_id'
and user_id='$user_id' and boss_id='$boss_id' and loan_id='$loan_no'"));     
$fine= $result['amount'];
if($fine>0){
$fine++;
$risk="<b>Renewed<b>";
}
else{
$risk="<b>AT RISK</b>";
}

echo"
<td> $j </font></td>
<td>".strtoupper($names)."<br>$phone </td>
<td>".strtoupper($gname)."<br>$phoneg </td>
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
$total_active+=$balance;
$total_arrears+=$arreas;
echo"
<td> $j </font></td>
<td>".strtoupper($names)."<br>$phone </td>
<td>".strtoupper($gname)."<br>$phoneg </td>
<td>".date("d-m-Y", strtotime($date_given))."</td>
<td> ".date("d-m-Y", strtotime($due_date))." </td> 
<td>".number_format($amount_given)."</td>
<td>".number_format($interest)."</td>
<td>".number_format($balance)."</td>
<td>".number_format($arreas)."</td>
<td>$days_missed  DAYS</td>";
echo "</tr>";
}

else if($days_missed>60){
$defaulters++;
$total_defaulters+=$balance;
$months=$days_missed/30;
echo"
<td> $j </font></td>
<td>".strtoupper($names)."<br>$phone </td>
<td>".strtoupper($gname)."<br>$phoneg </td>
<td>".date("d-m-Y", strtotime($date_given))."</td>
<td> ".date("d-m-Y", strtotime($due_date))." </td> 
<td>".number_format($amount_given)."</td>
<td>".number_format($interest)."</td>
<td>".number_format($balance)."</td>
<td>".number_format($arreas)."</td>
<td><b>Defaulter</b></td>";
echo "</tr>";
}
 else{
  echo "";
 }
}
$i_active=$total_active*0.2;
$daily_active=($total_active+$i_active)/30;

$i_risk=$total_risk*0.2;
$daily_risk=($total_risk+$i_risk)/30;

$i_defaulters=$total_defaulters*0.2;
$daily_defaulters=($total_defaulters+$i_defaulters)/30;
$total_daily=$daily_active+$daily_risk+$daily_defaulters;

echo "
<tr>
<td><td colspan=9> &nbsp;&nbsp;&nbsp;  </td>
</tr>
<tr>
<td></td><td colspan=3>Active:$active &nbsp;&nbsp; Total Blc:".number_format($total_active)."&nbsp;&nbsp; <b>Tot.Arrears: ".number_format($total_arrears)."</b></td>
<td colspan=3>Risk:$at_risk &nbsp;&nbsp;&nbsp;Total Blc:".number_format($total_risk)."&nbsp;&nbsp;&nbsp;</td> 
<td colspan=3>Deflt:$defaulters &nbsp;&nbsp;&nbsp;  Total Blc:".number_format($total_defaulters)."&nbsp;&nbsp;&nbsp;</td></tr>
<tr>
<td><td colspan=9>Total Amount suppossed to be collected from $location:&nbsp;&nbsp;&nbsp;<b>".number_format($total_daily)."</td>
</tr>
</table>
<table border=1 width=100% style='font-size:12px'>
<tr>";
$curr_year=date('Y');
$total_arrears=0;
$total_def=0;
$total_blc=0;

for($year=2020; $year<=$curr_year; $year++){
$total_def=0;
$total_blc=0;
$search_query= mysqli_query($conn,"SELECT  * FROM clients, portifolios  where boss_id='$boss_id' and user_id='$user_id' 
and YEAR(date_given)='$year' and clientf_id=client_id and days_missed>60 and b_location='$location' order by date_given Desc");  
while($returned_result = mysqli_fetch_assoc($search_query)){
$debt  = $returned_result["balance"];
$total_blc+=$debt;
$total_def++;
}

echo"<tr>
<td colspan=4>$year &nbsp; &nbsp;&nbsp;Total No:$total_def &nbsp;&nbsp;&nbsp;  Total blc:&nbsp;".number_format($total_blc)."</td></tr>";
}
echo"
</tbody></table>"; 
}

echo"
<div>"; 

include("mpdf60/mpdf.php");
$mpdf=new mPDF('P','A3'); 
$mpdf = new mPDF(); $stylesheet = file_get_contents('mpdf60/pdf.css'); $mpdf->WriteHTML($stylesheet,1);
$mpdf->SetDisplayMode('fullpage');
//$mpdf->AddPage("L");

// LOAD a stylesheet
$stylesheet = file_get_contents('mpdf60/mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this is css/style only and no body/html/text
$report_content=ob_get_contents();
ob_clean();

$mpdf->WriteHTML($report_content,2);
$mpdf->Output('Portifolio.pdf','I');
 
exit;
?>
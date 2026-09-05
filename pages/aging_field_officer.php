<?php
include('header.php');
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
<?php include ('summary_admin.php');

echo"
<div id='main_heading'> 
<table border=0 style='margin-left:1px; width:100%'>
<tr><td>
<b>Loan Aging Analysis Statement </b> 
</td><td>
<font color='#E4E4E4'>---------</font>
</td><td>
<form  method='post'> 
Select Field Officer:                    
 
<select  name='fname'  style='width:180px; border: 1px solid #006F37; height:25px' required> 
<option></option>
 ";
$comb = mysqli_query($conn,"SELECT * from officers where boss_id='$boss_id' and active=1");

while($select_comb = mysqli_fetch_array($comb)){
$officerid=$select_comb['officer_id'];
$officers_name=$select_comb['firstname']." ".$select_comb['lastname'];
 
echo "<option value= $officers_name>".strtoupper($officers_name)." </option>";
 
}
echo" 
</select> 
<button type='submit' name='officers_analysis' class='button is-primary' 
style='background-color:#006F37; color:white; font-size:12px; border:1px solid #006F37; height:25px; '>
&nbsp;OK&nbsp;</button></form> 
 
</td></tr></table></div>
<br>  
</div>
  
<div id='main_container' style='height:430px'> 
<div id='main_body' style='height:410px'> ";
if(isset($_POST['officers_analysis'])){
$off = $_POST['fname'];

$total_days=0;
$j=0;
$missed_balance=0 ;
$remaining_day=0 ;
$total_amount=0;
echo "<br>";
$returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and firstname='$off' "));     
$name = $returned_result["firstname"]. " ". $returned_result["lastname"];
$location  = $returned_result["location"];
$branch  = $returned_result["branch"];

echo"
Loan Aging Analysis Statement for <b>$name  $branch  Branch ($location)</b><br><br>";
 
echo"  
<table border=1 width=100%>
<tr>
<th>No</th>
<th width=20%>Client's Name</th>
<th width=12%>Client's Contact</th>
<th>Loan No</th>
<th width=12%>Date Given Loan</th>
<th width=12%>Due Date</th>
<th>Amount Given</th>
<th>Interest</th>
<th>Balance</th>
<th>Total Arrears </th>
<th>Days Missed</th>
<th>Days Remaining</th>
</tr>";
$search_query= mysqli_query($conn,"SELECT  *
FROM clients, clients_with_loan where bosses_id='$boss_id' and client_id=clientsid and   b_location='$location' order by pay_date");  
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
$debt= number_format($returned_result["debt"]);
$date_given_loan =date("d-m-Y", strtotime($date_given));

$interest=$amount_given*20/100;
$total_amount=$amount_given+$interest;
$curr_date=date('Y-m-d');
$prev_date = date("Y-m-d", strtotime("$curr_date -1 day"));
//starting date
$date = date("Y-m-d", strtotime("$date_given +1 day"));
$end_date = date("Y-m-d", strtotime("$date_given +30 day"));

echo "
<tr style='font-size:11px'>
<td> $j</td> 
<td> $name</td>
<td> $phone</td>
<td> $loanNo</td>              
<td> $date_given_loan</td>
<td> ".date("d-m-Y", strtotime($end_date))."</td>
<td> ".number_format($amount_given)."</td>
<td> ".number_format($interest)."</td>
<td> $debt </td> ";

//last date
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loanNo' order by p_date desc limit 1"));     
$last_date = $result['p_date'];

$current_loans=mysqli_query($conn,"SELECT * FROM loan_pay where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loanNo' order by p_date desc limit 1");
$now=mysqli_fetch_array($current_loans);

$no_date_found=mysqli_num_rows($current_loans); 
if($no_date_found==0){
$last_date=$curr_date;
}

if($date_given==$last_date){
$last_date=$date;
}
//No of days
$x=0;
$total_amount_paid=0;
$amount_supposed_paid=0;
$y=0;
 
while($x>=0){

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loanNo' and p_date='$date' order by p_date "));     
$amount_paid= $result['amount_paid'];
$payed_date=$result['p_date'];

$total_amount_paid+=$amount_paid;
$y++;
if($amount_paid==0){
$x++;
}
if($date==$last_date || $date==$end_date){
break;
}
$date = date("Y-m-d", strtotime("$date +1 day"));
} 
//remaining days
$d1 = new DateTime("$curr_date 00:00:00");
$d2 = new DateTime("$end_date 00:00:00");
$interval = $d2->diff($d1);
$remaining_day= $interval->d; //21
if($end_date<$curr_date){
$remaining_day='<font Color=red>Defaulter</font>';	
$missed_balance=$debt;
 }
 else {
$amount_supposed_paid=$daily_p*$y;
$missed_balance=$amount_supposed_paid-$total_amount_paid;
 }

 if($missed_balance<0){
 $missed_balance=0;
 }
echo "<td> $missed_balance </td>";
echo "<td> $x  </td>";
echo "<td> $remaining_day  </td>";
echo "</tr>";

}
echo "</tbody></table>"; 

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
</script>
</body> 
</html>
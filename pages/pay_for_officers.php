<?php
$sa="";
include('header_user.php');

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from total_msg where user_id='$user_id' 
and boss_id='$boss_id'")); 
$total_msg= $result['total'];

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from sent_msgs where user_id='$user_id' 
and boss_id='$boss_id' order by ts_id DESC Limit 1")); 
$msg_date= $result['msg_date'];

?>
<style type="text/css">
table {
border-collapse: collapse;
border-spacing: 0;
width: 100%;
border: 0px solid #D9FFD9 ;
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
<?php include ('summary.php') ;
 
echo"
<div id='main_heading'> 
<table border=0 style='margin-left:1px; width:100%'>
<tr><td>
<b>Pay For Clients from the Field </b> 
</td><td>
<font color='#E4E4E4'>--</font>
</td><td>

<form  method='post'> 
Field Officer:                    
 
<select  name='officer_id'  style='width:140px; border: 1px solid #006F37; height:25px' required> 
<option></option> ";
$comb = mysqli_query($conn,"SELECT * from officers where boss_id='$boss_id' and active=1 and branch='$bra'");

while($select_comb = mysqli_fetch_array($comb)){
$officerid=$select_comb['officer_id'];
$officers_name=$select_comb['firstname']." ".$select_comb['lastname'];
$phone=$select_comb['phone'];
echo "<option value= $officerid>$officers_name </option>";
 
}
echo" 
</select> 

Date: 
<input type='date' name='p_date' style='width:150px; height:25px; border: 1px solid #006F37' required> 

<button type='submit' name='officers' class='button is-primary' 
style='background-color:#006F37; color:white; font-size:12px; border:1px solid #006F37; height:25px; '>
&nbsp;OK&nbsp;</button></form>  
</td>";
//general 
echo"<td width=20%></td>
</tr></table>
</div>";
 ?>
 
<div id="main_container" style="height:450px"> 
<div id="main_body" style="height:410px"> 
<?php
if(isset($_POST['officers'])){
$officer_id = $_POST['officer_id'];
$pay_date = $_POST['p_date'];
  
$not_report_sent = date('Y-m-d', strtotime("$pay_date -1 day"));
  
$report=0;
if($pay_date <=$msg_date){
$report=1;
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>You Have Already Sent Report. Not Allowed to make this Payment
<a href='user_homepage.php' style='color:white; margin-left:70px;''>X</a></div>";
}

if($not_report_sent>$msg_date){
$report=2;
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>You have not sent a report, First send a report to Your Boss!! $not_report_sent
<a href='user_homepage.php' style='color:white; margin-left:70px;''>X</a></div>";
}
/*
if($total_msg <=0){
$report=1;
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>You Have No SMS Left on Your Account! Call 0777842873
<a href='user_homepage.php' style='color:white; margin-left:70px;''>X</a></div>";
}
*/
?>
<div id="list_cont"><?php echo $sa; ?></div>
<?php
$returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and officer_id='$officer_id' "));     
$name = $returned_result["firstname"]. " ". $returned_result["lastname"];
$officers_name = $name;
$location  = $returned_result["location"];
$branch  = $returned_result["branch"];
$phone  = $returned_result["phone"];

//total Paid
$total_amount=0;
$select = mysqli_query($conn,"SELECT * FROM field_payment where  pay_date='$pay_date' 
 and user_id='$user_id' and boss_id='$boss_id' and officerid='$officer_id' and mom=0");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$total_amount+=$amount;
}

//total Paid
$total_unknown_cash=0;
$select = mysqli_query($conn,"SELECT * FROM unknown_cash where  unknown_date='$pay_date' 
 and user_id='$user_id' and boss_id='$boss_id' and officer='$officer_id'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$total_unknown_cash+=$amount;
}

//Total deposite using MOM
$total_amount_mom=0;
 
$select = mysqli_query($conn,"SELECT * FROM field_payment where  pay_date='$pay_date' 
 and user_id='$user_id' and boss_id='$boss_id' and officerid='$officer_id' and mom=1");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$total_amount_mom+=$amount;
}
$total_coll=0;
$total_coll=$total_amount+$total_amount_mom+$total_unknown_cash;
$payingdate=date("d-m-Y", strtotime($pay_date));
echo"<table border=1 width=100%><tr>
<td width=60%>
<p align=left><b>You are Paying for $officers_name  - ($location) on <u> $payingdate</u></b></td>
<td width=30%>Cash: $total_amount &nbsp;&nbsp;  Unknown: $total_unknown_cash &nbsp;&nbsp;  Total: $total_coll</td>

</tr></table>";?>

<table border="1" style="border: 1px solid #006F37">
<tr>
<td width="50%">
 
<div class="table-responsive" style="height:30%; width: 50%">
<table id="example" class="table table-striped table-bordered second" 
style="width:50%; height:30%; font-size:12px; border: 1px solid #006F37" align="left">            
 
<thead>
<tr>
<th width="4%">No</th>
<th width="15%">Name</th>
<th width="7%">Phone</th>
<th width="10%">Balance</th>
<th width="13%">Daily Pyt</th>
<th width="10%">Amount</th> 
<th width="7%"> </th>
</tr></thead><tbody>

<?php

$j=0;
$search_query= mysqli_query($conn,"SELECT * FROM clients, clients_with_loan  where users_id='$user_id' 
and bosses_id='$boss_id' and debt>0 and client_id=clientsid order by firstname");
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id=$returned_result["client_id"];
$name = strtoupper($returned_result["firstname"]." ".$returned_result["lastname"]); 
$phone = $returned_result["phone"];                
$business = $returned_result["business"];
$loan_no = $returned_result["loan_no"];
$b_location = $returned_result["b_location"];
$amount = $returned_result["amount_given"];
$interest = 20/100*$amount;
$total=number_format($amount+$interest);
$daily_p = number_format($returned_result["daily_p"]);
$debt =number_format($returned_result["debt"]);
$amount_given=number_format($amount);

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from new_users where username='$user_email'"));
$user_id = $results["user_id"];
$boss_id = $results["boss_id"];

$j++;

echo "<tr>
<td> $j </td>
<td> $name </td>
<td> $phone <br>$b_location</td>

<td> $debt</td>           
<td> $daily_p</td>
<td>

<form method='post' action='pay_for_officers_connector.php' >
<input type='hidden' name='boss_id' value='$boss_id'>
<input type='hidden' name='user_id' value='$user_id'>
<input type='hidden' name='client_id' value='$client_id'>
<input type='hidden' name='loan_no' value='$loan_no'>
<input type='hidden' name='b_date' value='$pay_date'>
<input type='hidden' name='officer_id' value='$officer_id'>
<input type='hidden' name='report' value='$report'>
<input type='text' name='amo' class='input is-success' style='width:100px; height:30px; border: 1px solid #006F37'></td>
<td>

<button type='submit' name=field_pay style='border: 1px solid #7C7C7C; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:80px'>
&nbsp;Pay  &nbsp;</button></form></td>";
echo "</tr>";

}
echo "</tbody></table>
</td>
<td>";

if($total_amount==0){
echo"";
}
else{
echo"<b> Clients Paid on $payingdate </b>
<br>
<table width=90% border=1 align=center style='font-size:12px; border: 1px solid #006F37'>
<thead>
<tr> 
<th>No</th>
<th>Names</th>
<th>Amount</th>
<th>Total</th>
</tr>
</thead>
<tbody>";
$total=0;
$no=0;
$search_query= mysqli_query($conn,"SELECT * FROM clients, field_payment  where users_id='$user_id' and bosses_id='$boss_id' and client_id=clientf_id and  pay_date='$pay_date' and  officerid='$officer_id' and mom=0 order by ts_id");
while($returned_result = mysqli_fetch_assoc($search_query)){
$no++;
$client_id=$returned_result["client_id"];
$name = strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);
$phone  = $returned_result["phone"];
$amount_paid  =$returned_result["amount"];
$total+=$amount_paid;

echo "<tr style=font-size:10px>
<td>&nbsp;&nbsp;&nbsp; $no  </td>
<td>&nbsp;&nbsp;&nbsp; $name  </td> 
<td>&nbsp;&nbsp;&nbsp;".number_format($amount_paid)."</td>
<td>&nbsp;&nbsp;&nbsp;".number_format($total)."</td>";
}

echo "</tr>
<tr>
<td></td>
<td><b>Total brought by $name  <br> Uknown: $total_unknown_cash </b></td>
<td><b>".number_format($total+$total_unknown_cash)."</b></td>
<td><b>".number_format($total+$total_unknown_cash)."</b></td>
</tr></table>
<br>
<table align=center>
<tr> 
<td>
<form method='post' action='print_field_officer_payments.php' target='_blank'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='branch' value='$branch'>
<input type=hidden name='officer_id' value='$officer_id'> 
<input type=hidden name='p_date' value='$pay_date'> 

<button type='submit' name='field_officer' style='border: 1px solid #006F37; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:200px'>
&nbsp;Print Payment Report&nbsp;</button></form>
</td>

<td>
<form method='post' action='field_receipts.php' target='_blank'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='officer_id' value='$officer_id'> 
<input type=hidden name='pay_date' value='$pay_date'> 

<button type='submit' name='allreceipt' style='border: 1px solid #006F37; border-radius:3px; 
font-size:17px; color: white; background-color:#006F37; height:30px; width:200px; margin-left:30px'>
&nbsp;Print Receipts&nbsp;</button></form>
</td>
</tr>
</table>";
}

echo"</td>
</tr>
</table>
";
}
//=================================================
//============================++++++===================
///============================================
else if(isset($_GET['officer_id'])){
$officer_id = $_GET['officer_id'];
$pay_date = $_GET['pay_date'];
 
$curr_date=date('Y-m-d');
$pre_date = date('Y-m-d', strtotime("$curr_date -1 day"));
$sa="";
if(isset($_GET['greater_balance'])){
$sa = "<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>Error! The Amount entered is greater than balance
<a href='pay_for_officers.php?officer_id=$officer_id' style='color:white; margin-left:100px;''>X</a></div>";
}

if(isset($_GET['reportsent'])){
$sa = "<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>Error! The Report is already Sent
<a href='user_homepage.php' style='color:white; margin-left:100px;''>X</a></div>";
}
  
if(isset($_GET['reportnotsent'])){
$sa = "<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>Error! The Previous Report is not Sent. First send report to the Boss!!
<a href='user_homepage.php' style='color:white; margin-left:100px;''>X</a></div>";
}

if(isset($_GET['wrong_date'])){

$sa = "<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>Error! You are beyond recommended time!!
<a href='pay_for_officers.php?officer_id=$officer_id' style='color:white; margin-left:100px;''>X</a></div>"; 
}

if(isset($_GET['wrong_date2'])){

$sa = "<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>Error! The Date Entered is less than the Date Last Paid!!
<a href='pay_for_officers.php?officer_id=$officer_id' style='color:white; margin-left:100px;''>X</a></div>"; 
}

if(isset($_GET['paid'])){
$client_id=$_GET['client_id'];
$pay_date=$_GET['pay_date'];
$amount=$_GET['amount'];
$loan_no=$_GET['loan_no'];
$officer_id = $_GET['officer_id'];

$sa = "<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 720px'>
<table border='0'>
<tr>
<td width='500px'><font size='4'>The client has already Paid!&nbsp;&nbsp; Do want to pay More?</font></td>
<td> 
<form method='GET' action='pay_for_officers_more.php'>
<input type=hidden name='client_id' value='$client_id'>
<input type=hidden name='b_date' value='$pay_date'>
<input type=hidden name='amount' value='$amount'>
<input type=hidden name='loan_no' value='$loan_no'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='officer_id' value='$officer_id'>
<button type='submit' name='pay_more' class='button is-default' 
style='padding-top:0px; border: 0px solid #006F37; border-radius:4px; height:15px; color:#006F37; background-color:red; color:white'>
&nbsp; Yes &nbsp;</button>
</form>
</td>
<td>
<a href='pay_for_officers.php?reload=1' style='color:white; margin-left:100px;''>No</a>
</td></tr></table>


</div>"; 
}

if(isset($_GET['success'])){

echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:5px; width: 430px'>Loan is Successfully Paid!
<a href='pay_for_officers.php?officer_id=$officer_id' style='color:white; margin-left:100px;''>X</a>
</div>"; 
}
 
 ?>
<div id="list_cont"><?php echo $sa; ?></div>
<?php
$returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and officer_id='$officer_id' "));     
$name = $returned_result["firstname"]. " ". $returned_result["lastname"];
$officers_name = $name;
$location  = $returned_result["location"];
$branch  = $returned_result["branch"];
$phone = $returned_result["phone"];

 
//total Paid
$total_amount=0;
$select = mysqli_query($conn,"SELECT * FROM field_payment where  pay_date='$pay_date' 
 and user_id='$user_id' and boss_id='$boss_id' and officerid='$officer_id' and mom=0");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$total_amount+=$amount;
}

//Total deposite using MOM
$total_amount_mom=0;
 
$select = mysqli_query($conn,"SELECT * FROM field_payment where  pay_date='$pay_date' 
 and user_id='$user_id' and boss_id='$boss_id' and officerid='$officer_id' and mom=1");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$total_amount_mom+=$amount;
}
$total_coll=0;
$total_coll=$total_amount+$total_amount_mom+$total_unknown_cash;

$payingdate=date("d-m-Y", strtotime($pay_date));
echo"<table border=1 width=100%><tr>
<td width=60%>
<p align=left><b>You are Paying for $officers_name  - ($location) on <u> $payingdate</u></b></td>
<td width=30%>Cash: $total_amount &nbsp;&nbsp;  Unknown: $total_unknown_cash &nbsp;&nbsp;  Total: $total_coll</td>

</tr></table>";
?>
 
<table border="1" style="border: 1px solid #006F37">
<tr>
<td width="50%">
 
<div class="table-responsive" style="height:30%; width: 50%">
<table id="example" class="table table-striped table-bordered second" 
style="width:50%; height:30%; font-size:12px; border: 1px solid #006F37" align="left">            
 
<thead>
<tr>
<th width="4%">No</th>
<th width="15%">Name</th>
<th width="7%">Phone</th>
<th width="10%">Balance</th>
<th width="13%">Daily Pyt</th>
<th width="10%">Amount</th> 
<th width="7%"> </th>
</tr></thead><tbody>

<?php

$j=0;
$report=0;
$search_query= mysqli_query($conn,"SELECT * FROM clients, clients_with_loan  where users_id='$user_id' 
and bosses_id='$boss_id' and debt>0 and client_id=clientsid order by firstname");
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id=$returned_result["client_id"];
$name = strtoupper($returned_result["firstname"]." ".$returned_result["lastname"]); 
$phone = $returned_result["phone"];                
$business = $returned_result["business"];
$loan_no = $returned_result["loan_no"];
$b_location = $returned_result["b_location"];
$amount = $returned_result["amount_given"];
$interest = 20/100*$amount;
$total=number_format($amount+$interest);
$daily_p = number_format($returned_result["daily_p"]);
$debt =number_format($returned_result["debt"]);
$amount_given=number_format($amount);

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from new_users where username='$user_email'"));
$user_id = $results["user_id"];
$boss_id = $results["boss_id"];

$j++;

echo "<tr>
<td> $j </td>
<td> $name </td>
<td> $phone <br>($b_location)</td>  
<td> $debt</td>           
<td> $daily_p</td>
<td>

<form method='post' action='pay_for_officers_connector.php' >
<input type='hidden' name='boss_id' value='$boss_id'>
<input type='hidden' name='user_id' value='$user_id'>
<input type='hidden' name='client_id' value='$client_id'>
<input type='hidden' name='loan_no' value='$loan_no'>
<input type='hidden' name='b_date' value='$pay_date'>
<input type='hidden' name='officer_id' value='$officer_id'>
<input type='hidden' name='report' value='$report'>
<input type='text' name='amo' class='input is-success' style='width:100px; height:30px; border: 1px solid #006F37'></td>
<td>

<button type='submit' name=field_pay style='border: 1px solid #7C7C7C; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:80px'>
&nbsp;Pay  &nbsp;</button></form></td>";
echo "</tr>";

}
echo "</tbody></table>
</td>
<td>";

if($total_amount==0){
echo"";
}
else{
echo"<b> Clients Paid on $payingdate </b>
<br>
<table width=90% border=1 align=center style='font-size:12px; border: 1px solid #006F37'>
<thead>
<tr> 
<th>No</th>
<th>Names</th>
<th>Amount</th>
<th>Total</th>
</tr>
</thead>
<tbody>";
$total=0;
$no=0;
$search_query= mysqli_query($conn,"SELECT * FROM clients, field_payment  where users_id='$user_id' and bosses_id='$boss_id' and client_id=clientf_id and  pay_date='$pay_date' and  officerid='$officer_id' and mom=0 order by ts_id");
while($returned_result = mysqli_fetch_assoc($search_query)){
$no++;
$client_id=$returned_result["client_id"];
$name = strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);
$phone  = $returned_result["phone"];
$amount_paid  =$returned_result["amount"];
$total+=$amount_paid;

echo "<tr style=font-size:10px>
<td>&nbsp;&nbsp;&nbsp; $no  </td>
<td>&nbsp;&nbsp;&nbsp; $name  </td> 
<td>&nbsp;&nbsp;&nbsp;".number_format($amount_paid)."</td>
<td>&nbsp;&nbsp;&nbsp;".number_format($total)."</td>";
}

echo "</tr>
<tr>
<td></td>
<td><b>Total brought by $name  </b><br>Uknown: $total_unknown_cash</td>
<td><b>".number_format($total+$total_unknown_cash)."</b></td>
<td><b>".number_format($total)."</b></td>
</tr></table>
<br>
<table align=center>
<tr> 
<td>
<form method='post' action='print_field_officer_payments.php' target='_blank'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='branch' value='$branch'>
<input type=hidden name='officer_id' value='$officer_id'> 
<input type=hidden name='p_date' value='$pay_date'> 

<button type='submit' name='field_officer' style='border: 1px solid #006F37; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:200px'>
&nbsp;Print Payment Report&nbsp;</button></form>
</td>

<td>
<form method='post' action='field_receipts.php' target='_blank'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='officer_id' value='$officer_id'> 
<input type=hidden name='pay_date' value='$pay_date'> 

<button type='submit' name='allreceipt' style='border: 1px solid #006F37; border-radius:3px; 
font-size:17px; color: white; background-color:#006F37; height:30px; width:200px; margin-left:30px'>
&nbsp;Print Receipts&nbsp;</button></form>
</td>
</tr>
</table>";
}

echo"</td>
</tr>
</table>
";
}

else{
$off="";
echo "Select Field Officer";
}
?> 
 

 
  </div>
</div>
</div> 
<?php include('footer.php');  
?>
</div>
</main>
</body> 
</html>
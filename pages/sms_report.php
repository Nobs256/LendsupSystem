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
<p align="center"> 
<?php include ('summary.php') ;?>
<div id="main_heading"> 
<table><tr><td><b>SMS LOAD STATEMENT</b> </td><td><font color="#EAEAEA">---------------------------------</font></td>
<td>
<form method="post"> 
<div class="select is-success">                   
<select  name="months"  style="width:180px; border: 1px solid #006F37; height:35px" required> 
<option>Month</option>
<option value="01">January</option> 
<option value="02">Febuary</option>
<option value="03">March</option>
<option value="04">April</option>
<option value="05">May</option>
<option value="06">June</option>
<option value="07">July</option>
<option value="08">August</option>
<option value="09">September</option>
<option value="10">October</option>
<option value="11">November</option> 
<option value="12">December</option>
</select>
</div>
<div class="select is-success">  
<select  name="year"  style="width:120px; border: 1px solid #006F37; height:35px" required> 
<option>Year:</option>
<?php 
 for ($yr=2021; $yr <=2040 ; $yr++) { 
  echo"<option> $yr </option>";
 }  
echo" 
</select></div>";
?>
<button type="submit" name="monthly_report" class="button is-primary" 
style="border: 1px solid #006F37; border-radius:4px; color:white; background-color:#006F37">
&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;</button>
</label>   
</form>     
</td></tr></table>

</div>     
 
<div id="main_container">
<div id="main_body">   
<br>  
<?php
$mon="";
$total_amounts=0;
if(isset($_POST['monthly_report'])){
$month = $_POST['months'];
$year = $_POST['year'];

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from new_users where boss_id='$boss_id' and active=1 
and category='User' and user_id='$user_id' "));
$branch=$results['branch'];
  
include ('cash_at_hand_profit.php');

$dateObj   = DateTime::createFromFormat('!m', $month);
$mon = $dateObj->format('F'); 

 $total_amount=0;

//Total Reg fee in this month
$select = mysqli_query($conn,"SELECT * FROM sms_add where  MONTH(sms_date)='$month' and YEAR(sms_date)='$year' 
and user_id='$user_id' and boss_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["sms_amount"]; 
$total_amount+=$amount;
}
$total_amount=number_format($total_amount);

//clients paid
$clients_paid = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM loan_pay, clients where 
userse_id='$user_id' and bossese_id='$boss_id' and  MONTH(p_date)='$month' and YEAR(p_date)='$year'  and client_id=clients_id")); 

$amount_used=number_format($clients_paid*40);
echo"<p align=center>
<b>Total SMS bought in $mon-$year from $branch is $total_amount &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 Total No. of Clients paid: $clients_paid &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Total SMS Used: $amount_used </b></p><br><br>
<table border=0 width=100%>
<tr>
<td>";
 
echo "<table width=60% border=1 align=center>
<thead>
<tr>
<th>No.</th>
<th>Date</th>
<th>Amount</th>
</tr>
</thead>
<tbody>
<tr>"; 
$j=0;
$select = mysqli_query($conn,"SELECT * FROM sms_add where  MONTH(sms_date)='$month' and YEAR(sms_date)='$year' 
and user_id='$user_id' and boss_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$date= $selected["sms_date"]; 
$amount= $selected["sms_amount"]; 
$j++;

echo"
<td><a href=#><font color=black>$j</font></a></td> 
<td><a href=#><font color=black>$date</font></a></td> 
<td><a href=#><font color=black>".number_format($amount)."</font></a></td>
</tr>";
}
 
echo"</table>
</td>
<td></td>
</tr>
</table>";
}
else{
echo "Select Month and Year";
}

?>
 
</div>
</div>
</div>
 
</main>
</body> 
</html>
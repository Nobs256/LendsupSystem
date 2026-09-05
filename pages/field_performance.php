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
<table><tr><td><b>FIELD PERFORMANCE</b> </td><td><font color="#EAEAEA">--------------------------------------</font></td>
<td>
<form method="post">

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
$officer_id=$_POST['officer_id'];

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from new_users where boss_id='$boss_id' and active=1 and category='User' and user_id='$user_id'"));
$branch=$results['branch'];
  
$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from officers where officer_id='$officer_id'"));
$fname=strtoupper($results['firstname']." ".$results['lastname']); 
$location=$results['location'];

$loan_clients = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients_with_loan, clients where clientsid=client_id and b_location='$location' and 
userseid='$user_id' and bosseseid='$boss_id' and debt>0")); 

$select = mysqli_query($conn,"SELECT * FROM loan_pay, clients  where MONTH(p_date)='$month' and YEAR(p_date)='$year'  AND userse_id='$user_id' 
and bossese_id='$boss_id' and  clients_id=client_id and b_location='$location' ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_paid"]; 
$total_amount+=$amount;
$avg=$total_amount/30;
$total_amounts=number_format($total_amount);
}


$dateObj   = DateTime::createFromFormat('!m', $month);
$mon = $dateObj->format('F'); 

echo" <b> New Clients For $fname  in $mon ($year)   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; No. of Clients with Loans: $loan_clients &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 Average Daily Collection:".ceil($avg)."</b><br><br>
<table width=60% border=1 align=center>
<thead>
<tr> 
<th>No</th>
<th>Date Taken</th> 
<th>Names</th>
<th>Phone</th>
<th>Amount Given</th>
</tr>
</thead>
<tbody>";
$new_clients=0;
$j=0;
$select = mysqli_query($conn,"SELECT * FROM clients WHERE users_id='$user_id' and bosses_id='$boss_id' and  b_location='$location'");
while($returned_result= mysqli_fetch_array($select)){  
$client_id=$returned_result["client_id"];
$firstname = $returned_result["firstname"]. " ". $returned_result["lastname"];
$phone  = $returned_result["phone"];

$hup=mysqli_query($conn,"SELECT * FROM completed_loan WHERE loans_no=1 and MONTH(pay_date)='$month' and YEAR(pay_date)='$year' and clientcpid='$client_id' and userscpid='$user_id' and bosscpid='$boss_id'");
$now=mysqli_fetch_array($hup);
$loanNo=$now["loans_no"];
$amount = $now["amount_given"];
$date = $now["pay_date"];
$d=date("d-m-Y", strtotime($date));

if($amount>0){
$j++;
$new_clients++;
$total+=$amount;  
echo "<tr style=font-size:12px>
<td>&nbsp;&nbsp;&nbsp; $j</td>
<td>&nbsp;&nbsp;&nbsp; $d</td>
<td>&nbsp;&nbsp;&nbsp; $firstname  </td>
<td>&nbsp;&nbsp;&nbsp; $phone</td>
<td>&nbsp;&nbsp;&nbsp; ".number_format($amount)."</td>";
echo "</tr>";

}
}
echo "<tr style=font-size:12px>
<td><b>TOTAL</b> </td>
<td><b>No. $new_clients</b> </td>
<td> </td>
<td></td>
<td>&nbsp;&nbsp;&nbsp;<b>".number_format($total)."</b></td>";
echo "</tr>";
 
echo "</tbody></table>";
mysqli_close($conn);
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
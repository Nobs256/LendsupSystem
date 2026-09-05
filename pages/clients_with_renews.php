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
<?php include ('summary.php') ;
 $total_amos=0;
 $total_amo=0;
//Total Loans in this month
$select = mysqli_query($conn,"SELECT * FROM loan_fines where  user_id='$user_id' and boss_id='$boss_id' and   fine_type='Renewed'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$total_amo+=$amount;
$total_amos=number_format($total_amo);
}
?>
<div id="main_heading"> 
<table><tr><td><b>All Clients With Renews</b> (<?php echo $total_amos;?>) </td><td><font color="#EAEAEA">----------------------------------</font></td>
<td>
<form method="post">    
<label class="label"> Select Month: 
<div class="select is-success">                   
<select  name="months"  style="width:180px; border: 1px solid #006F37; height:35px" required> 
<option value="01">January</option> 
<option value="02">Febuary</option>
<option value="03">March</option>
<option value="04">April</option>
<option value="05">May</option>
<option value="06">June</option>
<option value="07">July</option>
<option value="08">August</option>
<option value="09">September</option>
<option value="10">Octomber</option>
<option value="11">November</option> 
<option value="12">December</option>

</select>
</div>
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
if(isset($_POST['monthly_report'])){
$month = $_POST['months'];
}
else{
$month=date('m');
}
$total_amount=0;
$total_amounts=0;

$dateObj   = DateTime::createFromFormat('!m', $month);
$mon = $dateObj->format('F'); 

//Total Loans in this month
$select = mysqli_query($conn,"SELECT * FROM loan_fines where  MONTH(pay_date)='$month' and user_id='$user_id' 
and boss_id='$boss_id' and fine_type='Renewed'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$total_amount+=$amount;
$total_amounts=number_format($total_amount);
}
echo "<b>Total Amount Added in $mon is $total_amounts </b>";
echo "<br><br>";
$j=0;

echo "<table width=90% border=1>
<thead>
<tr>
<th>No</th>
<th>Date</th>
<th>Names</th>
<th>Amount added</th>
<th>Fine Type</th>
 
</tr>
</thead>
<tbody>";
$amounts=0;
$search_query= mysqli_query($conn,"SELECT client_id, ucase(firstname) as firstname, ucase(lastname) as lastname, pay_date, amount, fine_type 
FROM clients, loan_fines where users_id='$user_id' and bosses_id='$boss_id' and client_id=clients_id and  MONTH(pay_date)='$month' and fine_type='Renewed' order by pay_date");
 while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;

$client_id=$returned_result["client_id"];
$firstname = $returned_result["firstname"]. " ". $returned_result["lastname"];
$amounts  = number_format($returned_result["amount"]); 
$date = $returned_result["pay_date"];
$type=$returned_result["fine_type"];
$d=date("d-m-Y", strtotime($date));
 
echo "<tr>
<td><font size=3> $j  </font></td>
<td><font size=3> $d  </font></td>
<td><font size=3> $firstname  </font></td>
<td><font size=3> $amounts</font></td>
<td><font size=3> $type </font></td>
 ";
}

echo "</tr>";
echo "</tbody></table>";
mysqli_close($conn);
 
 echo"
<hr></hr>
";
echo"
</div>
</div>
 "; 
include('footer.php');  
?>
</div>
</main>
</body> 
</html>
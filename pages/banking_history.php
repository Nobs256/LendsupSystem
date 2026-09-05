<?php
include('header_user.php');
?>
<style type="text/css">
 

th, td {
text-align: left;
padding: 2px;
padding-top: 2px;
}

tr:nth-child(even) {
background-color: #D9FFD9;
}
form {
border-collapse: collapse;
}

 
</style>
<main class="column main" style="background-color:#EAEAEA;"> 
<?php include ('summary.php') ;
$cent_total=0;
$equity_total=0;
$select = mysqli_query($conn,"SELECT * FROM total_banking where user_id='$user_id' and boss_id='$boss_id' and bank_name='Centenary'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["total"]; 
$cent_total+=$amount;
}

$select = mysqli_query($conn,"SELECT * FROM total_banking where user_id='$user_id' and boss_id='$boss_id' and bank_name='Equity'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["total"]; 
$equity_total+=$amount;
}

?>
 
<div id="main_heading"> 
<table><tr><td><b>Banking History</b>   </td><td><font color="#EAEAEA">---------------</font></td>
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
<p> 
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

$dateObj   = DateTime::createFromFormat('!m', $month);
$monthName = $dateObj->format('F'); 

echo "<table align=center width=90%><tr><td>
<p align=center><b>BANKING HISTORY $monthName-$year</b><br>
</td><td>
";

echo"
<form method='post' action='print_banking_history.php' target='_blank'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='month' value='$month'>
<input type=hidden name='year' value='$year'>
 
<button type='submit' name=print_banking style='border: 1px solid #7C7C7C; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:80px'>
&nbsp;Print&nbsp;</button></form>

</td></tr></table><br>";

$cent_deposit=0;
$equity_deposit=0;
$cent_withdraw=0;
$equity_withdraw=0;

$select = mysqli_query($conn,"SELECT * FROM banking where  userde_id='$user_id' and MONTH(de_date)='$month' and YEAR(de_date)='$year'
and bossde_id='$boss_id' and transc='Deposit'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["de_amount"]; 
$cent_deposit+=$amount;
}

$select = mysqli_query($conn,"SELECT * FROM banking where userde_id='$user_id' and MONTH(de_date)='$month' and YEAR(de_date)='$year'
and bossde_id='$boss_id' and transc='Withdraw'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["de_amount"]; 
$cent_withdraw+=$amount;
}

$select = mysqli_query($conn,"SELECT * FROM banking where bank_name='Equity' and userde_id='$user_id' and MONTH(de_date)='$month' and YEAR(de_date)='$year' 
and bossde_id='$boss_id' and transc='Deposit'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["de_amount"]; 
$equity_deposit+=$amount;
}

$select = mysqli_query($conn,"SELECT * FROM banking where bank_name='Equity' and userde_id='$user_id' and MONTH(de_date)='$month' and YEAR(de_date)='$year'
and bossde_id='$boss_id' and transc='Withdraw'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["de_amount"]; 
$equity_withdraw+=$amount;
}

?>  
<table border="0" width="100%">
<tr>
<td><b>BANKING</b>
<?php echo " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 Deposited:&nbsp;&nbsp;<b>".number_format($cent_deposit)."</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
 Withdrawn:&nbsp;&nbsp;<b>".number_format($cent_withdraw)." </b>";
echo "<br><br>"; ?>
<table border="1" width="90%">

<tr style="height:0px">
<th>No.</th>
<th>Date</th>
<th>Trans.</th>
<th>Amount</th>
</tr>
<body>
<?php
$j=0;
$query = mysqli_query($conn,"SELECT * from banking where userde_id='$user_id' and MONTH(de_date)='$month' and YEAR(de_date)='$year'
and bossde_id='$boss_id' order by de_date");
while($selected = mysqli_fetch_array($query)){
$transc = $selected["transc"];
$de_date=$selected["de_date"];
$de_amount=$selected["de_amount"];			  
$j++;
echo"<tr style='height:0px'>
<td>&nbsp;$j.</td>
<td>&nbsp;&nbsp;".date("d-m-Y", strtotime($de_date))."</td>
<td>&nbsp;$transc</td>
<td>&nbsp;".number_format($de_amount)."</td>
</tr>";
}
echo "</body></table>";
?>
 
</td>
</tr>
</table>
</td>
</tr>
</table>
<?php
}
else{
echo "Select Month and Year";
}

?>
</div>
</div>
</div>
 
<?php include('footer.php'); ?>

</main>
<script type="text/javascript">
$(document).ready(function()
{
$("#client_info").modal("show");
$("#client_update").modal("show");
$("#tr_sms").modal("show");
 
});


</script>
</body> 
</html>
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
?>
<div id="main_heading"> 
<table><tr><td><b>Payables(Money From Another Branch)</b> </td><td>
	<font color="#EAEAEA">------------------------</font></td>
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

$total_amount=0;
$total_amounts=0;

$dateObj   = DateTime::createFromFormat('!m', $month);
$mon = $dateObj->format('F'); 

echo "<br><br>";
$j=0;
echo "<b>PAYABLES in $mon</b><br><br> ";
echo "<table width=90% border=1>
<thead>
<tr>
<th>No</th>
<th>Date</th>
<th>Branch</th>
<th>Amount</th>
 
</tr>
</thead>
<tbody>";
$search_query= mysqli_query($conn,"SELECT * FROM cr where usercr_id='$user_id' and bosscr_id='$boss_id' and MONTH(cr_date)='$month' order by cr_date");
 while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;

$date=$returned_result["cr_date"];
$amount  = number_format($returned_result["cr_amount"]); 
$branch= $returned_result["branch"];
$d=date("d-m-Y", strtotime($date));
 
echo "<tr>
<td><font size=3> $j  </font></td>
<td><font size=3> $d  </font></td>
<td><font size=3> $branch </font></td>
<td><font size=3> $amount</font></td>
 ";
}

echo "</tr>";
echo "</tbody></table>";
mysqli_close($conn);
}
else{
echo "Select Month";
 }
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
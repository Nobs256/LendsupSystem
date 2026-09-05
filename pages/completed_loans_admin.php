<?php
include('header.php');
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
<?php include ('summary_admin.php') ;
?>
<div id="main_heading"> 
<table border="0" width="100%"><tr> 
<td width="40%"><b>Completed Loans</b></td>
<td width="60%">
<form method="post"> 
<div class="select is-success">  
<select  name="branch"  style="width:180px; border: 1px solid #006F37; height:35px" required> 
<option>Branch</option>
<?php
$comb = mysqli_query($conn,"SELECT * from new_users where boss_id='$boss_id' and active=1 and category='User'");

while($select_comb = mysqli_fetch_array($comb)){
$branch=$select_comb['branch'];
 
echo "<option value= $branch> $branch </option>";
 
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
if(isset($_POST['monthly_report'])){
$month = $_POST['months'];
$branch = $_POST['branch'];
$year = $_POST['year'];

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from new_users where  boss_id='$boss_id' and category='User' and branch='$branch'"));
$user_id = $results["user_id"];
}
else{
$month=date('m');
$year=date('Y');
$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from new_users where  boss_id='$boss_id' and category='User'"));
$branch = $results["branch"];
$user_id = $results["user_id"];
}

$dateObj   = DateTime::createFromFormat('!m', $month);
$mon = $dateObj->format('F');

$total_amount=0; 
$total_charges=0; 
$total_ch=0;
$total_amounts=0;
 

echo "<b>Completed Loans in <i>$mon - $year</i>  from <i>$branch</i></b>";
echo "<br><br>";
$j=0;
$search_query= mysqli_query($conn,"SELECT client_id, ucase(firstname) as firstname, ucase(lastname) as lastname, phone, e_date, amount_given, pay_date
FROM clients, completed_loan  where users_id='$user_id' and bosses_id='$boss_id' and client_id=clientcpid and completed=1 and  MONTH(e_date)='$month' and YEAR(e_date)='$year'");
  
echo "<div class='table-responsive'>
<table border=1 style='width:80%;'>
<thead>
<tr>
<th>No</th>
<th>Names</th>
<th>Phone</th>
<th>Amount Given</th>
<th>Date Taken</th>
<th>Date Ended</th> 
</tr>
</thead>
<tbody>";

while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;

$client_id=$returned_result["client_id"];
$firstname = $returned_result["firstname"]. " ". $returned_result["lastname"];
$phone  = $returned_result["phone"];
$amount_given  =number_format($returned_result["amount_given"]);
$date = $returned_result["e_date"];
$date2 = $returned_result["pay_date"];
$d=date("d-m-Y", strtotime($date2));
$d2=date("d-m-Y", strtotime($date));
 
echo "<tr>
<td><font size=3> $j  </font></td>
<td><font size=3> $firstname  </font></td>
<td><font size=3> $phone</font></td>
<td><font size=3> $amount_given</font></td>
<td><font size=3> $d</font></td>
<td><font size=3> $d2</font></td>";
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
</div>
</div>
<div>"; 
include('footer.php');  
?>
</div>
</main>
</body> 
</html>
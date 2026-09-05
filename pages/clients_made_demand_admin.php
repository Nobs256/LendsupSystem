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
<td width="40%"><b>Clients Made Loan  Demand for Tomorow</b></td>
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
$branch = $_POST['branch'];

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from new_users where  boss_id='$boss_id' and category='User' and branch='$branch'"));
$user_id = $results["user_id"];
}
else{

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from new_users where  boss_id='$boss_id' and category='User'"));
$branch = $results["branch"];
$user_id = $results["user_id"];

}
$total_dem=0; $total_dems=0; 
 //==loan Demanded for tomorow
$select = mysqli_query($conn,"SELECT * FROM demand 
where userde_id='$user_id' and bossde_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$dem_amount= $selected["amount"]; 
$total_dem+=$dem_amount;
$total_dems=number_format($total_dem);
}
 
echo "<b>Total Amount Demanded is <u>$total_dems</u> From <i>$branch</i></b>";
echo "<br><br>";
$j=0;
$search_query= mysqli_query($conn,"SELECT client_id, ucase(firstname) as firstname, ucase(lastname) as lastname, phone, amount, demand_date
FROM clients, demand  where users_id='$user_id' and bosses_id='$boss_id' and client_id=clientde_id");
  
echo "<table width=80% border=1>
<thead>
<tr>
<th>No</th>
<th>Date</th> 
<th>Names</th>
<th>Phone</th>
<th>Amount Demanded</th>
</tr>
</thead>
<tbody>";

while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;

$client_id=$returned_result["client_id"];
$firstname = $returned_result["firstname"]. " ". $returned_result["lastname"];
$phone  = $returned_result["phone"];
$date = $returned_result["demand_date"];
$d=date("d-m-Y", strtotime($date));
$amount  = number_format($returned_result["amount"]);

echo "<tr>
<td><font size=3> $j  </font></td>
<td><font size=3> $d</font></td>
<td><font size=3> $firstname  </font></td>
<td><font size=3> $phone</font></td>
<td><font size=3> $amount</font></td>";
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
 
<div>"; 
include('footer.php');  
?>
</div>
</main>
</body> 
</html>
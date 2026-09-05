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
<p align="center"> <?php include ('summary.php') ?>
 
<div id="main_heading"> <b>Clients that Made Demand for Tomorow</b>
</div>     
 
<div id="main_container">
<div id="main_body">   
    
<br>  
<?php
$j=0;

$search_query= mysqli_query($conn,"SELECT client_id, ucase(firstname) as firstname, ucase(lastname) as lastname, phone, amount, demand_date
FROM clients, demand  where users_id='$user_id' and bosses_id='$boss_id' and client_id=clientde_id order by demand_date desc ");
  
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
"; 
include('footer.php');  
?>
</div>
</main>
</body> 
</html>
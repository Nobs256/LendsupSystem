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
<p align="center"> <?php include ('summary_admin.php') ?>
 
<div id="main_heading"> <b>Clients that TooK Their IDs</b>
</div>     
 
<div id="main_container">
<div id="main_body">   
    
<br>  
<?php
$j=0;

$search_query= mysqli_query($conn,"SELECT client_id, ucase(firstname) as firstname, ucase(lastname) as lastname, phone, userde_id, take_date FROM clients, idTake  where bosses_id='$boss_id' and client_id=clientde_id and take=1");
  
echo "<table width=80% border=1>
<thead>
<tr>
<th>No</th>
<th>Names</th>
<th>Phone</th>
<th>Debt</th>
<th>Date Taken</th> 
</tr>
</thead>
<tbody>";

while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;

$client_id=$returned_result["client_id"];
$firstname = $returned_result["firstname"]. " ". $returned_result["lastname"];
$user_id = $returned_result["userde_id"];
$phone  = $returned_result["phone"];
$date = $returned_result["take_date"];
$d=date("d-m-Y", strtotime($date));

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients_with_loan
where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'"));
$debt = number_format($results["debt"]);
 
echo "<tr>
<td><font size=3> $j  </font></td>
<td><font size=3> $firstname  </font></td>
<td><font size=3> $phone</font></td>
<td><font size=3> $debt</font></td>
<td><font size=3> $d</font></td>";
}

echo "</tr>";
echo "</tbody></table>";
mysqli_close($conn);
 
 echo"
<hr></hr>
";
 ?>
 </div>
</div>
</div>
<div>
<?php include('footer.php');  
?>
</div>
</main>
</body> 
</html>
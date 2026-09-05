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
<?php include ('summary.php') ?>
 
<div id="main_heading"> <b>Clients with Out Loans</b>
 
</div>     

<br> 
<div id="main_container"> 
<div id="main_body"> 
<?php
//total No of clients with no loans
$j=0;
$search_query= mysqli_query($conn,"SELECT client_id, ucase(firstname) as firstname, ucase(lastname) as lastname, phone
FROM clients where users_id='$user_id' and bosses_id='$boss_id'  order by firstname");
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id=$returned_result["client_id"]; 

$client_with_no_loan ="SELECT * from clients_with_loan where userseid='$user_id' 
and bosseseid='$boss_id' and clientsid='$client_id'";
$run = mysqli_query($conn, $client_with_no_loan) or die("Could DB");
$client_with_no_loan = mysqli_num_rows($run);
if ($client_with_no_loan==0){
$j++;
}
}

echo " <font size=4> Number of Clients With out Loan:&nbsp;&nbsp;&nbsp;<b>$j</b></font><br><br>";

$j=0;
$search_query= mysqli_query($conn,"SELECT client_id, users_id, bosses_id, 
ucase(firstname) as firstname,ucase(lastname) as lastname,  phone, business, b_location 
FROM clients where users_id='$user_id' and bosses_id='$boss_id' order by firstname");
?>  
<div class="table-responsive">
<table  style="width:60%" align="center" border="1">            
 
<thead>
<tr><th>No</th>
<th>Name</th>
<th>Phone</th>
<th>Location</th>

</tr></thead><tbody>

<?php
$j=0;
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id=$returned_result["client_id"];
$name = $returned_result["firstname"]." ".$returned_result["lastname"]; 
$phone = $returned_result["phone"];                
$business = $returned_result["business"];
$b_location = $returned_result["b_location"];

$client_with_no_loan ="SELECT * from clients_with_loan where userseid='$user_id' 
and bosseseid='$boss_id' and clientsid='$client_id'";
$run = mysqli_query($conn, $client_with_no_loan) or die("Could DB");
$client_with_no_loan = mysqli_num_rows($run);
if ($client_with_no_loan==0){
$j++;

echo "<tr>
<td> $j </td>
<td> $name </td>
<td> $phone </td>                  
<td> $b_location</td>
";
}
else{
$d;
$d=0;
}

echo "</tr>";
}
echo "</tbody></table>";
?> 

 
</div>
</div>
</div>
<div> 
 
 
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>
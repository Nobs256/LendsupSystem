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
 
<div id="main_heading"> 
<table border="0">
<tr><td>
<b>Clients Without Loans</b>
</td>
<td>
<font color="#E4E4E4">----------------------------------------</font>
</td><td>
<form  method="post"> Select Branch:
<div class="select is-success">  
<select  name="user_id"  style="width:180px; border: 1px solid #006F37; height:35px" required> 
<option>Branch</option>
<?php
$comb = mysqli_query($conn,"SELECT * from new_users where boss_id='$boss_id' and active=1 and category='User'");
while($select_comb = mysqli_fetch_array($comb)){
$branch=$select_comb['branch']; 
$user_id=$select_comb['user_id']; 
echo "<option value= $user_id> $branch </option>"; 
}
echo"</select></div>";
?>
<button type="submit" name="with_no_loan" class="button is-primary" 
style="background-color:#006F37; color:white; font-size:12px; border:1px solid #006F37; height:35px; margin-left:4px ">
&nbsp;OK&nbsp;</button></form> 
 
</td></tr></table></div>
     
 
<div id="main_container">
<div id="main_body">   
    
<br>  
<?php
if(isset($_POST['with_no_loan'])){       
$user_id= $_POST['user_id'];
 
$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from new_users where boss_id='$boss_id' and active=1 and category='User' and user_id='$user_id'"));
$branch=strtoupper($results["branch"]);

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

echo "<p align=center> <font size=4> $branch Branch<br> 
 Number of Clients With out Loan:&nbsp;&nbsp;&nbsp;<b>$j</b></font><br><br>";

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
}
else{
echo "Select Branch";
}
?> 
</div>
</div>
</div>
<?php include('footer.php'); ?>

</div>
</main>
</script>
</body> 
</html>
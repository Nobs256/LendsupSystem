<?php
$s="";
include('header_user.php');
if(isset($_GET['client_id'])){
$client_id=$_GET['client_id'];
$date=date('Y-m-d');
$take=1;

$query ="UPDATE idTake set return_date='$date', take=0
 where clientde_id='$client_id' and userde_id='$user_id' and bossde_id='$boss_id'";
$execute = mysqli_query($conn, $query);

$query ="UPDATE idTake_history set return_date='$date'
 where clientde_id='$client_id' and userde_id='$user_id' and bossde_id='$boss_id'";
$execute = mysqli_query($conn, $query);


$s="<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:5px; width: 400px'>Data is Successfully Saved 
<a href='user_homepage.php' style='color:white; margin-left:100px;''>X</a>
</div>"; 

}

?>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> <?php include ('summary.php') ?>
 
<div id="main_heading"> <b>Search a Client to Return ID</b>
</div>     
 
<div id="main_container">
<div id="main_body">   
<br>
  <?php echo $s;?>        
  <br><br>                     
<table border="0" width="70%">
<form  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<table style="width:730px;" border="0">
<tr><td width="150px">
Enter First Name: 
</td><td width="400px">
<input type="text" name="firstname" class="input is-success" style="width:320px; border: 1px solid #006F37" required >
<button type="submit" name="submit" class="button is-primary" style="background-color:#006F37">
&nbsp;&nbsp;&nbsp;Search&nbsp;&nbsp;&nbsp;</button><div>
</form></td> </tr></table>    
<br>  
<?php
if(isset($_POST['firstname'])){
$q = $_POST['firstname'];
$j=0;
$select_client = mysqli_query($conn,"SELECT * FROM clients where firstname='$q'");
$search_query= mysqli_query($conn,"SELECT client_id, ucase(firstname) as firstname, ucase(lastname) as lastname, phone, nid, 
b_location FROM clients  where firstname Like '%$q%' and users_id='$user_id' and bosses_id='$boss_id'");
  
echo "<table  class='table table-responsive table-hover'>
<thead>
<tr>
<th>No</th>
<th>Names</th>
<th>Place of Work</th>
<th>Phone</th>
<th>National ID</th>
<th>Debt</th>
<th>Action</th>
</tr>
</thead>
<tbody>";

while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;

$client_id=$returned_result["client_id"];
$firstname = $returned_result["firstname"]. " ". $returned_result["lastname"];
$phone  = $returned_result["phone"];
$nid  = $returned_result["nid"];
$b_location = $returned_result["b_location"];

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients_with_loan
where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'"));
$debt = number_format($results["debt"]);

$take = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM idTake
where clientde_id='$client_id' and userde_id='$user_id' and bossde_id='$boss_id' and take=1")); 
if($take==0){
 $allowed="<a href='#'><font color=red><b>Already returned</b></font></a>";
 }
 else{
 $allowed="<a href='search_client_return_id.php?client_id=$client_id'><font size=4 color=green><b>RETURN ID</b></font></a>";
 $take=0;
 }
echo "<tr>
<td><font size=3> $j  </font></td>
<td><font size=3> $firstname  </font></td>
<td><font size=4> $b_location  </font></td>
<td><font size=3> $phone</font></td>
<td><font size=3> $nid</font></td>
<td><font size=3> $debt</font></td>
<td>$allowed</td>";
}

echo "</tr>";
}
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
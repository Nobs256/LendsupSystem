<?php
$s="";
include('header_user.php');
?>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> <?php include ('summary.php') ?>
 
<div id="main_heading"> <b>Search a Client that Sent Money on Mobile Money</b>
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
$date=date('Y-m-d');

$select_client = mysqli_query($conn,"SELECT * FROM clients where firstname='$q'");
$search_query= mysqli_query($conn,"SELECT client_id, ucase(firstname) as firstname, ucase(lastname) as lastname, phone, nid, 
b_location FROM clients  where firstname Like '%$q%' and users_id='$user_id' and bosses_id='$boss_id'");
  
echo "<table  class='table table-responsive table-hover'>
<thead>
<tr>
<th>No</th>
<th>Names</th>
<th>Phone</th>
<th>National ID</th>
<th>Balance</th>
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

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from mobile
where clientmo_id='$client_id' and usermo_id='$user_id' and bossmo_id='$boss_id' and mom_date='$date'"));
$mom_use = number_format($results["amount_mo"]);

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients_with_loan
where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'"));
$debt = number_format($results["debt"]);

 if($debt==0){
 $allowed="<a href='#'><font color=red>Cleared the Loan</font></a>";
 }
 else if($mom_use>0){
 $allowed="<a href='#'><font color=red>Already Paid, <a href='pay_loan_mom.php?client_id=$client_id'><font size=3 color=green>Pay More</font></a> </font></a>";
 }
 else{
 $allowed="<a href='pay_loan_mom.php?client_id=$client_id'><font size=3 color=green>Pay With MOM</font></a>";
 
 }

echo "<tr>
<td><font size=3> $j  </font></td>
<td><font size=3> $firstname  </font></td>
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
</div>
</div>
</div>
 "; 
include('footer.php');  
?>
</div>
</main>
</body> 
</html>
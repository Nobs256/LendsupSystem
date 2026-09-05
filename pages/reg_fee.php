<?php
$sa="";
if(isset($_POST['reload']))
{
$sec=$_POST['re'];
 header("refresh: $sec");
}   
 
if(isset($_GET['success'])){
$sa = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 500px'>
<font color=white>An Registration Fee is successfully Entered!!</font>
<a href='reg_fee.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</div>";
}

if(isset($_GET['deleted'])){
$sa = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 520px'>
<font color=white>An Registration Fee  is Successfully Deleted!!</font>
<a href='reg_fee.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</div>";
}

if(isset($_GET['already_fee'])){
$sa = "<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>
<font color=white>The Fees  is already Entered!!</font>
<a href='reg_fee.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</div>";
}
include('header_user.php');

?>
<main class="column main" style="background-color:#EAEAEA;">
<?php include ('summary.php') ?>

<div id="main_heading" style="width: 500px; margin-left:0px"> <b>Enter Registaration Fee</b>
<font color="#EAEAEA">-------------------</font>
<a href="#"><font color="green">View Registaration Fee</font></a> 

<?php echo $sa;?>
</div>     
 <br>
<div id="main_body">   
<br>
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
$debt = $results["debt"];

 if($debt>0){
 $allowed="<a href='#'><font color=red>Not allowed</font></a>";
 }
 else{
 $allowed="<a href='reg_fee.php?client_id=$client_id'>Give Loan</a>";
 $debt=0;
 }
echo "<tr>
<td><font size=2> $j  </font></td>
<td><font size=2> $firstname  </font></td>
<td><font size=4> $b_location  </font></td>
<td><font size=2> $phone</font></td>
<td><font size=2> $nid</font></td>
<td><font size=2> $debt</font></td>
<td>$allowed</td>";
}

echo "</tr>";
}
echo "</tbody></table>";
 
if(isset($_GET['client_id'])){
$client_id = $_GET['client_id'];
?>
<br>  
<hr></hr>                             
<form method="post" action="user_connector.php">
<input type="hidden" name="boss_id" value="<?php echo $boss_id; ?>">
<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
<input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
<table style="width:500px;" border="0">
<tr>
<td width="90px"> 
Date: 
</td>
<td width="200px">
<input type="date" name="reg_date" class="input is-success" style="width:150px; border: 1px solid #006F37" 
required><br><br>
</td>
<td width="90px"> 
Amount: 
</td>
<td width="180px">
<input type="text"   name="reg_amount" class="input is-success" style="width:150px; border: 1px solid #006F37"
required><br><br>       
</td>
</tr>   
</table>          
 
<button type="submit" name="reg_fee" class="button is-primary"
 style="border: 1px solid #006F37; border-radius:4px; color:white;
  background-color:#006F37; margin-left:400px">
&nbsp;&nbsp;&nbsp;Add Fee &nbsp;&nbsp;&nbsp;</button>

</form>   
<?php } ?>
 
</div>
</div>


<div> 
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>
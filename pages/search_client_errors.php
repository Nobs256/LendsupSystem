<?php
include('header.php');

?>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> <?php include ('summary_admin.php') ?>
<div id="main_heading"> <b>

<table border="0" width="100%"><tr> 
<td width="40%"><b>Data Entering Errors</b> </td><td>Other Errors</td>
<td width="50%"> 
<form method="post" action="error_others_admin.php"> 
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
<select  name="item"  style="width:180px; border: 1px solid #006F37; height:35px" required> 
 
<option value="Expenses">Expenses Errors</option> 
<option value="Banking">Banking Errors</option>
<option value="Unknown">Unknown Errors</option>
</select> 
</div>
 
 
 
<button type="submit" name="errors" class="button is-primary" 
style="border: 1px solid #006F37; border-radius:4px; color:white; background-color:#006F37">
&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;</button>
</label>   
</form>     
</td></tr></table>


</div>

<div id="main_container">
<div id="main_body">   
<br>
<p><font size="4">Search a Client with Errors</font></p>
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

$search_query= mysqli_query($conn,"SELECT * FROM clients  where firstname Like '%$q%' and bosses_id='$boss_id'");
  
echo "<table  class='table table-responsive table-hover' width='1080'>
<thead>
<tr>
<th>No</th>
<th>Names</th>
<th>Phone</th>
<th>Branch</th>
<th></th>
<th>Actions</th>
<th></th>
<th></th>
</tr>
</thead>
<tbody>";

while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;
$client_id=$returned_result["client_id"];
$firstname = strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);
$phone  = $returned_result["phone"];
$user_id  = $returned_result["users_id"];

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from new_users where user_id='$user_id'"));
$branch = $results['branch'];
 
echo "<tr>
<td> $j </td>
<td> $firstname  </td>
<td> $phone</td>
<td> $branch</td>
<td> <a href='error_give_loan.php?client_id=$client_id'><font color=green><b>Give Loan</a></b></font></td>
<td> <a href='error_pay_loan.php?client_id=$client_id'><font color=green><b>Pay Loan</a></font></td>
<td><a href='error_mom.php?client_id=$client_id'><font color=green><b>Pay with MOM</a></td>";
}

echo "</tr>";
}
echo "</tbody></table>";
mysqli_close($conn);
 
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
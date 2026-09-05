<?php
include('header_user.php');

?>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> <?php include ('summary.php') ?>
<div id="main_heading"> <b>
SAVINGS <br>Search a Client for Savings</b>
</div>

<div id="main_container">
<div id="main_body">   
<br>
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
$total_saving=0;
$total_with=0;
$select_client = mysqli_query($conn,"SELECT * FROM clients where firstname='$q'");
$search_query= mysqli_query($conn,"SELECT * FROM clients where firstname Like '%$q%' and users_id='$user_id'
 and bosses_id='$boss_id' ");
  
echo "<table  class='table table-responsive table-hover' width='800'>
<thead>
<tr>
<th>Names</th>
<th>Phone</th>
<th>Business Location</th>
<th>Action</th>
</tr>
</thead>
<tbody>";

while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;
$client_id=$returned_result["client_id"];
$firstname = strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);
$phone  = $returned_result["phone"];
$loc  = $returned_result["b_location"];

echo "<tr>

<td><font size=2> $firstname  </font></td>
<td><font size=2> $phone</font></td>
<td><font size=2> $loc</font></td>
<td><a href='make_save.php?client_id=$client_id'>Make Save</a>
</td>";
}

echo "</tr>";
}
echo "</tbody></table>";
mysqli_close($conn);
 
?>

</div>
</div>
</div>
 
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>
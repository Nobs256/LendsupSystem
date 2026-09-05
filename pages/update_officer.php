<?php   
include("conn.php");
$s=""; 
include('header.php');                       
if(isset($_REQUEST['officer_id']))
{ 

$officer_id = $_REQUEST['officer_id'];  
$result = mysqli_fetch_assoc(mysqli_query($conn,"select officer_id, boss_id, ucase(firstname) as firstname, ucase(lastname) as lastname,
phone, nid, branch, location from officers where officer_id ='$officer_id'"));

$ufirstname = $result['firstname'];
$ulastname = $result['lastname'];
$uphone = $result['phone']; 
$unid = $result['nid'];
$branch = $result['branch'];
$location = $result['location'];

if(isset($_GET['success'])){
$s = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>
<font color=white>An Officer is Successfully Updated!!</font>
<a href='update_officer.php?officer_id=$officer_id' style='color:white; margin-left:100px;''>X</a>
</div>";
}
}

?>
<main class="column main" style="background-color:#EAEAEA;"> 
<?php include ('summary_admin.php') ?>
<div id="main_heading"> <b>Update officers</b> 
<font color="#EAEAEA">-----------------------------------------------------------------------------</font>
 <a href="view_officer.php"><font color="green">Back</font></a>
</div> 
<div id="main_container">
<div id="main_body"> 
 
<div>
<?php echo $s; ?>
<br>
<form method="post" action="admin_connector.php">
<input type="hidden"  value="<?php echo $officer_id?>" name="officer_id">
<input type="hidden" value="<?php echo $boss_id?>" name="boss_id">

<table style="width:500px;" border="0">
<tr>
<td width="100px"> 
<label class="label">First Name: </label>
</td>
<td width="240px">
<input type="text" value="<?php echo $ufirstname; ?>" name="fname" class="input is-success" style="width:320px; border: 1px solid #006F37" 
required><br><br>
</td>
</tr><!--end of tr -->
<tr>    
<td width="100px"> 
<label class="label">Last Name: </label>
</td>
<td width="180px">
<input type="text" value="<?php echo $ulastname; ?>" name="lname" class="input is-success" style="width:320px; border: 1px solid #006F37"
required><br><br>       
</td>
</tr><!--end of tr -->
<tr>
<td> 
<label class="label">Phone No: </label>
</td>
<td>
<input type="tel" value="<?php echo $uphone; ?>" maxlength = "10" name="phone" class="input is-success" style="width:320px; border: 1px solid #006F37" 
required><br><br>          
</td> 
</tr><!--end of tr -->
<tr>
<td> 
<label class="label">Branch: </label>
</td>
<td>
<div class="select is-success">  
<select  name="branch"  style="width:320px; border: 1px solid #006F37" required> 
<option><?php echo $branch; ?></option>
<?php
$comb = mysqli_query($conn,"SELECT * from new_users where boss_id='$boss_id' and active=1 and category='User'");
while($select_comb = mysqli_fetch_array($comb)){
$branch=$select_comb['branch']; 
echo "<option> $branch </option>"; 
}
echo" 
</select></div>";
?>     
  <br><br>          
</td> 
</tr><!--end of tr -->

<tr> 
<td>      
<label class="label">Location:</label>
</td>
<td>
<div class="select is-success">  
<select  name="b_location"  style="width:320px; border: 1px solid #006F37" required> 
<option><?php echo $location; ?></option>
<?php
$comb = mysqli_query($conn,"SELECT * from location where bossloc_id='$boss_id' ");
while($select_comb = mysqli_fetch_array($comb)){
$location=$select_comb['location']; 
echo "<option> $location </option>"; 
}
echo" 
</select></div>";
?> <br><br>
</td>        
</tr>   
</table>          
<label class="label" style="margin-left: 260px">
<button type="submit" name="update_officers" class="button is-primary" style="border: 1px solid #006F37; border-radius:4px; color:white; background-color:#006F37">
&nbsp;&nbsp;&nbsp;Update officer&nbsp;&nbsp;&nbsp;</button>
</label>
</form> 
<!--END of FOrm-->   
<br><br>
 
</div>
</div>
</div>
<div> 
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>


<?php
$sa="";
if(isset($_POST['reload']))
{
$sec=$_POST['re'];
 header("refresh: $sec");
} 
include('header.php');
//======================================CREATE FIELD OFFICERS
if(isset($_POST['add_officers'])){
$boss_id = $_POST['boss_id'];
$f = $_POST['fname'];
$l = $_POST['lname'];
$phone = $_POST['phone'];
$branch = $_POST['branch'];
$nid = $_POST['nid'];
$location = $_POST['location']; 
$active=1;

$same_user ="select * from officers where firstname='$f' and lastname='$l' and phone='$phone' and nid='$nid' and location='$location' ";
$run = mysqli_query($conn, $same_user) or die("Could DB");
$same_user = mysqli_num_rows($run);
if ($same_user==1){
$sa = "<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>
<font color=white>This field officer is already added!!</font>
<a href='add_officer.php?reload=1' style='color:white; margin-left:60px;''>X</a>
</div>";
}
else{
mysqli_query($conn,"INSERT INTO officers(officer_id, boss_id, firstname, lastname, 	phone, nid, branch, location, active) 
VALUES (NULL, '$boss_id', '$f', '$l', '$phone', '$nid', '$branch', '$location', '$active')");
$s = 1;

$sa = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 500px'>
<font color=white>A Field Officer is successfully Added!!</font>
<a href='add_officer.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</div>";               

} }   
 
?>
<main class="column main" style="background-color:#EAEAEA;">
<?php include ('summary_admin.php') ?>   
<div id="main_heading"> <b>Create New Field Officer</b>
<font color="#EAEAEA">----------------------</font>
 <a href="view_officer.php"><font color="green">View Registered Field Officers</font></a>
 <br>
<?php echo $sa;?>

</div> 
<br>    
<div id="main_container">  
<div id="main_body">   
<br>
<br>                               
<form method="post" action="add_officer.php">
<input type="hidden" name="boss_id" value="<?php echo $boss_id; ?>">
<table style="width:900px;" border="0">
<tr>
<td width="100px"> 
First Name: 
</td>
<td width="240px">
<input type="text"   name="fname" class="input is-success" style="width:270px; border: 1px solid #006F37" 
required><br><br>
</td>

<td width="100px"> 
Last Name: 
</td>
<td width="180px">
<input type="text"   name="lname" class="input is-success" style="width:270px; border: 1px solid #006F37"
required><br><br>       
</td>
</tr><!--end of tr -->
<tr>
<td> 
Phone No: 
</td>
<td>
<input type="tel" maxlength = "10" name="phone" class="input is-success" style="width:270px; border: 1px solid #006F37" 
required><br><br>          
</td> 
<td>      
National ID:  
</td>
<td>
<input type="text"   name="nid" class="input is-success" style="width:270px; border: 1px solid #006F37"
required><br><br>         
</td>
</tr>

<tr>
<td>      
Branch:  
</td>
<td>
<div class="select is-success">  
<select  name="branch"  style="width:270px; border: 1px solid #006F37; height:35px" required> 
<option></option>
<?php
$comb = mysqli_query($conn,"SELECT * from new_users where boss_id='$boss_id' and active=1 and category='User'");
while($select_comb = mysqli_fetch_array($comb)){
$branch=$select_comb['branch']; 
echo "<option> $branch </option>"; 
}
echo" 
</select></div>";
?>     
</td>       
</td>         
<td> 
Location: 
</td>
<td>
<div class="select is-success">  
<select  name="location"  style="width:270px; border: 1px solid #006F37; height:35px" required> 
<option></option>
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
</tr><!--end of tr -->  
</table>          
 
<button type="submit" name="add_officers" class="button is-primary"
 style="border: 1px solid #006F37; border-radius:4px; color:white;
  background-color:#006F37; margin-left:400px">
&nbsp;&nbsp;&nbsp;Add A Field Officer&nbsp;&nbsp;&nbsp;</button>

</form>                

</tbody>
</table>  
</div>
</div>


<div> 
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>
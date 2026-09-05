<?php   
include("conn.php");
$s="";

if(isset($_REQUEST['user_updated_id']))
{ 
$uuser_id = $_REQUEST['user_updated_id'];

$s = "<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:0px; padding:7px; width: 500px'>
<font color=white>The Username is already used by another User !!</font>
<a  style='color:white; margin-left:220px;' href='update_users.php?user_id=$uuser_id'>X</a>
</div>";

$result = mysqli_fetch_assoc(mysqli_query($conn,"select user_id, boss_id, ucase(firstname) as firstname, ucase(lastname) as lastname,
sex, marital, phone, nid, branch, place_o, place_r, dob, username, category, password, users_image from new_users where user_id ='$uuser_id'"));
$uuser_id = $result["user_id"];
$ufirstname = $result['firstname'];
$ulastname = $result['lastname'];
$username = $result['username']; 
$uphone = $result['phone']; 
$unid = $result['nid'];
$branch = $result['branch'];
$sex = $result['sex'];
$marital = $result['marital'];
$place_r = $result['place_r'];
$place_o = $result['place_o'];
$dob = $result['dob'];
$cat = $result['category'];
$password = $result['password'];
$uuser_image = $result['users_image'];

//checking if user profile image is uploaded if not then use default image
if($uuser_image == ""){
$uuser_image = "assets/images/users_images/user_sample.png";
}else{
$uuser_image = "assets/images/users_images/".$uuser_image;
}
}

                        
if(isset($_REQUEST['user_id']))
{ 

$uuser_id = $_REQUEST['user_id'];  
$result = mysqli_fetch_assoc(mysqli_query($conn,"select user_id, boss_id, ucase(firstname) as firstname, ucase(lastname) as lastname,
sex, marital, phone, nid, branch, place_o, place_r, dob, username, category, password, 
users_image from new_users where user_id ='$uuser_id'"));
$uuser_id = $result["user_id"];
$ufirstname = $result['firstname'];
$ulastname = $result['lastname'];
$username = $result['username']; 
$uphone = $result['phone']; 
$unid = $result['nid'];
$branch = $result['branch'];
$sex = $result['sex'];
$marital = $result['marital'];
$place_r = $result['place_r'];
$place_o = $result['place_o'];
$dob = $result['dob'];
$cat = $result['category'];
$password = $result['password'];
$uuser_image = $result['users_image'];

//checking if user profile image is uploaded if not then use default image
if($uuser_image == ""){
$uuser_image = "assets/images/users_images/user_sample.png";
}else{
$uuser_image = "assets/images/users_images/".$uuser_image;
}
}
include('header.php'); 
?>
<main class="column main" style="background-color:#EAEAEA;"> 
<?php include ('summary_admin.php') ?>
<div id="main_heading"> <b>Update Users</b> 
<font color="#EAEAEA">-----------------------------------------------------------------------------</font>
 <a href="view_users.php"><font color="green">Back</font></a>
</div> 
<div id="main_container"> 
<div id="main_body"> 
<div class="field is-horizontal">
<div style="width:800px">
<?php echo $s; ?>

<form method="post" action="admin_connector.php">
<input type="hidden"  value="<?php echo $uuser_id?>" name="user_id">
<input type="hidden" value="<?php echo $username?>" name="username1">

<table style="width:500px;" border="0">
<tr>
<td width="100px"> 
<label class="label">First Name: </label>
</td>
<td width="240px">
<input type="text" value="<?php echo $ufirstname; ?>" name="fname" class="input is-success" style="width:320px; border: 1px solid #EAEAEA" 
required>
</td>
</tr><!--end of tr -->
<tr>    
<td width="100px"> 
<label class="label">Last Name: </label>
</td>
<td width="180px">
<input type="text" value="<?php echo $ulastname; ?>" name="lname" class="input is-success" style="width:320px; border: 1px solid #EAEAEA"
required>       
</td>
</tr><!--end of tr -->
<tr>
<td> 
<label class="label">Phone No: </label>
</td>
<td>
<input type="tel" value="<?php echo $uphone; ?>" maxlength = "10" name="phone" class="input is-success" style="width:320px; border: 1px solid #EAEAEA" 
required>        
</td> 
</tr><!--end of tr -->
<tr>
<td> 
<label class="label">Branch: </label>
</td>
<td>
<input type="text" value="<?php echo $branch; ?>" name="branch" class="input is-success" style="width:320px; border: 1px solid #EAEAEA" 
required>        
</td> 
</tr><!--end of tr -->
<tr>
<td> 
<label class="label">Place of Resident: </label>
</td>
<td>
<input type="text" value="<?php echo $place_r; ?>" name="place_r" class="input is-success" style="width:320px; border: 1px solid #EAEAEA" 
required>        
</td> 
</tr><!--end of tr -->
<tr>  
<td>      
<label class="label">Username: </label> 
</td>
<td>
<input type="text" value="<?php echo $username; ?>" name="username2" class="input is-success" style="width:320px; border: 1px solid #EAEAEA"
required>       
</td>        
</tr><!--end of tr -->
 
<tr> 
<td>      
<label class="label">Password:</label>
</td>
<td>
<input type="text" value="<?php echo $password ?>" name="password" class="input is-success" style="width:320px; border: 1px solid #EAEAEA"
required> 
</td>        
</tr>   
</table>          
<label class="label" style="margin-left: 260px">
<button type="submit" name="update_users" class="button is-primary" style="border: 1px solid #006F37; border-radius:4px; color:white; background-color:#006F37">
&nbsp;&nbsp;&nbsp;Update User&nbsp;&nbsp;&nbsp;</button>
</label>
</form> 
<!--END of FOrm-->   
</div>              
<div style="border: 1px solid #E5E7E9; width: 200px; height: 300px; padding-left:30px">
<img src="<?php echo $uuser_image;?>" alt="<?php echo $ufirstname." ".$ulastname;?>" 
style="width:150px;height:120px; border-radius:5px; margin-top:10px;" > 
<form action="admin_connector.php" method="post" enctype="multipart/form-data">
<style>#upload{visibility: hidden;}</style>
<input type="hidden" value="<?php  echo $uuser_id;?>" name="user_id">
<input type="file" name="image" id="upload">
<input type="button" value="Select the Image" class="button is-default"
onclick="document.getElementById('upload').click()"> <br><br>
<input type="submit" name="upload_user" value="&nbsp;&nbsp;Upload Image &nbsp;&nbsp;" class="button is-primary" 
style="border: 1px solid #006F37; border-radius:4px; color:white; background-color:#006F37">   
<br><br>   
</form>
</div>
</div>
</div>
</div>

<div> 
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>

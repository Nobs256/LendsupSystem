<?php   
include("../conn.php");
$s="";


if(isset($_REQUEST['user_updated_id']))
{ 
$user_id = $_REQUEST['user_updated_id'];

$s = "<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:0px; padding:7px; width: 500px'>
<font color=white>The email is already used by another user !!</font>
<a  style='color:white; margin-left:220px;' href='update_school_users.php?user_id=$user_id'>X</a>
</div>";

$result = mysqli_fetch_assoc(mysqli_query($conn,"select user_id, ucase(firstname) as firstname, ucase(lastname) as lastname,
username, phone, category, password, users_image from new_users where user_id ='$user_id'"));
$sch_user_id = $result["user_id"];
$sch_firstname = $result['firstname'];
$sch_lastname = $result['lastname'];
$sch_email = $result['username']; 
$sch_phone = $result['phone'];  
$sch_cat = $result['category'];
$sch_password = $result['password'];
$sch_user_image = $result['users_image'];

//checking if user profile image is uploaded if not then use default image
if($sch_user_image == ""){
$sch_user_image = "../assets/images/users_images/user_sample.png";
}else{
$sch_user_image = "../assets/images/users_images/".$sch_user_image;
}
}

                        
if(isset($_REQUEST['user_id']))
{   
$user_id = $_REQUEST['user_id'];
$result = mysqli_fetch_assoc(mysqli_query($conn,"select user_id, ucase(firstname) as firstname, ucase(lastname) as lastname,
username, phone, category, password, users_image from new_users where user_id ='$user_id'"));
$sch_user_id = $result["user_id"];
$sch_firstname = $result['firstname'];
$sch_lastname = $result['lastname'];
$sch_email = $result['username']; 
$sch_phone = $result['phone'];  
$sch_cat = $result['category'];
$sch_password = $result['password'];
$sch_user_image = $result['users_image'];

//checking if user profile image is uploaded if not then use default image
if($sch_user_image == ""){
$sch_user_image = "../assets/images/users_images/user_sample.png";
}else{
$sch_user_image = "../assets/images/users_images/".$sch_user_image;
}
}
include('header.php'); 
?>
<main class="column main">   
<div id="main_heading" style="width: 260px; margin-left:860px"> <b>UPDATE SCHOOL USER</b> </div>
<div id="main_body"> 
<div class="field is-horizontal">
<div style="width:800px">
<?php echo $s; ?>
<br>
<form method="post" action="admin_connector.php">
<input type="hidden"  value="<?php echo $sch_user_id?>" name="user_id">
<input type="hidden" value="<?php echo $sch_email?>" name="user_email">

<table style="width:500px;" border="0">
<tr>
<td width="100px"> 
<label class="label">First Name: </label>
</td>
<td width="240px">
<input type="text" value="<?php echo $sch_firstname; ?>" name="fname" class="input is-success" style="width:320px; border: 1px solid #5E4EA0" 
required><br><br>
</td>
</tr><!--end of tr -->
<tr>    
<td width="100px"> 
<label class="label">Last Name: </label>
</td>
<td width="180px">
<input type="text" value="<?php echo $sch_lastname; ?>" name="lname" class="input is-success" style="width:320px; border: 1px solid #5E4EA0"
required><br><br>       
</td>
</tr><!--end of tr -->
<tr>
<td> 
<label class="label">Phone No: </label>
</td>
<td>
<input type="tel" value="<?php echo $sch_phone; ?>" maxlength = "10" name="phone" class="input is-success" style="width:320px; border: 1px solid #5E4EA0" 
required><br><br>          
</td> 
</tr><!--end of tr -->
<tr>  
<td>      
<label class="label">E-Mail: </label> 
</td>
<td>
<input type="email" value="<?php echo $sch_email; ?>" name="email" class="input is-success" style="width:320px; border: 1px solid #5E4EA0"
required><br><br>         
</td>        
</tr><!--end of tr -->
<tr>       
<td>        
<label class="label">Category: </label>
</td><td>
<div class="select is-success">
<select  name="cat"  style="width:320px; border: 1px solid #5E4EA0" required>
  <option><?php echo $sch_cat; ?></option>
  <option>Admin</option>
  <option>User</option>            
 </select> 
</div><br><br>
</td> 
</tr><!--end of tr -->
<tr> 
<td>      
<label class="label">Password:</label>
</td>
<td>
<input type="text" value="<?php echo $sch_password ?>" name="password" class="input is-success" style="width:320px; border: 1px solid #5E4EA0"
required><br><br>
</td>        
</tr>   
</table>          
<label class="label" style="margin-left: 260px">
<button type="submit" name="update_schoolusers" class="button is-primary" style="border: 1px solid #5E4EA0; border-radius:4px; color:white; background-color:#5E4EA0">
&nbsp;&nbsp;&nbsp;Update User&nbsp;&nbsp;&nbsp;</button>
</label>
</form> 
<!--END of FOrm-->   
<br><br>
</div>              
<div style="border: 1px solid #E5E7E9; width: 200px; height: 300px; padding-left:30px">
<img src="<?php echo $sch_user_image;?>" alt="<?php echo $sch_firstname." ".$sch_lastname;?>" 
style="width:150px;height:120px; border-radius:5px; margin-top:10px;" > 
<form action="admin_connector.php" method="post" enctype="multipart/form-data">
<style>#upload{visibility: hidden;}</style>
<input type="hidden" value="<?php  echo $sch_user_id;?>" name="user_id">
<input type="file" name="image" id="upload">
<input type="button" value="Select the Image" class="button is-default"
onclick="document.getElementById('upload').click()"> <br><br>
<input type="submit" name="upload_schooluser" value="&nbsp;&nbsp;Upload Image &nbsp;&nbsp;" class="button is-primary" 
style="border: 1px solid #5E4EA0; border-radius:4px; color:white; background-color:#5E4EA0">   
<br><br>   
</form>
</div>
</div>
</div>
</div>

<div> 
<?php include('../footer.php'); ?>
</div>
</main>
</body> 
</html>

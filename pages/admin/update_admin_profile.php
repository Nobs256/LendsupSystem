<?php   
include("../conn.php");
$s="";
if(isset($_REQUEST['updated_user_id']))
{   
$user_id = $_REQUEST['updated_user_id'];

$s = "<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:0px; padding:7px; width: 500px'>
<font color=white>The email is already used by another user !!</font>
<a  style='color:white; margin-left:150px;' href='update_admin_profile.php?user_id=$user_id'>X</a>
</div>";
    
  $result = mysqli_fetch_assoc(mysqli_query($conn,"select user_id, ucase(firstname) as firstname, ucase(lastname) as lastname,
  username, phone, category, password, users_image from new_users where user_id ='$user_id'"));
  $user_id = $result["user_id"];
  $firstname = $result['firstname'];
  $lastname = $result['lastname'];
  $username = $result['username'];   
  $phone = $result['phone'];
  $password = $result['password'];
  $user_image = $result['users_image'];
   
  //checking if user profile image is uploaded if not then use default image
if($user_image == ""){
    $user_image = "../assets/images/users_images/user_sample.png";
}else{
    $user_image = "../assets/images/users_images/".$user_image;
}
}
//======================Not Updated 
                                  
if(isset($_REQUEST['user_id']))
{   
$user_id = $_REQUEST['user_id'];
    
  $result = mysqli_fetch_assoc(mysqli_query($conn,"select user_id, ucase(firstname) as firstname, ucase(lastname) as lastname,
  username, phone, category, password, users_image from new_users where user_id ='$user_id'"));
  $user_id = $result["user_id"];
  $firstname = $result['firstname'];
  $lastname = $result['lastname'];
  $username = $result['username'];   
  $phone = $result['phone'];
  $password = $result['password'];
  $user_image = $result['users_image'];
   
  //checking if user profile image is uploaded if not then use default image
if($user_image == ""){
    $user_image = "../assets/images/users_images/user_sample.png";
}else{
    $user_image = "../assets/images/users_images/".$user_image;
}
}
 include('header.php'); 
 ?>
<main class="column main">   
 <div id="main_heading" style="width: 200px; margin-left:730px"> <b>USER PROFILE</b> </div>
 <div id="main_body"> 
  <div class="field is-horizontal">
  <div style="width:800px">
    <?php echo $s; ?>
 <br>
 <form method="post" action="admin_connector.php">
<input type="hidden"   value="<?php echo $user_id;?>" name="user_id">
<input type="hidden"   value="<?php echo $username;?>" name="check_email">
<label class="label">First Name:&nbsp;&nbsp;&nbsp;&nbsp; 
 <input type="text" name="firstname" class="input is-success"
 style="width:320px; border: 1px solid #5E4EA0" value="<?php echo $firstname;?>" required >
  </label>
  

<label class="label">Last Name:&nbsp;&nbsp; &nbsp;&nbsp; 
<input type="text"  name="lastname" value="<?php echo $lastname;?>"  class="input is-success"
style="width:320px; border: 1px solid #5E4EA0" required>
 </label>
 
<label class="label">Email:&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="text" name="email" value="<?php echo $username;?>"  class="input is-success"
style="width:320px; border: 1px solid #5E4EA0" required>
</label>

<label class="label">Phone No:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="tel" name="phone" value="<?php echo $phone;?>" pattern="^[0-9]+" title="User Numbers only" 
maxlength = "10" class="input is-success"
style="width:320px; border: 1px solid #5E4EA0" required>
</label>
 
<label class="label">Password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
<input type="text" name="password" value="<?php echo $password;?>"  class="input is-success"
style="width:320px; border: 1px solid #5E4EA0" required>
</label>
  <br>
<label class="label" style="margin-left: 260px">  
<button type="submit" name="update_admin" class="button is-primary" style="border: 1px solid #5E4EA0; border-radius:4px; color:white; background-color:#5E4EA0">
               &nbsp;&nbsp;&nbsp;  Update a User&nbsp;&nbsp;&nbsp;</button>
</label>
</form>   
   <!--END of FOrm-->   
 <br><br>
</div>
<div style="border: 1px solid #E5E7E9; width: 200px; height:150px; padding-left:30px">
<img src="<?php echo $user_image;?>" alt="<?php echo $firstname." ".$lastname;?>" 
style="width:150px;height:120px; border-radius:5px; margin-top:10px;" > 
 <form action="admin_connector.php" method="post" enctype="multipart/form-data">
<style>#upload{visibility: hidden;}</style>
 <input type="hidden"  class="form-control" value="<?php  echo $user_id;?>" name="user_id">
 <input type="file" name="image" id="upload">
<input type="button" value="Select the Image" class="button is-default"
 onclick="document.getElementById('upload').click()"> <br><br>
<input type="submit" name="upload_admin_image" value="&nbsp;&nbsp;Upload Image &nbsp;&nbsp;" class="button is-primary" 
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

         
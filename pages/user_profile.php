<?php   
$s="";
include('header_user.php');                                 
if(isset($_REQUEST['user_id']))
{
$user_id = $_REQUEST['user_id'];

$result = mysqli_fetch_assoc(mysqli_query($conn,"select user_id, ucase(firstname) as firstname, ucase(lastname) as lastname, phone,
username, category, password, users_image from new_users where user_id ='$user_id'"));
$user_id = $result["user_id"];
$firstname = $result['firstname'];
$lastname = $result['lastname'];
$phone = $result['phone'];
$username = $result['username'];   
$cat = $result['category'];
$password = $result['password'];
$user_image = $result['users_image'];

//checking if user profile image is uploaded if not then use default image
if($user_image == ""){
$user_image = "assets/images/users_images/user_sample.png";
}else{
$user_image = "assets/images/users_images/".$user_image;
}
}

?>
<main class="column main" style="background-color:#EAEAEA;">
<?php include ('summary.php') ?>

<div id="main_heading"> <b>Cashier Profile</b><br>You Can Change Your Password</div>     
 
<div id="main_container">
<div id="main_body">   
<br>
<br>                   
<div class="field is-horizontal">
<div style="width:800px">
<?php echo $s; ?>
<br>
<form  method="post" action="user_connector.php">
<input type="hidden"   value="<?php echo $user_id;?>" name="user_id">
<input type="hidden"   value="<?php echo $username;?>" name="username">
<input type="hidden"   value="<?php echo $boss_id;?>" name="boss_id">


<table style="width:500px;" border="0">
<tr>
<td width="100px"> 
<label class="label">First Name: </label>
</td>
<td width="240px">
<input type="text" value="<?php echo $firstname; ?>" name="firstname" class="input is-success" style="width:320px; border: 1px solid #006F37" 
required><br><br>
</td>
</tr><!--end of tr -->
<tr>    
<td width="100px"> 
<label class="label">Last Name: </label>
</td>
<td width="180px">
<input type="text" value="<?php echo $lastname; ?>" name="lastname" class="input is-success" style="width:320px; border: 1px solid #006F37"
required><br><br>       
</td>
</tr><!--end of tr -->
<tr>
<td> 
<label class="label">Phone No: </label>
</td>
<td>
<input type="tel" value="<?php echo $phone; ?>" maxlength = "10" name="phone" class="input is-success" style="width:320px; border: 1px solid #006F37" 
required><br><br>          
</td> 
</tr><!--end of tr -->
<tr>  
<td>      
<label class="label">Username: </label> 
</td>
<td>
<input type="text" value="<?php echo $username; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cannot be Changed" class="input is-success" style="width:320px; border: 1px solid #006F37"
required><br><br>         
</td>        
</tr><!--end of tr -->
<tr> 
<td>      
<label class="label">Password:</label>
</td>
<td>
<input type="text" value="<?php echo $password ?>" name="password" class="input is-success" style="width:320px; border: 1px solid #006F37"
required><br><br>
</td>        
</tr>   
</table>          
<label class="label" style="margin-left: 260px">
<button type="submit" name="update_userprofile" class="button is-primary" style="border: 1px solid #006F37; border-radius:4px; color:white; background-color:#006F37">
&nbsp;&nbsp;&nbsp;Update User&nbsp;&nbsp;&nbsp;</button>
</label>
</form> 
<!--END of FOrm-->   
<br><br>
</div>
<div style="border: 1px solid #E5E7E9; width: 200px; height:150px; padding-left:30px">
<img src="<?php echo $user_image;?>" alt="<?php echo $firstname." ".$lastname;?>" 
style="width:150px;height:120px; border-radius:5px; margin-top:10px;" > 
<form action="user_connector.php" method="post" enctype="multipart/form-data">
<style>#upload{visibility: hidden;}</style>
<input type="hidden"  class="form-control" value="<?php  echo $user_id;?>" name="user_id">
<input type="hidden"   value="<?php echo $boss_id;?>" name="boss_id">
<input type="file" name="image" id="upload">
<input type="button" value="Select the Image" class="button is-default"
onclick="document.getElementById('upload').click()"> <br><br>
<input type="submit" name="upload_user_image" value="&nbsp;&nbsp;Upload Image &nbsp;&nbsp;" class="button is-primary" 
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




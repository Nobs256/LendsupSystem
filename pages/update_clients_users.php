<?php
$sa="";
if(isset($_POST['reload']))
{
$sec=$_POST['re'];
 header("refresh: $sec");
}   
 
include('header_user.php');
if(isset($_REQUEST['client_update']))
{	  
$client_id = $_REQUEST['client_update'];
 
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients where client_id ='$client_id'"));
$client_id = $result["client_id"];
$firstname = $result['firstname'];
$lastname = $result['lastname'];
$sex = $result['sex'];
$phone= $result['phone'];
$dob = $result['dob'];
$nid = $result['nid']; 
$place_r= $result['place_r'];
$marital= $result['marital'];
$business = $result['business'];
$b_location = $result['b_location'];
$user_id = $result['users_id'];

if(isset($_GET['success'])){
$sa = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>
<font color=white>A Client is Successfully Updated!!</font>
<a href='update_clients_users.php?client_update=$client_id' style='color:white; margin-left:100px;''>X</a>
</div>";
}

}
?>
<style>
tr{height:45px;}
</style>
<main class="column main" style="background-color:#EAEAEA;">
<?php include ('summary.php') ?>   
<div id="main_heading"> <b>UPDATE CLIENTS</b>
<font color="#EAEAEA">----------------------------------------------------------</font>
 <a href="view_clients.php"><font color="green">BACK</font></a>
<?php echo $sa;?>

</div>     
<div id="main_container"> 
<div id="main_body">   
<br>
<form method="post" action="user_connector.php">
<input type="hidden" name="boss_id" value="<?php echo $boss_id;?>">
<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
<input type="hidden"  value="<?php echo $client_id;?>" name="client_id">
<input type="hidden"  value="<?php echo $nid;?>" name="nid">
 

<table style="width:485px; font-size:15px; margin-left:30px">
<tr><td width="180px">        
&nbsp;&nbsp;FIRST NAME: 
</td><td width="300px"> 
<input type="text" name="firstname" value="<?php echo $firstname;?>" class="input is-success" style="width:270px; border: 1px solid #006F37"
required>

</td>
</tr><tr><td>
&nbsp;&nbsp;LAST NAME: 
</td><td>
<input type="text" name="lastname" value="<?php echo $lastname;?>" class="input is-success" style="width:270px; border: 1px solid #006F37" 
required>

</td>
</tr><tr><td>
&nbsp;&nbsp;SEX:
</td><td> 
<div class="select is-success">
<select name="sex" style="width:270px; border: 1px solid #006F37; height:35px" required>
<option><?php echo $sex;?></option>
<option>Male</option>
<option>Female</option>
</select>
</td>
</tr>

<tr><td>
&nbsp;&nbsp;PHONE NO:</td><td>
<input type="tel" maxlength = "10"  name="phone" class="input is-success" style="width:270px; border: 1px solid #006F37" 
value="<?php echo $phone;?>"pattern="^[0-9]+" title="User Numbers only" required>

</td>
</tr>
<tr>
<td>
<div class="form-group"> 
&nbsp;&nbsp;PLACE OF RESIDENCE: 
</td><td>
<div class="col-sm-6"> 
<input type="text" name="place_r" value="<?php echo $place_r;?>" class="input is-success" style="width:270px; border: 1px solid #006F37">

</td>
</tr><tr><td>
&nbsp;&nbsp;BUSINESS: 
</td><td>
<div class="col-sm-6"> 
<input type="text" name="business" value="<?php echo $business;?>" class="input is-success" style="width:270px; border: 1px solid #006F37" >

</td>
<tr> 
<td>      
&nbsp;&nbsp;LOCATION: 
</td>
<td>
<div class="select is-success">  
<select  name="b_location"  style="width:270px; border: 1px solid #006F37; height:35px" required> 
<option><?php echo $b_location; ?></option>
<?php
$comb = mysqli_query($conn,"SELECT * from location where bossloc_id='$boss_id' and userloc_id='$user_id' ");
while($select_comb = mysqli_fetch_array($comb)){
$location=$select_comb['location']; 
echo "<option> $location </option>"; 
}
echo" 
</select></div>";
?>   
</td>        
</tr>
</table>
<label class="label" style="margin-left: 300px">
<button type="submit" name="update_clients" class="button is-primary" style="border: 1px solid #006F37; border-radius:4px; color:white; background-color:#006F37">
&nbsp;&nbsp;&nbsp; Update a Client&nbsp;&nbsp;&nbsp;</button>
</label>
</form>   
</div>
</div>


<div> 
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>
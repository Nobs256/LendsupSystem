<?php
$sa="";
if(isset($_POST['reload']))
{
$sec=$_POST['re'];
 header("refresh: $sec");
}   
 
if(isset($_GET['success'])){
$sa = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>
<font color=white>A Cashier is successfully Added!!</font>
<a href='add_users.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</div>";
}

if(isset($_GET['deleted'])){
$sa = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>
<font color=white>A Cashier is Successfully Deleted!!</font>
<a href='add_users.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</div>";
}

if(isset($_GET['already_user'])){
$sa = "<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>
<font color=white>The username is already taken, Choose another one!!</font>
<a href='add_users.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</div>";
}
include('header.php');
?>
<main class="column main" style="background-color:#EAEAEA;">
<?php include ('summary_admin.php') ?>   
<div id="main_heading"> <b>Create New Cashier</b>
<font color="#EAEAEA">-------------------</font>
 <a href="view_users.php"><font color="green">View Registered Cashiers</font></a>
<?php echo $sa;?>

</div>     
<div id="main_container"> 
<div id="main_body">   
<br>
<br>                               
<form method="post" action="admin_connector.php">
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
Sex: 
</td>
<td>
<div class="select is-success">
<select  name="sex"  style="width:270px; border: 1px solid #006F37" required>
<option></option>
<option value="Male">Male</option>
<option value="Female">Female</option>            
</select> <br><br>
</div>          
</td> 

<td>      
Marital Status:  
</td>
<td>
<div class="select is-success">
<select  name="marital"  style="width:270px; border: 1px solid #006F37" required>
<option></option>
<option value="Single">Single</option>
<option value="Married">Married</option>            
</select> 
</div> <br><br>       
</td>        
</tr><!--end of tr -->
<tr>

 
<tr>
<td> 
Phone No: 
</td>
<td>
<input type="tel" maxlength = "10" name="phone" class="input is-success" style="width:270px; border: 1px solid #006F37" 
required><br><br>          
</td> 

<td>      
Branch:  
</td>
<td>
<input type="text"   name="branch" class="input is-success" style="width:270px; border: 1px solid #006F37"
required><br><br>         
</td>        
</tr><!--end of tr -->

<tr>
<td> 
Year of Birth: 
</td>
<td>
<input type="text" maxlength = "4" name="year" class="input is-success" style="width:270px; border: 1px solid #006F37" 
required><br><br>          
</td> 

<td>      
National ID:  
</td>
<td>
<input type="text"   name="nid" class="input is-success" style="width:270px; border: 1px solid #006F37"
required><br><br>         
</td>        
</tr><!--end of tr -->
<tr>

<tr>
<td> 
Place of Origin: 
</td>
<td>
<input type="text"  name="place_o" class="input is-success" style="width:270px; border: 1px solid #006F37" 
required><br><br>          
</td> 

<td>      
Place of Residence:  
</td>
<td>
<input type="text"   name="place_r" class="input is-success" style="width:270px; border: 1px solid #006F37"
required><br><br>         
</td>        
</tr><!--end of tr -->
<tr> 
<td>      
Username:
</td>
<td>
<input type="text"  name="username" class="input is-success" style="width:270px; border: 1px solid #006F37"
required><br><br>
</td>     
<td>      
Password:
</td>
<td>
<input type="text"  name="password" class="input is-success" style="width:270px; border: 1px solid #006F37"
required><br><br>
</td>        
</tr>   
</table>          
 
<button type="submit" name="add_users" class="button is-primary"
 style="border: 1px solid #006F37; border-radius:4px; color:white;
  background-color:#006F37; margin-left:400px">
&nbsp;&nbsp;&nbsp;Add A Cashier&nbsp;&nbsp;&nbsp;</button>

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
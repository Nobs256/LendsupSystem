<?php   
include("../conn.php");

if(isset($_REQUEST['sc_id']))
{   
$sc_id = $_REQUEST['sc_id'];
$result = mysqli_fetch_assoc(mysqli_query($conn,"select sc_id, ucase(sc_name) as sc_name, ucase(hm_name) as hm_name,
hm_phone, sc_email, sc_moto, ucase(province) as province, ucase(dist) as dist, ucase(county) as county, ucase(sub_county) as sub_county,
ucase(parish) as parish, ucase(cell) as cell, sc_logo from schools where sc_id ='$sc_id'"));
$schs_id = $result["sc_id"];
$sch_name = $result['sc_name'];

}

include('header.php'); 

?>
<main class="column main">   
<div id="main_heading" style="width: 500px; margin-left:0px"> <b>CREATE USERS FOR <?php echo $sch_name;?></b></div>     
<div id="main_body">   
<br><br>                               
<form method="post" action="admin_connector.php">
<input type="hidden" name="sc_id" value="<?php echo $schs_id; ?>">
<table style="width:900px;" border="0">
<tr>
<td width="100px"> 
<label class="label">First Name: </label>
</td>
<td width="240px">
<input type="text"   name="fname" class="input is-success" style="width:270px; border: 1px solid #5E4EA0" 
required><br><br>
</td>

<td width="100px"> 
<label class="label">Last Name: </label>
</td>
<td width="180px">
<input type="text"   name="lname" class="input is-success" style="width:270px; border: 1px solid #5E4EA0"
required><br><br>       
</td>
</tr><!--end of tr -->

<tr>
<td> 
<label class="label">Phone No: </label>
</td>
<td>
<input type="tel" maxlength = "10" name="phone" class="input is-success" style="width:270px; border: 1px solid #5E4EA0" 
required><br><br>          
</td> 

<td>      
<label class="label">E-Mail: </label> 
</td>
<td>
<input type="email"   name="email" class="input is-success" style="width:270px; border: 1px solid #5E4EA0"
required><br><br>         
</td>        
</tr><!--end of tr -->
<tr>

<td>        
<label class="label">Category: </label>
</td><td>
<div class="select is-success">
<select  name="cat"  style="width:270px; border: 1px solid #5E4EA0" required>
<option></option>
<option value="Admin">DOS/SECRETARY</option>
<option value="User">TEACHER</option> 
<option value="Headmaster">HEADTEACHER</option> 
<option value="Bursar">SCHOOL BURSAR</option>  
<option value="Librarian">LIBRARIAN</option>            
</select> <br><br>
</div>
</td>  
<td>      
<label class="label">Password:</label>
</td>
<td>
<input type="text"  name="password" class="input is-success" style="width:270px; border: 1px solid #5E4EA0"
required><br><br>
</td>        
</tr>   
</table>          
<label class="label" style="margin-left: 270px">
<button type="submit" name="create_schoolusers" class="button is-primary" style="border: 1px solid #5E4EA0; border-radius:4px; color:white; background-color:#5E4EA0">
&nbsp;&nbsp;&nbsp;Craete A User&nbsp;&nbsp;&nbsp;</button>
</label>
</form>   

<hr color="#5E4EA0" size="3"></hr>

<table class="table table-hover">
<thead>
<tr  style="height:5px;"> 
<th>N<sup>o</sup></th>
<th>Names</th>
<th>Email</th>                                                   
<th>Category</th> 
<th> password</th>
<th></th>
<th>Manage</th>             
</tr>
</thead>
<tbody>
<?php
$i = 0;
$select_users = mysqli_query($conn,"SELECT * FROM new_users where sch_id='$schs_id' ");

while($selected_users = mysqli_fetch_array($select_users)){
$user_id = $selected_users["user_id"];
$name = $selected_users["firstname"].' '.$selected_users["lastname"];   
$cat= $selected_users["category"]; 
$em= $selected_users["username"];                      
$pass= $selected_users["password"];                                                                            
$i++;
?>


<tr> 
<td><?php echo $i;?></td>
<td><?php echo $name;?></td>
<td><?php echo $em;?></td> 
<td><?php echo $cat;?></td> 
<td><?php echo $pass;?></td>  
<td> </td> 
<td><a style="border: 1px solid #5E4EA0; border-radius:4px; color:#5E4EA0" 
class="button is-default" 
href="update_school_users.php?user_id=<?php echo $user_id;?>">&nbsp;UPDATE</a></td>

<td><a style="border: 1px solid red; border-radius:4px; color:white" 
class="button is-danger" 
href="admin_connector.php?delete_user=<?php echo $user_id;?>">&nbsp;DELETE</a></td>
</tr>
<?php  } ?>                

</tbody>
</table>  
</div>
</div>


<div> 
<?php include('../footer.php'); ?>
</div>
</main>
</body> 
</html>
<?php 
$s="";
if(isset($_POST['reload']))
{
$sec=$_POST['re'];
header("refresh: $sec");
}
if(isset($_GET['add_sch'])){
 $s = "<div style='background-color:#5E4EA0; border-radius:5px; color:white; 
 height:40px; margin-left:0px; padding:7px; width: 880px'>
<font color=white>A School is Successfully added !!</font>
<a href='school_reg_form.php?reload=1' style='color:white; margin-left:600px;''>X</a>
</div>";
}

if(isset($_GET['delete_sch'])){
 $s = "<div style='background-color:#5E4EA0; border-radius:5px; color:white; 
 height:40px; margin-left:0px; padding:7px; width: 880px'>
<font color=white>A School is successfully deleted!!</font>
<a href='school_reg_form.php?reload=1' style='color:white; margin-left:600px;''>X</a>
</div>";
}

if(isset($_GET['already_sch'])){
 $s = "<div style='background-color:red; border-radius:5px; color:white; 
 height:40px; margin-left:0px; padding:7px; width: 880px'>
<font color=white>A Scool is Already Entered!!</font>
<a href='school_reg_form.php?reload=1' style='color:white; margin-left:600px;''>X</a>
</div>";
}

include('header.php'); ?>
<main class="column main">   
<div id="main_heading" style="margin-left:630px">SCHOOL REGISTRATION FORM</div>     
<div id="main_body">   
<?php   echo $s; ?> 
<br><br>                               
<form method="post" action="admin_connector.php">
<input type="hidden" name="user_email" value="<?php echo $user_email; ?>">
<table style="width:900px;" border="0">
<tr>
<td width="100px"> 
<label class="label"> School Name: </label>
</td>
<td width="240px">
<input type="text"   name="sc_name" class="input is-success" style="width:260px; border: 1px solid #5E4EA0" 
required><br><br>
</td>

<td width="100px"> 
<label class="label">H/M Name: </label>
</td>
<td width="180px">
<input type="text"   name="hm_name" class="input is-success" style="width:260px; border: 1px solid #5E4EA0"
required><br><br>       
</td>
</tr><!--end of tr -->

<tr>
<td> 
<label class="label">H/M Phone No: </label>
</td>
<td>
<input type="tel" maxlength = "10" name="hm_phone" class="input is-success" style="width:260px; border: 1px solid #5E4EA0" 
required><br><br>          
</td> 

<td>      
<label class="label">  School E-Mail: </label> 
</td>
<td>
<input type="text"   name="email" class="input is-success" style="width:260px; border: 1px solid #5E4EA0"
required><br><br> 

</td>        
</tr><!--end of tr -->
<tr>
<td>       
<label class="label">  Moto: </label>
</td><td>
<input type="text"   name="moto" class="input is-success" style="width:260px; border: 1px solid #5E4EA0" 
required><br><br>

</td>    
<td>        
<label class="label"> Province: </label>
</td><td>
<div class="select is-success">
<select  name="pro"  style="width:260px; border: 1px solid #5E4EA0" required>
<option></option>
<option>Western</option>
<option>Central</option>
<option>Eastern</option>
<option>Northern</option>
<option>Southern</option>
</select> <br><br>
</div>
</td>        
</tr><!--end of tr -->

<tr>
<td>      
<label class="label"> District: </label>
</td>
<td>
<input type="text"   name="dist" class="input is-success" style="width:260px; border: 1px solid #5E4EA0"
required><br><br>
</td>               
<td>
<label class="label"> County: </label>
</td>
<td>
<input type="text"   name="county" class="input is-success" style="width:260px; border: 1px solid #5E4EA0"
required><br><br>

</td>        
</tr><!--end of tr -->

<tr>
<td>
<label class="label"> Sub-County:</label> 
</td>
<td>
<input type="text"   name="sub"  class="input is-success" style="width:260px; border: 1px solid #5E4EA0"
required><br><br>         
</td> 

<td>
<label class="label"> Parish: </label>
</td>
<td>
<input type="text"   name="par" class="input is-success" style="width:260px; border: 1px solid #5E4EA0"
required><br><br>
</td>
</tr><!--end of tr -->

<tr>
<tr>
<td>
<label class="label">   Cell: </label>
</td>
<td>
<input type="text"  name="cell" class="input is-success" style="width:260px; border: 1px solid #5E4EA0"
required><br><br>
</td>        
<td>                 
</td>
<td> <button type="submit" name="submit_sch" class="button is-primary" style="border: 1px solid #5E4EA0; border-radius:4px; color:white; background-color:#5E4EA0">
&nbsp;&nbsp;&nbsp;Add School&nbsp;&nbsp;&nbsp;</button>         
</td>
</tr>
</table>
</form> 
<hr color="#5E4EA0" size="3"></hr>
<table class="table table-hover">
<thead>
<tr  style="height:5px;"> 
<th>N<sup>o</sup></th>
<th>School</th>
<th>Head Master</th>
<th>Phone No.</th>   

<th>Modify</th>
</tr>
</thead>
<tbody>
<?php
$i = 0;

$select_grades = mysqli_query($conn,"SELECT sc_id, ucase(sc_name) as sc_name, ucase(hm_name) as hm_name, hm_phone FROM schools where user_reg='$user_email' order by sc_name");

while($selected_grade = mysqli_fetch_array($select_grades)){
$sc_id = $selected_grade["sc_id"];
$sc_name = $selected_grade["sc_name"];
$hm_name = $selected_grade["hm_name"];
$hm_phone = $selected_grade["hm_phone"];
                    
$i++;
?>        
<tr> 
<td><?php echo $i;?></td>
<td><?php echo $sc_name;?></td>  
<td><?php echo $hm_name;?></td> 
<td><?php echo $hm_phone;?></td>     
<td><a style="border: 1px solid #5E4EA0; border-radius:4px; color:#5E4EA0" class="button is-default" href="update_schools.php?sc_id=<?php echo $sc_id;?>">&nbsp;EDIT</a></td>
<td><a style="border: 1px solid red; border-radius:4px; color:red" class="button is-default" href="admin_connector.php?delete_sch=<?php echo $sc_id;?>">&nbsp;DELETE</a></td>
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


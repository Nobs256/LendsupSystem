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

$total_students = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM student where 
std_sch_id='$sc_id'"));

}
include('header.php'); 
?>
<!DOCTYPE html>
<html>
<body>

<main class="column main">  
<br>
<div id="main_body"> 
<div id="main_heading" style="width:430px; background-color:white; color:#5E4EA0;
margin-left:0px"> <b>ADD STUDENTS FOR <?php echo $sch_name;?><br></b>
<?php echo "<font size='2' color='red'>The total No. of Student: $total_students</font>"; ?>
</div>     
<br>                              
<form enctype="multipart/form-data" method="post" role="form">
<div class="form-group">
<label for="exampleInputFile">File Upload</label>
<input type="file" name="file" id="file" class="input is-success" style="width:270px; border: 1px solid #5E4EA0" >
<p class="help-block">Only Excel/CSV File Import.</p>
</div>
<br>

<button type="submit" class="button is-primary" style="border: 1px solid #5E4EA0; border-radius:4px; color:white; background-color:#5E4EA0"
name="submit" value="submit">Upload Data</button>
</form>
<?php
if(isset($_POST["submit"]))
{
$del1=mysqli_query($conn, "DELETE FROM student_upload");
if(!$conn){
die('Could not Connect My Sql:' .mysqli_error());
}
$file = $_FILES['file']['tmp_name'];
$handle = fopen($file, "r");
$c = 0;
while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
{

$sch_id = $filesop[0];
$firstname = $filesop[1];
$lastname = $filesop[2];
$sex =$filesop[3];
$dob = $filesop[4];
$boarder_day = $filesop[5];
$nationality = $filesop[6];
$class = $filesop[7];
$comb = $filesop[8];
$previous_school = $filesop[9];
$parent_names = $filesop[10];
$parent_phone =$filesop[11];
$next_of_kin = $filesop[12];
$nextofkin_phone =$filesop[13];
$std_district = $filesop[14];
$special_need = $filesop[15];  
$mother = $filesop[16]; 
$mother_tel = $filesop[17]; 
$std_sector = $filesop[18];
$std_cell = $filesop[19];
$std_village = $filesop[20];

$school_query = "INSERT INTO student (std_sch_id, firstname, lastname, sex, dob, boarder_day, nationality, class, comb, previous_school, parent_names, parent_phone, mother, mother_tel, next_of_kin, 
nextofkin_phone, std_district, std_sector, std_cell, std_village, special_need)

VALUES ('$sch_id','$firstname', '$lastname', '$sex', '$dob', '$boarder_day', '$nationality', '$class', '$comb', '$previous_school', '$parent_names', '$parent_phone', '$mother', '$mother_tel', '$next_of_kin',
'$nextofkin_phone', '$std_district', '$std_sector', '$std_cell', '$std_village', '$special_need')";
$execute = mysqli_query($conn,$school_query);  

$school_query = "INSERT INTO student_upload (std_sch_id, firstname, lastname, sex, dob, boarder_day, nationality, class, comb, previous_school, parent_names, parent_phone, mother, mother_tel, next_of_kin, 
nextofkin_phone, std_district, std_sector, std_cell, std_village, special_need)

VALUES ('$sch_id','$firstname', '$lastname', '$sex', '$dob', '$boarder_day', '$nationality', '$class', '$comb', '$previous_school', '$parent_names', '$parent_phone', '$mother', '$mother_tel', '$next_of_kin',
'$nextofkin_phone', '$std_district', '$std_sector', '$std_cell', '$std_village', '$special_need')";
$execute = mysqli_query($conn,$school_query);  
$c = $c + 1;
}



if($execute){
echo "<font size='4' color='green'><b>Data is successfully Entered!!</b></font>";
} 
else
{
echo "Sorry! Unable to impo.";
}

?>
<hr color="#5E4EA0" size="3"></hr>

<table class="table table-hover">
<thead>
<tr  style="height:5px;"> 
<th>N<sup>o</sup></th>
<th>School ID</th>
<th>Name</th>                                                   
<th>Class</th>             
<th>Combination</th>                      
</tr>
</thead>
<tbody>
<?php
$i = 0;
$select_users = mysqli_query($conn,"SELECT * FROM student_upload");
while($selected_users = mysqli_fetch_array($select_users)){
$scho_id = $selected_users["std_sch_id"];
$name = $selected_users["firstname"].' '.$selected_users["lastname"];   
$class= $selected_users["class"]; 
$comb= $selected_users["comb"];                                                                     
$i++;
?>   
<tr> 
<td><?php echo $i;?></td>
<td><?php echo $scho_id;?></td>
<td><?php echo $name;?></td>
<td><?php echo $class;?></td> 
<td><?php echo $comb;?></td> 
</tr>
<?php  } ?>                

</tbody>
</table> 
<?php } ?> 
</div>
</div>


<div> 
<?php include('../footer.php'); ?>
</div>
</main>
</body> 
</html>
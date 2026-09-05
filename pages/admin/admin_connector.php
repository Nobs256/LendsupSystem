<?php 
include("../conn.php"); 
//preventing sql injection
function prevent_sql_injection($data){
$data = stripslashes($data);
$data = htmlspecialchars($data);
$data = trim($data);
return $data;
}
//======================================================ADD A SCHOOL
$s="";
if(isset($_POST['submit_sch'])){
$user_email = $_POST['user_email'];
$sc_name = $_POST['sc_name'];
$hm_name = $_POST['hm_name'];
$hm_phone = $_POST['hm_phone'];
$email = $_POST['email'];
$moto = $_POST['moto'];
$province = $_POST['pro'];
$d = $_POST['dist'];
$county = $_POST['county'];
$sub = $_POST['sub'];
$par = $_POST['par'];
$cell = $_POST['cell'];


$same_sch ="select * from schools where sc_name='$sc_name' and hm_name='$hm_name' and hm_phone='$hm_phone' and sc_email='$email'
and sc_moto='$moto'";
$run = mysqli_query($conn,$same_sch) or die("Could get DB");
$same_sch = mysqli_num_rows($run);
if ($same_sch==1){
$s = 1;
header("Location: school_reg_form.php?already_sch=$s"); 
}
else{
mysqli_query($conn,"INSERT INTO schools(sc_id, sc_name, hm_name, hm_phone, sc_email, sc_moto, province, dist, county,
sub_county,  parish, cell, user_reg) 
VALUES (NULL, '$sc_name', '$hm_name', '$hm_phone', '$email', '$moto', '$province',  '$d', '$county',  '$sub', '$par', '$cell', '$user_email')");

header("Location: school_reg_form.php?add_sch=$s");            

} } 


//==================================================Update School
if(isset($_POST['update_sch'])){
$sc_id = prevent_sql_injection(ucfirst($_POST['sc_id']));
$sc_name =prevent_sql_injection(ucfirst( $_POST['sc_name']));
$hm_name =prevent_sql_injection(ucfirst( $_POST['hm_name']));
$hm_phone =prevent_sql_injection(ucfirst( $_POST['hm_phone']));
$email = prevent_sql_injection(ucfirst($_POST['email']));
$moto =prevent_sql_injection(ucfirst( $_POST['moto']));
$province = prevent_sql_injection(ucfirst($_POST['pro']));
$d =prevent_sql_injection(ucfirst( $_POST['dist']));
$county =prevent_sql_injection(ucfirst( $_POST['county']));
$sub =prevent_sql_injection(ucfirst( $_POST['sub']));
$par = prevent_sql_injection(ucfirst($_POST['par']));
$cell = prevent_sql_injection(ucfirst($_POST['cell']));

$school_update ="update schools set  sc_id='$sc_id', sc_name='$sc_name', hm_phone='$hm_phone', sc_email='$email',  
sc_moto='$moto', province='$province', dist='$d', county='$county', sub_county='$sub', parish='$par', cell='$cell'
where sc_id='$sc_id'";
$execute = mysqli_query($conn,$school_update);

header("Location: update_schools.php?sc_id=$sc_id");
}

//=============================SCHOOL LOGO

if(isset($_POST['school_logo'])){
$sch_id = $_POST["sc_id"];
if($_FILES['image']['name']){ //if image is selected
$allowed_ext =array("jpg","png","JPG","PNG");//allowed extensions
$ext = end(explode(".",$_FILES['image']['name']));//spilt file name into array
if(in_array($ext,$allowed_ext)){//check if image has allowed extenstion.
if($_FILES["image"]["size"]<1000000){//check image size 1000000 means 1000KB or 1MB
$new_name =md5(rand()).'.'.$ext;//rename file before uploding
$path ="../assets/images/sch_logo/".$new_name;
//if image have been uploaded then update student table
if(move_uploaded_file($_FILES['image']['tmp_name'],$path)){

//remove the first uploaded image to save space

$img_upload = "update schools Set sc_logo='$new_name' where sc_id='$sch_id'";
mysqli_query($conn,$img_upload);

header("Location:update_schools.php?sc_id=$sch_id");

} 
} 
} 
} 
}
//===========================================Delete A School

if(isset($_GET['delete_sch'])){
$sc_id = $_GET['delete_sch'];
mysqli_query($conn,"DELETE FROM schools WHERE sc_id='$sc_id'");
mysqli_query($conn,"DELETE FROM new_users WHERE sch_id='$sc_id'");
mysqli_query($conn,"DELETE FROM teachers WHERE tr_sch_id='$sc_id'");
mysqli_query($conn,"DELETE FROM class_teachers WHERE class_tr_sch_id='$sc_id'");
mysqli_query($conn,"DELETE FROM combinations WHERE comb_sch_id='$sc_id'");
mysqli_query($conn,"DELETE FROM combination_subject WHERE comb_sub_sch_id='$sc_id'");
mysqli_query($conn,"DELETE FROM courses_percentages WHERE perc_sch_id='$sc_id'");
mysqli_query($conn,"DELETE FROM divisions WHERE sc_div_id='$sc_id'");
mysqli_query($conn,"DELETE FROM grades WHERE sc_grade_id='$sc_id'");
mysqli_query($conn,"DELETE FROM marks WHERE marks_sch_id='$sc_id'");
mysqli_query($conn,"DELETE FROM student WHERE position_sch_id='$sc_id'");
mysqli_query($conn,"DELETE FROM subjects WHERE sub_sch_id='$sc_id'");
mysqli_query($conn,"DELETE FROM subject_teachers WHERE sub_tr_sch_id='$sc_id'");
mysqli_query($conn,"DELETE FROM total_aggregate WHERE agg_sch_id='$sc_id'");
mysqli_query($conn,"DELETE FROM total_divisions WHERE div_sch_id='$sc_id'");
$s = 1;
header("Location: school_reg_form.php?delete_sch=$s"); 
}

if(isset($_GET['delete_user'])){
$user_id = $_GET['delete_user'];
mysqli_query($conn,"DELETE FROM new_users WHERE user_id='$user_id'");
$s = 1;

header("Location:admin_homepage.php?delete_user=$s"); 
}

//=============================User Images

if(isset($_POST['user_image'])){
$sch_id = $_POST["sc_id"];
$userID = $_POST["user_id"];
if($_FILES['image']['name']){ //if image is selected
$allowed_ext =array("jpg","png","JPG","PNG");//allowed extensions
$ext = end(explode(".",$_FILES['image']['name']));//spilt file name into array
if(in_array($ext,$allowed_ext)){//check if image has allowed extenstion.
if($_FILES["image"]["size"]<1000000){//check image size 1000000 means 1000KB or 1MB
$new_name =md5(rand()).'.'.$ext;//rename file before uploding
$path ="../assets/images/users_images/".$new_name;
//if image have been uploaded then update student table
if(move_uploaded_file($_FILES['image']['tmp_name'],$path)){

//remove the first uploaded image to save space

$img_upload = "update new_users Set users_image='$new_name' where user_id='$userID'";
mysqli_query($conn,$img_upload);

header("Location:create_school_users.php?sc_id=$sch_id");

} 
} 
} 
} 
}

//========================================UPDATE ADMINISTRATOR PROFILES

if(isset($_POST['update_admin'])){   
$updated_user_id = $_POST["user_id"];
$updated_firstname = $_POST["firstname"];
$updated_lastname = $_POST["lastname"];
$updated_username= $_POST["email"];                    
$updated_phone= $_POST["phone"];
$updated_password = $_POST["password"];
$check_email = $_POST["check_email"];

if($updated_username!=$check_email ){
$same_email ="select * from new_users where username='$updated_username'";
$run = mysqli_query($conn, $same_email) or die("Could DB");
$same_email = mysqli_num_rows($run);

if ($same_email==1){ 
$s = 1;
header("Location:update_admin_profile.php?updated_user_id=$updated_user_id");

} 
else{
$teacher_query ="update new_users set firstname='$updated_firstname',lastname='$updated_lastname', 
username='$updated_username',  phone='$updated_phone', 
password='$updated_password' where user_id='$updated_user_id'";
$execute = mysqli_query($conn,$teacher_query);
header("Location: update_admin_profile.php?user_id=$updated_user_id");
} 
}

else{
$teacher_query ="update new_users set firstname='$updated_firstname',lastname='$updated_lastname', 
username='$updated_username',  phone='$updated_phone', 
password='$updated_password' where user_id='$updated_user_id'";
$execute = mysqli_query($conn,$teacher_query);
header("Location: update_admin_profile.php?user_id=$updated_user_id");
} 
}


//====================== UPDATE ADMIN IMAGES

if(isset($_POST['upload_admin_image'])){
$user_id = $_POST["user_id"];
if($_FILES['image']['name']){ //if image is selected
$allowed_ext =array("jpg","png","JPG","PNG");//allowed extensions
$ext = end(explode(".",$_FILES['image']['name']));//spilt file name into array
if(in_array($ext,$allowed_ext)){//check if image has allowed extenstion.
if($_FILES["image"]["size"]<1000000){//check image size 1000000 means 1000KB or 1MB
$new_name =md5(rand()).'.'.$ext;//rename file before uploding
$path ="../assets/images/users_images/".$new_name;
//if image have been uploaded then update student table
if(move_uploaded_file($_FILES['image']['tmp_name'],$path)){

//remove the first uploaded image to save space

$img_upload = "update new_users Set users_image='$new_name' where user_id='$user_id'";
mysqli_query($conn,$img_upload);

header("Location:update_admin_profile.php?user_id=$user_id");

} 
} 
} 
} 
?>
You have not selected the image. <a href=" admin_homepage.php"> BACK </a>    

<?php      

}  

//======================================CREATE SCHOOL USERS
if(isset($_POST['create_schoolusers'])){
$sc_id_u = $_POST['sc_id'];
$f = $_POST['fname'];
$l = $_POST['lname'];
$phone = $_POST['phone'];
$em = $_POST['email'];
$cat = $_POST['cat'];
$p = $_POST['password'];

$same_sch ="select * from new_users where username='$em'";
$run = mysqli_query($conn,$same_sch) or die("Could DB");
$same_sch = mysqli_num_rows($run);
if ($same_sch==1){
$s = 1;
header("Location: admin_homepage.php?already_user=$s"); 
}
else{
mysqli_query($conn,"INSERT INTO new_users(user_id, sch_id, firstname, lastname, phone, username, category, password) 
VALUES (NULL, '$sc_id_u', '$f', '$l', '$phone', '$em', '$cat', '$p')");
$s = 1;

header("Location:create_school_users.php?sc_id=$sc_id_u");                

} } 

//====================== UPDATE SCHOOL USERS

if(isset($_POST['update_schoolusers'])){   
$updated_user_id = $_POST["user_id"];
$updated_firstname = $_POST["fname"];
$updated_lastname = $_POST["lname"];
$updated_username= $_POST["email"]; 
$updated_phone= $_POST["phone"];                   
$updated_cat= $_POST["cat"];
$updated_password = $_POST["password"];
$user_email = $_POST["user_email"];

if($updated_username!=$user_email){
$same_email ="select * from new_users where username='$updated_username'";
$run = mysqli_query($conn, $same_email) or die("Could DB");
$same_email = mysqli_num_rows($run);

if ($same_email==1){ 
$s = 1;
header("Location:update_school_users.php?user_updated_id=$updated_user_id");

} 
else{
$teacher_query ="update new_users set 
firstname='$updated_firstname',lastname='$updated_lastname', 
username='$updated_username',  phone='$updated_phone', category='$updated_cat',
password='$updated_password' where user_id='$updated_user_id'";
$execute = mysqli_query($conn,$teacher_query);
header("Location: update_school_users.php?user_id=$updated_user_id");
} 
}

else{
$teacher_query ="update new_users set 
firstname='$updated_firstname',lastname='$updated_lastname', 
username='$updated_username',  phone='$updated_phone', category='$updated_cat',
password='$updated_password' where user_id='$updated_user_id'";
$execute = mysqli_query($conn,$teacher_query);
header("Location: update_school_users.php?user_id=$updated_user_id");
}

}



//====================== UPDATE SCHOOl USERS IMAGES

if(isset($_POST['upload_schooluser'])){
$user_id = $_POST["user_id"];
if($_FILES['image']['name']){ //if image is selected
$allowed_ext =array("jpg","png","JPG","PNG");//allowed extensions
$ext = end(explode(".",$_FILES['image']['name']));//spilt file name into array
if(in_array($ext,$allowed_ext)){//check if image has allowed extenstion.
if($_FILES["image"]["size"]<1000000){//check image size 1000000 means 1000KB or 1MB
$new_name =md5(rand()).'.'.$ext;//rename file before uploding
$path ="../assets/images/users_images/".$new_name;
//if image have been uploaded then update student table
if(move_uploaded_file($_FILES['image']['tmp_name'],$path)){

//remove the first uploaded image to save space

$img_upload = "update new_users Set users_image='$new_name' where user_id='$user_id'";
mysqli_query($conn,$img_upload);

header("Location:update_school_users.php?user_id=$user_id");

} 
} 
} 
} 
?>
You have not selected the image. <a href="admin_homepage.php"> BACK </a>    

<?php      

}  
?>
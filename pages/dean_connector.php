<?php 
include("conn.php"); 
//preventing sql injection
function prevent_sql_injection($data){
$data = stripslashes($data);
$data = htmlspecialchars($data);
$data = trim($data);
return $data;
}

$s="";
//============================STUDENT REGISTRATION 

if(isset($_POST["student"])){
$sch_id = $_POST['sc_id'];
$firstname = prevent_sql_injection(ucfirst($_POST["firstname"]));
$lastname = prevent_sql_injection(ucfirst($_POST["lastname"]));
$sex =prevent_sql_injection(ucfirst($_POST["sex"]));
$dob = prevent_sql_injection($_POST["dob"]);

$boarder_day = prevent_sql_injection(ucfirst($_POST["boarder_day"]));
$nationality = prevent_sql_injection(ucfirst($_POST["nationality"]));
$class = prevent_sql_injection(ucfirst($_POST["class"]));
$comb = prevent_sql_injection($_POST["comb"]);
$previous_school = prevent_sql_injection(ucfirst($_POST["previous_school"]));
$parent_names = prevent_sql_injection(ucfirst($_POST["parent_names"]));
$parent_phone =$_POST["parent_phone"];
$next_of_kin = prevent_sql_injection(ucfirst($_POST["next_of_kin"]));
$nextofkin_phone =$_POST["nextofkin_phone"];
$std_district = prevent_sql_injection(ucfirst($_POST["std_district"]));
$special_need = prevent_sql_injection(ucfirst($_POST["special_need"]));  
$mother = prevent_sql_injection(ucfirst($_POST["mother"])); 
$mother_tel = prevent_sql_injection(ucfirst($_POST["mother_tel"]));  
$county = prevent_sql_injection(ucfirst($_POST["county"]));
$sub_county = prevent_sql_injection(ucfirst($_POST["sub_county"]));
$parish = prevent_sql_injection(ucfirst($_POST["parish"]));

$same_student = "select * from student where firstname='$firstname' and lastname='$lastname' and dob='$dob' and comb='$comb' and class='$class' and std_sch_id='$sch_id'";
$run = mysqli_query($conn,$same_student) or die("Could not get Email");
$same_student = mysqli_num_rows($run);
if($same_student==1){
$s =0;
header("Location: register_students.php?success_reg=$s");
}
else{
$school_query = "INSERT INTO student (std_sch_id, firstname, lastname, sex, dob, boarder_day, nationality, class, comb, previous_school, parent_names, parent_phone, mother, mother_tel, next_of_kin, 
nextofkin_phone, std_district, county, sub_county, parish, special_need)

VALUES ('$sch_id','$firstname', '$lastname', '$sex', '$dob', '$boarder_day', '$nationality', '$class', '$comb', '$previous_school', '$parent_names', '$parent_phone', '$mother', '$mother_tel', '$next_of_kin',
'$nextofkin_phone', '$std_district', '$county', '$sub_county', '$parish', '$special_need')";
$execute = mysqli_query($conn,$school_query);                 
$s = 1;
header("Location: register_students.php?success_reg=$s");

}  
}


//====================== UPDATE DEAN PROFILE

if(isset($_POST['update_deanprofile'])){   
$updated_user_id = $_POST["user_id"];
$updated_firstname = $_POST["firstname"];
$updated_lastname = $_POST["lastname"];
$phone = $_POST["phone"];
$updated_email = $_POST["email"];              
$updated_password = $_POST["password"];
$user_email = $_POST["user_email"];

if($updated_email!=$user_email){

header("Location:update_dean_profile.php?updated_user_id=$updated_user_id"); 
} 

else{
$user_query ="update new_users set firstname='$updated_firstname', 
lastname='$updated_lastname', phone='$phone',  
password='$updated_password'
where user_id='$updated_user_id'";
$execute = mysqli_query($conn,$user_query);
header("Location: update_dean_profile.php?user_id=$updated_user_id");
}

}

//====================== UPDATE DEAN IMAGE

if(isset($_POST['upload_dean_image'])){
$user_id = $_POST["user_id"];
if($_FILES['image']['name']){ //if image is selected
$allowed_ext =array("jpg","png","JPG","PNG");//allowed extensions
$ext = end(explode(".",$_FILES['image']['name']));//spilt file name into array
if(in_array($ext,$allowed_ext)){//check if image has allowed extenstion.
if($_FILES["image"]["size"]<1000000){//check image size 1000000 means 1000KB or 1MB
$new_name =md5(rand()).'.'.$ext;//rename file before uploding
$path ="assets/images/users_images/".$new_name;
//if image have been uploaded then update student table
if(move_uploaded_file($_FILES['image']['tmp_name'],$path)){

//remove the first uploaded image to save space

$img_upload = "update new_users Set users_image='$new_name' where user_id='$user_id'";
mysqli_query($conn,$img_upload);

header("Location:update_dean_profile.php?user_id=$user_id");

} 
} 
} 
}      

header("Location:update_dean_profile.php?user_id=$user_id");   

}    
//===================================UPDATE STUDENT

if(isset($_POST["update_student1"])){   
$updated_std_id = $_POST["std_id"];
$sch_id = $_POST["sc_id"];
$updated_firstname = $_POST["firstname"];
$updated_lastname = $_POST["lastname"];
$updated_sex = $_POST["sex"];                   
$updated_boarder_day = $_POST["boarder_day"];
$updated_nationality = $_POST["nationality"];
$updated_dob = $_POST["dob"];
$updated_class = 0;
$updated_comb = $_POST["comb"];
$updated_previous_school = $_POST["previous_school"];
$updated_parent_names = $_POST["parent_names"];
$updated_parent_phone = $_POST["parent_phone"];
$updated_mother = $_POST["mother"];
$updated_mother_phone = $_POST["mother_phone"];
$updated_next_of_kin = $_POST["next_of_kin"];
$updated_nextofkin_phone = $_POST["nextofkin_phone"];
$updated_std_district = $_POST["std_district"];
$updated_county = $_POST["county"];
$updated_sub_county = $_POST["sub_county"];
$updated_parish = $_POST["parish"];
$updated_cell = $_POST["cell"];
$updated_special_need = $_POST["special_need"];

$student_query ="update student set firstname='$updated_firstname',lastname='$updated_lastname', 
sex='$updated_sex', boarder_day='$updated_boarder_day',
nationality='$updated_nationality', dob='$updated_dob', class='$updated_class', comb='$updated_comb',
previous_school='$updated_previous_school', parent_names='$updated_parent_names',
mother='$updated_mother', mother_tel='$updated_mother_phone',
parent_phone='$updated_parent_phone', next_of_kin='$updated_next_of_kin', 
nextofkin_phone='$updated_nextofkin_phone', std_district='$updated_std_district',
county='$updated_county', sub_county='$updated_sub_county', parish='$updated_parish', cell='$updated_cell',
special_need='$updated_special_need' where std_id='$updated_std_id' and std_sch_id='$sch_id'";
$execute = mysqli_query($conn,$student_query);
$s = 1;
header("Location: update_student_second.php?student_id=$updated_std_id");
}

// ====================================== UPDATE STUDENT IMAGE

if(isset($_POST['upload_studentimage1'])){
$std_id = $_POST["std_id"];
$sch_id = $_POST["sc_id"];
if($_FILES['image']['name']){ //if image is selected
$allowed_ext =array("jpg","png","JPG","PNG");//allowed extensions
$ext = end(explode(".",$_FILES['image']['name']));//spilt file name into array
if(in_array($ext,$allowed_ext)){//check if image has allowed extenstion.
if($_FILES["image"]["size"]<1000000){//check image size 1000000 means 1000KB or 1MB
$new_name =md5(rand()).'.'.$ext;//rename file before uploding
$path ="assets/images/students_images/".$new_name;
//if image have been uploaded then update student table
if(move_uploaded_file($_FILES['image']['tmp_name'],$path)){

//remove the first uploaded image to save space

$img_upload = "update student Set std_image='$new_name' where std_id='$std_id' and std_sch_id='$sch_id' ";
mysqli_query($conn,$img_upload);

header("Location:update_student_second.php?student_id=$std_id");

} 
} 
} 
} 
}
//=======UPDATE STUDENT CLASS LIST
if(isset($_POST["update_student"])){   
$updated_std_id = $_POST["std_id"];
$sch_id = $_POST["sc_id"];
$updated_firstname = $_POST["firstname"];
$updated_lastname = $_POST["lastname"];
$updated_sex = $_POST["sex"];                   
$updated_boarder_day = $_POST["boarder_day"];
$updated_nationality = $_POST["nationality"];
$updated_dob = $_POST["dob"];
$updated_class = 0;
$updated_comb = $_POST["comb"];
$updated_previous_school = $_POST["previous_school"];
$updated_parent_names = $_POST["parent_names"];
$updated_parent_phone = $_POST["parent_phone"];
$updated_mother = $_POST["mother"];
$updated_mother_phone = $_POST["mother_phone"];
$updated_next_of_kin = $_POST["next_of_kin"];
$updated_nextofkin_phone = $_POST["nextofkin_phone"];
$updated_std_district = $_POST["std_district"];
$updated_county = $_POST["county"];
$updated_sub_county = $_POST["sub_county"];
$updated_parish = $_POST["parish"];
$updated_cell = $_POST["cell"];
$updated_special_need = $_POST["special_need"];

$student_query ="update student set firstname='$updated_firstname',lastname='$updated_lastname', 
sex='$updated_sex', boarder_day='$updated_boarder_day',
nationality='$updated_nationality', dob='$updated_dob', class='$updated_class', comb='$updated_comb',
previous_school='$updated_previous_school', parent_names='$updated_parent_names',
mother='$updated_mother', mother_tel='$updated_mother_phone',
parent_phone='$updated_parent_phone', next_of_kin='$updated_next_of_kin', 
nextofkin_phone='$updated_nextofkin_phone', std_district='$updated_std_district',
county='$updated_county', sub_county='$updated_sub_county', parish='$updated_parish', cell='$updated_cell',
special_need='$updated_special_need' where std_id='$updated_std_id' and std_sch_id='$sch_id'";
$execute = mysqli_query($conn,$student_query);
$s = 1;
header("Location: class_lists_form.php?update=$updated_std_id");
}

// ====================================== UPDATE STUDENT IMAGE classlist

if(isset($_POST['upload_studentimage'])){
$std_id = $_POST["std_id"];
$sch_id = $_POST["sc_id"];
if($_FILES['image']['name']){ //if image is selected
$allowed_ext =array("jpg","png","JPG","PNG");//allowed extensions
$ext = end(explode(".",$_FILES['image']['name']));//spilt file name into array
if(in_array($ext,$allowed_ext)){//check if image has allowed extenstion.
if($_FILES["image"]["size"]<1000000){//check image size 1000000 means 1000KB or 1MB
$new_name =md5(rand()).'.'.$ext;//rename file before uploding
$path ="assets/images/students_images/".$new_name;
//if image have been uploaded then update student table
if(move_uploaded_file($_FILES['image']['tmp_name'],$path)){

//remove the first uploaded image to save space

$img_upload = "update student Set std_image='$new_name' where std_id='$std_id' and std_sch_id='$sch_id' ";
mysqli_query($conn,$img_upload);

header("Location:class_lists_form.php?update=$std_id");

} 
} 
} 
} 
}
//============================================= TEACHER REGISTRATION 
if(isset($_POST["teacher_account"])){
$sch_id = $_POST["sc_id"];
$firstname = prevent_sql_injection(ucfirst($_POST["firstname"]));
$lastname = prevent_sql_injection(ucfirst($_POST["lastname"]));
$sex = prevent_sql_injection(ucfirst($_POST["sex"]));
$dob = $_POST["dob"];
$q = $_POST["qualify"];
$email = prevent_sql_injection(strtolower($_POST["email"]));
$nationality = $_POST["nationality"];
$d = $_POST["district"];
$m = $_POST["marital"];
$phone = prevent_sql_injection($_POST["phone"]);
$subject1 = prevent_sql_injection(ucfirst($_POST["subject1"]));
$subject2 = prevent_sql_injection(ucfirst($_POST["subject2"]));
$others = $_POST["others"];
$password = prevent_sql_injection($_POST["password"]);
$conf_pass =$password;
$cat="User";

$check_email = "select * from teacher where email='$email'";
$run = mysqli_query($conn,$check_email) or die("Could not get Email");
$check_email = mysqli_num_rows($run);

$user_email = "select * from new_users where username='$email'";
$run = mysqli_query($conn,$user_email) or die("Could not get Email");
$user_email = mysqli_num_rows($run);

$check_phone = "select * from teacher where phone='$phone'";
$run = mysqli_query($conn,$check_phone) or die("Could not get Phone");
$check_phone = mysqli_num_rows($run);

$user_phone = "select * from new_users where phone='$phone'";
$run = mysqli_query($conn,$user_phone) or die("Could not get Phone");
$user_phone = mysqli_num_rows($run);

if(!filter_var($email, FILTER_VALIDATE_EMAIL) === true){

$error_email_validate = 1;
header("Location: register_teacher.php?success=$error_email_validate");           
}
else if($password != $conf_pass){//passwords Validation Equarity
$error_pass = 2;
header("Location: register_teacher.php?success=$error_pass");                            
}
else if(strlen($password) <6){
$error_pass_length =3;
header("Location: register_teacher.php?success=$error_pass_length");                          
}

else if($check_email ==1){
$error_alredy =4;
header("Location: register_teacher.php?success=$error_alredy"); 
       
}

else if($check_phone ==1){
$error_phone = 5;
header("Location: register_teacher.php?success=$error_phone"); 

}

else if($user_phone ==1){
$error_phone = 5;
header("Location: register_teacher.php?success=$error_phone"); 

}

else if($user_email ==1){
$error_alredy =4;
header("Location: register_teacher.php?success=$error_alredy"); 

}

else{

$school_query = "
INSERT INTO teacher (tr_sch_id, firstname, lastname, email, sex, dob, qualify, nationality, district, marital, phone, subject1, subject2, others, password) 
VALUES ('$sch_id', '$firstname', '$lastname', '$email', '$sex', '$dob', '$q', '$nationality', '$d','$m', '$phone','$subject1', '$subject2', '$others', '$password')";
$execute = mysqli_query($conn,$school_query);

$users = "
INSERT INTO new_users (sch_id, firstname, lastname, phone, username, category, password) 
VALUES ('$sch_id', '$firstname', '$lastname', '$phone', '$email', '$cat', '$password')";
$execute2 = mysqli_query($conn,$users);

if($execute){
$from_sender_email = "SMARTSCHOOL.COM";
$from_sender_message = "Congratulation your account is succesfully created with Smartschool.com, 
your username is:".$email." And Password is:".$password."&nbsp;Please Log in to your Account to Activate.";
$to_receiver_email = "$email";

$from_sender_message =str_replace("\n.", "\n..", $from_sender_message);

$to = $to_receiver_email;
$subject =$from_sender_email;
$message = $from_sender_message."<br/>".$from_sender_email;
$header ="From:".$from_sender_email.""."\r\n";
$header ="Cc:".$from_sender_email.""."\r\n";
$header .="MIME-Version:1.0"."\r\n";
$header .="Content-Type:text/html"."\r\n";

mail($to,$subject,$message,$header);
$s= 6;
header("Location: register_teacher.php?success=$s"); 
}
}
}
//==========================================UPDATE TEACHERS

if(isset($_POST["update_teacher"])){ 
$sch_id= $_POST["sc_id"];  
$user_id= $_POST["user_id"]; 
$updated_tr_id = $_POST["tr_id"];
$updated_firstname = $_POST["firstname"];
$updated_lastname = $_POST["lastname"];
$updated_sex = $_POST["sex"];                   
$email = prevent_sql_injection(strtolower($_POST["email"]));
$updated_nationality = $_POST["nationality"];
$updated_district= $_POST["district"];
$updated_dob = $_POST["dob"];
$phone = $_POST["phone"];
$updated_qualify = $_POST["qualify"];
$updated_marital = $_POST["marital"];
$updated_subject1 = $_POST["subject1"];
$updated_subject2 = $_POST["subject2"];
$updated_others = $_POST["others"];
$password = $_POST["password"];
$email2 = $_POST["email2"];
$phone2 = $_POST["phone2"];
$conf_pass =$password;

if($email!=$email2){
$check_email = "select * from teacher where email='$email'";
$run = mysqli_query($conn,$check_email) or die("Could not get Email");
$check_email = mysqli_num_rows($run);

$user_email = "select * from new_users where username='$email'";
$run = mysqli_query($conn,$user_email) or die("Could not get Email");
$user_email = mysqli_num_rows($run);
}
if($phone!=$phone2){
$check_phone = "select * from teacher where phone='$phone'";
$run = mysqli_query($conn,$check_phone) or die("Could not get Phone");
$check_phone = mysqli_num_rows($run);

$user_phone = "select * from new_users where phone='$phone'";
$run = mysqli_query($conn,$user_phone) or die("Could not get Phone");
$user_phone = mysqli_num_rows($run);
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL) === true){

$error_email_validate = 1;
header("Location: list_of_trs.php?tr_error=$error_email_validate");           
}
else if($password != $conf_pass){//passwords Validation Equarity
$error_pass = 2;
header("Location: list_of_trs.php?tr_error=$error_pass");                            
}
else if(strlen($password) <6){
$error_pass_length =3;
header("Location: list_of_trs.php?tr_error=$error_pass_length");                          
}

else if($check_email ==1){
$error_alredy =4;
header("Location: list_of_trs.php?tr_error=$error_alredy"); 
       
}

else if($check_phone ==1){
$error_phone = 5;
header("Location: list_of_trs.php?tr_error=$error_phone"); 

}

else if($user_phone ==1){
$error_phone = 5;
header("Location: list_of_trs.php?tr_error=$error_phone"); 

}

else if($user_email ==1){
$error_alredy =4;
header("Location: list_of_trs.php?tr_error=$error_alredy"); 

}

else{
$teacher_query ="update teacher set firstname='$updated_firstname',lastname='$updated_lastname', sex='$updated_sex',
dob='$updated_dob', qualify='$updated_qualify', nationality='$updated_nationality', district='$updated_district',
marital='$updated_marital',  phone='$phone', email='$email', others='$updated_others',
subject1='$updated_subject1', subject2='$updated_subject2', password='$password'
where tr_id='$updated_tr_id' and tr_sch_id='$sch_id'";
$execute = mysqli_query($conn,$teacher_query);

$users ="update new_users set firstname='$updated_firstname', lastname='$updated_lastname', phone='$phone',
username='$email', password='$password' where user_id='$user_id'";
$execute = mysqli_query($conn,$users);

$s=2;
header("Location:list_of_trs.php?tr_update=$updated_tr_id");
} 

}

//=============================================  UPLOAD TEACHER IMAGE
if(isset($_POST['upload_teacherimage'])){
$sch_id= $_POST["sc_id"]; 
$tr_id = $_POST["tr_id"];
if($_FILES['image']['name']){ //if image is selected
$allowed_ext =array("jpg","png","JPG","PNG");//allowed extensions
$ext = end(explode(".",$_FILES['image']['name']));//spilt file name into array
if(in_array($ext,$allowed_ext)){//check if image has allowed extenstion.
if($_FILES["image"]["size"]<1000000){//check image size 1000000 means 1000KB or 1MB
$new_name =md5(rand()).'.'.$ext;//rename file before uploding
$path ="assets/images/teachers_images/".$new_name;
//if image have been uploaded then update student table
if(move_uploaded_file($_FILES['image']['tmp_name'],$path)){

//remove the first uploaded image to save space

$img_upload = "update teacher Set tr_image='$new_name' where tr_id='$tr_id' and tr_sch_id='$sch_id'";
mysqli_query($conn,$img_upload);

header("Location:list_of_trs.php?tr_update=$tr_id");

} 
} 
} 
} 
}

// =====================================UPDATE CLASS LISTS
if(isset($_POST["update_class"])){   
$updated_std_id = $_POST["std_id"]; 
$sch_id = $_POST["sc_id"];                   
$updated_class = $_POST["class"];

$student_query ="update student set class='$updated_class'          
where std_id='$updated_std_id' and std_sch_id='$sch_id'";

$execute = mysqli_query($conn,$student_query);
$s = 1;
header("Location: class_lists_form.php?updated=$s"); 
}
//===============================UPDATE TEACHER PROFILE===============
//====================== ==============================================

if(isset($_POST['update_teacherprofile'])){   
$updated_user_id = $_POST["user_id"];
$updated_firstname = $_POST["firstname"];
$updated_lastname = $_POST["lastname"];
$phone = $_POST["phone"];
$updated_email = $_POST["email"];              
$updated_password = $_POST["password"];
$user_email = $_POST["user_email"];
$sch_id = $_POST["sch_id"];

if($updated_email!=$user_email){
header("Location:update_teacher_profile.php?updated_user_id=$updated_user_id"); 
} 

else{
$user_query ="update new_users set firstname='$updated_firstname', 
lastname='$updated_lastname', phone='$phone',  
password='$updated_password'
where user_id='$updated_user_id'";
$execute = mysqli_query($conn,$user_query);

$user_query ="update teacher set firstname='$updated_firstname', 
lastname='$updated_lastname', phone='$phone',  
password='$updated_password'
where tr_sch_id='$sch_id' and email='$user_email'";
$execute = mysqli_query($conn,$user_query);
header("Location: update_teacher_profile.php?user_id=$updated_user_id");
}

}

//====================== UPDATE TEACHER IMAGE

if(isset($_POST['upload_teacher_image'])){
$user_id = $_POST["user_id"];
if($_FILES['image']['name']){ //if image is selected
$allowed_ext =array("jpg","png","JPG","PNG");//allowed extensions
$ext = end(explode(".",$_FILES['image']['name']));//spilt file name into array
if(in_array($ext,$allowed_ext)){//check if image has allowed extenstion.
if($_FILES["image"]["size"]<1000000){//check image size 1000000 means 1000KB or 1MB
$new_name =md5(rand()).'.'.$ext;//rename file before uploding
$path ="assets/images/users_images/".$new_name;
//if image have been uploaded then update student table
if(move_uploaded_file($_FILES['image']['tmp_name'],$path)){

//remove the first uploaded image to save space

$img_upload = "update new_users Set users_image='$new_name' where user_id='$user_id'";
mysqli_query($conn,$img_upload);

header("Location:update_teacher_profile.php?user_id=$user_id");

} 
} 
} 
}      

header("Location:update_teacher_profile.php?user_id=$user_id");   

}    

//===========================UPADTE MARKS

if(isset($_POST["update_marks"])){   
include("conn.php");
$upstd_id = $_POST["std_id"];
$upclass= $_POST['class'];
$comb= $_POST['comb'];
$upterm=$_POST['term'];
$upyear=$_POST['year'];
$sch_id=$_POST['sch_id'];
$subject=$_POST['subject'];
$mid = $_POST["mid"];
$end = $_POST["end"];
$total_marks=$mid+$end;

if ($total_marks>=80){
$tr_comment='Excellent';
}
if ($total_marks>=70 and $total_marks<=79 ){
$tr_comment='Very Good';
}
if ($total_marks>=60 and $total_marks<=69 ){
$tr_comment='Good';
}
if ($total_marks>=50 and $total_marks<=59 ){
$tr_comment='Fair';
}
if ($total_marks<=49){
$tr_comment='Poor';
}		

if($mid>30||$end>70)              
{
$s = 0;
header("Location: marksheet_form_first.php?success_reg=$s");

}

else{
 
$upda="UPDATE marks set 
mid_term_marks='$mid', 
End_term_marks='$end', 
total_marks='$total_marks',
tr_comment='$tr_comment'
WHERE marks_class='$upclass' 
and term='$upterm'
and year='$upyear'
and subject='$subject' 
and student_id='$upstd_id'
and marks_comb='$comb'
and marks_sch_id='$sch_id'";
$execute = mysqli_query($conn, $upda);

$s = 1;
header("Location:marksheet_form_first.php?success_reg=$s");

 }
 
}
//==========UPDATE MARKS TR

if(isset($_POST["update_marks_tr"])){   
include("conn.php");
$upstd_id = $_POST["std_id"];
$upclass= $_POST['class'];
$comb= $_POST['comb'];
$tr_id= $_POST['tr_id'];
$upterm=$_POST['term'];
$upyear=$_POST['year'];
$sch_id=$_POST['sch_id'];
$subject=$_POST['subject'];
$mid = $_POST["mid"];
$end = $_POST["end"];
$total_marks=$mid+$end;

if ($total_marks>=80){
$tr_comment='Excellent';
}
if ($total_marks>=70 and $total_marks<=79 ){
$tr_comment='Very Good';
}
if ($total_marks>=60 and $total_marks<=69 ){
$tr_comment='Good';
}
if ($total_marks>=50 and $total_marks<=59 ){
$tr_comment='Fair';
}
if ($total_marks<=49){
$tr_comment='Poor';
}		

if($mid>30||$end>70)              
{
$s = 0;
header("Location: view_exam_marks_first.php?success_reg=$s");

}

else{
 
$upda="UPDATE marks set 
mid_term_marks='$mid', 
End_term_marks='$end', 
total_marks='$total_marks',
tr_comment='$tr_comment'
WHERE marks_class='$upclass' 
and term='$upterm'
and year='$upyear'
and subject='$subject' 
and student_id='$upstd_id'
and marks_comb='$comb'
and tr_id='$tr_id'
and marks_sch_id='$sch_id'";
$execute = mysqli_query($conn, $upda);

$s = 1;
header("Location:view_exam_marks_first.php?success_reg=$s");

 }
 
}


//===============UPDATE TEST MARKS TRS

if(isset($_POST["update_test_tr"])){   
include("conn.php");
$upstd_id = $_POST["std_id"];
$upclass= $_POST['class'];
$comb= $_POST['comb'];
$upterm=$_POST['term'];
$upyear=$_POST['year'];
$sch_id=$_POST['sch_id'];
$subject=$_POST['subject'];
$tr_id = $_POST["tr_id"];
$test_no = $_POST["test_no"];
$marks = $_POST["marks"];

$upda="UPDATE test_marks set 
test_marks='$marks'
WHERE test_marks_class='$upclass' 
and test_term='$upterm'
and test_year='$upyear'
and test_subject='$subject' 
and test_student_id='$upstd_id'
and test_comb='$comb'
and test_no='$test_no'
and test_tr_id='$tr_id'
and test_sch_id='$sch_id'";
$execute = mysqli_query($conn, $upda);

$s = 1;
header("Location:tr_view_test_marks_first.php?success_reg=$s");

 
 
}



//==================ENTER FEES
if(isset($_POST["submit_fees"])){   
$std_id = $_POST["std_id"]; 
$sch_id = $_POST["sch_id"]; 
$term = $_POST["term"]; 
$class = $_POST["class"];                  
$boarder_day = $_POST["boarder_day"];
$amount = $_POST["amount"]; 
$year=date("Y");
$date=date("Y-m-d");
// Fees settings
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from fees_settings 
where fee_sch_id='$sch_id' and term='$term' and fees_class='$class' and year='$year'"));     
$fees_boarder= $result['fees'];
$fees_day= $result['fees_day'];
//Get balance
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from fees_std_settings 
where sch_id='$sch_id' and stud_id='$std_id' and term='$term' and year='$year' and fee_class='$class'"));     
$old_balance= $result['balance'];
$new_balance=$old_balance-$amount;

if($amount>$fees_boarder && $boarder_day=="Boarder"){
header("Location: bursar_homepage.php?error=$fees_boarder"); 
} 

else if($amount>$fees_boarder && $boarder_day=="Day"){
header("Location: bursar_homepage.php?error=$fees_day"); 
}

else if($old_balance==0){
header("Location: bursar_homepage.php?already_paid"); 
}

else if($amount>$old_balance){
header("Location: bursar_homepage.php?error=$old_balance"); 
}
else{
mysqli_query($conn,"INSERT INTO fees(fees_id, fees_std_id, feese_class, fees_sch_id, fees_date, fees_amount,
term, year, fees_balance) VALUES (NULL, '$std_id', '$class', '$sch_id', '$date', '$amount','$term', '$year', '$new_balance')");

$upda="UPDATE fees_std_settings set balance=$new_balance
WHERE sch_id='$sch_id' and stud_id='$std_id' and term='$term' and year='$year'";
$execute = mysqli_query($conn, $upda);

header("Location: bursar_homepage.php?success=$new_balance");

}
}
//===================ENTER FEES for Model============
//===============students with debts=====
if(isset($_POST["save_fees"])){   
$std_id = $_POST["std_id"]; 
$sch_id = $_POST["sch_id"]; 
$term = $_POST["term"]; 
$class = $_POST["class"];                  
$boarder_day = $_POST["boarder_day"];
$amount = $_POST["amount"]; 
$year=date("Y");
$date=date("Y-m-d");
// Fees settings
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from fees_settings 
where fee_sch_id='$sch_id' and term='$term' and fees_class='$class' and year='$year'"));     
$fees_boarder= $result['fees'];
$fees_day= $result['fees_day'];
//Get balance
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from fees_std_settings 
where sch_id='$sch_id' and stud_id='$std_id' and term='$term' and year='$year' and fee_class='$class'"));     
$old_balance= $result['balance'];
$new_balance=$old_balance-$amount;

if($amount>$fees_boarder && $boarder_day=="Boarder"){
header("Location: student_with_debt.php?error=$fees_boarder"); 
} 

else if($amount>$fees_boarder && $boarder_day=="Day"){
header("Location: student_with_debt.php?error=$fees_day"); 
}

else if($old_balance==0){
header("Location: student_with_debt.php?already_paid"); 
}

else if($amount>$old_balance){
header("Location: student_with_debt.php?error=$old_balance"); 
}
else{
mysqli_query($conn,"INSERT INTO fees(fees_id, fees_std_id, feese_class, fees_sch_id, fees_date, fees_amount,
term, year, fees_balance) VALUES (NULL, '$std_id', '$class', '$sch_id', '$date', '$amount','$term', '$year', '$new_balance')");

$upda="UPDATE fees_std_settings set balance=$new_balance
WHERE sch_id='$sch_id' and stud_id='$std_id' and term='$term' and year='$year'";
$execute = mysqli_query($conn, $upda);

header("Location:student_with_debt.php?success=$new_balance");

}
}

//======================Check the Balance
if(isset($_POST["check_balance"])){   
$std_id = $_POST["std_id"]; 
$sch_id = $_POST["sch_id"]; 
$term = $_POST["term"];                   
$year=date("Y");
 
// Fees settings
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from fees_std_settings 
where sch_id='$sch_id' and stud_id='$std_id' and term='$term' and year='$year'"));     
$balance= $result['balance'];

header("Location: bursar_homepage.php?check_balances=$balance");
}
//========================UPDATE HEAD MASTER PROFILE

if(isset($_POST['update_headprofile'])){   
$updated_user_id = $_POST["user_id"];
$updated_firstname = $_POST["firstname"];
$updated_lastname = $_POST["lastname"];
$phone = $_POST["phone"];
$updated_email = $_POST["email"];              
$updated_password = $_POST["password"];
$user_email = $_POST["user_email"];

if($updated_email!=$user_email){
header("Location:update_head_profile.php?updated_user_id=$updated_user_id"); 
} 

else{
$user_query ="update new_users set firstname='$updated_firstname', 
lastname='$updated_lastname', 
phone='$phone',  
password='$updated_password'
where user_id='$updated_user_id'";
$execute = mysqli_query($conn,$user_query);
header("Location: update_head_profile.php?user_id=$updated_user_id");
}

}

//====================== UPDATE HEAD MASTER IMAGE

if(isset($_POST['upload_head_image'])){
$user_id = $_POST["user_id"];
if($_FILES['image']['name']){ //if image is selected
$allowed_ext =array("jpg","png","JPG","PNG");//allowed extensions
$ext = end(explode(".",$_FILES['image']['name']));//spilt file name into array
if(in_array($ext,$allowed_ext)){//check if image has allowed extenstion.
if($_FILES["image"]["size"]<1000000){//check image size 1000000 means 1000KB or 1MB
$new_name =md5(rand()).'.'.$ext;//rename file before uploding
$path ="assets/images/users_images/".$new_name;
//if image have been uploaded then update student table
if(move_uploaded_file($_FILES['image']['tmp_name'],$path)){

//remove the first uploaded image to save space

$img_upload = "update new_users Set users_image='$new_name' where user_id='$user_id'";
mysqli_query($conn,$img_upload);

header("Location:update_head_profile.php?user_id=$user_id");

} 
} 
} 
}      
header("Location:update_head_profile.php?user_id=$user_id");   
} 


//===========================UPADTE BURSAR PROFILE


if(isset($_POST['update_bursarprofile'])){   
$updated_user_id = $_POST["user_id"];
$updated_firstname = $_POST["firstname"];
$updated_lastname = $_POST["lastname"];
$phone = $_POST["phone"];
$updated_email = $_POST["email"];              
$updated_password = $_POST["password"];
$user_email = $_POST["user_email"];

if($updated_email!=$user_email){
 
header("Location:update_bursar_profile.php?updated_user_id=$updated_user_id"); 
}

else{
$user_query ="update new_users set firstname='$updated_firstname', 
lastname='$updated_lastname', phone='$phone', 
password='$updated_password'
where user_id='$updated_user_id'";
$execute = mysqli_query($conn,$user_query);
header("Location: update_bursar_profile.php?user_id=$updated_user_id");
}

}

//====================== UPDATE bursar IMAGE

if(isset($_POST['upload_bursar_image'])){
$user_id = $_POST["user_id"];
if($_FILES['image']['name']){ //if image is selected
$allowed_ext =array("jpg","png","JPG","PNG");//allowed extensions
$ext = end(explode(".",$_FILES['image']['name']));//spilt file name into array
if(in_array($ext,$allowed_ext)){//check if image has allowed extenstion.
if($_FILES["image"]["size"]<1000000){//check image size 1000000 means 1000KB or 1MB
$new_name =md5(rand()).'.'.$ext;//rename file before uploding
$path ="assets/images/users_images/".$new_name;
//if image have been uploaded then update student table
if(move_uploaded_file($_FILES['image']['tmp_name'],$path)){

//remove the first uploaded image to save space

$img_upload = "update new_users Set users_image='$new_name' where user_id='$user_id'";
mysqli_query($conn,$img_upload);

header("Location:update_bursar_profile.php?user_id=$user_id");

} 
} 
} 
}      
header("Location:update_bursar_profile.php?user_id=$user_id");   
} 

//====================ADD BOOKS

if(isset($_POST["add_book"])){
$sch_id = $_POST['sc_id'];
$bookno = prevent_sql_injection(ucfirst($_POST["bookno"]));
$title = prevent_sql_injection(ucfirst($_POST["title"]));
$cat =prevent_sql_injection(ucfirst($_POST["cat"]));
$class = prevent_sql_injection($_POST["class"]);
$subject = prevent_sql_injection(ucfirst($_POST["subject"]));
$shelf = prevent_sql_injection(ucfirst($_POST["shelf"]));
$book_level=0;
$Auther=0; 
$publisher=0;
$book_year=0;
$edition=0;
$out_book=1;

$same_book = "select * from books where book_sch_id ='$sch_id' and book_no='$bookno'
and category='$cat' and title='$title' and book_class ='$class'";
$run = mysqli_query($conn,$same_book) or die("Could not get db");
$same_book = mysqli_num_rows($run);
if($same_book==1){
$s =0;
header("Location: add_books.php?already=$s");
}
else{
$school_query = "INSERT INTO books (book_sch_id, book_no, category, title, Auther, publisher, 
book_level, book_class, book_subject, book_year, edition, shelf, out_book)

VALUES ('$sch_id','$bookno', '$cat', '$title', '$Auther', '$publisher', '$book_level', '$class', '$subject', 
'$book_year', '$edition', '$shelf', '$out_book')";
$execute = mysqli_query($conn,$school_query);                 
$s = 1;
header("Location: add_books.php?success_reg=$s");

}  
}
//==================UPDATE BOOKS
if(isset($_POST['update_books'])){   
$book_no = $_POST["book_no"];
$bookno = $_POST["bookno"];
$category = $_POST["category"];
$title = $_POST["title"];
$book_class = $_POST["class"];
$book_subject = $_POST["subject"];              
$shelf = $_POST["shelf"];
$sch_id = $_POST["sch_id"];

if($book_no!=$bookno){
$same_no ="select * from books where book_no='$book_no'";
$run = mysqli_query($conn, $same_no) or die("Could DB");
$same_no = mysqli_num_rows($run);

if ($same_no==1){ 
$s = 1;
header("Location:update_book.php?updated_book_no=$bookno"); 
} 
else{
$user_query ="update books set book_no='$book_no', 
category='$category',
title='$title',
book_class='$book_class',  
book_subject='$book_subject',
shelf='$shelf'
where book_no='$bookno' and book_sch_id='$sch_id'";
$execute = mysqli_query($conn,$user_query);
header("Location: update_book.php?book_no=$book_no");
} 
}

else{
$user_query ="update books set book_no='$book_no', 
category='$category',
title='$title',
book_class='$book_class',  
book_subject='$book_subject',
shelf='$shelf'
where book_no='$book_no' and book_sch_id='$sch_id'";
$execute = mysqli_query($conn,$user_query);
header("Location: update_book.php?book_no=$book_no");
}

}
 
//==================STUDENTS BORROWING BOOKS
if(isset($_POST["submit_book"])){   
$std_id = $_POST["std_id"];
$sch_id = $_POST["sch_id"];  
$term = $_POST["term"];                   
$book_no = $_POST["book_no"]; 
$date=date("Y-m-d");
$returned=0;
//  check whether the book is not borrowed
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from books
where book_sch_id='$sch_id' and book_no='$book_no'"));     
$book_no2= $result['book_no'];
$borrowed= $result['out_book'];

if($book_no2!=$book_no){
header("Location: library_homepage.php?error=$book_no2"); 
} 

else if($borrowed==0){
header("Location: library_homepage.php?borrowed"); 
}

else{
mysqli_query($conn,"INSERT INTO borrow(borrow_id, student_no, bookNo, term, borrow_date, returned)
VALUES (NULL, '$std_id', '$book_no2', '$term', '$date', '$returned')");

$upda="UPDATE books set out_book=0
WHERE book_sch_id ='$sch_id' and book_no='$book_no2'";
$execute = mysqli_query($conn, $upda);

header("Location: library_homepage.php?success");

}
}

//=============RETURNING BOOKS=======
 
if(isset($_POST["return_book"])){   
$std_id = $_POST["std_id"];
$sch_id = $_POST["sch_id"];  
$term = $_POST["term"];                   
$book_no = $_POST["book_no"]; 
$date=date("Y-m-d");
 
//  check whether the book is not borrowed
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from books
where book_sch_id='$sch_id' and book_no='$book_no'"));     
$book_no2= $result['book_no'];
$borrowed= $result['out_book'];

$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from borrow where student_no='$std_id' and bookNo='$book_no' and returned=0"));     
$bookNo= $result['bookNo'];
$borrow_date = $result['borrow_date'];

if($book_no2!=$book_no){
header("Location: library_homepage.php?error=$book_no2"); 
} 

else if($bookNo!=$book_no){
header("Location: library_homepage.php?not_borrowed"); 
}

else{
mysqli_query($conn,"INSERT INTO returne(return_id, studentNo, book_No, term, return_date)
VALUES (NULL, '$std_id', '$book_no2', '$term', '$date')");

mysqli_query($conn,"INSERT INTO borrow_statement(borrow_id, borrow_sch_id, student_no, bookNo, borrow_date, retunee_date)
VALUES (NULL, '$sch_id', '$std_id', '$book_no2', '$borrow_date', '$date')");

$upda="UPDATE books set out_book=1
WHERE book_sch_id ='$sch_id' and book_no='$book_no2'";
$execute = mysqli_query($conn, $upda);

$upda="UPDATE borrow set returned=1
WHERE student_no='$std_id' and bookNo='$book_no2'";
$execute = mysqli_query($conn, $upda);

header("Location: library_homepage.php?success_ret");

}
}

//==================TEACHERS BORROWING BOOKS

if(isset($_POST["submit_book_tr"])){   
$tr_id = $_POST["tr_id"];
$sch_id = $_POST["sch_id"];  
$term = $_POST["term"];                   
$book_no = $_POST["book_no"]; 
$date=date("Y-m-d");
$returned=0;
//  check whether the book is not borrowed
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from books
where book_sch_id='$sch_id' and book_no='$book_no'"));     
$book_no2= $result['book_no'];
$borrowed= $result['out_book'];

if($book_no2!=$book_no){
header("Location: lib_teachers.php?error=$book_no2"); 
} 

else if($borrowed==0){
header("Location: lib_teachers.php?borrowed"); 
}

else{
mysqli_query($conn,"INSERT INTO borrow_tr(borrow_id, tr_no, bookNo, term, borrow_date, returned)
VALUES (NULL, '$tr_id', '$book_no2', '$term', '$date', '$returned')");

$upda="UPDATE books set out_book=0
WHERE book_sch_id ='$sch_id' and book_no='$book_no2'";
$execute = mysqli_query($conn, $upda);

header("Location: lib_teachers.php?success");

}
}
//=============TEACHERS RETURNING BOOKS=======
 
if(isset($_POST["return_book_tr"])){   
$tr_id = $_POST["tr_id"];
$sch_id = $_POST["sch_id"];  
$term = $_POST["term"];                   
$book_no = $_POST["book_no"]; 
$date=date("Y-m-d");
 
//  check whether the book is not borrowed
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from books
where book_sch_id='$sch_id' and book_no='$book_no'"));     
$book_no2= $result['book_no'];
$borrowed= $result['out_book'];

$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from borrow_tr where tr_no='$tr_id' and bookNo='$book_no' and returned=0"));     
$bookNo= $result['bookNo'];
$borrow_date = $result['borrow_date'];

if($book_no2!=$book_no){
header("Location: lib_teachers.php?error=$book_no2"); 
} 

else if($bookNo!=$book_no){
header("Location: lib_teachers.php?not_borrowed"); 
}

else{
mysqli_query($conn,"INSERT INTO returne_tr(return_id, teacherNo, book_No, term, return_date)
VALUES (NULL, '$tr_id', '$book_no2', '$term', '$date')");

mysqli_query($conn,"INSERT INTO borrow_statement_tr(borrow_id, borrow_sch_id, tr_no, bookNo, borrow_date, returne_date)
VALUES (NULL, '$sch_id', '$tr_id', '$book_no2', '$borrow_date', '$date')");

$upda="UPDATE books set out_book=1
WHERE book_sch_id ='$sch_id' and book_no='$book_no2'";
$execute = mysqli_query($conn, $upda);

$upda="UPDATE borrow_tr set returned=1
WHERE tr_no='$tr_id' and bookNo='$book_no2'";
$execute = mysqli_query($conn, $upda);

header("Location: lib_teachers.php?success_ret");

}
}


//===========================UPADTE LIBRARIAN PROFILE


if(isset($_POST['update_libprofile'])){   
$updated_user_id = $_POST["user_id"];
$updated_firstname = $_POST["firstname"];
$updated_lastname = $_POST["lastname"];
$phone = $_POST["phone"];
$updated_email = $_POST["email"];              
$updated_password = $_POST["password"];
$user_email = $_POST["user_email"];

if($updated_email!=$user_email){
header("Location:update_librarian_profile.php?updated_user_id=$updated_user_id"); 
} 

else{
$user_query ="update new_users set firstname='$updated_firstname', 
lastname='$updated_lastname', phone='$phone',  
password='$updated_password'
where user_id='$updated_user_id'";
$execute = mysqli_query($conn,$user_query);
header("Location: update_librarian_profile.php?user_id=$updated_user_id");
}

}

//====================== UPDATE bursar IMAGE

if(isset($_POST['upload_lib_image'])){
$user_id = $_POST["user_id"];
if($_FILES['image']['name']){ //if image is selected
$allowed_ext =array("jpg","png","JPG","PNG");//allowed extensions
$ext = end(explode(".",$_FILES['image']['name']));//spilt file name into array
if(in_array($ext,$allowed_ext)){//check if image has allowed extenstion.
if($_FILES["image"]["size"]<1000000){//check image size 1000000 means 1000KB or 1MB
$new_name =md5(rand()).'.'.$ext;//rename file before uploding
$path ="assets/images/users_images/".$new_name;
//if image have been uploaded then update student table
if(move_uploaded_file($_FILES['image']['tmp_name'],$path)){

//remove the first uploaded image to save space

$img_upload = "update new_users Set users_image='$new_name' where user_id='$user_id'";
mysqli_query($conn,$img_upload);

header("Location:update_librarian_profile.php?user_id=$user_id");

} 
} 
} 
}      
header("Location:update_bursar_profile.php?user_id=$user_id");   
} 

//================ADD LEFT STUDENTS=========


if(isset($_POST['left_student'])){
$class = $_POST['class'];
$sch_id = $_POST['sch_id'];
$yr = date("Y");
$year=$yr-1;
 
$mana=mysqli_query($conn, "SELECT std_id FROM student, std_classes WHERE
class_name='$class' and std_sch_id='$sch_id' and std_id=student_id order by std_id");
$no_students = mysqli_num_rows($mana);
if ($no_students==0){
$s = 1;
header("Location: add_left_student_form.php?success_reg=$s"); 
}
$same_data ="SELECT * from left_student where left_class='$class' and left_sch_id='$sch_id' and left_year='$year'";
$run = mysqli_query($conn,$same_data) or die("Could get DB");
$same_data = mysqli_num_rows($run);
if ($same_data>=1){
$s = 2;
header("Location: add_left_student_form.php?success_reg=$s"); 
}
else{
$student=mysqli_query($conn, "SELECT std_id FROM student, std_classes WHERE
class_name='$class' and std_sch_id='$sch_id' and std_id=student_id order by std_id");
while($finda=mysqli_fetch_array($student)){
 
$stdcode=$finda["std_id"];
mysqli_query($conn,"INSERT INTO left_student(left_id, left_sch_id, left_std_id,
left_class, left_year) 
VALUES (NULL, '$sch_id', '$stdcode', '$class', '$year')");

$upda="UPDATE student set left_student=1
WHERE std_sch_id='$sch_id'
and std_id='$stdcode'";
mysqli_query($conn, $upda);
$s=0;
header("Location: add_left_student_form.php?success_reg=$s");            
} 
}
}
//===============UN DO CAHNGES OF ADDING CANDIDATES TO LEFT TABLE======

if(isset($_POST['undo'])){
$class = $_POST['class'];
$sch_id = $_POST['sch_id'];
$yr = date("Y");
$year=$yr-1;

$same_data ="SELECT * from left_student where left_class='$class' and left_sch_id='$sch_id' and left_year='$year'";
$run = mysqli_query($conn,$same_data) or die("Could get DB");
$same_data = mysqli_num_rows($run);
if ($same_data==0){
$s = 3;
header("Location: add_left_student_form.php?success_reg=$s"); 
}
else{
$student=mysqli_query($conn, "SELECT std_id FROM student, std_classes WHERE
class_name='$class' and std_sch_id='$sch_id' and std_id=student_id order by std_id");
while($finda=mysqli_fetch_array($student)){
 
$stdcode=$finda["std_id"];

$upda="UPDATE student set left_student=0
WHERE std_sch_id='$sch_id'
and std_id='$stdcode'";
mysqli_query($conn, $upda);
}

$delete ="DELETE from left_student where left_class='$class' and left_sch_id='$sch_id' and left_year='$year'";
$run = mysqli_query($conn,$delete) or die("Could get DB");    
$s = 4;
header("Location: add_left_student_form.php?success_reg=$s");    
}
}

//===============UN DO PROMOTIONS======

if(isset($_POST['undo_promotion'])){
$class = $_POST['class'];
$sch_id = $_POST['sch_id'];
$year = date("Y");
 

$same_data ="SELECT * from std_classes where class_name='$class' and 
class_schc_id='$sch_id' and year='$year'";
$run = mysqli_query($conn, $same_data) or die("Could get DB");
$same_data = mysqli_num_rows($run);
if ($same_data==0){
$s = 1;
header("Location: cancel_promotion.php?success_reg=$s"); 
}
else
{
$delete ="DELETE from std_classes where class_schc_id='$sch_id' and class_name='$class'
and year='$year'";
$run = mysqli_query($conn, $delete) or die("Could get DB");    
$s = 2;
header("Location: cancel_promotion.php?undo_promotions=$s");    
}
}

//==============GET REPEATED STUDENTS====
if(isset($_POST['undo_repeated'])){
$class = $_POST['class'];
$sch_id = $_POST['sch_id'];
$year = date("Y");
 

$same_data ="SELECT * from std_classes where class_name='$class' and 
class_schc_id='$sch_id' and year='$year' and repeati='REPEATED'";
$run = mysqli_query($conn, $same_data) or die("Could get DB");
$same_data = mysqli_num_rows($run);
if ($same_data==0){
$s = 1;
header("Location: cancel_promotion.php?success_reg=$s"); 
}
else
{
$delete ="DELETE from std_classes where class_schc_id='$sch_id' and class_name='$class'
and year='$year' and repeati='REPEATED'";
$run = mysqli_query($conn, $delete) or die("Could get DB");    
$s = 4;
header("Location: cancel_promotion.php?undo_promotions=$s");    
}
}

//============SAVE TEST MARKS
if(isset($_POST['save_marks'])){
$class=$_POST['class'];
$subject=$_POST['subject'];
$term=$_POST['term'];
$year=$_POST['year'];
$tr_id=$_POST['tr_id'];
$comb=$_POST['comb'];
$test_no=$_POST['test_no'];
$max1=$_POST['max1'];
$marks_entered=1; 
$student_id = $_POST['stud_id'];
$sch_id = $_POST['sch_id'];
$marks = $_POST['marks'];
$date=date("Y-m-d");
 

for($i=0;$i<count($student_id); $i++){
$same_marks = "select * from test_marks where test_marks_class='$class' and test_comb='$comb' AND test_sch_id='$sch_id' and test_no='$test_no'
and test_year='$year'  and test_term='$term' and test_subject='$subject' and test_student_id='$student_id[$i]'";
$run = mysqli_query($conn,$same_marks) or die("Could not get Email");
$same_marks = mysqli_num_rows($run);
if($same_marks==1){
header("Location: enter_marks_test.php?error_already");
break;
}

else{

$total_marks=$marks[$i];

if ($total_marks>=80){
$tr_comment='Excellent';
}
if ($total_marks>=70 and $total_marks<=79 ){
$tr_comment='Very Good';
}
if ($total_marks>=60 and $total_marks<=69 ){
$tr_comment='Good';
}
if ($total_marks>=50 and $total_marks<=59 ){
$tr_comment='Fair';
}
if ($total_marks<=49){
$tr_comment='Poor';
}		

mysqli_query($conn,"INSERT INTO test_marks(test_marks_id, test_sch_id, test_student_id,
test_date, test_term, test_year, test_marks_class, test_comb, test_subject, test_tr_id,
test_marks, test_max, test_no, test_comment, marks_entered)
VALUES (NULL, '$sch_id', '$student_id[$i]', '$date', ' $term', '$year','$class', '$comb',
'$subject',  '$tr_id', '$marks[$i]', '$max1', '$test_no', '$tr_comment', '$marks_entered')");
          
}
}
header("Location: enter_marks_test.php?success");
}	

//===============ENTER EXAM MARKS

if(isset($_POST['enter_marks'])){
$class=$_POST['class'];
$subject=$_POST['subject'];
$term=$_POST['term'];
$year=$_POST['year'];
$tr_id=$_POST['tr_id'];
$comb=$_POST['comb'];
$marks_entered=1; 
$student_id = $_POST['stud_id'];
$schs_id = $_POST['sch_id'];
$mid_term_marks = $_POST['mid'];
$End_term_marks = $_POST['end'];

for($i=0;$i<count($student_id); $i++){
$total_marks=$mid_term_marks[$i]+$End_term_marks[$i];

if ($total_marks>=80){
$tr_comment='Excellent';
}
if ($total_marks>=70 and $total_marks<=79 ){
$tr_comment='Very Good';
}
if ($total_marks>=60 and $total_marks<=69 ){
$tr_comment='Good';
}
if ($total_marks>=50 and $total_marks<=59 ){
$tr_comment='Fair';
}
if ($total_marks<=49){
$tr_comment='Poor';
}		

mysqli_query($conn,"INSERT INTO marks(marks_id, marks_sch_id, student_id, term, year, marks_class, marks_comb, subject, tr_id, cwork, mid_term_marks, End_term_marks, total_marks, tr_comment, marks_entered)
VALUES (NULL, '$schs_id', '$student_id[$i]', ' $term', '$year','$class', '$comb', '$subject',  '$tr_id', '$cwork[$i]', '$mid_term_marks[$i]', '$End_term_marks[$i]', '$total_marks', '$tr_comment', '$marks_entered')");

header("Location: enter_marks_both.php?success");
           
}
}		
//=================ENTER END ONLY
if(isset($_POST['enter_marks_end'])){
$class=$_POST['class'];
$subject=$_POST['subject'];
$term=$_POST['term'];
$year=$_POST['year'];
$tr_id=$_POST['tr_id'];
$comb=$_POST['comb'];
$marks_entered=1; 
$student_id = $_POST['stud_id'];
$schs_id = $_POST['sch_id'];
$mid_term_marks = $_POST['mid'];
$End_term_marks = $_POST['end'];

for($i=0;$i<count($student_id); $i++){
$total_marks=$mid_term_marks[$i]+$End_term_marks[$i];

if ($total_marks>=80){
$tr_comment='Excellent';
}
if ($total_marks>=70 and $total_marks<=79 ){
$tr_comment='Very Good';
}
if ($total_marks>=60 and $total_marks<=69 ){
$tr_comment='Good';
}
if ($total_marks>=50 and $total_marks<=59 ){
$tr_comment='Fair';
}
if ($total_marks<=49){
$tr_comment='Poor';
}		

mysqli_query($conn,"INSERT INTO marks(marks_id, marks_sch_id, student_id, term, year, marks_class, marks_comb, subject, tr_id, cwork, mid_term_marks, End_term_marks, total_marks, tr_comment, marks_entered)
VALUES (NULL, '$schs_id', '$student_id[$i]', ' $term', '$year','$class', '$comb', '$subject',  '$tr_id', '$cwork[$i]', '$mid_term_marks[$i]', '$End_term_marks[$i]', '$total_marks', '$tr_comment', '$marks_entered')");

header("Location: enter_end_only_first.php?success");
           
}
}		
?>




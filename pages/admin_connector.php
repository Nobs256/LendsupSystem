<?php 
include("conn.php"); 
//preventing sql injection
function prevent_sql_injection($data){
$data = stripslashes($data);
$data = htmlspecialchars($data);
$data = trim($data);
return $data;
}
 //======================================CREATE USERS
if(isset($_POST['add_users'])){
 
$f = $_POST['names'];
$l = $_POST['amount'];
 
 
 
mysqli_query($conn,"INSERT INTO new_users(test_id, names,  amount) 
VALUES (NULL, '$f', '$l')");
$s = 1;

header("Location:admin_homepage.php?success");                

} 
//======================================CREATE USERS
if(isset($_POST['add_users'])){
$boss_id = $_POST['boss_id'];
$f = $_POST['fname'];
$l = $_POST['lname'];
$phone = $_POST['phone'];
$branch = $_POST['branch'];
$cat = "User";
$sex = $_POST['sex'];
$marital = $_POST['marital'];
$place_o = $_POST['place_o'];
$place_r = $_POST['place_r'];
$nid = $_POST['nid'];
$dob = $_POST['year'];
$username = $_POST['username'];
$p = $_POST['password'];
$active=1;

$same_user ="select * from new_users where username='$username'";
$run = mysqli_query($conn, $same_user) or die("Could DB");
$same_user = mysqli_num_rows($run);
if ($same_user==1){
$s = 1;
header("Location: add_users.php?already_user"); 
}
else{
mysqli_query($conn,"INSERT INTO new_users(user_id, boss_id, firstname, lastname, sex, marital,
	phone, nid, branch, place_o, place_r, dob, username, category, password, active) 
VALUES (NULL, '$boss_id', '$f', '$l', '$sex', '$marital', '$phone', '$nid', '$branch', 
'$place_o', '$place_r', '$dob', '$username', '$cat', '$p', '$active')");
$s = 1;

header("Location:add_users.php?success");                

} } 




 
//====================== UPDATE   USERS

if(isset($_POST['update_users'])){   
$updated_user_id = $_POST["user_id"];
$updated_firstname = $_POST["fname"];
$updated_lastname = $_POST["lname"];
$username= $_POST["username1"]; 
$updated_phone= $_POST["phone"]; 
$updated_branch= $_POST["branch"];                   
$updated_place_r= $_POST["place_r"];
$updated_password = $_POST["password"];
$changed_username = $_POST["username2"];

if($changed_username!=$username){
$same_email ="select * from new_users where username='$changed_username'";
$run = mysqli_query($conn, $same_email) or die("Could DB");
$same_email = mysqli_num_rows($run);

if ($same_email==1){ 
$s = 1;
header("Location:update_users.php?user_updated_id=$updated_user_id");

} 
else{
$teacher_query ="update new_users set 
firstname='$updated_firstname',lastname='$updated_lastname', 
username='$changed_username',  phone='$updated_phone', branch='$updated_branch', place_r='$updated_place_r',
password='$updated_password' where user_id='$updated_user_id'";
$execute = mysqli_query($conn,$teacher_query);
header("Location: update_users.php?user_id=$updated_user_id");
} 
}

else{
$teacher_query ="update new_users set 
firstname='$updated_firstname',lastname='$updated_lastname', 
username='$username',  phone='$updated_phone', branch='$updated_branch', place_r='$updated_place_r',
password='$updated_password' where user_id='$updated_user_id'";
$execute = mysqli_query($conn,$teacher_query);
header("Location: update_users.php?user_id=$updated_user_id");
} 
}



//====================== UPDATE SCHOOl USERS IMAGES

if(isset($_POST['upload_user'])){
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

header("Location:update_users.php?user_id=$user_id");

} 
} 
} 
}
}

if(isset($_GET['delete_user'])){
$user_id = $_GET['delete_user'];
$ac=0;
$query ="update new_users set 
active='$ac' where user_id='$user_id'";
$execute = mysqli_query($conn, $query);
$s = 1;

header("Location:add_users.php?delete_user=$s"); 
}

//================UPDATE User PROFILE

if(isset($_POST['update_userprofile'])){   
$updated_user_id = $_POST["user_id"];
$updated_firstname = $_POST["firstname"];
$updated_lastname = $_POST["lastname"];
$phone = $_POST["phone"]; 
$boss_id = $_POST["boss_id"];           
$updated_password = $_POST["password"]; 
 
 
$user_query ="update new_users set firstname='$updated_firstname', 
lastname='$updated_lastname', phone='$phone',  
password='$updated_password'
where user_id='$updated_user_id' and boss_id='$boss_id'";
$execute = mysqli_query($conn,$user_query);
header("Location: admin_profile.php?user_id=$updated_user_id");
 

}

//====================== UPDATE user IMAGE

if(isset($_POST['upload_user_image'])){
$user_id = $_POST["user_id"];
$boss_id = $_POST["boss_id"];
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

$img_upload = "update new_users Set users_image='$new_name' where user_id='$user_id' and boss_id='$boss_id'";
mysqli_query($conn,$img_upload);

header("Location:admin_profile.php?user_id=$user_id");

} 
} 
} 
}      

header("Location:admin_profile.php?user_id=$user_id");   

}    
//=======================UPDATE FIELD OFFICERS

if(isset($_POST['update_officers'])){   
$updated_officer_id = $_POST["officer_id"];
$updated_firstname = $_POST["fname"];
$updated_lastname = $_POST["lname"];
$updated_phone= $_POST["phone"]; 
$updated_branch= $_POST["branch"];                   
$updated_location= $_POST["b_location"];
 
$officer_query ="update officers set 
firstname='$updated_firstname',lastname='$updated_lastname', 
phone='$updated_phone', branch='$updated_branch', location='$updated_location' where officer_id='$updated_officer_id'";
$execute = mysqli_query($conn, $officer_query);
header("Location: update_officer.php?officer_id=$updated_officer_id&success");
} 

//======================================LOACTION
if(isset($_POST['location'])){
$boss_id = $_POST['boss_id'];
$user_id = $_POST['user_id'];
$loc = $_POST['loc'];
 

$same_loc ="select * from location where location='$loc' and userloc_id='$user_id' and bossloc_id='$boss_id'";
$run = mysqli_query($conn, $same_loc) or die("Could DB");
$same_loc = mysqli_num_rows($run);
if ($same_loc==1){
$s = 1;
header("Location: add_location_admin.php?already_location"); 
}
else{
mysqli_query($conn,"INSERT INTO location(location_id, userloc_id, bossloc_id, location) 
VALUES (NULL, '$user_id', '$boss_id', '$loc')");
$s = 1;
header("Location:add_location_admin.php?success");                
} 

} 
//====================== UPDATE   clients

if(isset($_POST['update_clients'])){   
$updated_user_id = $_POST["user_id"];
$updated_boss_id = $_POST["boss_id"];
$client_id = $_POST["client_id"];
$updated_firstname = $_POST["firstname"];
$updated_lastname = $_POST["lastname"]; 
$updated_phone= $_POST["phone"]; 
$updated_nid= $_POST["nid"];   
$updated_sex= $_POST["sex"]; 
$updated_dob= 0; 
$updated_marital= 0;                 
$updated_place_r= $_POST["place_r"];
$updated_business = $_POST["business"];
$b_location = $_POST["b_location"];

 
$teacher_query ="update clients set 
firstname='$updated_firstname',lastname='$updated_lastname', 
sex='$updated_sex', dob='$updated_dob', marital='$updated_marital', nid='$updated_nid',
phone='$updated_phone', place_r='$updated_place_r', business='$updated_business', b_location='$b_location' 
where users_id='$updated_user_id' and bosses_id='$updated_boss_id' and client_id='$client_id' ";
$execute = mysqli_query($conn,$teacher_query);
header("Location: update_clients_admin.php?client_update=$client_id&success");
} 
//==========================UPADATE LOAN Given

if(isset($_GET['update_loan'])){
 //--------------------
$boss_id=$_GET['boss_id'];
$client_id=$_GET['client_id'];
$b_date=$_GET['b_date'];
$b_date1=$_GET['b_date1'];
$amo=str_replace(",","",$_GET['amo']);
$reg_fee=str_replace(",","",$_GET['reg_fee']);
$curr_date=date('Y-m-d');
$pre_date = date('Y-m-d', strtotime("$curr_date -1 day"));

$interest=20/100*$amo;
$total=$amo+$interest;
$daily_p=$total/30;
$completed=0;

$result = mysqli_fetch_assoc(mysqli_query($conn,"select* from clients_with_loan
where clientsid='$client_id' and bosseseid='$boss_id' and pay_date='$b_date' "));     
$loan_no = $result['loan_no'];

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay  where  clients_id='$client_id'
and bossese_id='$boss_id' and  p_date>='$b_date' and loanNo='$loan_no'"));     
$amount = $result['amount_paid'];

if($b_date1>$curr_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>Error!  Wrong Date
<a href='error_give_loan.php?client_id=$client_id' style='color:white; margin-left:100px;''>X</a></div>";
}
else if ($amount>0){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 800px'>The Loan is already started to be paid, pay it off and give it as a new loan.
<a href='admin_homepage.php?reload=1' style='color:white; margin-left:100px;''>X</a></div>";
}
else{

//loans table
$loan_query ="UPDATE loans set amount_given='$amo', reg_fee='$reg_fee', b_date='$b_date1'
where cliente_id='$client_id' and bossese_id='$boss_id' and b_date='$b_date'";
$execute = mysqli_query($conn, $loan_query);

//clients with loans table
$loan_query ="UPDATE clients_with_loan set amount_given='$amo', daily_p='$daily_p', debt='$total', pay_date='$b_date1'
where clientsid='$client_id' and bosseseid='$boss_id' and pay_date='$b_date'";
$execute = mysqli_query($conn, $loan_query);

//completed table 

$loan_query ="UPDATE completed_loan set amount_given='$amo', pay_date='$b_date1'
where clientcpid='$client_id' and bosscpid='$boss_id' and pay_date='$b_date' and loans_no='$loan_no'";
$execute = mysqli_query($conn, $loan_query);

echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>Data Saved Successfully!
<a href='admin_homepage.php?client_id=$client_id' style='color:white; margin-left:100px;''>X</a></div>";

}
}

///================================================Update Loan payed
if(isset($_GET['update_pay_loan'])){
 //--------------------
$boss_id=$_GET['boss_id'];
$client_id=$_GET['client_id'];
$p_date=$_GET['p_date'];
$change_date=$_GET['change_date'];
$loan_no=$_GET['loan_no'];
$pre_balance=$_GET['pre_balance'];
$amo=str_replace(",","",$_GET['amo']);
 
$daily_p=$amo;
$balance=$pre_balance-$amo;
$completed=0;
$j=0;
$p_date2=0;
$p_date2=0;

$curr_date=date('Y-m-d');
$prev_date = date("Y-m-d", strtotime("$curr_date -1 day"));
 
if($amo>$pre_balance){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 500px'>The amount entered is greater than balance
<a href='error_pay_loan.php?client_id=$client_id' style='color:white; margin-left:100px;''>X</a></div>";
}
else if($change_date!=$curr_date && $change_date!=$prev_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>Error! You must select a current date or yesterday's date
<a href='error_pay_loan.php?client_id=$client_id' style='color:white; margin-left:100px;''>X</a></div>";
}
else{

if($balance==0){
$query ="UPDATE completed_loan set e_date='$change_date', completed=1 where loans_no=$loan_no and clientcpid='$client_id' and bosscpid='$boss_id'";
$execute = mysqli_query($conn, $query);
}
//clients with loans table
$query ="UPDATE clients_with_loan set debt='$balance' where clientsid='$client_id' and bosseseid='$boss_id'";
$execute = mysqli_query($conn, $query);

$query ="UPDATE loan_pay set amount_paid='$amo', p_date='$change_date', balance='$balance' where clients_id='$client_id' and bossese_id='$boss_id' and loanNo='$loan_no' and p_date='$p_date' ";
$execute = mysqli_query($conn, $query);

echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>Data Saved Successfully!  
<a href='error_pay_loan.php?client_id=$client_id'  style='color:white; margin-left:100px;''>X</a></div>";

}

}


if(isset($_GET['update_pay_mom'])){
 //--------------------
$boss_id=$_GET['boss_id'];
$client_id=$_GET['client_id'];
$p_date=$_GET['p_date'];
$amount_mo=str_replace(",","",$_GET['amount_mo']);
$loan_no=$_GET['loan_no'];
$pre_balance=$_GET['pre_balance'];
$amo=str_replace(",","",$_GET['amo']);
 
$daily_p=$amo;
$balance=$pre_balance-$amo;
$completed=0;
$charges=$amount_mo-$amo;

if($balance==0){
$query ="UPDATE completed_loan set e_date='$p_date', completed=1 where loans_no=$loan_no and clientcpid='$client_id' and bosscpid='$boss_id'";
$execute = mysqli_query($conn, $query);
}
//clients with loans table
$query ="UPDATE clients_with_loan set debt='$balance' where clientsid='$client_id' and bosseseid='$boss_id'";
$execute = mysqli_query($conn, $query);

$query ="UPDATE loan_pay set amount_paid='$amo', balance='$balance' where clients_id='$client_id' and bossese_id='$boss_id' and loanNo='$loan_no' and p_date='$p_date' ";
$execute = mysqli_query($conn, $query);
//mobile table
$query ="UPDATE mobile set amount_mo='$amount_mo' where clientmo_id='$client_id' and bossmo_id='$boss_id' and mom_date='$p_date'";
$execute = mysqli_query($conn, $query);

//charge table
$query ="UPDATE charges set amount_ch='$charges' where clientch_id='$client_id' and bossch_id='$boss_id' and ch_date='$p_date'";
$execute = mysqli_query($conn, $query);

echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>Data Saved Successfully!
<a href='admin_homepage.php?reload=1' style='color:white; margin-left:100px;''>X</a></div>";

}

//EXPENSESS ERRORS
if(isset($_POST['expenses_error'])){  
$exp_id=$_POST['exp_id'];
$amount=$_POST['amount'];
$exp_date=$_POST['exp_date'];

$curr_date=date('Y-m-d');
$prev_date = date("Y-m-d", strtotime("$curr_date -1 day"));

if($exp_date!=$curr_date && $exp_date!=$prev_date){
 header("Location: error_others_admin.php?error_expenses&Expenses");  
}
else{
$query ="UPDATE expenses set exp_date='$exp_date', cost='$amount' where exp_id='$exp_id'";
$execute = mysqli_query($conn, $query);

header("Location: error_others_admin.php?item=Expenses&Success_Expenses");   
}
}

//Delete Errors

if(isset($_GET['exp_id'])){
$exp_id = $_GET['exp_id'];
 
$query ="DELETE from expenses where exp_id='$exp_id'";
$execute = mysqli_query($conn, $query);

header("Location:error_others_admin.php?Deleted_Expenses&Expenses"); 
}

//=========================
//BANKING ERRORS==========

if(isset($_POST['banking_error'])){  
$deposit_id=$_POST['deposit_id'];
$amount=$_POST['amount'];
$deposit_date=$_POST['de_date'];

$curr_date=date('Y-m-d');
$prev_date = date("Y-m-d", strtotime("$curr_date -1 day"));

if($deposit_date!=$curr_date && $deposit_date!=$prev_date){
 header("Location: error_others_admin.php?error_Banking&Banking");  
}
else{
$query ="UPDATE deposit set de_date='$deposit_date', de_amount='$amount' where deposit_id='$deposit_id'";
$execute = mysqli_query($conn, $query);

header("Location: error_others_admin.php?Banking&Success_Banking");   
}
}

//Delete Errors

if(isset($_GET['deposit_id'])){
$deposit_id = $_GET['deposit_id'];
 
$query ="DELETE from deposit where deposit_id='$deposit_id'";
$execute = mysqli_query($conn, $query);

header("Location:error_others_admin.php?Deleted_Banking&Banking"); 
}


//=========================
//UNKNOWNERRORS==========

if(isset($_POST['unknown_error'])){  
$uknown_id=$_POST['uknown_id'];
$amount=$_POST['amount'];
$rec_date=$_POST['rec_date'];

$curr_date=date('Y-m-d');
$prev_date = date("Y-m-d", strtotime("$curr_date -1 day"));

if($rec_date!=$curr_date && $rec_date!=$prev_date){
 header("Location: error_others_admin.php?error_Unknown&Unknown");  
}
else{
$query ="UPDATE uknown set rec_date='$rec_date', paid_amount='$amount' where uknown_id='$uknown_id'";
$execute = mysqli_query($conn, $query);

header("Location: error_others_admin.php?Unknown&Success_Unknown");   
}
}

//Delete Errors

if(isset($_GET['unknown_id'])){
$unknown_id = $_GET['unknown_id'];
 
$query ="DELETE from uknown where uknown_id='$unknown_id'";
$execute = mysqli_query($conn, $query);

header("Location:error_others_admin.php?Deleted_Unknown&Unknown"); 
}

//allocate clients
if(isset($_POST['submit'])){   
$loc = $_POST["loc"];
$client_id= $_POST["client_id"]; 



for($i=0;$i<count($client_id); $i++){

$query ="UPDATE clients set b_location='$loc' where client_id='$client_id[$i]'";
$execute = mysqli_query($conn, $query);
}

header("Location:add_location_admin.php?success");     
}
?>

 

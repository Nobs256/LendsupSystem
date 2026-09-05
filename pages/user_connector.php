<?php 
include("conn.php"); 
//preventing sql injection
function prevent_sql_injection($data){
$data = stripslashes($data);
$data = htmlspecialchars($data);
$data = trim($data);
return $data;
}

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
header("Location: user_profile.php?user_id=$updated_user_id");
 

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

header("Location:user_profile.php?user_id=$user_id");

} 
} 
} 
}      

header("Location:user_profile.php?user_id=$user_id");   

}    
 
//======================================CREATE clients
if(isset($_GET['add_clients'])){
$boss_id=$_GET['boss_id'];
$user_id=$_GET['user_id']; 
$f = $_GET['fname'];
$l = $_GET['lname'];
$phone = $_GET['phone'];
$sex = $_GET['sex'];
$marital = 0;
$place_r = $_GET['place_r'];
$nid = 0;
$dob = 00;
$business = $_GET['business'];
$b_location= $_GET['b_location'];
$date=date('Y-m-d');

$same_client ="select * from clients where  phone='$phone'";
$run = mysqli_query($conn, $same_client) or die("Could DB");
$same_client = mysqli_num_rows($run);
if ($same_client==1){
 echo "<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>
<font color=white>The Client  is already Registered!!</font>
<a href='add_clients.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</div>";
}
else{
mysqli_query($conn,"INSERT INTO clients(client_id, users_id, bosses_id, 
    firstname, lastname, sex, dob, marital, nid, phone,  place_r,  business, b_location, reg_date) 
VALUES (NULL, '$user_id', '$boss_id', '$f', '$l', '$sex', '$dob', '$marital',  '$nid', '$phone', 
 '$place_r',  '$business', '$b_location', '$date')");
 

echo "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 500px'>
<font color=white>A Client is successfully Registered!!</font>
<a href='add_clients.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</div>";              

} 

} 

//======================================CREATE clients
if(isset($_GET['add_clients2'])){
$boss_id=$_GET['boss_id'];
$user_id=$_GET['user_id']; 
$f = $_GET['fname'];
$l = $_GET['lname'];
$phone = $_GET['phone'];
$sex = $_GET['sex'];
$marital = 0;
$place_r = $_GET['place_r'];
$nid = 0;
$dob = 00;
$business = $_GET['business'];
$b_location= $_GET['b_location'];
$date=date('Y-m-d');

$same_client ="select * from clients where  phone='$phone'";
$run = mysqli_query($conn, $same_client) or die("Could DB");
$same_client = mysqli_num_rows($run);
if ($same_client==1){
 echo "<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>
<font color=white>The Client  is already Registered!!</font>
<a href='add_clients.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</div>";
}
else{
mysqli_query($conn,"INSERT INTO clients(client_id, users_id, bosses_id, 
    firstname, lastname, sex, dob, marital, nid, phone,  place_r,  business, b_location, reg_date) 
VALUES (NULL, '$user_id', '$boss_id', '$f', '$l', '$sex', '$dob', '$marital',  '$nid', '$phone', 
 '$place_r',  '$business', '$b_location', '$date')");
 

echo "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 500px'>
<font color=white>A Client is successfully Registered!!</font>
<a href='add_client_data_entry.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</div>";              

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
$updated_dob= $_POST["dob"]; 
$updated_marital= $_POST["marital"];                 
$updated_place_r= $_POST["place_r"];
$updated_business = $_POST["business"];
$b_location = $_POST["b_location"];

 
$teacher_query ="update clients set 
firstname='$updated_firstname',lastname='$updated_lastname', 
sex='$updated_sex', dob='$updated_dob', marital='$updated_marital', nid='$updated_nid',
phone='$updated_phone', place_r='$updated_place_r', business='$updated_business', b_location='$b_location' 
where users_id='$updated_user_id' and bosses_id='$updated_boss_id' and client_id='$client_id' ";
$execute = mysqli_query($conn,$teacher_query);
header("Location: update_clients_users.php?client_update=$client_id&success");
} 




//====================== UPDATE clients IMAGES

if(isset($_POST['upload_user'])){
$user_id = $_POST["user_id"];
if($_FILES['image']['name']){ //if image is selected
$allowed_ext =array("jpg","png","JPG","PNG");//allowed extensions
$ext = end(explode(".",$_FILES['image']['name']));//spilt file name into array
if(in_array($ext,$allowed_ext)){//check if image has allowed extenstion.
if($_FILES["image"]["size"]<1000000){//check image size 1000000 means 1000KB or 1MB
$new_name =md5(rand()).'.'.$ext;//rename file before uploding
$path ="assets/images/clients_images/".$new_name;
//if image have been uploaded then update student table
if(move_uploaded_file($_FILES['image']['tmp_name'],$path)){

//remove the first uploaded image to save space

$img_upload = "update new_clients Set clients_image='$new_name' where user_id='$user_id'";
mysqli_query($conn,$img_upload);

header("Location:update_clients.php?user_id=$user_id");

} 
} 
} 
}
}

if(isset($_GET['delete_user'])){
$user_id = $_GET['delete_user'];
$ac=0;
$query ="update new_clients set 
active='$ac' where user_id='$user_id'";
$execute = mysqli_query($conn, $query);
$s = 1;

header("Location:add_clients.php?delete_user=$s"); 
}
 
//====================================== EXPENSES
if(isset($_GET['exp'])){
$boss_id=$_GET['boss_id'];
$user_id=$_GET['user_id'];
$item=$_GET['item'];
$nara=$_GET['nara'];
$exp_date=$_GET['b_date'];
$amo=str_replace(",","",$_GET['amo']);
$client_id=0;
$reg_fee=0;
$mom=0;

if($item=="Salaries"){
$item="Salaries and Wages";
}
if($item=="Employee"){
$item="Employee Allowances";
}
if($item=="Maintainance"){
$item="Maintainance of Office Equipments";
}
if($item=="Rent"){
$item="Rent Expenses";
}
if($item=="Office"){
$item="Office Maintainance";
}
if($item=="Loan"){
$item="Loan Recovery";
}
if($item=="Stationary"){
$item="Stationary and Printing";
}
if($item=="Lunch"){
$item="Lunch Allowance";
}
if($item=="Supper"){
$item="Supper Allowance";
}
if($item=="Break"){
$item="Break fast Allowance";
}
if($item=="Parking"){
$item="Parking and Security";
}
if($item=="Directors"){
$item="Directors Expenses";
}
 
if($item=="Air"){
$item="Air Time Allowance";
}

$curr_date=date('Y-m-d');
$pre_date = date('Y-m-d', strtotime("$curr_date -1 day"));
$sent_date=date("Y-m-d", strtotime($exp_date));
 
date_default_timezone_set("Africa/Nairobi");
$time=date("H:i:s");
$dateTime = new DateTime($time);
$dateTime=$dateTime->format('H:i:s');
  
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from sent_msgs where user_id='$user_id' 
and boss_id='$boss_id' order by ts_id DESC Limit 1")); 
$msg_date= $result['msg_date'];

if($sent_date>$curr_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>Error! Select Correct date. You are have selected a date above todate!
<a href='add_expenses.php' style='color:white; margin-left:100px;''>X</a></div>";
}
  
else if($sent_date<=$msg_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>You Have Already Sent Report. Not Allowed to make this Transaction
<a href='user_homepage.php' style='color:white; margin-left:70px;''>X</a></div>";
}

else {
 
$same_expense ="SELECT * from expenses where exp_date='$exp_date' and item='$item'and cost='$amo' and userexp_id='$user_id' and bossexp_id='$boss_id' and naration='$nara'";
$run = mysqli_query($conn, $same_expense) or die("Could DB");
$same_expense = mysqli_num_rows($run);

if ($same_expense==0){
mysqli_query($conn,"INSERT INTO expenses(exp_id, userexp_id, bossexp_id, exp_date, item, cost, naration) 
VALUES (NULL, '$user_id','$boss_id', '$exp_date', '$item', '$amo', '$nara')");

$names=$item." ".$nara;
$transc_type="Cash_out";
mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$exp_date', '$names',  '$transc_type', '$amo', '$reg_fee', '$mom')");


echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:5px; width: 400px'>Data is Successfully Saved 
<a href='add_expenses.php' style='color:white; margin-left:100px;''>X</a>
</div>";           
} 

else
{
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>Data is Already Entered
<a href='add_expenses.php?reload=1' style='color:white; margin-left:100px;''>X</a></div>";
}
}
}

//======================================CASH RECIEVED(Payables)
if(isset($_GET['cr'])){
$boss_id=$_GET['boss_id'];
$user_id=$_GET['user_id'];
$date=$_GET['b_date'];
$branch=$_GET['branch'];
$amo=str_replace(",","",$_GET['amo']);

$client_id=0;
$reg_fee=0;
$mom=0;
  
$curr_date=date('Y-m-d');
$pre_date = date('Y-m-d', strtotime("$curr_date -1 day"));
$sent_date=date("Y-m-d", strtotime($exp_date));
 
date_default_timezone_set("Africa/Nairobi");
$time=date("H:i:s");
$dateTime = new DateTime($time);
$dateTime=$dateTime->format('H:i:s');
  
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from sent_msgs where user_id='$user_id' 
and boss_id='$boss_id' order by ts_id DESC Limit 1")); 
$msg_date= $result['msg_date'];

if($sent_date>$curr_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>Error! Select Correct date. You are have selected a date above todate!
<a href='add_expenses.php' style='color:white; margin-left:100px;''>X</a></div>";
}
  
else if($sent_date<=$msg_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>You Have Already Sent Report. Not Allowed to make this Transaction
<a href='user_homepage.php' style='color:white; margin-left:70px;''>X</a></div>";
}

else {
 
mysqli_query($conn,"INSERT INTO cr(cr_id, usercr_id, bosscr_id, cr_date, branch, cr_amount) 
VALUES (NULL, '$user_id', '$boss_id', '$date', '$branch', '$amo')");

$names="Payables from"." ".$branch;
$transc_type="Cash_in";
mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$date', '$names',  '$transc_type', '$amo', '$reg_fee', '$mom')");


echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:5px; width: 500px'>Data is Successfully Saved
<a href='add_cr.php' style='color:white; margin-left:100px;''>X</a>
</div>"; 
} 
}

//======================================CASH Sent (Recievables)
if(isset($_GET['money_sent'])){
$boss_id=$_GET['boss_id'];
$user_id=$_GET['user_id'];
$date=$_GET['b_date'];
$branch=$_GET['branch'];
$amo=str_replace(",","",$_GET['amo']);
$client_id=0;
$reg_fee=0;
$mom=0;
  
$curr_date=date('Y-m-d');
$pre_date = date('Y-m-d', strtotime("$curr_date -1 day"));
$sent_date=date("Y-m-d", strtotime($exp_date));
 
date_default_timezone_set("Africa/Nairobi");
$time=date("H:i:s");
$dateTime = new DateTime($time);
$dateTime=$dateTime->format('H:i:s');
  
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from sent_msgs where user_id='$user_id' 
and boss_id='$boss_id' order by ts_id DESC Limit 1")); 
$msg_date= $result['msg_date'];

if($sent_date>$curr_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>Error! Select Correct date. You are have selected a date above todate!
<a href='add_expenses.php' style='color:white; margin-left:100px;''>X</a></div>";
}
  
else if($sent_date<=$msg_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>You Have Already Sent Report. Not Allowed to make this Transaction
<a href='user_homepage.php' style='color:white; margin-left:70px;''>X</a></div>";
}

else {
 
mysqli_query($conn,"INSERT INTO sent_to_branch(cr_id, usercr_id, bosscr_id, cr_date, branch, cr_amount) 
VALUES (NULL, '$user_id', '$boss_id', '$date', '$branch', '$amo')");

$names="Recievables to"." ".$branch;
$transc_type="Cash_out";
mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$date', '$names',  '$transc_type', '$amo', '$reg_fee', '$mom')");

echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:5px; width: 500px'>Data is Successfully Saved
<a href='add_cr.php' style='color:white; margin-left:100px;''>X</a>
</div>"; 
} 
}


//==============Mobile MONEy PAYMENT

if(isset($_GET['mom'])){
$boss_id=$_GET['boss_id'];
$user_id=$_GET['user_id'];
$client_id=$_GET['client_id'];
$numb=$_GET['numb'];
$date=$_GET['p_date'];
$amo=str_replace(",","",$_GET['amo']);
$paid=str_replace(",","",$_GET['amount_paid']);
$charges=$amo-$paid;
$mom=1;
$printed=0;
$withdraw=0;
$curr_date=date('Y-m-d');
$pre_date = date('Y-m-d', strtotime("$curr_date -1 day"));
$sent_date=date("Y-m-d", strtotime($date));
$reg_fee=0;
date_default_timezone_set("Africa/Nairobi");
$time=date("H:i:s");
$dateTime = new DateTime($time);
$dateTime=$dateTime->format('H:i:s');

$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients_with_loan where clientsid='$client_id'"));     
$debt= $result['debt'];
$loan_no= $result['loan_no'];
$balance=$debt-$paid;

function countExist($conn,$table,$key)
{
$k=mysqli_query($conn,"SELECT * FROM $table WHERE $key");
$rows=mysqli_num_rows($k);
return $rows;
}

$check=countExist($conn,"loan_pay","p_date='$date' AND clients_id='$client_id' and mom=1");
$check2=countExist($conn,"total_mom","phone='$numb' AND user_id='$user_id' ");

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id' order by p_date desc limit 1"));     
$entered_prev_date = $result['p_date'];

if($paid>$debt){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 500px'>The amount entered is greater than balance
<a href='pay_loan_mom.php?client_id=$client_id' style='color:white; margin-left:100px;''>X</a></div>";
}

else if($sent_date>$curr_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>Error! Select Correct date. You are have selected a date above todate!
<a href='pay_loan_mom.php?client_id=$client_id' style='color:white; margin-left:100px;''>X</a></div>";
}
else if($sent_date<$curr_date && $dateTime>'10:00:00'){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width:  700px'>Error! Select Correct date. You are beyond time of Yesterday's Payment 
<a href='pay_loan_mom.php?client_id=$client_id' style='color:white; margin-left:100px;''>X</a></div>";
}
else if($sent_date<$pre_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width:  700px'>Error! Select Correct date. You Must Current Date 
<a href='pay_loan_mom.php?client_id=$client_id' style='color:white; margin-left:100px;''>X</a></div>";
}
else if($entered_prev_date>$sent_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>Error! The Date Entered is less than the Date Last Paid!!
<a href='pay_loan_mom.php?client_id=$client_id' style='color:white; margin-left:100px;''>X</a></div>";
}

else if($check==0)
{
if($check2==0)
{
mysqli_query($conn,"INSERT INTO total_mom(ts_id, phone, user_id, boss_id, total) 
VALUES (NULL, '$numb',  '$user_id', '$boss_id', '$amo')");
}
else{
$total=0;
$total_saved=0;

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM total_mom  where phone='$numb' and user_id='$user_id'
and boss_id='$boss_id'"));
$total = $results["total"];
$total_saved=$total+$amo;

$query ="UPDATE total_mom set total='$total_saved' where phone='$numb' and user_id='$user_id' 
and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);
}

if($balance==0){
$query ="update completed_loan set e_date='$date', completed=1 where loans_no=$loan_no and clientcpid='$client_id' and userscpid='$user_id' and bosscpid='$boss_id'";
$execute = mysqli_query($conn, $query);
}
//Record transaction
$transc_type="Cash_in";
$hup=mysqli_query($conn,"SELECT * FROM clients WHERE  client_id='$client_id' and users_id='$user_id' and bosses_id='$boss_id'");
$now=mysqli_fetch_array($hup);
$names=$now["firstname"]." ".$now["lastname"];

 //====enter data in mom Number table
mysqli_query($conn,"INSERT INTO mobile_numbers(mobile_id, usermo_id, bossmo_id, phone, mom_date, names, amount_mo, withdraw, balance) 
VALUES (NULL, '$user_id', '$boss_id', '$numb', '$date', '$names', '$amo', '$withdraw', '$total_saved')");

 //====enter data in mobile money table
mysqli_query($conn,"INSERT INTO mobile(mobile_id, clientmo_id, usermo_id, bossmo_id, amount_mo, mom_date, phone_sent) 
VALUES (NULL, '$client_id', '$user_id', '$boss_id',  '$amo', '$date', '$numb')");
  
 //====enter data in pay loan table
mysqli_query($conn,"INSERT INTO loan_pay(loan_id, loanNo, clients_id, userse_id, bossese_id, p_date, amount_paid, balance, mom) 
VALUES (NULL, '$loan_no', '$client_id',  '$user_id', '$boss_id', '$date', '$paid', '$balance', '$mom')");

 //====enter data in pay loan table
mysqli_query($conn,"INSERT INTO loan_pay_daily(loan_id, loanNo, clients_id, userse_id, bossese_id, p_date, amount_paid, balance, mom) 
VALUES (NULL, '$loan_no', '$client_id',  '$user_id', '$boss_id', '$date', '$paid', '$balance', '$mom')");

mysqli_query($conn,"INSERT INTO receipts(receipt_id, userrec_id, bossrec_id, clientrec_id, loan_norec_id, rec_date, paid_amount, balancerec, printed) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$loan_no', '$date', '$paid', '$balance', '$printed')");
//===update the loans
$query ="update clients_with_loan set debt='$balance' where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'";
$execute = mysqli_query($conn, $query);

$mom=1;
mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$date', '$names',  '$transc_type', '$amo', '$reg_fee', '$mom')");

echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:5px; width: 900px'>
<table border='0'>
<tr>
<td width='500px'><font size='4'>Data Saved Successfully!</font></td>
<td> 
<form method='post' action='payment_receipt.php' target='_blank'>
<input type=hidden name='client_id' value='$client_id'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='loan_no' value='$loan_no'>
<input type=hidden name='pay_date' value='$date'>
<button type='submit' name=receipt class='button is-default' 
style='padding-top:1px; border: 1px solid #006F37; border-radius:4px; color:#006F37'>
&nbsp;Print Receipt &nbsp;</button>
</form>
</td>
<td>
<a href='search_client_mom_pay.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</td></tr></table>
</div>"; 
} 

else
{
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 800px'> 
 
<table border='0'>
<tr>
<td width='500px'><font size='4'>The client has already Paid!&nbsp;&nbsp; Do want to pay More?</font></td>
<td> 
<form method='post' action='user_connector.php'>
<input type=hidden name='client_id' value='$client_id'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='loan_no' value='$loan_no'>
<input type=hidden name='pay_date' value='$date'>
<input type=hidden name='paid' value='$amo'>
<input type=hidden name='amount' value='$paid'>
<input type=hidden name='numb' value='$numb'>
<input type=hidden name='mom' value='$mom'>
<button type='submit' name=pay_loan_connector2 class='button is-default' 
style='padding-top:0px; border: 0px solid #006F37; border-radius:4px; height:15px; color:#006F37; background-color:red; color:white'>
&nbsp; Yes &nbsp;</button>
</form>
</td>
<td>
<a href='search_client_pay_loan.php?reload=1' style='color:white; margin-left:100px;''>No</a>
</td></tr></table>

</div>";
}
}

//========================UKNOWN SOURCES
if(isset($_GET['unknown'])){
$boss_id=$_GET['boss_id'];
$user_id=$_GET['user_id'];
$date=$_GET['b_date'];
$tel=$_GET['numb'];
$amo=str_replace(",","",$_GET['amo']);
$names="Money From Unknow Source";
$type='Cash_in';
$client_id=0;
$mom=1;
$reg_fee=0;
$withdraw=0;
$known=0;
$known_date='0000-00-00';
$curr_date=date('Y-m-d');
$pre_date = date('Y-m-d', strtotime("$curr_date -1 day"));
$sent_date=date("Y-m-d", strtotime($date));
date_default_timezone_set("Africa/Nairobi");
$time=date("H:i:s");
$dateTime = new DateTime($time);
$dateTime=$dateTime->format('H:i:s');

if($sent_date>$curr_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>Error! Select Correct date. You are have selected a date above todate!
<a href='unknown.php' style='color:white; margin-left:100px;''>X</a></div>";
}
else if($sent_date<$pre_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>Error! Select Correct date. You must select the Current Date!
<a href='unknown.php' style='color:white; margin-left:100px;''>X</a></div>";
}
else if($sent_date==$pre_date && $dateTime>'10:00:00'){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width:  700px'>Error! Select Correct date. You are beyond time of Yesterday's Payment 
<a href='unknown.php' style='color:white; margin-left:100px;''>X</a></div>";
}
else{
$total=0;
$total_saved=0;

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM total_mom  where phone='$tel' and user_id='$user_id' and boss_id='$boss_id'"));
$total = $results["total"];
$total_saved=$total+$amo;

$query ="UPDATE total_mom set total='$total_saved' where phone='$tel' and user_id='$user_id' and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);

 //====enter data in mom Number table
mysqli_query($conn,"INSERT INTO mobile_numbers(mobile_id, usermo_id, bossmo_id, phone, mom_date, names, amount_mo, withdraw, balance) 
VALUES (NULL, '$user_id', '$boss_id', '$tel', '$date', '$names', '$amo', '$withdraw', '$total_saved')");


mysqli_query($conn,"INSERT INTO uknown(uknown_id, userrec_id, bossrec_id, rec_date, tel, paid_amount, known, known_date) 
VALUES (NULL, '$user_id', '$boss_id', '$date', '$tel', '$amo', '$known', '$known_date')");

mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$date', '$names',  '$type', '$amo', '$reg_fee', '$mom')");

echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:5px; width: 500px'>Data is Successfully Saved
<a href='unknown.php' style='color:white; margin-left:100px;''>X</a>
</div>"; 
}
}

 
//========================UKNOWN SOURCES uPDATES
if(isset($_GET['unknown_id'])){
$unknown_id = $_GET['unknown_id'];

$query ="UPDATE uknown set known=1 where uknown_id='$unknown_id'";
$execute = mysqli_query($conn, $query);
 
header("Location:unknown.php"); 
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
header("Location: add_location.php?already_location"); 
}
else{
mysqli_query($conn,"INSERT INTO location(location_id, userloc_id, bossloc_id, location) 
VALUES (NULL, '$user_id', '$boss_id', '$loc')");
$s = 1;
header("Location:add_location.php?success");                
}
} 

//=====================PAY LOAN more than Once
if(isset($_POST['pay_loan_connector2'])){  
$user_id=$_POST['user_id'];
$boss_id=$_POST['boss_id'];
$client_id=$_POST['client_id'];
$b_date=$_POST['pay_date'];
$loan_no=$_POST['loan_no'];
$amo= $_POST['amount'];
$mom= $_POST['mom'];
$amount_sent= $_POST['paid'];
$numb= $_POST['numb'];
$printed=0;
$withdraw=0; 
$day_total=0;
$reg_fee=0;
$total=0;
if($mom==0){
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients_with_loan where clientsid='$client_id'"));     
$debt= $result['debt'];
$balance=$debt-$amo;

if($amo>$debt){
header("Location: pay_loan.php?balance&client_id=$client_id");    
}

else{
if($balance==0){
$query ="update completed_loan set e_date='$b_date', completed=1 where loans_no=$loan_no and clientcpid='$client_id' and userscpid='$user_id' and bosscpid='$boss_id'";
$execute = mysqli_query($conn, $query);
}

$search_query= mysqli_query($conn,"SELECT * FROM loan_pay  where  loanNo='$loan_no' and 
clients_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id' and p_date='$b_date' and mom=0");
while($returned_result = mysqli_fetch_assoc($search_query)){
 
$amount_paid=$returned_result["amount_paid"];
$total=$total+$amount_paid;
}

$day_total=$total+$amo;

$transc_type="Cash_in";
$hup=mysqli_query($conn,"SELECT * FROM clients WHERE  client_id='$client_id' and users_id='$user_id' and bosses_id='$boss_id'");
$now=mysqli_fetch_array($hup);
$names=$now["firstname"]." ".$now["lastname"]; 

$query ="UPDATE loan_pay set balance='$balance', amount_paid='$day_total'  where clients_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loan_no' and p_date='$b_date' ";
$execute = mysqli_query($conn, $query);

$query ="UPDATE loan_pay_daily set balance='$balance', amount_paid='$day_total'  where clients_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loan_no' and p_date='$b_date' ";
$execute = mysqli_query($conn, $query);

$query ="UPDATE receipts set balancerec='$balance', paid_amount='$day_total'  where clientrec_id='$client_id' and userrec_id='$user_id' and bossrec_id='$boss_id' and loan_norec_id='$loan_no' and rec_date='$b_date' ";
$execute = mysqli_query($conn, $query);
 
//===update the loans
$query ="update clients_with_loan set debt='$balance' where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'";
$execute = mysqli_query($conn, $query);

mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id',  '$b_date', '$names',  '$transc_type', '$amo', '$reg_fee', '$mom')");

header("Location: pay_loan.php?success&client_id=$client_id&remain_balance=$balance");    
}
}
else{
//=================More Paying with MOM 
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients_with_loan where clientsid='$client_id'"));     
$debt= $result['debt'];
$balance=$debt-$amo;

if($amo>$debt){
header("Location: pay_loan_mom.php?balance&client_id=$client_id");    
}

else{
if($balance==0){
$query ="update completed_loan set e_date='$b_date', completed=1 where loans_no=$loan_no and clientcpid='$client_id' and userscpid='$user_id' and bosscpid='$boss_id'";
$execute = mysqli_query($conn, $query);
}

$transc_type="Cash_in";
$hup=mysqli_query($conn,"SELECT * FROM clients WHERE  client_id='$client_id' and users_id='$user_id' and bosses_id='$boss_id'");
$now=mysqli_fetch_array($hup);
$names=$now["firstname"]." ".$now["lastname"];
$number=$now["phone"];

$search_query= mysqli_query($conn,"SELECT * FROM loan_pay  where  loanNo='$loan_no' and 
clients_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id' and p_date='$b_date' and mom=1");
while($returned_result = mysqli_fetch_assoc($search_query)){
 
$amount_paid=$returned_result["amount_paid"];
$total=$total+$amount_paid;
}

$search_query= mysqli_query($conn,"SELECT * FROM mobile where clientmo_id='$client_id' and usermo_id='$user_id' and bossmo_id='$boss_id' and mom_date='$b_date' ");
while($returned_result = mysqli_fetch_assoc($search_query)){
 
$amount_mo=$returned_result["amount_mo"];
$total_amount_mo+=$amount_mo;
}

$charges=$amount_sent-$amo;
$day_total=$total+$amo;
$day_charges=$total_charges+$charges;
$day_mobile=$total_amount_mo+$amount_sent;

$total=0;
$total_saved=0;
$mom=1;

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM total_mom  where phone='$numb' and user_id='$user_id'
and boss_id='$boss_id'"));
$total = $results["total"];
$total_saved=$total+$amount_sent;

$query ="UPDATE total_mom set total='$total_saved' where phone='$numb' and user_id='$user_id' 
and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);


 //====enter data in mom Number table
mysqli_query($conn,"INSERT INTO mobile_numbers(mobile_id, usermo_id, bossmo_id, phone, mom_date, names, amount_mo, withdraw, balance) 
VALUES (NULL, '$user_id', '$boss_id', '$numb', '$b_date', '$names', '$amo', '$withdraw', '$total_saved')");

$query ="UPDATE mobile set amount_mo='$day_mobile'  where phone_sent='$numb' and  clientmo_id='$client_id' and usermo_id='$user_id' and bossmo_id='$boss_id' and mom_date='$b_date' ";
$execute = mysqli_query($conn, $query);

$query ="UPDATE loan_pay set balance='$balance', amount_paid='$day_total'  where clients_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loan_no' and p_date='$b_date' and mom=1";
$execute = mysqli_query($conn, $query);

$query ="UPDATE receipts set balancerec='$balance', paid_amount='$day_total'  where clientrec_id='$client_id' and userrec_id='$user_id' and bossrec_id='$boss_id' and loan_norec_id='$loan_no' and rec_date='$b_date' ";
$execute = mysqli_query($conn, $query);
 
//===update the loans
$query ="update clients_with_loan set debt='$balance' where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'";
$execute = mysqli_query($conn, $query);

mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id',  '$b_date', '$names',  '$transc_type', '$amo', '$reg_fee', '$mom')");
 
//msg
$amount_msg=0;
$date=date("d-m-Y", strtotime($b_date));
$msg="$date -  Hello  ".strtoupper($names). ", You have Paid UGX $amo to MUSHA-Mengo
 Balance is UGX $balance. Thank You.";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://bluesmsuganda.com/api-sub.php");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch,
CURLOPT_POSTFIELDS,"user=abelk&password=0777842873&sender=".urlencode("QuickAcounts")."&message=".urlencode($msg)."&reciever=$number");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$hamza = curl_exec ($ch);
curl_close ($ch); 

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from total_msg where user_id='$user_id' 
and boss_id='$boss_id'")); 
$total= $result['total'];
$new_total=$total-35;

$query ="UPDATE total_msg set total='$new_total' where user_id='$user_id' and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);

header("Location: pay_loan_mom.php?success&client_id=$client_id");
}
                         
}
}
//PAY MORE USING UNKNOWN 
if(isset($_POST['pay_loan_more_unknown'])){  
$user_id=$_POST['user_id'];
$boss_id=$_POST['boss_id'];
$client_id=$_POST['client_id'];
$b_date=$_POST['pay_date'];
$loan_no=$_POST['loan_no'];
$amo= $_POST['amount'];
$mom= $_POST['mom'];
$amount_sent= $_POST['paid'];
$numb= $_POST['numb'];
$unknown_id=$_POST['unknown_id'];
$printed=0; 
$day_total=0;
$total=0;


$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients_with_loan where clientsid='$client_id' and bosseseid='$boss_id'"));     
$debt= $result['debt'];
$balance=$debt-$amo;

if($amo>$debt){
header("Location: pay_loan_using_unknown.php?balance&client_id=$client_id");    
}
else{

$query ="UPDATE uknown set known=1, known_date='$b_date' where uknown_id='$unknown_id'";
$execute = mysqli_query($conn, $query);

 
if($balance==0){
$query ="update completed_loan set e_date='$b_date', completed=1 where loans_no=$loan_no and clientcpid='$client_id' and userscpid='$user_id' and bosscpid='$boss_id'";
$execute = mysqli_query($conn, $query);

mysqli_query($conn,"DELETE FROM clients_with_fines where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id'");
}

$search_query= mysqli_query($conn,"SELECT * FROM loan_pay  where  loanNo='$loan_no' and 
clients_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id' and p_date='$b_date'");
while($returned_result = mysqli_fetch_assoc($search_query)){
 
$amount_paid=$returned_result["amount_paid"];
$total=$total+$amount_paid;
}

$day_total=$total+$amo;

$transc_type="Cash_in";
$hup=mysqli_query($conn,"SELECT * FROM clients WHERE  client_id='$client_id' and users_id='$user_id' and bosses_id='$boss_id'");
$now=mysqli_fetch_array($hup);
$names=$now["firstname"]." ".$now["lastname"]; 
$number=$now["phone"];

$query ="UPDATE loan_pay set balance='$balance', amount_paid='$day_total'  where clients_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loan_no' and p_date='$b_date' ";
$execute = mysqli_query($conn, $query);

$query ="UPDATE receipts set balancerec='$balance', paid_amount='$day_total'  where clientrec_id='$client_id' and userrec_id='$user_id' and bossrec_id='$boss_id' and loan_norec_id='$loan_no' and rec_date='$b_date' ";
$execute = mysqli_query($conn, $query);

mysqli_query($conn,"INSERT INTO withdraws(with_id, withdraws, user_id, boss_id, with_date, amount) 
VALUES (NULL, '$type', '$user_id', '$boss_id', '$b_date', '$amo')");


mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id',  '$b_date', '$names',  '$transc_type', '$amo', '$reg_fee', '$mom')");

//===update the loans
$query ="update clients_with_loan set debt='$balance' where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'";
$execute = mysqli_query($conn, $query);

//msg
$amount_msg=0;
$date=date("d-m-Y", strtotime($b_date));
$msg="$date -  Hello  ".strtoupper($names). ", You have Paid UGX $amo to MUSHA-Mengo
 Balance is UGX $balance. Thank You.";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://bluesmsuganda.com/api-sub.php");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch,
CURLOPT_POSTFIELDS,"user=abelk&password=0777842873&sender=".urlencode("QuickAcounts")."&message=".urlencode($msg)."&reciever=$number");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$hamza = curl_exec ($ch);
curl_close ($ch); 

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from total_msg where user_id='$user_id' 
and boss_id='$boss_id'")); 
$total= $result['total'];
$new_total=$total-35;

$query ="UPDATE total_msg set total='$new_total' where user_id='$user_id' and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);

header("Location: pay_loan_using_unknown.php?success&client_id=$client_id&remain_balance=$balance");    
}
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
$amount_given = $result['amount_given'];

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay  where  clients_id='$client_id'
and bossese_id='$boss_id' and  p_date>='$b_date' and loanNo='$loan_no'"));     
$amount = $result['amount_paid'];
  
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from sent_msgs where user_id='$user_id' 
and boss_id='$boss_id' order by ts_id DESC Limit 1")); 
$msg_date= $result['msg_date'];

if($b_date1>$curr_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>Error! You must select a current date 
<a href='error_give_loan_user.php?client_id=$client_id' style='color:white; margin-left:100px;''>X</a></div>";
}
 
else if($b_date1<=$msg_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>You Have Already Sent Report. Not Allowed to make this Transaction
<a href='user_homepage.php' style='color:white; margin-left:70px;''>X</a></div>";
}
  
else if ($amount>0){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 800px'>The Loan has already started to be paid
<a href='user_homepage.php?reload=1' style='color:white; margin-left:100px;''>X</a></div>";
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

//Transactions
$loan_query ="UPDATE transcations set transc_amount='$amo', reg_fee='$reg_fee', transc_date='$b_date1'
where clientr_id='$client_id' and boss_id='$boss_id' and transc_date='$b_date' and transc_type='Cash_out'";
$execute = mysqli_query($conn, $loan_query);

$error="Giving Loan";
mysqli_query($conn,"INSERT INTO errors(ts_id, boss_id, clients_id, error, pay_date, amount) 
VALUES (NULL, '$boss_id', '$client_id',  '$error', '$b_date', '$amo')");


echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 500px'>Giving Loan Errors are Successfully Corrected!
<a href='search_errors_user.php' style='color:white; margin-left:50px;''>X</a></div>";

}
}
//================================================Update Loan payed
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
  
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from sent_msgs where user_id='$user_id' 
and boss_id='$boss_id' order by ts_id DESC Limit 1")); 
$msg_date= $result['msg_date'];
 
if($amo>$pre_balance){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 500px'>The amount entered is greater than balance
<a href='error_pay_loan_user.php?client_id=$client_id' style='color:white; margin-left:100px;''>X</a></div>";
}

else if($p_date<=$msg_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>You Have Already Sent Report. Not Allowed to make this Transaction
<a href='user_homepage.php' style='color:white; margin-left:70px;''>X</a></div>";
}
  

else{

if($balance==0){
$query ="UPDATE completed_loan set e_date='$change_date', completed=1 where loans_no=$loan_no and clientcpid='$client_id' and bosscpid='$boss_id'";
$execute = mysqli_query($conn, $query);
}
//clients with loans table
$query ="UPDATE clients_with_loan set debt='$balance' where clientsid='$client_id' and bosseseid='$boss_id'";
$execute = mysqli_query($conn, $query);

//loan_pay table
$query ="UPDATE loan_pay set amount_paid='$amo', p_date='$change_date', balance='$balance' where clients_id='$client_id' and bossese_id='$boss_id' and loanNo='$loan_no' and p_date='$p_date' and mom=0";
$execute = mysqli_query($conn, $query);

//field officers
$query ="UPDATE field_payment set amount='$amo', pay_date='$change_date'  where clientf_id='$client_id' and boss_id='$boss_id' and pay_date='$p_date' and mom=0";
$execute = mysqli_query($conn, $query);

//Receipts table
$query ="UPDATE receipts set balancerec='$balance', paid_amount='$amo', rec_date='$change_date'  where clientrec_id='$client_id' and bossrec_id='$boss_id' and loan_norec_id='$loan_no' and rec_date='$p_date' ";
$execute = mysqli_query($conn, $query);

//Transactions
$loan_query ="UPDATE transcations set transc_amount='$amo', transc_date='$change_date'
where clientr_id='$client_id' and boss_id='$boss_id' and transc_date='$p_date' and transc_type='Cash_in' ";
$execute = mysqli_query($conn, $loan_query);

$error="Paying Loan";
mysqli_query($conn,"INSERT INTO errors(ts_id, boss_id, clients_id, error, pay_date, amount) 
VALUES (NULL, '$boss_id', '$client_id',  '$error', '$p_date', '$amo')");


echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 450px'>Payment Errors are Successfully Corrected!  
<a href='error_pay_loan_user.php?client_id=$client_id'  style='color:white; margin-left:100px;''>X</a></div>";

}

}

//EXPENSESS ERRORS
if(isset($_POST['expenses_error'])){  
$exp_id=$_POST['exp_id'];
$amount=$_POST['amount'];
$exp_date=$_POST['exp_date'];
$curr_date=date('Y-m-d');
$prev_date = date("Y-m-d", strtotime("$curr_date -1 day"));

if($exp_date!=$curr_date && $exp_date!=$prev_date){
 header("Location: error_others.php?error_expenses&Expenses");  
}
else{
$query ="UPDATE expenses set exp_date='$exp_date', cost='$amount' where exp_id='$exp_id'";
$execute = mysqli_query($conn, $query);

header("Location: error_others.php?Expenses&Success_Expenses");   
}
}

//Delete Errors

if(isset($_GET['exp_id'])){
$exp_id = $_GET['exp_id'];
 
$query ="DELETE from expenses where exp_id='$exp_id'";
$execute = mysqli_query($conn, $query);

header("Location:error_others.php?Deleted_Expenses&Expenses"); 
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
 header("Location: error_others.php?error_Banking&Banking");  
}
else{

$results = mysqli_fetch_assoc(mysqli_query($conn,"select user_id, boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, branch, 
users_image from new_users where username='$user_email'"));
$user_id = $results["user_id"];
$boss_id = $results["boss_id"];

$query ="UPDATE banking set de_date='$deposit_date', de_amount='$amount' where deposit_id='$deposit_id'";
$execute = mysqli_query($conn, $query);

$loan_query ="UPDATE transcations set transc_amount='$amount', transc_date='$deposit_date'
where boss_id='$boss_id' and user_id='$user_id' and transc_date='$p_date' and transc_type='Cash_in' ";
$execute = mysqli_query($conn, $loan_query);


header("Location: error_others.php?Banking&Success_Banking");   
}
}

//Delete Errors

if(isset($_GET['deposit_id'])){
$deposit_id = $_GET['deposit_id'];
 
$query ="DELETE from banking where deposit_id='$deposit_id'";
$execute = mysqli_query($conn, $query);

header("Location:error_others.php?Deleted_Banking&Banking"); 
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
 header("Location: error_others.php?error_Unknown&Unknown");  
}
else{
$query ="UPDATE uknown set rec_date='$rec_date', paid_amount='$amount' where uknown_id='$uknown_id'";
$execute = mysqli_query($conn, $query);

header("Location: error_others.php?Unknown&Success_Unknown");   
}
}

//Delete Errors

if(isset($_GET['unknown_id'])){
$unknown_id = $_GET['unknown_id'];
 
$query ="DELETE from uknown where uknown_id='$unknown_id'";
$execute = mysqli_query($conn, $query);

header("Location:error_others.php?Deleted_Unknown&Unknown"); 
}

//More_Mom_withdraw
if(isset($_POST['mom_withdraws_more'])){
$boss_id=$_POST['boss_id'];
$user_id=$_POST['user_id'];
$numb=$_POST['numb'];
$date=$_POST['with_date'];
$amo=$_POST['amount'];
$withdraw="MOM";
$client_id=0;
$reg_fee=0;
$mom=0;

//check the with saving balance
$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM total_mom  where phone='$numb' 
and user_id='$user_id' and boss_id='$boss_id'"));
$total = $results["total"];

if($amo>$total){
header("Location:mom_withdraws.php?more_entered"); 
}
else 
{
$total_amount=0;
$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM withdraws  where withdraws='MOM' and with_date='$date' and phone='$numb'
and user_id='$user_id' and boss_id='$boss_id'"));
$amount = $results["amount"];
$total_amount=$amo+$amount; 

$query ="UPDATE withdraws set amount='$total_amount' where withdraws='MOM' and with_date='$date' and phone='$numb'
and user_id='$user_id' and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);
//update total savings table

$balance=$total-$amo;

$query ="UPDATE total_mom set total='$balance' where phone='$numb' and user_id='$user_id' 
and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);

$names="MOM withdraws";
$transc_type="Cash_in";
mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$date', '$names',  '$transc_type', '$amo', '$reg_fee', '$mom')");

header("Location:mom_withdraws.php?success"); 
}
}

//allocate clients
if(isset($_POST['submit'])){   
$loc = $_POST["loc"];
$client_id= $_POST["client_id"]; 

for($i=0;$i<count($client_id); $i++){

$query ="UPDATE clients set b_location='$loc' where client_id='$client_id[$i]'";
$execute = mysqli_query($conn, $query);
}

header("Location:add_location_user.php?success");     
}
 
//========================banking
if(isset($_GET['banking'])){
$boss_id=$_GET['boss_id'];
$user_id=$_GET['user_id'];
$transc=$_GET['transc'];
$bank_name=$_GET['bank_name'];
$date=$_GET['b_date'];
$amo=str_replace(",","",$_GET['amo']);

$client_id=0;
$reg_fee=0;
$mom=0;
$curr_date=date('Y-m-d');
$pre_date = date('Y-m-d', strtotime("$curr_date -1 day"));
$sent_date=date("Y-m-d", strtotime($date));
  
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from sent_msgs where user_id='$user_id' 
and boss_id='$boss_id' order by ts_id DESC Limit 1")); 
$msg_date= $result['msg_date']; 

date_default_timezone_set("Africa/Nairobi");
$time=date("H:i:s");
$dateTime = new DateTime($time);
$dateTime=$dateTime->format('H:i:s');

if($sent_date>$curr_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>Error! Select Correct date. You are have selected a date above todate!
<a href='add_deposit.php' style='color:white; margin-left:100px;''>X</a></div>";
}
  
else if($sent_date<=$msg_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>You Have Already Sent Report. Not Allowed to make this Transaction
<a href='user_homepage.php' style='color:white; margin-left:70px;''>X</a></div>";
}
  
else{
function countExist($conn,$table,$key)
{
$k=mysqli_query($conn,"SELECT * FROM $table WHERE $key");
$rows=mysqli_num_rows($k);
return $rows;
}
 
$check1=countExist($conn,"banking","de_date='$date' AND transc='$transc' AND bank_name='$bank_name' and userde_id='$user_id' and bossde_id='$boss_id'");
$check2=countExist($conn,"total_banking","bank_name='$bank_name' and user_id='$user_id' and boss_id='$boss_id'");

$bank_balance=0;
$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM total_banking  where user_id='$user_id'and boss_id='$boss_id' and bank_name='$bank_name'"));
$bank_balance = $results["total"];

if ($transc=='Deposit') {
 
$total=0;
$total_saved=0;

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM total_banking  where user_id='$user_id' and boss_id='$boss_id' and bank_name='$bank_name'"));
$total = $results["total"];
$total_saved=$total+$amo;

$query ="UPDATE total_banking set total='$total_saved' where user_id='$user_id'and boss_id='$boss_id' and bank_name='$bank_name'";
$execute = mysqli_query($conn, $query);

mysqli_query($conn,"INSERT INTO banking(deposit_id, userde_id, bossde_id, transc, bank_name, de_date, de_amount) 
VALUES (NULL, '$user_id', '$boss_id', '$transc', '$bank_name', '$date', '$amo')");

$names="Bank Deposit"." ".$bank_name;
$transc_type="Cash_out";
mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$date', '$names',  '$transc_type', '$amo', '$reg_fee', '$mom')");

echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:5px; width: 500px'>Data is Successfully Saved
<a href='add_deposit.php' style='color:white; margin-left:100px;''>X</a>
</div>"; 
 
}


else{

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM total_banking  where user_id='$user_id'and boss_id='$boss_id' and bank_name='$bank_name'"));
$total = $results["total"];
$total_saved=$total-$amo;


$query ="UPDATE total_banking set total='$total_saved' where user_id='$user_id'and boss_id='$boss_id' and bank_name='$bank_name'";
$execute = mysqli_query($conn, $query);


mysqli_query($conn,"INSERT INTO banking(deposit_id, userde_id, bossde_id, transc, bank_name, de_date, de_amount) 
VALUES (NULL, '$user_id', '$boss_id', '$transc', '$bank_name', '$date', '$amo')");

$names="Bank Withdraw"." ".$bank_name; 
$transc_type="Cash_in";
mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$date', '$names',  '$transc_type', '$amo', '$reg_fee', '$mom')");

echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:5px; width: 500px'>Data is Successfully Saved
<a href='add_deposit.php' style='color:white; margin-left:100px;''>X</a>
</div>"; 

}
}
}

//Add Mom Charges
 
if(isset($_GET['mom_balance'])){
 
$boss_id=$_GET['boss_id'];
$user_id=$_GET['user_id'];
$date=$_GET['b_date'];
$amo=str_replace(",","",$_GET['amo']);
$names="MOM Charges";
$transc_type="Cash_in";

$total_saved=0;
$client_id=0;
$reg_fee=0;
$mom=0;
 
$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM total_savings where user_id='$user_id' and boss_id='$boss_id' and clientts_id='MOM'"));
$total = $results["total"];
$total_saved=$total+$amo;

$query ="UPDATE total_savings set total='$total_saved' where user_id='$user_id' and boss_id='$boss_id' and clientts_id='MOM'";
$execute = mysqli_query($conn, $query);

mysqli_query($conn,"INSERT INTO mom_balance(mom_id, userch_id, bossch_id, amount, ch_date) 
VALUES (NULL,  '$user_id', '$boss_id', '$amo', '$date')");

mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$date', '$names',  '$transc_type', '$amo', '$reg_fee', '$mom')");


echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:5px; width: 500px'>Data is Successfully Saved
<a href='add_charges.php' style='color:white; margin-left:100px;''>X</a>
</div>"; 
}

//mom_balance_withdraw

if(isset($_GET['mom_balance_withdraw'])){
 
$boss_id=$_GET['boss_id'];
$user_id=$_GET['user_id'];
$date=$_GET['b_date'];
$amo=str_replace(",","",$_GET['amo']);
$withdraw="Charges";

//check the with saving balance
$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM total_savings where user_id='$user_id' and boss_id='$boss_id' and clientts_id='MOM'"));
$total = $results["total"];

if($amo>$total){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:5px; width: 500px'>The Amount Entered is Greater than Total Saved 
<a href='withdraw_mom_charges.php' style='color:white; margin-left:100px;''>X</a>
</div>"; 
}
else 
{
$balance=0;
$balance=$total-$amo;

$query ="UPDATE total_savings set total='$balance' where user_id='$user_id' and boss_id='$boss_id' and clientts_id='MOM'";
$execute = mysqli_query($conn, $query);

mysqli_query($conn,"INSERT INTO withdraws(with_id, withdraws, user_id, boss_id, with_date, amount) 
VALUES (NULL, '$withdraw', '$user_id',  '$boss_id',  '$date', '$amo')");

echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:5px; width: 500px'>Data is Successfully Saved 
<a href='withdraw_mom_charges.php' style='color:white; margin-left:100px;''>X</a>
</div>";
}
}

//================================================Update Loan given date
if(isset($_GET['update_loan_date'])){
//--------------------------------------
$boss_id=$_GET['boss_id'];
$client_id=$_GET['client_id'];
$p_date=$_GET['b_date'];
$change_date=$_GET['date1'];
$loan_no=$_GET['loan_no'];


//completed Loan
$query ="UPDATE completed_loan set pay_date='$p_date' where loans_no=$loan_no and clientcpid='$client_id' and bosscpid='$boss_id'";
$execute = mysqli_query($conn, $query);
 
//clients with loans table
$query ="UPDATE clients_with_loan set pay_date='$p_date' where clientsid='$client_id' and bosseseid='$boss_id'";
$execute = mysqli_query($conn, $query);

//loan_pay table
$query ="UPDATE loans set b_date='$p_date' where cliente_id='$client_id' and bossese_id='$boss_id' and b_date='$p_date'";
$execute = mysqli_query($conn, $query);


echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 450px'>Payment Errors are Successfully Corrected!  
<a href='edit_loan_date2.php?client_id=$client_id'  style='color:white; margin-left:100px;''>X</a></div>";

}

//-===============ENTER SAVINGS
if(isset($_GET['savings'])){
$boss_id=$_GET['boss_id'];
$user_id=$_GET['user_id'];
$client_id=$_GET['client_id'];
$date=$_GET['save_date'];
$amo=str_replace(",","",$_GET['amo']);
$reg_fee=0;
$mom=4;
$loan_no=0;
$printed=0;

$curr_date=date('Y-m-d');
$pre_date = date('Y-m-d', strtotime("$curr_date -1 day"));
$sent_date=date("Y-m-d", strtotime($date));

//Record transaction
$transc_type="Cash_in";
$hup=mysqli_query($conn,"SELECT * FROM clients WHERE  client_id='$client_id' and users_id='$user_id' and bosses_id='$boss_id'");
$now=mysqli_fetch_array($hup);
$names=$now["firstname"]." ".$now["lastname"];


function countExist($conn,$table,$key)
{
$k=mysqli_query($conn,"SELECT * FROM $table WHERE $key");
$rows=mysqli_num_rows($k);
return $rows;
}

$check=countExist($conn,"savings","save_date='$date' AND clientsave_id='$client_id'");
$check2=countExist($conn,"total_savings","clientts_id='$client_id'");

if($sent_date>$curr_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>Error! Select Correct date. You are have selected a date above todate!
<a href='make_save.php?client_id=$client_id' style='color:white; margin-left:100px;''>X</a></div>";
}
 
else if($check==0)
{

if($check2==0)
{
mysqli_query($conn,"INSERT INTO total_savings(ts_id, clientts_id, user_id, boss_id, total) 
VALUES (NULL, '$client_id',  '$user_id', '$boss_id',  '$amo')");
}
else{
$total=0;
$total_saved=0;

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM total_savings  where clientts_id='$client_id' and user_id='$user_id'
and boss_id='$boss_id'"));
$total = $results["total"];
$total_saved=$total+$amo;


$query ="UPDATE total_savings set total='$total_saved' where clientts_id='$client_id' and user_id='$user_id' 
and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);
}

mysqli_query($conn,"INSERT INTO savings(save_id, clientsave_id, user_id, boss_id, save_date, amount) 
VALUES (NULL, '$client_id',  '$user_id', '$boss_id', '$date', '$amo')");

mysqli_query($conn,"INSERT INTO receipts(receipt_id, userrec_id, bossrec_id, clientrec_id, loan_norec_id, rec_date, paid_amount, balancerec, printed) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$loan_no', '$date', '$amo', '$total_saved', '$printed')");

mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$date', '$names',  '$transc_type', '$amo', '$reg_fee', '$mom')");


echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:5px; width: 900px'>
<table border='0'>
<tr>
<td width='500px'><font size='4'>Data Saved Successfully!</font></td>
<td> 
<form method='post' action='saving_receipt.php' target='_blank'>
<input type=hidden name='client_id' value='$client_id'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='pay_date' value='$date'>
<button type='submit' name=receipt class='button is-default' 
style='padding-top:1px; border: 1px solid #006F37; border-radius:4px; color:#006F37'>
&nbsp;Print Receipt &nbsp;</button>
</form>
</td>
<td>
<a href='search_client_save.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</td></tr></table>
</div>";         

}

else{
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 800px'> 
 
<table border='0'>
<tr>
<td width='500px'><font size='4'>The client has already Saved today!&nbsp;&nbsp; Do want to Save More?</font></td>
<td> 
<form method='post' action='user_connector.php'>
<input type=hidden name='client_id' value='$client_id'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='save_date' value='$date'>
<input type=hidden name='amount' value='$amo'>
<button type='submit' name=save_more class='button is-default' 
style='padding-top:0px; border: 0px solid #006F37; border-radius:4px; height:15px; color:#006F37; background-color:red; color:white'>
&nbsp; Yes &nbsp;</button>
</form>
</td>
<td>
<a href='search_client_save.php?reload=1' style='color:white; margin-left:100px;''>No</a>
</td></tr></table>

</div>";
}
}
//=============================Already Saved(Save More)
if(isset($_POST['save_more'])){  
$user_id=$_POST['user_id'];
$boss_id=$_POST['boss_id'];
$client_id=$_POST['client_id'];
$date=$_POST['save_date'];
$amo= $_POST['amount'];
$total=0;
$total_saved=0;
$reg_fee=0;
$mom=4;

//Record transaction
$transc_type="Cash_in";
$hup=mysqli_query($conn,"SELECT * FROM clients WHERE  client_id='$client_id' and users_id='$user_id' and bosses_id='$boss_id'");
$now=mysqli_fetch_array($hup);
$names=$now["firstname"]." ".$now["lastname"];

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM savings  where clientsave_id='$client_id' and user_id='$user_id'
 and boss_id='$boss_id' and save_date='$date'"));
$total = $results["amount"];
$total_saved=$total+$amo;

 

$query ="UPDATE savings set amount='$total_saved' where clientsave_id='$client_id' and user_id='$user_id' 
and boss_id='$boss_id'  and save_date='$date'";
$execute = mysqli_query($conn, $query);

//update total savings table

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM total_savings  where clientts_id='$client_id' and user_id='$user_id'
and boss_id='$boss_id'"));
$total = $results["total"];
$total_saved=$total+$amo;

$query ="UPDATE total_savings set total='$total_saved' where clientts_id='$client_id' and user_id='$user_id' 
and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);

mysqli_query($conn,"INSERT INTO receipts(receipt_id, userrec_id, bossrec_id, clientrec_id, loan_norec_id, rec_date, paid_amount, balancerec, printed) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$loan_no', '$date', '$amo', '$total_saved', '$printed')");

mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$date', '$names',  '$transc_type', '$amo', '$reg_fee', '$mom')");

header("Location: make_save.php?success&client_id=$client_id");
}

//-===============WITHDRAW MONEY
if(isset($_GET['withdraw'])){
$boss_id=$_GET['boss_id'];
$user_id=$_GET['user_id'];
$client_id=$_GET['client_id'];
$date=$_GET['with_date'];
$amo=str_replace(",","",$_GET['amo']);

$reg_fee=0;
$mom=4;
$loan_no=-1;
$printed=0;

//Record transaction
$transc_type="Cash_out";
$hup=mysqli_query($conn,"SELECT * FROM clients WHERE  client_id='$client_id' and users_id='$user_id' and bosses_id='$boss_id'");
$now=mysqli_fetch_array($hup);
$names=$now["firstname"]." ".$now["lastname"];


function countExist($conn,$table,$key)
{
$k=mysqli_query($conn,"SELECT * FROM $table WHERE $key");
$rows=mysqli_num_rows($k);
return $rows;
}
//check the with saving balance
$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM total_savings  where clientts_id='$client_id' and user_id='$user_id'
and boss_id='$boss_id'"));
$total = $results["total"];

$check=countExist($conn,"withdraw","with_date='$date' AND clientwith_id='$client_id'");

if($amo>$total){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 800px'>The amount entered is greater than Total Savings, the possible amount to withdraw
is $total
<a href='make_withdraw.php?client_id=$client_id' style='color:white; margin-left:100px;''>X</a></div>";
}
else if($check==0)
{
mysqli_query($conn,"INSERT INTO withdraw(with_id, clientwith_id, user_id, boss_id, with_date, amount) 
VALUES (NULL, '$client_id',  '$user_id', '$boss_id', '$date', '$amo')");

//update total savings table

$balance=$total-$amo;

$query ="UPDATE total_savings set total='$balance' where clientts_id='$client_id' and user_id='$user_id' 
and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);

mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$date', '$names',  '$transc_type', '$amo', '$reg_fee', '$mom')");

mysqli_query($conn,"INSERT INTO receipts(receipt_id, userrec_id, bossrec_id, clientrec_id, loan_norec_id, rec_date, paid_amount, balancerec, printed) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$loan_no', '$date', '$amo', '$balance', '$printed')");

echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:5px; width: 900px'>
<table border='0'>
<tr>
<td width='500px'><font size='4'>Data Saved Successfully!</font></td>
<td> 
<form method='post' action='withdraw_receipt.php' target='_blank'>
<input type=hidden name='client_id' value='$client_id'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='pay_date' value='$date'>
<button type='submit' name=receipt class='button is-default' 
style='padding-top:1px; border: 1px solid #006F37; border-radius:4px; color:#006F37'>
&nbsp;Print Receipt &nbsp;</button>
</form>
</td>
<td>
<a href='search_client_withdraw.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</td></tr></table>
</div>";
 
}

else{
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 800px'> 
 
<table border='0'>
<tr>
<td width='500px'><font size='4'>The client has already withdrawn today!&nbsp;&nbsp; Do want to withdraw More?</font></td>
<td> 
<form method='post' action='user_connector.php'>
<input type=hidden name='client_id' value='$client_id'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='with_date' value='$date'>
<input type=hidden name='amount' value='$amo'>
<button type='submit' name=withdraw_more class='button is-default' 
style='padding-top:0px; border: 0px solid #006F37; border-radius:4px; height:15px; color:#006F37; background-color:red; color:white'>
&nbsp; Yes &nbsp;</button>
</form>
</td>
<td>
<a href='search_client_withdraw.php?reload=1' style='color:white; margin-left:100px;''>No</a>
</td></tr></table>

</div>";
}
}
//=============================Already Withdrwan(withdraw More)
if(isset($_POST['withdraw_more'])){  
$user_id=$_POST['user_id'];
$boss_id=$_POST['boss_id'];
$client_id=$_POST['client_id'];
$date=$_POST['with_date'];
$amo= $_POST['amount'];
$total=0;
$total_withdrawn=0;
$total_with=0;

$reg_fee=0;
$mom=4;
$printed=0;
$loan_no=-1;

//Record transaction
$transc_type="Cash_out";
$hup=mysqli_query($conn,"SELECT * FROM clients WHERE  client_id='$client_id' and users_id='$user_id' and bosses_id='$boss_id'");
$now=mysqli_fetch_array($hup);
$names=$now["firstname"]." ".$now["lastname"];

//check the with saving balance
$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM total_savings  where clientts_id='$client_id' and user_id='$user_id'
and boss_id='$boss_id'"));
$total = $results["total"];

if($amo>$total){
header("Location: make_withdraw.php?more_entered&client_id=$client_id");
}

$search_query= mysqli_query($conn,"SELECT * FROM withdraw  where clientwith_id='$client_id' and user_id='$user_id'
and boss_id='$boss_id' and with_date='$date'");
while($returned_result = mysqli_fetch_assoc($search_query)){
 
$amount=$returned_result["amount"];
$total_with=$total_with+$amount;
}

$total_withdrawn=$total_with+$amo;

$query ="UPDATE withdraw set amount='$total_withdrawn' where clientwith_id='$client_id' and user_id='$user_id' 
and boss_id='$boss_id'  and with_date='$date'";
$execute = mysqli_query($conn, $query);

$balance=$total-$amo;
$query ="UPDATE total_savings set total='$balance' where clientts_id='$client_id' and user_id='$user_id' 
and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);

mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$date', '$names',  '$transc_type', '$amo', '$reg_fee', '$mom')");

mysqli_query($conn,"INSERT INTO receipts(receipt_id, userrec_id, bossrec_id, clientrec_id, loan_norec_id, rec_date, paid_amount, balancerec, printed) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$loan_no', '$date', '$amo', '$balance', '$printed')");


header("Location: make_withdraw.php?success&client_id=$client_id");
}

//==============ADD FINES

if(isset($_POST['fines'])){
$client_id=$_POST['client_id'];
$user_id=$_POST['user_id'];
$boss_id=$_POST['boss_id'];
$amount=$_POST['amount'];
$date=$_POST['f_date'];
$fine_type = "Fined";

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients_with_loan
where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'"));
$debt = $results["debt"];
$loan_no = $results["loan_no"];
$new_debt=$debt + $amount;

//last date paid
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loan_no' order by p_date desc limit 1"));     
$last_date = $result['p_date'];
  
  

mysqli_query($conn,"INSERT INTO clients_with_fines(ts_id, user_id, boss_id, clients_id, loan_id, pay_date, amount) 
VALUES (NULL,  '$user_id', '$boss_id', '$client_id', '$loan_no', '$date',  '$amount')");

mysqli_query($conn,"INSERT INTO loan_fines(ts_id, user_id, boss_id, clients_id, loan_id, pay_date, amount, fine_type) 
VALUES (NULL,  '$user_id', '$boss_id', '$client_id', '$loan_no', '$date',  '$amount', '$fine_type')");

//===UPDATE the loans
$query ="UPDATE clients_with_loan set debt='$new_debt' where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'";
$execute = mysqli_query($conn, $query);

header("Location:add_fine.php?success=$client_id"); 

}

//========================EXCESS AND SHORTAGE
if(isset($_GET['excess'])){
$boss_id=$_GET['boss_id'];
$user_id=$_GET['user_id'];
$officer_id=$_GET['officer_id'];
$excess=$_GET['excess'];
$date=$_GET['b_date'];
$amo=str_replace(",","",$_GET['amo']);
$recovered=0;
$type='Cash_in';
$client_id=0;
$mom=1;
$reg_fee=0;
$names='Exccess';


$curr_date=date('Y-m-d');
$sent_date=date("Y-m-d", strtotime($date));

// Check last sent message date
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from sent_msgs where user_id='$user_id' and boss_id='$boss_id' order by ts_id DESC Limit 1")); 
$msg_date= $result['msg_date'];

if($sent_date > $curr_date){
    echo"<div style='background-color:red; border-radius:5px; color:white; height:40px; margin-left:25px; padding:7px; width: 700px'>Error! Select Correct date. You have selected a date above today!
    <a href='enter_unknown_cash.php' style='color:white; margin-left:100px;''>X</a></div>";
}
else if($msg_date && $sent_date <= $msg_date){
    echo"<div style='background-color:red; border-radius:5px; color:white; height:40px; margin-left:25px; padding:7px; width: 700px'>You Have Already Sent Report. Not Allowed to make this entry.
    <a href='user_homepage.php' style='color:white; margin-left:70px;''>X</a></div>";
}
else {

        function countExist($conn,$table,$key)
        {
        $k=mysqli_query($conn,"SELECT * FROM $table WHERE $key");
        $rows=mysqli_num_rows($k);
        return $rows;
        }
        
        $check2=countExist($conn,"total_savings","clientts_id='$excess' AND user_id='$user_id' and boss_id='$boss_id'");
        $check=countExist($conn,"excess_short","rec_date='$date' AND excess_short='$excess' AND officer_id='$officer_id' AND userrec_id='$user_id' and bossrec_id='$boss_id'");
        $check1=countExist($conn,"shortage","rec_date='$date' AND officer_id='$officer_id' AND userrec_id='$user_id' and bossrec_id='$boss_id'");

        if ($check1==0 && $excess=='Shortage') {
        mysqli_query($conn,"INSERT INTO shortage(short_id, userrec_id, bossrec_id, officer_id, rec_date, paid_amount, recovered) 
        VALUES (NULL, '$user_id', '$boss_id', '$officer_id', '$date', '$amo', '$recovered')");

        echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
        height:40px; margin-left:25px; padding:5px; width: 500px'>Data is Successfully Saved
        <a href='add_excess_short.php' style='color:white; margin-left:100px;''>X</a>
        </div>"; 
        }

        else if ($check==0 && $excess=='Excess'){

        if($check2==0)
        {
        mysqli_query($conn,"INSERT INTO total_savings(ts_id, clientts_id, user_id, boss_id, total) 
        VALUES (NULL, '$excess',  '$user_id', '$boss_id', '$amo')");
        }
        else{
        $total=0;
        $total_saved=0;
        $withdrawn=0;

        $results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM total_savings  where clientts_id='$excess' and user_id='$user_id'
        and boss_id='$boss_id'"));
        $total = $results["total"];
        $total_saved=$total+$amo;

        $query ="UPDATE total_savings set total='$total_saved' where clientts_id='$excess' and user_id='$user_id' 
        and boss_id='$boss_id'";
        $execute = mysqli_query($conn, $query);
        }

        mysqli_query($conn,"INSERT INTO excess_short(excess_id, userrec_id, bossrec_id, officer_id, rec_date, excess_short, paid_amount, withdrawn) 
        VALUES (NULL, '$user_id', '$boss_id', '$officer_id', '$date', '$excess', '$amo', '$withdrawn')");

        mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
        VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$date', '$names',  '$type', '$amo', '$reg_fee', '$mom')");


        echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
        height:40px; margin-left:25px; padding:5px; width: 500px'>Data is Successfully Saved
        <a href='add_excess_short.php' style='color:white; margin-left:100px;''>X</a>
        </div>"; 
        }
        else{
        echo"<div style='background-color:red; border-radius:5px; color:white; 
        height:40px; margin-left:25px; padding:7px; width:930px'> 
        
        <table border='0' style='width:900px''>
        <tr>
        <td width='700px'><font size='4'>You have Already Entered $excess for Today!&nbsp;&nbsp; Do you want to Add More?</font></td>
        <td> 
        <form method='post' action='user_connector.php'>
        <input type=hidden name='user_id' value='$user_id'>
        <input type=hidden name='boss_id' value='$boss_id'>
        <input type=hidden name='officer_id' value='$officer_id'>
        <input type=hidden name='excess_date' value='$date'>
        <input type=hidden name='amount' value='$amo'>
        <input type=hidden name='excess' value='$excess'>
        <button type='submit' name=excess_more class='button is-default' 
        style='padding-top:10px; border: 0px solid #006F37; border-radius:4px; height:15px; color:#006F37; background-color:red; color:white'>
        &nbsp; Yes &nbsp;</button>
        </form>
        </td>
        <td>
        <a href='user_homepage.php?reload=1' style='color:white; margin-left:100px;''>No</a>
        </td></tr></table>

        </div>";
        }
    }
}

//=============================Already EXCESS OR SHORTAGE
if(isset($_POST['excess_more'])){  
$user_id=$_POST['user_id'];
$boss_id=$_POST['boss_id'];
$officer_id=$_POST['officer_id'];
$date=$_POST['excess_date'];
$excess=$_POST['excess'];
$amo= $_POST['amount'];
$total=0;
$total_excess=0;
$recovered=0;
$type='Cash_in';
$client_id=0;
$mom=1;
$reg_fee=0;
$names='Exccess';

$curr_date=date('Y-m-d');
$sent_date=date("Y-m-d", strtotime($date));

// Check last sent message date
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from sent_msgs where user_id='$user_id' and boss_id='$boss_id' order by ts_id DESC Limit 1")); 
$msg_date= $result['msg_date'];

if($sent_date > $curr_date){
    echo"<div style='background-color:red; border-radius:5px; color:white; height:40px; margin-left:25px; padding:7px; width: 700px'>Error! Select Correct date. You have selected a date above today!
    <a href='enter_unknown_cash.php' style='color:white; margin-left:100px;''>X</a></div>";
}
else if($msg_date && $sent_date <= $msg_date){
    echo"<div style='background-color:red; border-radius:5px; color:white; height:40px; margin-left:25px; padding:7px; width: 700px'>You Have Already Sent Report. Not Allowed to make this entry.
    <a href='user_homepage.php' style='color:white; margin-left:70px;''>X</a></div>";
}


if ($excess=='Excess') {
 
$search_query= mysqli_query($conn,"SELECT * FROM excess_short  where userrec_id='$user_id' and bossrec_id='$boss_id' and rec_date='$date' and excess_short='$excess' ");
while($returned_result = mysqli_fetch_assoc($search_query)){ 
$amount=$returned_result["paid_amount"];
$total=$total+$amount;
}

$total_excess=$total+$amo;
$query ="UPDATE excess_short set paid_amount='$total_excess' where userrec_id='$user_id' and bossrec_id='$boss_id'  and officer_id='$officer_id' and rec_date='$date' and excess_short='$excess'";
$execute = mysqli_query($conn, $query);

$total=0;
$total_saved=0;

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM total_savings where clientts_id='$excess' and user_id='$user_id'
and boss_id='$boss_id'"));
$total = $results["total"];
$total_saved=$total+$amo;

$query ="UPDATE total_savings set total='$total_saved' where clientts_id='$excess' and user_id='$user_id' 
and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);

mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$date', '$names',  '$type', '$amo', '$reg_fee', '$mom')");


header("Location: add_excess_short.php?success");
}

else{
$search_query= mysqli_query($conn,"SELECT * FROM shortage where userrec_id='$user_id' and bossrec_id='$boss_id' and rec_date='$date'");
while($returned_result = mysqli_fetch_assoc($search_query)){
 
$amount=$returned_result["paid_amount"];
$total=$total+$amount;
}

$total_excess=$total+$amo;

$query ="UPDATE shortage set paid_amount='$total_excess' where userrec_id='$user_id' and bossrec_id='$boss_id'  and officer_id='$officer_id' and rec_date='$date'";
$execute = mysqli_query($conn, $query);

header("Location: add_excess_short.php?success");
}
}

//===================Shortage Recovery
if(isset($_GET['shortage_recovery'])){
$short_id=$_GET['shortage_recovery'];
$date=date('Y-m-d');
$type='Shortage';
$transc_type='Cash_in';
$name='Shortage Recovered';


$curr_date=date('Y-m-d');
$sent_date=date("Y-m-d", strtotime($date));

// Check last sent message date
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from sent_msgs where user_id='$user_id' and boss_id='$boss_id' order by ts_id DESC Limit 1")); 
$msg_date= $result['msg_date'];

if($sent_date > $curr_date){
    echo"<div style='background-color:red; border-radius:5px; color:white; height:40px; margin-left:25px; padding:7px; width: 700px'>Error! Select Correct date. You have selected a date above today!
    <a href='enter_unknown_cash.php' style='color:white; margin-left:100px;''>X</a></div>";
}
else if($msg_date && $sent_date <= $msg_date){
    echo"<div style='background-color:red; border-radius:5px; color:white; height:40px; margin-left:25px; padding:7px; width: 700px'>You Have Already Sent Report. Not Allowed to make this entry.
    <a href='user_homepage.php' style='color:white; margin-left:70px;''>X</a></div>";
}

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM shortage where short_id='$short_id'"));
$amount = $results["paid_amount"];
$user_id = $results["userrec_id"];
$boss_id = $results["bossrec_id"];

$query ="UPDATE shortage set recovered=1 where short_id='$short_id'";
$execute = mysqli_query($conn, $query);

mysqli_query($conn,"INSERT INTO withdraws(with_id, withdraws, user_id, boss_id, with_date, amount) 
VALUES (NULL, '$type', '$user_id', '$boss_id', '$date', '$amount')");

mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, transc_date, transc_name, transc_type, transc_amount) 
VALUES (NULL, '$user_id', '$boss_id',  '$date', '$name',  '$transc_type', '$amount')");

header("Location:shortage_recovery.php"); 
}



//======================================UNKNOWN CASH
if(isset($_GET['unknown_cash'])){
    $boss_id=$_GET['boss_id'];
    $user_id=$_GET['user_id'];
    $date=$_GET['b_date'];
    $amo=str_replace(",","",$_GET['amo']);
    $officer_id=$_GET['officer_id'];
    $client_id=0;
    $reg_fee=0;
    $mom=0;

    $curr_date=date('Y-m-d');
    $sent_date=date("Y-m-d", strtotime($date));

    // Check last sent message date
    $result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from sent_msgs where user_id='$user_id' and boss_id='$boss_id' order by ts_id DESC Limit 1")); 
    $msg_date= $result['msg_date'];

    if($sent_date > $curr_date){
        echo"<div style='background-color:red; border-radius:5px; color:white; height:40px; margin-left:25px; padding:7px; width: 700px'>Error! Select Correct date. You have selected a date above today!
        <a href='enter_unknown_cash.php' style='color:white; margin-left:100px;''>X</a></div>";
    }
    else if($msg_date && $sent_date <= $msg_date){
        echo"<div style='background-color:red; border-radius:5px; color:white; height:40px; margin-left:25px; padding:7px; width: 700px'>You Have Already Sent Report. Not Allowed to make this entry.
        <a href='user_homepage.php' style='color:white; margin-left:70px;''>X</a></div>";
    }
    else {
        function countExist($conn,$table,$key)
        {
            $k=mysqli_query($conn,"SELECT * FROM $table WHERE $key");
            $rows=mysqli_num_rows($k);
            return $rows;
        }
        
        $check2=countExist($conn,"total_savings","clientts_id='$officer_id' AND user_id='$user_id' and boss_id='$boss_id'");

        if($check2==0)
        {
            mysqli_query($conn,"INSERT INTO total_savings(ts_id, clientts_id, user_id, boss_id, total) 
            VALUES (NULL, '$officer_id',  '$user_id', '$boss_id', '$amo')");
        }
        else{
            $total=0;
            $total_saved=0;
            $withdrawn=0;

            $results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM total_savings  where clientts_id='$officer_id' and user_id='$user_id' and boss_id='$boss_id'"));
            $total = $results["total"];
            $total_saved=$total+$amo;

            $query ="UPDATE total_savings set total='$total_saved' where clientts_id='$officer_id' and user_id='$user_id' and boss_id='$boss_id'";
            $execute = mysqli_query($conn, $query);
        }

        mysqli_query($conn,"INSERT INTO unknown_cash(ts_id, user_id, boss_id, unknown_date, amount, officer) 
        VALUES (NULL, '$user_id', '$boss_id', '$date', '$amo', '$officer_id')");

        $results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM unknown_cash where  user_id='$user_id' and boss_id='$boss_id' and unknown_date='$date' order by ts_id DESC LIMIT 1"));
        $ts_id= $results['ts_id'];

        $names="Unknown Cash";
        $transc_type="Cash_in";
        mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
        VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$date', '$names',  '$transc_type', '$amo', '$reg_fee', '$ts_id')");

        echo"<div style='background-color:#006F37; border-radius:5px; color:white; height:40px; margin-left:25px; padding:5px; width: 500px'>Data is Successfully Saved
        <a href='enter_unknown_cash.php' style='color:white; margin-left:100px;''>X</a></div>"; 
    }
} 

//====================================== withdraw_unknown_cash
if(isset($_GET['withdraw_unknown_cash'])){
    $boss_id=$_GET['boss_id'];
    $user_id=$_GET['user_id'];
    $date=$_GET['b_date'];
    $officer_id=$_GET['officer_id'];
    $amo=str_replace(",","",$_GET['amo']);

    $curr_date=date('Y-m-d');
    $sent_date=date("Y-m-d", strtotime($date));

    // Check last sent message date
    $result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from sent_msgs where user_id='$user_id' and boss_id='$boss_id' order by ts_id DESC Limit 1")); 
    $msg_date= $result['msg_date'];

    if($sent_date > $curr_date){
        echo"<div style='background-color:red; border-radius:5px; color:white; height:40px; margin-left:25px; padding:7px; width: 700px'>Error! Select Correct date. You have selected a date above today!
        <a href='withdraws.php' style='color:white; margin-left:100px;''>X</a></div>";
    }
    else if($msg_date && $sent_date <= $msg_date){
        echo"<div style='background-color:red; border-radius:5px; color:white; height:40px; margin-left:25px; padding:7px; width: 700px'>You Have Already Sent Report. Not Allowed to make this entry.
        <a href='user_homepage.php' style='color:white; margin-left:70px;''>X</a></div>";
    }
    else {
        function countExist($conn,$table,$key)
        {
            $k=mysqli_query($conn,"SELECT * FROM $table WHERE $key");
            $rows=mysqli_num_rows($k);
            return $rows;
        }

        //check the with saving balance
        $results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM total_savings where clientts_id='$officer_id' and user_id='$user_id' and boss_id='$boss_id'"));
        $total = $results["total"];

        $check=countExist($conn,"withdraw_unknown_cash","unknown_date='$date' AND officer='$officer_id' AND user_id='$user_id'");

        if($amo>$total){
            echo"<div style='background-color:red; border-radius:5px; color:white; height:40px; margin-left:25px; padding:7px; width: 900px'>
            The amount entered is greater than Total Savings, the possible amount to withdraw is $total
            <a href='withdraws.php' style='color:white; margin-left:100px;''>X</a></div>";
        }
        else if($check==0)
        {
            mysqli_query($conn,"INSERT INTO withdraw_unknown_cash(ts_id, user_id, boss_id, unknown_date, amount, officer) 
            VALUES (NULL, '$user_id', '$boss_id', '$date', '$amo', '$officer_id')");

            $names="Unknown Withdraws";
            $transc_type="Cash_in";
            $client_id=0;
            $reg_fee=0;
            $mom=1;
            
            mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
            VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$date', '$names',  '$transc_type', '$amo', '$reg_fee', '$mom')");

            //update total savings table
            $balance=$total-$amo;

            $query ="UPDATE total_savings set total='$balance' where clientts_id='$officer_id' and user_id='$user_id' and boss_id='$boss_id'";
            $execute = mysqli_query($conn, $query);

            echo"<div style='background-color:#006F37; border-radius:5px; color:white; height:40px; margin-left:25px; padding:7px; width: 400px'>Data Saved Successfully!
            <a href='search_client_withdraw.php' style='color:white; margin-left:100px;''>X</a></div>";
        }
        else{
            echo"<div style='background-color:red; border-radius:5px; color:white; height:40px; margin-left:25px; padding:7px; width: 1000px'> 
            <table border='0' width='100%''>
            <tr>
            <td width='650px'><font size='4'>You have have already withdrawn $withdraw today!&nbsp;&nbsp; Do want to withdraw More?</font></td>
            <td> 
            <form method='post' action='user_connector.php'>
            <input type=hidden name='withdraw' value='$withdraw'>
            <input type=hidden name='user_id' value='$user_id'>
            <input type=hidden name='boss_id' value='$boss_id'>
            <input type=hidden name='with_date' value='$date'>
            <input type=hidden name='amount' value='$amo'>
            <button type='submit' name=withdraws_more class='button is-default' 
            style='padding-top:0px; border: 0px solid #006F37; border-radius:4px; height:15px; color:#006F37; background-color:red; color:white'>
            &nbsp; Yes &nbsp;</button>
            </form>
            </td>
            <td>
            <a href='withdraws.php?reload=1' style='color:white; margin-left:100px;''>No</a>
            </td></tr></table>
            </div>";
        }
    }
}
?>
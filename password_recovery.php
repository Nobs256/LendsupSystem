<?php 
include("pages/conn.php");

if (isset($_POST['send_email'])) {
$email = $_POST['email'];
$subject = "Smartschools-Set your new Password";
$msg = "smartschools.xyz/passwoll5287rd_rhjlestHHH(KUBsjmvlnklnvnv.php";

$result = mysqli_fetch_assoc(mysqli_query($conn,"select lastname, username from new_users
where username='$email'"));     
$name= $result['lastname'];
$user_email= $result['username'];

if($email!=$user_email){
$error =0;
header("Location: forgot_password.php?error=$error");
}
else{
// Content-Type helps email client to parse file as HTML 
// therefore retaining styles
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$message = "<html>
<body>
<p>Dear ".$name.", <br>
Kindly set your password by clicking the link below</p>
<p>".$msg."</p>
<p>
There you will be able to use your Smart School account as usual.</p>
<p>Best Regards,<br>Smart Schools Team</p>
</body>
</html>";
if (mail($email, $subject, $message, $headers)) {
$s =1;
header("Location: forgot_password.php?s=$s"); 
}else{
$error =1235;
header("Location: forgot_password.php?error=$error"); 
}
}
}

//===================RESET THE PASSWORD
if (isset($_POST['rest_password'])) {
$email = $_POST['email'];
$pass = $_POST['password'];
$pass2=$_POST['password2'];

$result = mysqli_fetch_assoc(mysqli_query($conn,"select lastname, username from new_users
where username='$email'"));     
$name= $result['lastname'];
$user_email= $result['username'];

if($email!=$user_email){
$error =0;
header("Location: passwoll5287rd_rhjlestHHH(KUBsjmvlnklnvnv.php?error_email=$error"); 
}
else if($pass!=$pass2){
$error =0;
header("Location: passwoll5287rd_rhjlestHHH(KUBsjmvlnklnvnv.php?error_password=$error");
}
else{
$user_query ="update new_users set password='$pass' where username='$email'";
$execute = mysqli_query($conn,$user_query);
$s=1;
header("Location: passwoll5287rd_rhjlestHHH(KUBsjmvlnklnvnv.php?success=$s");
}
}

if (isset($_POST['paid'])) {
$user_id = $_POST['user_id'];
$boss_id = $_POST['boss_id'];
$amount=50000;
$date=date('Y-m-d');
$paid=1;

mysqli_query($conn,"INSERT INTO payments(pay_id, user_id, boss_id, amount, pay_date,  paid) 
VALUES (NULL, '$user_id', '$boss_id', '$amount', '$date', '$paid')");

$s=1;
header("Location: kyaabel.php?success=$s");
}
?>

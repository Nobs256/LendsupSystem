<?php

// We remove NOTICE, WARNING, DEPRECATED, and STRICT
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT);

// 2. Hide from the user's browser
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

// 3. Keep the logger alive for E_ERROR (Fatal) and E_PARSE (Syntax)
ini_set('log_errors', '1');

$s="";
if(isset($_POST['reload']))
{
$sec=$_POST['re'];
header("refresh: $sec");
}
include("conn.php"); 
session_start();
if((!$_SESSION['email'] && !$_SESSION['category']) || 
  ($_SESSION['email'] && $_SESSION['category']!="User")) {
    //if user is not logged in send user to index page
 header("Location: ../index.php");
 }
 
$user_email = $_SESSION['email'];
$results = mysqli_fetch_assoc(mysqli_query($conn,"select user_id, boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, branch, 
users_image from new_users where username='$user_email'"));
$user_id = $results["user_id"];
$boss_id = $results["boss_id"];
$bra = $results["branch"];
$user_firstname = $results['firstname'];
$user_lastname = $results['lastname'];
$user_image = $results['users_image'];
$subscription_payment_context = [
  'tenant_db' => $db_config,
  'tenant_id' => (int)($_SESSION['tenant_id'] ?? 0),
  'user_id' => (int)$user_id,
  'boss_id' => (int)$boss_id,
  'return_to' => 'home'
];
if($user_image == ""){
$user_image = "assets/images/users_images/user_sample.png";
}else{
$user_image = "assets/images/users_images/".$user_image;
}

// Allow Data Entry menu only for one specific user.
// Replace 12 with the actual user_id that should see it.
$allowed_data_entry_users = ['iganga']; // exact username
$show_data_entry = in_array($user_email, $allowed_data_entry_users, true);

$result = mysqli_fetch_assoc(mysqli_query($conn,"select boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
from bosses where boss_id='$boss_id'"));     
$company= $result['firstname'];

$d=date("Y-m-d");
$rec_date = date("Y-m-d", strtotime("$d -1 day"));

mysqli_query($conn,"DELETE FROM idTake WHERE take=0");
mysqli_query($conn,"DELETE FROM clients_with_loan WHERE debt=0");

$total_clients = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients where 
users_id='$user_id' and bosses_id='$boss_id'")); 

$loan_clients = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients_with_loan where 
userseid='$user_id' and bosseseid='$boss_id' and debt>0")); 
//total paid
$clients_paid = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM loan_pay where 
userse_id='$user_id' and bossese_id='$boss_id' and p_date='$d'")); 
 
$unprited = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM receipts where 
userrec_id='$user_id' and bossrec_id='$boss_id' and rec_date>='$rec_date' and printed=0")); 

//total_savings
$total_saved=0;
//total_savings
$total_known=0;
$total_saves=0;
$recovered_shortage=0;
$select = mysqli_query($conn,"SELECT * FROM uknown where userrec_id='$user_id' and bossrec_id='$boss_id' and known=0");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["paid_amount"]; 
$total_known+=$amount;
}

//shortage recovered
$select = mysqli_query($conn,"SELECT * FROM withdraws where  user_id='$user_id' and boss_id='$boss_id' and withdraws='Shortage'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$recovered_shortage+=$amount;
}

//total messages
$total_msg=0;
$select = mysqli_query($conn,"SELECT * FROM total_msg where user_id='$user_id' and boss_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["total"]; 
$total_msg+=$amount;
}

$select = mysqli_query($conn,"SELECT * FROM total_savings where user_id='$user_id' and boss_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["total"]; 
$total_saved+=$amount;
}
$total_saves=$total_known+$total_saved+$recovered_shortage;

//Subscription Payments
$curr_date=date('Y-m-d');
$first_date= date('Y-m-01');
$days="";
$warning="";
//remaining days
$d1 = new DateTime("$first_date 00:00:00");
$d2 = new DateTime("$curr_date 00:00:00");
$interval = $d2->diff($d1);
$remaining_day= $interval->d; 

$curr_month=date('m');
$curr_yr=date('Y');

$payments=mysqli_query($conn,"SELECT * FROM payments WHERE user_id='$user_id' and boss_id='$boss_id' 
  and MONTH(pay_date)='$curr_month' and paid=1 and YEAR(pay_date)='$curr_yr'");
$now=mysqli_fetch_array($payments);
$amount_paid=$now["amount"];

if($remaining_day==1){
$days="You are remaining with 1 day to be off";
}

if($amount_paid==0){
$warning="<div style='background-color:red; border-radius:5px; color:white; 
height:50px; margin-left:25px; padding:7px; width: 900px; font-size:15px'>
<font color=white>Your monthly subscription is not active. </font>
<a href='payments/subscription.php?return=home' target='subscriptionPayment' onclick='window.open(this.href, &quot;subscriptionPayment&quot;, &quot;width=560,height=650,resizable=yes,scrollbars=yes&quot;); return false;' style='color:white; margin-left:100px;'>Make Payment</a><br>$days
</div>";
}

$_SESSION['subscription_payment_context'] = $subscription_payment_context;

/*if($total_msg<500){

$warning="<div style='background-color:red; border-radius:5px; color:white; 
height:50px; margin-left:25px; padding:7px; width: 800px; font-size:18px'>
<font color=white>You Remaining with $total_msg messages. To buy sms send money to 0777842873</font>
<a href='#' style='color:white; margin-left:100px;''>X</a>
</div>";
}*/

?>
<!doctype html>
<html lang="en"> 
<head>
<!-- Required meta tags -->
<meta name="robots" content="index, follow" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="language" content="en-EN" />
<meta name="author" content="Irfan Maulana" />
<title> <?php echo $user_firstname." ".$user_lastname;  ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/bulma.min.css">
<link rel="stylesheet" href="dist/main.css">
<link rel="shortcut icon" href="assets/images/logo.ico" />

<script src="js/jquery.js"></script>
<!--====================SMS word Counter ==== -->
<script type="text/javascript">
function wordCounter()
{
var content=document.getElementById("msg").value;
var count=content.length;
var numsms=Math.ceil(count/159);
if(numsms==1)
{
document.getElementById("numofwords").innerHTML=count+" characters: "+numsms+" message";
}

else
{
document.getElementById("numofwords").innerHTML=count+" characters: "+numsms+" messages";
}
}
</script>
<style type="text/css">
//* Style the header */
.header {
  padding: 10px 16px;
  background-color:#6A6A6A;
  color: #f1f1f1;
}

/* Page content */
.content {
  padding: 30px;
}

/* The sticky class is added to the header with JS when it reaches its scroll position */
.sticky {
  position: fixed;
  top: 0;
  width: 100%
}

/* Add some top padding to the page content to prevent sudden quick movement (as the header gets a new position at the top of the page (position:fixed and top:0) */
.sticky + .content {
  padding-top: 102px;
}

/* ===============MENU==============*/
.topnav {
  overflow: hidden;
  background:url('assets/images/bg.jpg') 0 0; 
  height:30px;
}

.topnav a {
  float: left;
  color: #f2f2f2;
  text-align: center;
  padding-left: 20px;
   
  padding-bottom: 14px;
  text-decoration: none;
  font-size: 17px;
}

.topnav a:hover {
  background-color: #ddd;
  color: black;
}

.topnav a.active {
  background-color: #04AA6D;
  color: white;
}

/* Style the sidenav links and the dropdown button */
.sidenav a, .dropdown-btn {
padding: 6px 8px 6px 16px;
text-decoration: none;
font-size: 17px;
color: white;
display: block;
border: none;
border-radius: 2px;
background: #E4E4E4;
width: 95%;
text-align: left;
cursor: pointer;
outline: none;
margin-left: 7px;


}

/* On mouse-over */
.sidenav a:hover, .dropdown-btn:hover {
color: #f1f1f1;
}

/* Add an active class to the active dropdown button */
.active {
background-color: #D9FFD9;
color: black;
}

/* Dropdown container (hidden by default). Optional: add a lighter background color and some left padding to change the design of the dropdown content */
.dropdown-container {
display: none;
background-color: white;
padding-left: 8px;
margin-left: 7px;
width: 16%;
position : fixed;
 
}

/* Optional: Style the caret down icon */
.fa-caret-down {
float: right;
padding-right: 8px;
}

#topbar{
width: 60%;
margin-left:290px ;
padding: 10px 10px  10px  10px;
list-style: none;  
background:url('assets/images/bg.jpg') 0 0; 
border-radius:  2px;
background-repeat: no-repeat;
background-size: 100% 100%;
float: left;   
height: auto;  
border: 0px solid red;
font-size:15px;  
font-family:Arial;
}

#topbar_button{
border: 1px solid #006F37; 
border-radius:4px; 
font-size:17px; 
color: white;
background-color:#7C7C7C; 
height:30px; 
width:100px; 
}
#main_heading{ 
background-color:#EAEAEA;       
border-radius: 3px; 
color: #006F37;
height: auto;
list-style: none;  
margin-left: 300px;
border: 0px solid #006F37;
font-size:18px;
font-family: Arial;
width: 1100px;
}
#main_body{
padding:10px;
width: 1100px; 
background-color:white;
height:410px;
border-radius:4px;
border: 1px solid #006F37;
margin-left: 0px;
font-weight:normal;
overflow-y:scroll; 
}
#main_container{
padding:10px;
width: 1100px; 
height:430px;
margin-left: 330px;
margin-bottom:0px;
}

#footer{

background-color:#6A6A6A;       
color: white;
font-size:15px;
font-family: Arial;
font-weight:normal;
padding-left: 20px; 
padding-bottom: 30px; 
width:1300px; 
margin-top:0px;
position: fixed; 
height:90px; 
margin-left:285px; 

padding-top:5px;
} 
 

table[border="1"] {
  border-collapse: collapse !important;
  border: 1px solid #6A6A6A !important;
}
  
</style>
</head>
<body>
<header class="hero is-light" >
<div class="hero-head">
<nav class="navbar has-shadow" role="navigation" aria-label="main navigation" >
<div class="navbar-brand" style="background-color:#6A6A6A; ">
<img class="navbar-brand-logo" src="assets/images/logo.jpg" 
style="background-color:#6A6A6A; border-radius:35px; ">  

<font color="white" size="5"> <b>QuickAccounts</b></font>
<div align="center" style="width:500px; color:white; padding-top:10px;  margin-left:250px;">
<font size="4" face="arial" >   <b><?php echo $company; ?></b></font><br>
 </div> 
</div>
<div style="width:400px; background-color:#6A6A6A;"><a>&nbsp;&nbsp;&nbsp;&nbsp;</a>
<a>&nbsp;&nbsp;&nbsp;&nbsp;</a></div>      

<div class="navbar-item has-dropdown is-hoverable"> 
<a class="navbar-link" style="background-color:#6A6A6A;"> 
<img style="width: 30px; border-radius:6px;" src="<?php echo $user_image;?>"/> 
&nbsp;&nbsp;&nbsp;&nbsp; 
<font color="white">
<?php echo $user_firstname." ".$user_lastname; ?>
</font> </a> 
<div class="navbar-dropdown is-right">    
<?php echo "<a href = 'user_profile.php?user_id=". $user_id ."' class='navbar-item'>"?> 
<font color="black" size="4">   <span class="icon is-small"><i class="fa fa-user-o"></i> </span> User Profile  </font> </a> 
<hr class="navbar-divider">
<a  href="logout.php" class="navbar-item"> 
<font color="black" size="4">   <span class="icon is-small"><i class="fa fa-power-off"></i> </span> Logout </a>  </font></div>
</div>
</div>
</nav>
</div>
</header>

<div class="wrapper" style="background-color:white; height:600px;  position: fixed; overflow-x: hidden">
<div class="columns" style="width:2000px" style="background-color:#6A6A6A;">
<aside class="column is-2 aside" style="background-color:#E4E4E4; height:700px; width: 20%; margin-top:12px;  
 position: fixed; overflow-x: hidden">
<nav class="menu" style="background-color:#EAEAEA; width: 90%; margin-left:10px;  border-radius: 5px;  ">
<ul class="menu-list">
<li><a class="dd" href="user_homepage.php" style="background-color:#006F37; color:white">
<p align="center"><font size="4" face="Verdana, Arial, Helvetica, sans-serif">
Borrowers List</FONT>
</p></a></li>           
<li> <button class="dropdown-btn"><font color="black">+Clients</font>
<i class="fa fa-caret-down"></i>
</button>
<div class="dropdown-container">
  
<a href="add_clients.php" style="color:black; font-size:17px;"> >> Add a New Client</a>
<a href="view_clients.php" style="color:black; font-size:17px;"> >> Client List</a>
<a href="view_clients.php" style="color:black; font-size:17px;"> >> Update/Delete</a> 
<a href="search_client_book.php" style="color:black; font-size:17px;"> >> Client's Book</a>
<a href="sms_to_clients.php" style="color:black; font-size:17px;"> >> SMS to All Clients</a>
</div>

</li>
<li> <button class="dropdown-btn"><font color="black">+Reports</font>
<i class="fa fa-caret-down"></i>
</button>
<div class="dropdown-container">
<a href="defaulters.php" style="color:black; font-size:17px;"> >> All Defaulters</a>  
<a href="clients_not_paid.php" style="color:black; font-size:17px;"> >> Clients Not Paid</a>
<a href="clients_with_out.php" style="color:black; font-size:17px;"> >>Clients With Out Loans</a> 
<a href="all_loans_given_out.php" style="color:black; font-size:17px;"> >> All Loan Given out</a>
<a href="clients_completed_loan.php" style="color:black; font-size:17px;"> >> Completed Loans</a>
<a href="field_performance.php" style="color:black; font-size:17px;"> >> Officer Performance</a>
<a href="clients_with_fines.php" style="color:black; font-size:17px;"> >> Clients Fined</a>
<a href="clients_with_renews.php" style="color:black; font-size:17px;"> >> Clients Renewed</a>
</div>
</li>
<li>
<li> <button class="dropdown-btn"><font color="black">+Loan Actions</font>
<i class="fa fa-caret-down"></i>
</button>
<div class="dropdown-container"> 
  
<a href="search_client_give_loan.php" style="color:black; font-size:17px;"> >> Give Loan</a>
  <a href="search_client_give_loan_in_parts.php" style="color:black; font-size:17px;"> >> Give Loan in Parts</a>
<a href="search_client_pay_loan.php" style="color:black; font-size:17px;"> >> Loan Payment</a>
<a href="return_loan.php" style="color:black; font-size:17px;"> >> Return Loan</a>
<a href="search_client_renew.php" style="color:black; font-size:17px;"> >> Renew Loan</a>
 <a href="search_client_give_fine.php" style="color:black; font-size:17px;"> >> Add Fine </a>
<a href="add_excess_short.php" style="color:black; font-size:17px;"> >> Add Excess</a>
<a href="enter_unknown_cash.php" style="color:black; font-size:17px;"> >> Add Unknown Amount </a>

<!--<a href="search_client_topup.php" style="color:black; font-size:17px;"> >> Add Top-Up</a>
<a href="search_client_quickloan.php" style="color:black; font-size:17px;"> >> Quick Loan</a>-->
<!--<a href="search_client_pay_loan_entry.php" style="color:black; font-size:17px;"> >> Data Entry</a> -->
<!--<a href="search_client_clear_balance.php" style="color:black; font-size:17px;"> >> Clear the Balance</a>-->

</div>
</li>
<?php if ($show_data_entry): ?>
<li>
<li> <button class="dropdown-btn"><font color="black">+Data Entry</font>
<i class="fa fa-caret-down"></i>
</button>
<div class="dropdown-container">
  
<a href="add_client_data_entry.php" style="color:black; font-size:17px;"> >> Add Clients</a>
<a href="search_client_give_data_entry.php" style="color:black; font-size:17px;"> >> Enter Loans</a>
<a href="search_client_pay_loan_entry.php" style="color:black; font-size:17px;"> >> Change Balance</a>
<a href="search_client_clear_balance.php" style="color:black; font-size:17px;"> >> Clear the Balance</a>
<a href="enter_op.php" style="color:black; font-size:17px;"> >> Enter OP</a>

</div>
</li>
<?php endif; ?>

<!-- <li> <button class="dropdown-btn"><font color="black">+Mobile Money</font>
<i class="fa fa-caret-down"></i>
</button>
<div class="dropdown-container">
<a href="search_client_mom_pay.php" style="color:black; font-size:17px;"> >> Pay With MOM</a>
<a href="clients_paid_with_mom.php" style="color:black; font-size:17px;"> >> Clients Paid with MOM</a>
<a href="clients_paid_mom_no.php" style="color:black; font-size:17px;"> >> Clients with Number</a>
<a href="unknown.php" style="color:black; font-size:17px;"> >> Unknown Source</a>
<a href="add_charges.php" style="color:black; font-size:17px;"> >> Add Mom Charges</a>
</div>
</li>
-->
<li>
<button class="dropdown-btn"><font color="black">+Withdraws</font>
<i class="fa fa-caret-down"></i>
</button>
<div class="dropdown-container">  
<a href="withdraw_unknown_cash.php" style="color:black; font-size:17px;"> >> Unknown Cash</a>
<a href="withdraws.php" style="color:black; font-size:17px;"> >> Withdraw Excess</a>
 
<!--<a href="mom_withdraws.php" style="color:black; font-size:17px;"> >> MOM Withdraws</a>
<a href="unknown.php" style="color:black; font-size:17px;"> >> Unknown Source</a>
<a href="withdraw_mom_charges.php" style="color:black; font-size:17px;"> >> Withdraw Charges</a>
-->
</div>
</li>
<!--
<li> <button class="dropdown-btn"><font color="black">+Savings</font>
<i class="fa fa-caret-down"></i>
</button>
<div class="dropdown-container">
  
<a href="search_client_save.php" style="color:black; font-size:17px;"> >> Enter Savings</a>
<a href="search_client_withdraw.php" style="color:black; font-size:17px;"> >> Client Withdraws</a>
<a href="savings.php" style="color:black; font-size:17px;"> >> Client Saved</a>
<a href="unprinted_receipts_savings.php" style="color:black; font-size:17px;"> >> Saving Receipt</a>
</div>
</li>
-->
 
<li>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

</li>
<li>
<div style="background-color:white; width:96%; padding-left:10px; font-size:18px; 
border-radius: 3px; border: 3px solid #006F37; ">
<?Php include('cash_at_hand.php'); ?>
</div>
</li>
</ul>
</nav>
</aside>
<script>
/* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
var dropdown = document.getElementsByClassName("dropdown-btn");
var i;

for (i = 0; i < dropdown.length; i++) {
dropdown[i].addEventListener("click", function() {
this.classList.toggle("active");
var dropdownContent = this.nextElementSibling;
if (dropdownContent.style.display === "block") {
dropdownContent.style.display = "none";
} else {
dropdownContent.style.display = "block";
}
});
}
 
window.onscroll = function() {myFunction()};

var header = document.getElementById("myHeader");
var sticky = header.offsetTop;

function myFunction() {
  if (window.pageYOffset > sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
}
</script>


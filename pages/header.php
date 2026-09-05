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
if((!$_SESSION['email'] && !$_SESSION['category']) || ($_SESSION['email'] && $_SESSION['category']!="Admin")) {
    //if user is not logged in send user to index page
 header("Location: ../index.php");
 }
             
 
$user_email = $_SESSION['email'];
$results = mysqli_fetch_assoc(mysqli_query($conn,"select user_id, boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, 
users_image from new_users where username='$user_email'"));
$user_id = $results["user_id"];
$boss_id = $results["boss_id"];
$user_firstname = $results['firstname'];
$user_lastname = $results['lastname'];
$user_image = $results['users_image'];
if($user_image == ""){
$user_image = "assets/images/users_images/user_sample.png";
}else{
$user_image = "assets/images/users_images/".$user_image;
}
$result = mysqli_fetch_assoc(mysqli_query($conn,"select boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
  from bosses where boss_id='$boss_id'"));     
$company= $result['firstname'];
 
 
//preventing sql injection
function prevent_sql_injection($data){
$data = stripslashes($data);
$data = htmlspecialchars($data);
$data = trim($data);
return $data;
}


$total_clients = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients where 
bosses_id='$boss_id'")); 

$loan_clients = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients_with_loan where 
bosseseid='$boss_id' and debt>0"));

$total_users = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM new_users where 
boss_id='$boss_id' and category='User'"));

$total_ids= mysqli_num_rows(mysqli_query($conn,"SELECT * FROM idTake where  bossde_id='$boss_id' and take=1")); 

//total_savings
$total_saved=0;
//total_savings
$total_unknown=0;
$total_saves=0;
$select = mysqli_query($conn,"SELECT * FROM uknown where bossrec_id='$boss_id' and known=0");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["paid_amount"]; 
$total_unknown+=$amount;
}

$select = mysqli_query($conn,"SELECT * FROM total_savings where boss_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["total"]; 
$total_saved+=$amount;
}

//shortage recovered
$recovered_shortage=0;
$select = mysqli_query($conn,"SELECT * FROM withdraws where  boss_id='$boss_id' and withdraws='Shortage'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$recovered_shortage+=$amount;
}

$total_saves=$total_unknown+$total_saved+$recovered_shortage;
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

<style> 
/*Header Fixing  */
/* Style the header */
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
height:440px;
border-radius:4px;
border: 1px solid #006F37;
margin-left: 0px;
font-weight:normal;
overflow-y:scroll; 
}
#main_container{
padding:10px;
width: 1100px; 
height:450px;
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

</style>
</head>
<body>
<header class="header" id="myHeader" >
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
<?php echo "<a href = 'admin_profile.php?user_id=". $user_id ."' class='navbar-item'>"?> 
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
<li><a class="dd" href="admin_homepage.php" style="background-color:#006F37; color:white">
<p align="center"><font size="4" face="Verdana, Arial, Helvetica, sans-serif">
DAILY REPORT</FONT>
</p></a></li>           

<li> <button class="dropdown-btn"><font color="black">+Reports</font>
<i class="fa fa-caret-down"></i>
</button>
<div class="dropdown-container">
  
<a href="transactions_admin.php" style="color:black; font-size:17px;"> >> Transactions</a>
<a href="clients_with_loan_admin.php" style="color:black; font-size:17px;"> >> Clients with Loans</a>
<a href="clients_with_out_admin.php" style="color:black; font-size:17px;"> >> Clients with out Loans</a>
<a href="clients_paid_admin.php" style="color:black; font-size:17px;"> >> Clients Paid</a>
<a href="clients_not_paid_admin.php" style="color:black; font-size:17px;"> >> Clients Not Paid</a>
<a href="search_client_book_admin.php" style="color:black; font-size:17px;"> >> Client Book</a>
<a href="all_loans_given_out_admin.php" style="color:black; font-size:17px;"> >> All Loans Cash out</a>
<a href="mobile_money_report_admin.php" style="color:black; font-size:17px;"> >> Mobile Money</a> 
<a href="completed_loans_admin.php" style="color:black; font-size:17px;"> >> Completed Loans</a>
<a href="expenses_admin.php" style="color:black; font-size:17px;"> >> Expenses </a>
<a href="profit.php" style="color:black; font-size:17px;"> >> Monthly Profit</a>
</div>
</li>
<li> <button class="dropdown-btn"><font color="black">+Cashiers</font>
<i class="fa fa-caret-down"></i>
</button>
<div class="dropdown-container">
  
<a href="add_users.php" style="color:black; font-size:17px;"> >> Add New Cashier</a>
<a href="view_users.php" style="color:black; font-size:17px;"> >> List of Cashier</a>
<a href="view_users.php" style="color:black; font-size:17px;"> >> Update/Delete</a> 
 
</div>
</li>

<li> <button class="dropdown-btn"><font color="black">+Field Officers</font>
<i class="fa fa-caret-down"></i>
</button>
<div class="dropdown-container">
  
<a href="add_officer.php" style="color:black; font-size:17px;"> >> Add New Field Officer</a>
<a href="view_officer.php" style="color:black; font-size:17px;"> >> List Of Officers</a>
<a href="aging_field_officer.php" style="color:black; font-size:17px;"> >> Aging Analysis</a> 
<a href="view_officer.php" style="color:black; font-size:17px;"> >> Update/Delete</a> 
 
</li>

<li><a href="view_clients_admin.php" style="color:black; font-size:17px;"> >> View Clients</a> </li>
<li><a href="add_location_admin.php" style="color:black; font-size:17px;"> >> Assign Clients to Officers</a> </li>
<li><hr></li>
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
 


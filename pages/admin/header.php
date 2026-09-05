<?php
include("../conn.php"); 
session_start();
if(!$_SESSION['email']) {
    //if user is not logged in send user to index page
 header("Location: ../../index.php");
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
$user_image = "../assets/images/users_images/user_sample.png";
}else{
$user_image = "../assets/images/users_images/".$user_image;
}


  


//preventing sql injection
function prevent_sql_injection($data){
$data = stripslashes($data);
$data = htmlspecialchars($data);
$data = trim($data);
return $data;
}
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
<title>Admin <?php echo $user_firstname." ".$user_lastname;  ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../css/bulma.min.css">
<link rel="stylesheet" href="../dist/main.css">
<link rel="shortcut icon" href="../assets/images/logo.ico" />


<style> 
/* Style the sidenav links and the dropdown button */
.sidenav a, .dropdown-btn {
padding: 6px 8px 6px 16px;
text-decoration: none;
font-size: 17px;
color: white;
display: block;
border: none;
border-radius: 2px;
background: #999999;
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
background-color: #FFCC00;
color: black;
}

/* Dropdown container (hidden by default). Optional: add a lighter background color and some left padding to change the design of the dropdown content */
.dropdown-container {
display: none;
background-color: #999999;
padding-left: 8px;
margin-left: 7px;
width: 95%;

}

/* Optional: Style the caret down icon */
.fa-caret-down {
float: right;
padding-right: 8px;
}
#main_heading{
margin-left: 0px;
margin-bottom: 20px;
width: 500px;
padding-left: 20px; 
padding-top: 10px; 
padding-bottom: 10px; 
background-color:white;       
border-radius: 3px; 
color: #5E4EA0;
font-weight: bold;
height: auto;
list-style: none;  
border: 0px solid #5E4EA0;
font-size:16px;
font-family: Arial;
}
#main_body{
padding:10px;
width: 900px; 
height:auto;
border-radius:4px;
border: 1px solid #5E4EA0;
margin-left: 30px;

}
#footer{

background-color:#333333;       
color: white;
height: 80px; 
font-size:20px;
font-family: Arial;
padding-left: 20px; 
padding-top: 30px; 
padding-bottom: 30px; 

}
</style>
</head>
<body>
<header class="hero is-light">
<div class="hero-head">
<nav class="navbar has-shadow" role="navigation" aria-label="main navigation">
<div class="navbar-brand">
<a class="navbar-item is--brand"><img class="navbar-brand-logo" 
src="../assets/images/sslogo.png" alt="smartschools">  </a>
<div align="center" style="width:700px; color:white; 
margin-left:270px;">    
<font size="6"><b> QuickAccounts</b></font><br>
<font size="3">We Empower Businesses to the next Level</font></div> 
</div>
<div style="width:400px;"><a>&nbsp;&nbsp;&nbsp;&nbsp;</a>
<a>&nbsp;&nbsp;&nbsp;&nbsp;</a></div>      

<div class="navbar-item has-dropdown is-hoverable"> <a class="navbar-link" style="background:#FFCC00;"> 
<figure class="image is-32x32" style="margin-right:.10em;"> <img src="<?php echo $user_image;?>"/></figure>
&nbsp;&nbsp;&nbsp;&nbsp; 
<font color="black">
<?php echo $user_firstname." ".$user_lastname; ?>
</font> </a> 
<div class="navbar-dropdown is-right">    
<?php echo "<a href = 'update_admin_profile.php?user_id=". $user_id ."' class='navbar-item'>"?> 
<font color="black" size="4"> <b>  <span class="icon is-small"><i class="fa fa-user-o"></i> </span> User Profile </b> </font> </a> 
<hr class="navbar-divider">
<a  href="../logout.php" class="navbar-item"> 
<font color="black" size="4"> <b>  <span class="icon is-small"><i class="fa fa-power-off"></i> </span> Logout </a> </b> </font></div>
</div>
</div>
</nav>
</div>
</header>

<div class="wrapper" style="background-color:white; height:650px">
<div class="columns" style="width:2000px">
<aside class="column is-2 aside" style="background-color:#999999; height:1300px; ">
<nav class="menu" style="background-color:#CCCCCC; width: 90%; margin-left:10px;  border-radius: 5px; ">
<ul class="menu-list">

<li><a class="dd" href="admin_homepage.php" style="background-color:#5E4EA0; color:white">
<span class="icon is-small"><i class="fa fa-table"></i></span><b> DASHBOARD(Home Page)</b></a></li>            

<li><hr></li>
<li><a class="is-active" href="school_reg_form.php" style="background-color:#CCCCCC; color:black;">
<span class="icon is-small"><i class="fa fa-table"></i></span> <b> ADD/UPDATE/DELETE SCHOOL</b> </a></li>

<li><a class="is-active" href="#" style="background-color:#CCCCCC; color:black;">
<span class="icon is-small"><i class="fa fa-table"></i></span> <b>REPORTS</b></a></li>


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
</script>


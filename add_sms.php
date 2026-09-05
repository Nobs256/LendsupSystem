<?php 
include("pages/conn.php");

if (isset($_GET['user_id'])) {
$user_id = $_GET['user_id'];
$boss_id = $_GET['boss_id'];

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from new_users where user_id='$user_id' 
and boss_id='$boss_id'")); 
$branch = $results["branch"];
$name= strtoupper($results["firstname"]." ".$results["lastname"]);
$branch  = $results["branch"];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<meta name="robots" content="index, follow" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="language" content="en-EN" />
<meta name="author" content="Irfan Maulana" />
<title>QuickAccounts </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="pages/css/bulma.min.css">
<link rel="stylesheet" href="pages/dist/main.css">
<link rel="shortcut icon" href="pages/assets/images/logo.ico" />

<style>

#main_body{
padding-top:100px;
padding-left:200px;
width: 900px; 
height:auto;
border-radius:4px;
border: 0px solid #006F37;
margin-left: 200px;

}
#login_part{
width:800px; 
margin-left:50px; 
margin-top:0px; 
padding-left:10px;
padding-top:60px;
padding-right:10px;
border-radius:2px; 
border: 1px solid #006F37;
height:auto;
}

#main_heading{
margin-left:50px;
margin-bottom: 0px;
width: 800px;
padding-left: 20px; 
padding-top: 10px; 
padding-bottom: 10px; 
background-color:#006F37;       
border-radius: 2px; 
color: white;
height: auto;
list-style: none;  
border: 1px solid #006F37;
font-size:16px;
font-family: Arial;
}
</style>

</head>
<body>

<main class="column main">  
<div id="main_body"> 
<div id="main_heading" align="center">
<font size="5" face="Verdana, Arial, Helvetica, sans-serif">
QuickAccounts</FONT><br>
<font size="2" face="Verdana, Arial, Helvetica, sans-serif">
Empowering Businesses to the next Level</font></div>                     
<div id="login_part" >   
               
<?php
echo "<p align=center><b>ENTER SMS for $name - $branch </b></p>
<br>";
 
echo"
<table><tr><td>
<form method='POST' action='connector.php'>
<input type='hidden' name='user_id' value='$user_id'>
<input type='hidden' name='boss_id' value='$boss_id'>
</td></tr>
<tr>
<td>
Amount:</td><td> <input type='text' name='amount' style='height:35px; width:200px'><br><br></td>
</tr>
 
<tr>
<td></td>
<td>
<button type='submit' name='add_sms' style='border: 1px solid green; margin-left:8px; margin-top:5px; color:white; background-color:green;'>
&nbsp;&nbsp;Enter SMS &nbsp;&nbsp; </button> 
</form>

</td></tr></table>";
 
?>
</div>
</div> 
</div>
</main>
</body> 
</html>
<?php
}
?>

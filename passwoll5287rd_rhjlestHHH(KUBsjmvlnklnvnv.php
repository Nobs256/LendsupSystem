<?php
$s="";
if(isset($_POST['reload']))
{
$sec=$_POST['re'];
header("refresh: $sec");
}
if(isset($_GET['error_email'])){
$s = "<div style='background-color:red; border-radius:5px; color:white; 
height:60px; margin-left:0px; padding:7px; width: 320px'>
<font color=white>The Email Entered might be incorrect or Not Registered!!</font>
<a href='passwoll5287rd_rhjlestHHH(KUBsjmvlnklnvnv.php?reload=1' style='color:white; margin-left:50px;''>X</a>
</div>";
}
if(isset($_GET['error_password'])){
$s = "<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:0px; padding:7px; width: 330px'>
<font color=white>The password entered  does not match!!</font>
<a href='passwoll5287rd_rhjlestHHH(KUBsjmvlnklnvnv.php?reload=1' style='color:white; margin-left:5px;''>X</a>
</div>";
}
if(isset($_GET['success'])){
$s = "<div style='background-color:#5E4EA0; border-radius:5px; color:white; 
height:70px; margin-left:0px; padding:7px; width: 320px'>
<font color=white size=4>Password is successfully reset !!!!
<a href='index.php' style='color:red; margin-left:20px;'>Login from here</font></a>
</div>";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<meta name="robots" content="index, follow" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="language" content="en-EN" />
<meta name="author" content="Irfan Maulana" />
<title>SMARTSCHOOLS </title>
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
border: 0px solid #5E4EA0;
margin-left: 200px;

}
#login_part{
width:500px; 
margin-left:50px; 
margin-top:0px; 
padding-left:80px;
padding-top:60px;
padding-right:10px;
border-radius:2px; 
border: 1px solid #5E4EA0;
height:420px;
}

#main_heading{
margin-left:50px;
margin-bottom: 0px;
width: 500px;
padding-left: 20px; 
padding-top: 10px; 
padding-bottom: 10px; 
background-color:#5E4EA0;       
border-radius: 2px; 
color: white;
height: auto;
list-style: none;  
border: 1px solid #5E4EA0;
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
SMARTSCHOOLS</FONT><br>
<font size="2" face="Verdana, Arial, Helvetica, sans-serif">
Empowering Schools to the next Level</font></div>                     
<div id="login_part" >   
<p><?php echo $s;?></p>   
<br>                  
<form action="password_recovery.php"  method="post">                               
<p class="control is-expanded has-icons-left">
<input class="input is-success" type="email" name="email" placeholder="&nbsp;&nbsp;&nbsp;Enter Your Email" style="width:320px; font-size:20px; border: 1px solid #5E4EA0;" required>
<span class="icon is-small is-left">
<i class="fa fa-envelope"></i>
</span>
</p>
<br> 
<p class="control is-expanded has-icons-left">
<input class="input is-success" type="password" name="password" placeholder="&nbsp;&nbsp;&nbsp;Enter new password" style="width:320px; font-size:20px; border: 1px solid #5E4EA0;" minlength="6" required>
<span class="icon is-small is-left">
<i class="fa fa-envelope"></i>
</span>
</p>
<br> 
<p class="control is-expanded has-icons-left">
<input class="input is-success" type="password" name="password2" placeholder="&nbsp;&nbsp;&nbsp;Confirm password" minlength="6" style="width:320px; font-size:20px; border: 1px solid #5E4EA0;" required>
<span class="icon is-small is-left">
<i class="fa fa-envelope"></i>
</span>
</p>
<br>
<button type="submit" class="button is-primary"  name="rest_password" 
style="border: 1px solid #5E4EA0; color:white; background-color:#5E4EA0;">
&nbsp;&nbsp;&nbsp;&nbsp;Reset Your Password&nbsp;&nbsp;&nbsp;&nbsp;</button>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

</form>

</div>
</div> 
</div>
</main>
</body> 
</html>
<?php
$s="";
include('header_user.php');
if(isset($_GET['error_expenses'])){

// Wrong date
$s="<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>Error! You must Select a Current or Yesterday's Date
<a href='error_others.php?Expenses' style='color:white; margin-left:60px;''>X</a></div>";
}
if(isset($_GET['error_Banking'])){

$s="<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>Error! You must Select a Current or Yesterday's Date
<a href='error_others.php?Banking' style='color:white; margin-left:60px;''>X</a></div>";
}

if(isset($_GET['error_Unknown'])){

$s="<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>Error! You must Select a Current or Yesterday's Date
<a href='error_others.php?Unknown' style='color:white; margin-left:60px;''>X</a></div>";
}

//delete
if(isset($_GET['Deleted_Banking'])){

$s="<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>Banking Successfully Deleted
<a href='error_others.php?Banking' style='color:white''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X</a></div>";
}
if(isset($_GET['Deleted_Expenses'])){

$s="<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>Banking Successfully Deleted
<a href='error_others.php?Expenses' style='color:white''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X</a></div>";
}
if(isset($_GET['Deleted_Unknown'])){

$s="<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>Unknown Successfully Deleted
<a href='error_others.php?Unknown' style='color:white''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X</a></div>";
}


//Changed 
if(isset($_GET['Success_Banking'])){

$s="<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>Banking Successfully Changed
<a href='error_others.php?Banking' style='color:white''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X</a></div>";
}
if(isset($_GET['Success_Expenses'])){

$s="<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>Banking Successfully Changed
<a href='error_others.php?Expenses' style='color:white''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X</a></div>";
}
if(isset($_GET['Success_Unknown'])){

$s="<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>Unknown Successfully Changed
<a href='error_others.php?Unknown' style='color:white''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X</a></div>";
}
?>
 
<style>
 tr:nth-child(even) {
background-color: #D9FFD9;
}

th, td {
text-align: left;
padding-left: 10px;

}
</style> 
 
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> <?php include ('summary.php') ?>
<div id="main_heading">  
<table width="100%">
<tr>
<td><b>Data Entering Errors</b> </td>
<td></td>
<td><a href="error_others.php?Expenses"> <font color="#006F37">Expenses Errors </font></a></td>
<td><a href="error_others.php?Banking"> <font color="#006F37">Banking Errors </font></a></td>
<td><a href="error_others.php?Unknown"> <font color="#006F37">Unknown Errors </font></a></td>
</tr></table>
 
</div>

<div id="main_container">
<div id="main_body">   
<br>
<?php
echo $s;

if(isset($_GET['Expenses'])){
$curr_date=date('Y-m-d');
$prev_date = date("Y-m-d", strtotime("$curr_date -1 day"));
$j=0;

echo "<p align=center><b>Expenses</b></p>
<br>";
echo "<table width='90%' border=1 style='font-size:14px' >
<thead>
<tr>
<th>&nbsp;&nbsp;&nbsp;No</th>
<th>&nbsp;&nbsp;&nbsp;Date</th>
<th>&nbsp;&nbsp;&nbsp;Item</th>
<th>&nbsp;&nbsp;&nbsp;Cost</th>
<th>&nbsp;&nbsp;&nbsp;Correct Errors</th>
<th>&nbsp;&nbsp;&nbsp;Change</th>
<th>&nbsp;&nbsp;&nbsp;Delete</th>
</tr>";
$search_query= mysqli_query($conn,"SELECT * FROM expenses  where bossexp_id='$boss_id' and userexp_id='$user_id' 
and exp_date='$prev_date' OR exp_date='$curr_date'order by exp_date Desc");
while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;
$exp_id=$returned_result["exp_id"];
$item= $returned_result["item"];
$cost  = $returned_result["cost"];
$exp_date  = $returned_result["exp_date"];
echo"<tr>
<td>&nbsp;&nbsp;&nbsp;$j</td>
<td>&nbsp;&nbsp;&nbsp;".date("d-m-Y", strtotime($exp_date))."</td>
<td>&nbsp;&nbsp;&nbsp;$item</td>
<td>&nbsp;&nbsp;&nbsp;".number_format($cost)."</td>
<td>
<form method='post' action='user_connector.php'>
<input type=hidden name='exp_id' value='$exp_id'>
<input type=date name='exp_date' value=$exp_date style='width: 140px'>
<input type=text name='amount' value=$cost style='width: 90px'>
</td>
<td>&nbsp;&nbsp;&nbsp;
<button type='submit' name=expenses_error class='button is-default' 
style='padding-top:5px; border: 0px solid #D9FFD9; border-radius:4px; 
height:20px; background-color:#D9FFD9; color:green'>
&nbsp; <b>Change</b> &nbsp;</button>
</form></td>
<td><a href=user_connector.php?exp_id=$exp_id><font size=4 color=red><b>Delete</b></font></td>
</tr>";

}
echo"</table>";
}

//Banking errors========================
//=============== ======================

if(isset($_GET['Banking'])){
$curr_date=date('Y-m-d');
$prev_date = date("Y-m-d", strtotime("$curr_date -1 day"));
$j=0;

echo "<p align=center><b>Banking</b></p>
<br>";
echo "<table width='900' border=1>
<thead>
<tr>
<th>&nbsp;&nbsp;&nbsp;No</th>
<th>&nbsp;&nbsp;&nbsp;Date</th>
<th>&nbsp;&nbsp;&nbsp;Amount</th>
<th>&nbsp;&nbsp;&nbsp;Correct Errors</th>
<th>&nbsp;&nbsp;&nbsp;Change</th>
<th>&nbsp;&nbsp;&nbsp;Delete</th>
</tr>";
$search_query= mysqli_query($conn,"SELECT * FROM deposit  where bossde_id='$boss_id' and userde_id='$user_id' 
and de_date='$prev_date' OR de_date='$curr_date'order by de_date Desc");
while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;
$deposit_id=$returned_result["deposit_id"];
$de_amount  = $returned_result["de_amount"];
$de_date  = $returned_result["de_date"];
echo"<tr>
<td>&nbsp;&nbsp;&nbsp;$j</td>
<td>&nbsp;&nbsp;&nbsp;".date("d-m-Y", strtotime($de_date))."</td>
<td>&nbsp;&nbsp;&nbsp;".number_format($de_amount)."</td>
<td>
<form method='post' action='user_connector.php'>
<input type=hidden name='deposit_id' value='$deposit_id'>
<input type=date name='de_date' value=$de_date style='width: 140px'>
<input type=text name='amount' value=$de_amount style='width: 90px'>
</td>
<td>&nbsp;&nbsp;&nbsp;
<button type='submit' name=banking_error class='button is-default' 
style='padding-top:5px; border: 0px solid #D9FFD9; border-radius:4px; 
height:20px; background-color:#D9FFD9; color:green'>
&nbsp; <b>Change</b> &nbsp;</button>
</form></td>
<td><a href=user_connector.php?deposit_id=$deposit_id><font size=4 color=red><b>Delete</b></font></td>
</tr>";

}
echo"</table>";
}

//Uknown ERRORS========================
//=============== ======================

if(isset($_GET['Unknown'])){
$curr_date=date('Y-m-d');
$prev_date = date("Y-m-d", strtotime("$curr_date -1 day"));
$j=0;

echo "<p align=center><b>Unknown Source of Money</b></p>
<br>";
echo "<table width='900' border=1>
<thead>
<tr>
<th>&nbsp;&nbsp;&nbsp;No</th>
<th>&nbsp;&nbsp;&nbsp;Date</th>
<th>&nbsp;&nbsp;&nbsp;Amount</th>
<th>&nbsp;&nbsp;&nbsp;Correct Errors</th>
<th>&nbsp;&nbsp;&nbsp;Change</th>
<th>&nbsp;&nbsp;&nbsp;Delete</th>
</tr>";
$search_query= mysqli_query($conn,"SELECT * FROM uknown  where bossrec_id='$boss_id' and userrec_id='$user_id' 
and rec_date='$prev_date' OR rec_date='$curr_date' and known=0 order by rec_date Desc");
while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;
$uknown_id=$returned_result["uknown_id"];
$amount  = $returned_result["paid_amount"];
$rec_date  = $returned_result["rec_date"];
echo"<tr>
<td>&nbsp;&nbsp;&nbsp;$j</td>
<td>&nbsp;&nbsp;&nbsp;".date("d-m-Y", strtotime($rec_date))."</td>
<td>&nbsp;&nbsp;&nbsp;".number_format($amount)."</td>
<td>
<form method='post' action='user_connector.php'>
<input type=hidden name='uknown_id' value='$uknown_id'>
<input type=date name='rec_date' value=$rec_date style='width: 140px'>
<input type=text name='amount' value=$amount style='width: 90px'>
</td>
<td>&nbsp;&nbsp;&nbsp;
<button type='submit' name=unknown_error class='button is-default' 
style='padding-top:5px; border: 0px solid #D9FFD9; border-radius:4px; 
height:20px; background-color:#D9FFD9; color:green'>
&nbsp; <b>Change</b> &nbsp;</button>
</form></td>
<td><a href=user_connector.php?unknown_id=$uknown_id><font size=4 color=red><b>Delete</b></font></td>
</tr>";

}
echo"</table>";
}
?>

</div>
 
</div>
  
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>
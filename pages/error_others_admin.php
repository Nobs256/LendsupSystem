<?php
$s="";
$item="";
include('header.php');
if(isset($_GET['error_expenses'])){

// Wrong date
$s="<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>Error! You must Select a Current or Yesterday's Date
<a href='error_others_admin.php?Expenses' style='color:white; margin-left:60px;''>X</a></div>";
}
if(isset($_GET['error_Banking'])){

$s="<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>Error! You must Select a Current or Yesterday's Date
<a href='error_others_admin.php?Banking' style='color:white; margin-left:60px;''>X</a></div>";
}

if(isset($_GET['error_Unknown'])){

$s="<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>Error! You must Select a Current or Yesterday's Date
<a href='error_others_admin.php?Unknown' style='color:white; margin-left:60px;''>X</a></div>";
}

//delete
if(isset($_GET['Deleted_Banking'])){

$s="<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>Banking Successfully Deleted
<a href='error_others_admin.php?Banking' style='color:white''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X</a></div>";
}
if(isset($_GET['Deleted_Expenses'])){

$s="<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>Banking Successfully Deleted
<a href='error_others_admin.php?Expenses' style='color:white''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X</a></div>";
}
if(isset($_GET['Deleted_Unknown'])){

$s="<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>Unknown Successfully Deleted
<a href='error_others_admin.php?Unknown' style='color:white''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X</a></div>";
}


//Changed 
if(isset($_GET['Success_Banking'])){

$s="<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>Banking Successfully Changed
<a href='error_others_admin.php?Banking' style='color:white''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X</a></div>";
}
if(isset($_GET['Success_Expenses'])){

$s="<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>Expenses Successfully Changed
<a href='error_others_admin.php?Expenses' style='color:white''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X</a></div>";
}
if(isset($_GET['Success_Unknown'])){

$s="<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>Unknown Successfully Changed
<a href='error_others_admin.php?Unknown' style='color:white''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X</a></div>";
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
<p align="center"> <?php include ('summary_admin.php') ?>
<div id="main_heading">  

<table border="0" width="100%"><tr> 
<td width="40%"><b>Data Entering Errors</b> </td><td>Other Errors</td>
<td width="50%"> 
<form method="GET" action="error_others_admin.php"> 
<div class="select is-success">  
<select  name="branch"  style="width:180px; border: 1px solid #006F37; height:35px" required> 
<option>Branch</option>
<?php
$comb = mysqli_query($conn,"SELECT * from new_users where boss_id='$boss_id' and active=1 and category='User'");

while($select_comb = mysqli_fetch_array($comb)){
$branch=$select_comb['branch'];
 
echo "<option value= $branch> $branch </option>";
 
}
echo" 
</select></div>";
?>

<div class="select is-success">                   
<select  name="item"  style="width:180px; border: 1px solid #006F37; height:35px" required> 
 
<option value="Expenses">Expenses Errors</option> 
<option value="Banking">Banking Errors</option>
<option value="Unknown">Unknown Errors</option>
</select> 
</div>
 
 
 
<button type="submit" name="errors" class="button is-primary" 
style="border: 1px solid #006F37; border-radius:4px; color:white; background-color:#006F37">
&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;</button>
</label>   
</form>     
</td></tr></table>
 
</div>
<div id="main_container" style="height:400px">
<div id="main_body" style="height:390px"> 
<br>
<?php
echo $s;

if(isset($_GET['errors'])){
$item=$_GET['item'];
$branch=$_GET['branch'];

$curr_date=date('Y-m-d');
$prev_date = date("Y-m-d", strtotime("$curr_date -1 day"));
$j=0;

if($item=='Expenses'){

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from new_users where  boss_id='$boss_id' and category='User' and branch='$branch' and active=1"));
$user_id = $results["user_id"];

echo "<p align=center><b>Expenses Errors for $branch</b></p>
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
and exp_date='$prev_date' OR bossexp_id='$boss_id' and userexp_id='$user_id' and exp_date>='$pre order by exp_date Desc");
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
<form method='post' action='admin_connector.php'>
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
<td><a href=admin_connector.php?exp_id=$exp_id><font size=4 color=red><b>Delete</b></font></td>
</tr>";

}
echo"</table>";
}

//Banking errors========================
//=============== ======================

if($item=='Banking'){
$j=0;
$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from new_users where  boss_id='$boss_id' and category='User' and branch='$branch' and active=1"));
$user_id = $results["user_id"];

echo "<p align=center><b>Banking Errors for $branch</b></p>
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
and de_date>='$prev_date' order by de_date ");
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
<form method='post' action='admin_connector.php'>
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
<td><a href=admin_connector.php?deposit_id=$deposit_id><font size=4 color=red><b>Delete</b></font></td>
</tr>";

}
echo"</table>";
}

//Uknown ERRORS========================
//=============== ======================

if($item=='Unknown'){
$j=0;
$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from new_users where  boss_id='$boss_id' and category='User' and branch='$branch' and active=1"));
$user_id = $results["user_id"];

echo "<p align=center><b>Unknown Source Errors for $branch</b></p>
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
 and known=0 and rec_date>='$prev_date' order by rec_date Desc");
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
<form method='post' action='admin_connector.php'>
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
<td><a href=admin_connector.php?unknown_id=$uknown_id><font size=4 color=red><b>Delete</b></font></td>
</tr>";

}
echo"</table>";
}
}
?>

</div>
 
</div>
  
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>
<?php
include('header.php');
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
<p align="center"><?php include ('summary_admin.php') ?></p>
<div id="main_heading"> 
<table border="0">
<tr><td>
<b><b>SAVINGS</b> </b> 
</td><td>
<font color="#E4E4E4">------------------------------------------------------------------------------------------</font>
</td><td>
<form  method="post">Select Branch:  
<div class="select is-success"> 
<select  name="user_id"  style="width:180px; border: 1px solid #006F37; height:35px" required> 
<option>Branch</option>
<?php
$comb = mysqli_query($conn,"SELECT * from new_users where boss_id='$boss_id' and active=1 and category='User'");
while($select_comb = mysqli_fetch_array($comb)){
$branch=$select_comb['branch']; 
$user_id=$select_comb['user_id']; 
echo "<option value= $user_id> $branch </option>"; 
}
echo" 
</select></div>";
?>
<button type="submit" name="savings" class="button is-primary" 
style="background-color:#006F37; color:white; font-size:12px; border:1px solid #006F37; height:35px; margin-left:4px ">
&nbsp;OK&nbsp;</button></form> 
 
</td></tr></table></div>
 
<div id="main_container">
<div id="main_body">   
    
<br>  
<?php
$j=0;
if(isset($_POST['savings'])){
$user_id= $_POST['user_id'];

$returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM new_users where boss_id='$boss_id' and user_id='$user_id' "));     
$branch  = strtoupper($returned_result["branch"]);

echo "<p align=center>SAVINGS FROM <b>$branch</b></p><br><br>";
echo "<table width=60% border=1 align=center>
<thead>
<tr>
<th>No</th> 
<th>Names</th>
 
<th>Total</th> 
 

</tr>
</thead>
<tbody>";
//uknown
$total_unknown=0;
$select = mysqli_query($conn,"SELECT * FROM uknown where userrec_id='$user_id' and bossrec_id='$boss_id' and known=0");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["paid_amount"]; 
$total_unknown+=$amount;
}

//Excess
$total_excess=0;
$select = mysqli_query($conn,"SELECT * FROM total_savings where user_id='$user_id' and boss_id='$boss_id' and clientts_id='Excess'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["total"]; 
$total_excess+=$amount;
}
//shortage recovered
$recovered_shortage=0;
$select = mysqli_query($conn,"SELECT * FROM withdraws where  boss_id='$boss_id' and user_id='$user_id' and withdraws='Shortage'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$recovered_shortage+=$amount;
}
//MOM
$total_mom=0;
$select = mysqli_query($conn,"SELECT * FROM total_savings where user_id='$user_id' and boss_id='$boss_id' and clientts_id='MOM'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["total"]; 
$total_mom+=$amount;
}

//savings from clients
$total_s=0;
$select = mysqli_query($conn,"SELECT * FROM total_savings, clients where user_id='$user_id' and boss_id='$boss_id' and client_id=clientts_id ");
while($selected= mysqli_fetch_array($select)){ 
$amount= $selected["total"];  
$total_s+=$amount;
}
echo "<tr><td>1</td><td>Unknown</td><td>".number_format($total_unknown)."</td></tr>";
//echo "<tr><td>2</td><td>MOM Balance</td><td>".number_format($total_mom)."</td></tr>";
echo "<tr><td>3</td><td>Excess</td><td>".number_format($total_excess)."</td></tr>";
echo "<tr><td>4</td><td>Shortage_Recovered</td><td>".number_format($recovered_shortage)."</td></tr>";


 }
 else{
echo "Select Branch";

 echo"
<hr></hr>
";
}
  
?>
</div>
</div>
</div>
<div>
<?php include('footer.php') ; ?>
</div>
</main>
</body> 
</html>
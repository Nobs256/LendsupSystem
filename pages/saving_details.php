<?php
include('header_user.php');

if(isset($_POST['saving_details'])){
$boss_id=$_POST['boss_id'];
$user_id=$_POST['user_id'];
$officer_id=$_POST['officer_id'];
 
$returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and officer_id='$officer_id' "));     
$name = $returned_result["firstname"]. " ". $returned_result["lastname"];
$location  = $returned_result["location"];
$branch  = $returned_result["branch"];
$phone  = $returned_result["phone"];
}
?>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"><?php include ('summary.php') ?></p>
<br>
<br>
<div id="main_heading"><b>SAVINGS DETAILS FOR </b> <?php echo "$name" ?> </div> 
<div id="main_container">
<div id="main_body">   
    
<br>  
<?php
$j=0;

$total_saved=0;
$select = mysqli_query($conn,"SELECT * FROM unknown_cash where user_id='$user_id' and boss_id='$boss_id' and officer='$officer_id'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$total_saved+=$amount;
}

$total_withdrawn=0;
$select = mysqli_query($conn,"SELECT * FROM withdraws where user_id='$user_id' and boss_id='$boss_id' and withdraws='$officer_id'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$total_withdrawn+=$amount;
}

$j=0;
$total=0;
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from total_savings where boss_id='$boss_id' and user_id='$user_id' and clientts_id='$officer_id' "));     
$total=number_format($result['total']);

echo "TOTAL SAVINGS: ".number_format($total_saved)." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
TOTAL WITHDRAWN: " .number_format($total_withdrawn)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
REMAINING BALANCE: $total<br><br>";

echo "<table width=100% border=1>
<thead>
<tr>
<th>&nbsp;&nbsp;&nbsp;SAVINGS</th> 
<th>&nbsp;&nbsp;&nbsp;WITHDRAWS</th>
</tr>
<tr><td>";

echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo"<table><tr><td>&nbsp;&nbsp;Savings History &nbsp;&nbsp;&nbsp;</td> 
<td><form method='post' action='saving_history.php' target='_blank'>
<input type=hidden name='client_id' value='$client_id'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>

<input type=date name=date1 required>
<input type=date name=date2 required>
<button type='submit' name=receipt style='padding-top:1px; 
border: 1px solid #006F37; border-radius:2px; color:#006F37'>
&nbsp;<b>Print</b> &nbsp;</button></form></td></tr></table>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

echo "<table width=60% border=1 style='margin-left:10px'>
<thead>
<tr>
<th>&nbsp;&nbsp;No</th> 
<th>&nbsp;&nbsp;&nbsp;Date</th>
<th>&nbsp;&nbsp;&nbsp;Saved Amount</th>
</tr>
</thead>
<tbody>";

$search_query= mysqli_query($conn,"SELECT * FROM unknown_cash 
where  user_id='$user_id' and boss_id='$boss_id' and officer='$officer_id'  order by unknown_date");  
while($returned_result = mysqli_fetch_assoc($search_query)){

$save_date= $returned_result['unknown_date'];
$save_date=date("d-m-Y", strtotime($save_date));
$amount=number_format($returned_result['amount']);

$j++;
echo "<tr>
<td><font size=3>&nbsp;&nbsp; $j  </font></td>
<td><font size=3>&nbsp;&nbsp;&nbsp; $save_date  </font></td>
<td><font size=3>&nbsp;&nbsp;&nbsp; $amount</font></td>
</tr>";

}

echo "</tbody></table>
</td><td>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo"<table><tr><td>&nbsp;&nbsp;Withdraw History &nbsp;&nbsp;&nbsp;</td> 
<td><form method='post' action='withdraw_history.php' target='_blank'>
<input type=hidden name='client_id' value='$client_id'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>

<input type=date name=date1 required>
<input type=date name=date2 required>
<button type='submit' name=receipt style='padding-top:1px; 
border: 1px solid #006F37; border-radius:2px; color:#006F37'>
&nbsp;<b>Print</b> &nbsp;</button></form></td></tr></table>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "<table width=60% border=1 style='margin-left:10px'>
<thead>
<tr>
<th>&nbsp;&nbsp;No</th> 
<th>&nbsp;&nbsp;&nbsp;Date</th>
<th>&nbsp;&nbsp;&nbsp;Withdrawn Amount</th>
</tr>
</thead>
<tbody>";
$k=0;
$search_query= mysqli_query($conn,"SELECT * FROM withdraw, clients  
where boss_id='$boss_id' and user_id='$user_id' and clientwith_id='$client_id' and clientwith_id=client_id  order by with_date");  
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id=$returned_result["client_id"]; 
$with_date= $returned_result['with_date'];
$with_date=date("d-m-Y", strtotime($with_date));
$amounts=number_format($returned_result['amount']);
$k++;
echo "<tr>
<td><font size=3> &nbsp;&nbsp;$k  </font></td>
<td><font size=3>&nbsp;&nbsp;&nbsp; $with_date  </font></td>
<td><font size=3>&nbsp;&nbsp;&nbsp; $amounts</font></td>";
}
echo "</tr></table>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "</td></tr></tbody></table>";
 ?>
</div>
</div>
</div>
  
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>
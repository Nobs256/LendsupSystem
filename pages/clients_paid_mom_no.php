<?php
include('header_user.php');
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
<table border="0">
<tr><td>
<b>Clients Paid using MOM </b>
</td>
<td>
<font color="#E4E4E4">-----------------</font>
</td><td>
 
<form  method="post"> 
<div class="select is-success">  
<select  name="number"  style="width:180px; border: 1px solid #006F37; height:35px" required> 
<option>Mom Number</option>
<?php
$comb = mysqli_query($conn,"SELECT * from mom_phones where   user_id='$user_id' and boss_id='$boss_id'");

while($select_comb = mysqli_fetch_array($comb)){
$phone=$select_comb['phone'];
 
echo "<option value= $phone> $phone</option>";
 
}
echo" 
</select></div> ";
?>
From:<input type="date" name="date1" style="width:180px; border: 1px solid #006F37; height:35px" required>
TO:<input type="date" name="date2" style="width:180px; border: 1px solid #006F37; height:35px" required>

<button type="submit" name="paid" class="button is-primary" 
style="background-color:#006F37; color:white; font-size:12px; 
border:1px solid #006F37; height:35px; margin-left:4px ">
&nbsp;OK&nbsp;</button></form> 
</td><td>
 <a href="mom_paid_on_no.php">More</a>

</td></tr></table></div>
 
<div id="main_container" style="height:420px">
<div id="main_body" style="height:390px">   
    
<br>  
<?php
$j=0;
$total_amount_paid=0;
$total_amount=0;
if(isset($_POST['paid'])){  
$phone = $_POST['number']; 
$date1 = $_POST['date1']; 
$date2 = $_POST['date2']; 

$d1 = date('d-m-Y', strtotime("$date1"));
$d2 = date('d-m-Y', strtotime("$date2"));

 
echo "<table width=100% border=0 style='font-size:14px' align=center><tr><td>
<p align=left><font size=3>CLIENTS PAID USING MOM <b> $phone</b> BETWEEN <u>$d1</u> 
AND <u>$d2</u></font><br><br>
</td>
<td>
<form method='post' action='print_clients_paid_mom_no.php' target='_blank'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='phone' value='$phone'>
<input type=hidden name='date1' value='$date1'>
<input type=hidden name='date2' value='$date2'>
<button type='submit' name=daily_report style='border: 1px solid #7C7C7C; margin-left:200px; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:200px'>
&nbsp;Print Report&nbsp;</button></form>
</td></tr></table>
";
echo "<table width=80% border=1 style='font-size:14px' align=center>
<thead>
<tr>
<th>No</th>
<th>Date</th>
<th>Name</th>
<th>Amount</th>
<th>Withdraw</th>
<th>Balance</th> 
 
</tr>
</thead>
<tbody>";

$amount=0;
$total_amount=0;
  
$search_query= mysqli_query($conn,"SELECT * FROM mobile_numbers where 
usermo_id='$user_id' and bossmo_id='$boss_id' and mom_date<='$date2' and mom_date>='$date1' and phone='$phone' order by  mobile_id");
while($returned_result = mysqli_fetch_assoc($search_query)){

$names = $returned_result["names"];
$mom_date = $returned_result["mom_date"];
$withdraw = $returned_result["withdraw"];
$amount  = $returned_result["amount_mo"];
$curr_balance  = $returned_result["balance"];
 

$j++;

if($withdraw>0){
echo "<tr style='font-size:13px'>";
 
echo"
<td> $j </font></td>
<td><b>".date("d-m-Y", strtotime($mom_date))."</td>
<td><b>".strtoupper($names)."</b></td>
<td><b>".number_format($amount)."</b></td>
<td><b>".number_format($withdraw)."</b></td>
<td><b>".number_format($curr_balance)."</b></td>";
echo "</tr>";	
}
else{
echo "<tr style='font-size:13px'>";
 
echo"
<td> $j </font></td>
<td>".date("d-m-Y", strtotime($mom_date))."</td>
<td>".strtoupper($names)."</td>
<td>".number_format($amount)."</td>
<td>".number_format($withdraw)."</td>
<td>".number_format($curr_balance)."</td>";
echo "</tr>";
}
}
echo "<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>";

echo "<tr><td></td><td></td><td><b></td><td><b>TOTAL MOM:</td><td></td><td><b>".number_format($curr_balance)."</b></td></tr>";
echo "</tbody></table><br> ";
mysqli_close($conn);
 }
 else{
echo "Select Phone Number";
}
echo"
</div>

 
</div>";
include('footer.php');
?>
 </div>
</main>
</body> 
</html>
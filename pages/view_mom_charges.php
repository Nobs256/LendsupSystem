<?php
include('header_user.php');
?>
 <style>
 tr:nth-child(even) {
background-color: #D9FFD9;
}

th, td {
text-align: left;
padding-left: 1px;
 
}
</style>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> <?php include ('summary.php') ?>
 
<div id="main_heading"> 
<table><tr><td><b>MOM Charges</b> </td><td><font color="#EAEAEA">-----    /font></td>

<td>
<?php
echo "
<form method='post' action='view_mom_charges.php'>
From:<input type=date name=date1 required>
To:<input type=date name=date2 required>
<button type='submit' name=all_loans style='padding-top:1px; border: 1px solid #006F37; border-radius:4px; color:#006F37'>
&nbsp;OK &nbsp;</button></form>";
?>   
</td>
 </tr></table>
</div>     
 
<div id="main_container">
<div id="main_body">   
    
<br>  
<?php

if(isset($_POST['all_loans'])){
$date1 = $_POST['date1'];
$date2 = $_POST['date2'];
$j=0;
$total=0;
echo "<table width=100% border=0 style='font-size:14px' align=center><tr><td>
<p align=left><font size=4>MOM CHARGES BETWEEN <b>".date('d-m-Y', strtotime($date1))."</b>
 AND <b>".date('d-m-Y', strtotime($date2))."</b>
</font><br><br>
</td>
<td>
<form method='post' action='print_mom_charges.php' target='_blank'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='date1' value='$date1'>
<input type=hidden name='date2' value='$date2'>
<button type='submit' name=completed_loans style='border: 1px solid #7C7C7C; margin-left:200px; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:170px'>
&nbsp;Print Report&nbsp;</button></form>
</td></tr></table>";
$search_query= mysqli_query($conn,"SELECT * FROM mom_balance where userch_id='$user_id' and bossch_id='$boss_id' and ch_date>='$date1' and ch_date<='$date2' order by ch_date");
 echo" 
<table width=60% border=1 align=center>
<thead>
<tr> 
<th>No</th>
<th>Date</th> 
<th>Amount</th> 
</tr>
</thead>
<tbody>";

while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;

$amount = $returned_result["amount"];
$date = $returned_result["ch_date"];
$d=date("d-m-Y", strtotime($date));
 

$total+=$amount;
 
 
echo "<tr style=font-size:12px>
<td>&nbsp;&nbsp;&nbsp; $j  </td>
<td>&nbsp;&nbsp;&nbsp; $d  </td>
<td>&nbsp;&nbsp;&nbsp; ".number_format($amount)."</td>";
echo "</tr>";
}
echo "<tr style=font-size:12px>
<td> </td>
<td><b>TOTAL</b></td>
<td>&nbsp;&nbsp;&nbsp;<b>".number_format($total)."</b></td>";
echo "</tr>";
 
echo "</tbody></table>";
mysqli_close($conn);
}
 else{
 
 echo"Select the Dates  
<hr></hr>
";
}
 
 
?>
</div>
</div>
</div>
<div> 
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>
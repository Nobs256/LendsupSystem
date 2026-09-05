<?php
$sa="";
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
<hr> 
<div id="main_heading"> <b>
EXCESS ADDED</b> </div>  
<br>
<div id="main_container">
<div id="main_body" style="height:auto">   
<br>
 
                             
<?php
//Total Excess
$total_excess=0;
$select = mysqli_query($conn,"SELECT * FROM excess_short where userrec_id='$user_id' and bossrec_id='$boss_id' and excess_short='Excess'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["paid_amount"]; 
$total_excess+=$amount;
}

//Withdrawn Excess
$withdrawn_excess=0;
$select = mysqli_query($conn,"SELECT * FROM withdraws where user_id='$user_id' and boss_id='$boss_id' and withdraws='Excess'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$withdrawn_excess+=$amount;
}
//remaining Excess
$remain_excess=0;
$select = mysqli_query($conn,"SELECT * FROM total_savings where user_id='$user_id' and boss_id='$boss_id' and clientts_id='Excess'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["total"]; 
$remain_excess+=$amount;
}


echo "Total Excess Saved:<b> ".number_format($total_excess)."</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Total Excess Withdrwan:<b> " .number_format($withdrawn_excess)."</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Remaining Excess Balance:<b> ".number_format($remain_excess)."</b><br><br>

<table border=1 style='width:80%;'>
<thead>
<tr>
<th>No</th>
<th>Date</th> 
<th>Excess Added</th>
</tr>
</thead>
<tbody>";
$j=0;
$month=date('m');
$search_query= mysqli_query($conn,"SELECT * FROM excess_short  where userrec_id='$user_id' and bossrec_id='$boss_id'");

while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;
$amount =number_format($returned_result["paid_amount"]);
$date = $returned_result["rec_date"]; 
$d=date("d-m-Y", strtotime($date));
echo "<tr>
<td><font size=3> $j  </font></td>
<td><font size=3> $d</font></td>
<td><font size=3> $amount</font></td>";
echo "</tr>";
}
echo "</tbody></table>";
?>  
</div>
</div>
<div> 
 
</div>
</main>
</body> 
</html>


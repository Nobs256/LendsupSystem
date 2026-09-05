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
Shortage Recovered</b> </div>  
<br>
<div id="main_container">
<div id="main_body">   
                             
<?php

//Shortage
$total_shortage=0;
$select = mysqli_query($conn,"SELECT * FROM shortage where userrec_id='$user_id' and bossrec_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["paid_amount"]; 
$total_shortage+=$amount;
}

//Shortage Recovered
 
$total_shortage_recovered=0;
$select = mysqli_query($conn,"SELECT * FROM shortage where userrec_id='$user_id' and bossrec_id='$boss_id' and recovered=1");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["paid_amount"]; 
$total_shortage_recovered+=$amount;
}

//Shortage Remained
$total_shortage_remain=0;
$select = mysqli_query($conn,"SELECT * FROM shortage where userrec_id='$user_id' and bossrec_id='$boss_id' and recovered=0");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["paid_amount"]; 
$total_shortage_remain+=$amount;
}
 


echo "Total Shortages:<b> ".number_format($total_shortage)."</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Total Recovered Shortages:<b> " .number_format($total_shortage_recovered)."</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
UnRecovered Shortages:<b> ".number_format($total_shortage_remain)."</b><br><br>";

echo "<div class='table-responsive'>
<table border=1 style='width:80%;'>
<thead>
<tr>
<th>No</th>
<th>Date</th> 
<th>Shortage Recovered</th>
</tr>
</thead>
<tbody>";
$j=0;
$month=date('m');
$search_query= mysqli_query($conn,"SELECT * FROM shortage  where userrec_id='$user_id' and bossrec_id='$boss_id' and recovered=1");
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


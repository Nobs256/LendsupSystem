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
<p align="center"><?php include ('summary.php') ?></p>
<br>
<br>
<div id="main_heading"><b>SAVINGS</b>  </div> 
<div id="main_container">
<div id="main_body">   
    
<br>  
<?php
$j=0;


echo "<table width=70% border=1>
<thead>
<tr>
<th>No</th> 
<th>Item</th> 
<th>Total Amount</th> 
<th> </th> 
 

</tr>
</thead>
<tbody>";

//uknown
$total_known=0;
$select = mysqli_query($conn,"SELECT * FROM uknown where userrec_id='$user_id' and bossrec_id='$boss_id' and known=0");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["paid_amount"]; 
$total_known+=$amount;
}


//Excess
$total_excess=0;
$select = mysqli_query($conn,"SELECT * FROM total_savings where user_id='$user_id' and boss_id='$boss_id' and clientts_id='Excess'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["total"]; 
$total_excess+=$amount;
}

//unknown cash
$total_unknown_cash=0;
$select = mysqli_query($conn,"SELECT * FROM total_savings where user_id='$user_id' and boss_id='$boss_id' and clientts_id!='Excess'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["total"]; 
$total_unknown_cash+=$amount;
}

//Shortage
$total_shortage=0;
$select = mysqli_query($conn,"SELECT * FROM shortage where userrec_id='$user_id' and bossrec_id='$boss_id' and recovered=1");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["paid_amount"]; 
$total_shortage+=$amount;
}
//MOM
$total_mom=0;
$select = mysqli_query($conn,"SELECT * FROM total_savings where user_id='$user_id' and boss_id='$boss_id' and clientts_id='MOM'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["total"]; 
$total_mom+=$amount;
}
echo "<tr><td>1.</td><td>Excess</td><td>".number_format($total_excess)."</td><td><a href='excess_details.php'> Saving Details </a></td></tr>";
$j=1;
$search_query= mysqli_query($conn,"SELECT * FROM total_savings where user_id='$user_id' and boss_id='$boss_id'");  
while($returned_result = mysqli_fetch_assoc($search_query)){
$officer_id = $returned_result["clientts_id"];
$amount  = number_format($returned_result["total"]);

$returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and officer_id='$officer_id' "));     
$name =  $returned_result["firstname"]. " ". $returned_result["lastname"];
$location  = $returned_result["location"];
$branch  = $returned_result["branch"];
$phone  = $returned_result["phone"];

$j++;

echo "<tr>
<td><font size=3> $j  </font></td>
<td><font size=3> $name  </font></td>
<td><font size=3> $amount</font></td>
<td><font size=3> 
<form method='post' action='saving_details.php'>
<input type=hidden name='officer_id' value='$officer_id'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<button type='submit' name=saving_details style='padding-top:1px; border: 1px solid white; 
color:#006F37; background-color:white; font-size:16px'>
&nbsp;Saving Details &nbsp;</button>
</form>

</font></td>";
}
echo "</tr>";
echo "</tbody></table>";
mysqli_close($conn);
 
 echo"
<hr></hr>
";
echo"
</div>
</div>
 "; 

include('footer.php');  
?>
</div>
</main>
</body> 
</html>
<?php
$s="";
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
VIEW EXCESS/SHORTAGE</b>
<a href="search_client_excess.php" style="border: 1px solid green; margin-left:600px; border-radius:1px; font-size:17px; color: green; background-color:white; height:30px; width:170px"
class="button is-default">Add Excess</a>
<a href="add_excess_short.php" style="border: 1px solid green; margin-left:10px; border-radius:1px; font-size:17px; color: green; background-color:white; height:30px; width:190px"
class="button is-default">Add Without Client</a>
<a href="shortage_recovery.php" style="border: 1px solid green; margin-left:10px; border-radius:1px; font-size:17px; color: green; background-color:white; height:30px; width:160px"
class="button is-default">Recover Shortage</a>

</div>

<br>
<div id="main_container">
<div id="main_body">
<br>
  <?php echo $s;?>
  <br>

<?php
$j=0;
$month=date('m');
$search_query= mysqli_query($conn,"SELECT * FROM excess_short  where userrec_id='$user_id' and bossrec_id='$boss_id' order by rec_date Desc");

echo "<div class='table-responsive'>
<table border=1 style='width:80%;'>
<thead>
<tr>
<th>No</th>
<th>Date</th>
<th>Field Officer</th>
<th>Client</th>
<th>Excess/Shortage</th>
<th>Amount</th>

</tr>
</thead>
<tbody>";

while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;

$amount =number_format($returned_result["paid_amount"]);
$date = $returned_result["rec_date"];
$excess_short = $returned_result["excess_short"];
$officer_id = $returned_result["officer_id"];
$officer_name = 'Not assigned';
if($officer_id){
	$officer_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT firstname, lastname FROM officers WHERE officer_id='$officer_id' AND boss_id='$boss_id'"));
	if($officer_result){
		$officer_name = $officer_result['firstname'].' '.$officer_result['lastname'];
	}
}
$d=date("d-m-Y", strtotime($date));

//attached client
$cl_name='-';
if($returned_result["client_id"]){
$cl_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT firstname, lastname FROM clients WHERE client_id='".$returned_result["client_id"]."'"));
if($cl_result){
$cl_name = $cl_result['firstname'].' '.$cl_result['lastname'];
}
}

echo "<tr>
<td><font size=3> $j  </font></td>
<td><font size=3> $d</font></td>
<td><font size=3> $officer_name</font></td>
<td><font size=3> $cl_name</font></td>
<td><font size=3> $excess_short</font></td>
<td><font size=3> $amount</font></td>";
echo "</tr>";
}


$search_query= mysqli_query($conn,"SELECT * FROM shortage  where userrec_id='$user_id' and bossrec_id='$boss_id' order by rec_date Desc");
while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;

$amount =number_format($returned_result["paid_amount"]);
$date = $returned_result["rec_date"];
$excess_short ='Shortage';
$officer_id = $returned_result["officer_id"];
$officer_name = 'Not assigned';
if($officer_id){
	$officer_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT firstname, lastname FROM officers WHERE officer_id='$officer_id' AND boss_id='$boss_id'"));
	if($officer_result){
		$officer_name = $officer_result['firstname'].' '.$officer_result['lastname'];
	}
}
$d=date("d-m-Y", strtotime($date));

//attached client
$cl_name='-';
if($returned_result["client_id"]){
$cl_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT firstname, lastname FROM clients WHERE client_id='".$returned_result["client_id"]."'"));
if($cl_result){
$cl_name = $cl_result['firstname'].' '.$cl_result['lastname'];
}
}

echo "<tr>
<td><font size=3> $j  </font></td>
<td><font size=3> $d</font></td>
<td><font size=3> $officer_name</font></td>
<td><font size=3> $cl_name</font></td>
<td><font size=3> $excess_short</font></td>
<td><font size=3> $amount</font></td>";
echo "</tr>";
}
echo "</tbody></table>";
?>
</div>
</div>
</div>
 <?php include('footer.php'); ?>
</div>
</main>
</body>
</html>
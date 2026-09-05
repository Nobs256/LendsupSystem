<?php 
session_start();
ob_start();

ini_set('memory_limit', ' 1969994M'); // or you could use 1G
ini_set('max_execution_time', 3600); //300 seconds = 5 minutes
include("conn.php");?>
 
<?php
if(isset($_POST['daily_report'])){       
 
$user_id = $_POST['user_id']; 
$boss_id = $_POST['boss_id'];  
$phone = $_POST['phone'];
$date1 = $_POST['date1'];
$date2 = $_POST['date2'];

$d1 = date('d-m-Y', strtotime("$date1"));
$d2 = date('d-m-Y', strtotime("$date2"));
 
 

//get branch
$results = mysqli_fetch_assoc(mysqli_query($conn,"select user_id, boss_id, ucase(branch) as branch from new_users 
where user_id='$user_id' and boss_id='$boss_id'")); 
$branch = $results["branch"];

//company
$result = mysqli_fetch_assoc(mysqli_query($conn,"select boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
from bosses where boss_id='$boss_id'"));     
$company= $result['firstname'];

echo "<p align=center><b>$company LTD<br>CLIENTS PAID USING MOM ON <u>".$phone."</u> ".$branch." BRANCH<br>FROM $d1 TO $d2</b><br><hr>";  



echo "<table width=80% border=1 style='font-size:14px' align=center>
<thead>
<tr>
<th>No</th>
<th>Date</th>
<th>Name</th>
<th>Amount</th>
 
</tr>
</thead>
<tbody>";

$amount=0;
$total_amount=0;
$curr_balance=0;


  
$search_query= mysqli_query($conn,"SELECT * FROM mobile, clients  where 
usermo_id='$user_id' and bossmo_id='$boss_id' and mom_date<='$date2' and mom_date>='$date1' and phone_sent='$phone'  and clientmo_id=client_id order by  mom_date");
while($returned_result = mysqli_fetch_assoc($search_query)){

$names = $returned_result["firstname"]." ".$returned_result["lastname"];
$mom_date = $returned_result["mom_date"];
$amount  = $returned_result["amount_mo"];
$curr_balance+=$amount;
$j++;

echo "<tr style='font-size:13px'>";
 
echo"
<td> $j </font></td>
<td>".date("d-m-Y", strtotime($mom_date))."</td>
<td>".strtoupper($names)."</td>
<td>".number_format($amount)."</td>";
echo "</tr>";	
}
 $total_unknown=0;
$search_query= mysqli_query($conn,"SELECT * FROM uknown where 
userrec_id='$user_id' and bossrec_id='$boss_id' and rec_date<='$date2' and rec_date>='$date1' 
and tel='$phone'");
while($returned_result = mysqli_fetch_assoc($search_query)){

$rec_date = $returned_result["rec_date"];
$amount2  = $returned_result["paid_amount"];
$total_unknown+=$amount2;
$names='Unknown Source';
$j++;

echo "<tr style='font-size:13px'>";
 
echo"
<td> $j </font></td>
<td>".date("d-m-Y", strtotime($mom_date))."</td>
<td>".strtoupper($names)."</td>
<td>".number_format($amount2)."</td>";
echo "</tr>";	
}

echo "<tr><td></td><td></td><td></td><td></td> </tr>";

echo "<tr><td></td><td> <b>TOTAL MOM:</td><td></td><td><b>".number_format($curr_balance+$total_unknown)."</b></td></tr>";
echo "</tbody></table><br> ";
mysqli_close($conn);
 }
include("mpdf60/mpdf.php");
$mpdf=new mPDF('P','A3'); 
$mpdf = new mPDF(); $stylesheet = file_get_contents('mpdf60/pdf.css'); $mpdf->WriteHTML($stylesheet,1);
$mpdf->SetDisplayMode('fullpage');
//$mpdf->AddPage("L");

// LOAD a stylesheet
$stylesheet = file_get_contents('mpdf60/mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
$report_content=ob_get_contents();
ob_clean();

$mpdf->WriteHTML($report_content,2);
$mpdf->Output('RECIEPT.pdf','I');
exit;
?>
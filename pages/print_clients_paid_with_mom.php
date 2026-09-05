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
$d = $_POST['date'];
 


//Total deposite using MOM
$total_amount_mom=0;
$select = mysqli_query($conn,"SELECT * FROM loan_pay where  p_date='$d'
and userse_id='$user_id' and bossese_id='$boss_id' and mom=1 ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_paid"]; 
$total_amount_mom+=$amount;
}

//get branch
$results = mysqli_fetch_assoc(mysqli_query($conn,"select user_id, boss_id, ucase(branch) as branch from new_users 
where user_id='$user_id' and boss_id='$boss_id'")); 
$branch = $results["branch"];

//company
$result = mysqli_fetch_assoc(mysqli_query($conn,"select boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
from bosses where boss_id='$boss_id'"));     
$company= $result['firstname'];

echo "<p align=center><b>$company LTD<br>CLIENTS PAID USING MOM ON <u>".date("d-m-Y", strtotime($d))."</u> ".$branch." BRANCH</b><br><hr>";  
echo "<table width=100% border=1 style='font-size:12px' align=center>
<thead>
<tr>
<th>No</th>
<th>Name</th>
<th>Phone</th>
<th>Amount Sent</th>
<th>Amout Paid</th>
<th>Sent On</th> 
 
</tr>
</thead>
<tbody>";


$j=0;
$closing=0;
$closing=$total_op;
$search_query= mysqli_query($conn,"SELECT * FROM mobile, clients where users_id='$user_id' and bosses_id='$boss_id' and 
mom_date='$d' and client_id=clientmo_id order by phone_sent");
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id = $returned_result["client_id"]; 
$names = $returned_result["firstname"]." ".$returned_result["lastname"];
$phone = $returned_result["phone_sent"];
$amount  = $returned_result["amount_mo"];

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from loan_pay where clients_id='$client_id' 
and p_date='$d' and mom=1"));
$amount_paid = $results["amount_paid"]; 

 
$j++;
echo "<tr style='font-size:11px'>";
 
echo"
<td> $j </font></td>
<td>".strtoupper($names)."</td>
<td>$phone</td>
<td>".number_format($amount)."</td>
<td>".number_format($amount_paid)."</td>
<td>$phone</td>";
echo "</tr>";

 
}

echo "<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>";

echo "<tr><td></td><td></td><td><b></td><td><b>TOTAL MOM:</td><td><b>".number_format($total_amount_mom)."</b></td><td></td></tr>";
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
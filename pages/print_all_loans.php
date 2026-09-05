<?php 
session_start();
ob_start();

ini_set('memory_limit', ' 1969994M'); // or you could use 1G
ini_set('max_execution_time', 3600); //300 seconds = 5 minutes
include("conn.php");?>
 
<?php
if(isset($_POST['all_loans'])){       
 
$user_id = $_POST['user_id']; 
$boss_id = $_POST['boss_id'];  
 

//get branch
$results = mysqli_fetch_assoc(mysqli_query($conn,"select user_id, boss_id, ucase(branch) as branch from new_users 
where user_id='$user_id' and boss_id='$boss_id'")); 
$branch = $results["branch"];

//company
$result = mysqli_fetch_assoc(mysqli_query($conn,"select boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
from bosses where boss_id='$boss_id'"));     
$company= $result['firstname'];

echo "<p align=center><b>$company LTD<br>ALL LOANS GIVEN OUT</u>
 $branch BRANCH</b><br><hr>";  
$j=0;
$search_query= mysqli_query($conn,"SELECT client_id, ucase(firstname) as firstname, ucase(lastname) as lastname, amount_given, pay_date, completed
FROM clients, completed_loan  where users_id='$user_id' and bosses_id='$boss_id' and client_id=clientcpid  order by pay_date");


 
echo "<table width=100% border=1 align=center>
<thead>
<tr>
<th>No</th>
<th>Date</th>
<th>Client's Name</th>
<th>Amount Given</th>
<th>Interest</th>
<th>Total Amount</th> 
<th>Status</th>
</tr>
</thead>
<tbody>";
$total_given=0;
$total_interest=0;
$total_paid=0;
while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;

$client_id=$returned_result["client_id"];
$firstname = $returned_result["firstname"]. " ". $returned_result["lastname"];
$amount  = $returned_result["amount_given"];
$completed  = $returned_result["completed"];
$date = $returned_result["pay_date"];
$d=date("d-m-Y", strtotime($date));

$interest = 20/100*$amount;
$paid=$amount+$interest;
$total=number_format($amount+$interest);
$amount_given=number_format($amount);
$interests=number_format($interest);

$total_given+=$amount;
$total_interest+=$interest;
$total_paid+=$paid;
if($completed==1){
$status="Completed";
}
else{
$status="Running";
}
 
echo "<tr style='font-size:13px'>
<td> $j  </td>
<td> $d  </td>
<td> $firstname  </td>
<td> $amount_given</td>
<td> $interests</td>
<td> $total</td>
<td> $status</td>";
}

echo "</tr>
 <tr><td></td><td></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td></td><td></td><td></td><td></td></tr>
 <tr><td></td><td></td><td><b>TOTAL</td><td><b>".number_format($total_given)."</b></td><td><b>".number_format($total_interest)."</b></td><td><b>".number_format($total_paid)."</td><td></td></tr>";
echo "</tbody></table>";
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
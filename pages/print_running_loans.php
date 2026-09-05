<?php 
session_start();
ob_start();

ini_set('memory_limit', ' 1969994M'); // or you could use 1G
ini_set('max_execution_time', 3600); //300 seconds = 5 minutes
include("conn.php");

if(isset($_POST['running'])){     
$user_id = $_POST['user_id']; 
$boss_id = $_POST['boss_id'];  
$branch = $_POST['branch'];
 
  
 //company
$result = mysqli_fetch_assoc(mysqli_query($conn,"select boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
from bosses where boss_id='$boss_id'"));     
$company= $result['firstname'];

echo "<p align=center><b>$company LTD <br>ALL RUNNING LOANS   $branch  BRANCH</b></p><br>";

  
 $mon="";
 
 

$total_amounts=0;
$total_amount=0;
 
$j=0;
echo "<table width=100% border=1 align=left>
<thead>
<tr>
<th>No</th>
<th>Date</th>
<th>Client's Name</th>
<th>Amount Given</th>
<th>Interest</th>
<th>Total Amount</th> 
<th>Balance</th> 
</tr>
</thead>
<tbody>";
$total_given=0;
$total_interest=0;
$total_paid=0;
$total=0;
$balances=0;
$search_query= mysqli_query($conn,"SELECT  *
FROM clients, clients_with_loan where bosses_id='$boss_id' and users_id='$user_id' and client_id=clientsid order by pay_date Desc");  
while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;
$client_id=$returned_result["client_id"];
$name = strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);
$phone=$returned_result["phone"];
$user_id  = $returned_result["users_id"];
$loanNo = $returned_result["loan_no"];
$date_given= $returned_result["pay_date"];
$amount_given = $returned_result["amount_given"];
$daily_p = $returned_result["daily_p"];
$debt=$returned_result["debt"];
$date_given_loan =date("d-m-Y", strtotime($date_given));

$interest = 20/100*$amount_given;
$paid=$amount_given+$interest;
$total=$amount_given+$interest;
$amount_given=$amount_given;
$interests=$interest;

$total_given+=$amount_given;
$total_interest+=$interest;
$total_paid+=$paid;
$balances+=$debt;
  
echo "<tr style='font-size:12px'>
<td> $j  </td>
<td> $date_given </td>
<td> $name  </td>
<td> $amount_given</td>
<td> $interests</td>
<td> $total</td>
<td> $debt</td>";
}

echo "</tr>
 <tr><td></td><td></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td></td><td></td><td></td><td></td></tr>
 <tr><td></td><td></td><td><b>TOTAL</td><td><b>".number_format($total_given)."</b></td><td><b>".number_format($total_interest)."</b></td><td><b>".number_format($total_paid)."</td><td><b>".number_format($balances)."</td></tr>";
echo "</tbody></table>";
}
mysqli_close($conn);
 
echo"
</div>
</div>
 "; 
echo"
<div>"; 


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
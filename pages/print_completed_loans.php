<?php 
session_start();
ob_start();

ini_set('memory_limit', ' 1969994M'); // or you could use 1G
ini_set('max_execution_time', 3600); //300 seconds = 5 minutes
include("conn.php");?>
 
<?php
if(isset($_POST['completed_loans'])){       
 
$user_id = $_POST['user_id']; 
$boss_id = $_POST['boss_id'];  
$date1 = $_POST['date1'];
$date2 = $_POST['date2'];
$total=0;
//get branch
$results = mysqli_fetch_assoc(mysqli_query($conn,"select user_id, boss_id, ucase(branch) as branch from new_users 
where user_id='$user_id' and boss_id='$boss_id'")); 
$branch = $results["branch"];

//company
$result = mysqli_fetch_assoc(mysqli_query($conn,"select boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
from bosses where boss_id='$boss_id'"));     
$company= $result['firstname'];

echo "<p align=center><b>$company LTD<br>COMPLETED LOANS BETWEEN <u>".date('d-m-Y', strtotime($date1))."</u>
 AND <u>".date('d-m-Y', strtotime($date2))."</u> ".$branch." BRANCH</b><br><hr>";  
$j=0;
$search_query= mysqli_query($conn,"SELECT client_id, ucase(firstname) as firstname, ucase(lastname) as lastname, phone, e_date, amount_given, pay_date
FROM clients, completed_loan  where users_id='$user_id' and bosses_id='$boss_id' and client_id=clientcpid and completed=1 and e_date>='$date1' and e_date<='$date2' order by e_date");
 echo" 
<table width=90% border=1 align=center>
<thead>
<tr> 
<th>No</th>
<th>Names</th>
<th>Phone</th>
<th>Amount Given</th>
<th>Date Taken</th> 
<th>Date Ended</th> 
</tr>
</thead>
<tbody>";

while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;

$client_id=$returned_result["client_id"];
$firstname = $returned_result["firstname"]. " ". $returned_result["lastname"];
$phone  = $returned_result["phone"];
$amount  =$returned_result["amount_given"];
$date = $returned_result["e_date"];
$date2 = $returned_result["pay_date"];
$d=date("d-m-Y", strtotime($date));
$d2=date("d-m-Y", strtotime($date2));

 $total+=$amount;
 
echo "<tr style=font-size:12px>
<td>&nbsp;&nbsp;&nbsp; $j  </td>
<td>&nbsp;&nbsp;&nbsp; $firstname  </td>
<td>&nbsp;&nbsp;&nbsp; $phone</td>
<td>&nbsp;&nbsp;&nbsp; ".number_format($amount)."</td>
<td>&nbsp;&nbsp;&nbsp; $d2</td>
<td>&nbsp;&nbsp;&nbsp; $d</td>";
echo "</tr>";
}

echo "<tr style=font-size:12px>
<td> </td>
<td> </td>
<td><b>TOTAL</b></td>
<td>&nbsp;&nbsp;&nbsp;<b>".number_format($total)."</b></td>
<td> </td>
<td> </td>";
echo "</tr>";
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
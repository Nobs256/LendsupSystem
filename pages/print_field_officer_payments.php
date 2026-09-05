<?php 
session_start();
ob_start();

ini_set('memory_limit', ' 1969994M'); // or you could use 1G
ini_set('max_execution_time', 3600); //300 seconds = 5 minutes
include("conn.php");?>
 
<?php
if(isset($_POST['field_officer'])){       
 
$user_id = $_POST['user_id']; 
$boss_id = $_POST['boss_id'];  
$pre_date = $_POST['p_date'];
$officer_id=$_POST['officer_id'];
 
 
 //get branch
$results = mysqli_fetch_assoc(mysqli_query($conn,"select user_id, boss_id, ucase(branch) as branch from new_users 
where user_id='$user_id' and boss_id='$boss_id'")); 
$branch = $results["branch"];

//company
$result = mysqli_fetch_assoc(mysqli_query($conn,"select boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
from bosses where boss_id='$boss_id'"));     
$company= $result['firstname'];

echo "<p align=center><b>$company LTD<br>FIELD PAYMENT  ON <u>".date("d-m-Y", strtotime($pre_date))."</u> ".$branch." BRANCH</b><br><hr>
";  

echo "
<table width=100% border=1 style='font-size:13px' align=center>";
echo"<tr><td></td><td></td><td><b>Phone</td><td><b>Amount Paid </td><td><b>MOM</td>
<td width=20%> </td></tr><tr>";

$j=0;
$closing=0;
$closing=$total_op;
$search_query= mysqli_query($conn,"SELECT * FROM field_payment where user_id='$user_id' and boss_id='$boss_id'
 and pay_date='$pre_date' and officerid='$officer_id'");
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id=$returned_result["client_id"];
$amount=$returned_result["amount"];
$mom=$returned_result["mom"];

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from clients where    client_id='$client_id' "));
$names = strtoupper($results["firstname"]." ".$results["lastname"]); 
$phone = $results["phone"]; 
 
$j++;
echo "<tr style='font-size:12px'>";
 if($mom==0){
 $mom="";
 }
 else{
 	$mom=number_format($amount);
 }
echo"
<td> $j </font></td>
<td>".$names."</td>
<td> $phone </font></td>
<td>".number_format($amount)."</td>
<td>$mom</td>
<td></td>";
echo "</tr>";

}
//total Paid
$total_amount=0;
$select = mysqli_query($conn,"SELECT * FROM field_payment where  pay_date='$pre_date' 
 and user_id='$user_id' and boss_id='$boss_id' and officerid='$officer_id' and mom=0 ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$total_amount+=$amount;
}

//Total deposite using MOM
$total_amount_mom=0;
 
$select = mysqli_query($conn,"SELECT * FROM field_payment where  pay_date='$pre_date' 
 and user_id='$user_id' and boss_id='$boss_id' and officerid='$officer_id' and mom=1");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$total_amount_mom+=$amount;
}
$total_coll=0;
$total_coll=$total_amount+$total_amount_mom;
  
 
echo "<tr><td></td><td> </td><td></td><td>Cash: <b>".$total_amount."</b></td><td>MOM: <b>".$total_amount_mom." </b></td><td>Total: <b>".number_format($total_coll)."</></td></tr>";
echo "</tbody></table><br>  
 
</div>";  
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
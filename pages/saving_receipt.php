<?php 
session_start();
ob_start();

ini_set('memory_limit', ' 1969994M'); // or you could use 1G
ini_set('max_execution_time', 3600); //300 seconds = 5 minutes
include("conn.php");
if(isset($_POST['receipt'])){ 
$client_id = $_POST['client_id']; 
$user_id = $_POST['user_id']; 
$boss_id = $_POST['boss_id'];  
$payed_date = $_POST['pay_date'];              
$curr_date= date("Y-m-d");

$results = mysqli_fetch_assoc(mysqli_query($conn,"select user_id, boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, phone, nid,
ucase (branch) as branch from new_users where user_id='$user_id'"));
$user_name = $results['firstname']." ".$results['lastname'];
$branch = $results['branch'];
$cushphone = $results['phone'];
$cushphone2 = $results['nid'];

  
$result = mysqli_fetch_assoc(mysqli_query($conn,"select boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
 from bosses where boss_id='$boss_id'"));     
$company= $result['firstname']." ". $result['lastname'];
$phone = $result['phone'];

$result = mysqli_fetch_assoc(mysqli_query($conn,"select ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
  from clients where client_id='$client_id'"));     
$c_name= $result['firstname']." ". $result['lastname'];
$c_phone = $result['phone'];

$result = mysqli_fetch_assoc(mysqli_query($conn,"select* from savings
where clientsave_id='$client_id' and user_id='$user_id' and  boss_id='$boss_id' and save_date='$payed_date'"));     
$receipt = $result['save_id'];
$amount_paid =number_format($result['amount']);
$p_date = $result['save_date'];
$date_paid=date("d-m-Y", strtotime($p_date));

$result = mysqli_fetch_assoc(mysqli_query($conn,"select* from total_savings
where clientts_id='$client_id' and user_id='$user_id' and  boss_id='$boss_id'"));  
$total =number_format($result['total']);


echo " <p align=left><font size='4'> <u><b>$company<br> $branch BRANCH</b></u></font>";
echo"<br>";
echo "<p align='left'><font size='3'>  *** Saving Receipt *** <br>==================</font>   
 
<table align='left' border='0' width='50%' style='font-size:12px'>
<tr><td>
<p align='left'>
Batch Number: <u>BTN01$receipt</u><br>
Customer:<u>$c_name<br>
           ($c_phone)</u><br><br><br>

     *****Payment Details**** <br>
=======================<br>
Amount Save: <u><b>UGX $amount_paid/=</b></u><br>
Payment Date: <u><b>$date_paid</b></u><br>
Total Saved: <u><b>UGX $total</b></u><br>
<br> 
=======================<br>
Cashier: <u>$user_name</u><br><br><br>

<u>Cashier Signiture:</u><br>

=======================<br>
<u>Customer Signiture:</u><br>
=======================<br>

<u>Mobile Money: <br>$cushphone2/$cushphone <br><br>
</td>
</tr>
</table> "; 
 
}
echo"";
echo "</div>";
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
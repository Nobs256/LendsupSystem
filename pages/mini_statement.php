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
$date1 = $_POST['date1']; 
$date2= $_POST['date2'];                   
$curr_date= date("d-m-Y");

$results = mysqli_fetch_assoc(mysqli_query($conn,"select user_id, boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, phone,
ucase (branch) as branch from new_users where user_id='$user_id'"));
$user_name = $results['firstname']." ".$results['lastname'];
$branch = $results['branch'];
$cushphone = $results['phone'];

 
$result = mysqli_fetch_assoc(mysqli_query($conn,"select boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
  from bosses where boss_id='$boss_id'"));     
$company= $result['firstname']." ". $result['lastname'];
$phone = $result['phone'];

$result = mysqli_fetch_assoc(mysqli_query($conn,"select ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
  from clients where client_id='$client_id'"));     
$c_name= $result['firstname']." ". $result['lastname'];
$c_phone = $result['phone'];

$result = mysqli_fetch_assoc(mysqli_query($conn,"select* from loan_pay 
where clients_id='$client_id' and userse_id='$user_id' and  bossese_id='$boss_id' and p_date='$date1' "));     
$receipt = $result['loan_id'];
$loanNo = $result['loanNo'];

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from completed_loan where clientcpid='$client_id'
and loans_no=$loanNo and userscpid='$user_id' and bosscpid='$boss_id' "));   
  
$amount = $results['amount_given'];
$date = $results['pay_date'];
$completed = $results['completed'];
$date_taken=date("d-m-Y", strtotime($date));
$interest = 20/100*$amount;
$total=number_format($amount+$interest);
$datee=date("Y-m-d", strtotime($date));

if($completed==1){
$status="Completed";
}
else{
$status="Running";
}
if($datee!=$date){
echo "<p align=center><font size=5 color=red><br><br><br><br><br>Error!!!!
You select one of the dates a client paid loan</font></p>";
}
else{

echo "<p align=left><font size=3> <b>$company<br> $branch BRANCH - $cushphone</b></font>";
echo"<br><br>";

echo "<p align='left'><font size='3'>  *** Loan Repayment Statement*** <br>=========================== </font> 
  
 
<table align='left' border='0' width='50%' style='font-size:13px'>
<tr><td>
<p align='left'>
Batch Number: <u>BTN $receipt</u><br>
Loan ID:NL $loanNo<br>
Customer:<u>$c_name <br>$c_phone</u><br>
Loan + Interest: <u>UGX $total/=</u><br>
Date Taken:<u>$date_taken</u><br>
Loan Status: <u>$status</u><br>
Statement Date: <u>$curr_date</u><br>
<br><br>

     *****Statement Details**** <br>
===========================<br>
<font size=3>Instalment&nbsp;&nbsp;&nbsp;&nbsp;Date&nbsp;&nbsp;&nbsp;&nbsp;AmtPaid&nbsp;&nbsp;&nbsp;&nbsp;Balance<br>
---------------------------------------------------------------------------<br>";

$j=1;
while($j>0){ 
	
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id'  and p_date='$date1'")); 

$d=date("d-m-Y", strtotime($date1));
$amount = number_format($result["amount_paid"]);
$balance = number_format($result["balance"]);

if($amount>0){
echo " 
  $j &nbsp;&nbsp;&nbsp;&nbsp; 
  $d&nbsp;&nbsp;&nbsp;&nbsp;
  $amount&nbsp;&nbsp;&nbsp;&nbsp;
  $balance <br>
  ---------------------------------------------------------------------------<br>";
 echo "";
}
if($date1==$date2){
break;
}
$j++;
$date1 = date("Y-m-d", strtotime("$date1 +1 day"));
} 
echo "
<br><br>
=================================<br>
Cashier: <u>$user_name</u><br>

<u>Cashier Signiture:</u><br><br><br>

===============================<br>
<u>Customer Signiture:</u><br>
==============================<br>

<u>OFFICE TEL. NUMBER:$cushphone </u><br><br>
 
 <br><br>

------------------------------------------------------------------------------------
</td>
</tr>
</table> "; 
 
}
}
include("mpdf60/mpdf.php");
$mpdf=new mPDF('P','A4'); 
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
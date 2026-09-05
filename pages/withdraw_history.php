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

$result = mysqli_fetch_assoc(mysqli_query($conn,"select* from total_savings
where clientts_id='$client_id' and user_id='$user_id' and  boss_id='$boss_id'"));  
$total =number_format($result['total']);

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from withdraw 
where clientwith_id='$client_id' and user_id='$user_id' and  boss_id='$boss_id' 
and with_date='$date1'"));     
$datee = $result['with_date'];
$with_id = $result['with_id'];

if($datee!=$date1){
echo "<p align=center><font size=5 color=red><br><br><br><br><br>Error!!!!
You select one of the dates a client did not Withdraw</font></p>";
}
else{

echo "<p align=left><font size=2> <b>$company<br> $branch BRANCH - $cushphone</b></font>";
echo"<br><br>";

echo "<p align='left'><font size='3'>  *** WITHDRAW HISTORY *** <br>=========================== </font> 
  
 
<table align='left' border='0' width='50%' style='font-size:13px'>
<tr><td>
<p align='left'>
Batch Number: <u>BTN $with_id</u><br>
Name:<font size=2><b>$c_name </b></font><br>
Phone:<font size=2><b> $c_phone</b></font><br>
Current Date: <b>$curr_date</b><br>
Balance Remained: <b>$total</b><br>
<br><br>

     *****Statement Details**** <br>
===========================<br>
<font size=3>Instalment&nbsp;&nbsp;&nbsp;&nbsp;Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
&nbsp;&nbsp;&nbsp;&nbsp; AmtWithdraw <br>
---------------------------------------------------------------------------<br>";
$k=1;
$j=1;
while($j>0){ 
	
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM withdraw where 
clientwith_id='$client_id' and user_id='$user_id' and boss_id='$boss_id'  and with_date='$date1'")); 

$d=date("d-m-Y", strtotime($date1));
$amount = number_format($result["amount"]);
 
if($amount>0){
echo " 
  $k &nbsp;&nbsp;&nbsp;&nbsp; 
  &nbsp;&nbsp;&nbsp;&nbsp; 
  $d&nbsp;&nbsp;&nbsp;&nbsp;
  &nbsp;&nbsp;&nbsp;&nbsp; 
  $amount&nbsp;&nbsp;&nbsp;&nbsp;
  <br>
  ---------------------------------------------------------------------------<br>";
 echo "";
  
}
if($date1==$date2){
break;
}
$k++;
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
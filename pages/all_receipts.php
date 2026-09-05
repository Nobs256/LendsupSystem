<?php session_start();
ob_start();

ini_set('memory_limit', ' 1969994M'); // or you could use 1G
ini_set('max_execution_time', 3600); //300 seconds = 5 minutes
include("conn.php"); 

if(isset($_POST['allreceipt'])){ 
$user_id = $_POST['user_id']; 
$boss_id = $_POST['boss_id'];    
$date = $_POST['date1'];
$curr_date= date("Y-m-d");   
$amount_paid=0;   
 
$q = mysqli_query($conn,"SELECT DISTINCT(clientrec_id), loan_norec_id , rec_date FROM receipts where 
userrec_id='$user_id' and bossrec_id='$boss_id' and rec_date='$date' and printed=0"); 
$total_clients=mysqli_num_rows($q);
for($i=1;$i<=$total_clients;$i++)
{
$data=mysqli_fetch_object($q);
$client_id=$data->clientrec_id;
$loan_no=$data->loan_norec_id;
$rec_date=$data->rec_date;

$results = mysqli_fetch_assoc(mysqli_query($conn,"select user_id, boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, phone, 
ucase (branch) as branch from new_users where user_id='$user_id'"));
$user_name = $results['firstname']." ".$results['lastname'];
$branch = $results['branch'];
$cushphone = $results['phone'];

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from completed_loan where clientcpid='$client_id' and userscpid='$user_id' and bosscpid='$boss_id' and loans_no='$loan_no'"));     
$amount = $results['amount_given'];
$date = $results['pay_date'];
$date_taken=date("d-m-Y", strtotime($date));
$end_date = date('Y-m-d', strtotime("$date +30 day"));

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients_with_loan where bosseseid='$boss_id' and clientsid='$client_id' and userseid='$user_id'"));     
$amount_given = $results['amount_given'];
$interest=$results["interest"];
$total_interest=$amount_given*$interest/100;
$total_amount=$amount_given+$total_interest;

  
$result = mysqli_fetch_assoc(mysqli_query($conn,"select boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, phone, nid
 from bosses where boss_id='$boss_id'"));     
$company= $result['firstname']." ". $result['lastname'];
$phone = $result['phone'];
$phone_boss = $result['nid'];

$result = mysqli_fetch_assoc(mysqli_query($conn,"select ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
  from clients where client_id='$client_id'"));     
$c_name= $result['firstname']." ". $result['lastname'];
$c_phone = $result['phone'];
//manager
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' 
and branch='$branch'"));     
$manager_phone = $result['phone'];

$select = mysqli_query($conn,"SELECT * FROM loan_pay where  p_date='$rec_date' and loanNo='$loan_no'
and userse_id='$user_id' and bossese_id='$boss_id' and clients_id='$client_id'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_paid"]; 
$amount_paid+=$amount;
}

$result = mysqli_fetch_assoc(mysqli_query($conn,"select* from loan_pay 
where clients_id='$client_id' and userse_id='$user_id' and  bossese_id='$boss_id' and p_date='$rec_date' and loanNo='$loan_no' order by loan_id DESC limit 1"));     
$receipt = $result['loan_id'];
$balance = number_format($result['balance']);
$p_date = $result['p_date'];
$date_paid=date("d-m-Y", strtotime($p_date));

$amount_paid =number_format($amount_paid);

$query ="update receipts set printed=1 where clientrec_id='$client_id' and rec_date='$rec_date' and userrec_id='$user_id' and bossrec_id='$boss_id'";
$execute = mysqli_query($conn, $query); 


echo "<p align=left><font size=2> <b>$company<br> $branch BRANCH - $cushphone </b></font>";
echo"<br>";
$search_query= mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' 
and branch='$branch'");
while($returned_result = mysqli_fetch_assoc($search_query)){
$phone=$returned_result["phone"];
echo "<font size=2>$phone </font>";
}

echo "<p align='left'><font size='2'>  *** Loan Payment Receipt *** <br>
==================</font>   

<table align='left' border='0' width='50%' style='font-size:11px'>
<tr><td>
<p align='left'>
Batch Number: <u>BTN01$receipt</u><br>
Loan ID:QANL452$loan_no<br>
Customer:<u>$c_name <br>$c_phone</u><br>
Loan Given: <u>UGX".number_format($amount_given)."/=</u><br>
Interest: <u>$interest</u><br>
Total Amount: <u>UGX".number_format($total_amount)."/=</u><br>
Date Taken:<u>$date_taken</u><br>
Status: <u>Running</u><br>
Payment Date: <u><b>$date_paid</b></u><br>
<br> 
     *****Payment Details**** <br>
=======================<br>
Loan Paid: <u><b> UGX $amount_paid/=</b></u><br>
Balance: <u><b>UGX $balance/=</b></u><br>
 
<br> 
Cashier: <u>$user_name  <br> </u>
<u>$cushphone</u><br>
Cashier Signiture<br>
<br><br>
=======================<br>
 
Customer Signiture: <br>

=============================
<br><br>
 
<br><br>

-------------------------------------------------------------------
</td>
</tr>
</table> "; 
echo"<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
$amount_paid=0;
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
$mpdf->Output('RECEIPTS.pdf','I');
exit;
?>
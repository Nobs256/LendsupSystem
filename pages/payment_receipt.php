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
$loan_no = $_POST['loan_no'];      
$payed_date = $_POST['pay_date'];    
$receipt_id = $_POST['receipt_id'];           
$curr_date= date("Y-m-d");
$amount_paid=0;

if($receipt_id==""){ 
echo "<font size=5 color=red>Error!! Go back to th System and Click On <b>Print Receipts</b><br> Select the Name of the Client and Click Print Receipts</font>";
  }
else{
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

 
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from receipts 
where clientrec_id='$client_id' and userrec_id='$user_id' and  bossrec_id='$boss_id' 
and rec_date='$payed_date' and loan_norec_id='$loan_no' and receipt_id='$receipt_id' order by loan_norec_id DESC limit 1"));     
$receipt = $result['receipt_id'];
$balance = number_format($result['balancerec']);
$p_date = $result['rec_date'];
$paid_amount = $result['paid_amount'];
$date_paid=date("d-m-Y", strtotime($p_date));
$amount_paid =number_format($paid_amount);

$query ="update receipts set printed=1 where  userrec_id='$user_id' and bossrec_id='$boss_id' and rec_date='$p_date' and clientrec_id='$client_id' and loan_norec_id='$loan_no'";
$execute = mysqli_query($conn, $query);

//Start of arreas
 
$search_query= mysqli_query($conn,"SELECT  * FROM clients_with_loan where bosseseid='$boss_id' and clientsid='$client_id' and userseid='$user_id'");  
while($returned_result = mysqli_fetch_assoc($search_query)){
$loanNo = $returned_result["loan_no"];
$date_given= $returned_result["pay_date"];
$amount_given = $returned_result["amount_given"];
$daily_p = $returned_result["daily_p"];
$debt=$returned_result["debt"];
$date_given_loan =date("d-m-Y", strtotime($date_given));
$interest=$returned_result["interest"];
$total_interest=$amount_given*$interest/100;
$total_amount=$amount_given+$total_interest; 
$curr_date=date('Y-m-d');
$prev_date = date("Y-m-d", strtotime("$curr_date -1 day")); 
//starting date                                              
$first_date = date("Y-m-d", strtotime("$date_given +1 day"));
$end_date = date("Y-m-d", strtotime("$date_given +30 day"));

$pre_yr=date("Y",strtotime($date_given));
$curr_yr=date("Y",strtotime($curr_date));
//First date
$x=0;
$not_paid_at_all=0;
 
//No of days
$risk_date=date("Y-m-d", strtotime("$end_date +1 day"));
$defaulter_date=date("Y-m-d", strtotime("$end_date +60 day"));

if($risk_date<$prev_date){  
$x=-1;
$missed_balance=$debt;
 }
if($defaulter_date<$prev_date){  
$x=-1;
$missed_balance=$debt;
 }

$total_amount_paid=0;
$amount_supposed_paid=0;
$y=0;

//amount paid
$select = mysqli_query($conn,"SELECT * FROM loan_pay where  
clients_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loanNo' ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_paid"]; 
$total_amount_paid+=$amount;
}


//days
$date1=date_create("$prev_date");
$date2=date_create("$date_given");
$diff=date_diff($date1,$date2);
$y=$diff->format("%a");

if($risk_date<=$prev_date){  
$x=30;
$missed_balance=$debt;
 }
if($defaulter_date<$prev_date){  
$x=91;
$missed_balance=$debt;
 }

if($risk_date>$prev_date && $defaulter_date>$prev_date ){
$amount_supposed_paid=$y*$daily_p;
$missed_balance=$amount_supposed_paid-$total_amount_paid;
$x=$missed_balance/$daily_p;
}

if($missed_balance<0){  
$missed_balance=0;
 }
 
if($date_given==$prev_date){
 $x=0;
 $missed_balance=0;  
}
  
if($curr_date==$date_given){ 
 $x=0;                     
 $missed_balance=0; 
}

if($x<0){
 $x=0;
 }

if($risk_date<$curr_date and $x<0){  
$x=30;
$missed_balance=$debt;
 }

 if($missed_balance<$daily_p && $x==0){  
$missed_balance=0;
 }
}
//end of arreaas
 

echo " <p align=left><font size='2'> <b>$company<br> $branch BRANCH - $cushphone</b></font>";
echo"<br>";
$search_query= mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' 
and branch='$branch'");
while($returned_result = mysqli_fetch_assoc($search_query)){
$phone=$returned_result["phone"];
echo "<font size=2>$phone </font>";
}
echo "<p align='left'><font size='2'>  *** Loan Payment Receipt $receipt_id *** <br>==================</font>   
 
<table align='left' border='0' width='50%' style='font-size:11px'>
<tr><td>
<p align='left'>
Batch Number: <u>BTN01$receipt</u><br>
Loan ID:QANL352$loan_no<br>
Customer:<u>$c_name<br>
           ($c_phone)</u><br>
Loan Given: <u>UGX".number_format($amount_given)."/=</u><br>
Interest: <b> $interest </b><br>
Total Amount: <u>UGX".number_format($total_amount)."/=</u><br>
Date Taken:<u>$date_taken</u><br>
Status: <u>Running</u><br>
Payment Date: <u><b>$date_paid</b></u><br>
<br>

     *****Payment Details**** <br>
=======================<br>
Loan Paid: <u><b> UGX $amount_paid/=</b></u><br>
Balance: <u><b>UGX $balance/=</b></u><br>
Arrears: <u><b>UGX $missed_balance/=</b></u><br>

<br> 
=======================<br>
Cashier: <u>$user_name<br>$cushphone</u><br>

<u>Cashier Signiture:</u><br>
<br><br>
=======================<br>
Customer Signiture: <br>

========================<br>


<br><br>
 
<br><br> 

------------------------------------------------------------------------
</td>
</tr>
</table> "; 
 
}
}
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
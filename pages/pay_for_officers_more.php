<?php
$sa="";
include('header_user.php');

 ?>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> <?php include ('summary.php') ?>
<hr> 
<div id="main_heading"> <b>
Paying for Field Clients Who have Already Paid:</b>   
 </div>  
<br>
</div> 
<div id="main_container" style="height:420px">
<div id="main_body" style="height:300px">   
<?php
//--------------------
if(isset($_GET['pay_more'])){
 
$boss_id=$_GET['boss_id'];
$user_id=$_GET['user_id'];
$client_id=$_GET['client_id'];
$b_date=$_GET['b_date'];
$loan_no=$_GET['loan_no'];
$officer_id=$_GET['officer_id'];
$amo=str_replace(",","",$_GET['amount']);
$mom=0;
$numb=0;
$paid_mom=0;
$printed=0;
$curr_date=date('Y-m-d');
$pre_date = date('Y-m-d', strtotime("$curr_date -1 day"));
$sent_date=date("Y-m-d", strtotime($b_date)); 
$reg_fee=0;
 


function countExist($conn,$table,$key)
{
$k=mysqli_query($conn,"SELECT * FROM $table WHERE $key");
$rows=mysqli_num_rows($k);
return $rows;
}

$transc_type="Cash_in";
$hup=mysqli_query($conn,"SELECT * FROM clients WHERE  client_id='$client_id' and users_id='$user_id' and bosses_id='$boss_id'");
$now=mysqli_fetch_array($hup);
$names=$now["firstname"]." ".$now["lastname"];
$number=$now["phone"];
//check whether the be4 date is the database
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id' order by p_date desc limit 1"));     
$entered_prev_date = $result['p_date'];
// Get the Balanace
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients_with_loan where clientsid='$client_id'"));     
$debt= $result['debt'];
$given_date=$result['pay_date'];
$balance=$debt-$amo;
$end_date=date('Y-m-d', strtotime("$given_date +30 day"));

$check=countExist($conn,"loan_pay","p_date='$b_date' AND clients_id='$client_id' AND loanNo='$loan_no' ");

if($amo>$debt){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 500px'>The amount entered is greater than balance
<a href='pay_loan.php?client_id=$client_id' style='color:white; margin-left:100px;''>X</a></div>";
}

else{ 

if($balance==0){
$query ="update completed_loan set e_date='$b_date', completed=1 where loans_no=$loan_no and clientcpid='$client_id' and userscpid='$user_id' and bosscpid='$boss_id'";
$execute = mysqli_query($conn, $query);
}
$total=0;
$search_query= mysqli_query($conn,"SELECT * FROM loan_pay  where  loanNo='$loan_no' and 
clients_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id' and p_date='$b_date'");
while($returned_result = mysqli_fetch_assoc($search_query)){
 
$amount_paid=$returned_result["amount_paid"];
$total=$total+$amount_paid;
}

$day_total=$total+$amo;

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from field_payment where
pay_date='$b_date' AND clientf_id='$client_id' AND  user_id='$user_id' and boss_id='$boss_id'"));     
$officerid= $result['officerid'];

if($officer_id==$officerid){
$query ="UPDATE field_payment set amount='$day_total'  where clientf_id='$client_id' and user_id='$user_id' and boss_id='$boss_id' and pay_date='$b_date' and officerid='$officer_id' ";
$execute = mysqli_query($conn, $query);
}
else{
mysqli_query($conn,"INSERT INTO field_payment(ts_id, user_id, boss_id, clientf_id, pay_date, officerid, amount, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id',  '$b_date', '$officer_id', '$amo', '$mom')");

}

$query ="UPDATE loan_pay set balance='$balance', amount_paid='$day_total'  where clients_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loan_no' and p_date='$b_date' ";
$execute = mysqli_query($conn, $query);

$query ="UPDATE loan_pay_daily set balance='$balance', amount_paid='$day_total'  where clients_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loan_no' and p_date='$b_date' ";
$execute = mysqli_query($conn, $query);

$query ="UPDATE receipts set balancerec='$balance', paid_amount='$day_total'  where clientrec_id='$client_id' and userrec_id='$user_id' and bossrec_id='$boss_id' and loan_norec_id='$loan_no' and rec_date='$b_date' ";
$execute = mysqli_query($conn, $query);

//===update the loans
$query ="UPDATE clients_with_loan set debt='$balance' where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'";
$execute = mysqli_query($conn, $query);

$query ="UPDATE loan_pay_cash set balance='$balance', amount_paid='$day_total'  where clients_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loan_no' and p_date='$b_date' ";
$execute = mysqli_query($conn, $query);

mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id',  '$b_date', '$names',  '$transc_type', '$amo', '$reg_fee', '$mom')");

/*msg
$amount_msg=0;
$date=date("d-m-Y", strtotime($b_date));
$msg="$date -  Hello  ".strtoupper($names). ", You have Paid UGX $amo to MUSHA-Mengo
 Balance is UGX $balance. Thank You.";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://bluesmsuganda.com/api-sub.php");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch,
CURLOPT_POSTFIELDS,"user=abelk&password=0777842873&sender=".urlencode("QuickAcounts")."&message=".urlencode($msg)."&reciever=$number");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$hamza = curl_exec ($ch);
curl_close ($ch); 

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from total_msg where user_id='$user_id' 
and boss_id='$boss_id'")); 
$total= $result['total'];
$new_total=$total-35;

$query ="UPDATE total_msg set total='$new_total' where user_id='$user_id' and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);
*/

echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:5px; width: 600px'>
<table border='0' width='600px'>
<tr>
<td width='220px'><font size='4'>Loan is Successfully Paid!</td><td>Balance is $balance &nbsp;&nbsp;&nbsp;</font> </td>
 
<td>
<a href='pay_for_officers.php?officer_id=$officer_id&pay_date=$b_date' style='color:white; margin-left:1px;''>Pay for Another Client</a>
</td></tr></table>
</div>";           
} 

}
?>  
</div>
</div>
 
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>


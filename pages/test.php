<?php
$s="";
include('header_user.php');
?>
<style type="text/css">
table {
border-collapse: collapse;
border-spacing: 0;
width: 100%;
border: 1px solid #D9FFD9 ;
}

th, td {
text-align: left;
padding: 4px;
padding-top: 4px;
}

tr:nth-child(even) {
background-color: #D9FFD9;
}
form {
border-collapse: collapse;
}

input, select {
width:340px; height:33px; font-size:12px; border: 2px solid green; border-radius: 4px;
}
</style>
<main class="column main" style="background-color:#EAEAEA;"> 
<?php include ('summary.php') ?> 
 
 
<div id="main_heading"> <b>Clients with Loans</b></div>     
 
<div id="main_container"> 
<div id="main_body"> 
 
<div class="table-responsive">

<?php
include('conn.php');

$search_query= mysqli_query($conn,"SELECT  * FROM field_payment where pay_date>='2026-04-02'");  
while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;
$client_id=$returned_result["clientf_id"]; 
$b_date=$returned_result["pay_date"];
$amo  = $returned_result["amount"];
$user_id  = $returned_result["user_id"];
$boss_id  = $returned_result["boss_id"];

 $transc_type="Cash_in";
  $reg_fee=0;
  $mom=0;
  $intrest_paid=0;
  

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id' order by p_date desc limit 1"));     
$pre_balance = $result['balance'];
  
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from clients_with_loan where clientsid='$client_id'"));     
$debt= $result['debt'];
$loan_no=$result['loan_no'];
  
$balance=$pre_balance-$amo;
  
  
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from loan_pay where clients_id='$client_id' and p_date>='2026-04-02'"));     
$amount= $result['amount_paid'];
  
if($amount==0){
  /*

   mysqli_query($conn,"INSERT INTO loan_pay(loan_id, loanNo, clients_id, userse_id, bossese_id, p_date, amount_paid, balance, mom, intrest_paid) 
VALUES (NULL, '$loan_no', '$client_id',  '$user_id', '$boss_id', '$b_date', '$amo', '$balance', '$mom', '$intrest_paid')");

mysqli_query($conn,"INSERT INTO loan_pay_daily(loan_id, loanNo, clients_id, userse_id, bossese_id, p_date, amount_paid, balance, mom, intrest_paid) 
VALUES (NULL, '$loan_no', '$client_id',  '$user_id', '$boss_id', '$b_date', '$amo', '$balance', '$mom', '$intrest_paid')");

 
   
   /*

mysqli_query($conn,"INSERT INTO transcations(ts_id, user_id, boss_id, clientr_id, transc_date, transc_name, transc_type, transc_amount, reg_fee, mom) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id',  '$b_date', '$names',  '$transc_type', '$amount_paid', '$reg_fee', '$mom')");

 /*
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from clients_with_loan where clientsid='$client_id'"));     
$debt= $result['debt'];

$balance=$debt+$amount_paid;

$loan_query ="UPDATE clients_with_loan set debt='$balance' where clientsid='$client_id'";
$execute = mysqli_query($conn, $loan_query);

$query ="DELETE from loan_pay where p_date='2026-03-19' and clients_id='$client_id'";
$execute = mysqli_query($conn, $query);

$query ="DELETE from loan_pay_daily where p_date='2026-03-19' and clients_id='$client_id'";
$execute = mysqli_query($conn, $query);

$query ="DELETE from field_payment where pay_date='2026-03-19' and officerid=5 and clientf_id='$client_id'";
$execute = mysqli_query($conn, $query);

$query ="DELETE from transcations where transc_date='2026-03-19' and transc_type='Cash_in' and clientr_id='$client_id'";
$execute = mysqli_query($conn, $query);
*/
  
  echo $client_id; 
  echo "--";
  echo $pre_balance;
  echo "--";
  echo $balance;
  echo "<br>";
 
}
}
?>  

</div>
</div>
</div>
</div> 
<?php include('footer.php');  
?>
</div>
</main>
</body> 
</html>
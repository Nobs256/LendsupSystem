<?php
include('conn.php');
//-------------------------------
 
$boss_id=$_GET['boss_id'];
$user_id=$_GET['user_id'];
$client_id=$_GET['client_id'];
$loan_no=$_GET['loan_no'];
$amo=str_replace(",","",$_GET['amo']);
$mom=0;
$paid_mom=0;
$printed=0;
$curr_date=date('Y-m-d');
$pre_date = date('Y-m-d', strtotime("$curr_date -1 day"));
$b_date=$pre_date;
$paid_unknown=0;
$mom=0;
$reg_fee=0;

 
//check whether the be4 date is the database
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id' order by p_date desc limit 1"));     
$entered_prev_date = $result['p_date'];
$balance = $result['balance'];

// Get the Balanace
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients_with_loan where clientsid='$client_id'"));     
$debt= $result['debt'];
$given_date=$result['pay_date'];
$amount_paid=$debt-$amo;
$end_date=date('Y-m-d', strtotime("$given_date +30 day"));

  
$query ="UPDATE loan_pay set balance='$amo'  where clients_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loan_no' and p_date='$b_date' ";
$execute = mysqli_query($conn, $query);

$query ="update clients_with_loan set debt='$amo' where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'";
$execute = mysqli_query($conn, $query);

 
echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:5px; width: 940px'>
 <font size='4'>Loan is Successfully Paid!</td><td>Amount Paid is $amount_paid &nbsp;&nbsp;&nbsp;</font> </td>
&nbsp; 
 
<a href='search_client_give_data_entry.php' style='color:white; margin-left:100px;''>Enter Another One</a>
 
</div>";           

?>
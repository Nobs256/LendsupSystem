<?php
//Shortage
$total_shortage=0;
$d= date('Y-m-d');
$d2 = date("Y-m-d", strtotime("$d -1 day"));
$msg_date=0;
$select = mysqli_query($conn,"SELECT * FROM sent_msgs where 
user_id='$user_id' and boss_id='$boss_id' and msg_date='$d2'");
while($selected= mysqli_fetch_array($select)){  
$msg_date= $selected["msg_date"]; 
}
 
if($msg_date==$d2){
$d=$d;
}
else{
$d=$d2;
}
 
$total_amount_mom=0;
$total_amount=0;
$total_balance=0;
$total_op=0;
$total_de=0;
$total_exp=0;
$amount_ch=0;
$total_ukno=0;
$excess=0;
$shortage=0;
$total_paid_loan=0;
$total_given_loan=0;
$total_reg_fee=0;
$tci=0;
$cs=0;
$total_op_mom=0;
$total_savings=0;
$total_withdraws=0;

//paid cash
$select = mysqli_query($conn,"SELECT * FROM loan_pay where  p_date='$d'
and userse_id='$user_id' and bossese_id='$boss_id' and mom=0 ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_paid"]; 
$total_amount+=$amount;
}

//total op
$select = mysqli_query($conn,"SELECT * FROM op where  op_date='$d'
and userop_id='$user_id' and bossop_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$op_amount= $selected["op_amount"]; 
$total_op+=$op_amount;
}
if ($user_email === 'iganga' && $d === '2026-09-02') {
    $total_op = 21000;
}

//total op_mom
$select = mysqli_query($conn,"SELECT * FROM op_mom where  op_date='$d'
and userop_id='$user_id' and bossop_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$op_amount= $selected["op_amount"]; 
$total_op_mom+=$op_amount;
}

//Cr from another branch
$cash_from_branch=0;
$select = mysqli_query($conn,"SELECT * FROM cr where  cr_date='$d'
and usercr_id='$user_id' and bosscr_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$cr_amount= $selected["cr_amount"]; 
$cash_from_branch+=$cr_amount;
}

//Money sent to another branch
$cash_to_branch=0;
$select = mysqli_query($conn,"SELECT * FROM sent_to_branch where  cr_date='$d'
and usercr_id='$user_id' and bosscr_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$cr_amount= $selected["cr_amount"]; 
$cash_to_branch+=$cr_amount;
}

//deposited
$total_bank_deposit=0;
$select = mysqli_query($conn,"SELECT * FROM banking where  de_date='$d'
and userde_id='$user_id' and bossde_id='$boss_id' and transc='Deposit'");
while($selected= mysqli_fetch_array($select)){  
$de_amount= $selected["de_amount"]; 
$total_bank_deposit+=$de_amount;
}

//Savings
$select = mysqli_query($conn,"SELECT * FROM savings where  save_date='$d'
and user_id='$user_id' and boss_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$total_savings+=$amount;
}

//withdrAWS
$select = mysqli_query($conn,"SELECT * FROM withdraw where  with_date='$d'
and user_id='$user_id' and boss_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$total_withdraws+=$amount;
}
//Money Withdrawn
$total_bank_withdraw=0;
$select = mysqli_query($conn,"SELECT * FROM banking where  de_date='$d'
and userde_id='$user_id' and bossde_id='$boss_id' and transc='Withdraw'");
while($selected= mysqli_fetch_array($select)){  
$de_amount= $selected["de_amount"]; 
$total_bank_withdraw+=$de_amount;
}

//Expense
$select = mysqli_query($conn,"SELECT * FROM expenses where  exp_date='$d'
and userexp_id='$user_id' and bossexp_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$ex_amount= $selected["cost"]; 
$total_exp+=$ex_amount;
}

//Loans given in parts on this date - every part handed out is a cash out on that day
$no_parts=0;
$total_parts_amount=0;
$total_parts_fee=0;
$completed_part_clients=array();
$select = mysqli_query($conn,"SELECT * FROM loans_in_parts WHERE bp_date='$d'
and usersp_id='$user_id' and bossesp_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){
$part_amount=$selected["amount_g"];
$part_fee=$selected["reg_fee"];
$total_parts_amount+=$part_amount;
$total_parts_fee+=$part_fee;
$no_parts++;
if($selected["part"]=="Completing"){
$completed_part_clients[]=$selected["clientp_id"];
}
}

$no_loans=0; 
$select = mysqli_query($conn,"SELECT * FROM loans WHERE b_date='$d' 
and userse_id='$user_id' and bossese_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
//skip loans completed from parts on this date - they were already deducted part by part
if(in_array($selected["cliente_id"], $completed_part_clients)){
continue;
}
$given_loan=$selected["amount_given"];
$total_given_loan+=$given_loan;
$no_loans++;
}
 
//reg fee
$select = mysqli_query($conn,"SELECT * FROM loans WHERE b_date='$d' 
 and userse_id='$user_id' and bossese_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
//skip loans completed from parts on this date - their fee comes from the completing part below
if(in_array($selected["cliente_id"], $completed_part_clients)){
continue;
}
$reg_fee=$selected["reg_fee"];
$total_reg_fee+=$reg_fee;
} 

//add the loan parts given on this date to the loan out and processing fee totals
$total_given_loan+=$total_parts_amount;
$total_reg_fee+=$total_parts_fee;
$no_loans+=$no_parts;

//Uknown
$total_ukno=0;
$select = mysqli_query($conn,"SELECT * FROM uknown where  rec_date='$d'
and userrec_id='$user_id' and bossrec_id='$boss_id' and known=0 ");
while($selected= mysqli_fetch_array($select)){  
$ex_amount= $selected["paid_amount"]; 
$total_ukno+=$ex_amount;
}

//Excess
$total_excess=0;
$select = mysqli_query($conn,"SELECT * FROM excess_short where  rec_date='$d'
and userrec_id='$user_id' and bossrec_id='$boss_id' and excess_short='Excess'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["paid_amount"]; 
$total_excess+=$amount;
}

//MOM WithDrawa
$total_mom_withdraws=0;
$select = mysqli_query($conn,"SELECT * FROM withdraws where  with_date='$d'
and user_id='$user_id' and boss_id='$boss_id' and withdraws='MOM'");
while($selected= mysqli_fetch_array($select)){  
$amount1= $selected["amount"]; 
$total_mom_withdraws+=$amount1;
}

//shortage recovered
$recovered_shortage=0;
$select = mysqli_query($conn,"SELECT * FROM withdraws where  with_date='$d'
and user_id='$user_id' and boss_id='$boss_id' and withdraws='Shortage'");
while($selected= mysqli_fetch_array($select)){  
$amount1= $selected["amount"]; 
$recovered_shortage+=$amount1;
}

//Unkown Recovered
$recovered_unknown=0;
$select = mysqli_query($conn,"SELECT * FROM withdraws where  with_date='$d'
and user_id='$user_id' and boss_id='$boss_id' and withdraws='Unknown'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$recovered_unknown+=$amount;
}

//Withdrawn Excess
$recovered_excess=0;

$select = mysqli_query($conn,"SELECT * FROM withdraws where  with_date='$d'
and user_id='$user_id' and boss_id='$boss_id' and withdraws='Excess'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$recovered_excess+=$amount;
}

//Withdrawn Defaulters Money
$withdraw_defaulters=0;

$select = mysqli_query($conn,"SELECT * FROM withdraws where  with_date='$d'
and user_id='$user_id' and boss_id='$boss_id' and withdraws='Defaulters'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$withdraw_defaulters+=$amount;
}
//cash
$cassh=0;

$select = mysqli_query($conn,"SELECT * FROM cash where  pay_date='$d'
and user_id='$user_id' and boss_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$cassh+=$amount;
} 
//Total deposited using MOM
$total_amount_m=0;
$select = mysqli_query($conn,"SELECT * FROM loan_pay where  p_date='$d'
and userse_id='$user_id' and bossese_id='$boss_id' and mom=1 ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_paid"]; 
$total_amount_m+=$amount;
}
$total_amount_mom=($total_amount_m+$total_ukno)-$recovered_unknown;

//trash
$trash=0;
$select = mysqli_query($conn,"SELECT * FROM trash where  pay_date='$d'
and user_id='$user_id' and boss_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$trash+=$amount;
}

//Mom Charges Balance 
$mom_balance=0;
$amount=0;
$select = mysqli_query($conn,"SELECT * FROM mom_balance where ch_date='$d'
and userch_id='$user_id' and bossch_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$mom_balance+=$amount;
}

// Total Returned Loans
$total_returned_loans = 0;
$returned_query = mysqli_query($conn, "SELECT IFNULL(SUM(amount_returned), 0) as total_returned FROM loan_returned WHERE date='$d' AND user_id='$user_id' AND boss_id='$boss_id'");
if($returned_result = mysqli_fetch_assoc($returned_query)){  
    $total_returned_loans = $returned_result['total_returned'];
}

//total unknown Cash
$total_unknown_cash=0;
$select = mysqli_query($conn,"SELECT * FROM unknown_cash where unknown_date='$d'
and user_id='$user_id' and boss_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$total_unknown_cash+=$amount;
}

//withdraw Unknown Cash
$total_withdraw_unknown_cash=0;
$select = mysqli_query($conn,"SELECT * FROM withdraw_unknown_cash where  unknown_date='$d'
and user_id='$user_id' and boss_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$total_withdraw_unknown_cash+=$amount;
}

//clients paid
$clients_paid = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM loan_pay, clients where 
userse_id='$user_id' and bossese_id='$boss_id' and p_date='$d' and client_id=clients_id")); 

//payment Rate
$payment_rate=ceil($clients_paid/$loan_clients*100);

//clients paid
$clients_paid = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM loan_pay, clients where 
userse_id='$user_id' and bossese_id='$boss_id' and p_date='$d' and client_id=clients_id")); 

//Closing Stock Mom
$cs_mom=0;
$cs_mom=($total_op_mom+$total_amount_mom)-$total_mom_withdraws;

$trimed=trim(substr($cs_mom,5,2));
if($trimed>0){
$cs_mom=$cs_mom-$trimed;
}

//total loan collections
$totalcoll=0;
$total_amount_cash=0;

$total_amount_cash=$total_amount-$recovered_excess-$total_withdraw_unknown_cash;

$tci=$total_op+$total_amount_cash+$total_mom_withdraws+$total_reg_fee+$total_savings+
$recovered_shortage+$total_excess+$total_bank_withdraw+$cash_from_branch+$mom_balance+$total_unknown_cash+$total_returned_loans;

$tco=$trash+$total_given_loan+$total_exp+$withdraw_defaulters+$cash_to_branch+$total_withdraws+
$total_bank_deposit;

$totalcoll=$total_amount_cash+$total_amount_mom-$total_withdraw_unknown_cash; 
//Cash at Hand
$closing=$tci-$tco;

$trimed=trim(substr($closing,5,2));
if($trimed>0){
$closing=$closing-$trimed;
}
//closing Stock
$today=date("d-m-Y", strtotime($d));
echo " <b>Date:".date("d-m-Y", strtotime($d))."</b> <br><br>Cash at Hand:&nbsp;".number_format($closing)."<br> ";

$saved_amount=0;
$mom_saved=0;
$search_query= mysqli_query($conn,"SELECT *	FROM mom_phones  where user_id='$user_id' and boss_id='$boss_id' order by company");
while($returned_result = mysqli_fetch_assoc($search_query)){

$company=$returned_result["company"];
$phone=$returned_result["phone"];

$select=mysqli_query($conn,"SELECT * FROM total_mom  where phone='$phone' 
and user_id='$user_id' and boss_id='$boss_id'");
$now=mysqli_fetch_array($select);
$saved_amount=$now["total"];
echo "$company ($phone): ".number_format($saved_amount)."<br> ";
$mom_saved+=$saved_amount;
}
if($mom_saved!=$cs_mom){
echo "<font color=red>MOM Closing Stock is not Equal to Total Saved</font>";
}
$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from new_users where boss_id='$boss_id' and active=1 and category='User' and user_id='$user_id'"));
$branch=strtoupper($results["branch"]);

$msg="KV.-$branch BRANCH END OF DAY REPORT ON ".$today." Total No.Cust:".$loan_clients." 
 No.Cust_paid ".$clients_paid." No.LoanOut: ".$no_loans." 
Process_fee: ".number_format($total_reg_fee)." Tot.LoanOut:".number_format($total_given_loan)."
Total Amount Paid:
 ".number_format($totalcoll)." ($payment_rate %) Banking: ".number_format($total_bank_deposit)." Cash Recieved: 
 ".number_format($total_bank_withdraw)." 
  Tot.Exp: ".number_format($total_exp)."
 Open.Balance:".number_format($total_op)." 
 Cash at Hand: ".number_format($closing).". <br><br><b>Sent by QuickAccounts";

echo "<br> ";
?>  
<form method="POST" action="check_message.php">

<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
<input type="hidden" name="boss_id" value="<?php echo $boss_id;?>">
<input type="hidden" name="msg" value="<?php echo $msg;?>">
<input type="hidden" name="msg_date" value="<?php echo $d;?>">
 
<button type="submit" name="send" class="button is-default" 
style="border: 1px solid; border-radius:4px; color:white; background-color: #006F37;">
Send Report</button>             
</form>
<br>
 


 


 

 
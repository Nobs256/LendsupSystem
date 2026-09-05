<?php
if(isset($_REQUEST['details']))
{   
$user_id = $_REQUEST['details'];
$date = $_REQUEST['date'];
$d=date("Y-m-d", strtotime($date));

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from new_users where boss_id='$boss_id' and active=1 and category='User' and user_id='$user_id'"));
$branch=strtoupper($results["branch"]);

$total_clients = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM clients where 
users_id='$user_id' and bosses_id='$boss_id'")); 
 
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

$select = mysqli_query($conn,"SELECT * FROM loan_pay where  p_date='$d'
and userse_id='$user_id' and bossese_id='$boss_id' and  mom=0");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_paid"]; 
$total_amount+=$amount;
}

$select = mysqli_query($conn,"SELECT * FROM loan_pay where  p_date='$d'
and userse_id='$user_id' and bossese_id='$boss_id' and mom=1");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_paid"]; 
$total_amount_mom+=$amount;
}

//total op
$select = mysqli_query($conn,"SELECT * FROM op where  op_date='$d'
and userop_id='$user_id' and bossop_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$op_amount= $selected["op_amount"]; 
$total_op+=$op_amount;
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

$no_loans=0; 
$select = mysqli_query($conn,"SELECT * FROM loans WHERE b_date='$d' 
and userse_id='$user_id' and bossese_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$given_loan=$selected["amount_given"];
$total_given_loan+=$given_loan;
$no_loans++;
}

 
//reg fee
$select = mysqli_query($conn,"SELECT * FROM loans WHERE b_date='$d' 
 and userse_id='$user_id' and bossese_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$reg_fee=$selected["reg_fee"];
$total_reg_fee+=$reg_fee;
} 

//Uknown
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

//Total deposite using MOM
$total_amount_m=0;
$select = mysqli_query($conn,"SELECT * FROM loan_pay where  p_date='$d'
and userse_id='$user_id' and bossese_id='$boss_id' and mom=1 ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_paid"]; 
$total_amount_m+=$amount;
}
$total_amount_mom=$total_amount_m+$total_ukno;

//trash
$trash=0;

$select = mysqli_query($conn,"SELECT * FROM trash where  pay_date='$d'
and user_id='$user_id' and boss_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount"]; 
$trash+=$amount;
}

 
$display_unknown="";
$display_reco_short="";
$display_reco_excess="";
$display_defaulters="";
$display_excess="";
$display_reco_unknown="";
$display_cash_from_branch="";
$display_cash_to_branch="";

if($cash_from_branch>0){
$display_cash_from_branch="<font color=red>Payables: ". number_format($cash_from_branch)."&nbsp;&nbsp;&nbsp;&nbsp;</font>";
}
if($cash_to_branch>0){
$display_cash_to_branch="<font color=red>Recievables: ". number_format($cash_to_branch)."&nbsp;&nbsp;&nbsp;&nbsp;</font>";
}

if($recovered_unknown>0){
$display_reco_unknown="<font color=red>Source Known: ". number_format($recovered_unknown)."&nbsp;&nbsp;&nbsp;&nbsp;</font>";
}

if($total_ukno>0){
$display_unknown="<font color=red>Uknown Source: ". number_format($total_ukno)."&nbsp;&nbsp;&nbsp;&nbsp;</font>";
}

if($recovered_shortage>0){
$display_reco_short="<font color=red>Shortage Recovered: ". number_format($recovered_shortage)."&nbsp;&nbsp;&nbsp;&nbsp;</font>";
}

if($recovered_excess>0){
$display_reco_excess="<font color=red>Excess Withdrawn: ". number_format($recovered_excess)."&nbsp;&nbsp;&nbsp;&nbsp;</font>";
}

if($withdraw_defaulters>0){
$display_defaulters="<font color=red>Refundable: ". number_format($withdraw_defaulters)."&nbsp;&nbsp;&nbsp;&nbsp;</font>";
}
//excess
if($total_excess>0){
$display_excess="<font color=red>Excess: ". number_format($total_excess)."&nbsp;&nbsp;&nbsp;&nbsp;</font>";
}

$total_amount_with_out_mom=$total_amount-$total_amount_mom-$recovered_unknown-$recovered_excess;
//clients paid
$clients_paid = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM loan_pay, clients where 
userse_id='$user_id' and bossese_id='$boss_id' and p_date='$d' and client_id=clients_id")); 

$search_query= mysqli_query($conn,"SELECT client_id, users_id, bosses_id, 
ucase(firstname) as firstname,ucase(lastname) as lastname,  phone, business, b_location, p_date, amount_paid, balance
FROM clients, loan_pay  where p_date='$d' and users_id='$user_id' and bosses_id='$boss_id' and client_id=clients_id order by firstname");
 

$total_amount_cash=0;
$total_amount_cash=$total_amount-$recovered_unknown-$recovered_excess;


$cs_mom=0;
$cs_mom=($total_op_mom+$total_amount_mom)-$total_mom_withdraws;

$tci=$total_op+$total_amount_cash+$total_mom_withdraws+$total_reg_fee+
$recovered_shortage+$total_excess+$cash_from_branch+$total_bank_withdraw;

$tco=$trash+$total_given_loan+$total_exp+$total_bank_deposit+$withdraw_defaulters+$cash_to_branch;

$cs=$tci-$tco;

echo"<div style='background-color:#D9FFD9; padding-left:10px; border-radius:6px;
border: 1px solid #006F37'>
<p align=center><b>A Report From $branch Branch on ".date("d-m-Y", strtotime($d))."</p> 
<table border=1 align=center width=80% style='background-color:White'><tr>
<td>&nbsp;&nbsp;Clients Paid: </td><td>&nbsp;&nbsp; $clients_paid </td></tr><tr>
<td>&nbsp;&nbsp;O.P Cash: </td><td>&nbsp;&nbsp; ".number_format($total_op)."</td></tr><tr>
<td>&nbsp;&nbsp;O.P MOM:</td><td>&nbsp;&nbsp; ".number_format($total_op_mom)."</td></tr><tr>
<td>&nbsp;&nbsp;No of Loans Given:</td><td>&nbsp;&nbsp; ". number_format($no_loans)."</td></tr><tr>
<td>&nbsp;&nbsp;Loan Given:</td><td>&nbsp;&nbsp; ". number_format($total_given_loan)."</td></tr><tr>
<td>&nbsp;&nbsp;Process.Fee:</td><td>&nbsp;&nbsp; ".number_format($total_reg_fee)."</td></tr><tr>
<td>&nbsp;&nbsp;Total Expenses:</td><td>&nbsp;&nbsp; ".number_format($total_exp)."</td></tr><tr>
<td>&nbsp;&nbsp;Total Loan Paid Cash:</td><td>&nbsp;&nbsp; ".number_format($total_amount)."</td></tr><tr>
<td>&nbsp;&nbsp;Total Loan Paid with MOM:</td><td>&nbsp;&nbsp; ".number_format($total_amount_mom)."</td></tr><tr> 
<td>&nbsp;&nbsp;Total Loan Collections:</td><td>&nbsp;&nbsp; ".number_format($total_amount_cash+$total_amount_mom)." </td></tr><tr>
<td>&nbsp;&nbsp;Total MOM Withdraws:</td><td>&nbsp;&nbsp; ".number_format($total_mom_withdraws)."</td></tr><tr>
<td>&nbsp;&nbsp;Total Bank Deposits:</td><td>&nbsp;&nbsp; ".number_format($total_bank_deposit)."</td></tr><tr>
<td>&nbsp;&nbsp;Total Bank Withdraws:</td><td>&nbsp;&nbsp;".number_format($total_bank_withdraw)."</td></tr><tr>
<td>&nbsp;&nbsp;MOM Closing Stock:</td><td>&nbsp;&nbsp;".number_format($cs_mom)."</td></tr><tr>
<td>&nbsp;&nbsp;Cash at Hand:</td><td>&nbsp;&nbsp;".number_format($cs)."</td></tr><tr>


</table><br>";
echo "</div>";
}
 
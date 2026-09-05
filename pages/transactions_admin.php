<?php
include('header.php');
?>
 <style>
 tr:nth-child(even) {
background-color: #D9FFD9;
}

th, td {
text-align: left;
padding-left: 10px;
 
}
</style>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> <?php include ('summary_admin.php') ?>
<div id="main_heading"> 
<table border="0">
<tr><td>
<b>TRANSACTIONS</b>
</td>
<td>
<font color="#E4E4E4">---------------------------------------</font>
</td><td>
<form  method="post"> 
Branch:
<div class="select is-success">  
<select  name="branch"  style="width:180px; border: 1px solid #006F37; height:35px" required> 
<option>Branch</option>
<?php
$comb = mysqli_query($conn,"SELECT * from new_users where boss_id='$boss_id' and active=1 and category='User'");

while($select_comb = mysqli_fetch_array($comb)){
$branch=$select_comb['branch'];
 
echo "<option value= $branch> $branch </option>";
 
}
echo" 
</select></div>";
?>
<font color="#E4E4E4">-----</font>
Select Date: 
<input type="date" name="p_date" style="width:190px; height:35px; border: 1px solid #006F37" required> 
<button type="submit" name="paid" class="button is-primary" 
style="background-color:#006F37; color:white; font-size:12px; 
border:1px solid #006F37; height:35px; margin-left:4px ">
&nbsp;OK&nbsp;</button></form> 
 
</td></tr></table></div>
     
 
<div id="main_container" style="height:420px">
<div id="main_body" style="height:390px">   
    
<br>  
<?php
$j=0;
$total_amount_paid=0;
$total_amount=0;

if(isset($_POST['paid'])){       
$date = $_POST['p_date'];
$branch = $_POST['branch'];
$d=date("Y-m-d", strtotime($date));

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from new_users where  boss_id='$boss_id' and category='User' and branch='$branch'"));
$user_id = $results["user_id"];
 

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
and userse_id='$user_id' and bossese_id='$boss_id' and mom=0");
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

$total_amount_cash=0;
$total_amount_cash=$total_amount-$recovered_unknown-$recovered_excess;
//clients paid
$clients_paid = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM loan_pay, clients where 
userse_id='$user_id' and bossese_id='$boss_id' and p_date='$d' and client_id=clients_id")); 

$search_query= mysqli_query($conn,"SELECT client_id, users_id, bosses_id, 
ucase(firstname) as firstname,ucase(lastname) as lastname,  phone, business, b_location, p_date, amount_paid, balance
FROM clients, loan_pay  where p_date='$d' and users_id='$user_id' and bosses_id='$boss_id' and client_id=clients_id order by firstname");
?>  
<div>
<?php 
echo"<p align=center><b><u>TRANSACTIONS MADE AT ".strtoupper($branch)." BRANCH ON  ".date("d-m-Y", strtotime($d))."</u></b>";
echo"<table width=100% border=0><tr><td>";
echo "<p align=center><font size=3>";  
echo "O.P: ".number_format($total_op)."&nbsp;&nbsp;&nbsp;
O.P MOM: ".number_format($total_op_mom)."&nbsp;&nbsp;&nbsp;
No of Loans Given: ". number_format($no_loans)."&nbsp;&nbsp;&nbsp;
Total Loan Given: ". number_format($total_given_loan)."&nbsp;&nbsp;&nbsp;
Total Processing Fee: ".number_format($total_reg_fee)."&nbsp;&nbsp;&nbsp;
</td>
<td width=5%>
<form method='post' action='print_transc_report.php' target='_blank'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='date' value='$date'>
<button type='submit' name=daily_report style='border: 1px solid #7C7C7C; margin-left:20px; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:60px'>
&nbsp;Print&nbsp;</button></form>
</td></tr></table>
 <p align=center>
Clients Paid: $clients_paid &nbsp;&nbsp;&nbsp; 
Total Loan Paid Cash: ".number_format($total_amount)."&nbsp;&nbsp;&nbsp;&nbsp;
Total Loan Paid with MOM: ".number_format($total_amount_mom)."&nbsp;&nbsp;&nbsp; 
Total Loan Collections: ".number_format($total_amount_cash+$total_amount_mom)."&nbsp;&nbsp;&nbsp;&nbsp;


<br>

Total Expenses: ".number_format($total_exp)."&nbsp;&nbsp;&nbsp
Total Bank Deposits: ".number_format($total_bank_deposit)."&nbsp;&nbsp;&nbsp
Total MOM Withdraws: ".number_format($total_mom_withdraws)."&nbsp;&nbsp;&nbsp;
Total Bank Withdraws: ".number_format($total_bank_withdraw)."&nbsp;&nbsp;&nbsp;"

.$display_cash_to_branch." &nbsp;&nbsp;&nbsp;&nbsp;"
.$display_cash_from_branch." &nbsp;&nbsp;&nbsp;&nbsp;"
.$display_unknown."&nbsp;&nbsp;&nbsp;&nbsp;"
.$display_excess."&nbsp;&nbsp;&nbsp;&nbsp;"
.$display_reco_unknown."&nbsp;&nbsp;&nbsp;&nbsp;"
.$display_reco_short." &nbsp;&nbsp;&nbsp;&nbsp;"
.$display_reco_excess." &nbsp;&nbsp;&nbsp;&nbsp;"
.$display_defaulters." &nbsp;&nbsp;&nbsp;&nbsp;
</font>";
 
echo "<table width=80% border=1 style='font-size:14px' align=center>
<thead>
<tr>
<th>No</th>
<th>Transactions</th>
<th>Cash_in</th>
<th>Cash_out</th>
<th>Break Down</th> 
</tr>
</thead>
<tbody>";
$j=1;
echo "<tr style='font-size:13px'> <td>$j</td><td><b>Opening Balance<b></td><td>".number_format($total_op)."</td><td>
</td><td><b>".number_format($total_op)."</b></td></tr>";


$closing=0;
$closing=$total_op;
$search_query= mysqli_query($conn,"SELECT * FROM transcations where user_id='$user_id' and boss_id='$boss_id' and transc_date='$d'");
while($returned_result = mysqli_fetch_assoc($search_query)){
$ts_id=$returned_result["ts_id"];
$transc_name = $returned_result["transc_name"];
$transc_type  = $returned_result["transc_type"];
$transc_amount  = $returned_result["transc_amount"];
$reg_fee  = $returned_result["reg_fee"];
$mom = $returned_result["mom"];
$j++;
echo "<tr style='font-size:13px'>";
//cash in
if($transc_type=='Cash_in' && $mom==0){
$closing+=$transc_amount;
echo"
<td> $j </font></td>
<td>".strtoupper($transc_name)."</td>
<td>".number_format($transc_amount)."</td>
<td> </td> 
<td>".number_format($closing)."</td>
";
echo "</tr>";
}
if($transc_type=='Cash_out'){
$closing-=$transc_amount;
$closing+=$reg_fee;
echo"
<td> $j </td>
<td>". strtoupper($transc_name)."</td>
<td> <b> ".number_format($reg_fee)."</b></td>
<td>".number_format($transc_amount)."</td>
<td>".number_format($closing)."</td>
";
echo "</tr>";
}

//cash in
if($transc_type=='Cash_in' && $mom==1){
$closing+=$transc_amount-$transc_amount;
echo"
<td> $j </td>
<td>".strtoupper($transc_name)."</td>
<td>".number_format($transc_amount)."</td>
<td>".number_format($transc_amount)."(MOM)</td>
<td>".number_format($closing)."</td>
";
echo "</tr>";
}
}

 
$tci=$total_op+$total_amount_cash+$total_mom_withdraws+$total_reg_fee+
$recovered_shortage+$total_excess+$cash_from_branch+$total_bank_withdraw;

$tco=$trash+$total_given_loan+$total_exp+$total_bank_deposit+$withdraw_defaulters+$cash_to_branch;

$cs=$tci-$tco;
 
echo "<tr><td></td><td> </td><td></td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td></td> </tr>";

echo "<tr><td></td><td></td><td><b>Cash_in: ".number_format($tci)."</td><td><b>
Cash_out: ".number_format($tco)."</td><td><b>Closing Stock:  ".number_format($closing)."</b></td></tr>";
echo "</tbody></table><br> ";
}
else{
echo "<font size=5>Select Branch and Date</font>";
}
mysqli_close($conn);
 
 
echo"
</div>

</div> 
</div>";
include('footer.php');
?>
 </div>
</main>
</body> 
</html>
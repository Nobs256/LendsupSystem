<?php 
session_start();
ob_start();

ini_set('memory_limit', ' 1969994M'); // or you could use 1G
ini_set('max_execution_time', 3600); //300 seconds = 5 minutes
include("conn.php"); 
$mon="";
$total_amounts=0;
if(isset($_POST['trial'])){
$month = $_POST['month'];
$user_id= $_POST['user_id'];
$boss_id= $_POST['boss_id'];
$year = $_POST['year'];
$closing = $_POST['closing'];

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from new_users where boss_id='$boss_id' and active=1 and category='User' and user_id='$user_id' "));
$branch=$results['branch']; 

}
 

$dateObj   = DateTime::createFromFormat('!m', $month);
$mon = $dateObj->format('F'); 

$total_amount=0;
$total_interest=0;
$total_interests=0;
$interest=0;
$amount=0;

$total_fee=0;
$total_fees=0;

$total_charge=0;
$total_charges=0;

$total_income=0;
$total_incomes=0;

$ex_amount=0;
$total_exp=0;
$total_exps=0;
$total_expenses=0;
$total=0;
$all_loan_given=0;

//all loans given out
$select = mysqli_query($conn,"SELECT * FROM loans where  MONTH(b_date)='$month' and userse_id='$user_id' and bossese_id='$boss_id' and YEAR(b_date)='$year'");
while($selected= mysqli_fetch_array($select)){  
$amount1= $selected["amount_given"]; 
$all_loan_given+=$amount1;
}

//Total debt in this month
$select = mysqli_query($conn,"SELECT * FROM clients_with_loan where userseid='$user_id'
and bosseseid='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$debt= $selected["debt"]; 
$debt=number_format($debt);
}

//deposited
$total_bank_balance=0;
$select = mysqli_query($conn,"SELECT * FROM total_banking where  user_id='$user_id' and boss_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$total= $selected["total"]; 
$total_bank_balance+=$total;
}

//Cr from another branch
$cash_from_branch=0;
$select = mysqli_query($conn,"SELECT * FROM cr where  MONTH(cr_date)='$month' and YEAR(cr_date)='$year'
and usercr_id='$user_id' and bosscr_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$cr_amount= $selected["cr_amount"]; 
$cash_from_branch+=$cr_amount;
}

//Money sent to another branch
$cash_to_branch=0;
$select = mysqli_query($conn,"SELECT * FROM sent_to_branch where MONTH(cr_date)='$month' and YEAR(cr_date)='$year'
and usercr_id='$user_id' and bosscr_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$cr_amount= $selected["cr_amount"]; 
$cash_to_branch+=$cr_amount;
}

//Total Interest in this month
$select = mysqli_query($conn,"SELECT * FROM loans where  MONTH(b_date)='$month' and YEAR(b_date)='$year' and userse_id='$user_id' and bossese_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$amount2= $selected["amount_given"]; 
$interest = 20/100*$amount2;
$total_interest+=$interest;
$total_interests=number_format($total_interest);
}

//Total Reg fee in this month
$select = mysqli_query($conn,"SELECT * FROM loans where  MONTH(b_date)='$month' and YEAR(b_date)='$year' and userse_id='$user_id' and bossese_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$amount3= $selected["reg_fee"]; 
$total_fee+=$amount3;
$total_fees=number_format($total_fee);
}

//Completed Loans
$completed_loan=0;
$select = mysqli_query($conn,"SELECT * FROM completed_loan where  MONTH(e_date)='$month' and YEAR(pay_date)='$year' and userscpid='$user_id' and bosscpid='$boss_id' and completed=1");
while($selected= mysqli_fetch_array($select)){  
$amount4= $selected["amount_given"]; 
$completed_loan+=$amount4;
}

//Running Loans
$running_loan=0;
$select = mysqli_query($conn,"SELECT * FROM completed_loan where  MONTH(pay_date)='$month' and YEAR(pay_date)='$year' and userscpid='$user_id' and bosscpid='$boss_id' and completed=0");
while($selected= mysqli_fetch_array($select)){  
$amount5= $selected["amount_given"]; 
$running_loan+=$amount5;
}
//Total Expenses
 
$select = mysqli_query($conn,"SELECT * FROM expenses where MONTH(exp_date)='$month' and YEAR(exp_date)='$year' and userexp_id='$user_id' and bossexp_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$ex_amount= $selected["cost"]; 
$total_exp+=$ex_amount;
$total_exps=number_format($total_exp);
}

//deposited
$total_bank_deposit=0;
$select = mysqli_query($conn,"SELECT * FROM banking where MONTH(de_date)='$month' and YEAR(de_date)='$year'
and userde_id='$user_id' and bossde_id='$boss_id' and transc='Deposit'");
while($selected= mysqli_fetch_array($select)){  
$de_amount= $selected["de_amount"]; 
$total_bank_deposit+=$de_amount;
}

//Money Withdrawn
$total_bank_withdraw=0;
$select = mysqli_query($conn,"SELECT * FROM banking where MONTH(de_date)='$month' and YEAR(de_date)='$year'
and userde_id='$user_id' and bossde_id='$boss_id' and transc='Withdraw'");
while($selected= mysqli_fetch_array($select)){  
$de_amount= $selected["de_amount"]; 
$total_bank_withdraw+=$de_amount;
}

//Mom Deposits
$total_amount_mom=0;
$select = mysqli_query($conn,"SELECT * FROM loan_pay where MONTH(p_date)='$month' and YEAR(p_date)='$year'
and userse_id='$user_id' and bossese_id='$boss_id' and mom=1");
while($selected= mysqli_fetch_array($select)){  
$amount6= $selected["amount_paid"]; 
$total_amount_mom+=$amount6;
}

//MOM WithDrawn
$total_mom_withdraws=0;
$select = mysqli_query($conn,"SELECT * FROM withdraws where  MONTH(with_date)='$month' and YEAR(with_date)='$year'
and user_id='$user_id' and boss_id='$boss_id' and withdraws='MOM'");
while($selected= mysqli_fetch_array($select)){  
$amount7= $selected["amount"]; 
$total_mom_withdraws+=$amount7;
}

//Total fines in this month
$total_fine=0;
$select = mysqli_query($conn, "SELECT * FROM loan_fines where  MONTH(pay_date)='$month' and YEAR(pay_date)='$year' and user_id='$user_id' and boss_id='$boss_id' order by pay_date");
while($selected= mysqli_fetch_array($select)){ 
$date= $selected["pay_date"];
$fine= $selected["amount"];
$total_fine+=$fine;
}

$retained_earnings=0;
$retained_earnings=($total_interest+$total_fee+$total_fine)-$total_exp;

$total_debits=0;
$total_credits=0;
$suspense_account=0;
$suspense_account=$total_credits-$total_debits;

$total_debits= $total_bank_deposit+$total_exp+$all_loan_given+$closing+$cash_to_branch;
$total_credits= $total_bank_withdraw+$total_interest+$total_fee+$total_fine+$cash_from_branch+$completed_loan+$retained_earnings;

if($total_debits>$total_credits){
$suspense_account=$total_debits-$total_credits;
$total_credits+=$suspense_account;
$suspense_account_dr=0;
$suspense_account_cr=$suspense_account;
}

if($total_debits<$total_credits){
$suspense_account=$total_credits-$total_debits;
$total_debits+=$suspense_account;
$suspense_account_dr=$suspense_account;
$suspense_account_cr=0;
}

//company
$result = mysqli_fetch_assoc(mysqli_query($conn,"select boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
from bosses where boss_id='$boss_id'"));     
$company= $result['firstname'];

echo "<p align=center><b>$company LTD<br>TRIAL BALANCE FOR ".strtoupper($branch)."  BRANCH ".strtoupper($mon)." ".$year."</b><br><hr>
"; 

echo "<table width=90% border=1>
<thead>
<tr>
<th width=40%>Account</th>
<th width=20%>Account Type</th>
<th width=15%>Debits </th>
<th width=15%>Credits</th>
</tr>
</thead>
<tbody>
<tr>
<td>Cash at Hand</td>
<td>Assets</td>
<td width=10%>".number_format($closing)."</td>
<td></td> 
</tr><tr>

<td>All Loan Given Out</td>
<td>Assets</td>
<td width=10%>".number_format($all_loan_given)."</td>
<td></td> 
</tr><tr>

<td>Bank Deposits</td>
<td>Assets</td>
<td width=10%>".number_format($total_bank_deposit)."</td>
<td></td> 
</tr><tr>

<td>Recievables(Money to Another Branch)</td>
<td>Assets</td>
<td width=10%>".number_format($cash_to_branch)."</td>
<td></td> 
</tr><tr>
";
$select = mysqli_query($conn,"SELECT * FROM expenses_list where bossexp_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$item= $selected["item"]; 
$exp_id= $selected["exp_id"]; 

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(cost) as total_exps FROM expenses where 
MONTH(exp_date)='$month' and userexp_id='$user_id' and bossexp_id='$boss_id' and item='$item'"));
$total_exp=$results['total_exps'];

echo"
<td>$item</font></td> 
<td>Expenses</td>
<td>".number_format($total_exp)."</td>
<td></td></tr><tr>";
}
echo"<td> Completed Loans</td> 
<td>Revenues</td>
<td></td>
<td>".number_format($completed_loan)."</td>
</tr><tr>";

echo"<td> Gross Interest Income </td> 
<td>Revenues</td>
<td></td>
<td>".number_format($total_interest)."</td>
</tr><tr>";

echo"<td> Loan Processing Fees </td> 
<td>Revenues</td>
<td></td>
<td>".number_format($total_fee)."</td>
</tr><tr>";

echo"<td>Renewed Interests</td>
<td>Revenue</td>
<td></td>
<td width=10%>".number_format($total_fine)."</td>
</tr><tr>";

echo"<td> Bank withdraws </td> 
<td>Liability</td>
<td></td>
<td>".number_format($total_bank_withdraw)."</td>
</tr><tr>";

echo"<td> Payables </td> 
<td>Liability</td>
<td></td>
<td>".number_format($cash_from_branch)."</td>
</tr><tr>";

echo"<td> Retained Earnings </td> 
<td>Equity/Capital</td>
<td></td>
<td>".number_format($retained_earnings)."</td>
</tr><tr>";

echo"<td> Suspense Account </td> 
<td>Equity/Capital</td>
<td>".number_format($suspense_account_dr)."</td>
<td>".number_format($suspense_account_cr)."</td>
</tr><tr>";

echo "
<tr>
<td></td>
<td><b>Total Value</b></td><td><b>".$total_debits."</td> 
<td><b>$total_credits</td> 
</tr>
</table>";
 
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
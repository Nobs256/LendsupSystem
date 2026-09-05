<?php 
session_start();
ob_start();

ini_set('memory_limit', ' 1969994M'); // or you could use 1G
ini_set('max_execution_time', 3600); //300 seconds = 5 minutes
include("conn.php"); 
$mon="";
$total_amounts=0;
if(isset($_POST['profit'])){
$month = $_POST['month'];
$user_id= $_POST['user_id'];
$boss_id= $_POST['boss_id'];
$year = $_POST['year'];

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from new_users where boss_id='$boss_id' and active=1 and category='User' and user_id='$user_id' "));
$branch=$results['branch']; 

}

include ('cash_at_hand_profit.php');

$dateObj   = DateTime::createFromFormat('!m', $month);
$mon = $dateObj->format('F'); 

$total_amount=0;
$total_interest=0;
$total_interests=0;
$interest=0;
$amount=0;

$total_fee=0;
$total_fees=0;
$total_fine=0;

$total_charge=0;
$total_charges=0;

$total_income=0;
$total_incomes=0;

$ex_amount=0;
$total_exp=0;
$total_exps=0;
$total_expenses=0;

//Total Interest in this month
$select = mysqli_query($conn,"SELECT * FROM loans where  MONTH(b_date)='$month' and YEAR(b_date)='$year' and userse_id='$user_id' and bossese_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_given"]; 
$interest = 20/100*$amount;
$total_interest+=$interest;
$total_interests=number_format($total_interest);
}

//Total Debts
$total_debt=0;
$select = mysqli_query($conn,"SELECT * FROM clients_with_loan where userseid='$user_id' and
 bosseseid='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$debt= $selected["debt"]; 
$total_debt+=$debt;
}

//Total Reg fee in this month
$select = mysqli_query($conn,"SELECT * FROM loans where  MONTH(b_date)='$month' and YEAR(b_date)='$year' and userse_id='$user_id' and bossese_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["reg_fee"]; 
$total_fee+=$amount;
$total_fees=number_format($total_fee);
}

//Total fines in this month
$select = mysqli_query($conn, "SELECT * FROM loan_fines where  MONTH(pay_date)='$month' and YEAR(pay_date)='$year' and user_id='$user_id' and boss_id='$boss_id' order by pay_date");
while($selected= mysqli_fetch_array($select)){ 
$fine= $selected["amount"];
$total_fine+=$fine;
}

//MOM Balance withdrawn 
$total_fuel_withdrawn=0;
$search_query= mysqli_query($conn,"SELECT * FROM withdraws where withdraws='fuel' and user_id='$user_id' and boss_id='$boss_id' and MONTH(with_date)='$month' and YEAR(with_date)='$year'");
while($returned_result = mysqli_fetch_assoc($search_query)){ 
$amount = $returned_result["amount"];
$total_fuel_withdrawn+=$amount;
} 

//MOM Balance added
$mom_balance_deposited=0;
$search_query= mysqli_query($conn,"SELECT * FROM mom_balance where userch_id='$user_id' and bossch_id='$boss_id' and MONTH(ch_date)='$month' and YEAR(ch_date)='$year'");
while($returned_result = mysqli_fetch_assoc($search_query)){ 
$amount = $returned_result["amount"];
$mom_balance_deposited+=$amount;
} 

$recovery_deposited=0;
$search_query= mysqli_query($conn,"SELECT * FROM loan_recovery where user_id='$user_id' and boss_id='$boss_id' and MONTH(recovery_date)='$month' and YEAR(recovery_date)='$year'");
while($returned_result = mysqli_fetch_assoc($search_query)){ 
$amount = $returned_result["amount"];
$recovery_deposited+=$amount;
}

$total_fuel_account=$mom_balance_deposited+$recovery_deposited;

//Total Income
$total_income=$total_interest+$total_fee+$total_fine;
 

//Total Expenses
 
$select = mysqli_query($conn,"SELECT * FROM expenses where MONTH(exp_date)='$month' and YEAR(exp_date)='$year' and userexp_id='$user_id' and bossexp_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$ex_amount= $selected["cost"]; 
$total_exp+=$ex_amount;
}
$total_exp=$total_exp-$total_fuel_withdrawn;
//Profit
$total_profit=0;
$total_profit=$total_income-$total_exp;


//company
$result = mysqli_fetch_assoc(mysqli_query($conn,"select boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
from bosses where boss_id='$boss_id'"));     
$company= $result['firstname'];

echo "<p align=center><b>$company LTD<br>STATEMENT OF INCOME AND RETAINED EARNINGS<br> ".strtoupper($branch)."  BRANCH ".strtoupper($mon)." ".$year."</b><br><hr>
"; 

echo "<table width=80% border=1>
<thead>
<tr>
<th style='text-align:left'>REVENUE (INCOME)</th>
<th></th>
</tr>
</thead>
<tbody>
<tr><td width=20%>Gross Interest Income</td> <td width=20%>$total_interests</td></tr>
<tr><td width=20%>Loan Processing Fees (Reg fee)</td><td width=20%>$total_fees</td></tr>
<tr><td width=20%>Renewed Interest</td><td width=20%>".number_format($total_fine)."</td></tr>
 
<tr><td><b>TOTAL INCOME</b></td><td>".number_format($total_income)."</td></tr>
<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>

<tr><td><b>EXPENSES</b></td><td></td></tr>";
$total=0;
$total_fuel=0;

$select = mysqli_query($conn,"SELECT * FROM expenses_list");
while($selected= mysqli_fetch_array($select)){  
$item= $selected["item"]; 
$exp_id= $selected["exp_id"]; 
echo "<tr>";

if($item=='Fuel'){

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(cost) as total_exps FROM expenses where 
MONTH(exp_date)='$month' and YEAR(exp_date)='$year' and  userexp_id='$user_id' and bossexp_id='$boss_id' and item='Fuel'"));
$total_fuel=$results['total_exps'];
$fuel_expenses=$total_fuel-$total_fuel_withdrawn;
echo"
<td>$item</td> 
<td>".number_format($fuel_expenses)."</td></tr>";
}
else{
$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(cost) as total_exps FROM expenses where 
MONTH(exp_date)='$month' and YEAR(exp_date)='$year' and  userexp_id='$user_id' and bossexp_id='$boss_id' and item='$item'"));
$total=$results['total_exps'];
$total_expenses+=$total;
echo"
<td>$item</td> 
<td>".number_format($total)."</td></tr>";
}
}
 
echo "
 
<tr>
<td><b>Money in Circulation</td><td>".number_format($total_debt)."</td>
</tr>
<tr>
<td><b>Total Income</td><td>".number_format($total_income)."</td>
</tr>

<tr>
<td><b>Total Expenses</b></td><td>".number_format($total_exp)."</td>
</tr>

<tr>
<td><b>Cash at Hands</td><td>".number_format($closing)."</td>
</tr>
<tr>
<td><b>Total Profit</td><td>".number_format($total_profit)."</td>
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
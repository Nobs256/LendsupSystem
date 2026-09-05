<?php
include('header_user.php');
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
<p align="center"> 
<?php include ('summary.php') ;?>
<div id="main_heading"> 
<table><tr><td><b>STATEMENT OF INCOME AND RETAINED EARNINGS</b> </td><td><font color="#EAEAEA">---------------------------------</font></td>
<td>
<form method="post"> 
<div class="select is-success">                    
<select  name="months"  style="width:180px; border: 1px solid #006F37; height:35px" required> 
<option value="">Month</option>
<option value="01">January</option> 
<option value="02">February</option>
<option value="03">March</option>
<option value="04">April</option>
<option value="05">May</option>
<option value="06">June</option>
<option value="07">July</option>
<option value="08">August</option>
<option value="09">September</option>
<option value="10">October</option>
<option value="11">November</option> 
<option value="12">December</option>
</select>
</div>
<div class="select is-success">  
<select  name="year"  style="width:120px; border: 1px solid #006F37; height:35px" required> 
<option value="">Year:</option>
<?php 
 for ($yr=2021; $yr <=2040 ; $yr++) { 
  echo"<option value='$yr'> $yr </option>";
 }  
echo "
</select></div>";
?>
<button type="submit" name="monthly_report" class="button is-primary" 
style="border: 1px solid #006F37; border-radius:4px; color:white; background-color:#006F37">
&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;</button>
</form>     
</td></tr></table>
</div>     
 
<div id="main_container">
<div id="main_body">   
    
<br>  
<?php
$mon = "";
$total_amounts = 0;

if(isset($_POST['monthly_report'])){
    // Sanitize inputs
    $month = str_pad((int)$_POST['months'], 2, '0', STR_PAD_LEFT);
    $year = (int)$_POST['year'];

    // Optimize Date Searching for Database Indexes
    $startDate = "$year-$month-01";
    $endDate = date('Y-m-d', strtotime("$startDate +1 month"));

    $q_user = mysqli_query($conn,"SELECT branch FROM new_users WHERE boss_id='$boss_id' AND active=1 AND category='User' AND user_id='$user_id' LIMIT 1");
    $results = mysqli_fetch_assoc($q_user);
    $branch = $results['branch'] ?? '';
  
    include ('cash_at_hand_profit.php');

    $dateObj = DateTime::createFromFormat('!m', $month);
    $mon = $dateObj->format('F'); 

    // 1. Total Interest (Calculated via SQL SUM)
    $q_interest = mysqli_query($conn, "SELECT SUM(amount_paid) AS total_amount FROM loan_pay WHERE p_date >= '$startDate' AND p_date < '$endDate' AND userse_id='$user_id' AND bossese_id='$boss_id'");
    $res_interest = mysqli_fetch_assoc($q_interest);
    $total_interest = (0.20 * (float)$res_interest['total_amount']);
    $total_interests = number_format($total_interest);

    // 2. Total Reg fee
    $q_fee = mysqli_query($conn, "SELECT SUM(reg_fee) AS total_fee FROM loans WHERE b_date >= '$startDate' AND b_date < '$endDate' AND userse_id='$user_id' AND bossese_id='$boss_id'");
    $res_fee = mysqli_fetch_assoc($q_fee);
    $total_fee = (float)$res_fee['total_fee'];
    $total_fees = number_format($total_fee);

    // 3. Total Debts (No date filter originally, left as-is but optimized with SUM)
    $q_debt = mysqli_query($conn, "SELECT SUM(debt) AS total_debt FROM clients_with_loan WHERE userseid='$user_id' AND bosseseid='$boss_id'");
    $res_debt = mysqli_fetch_assoc($q_debt);
    $total_debt = (float)$res_debt['total_debt'];

    // 4. Total fines
    $q_fine = mysqli_query($conn, "SELECT SUM(amount) AS total_fine FROM loan_fines WHERE pay_date >= '$startDate' AND pay_date < '$endDate' AND user_id='$user_id' AND boss_id='$boss_id'");
    $res_fine = mysqli_fetch_assoc($q_fine);
    $total_fine = (float)$res_fine['total_fine'];

    // Total Income
    $total_income = $total_interest + $total_fee + $total_fine;

    // 5. Total Expenses
    $q_exp = mysqli_query($conn, "SELECT SUM(cost) AS total_exp FROM expenses WHERE exp_date >= '$startDate' AND exp_date < '$endDate' AND userexp_id='$user_id' AND bossexp_id='$boss_id'");
    $res_exp = mysqli_fetch_assoc($q_exp);
    $total_exp = (float)$res_exp['total_exp'];
    $total_exps = number_format($total_exp);

    // Profit
    $total_profit = number_format($total_income - $total_exp);

    echo"<table border=0 width=100%><tr>
    <td width=40%>
    <b>Total Profit in $mon-$year from $branch is $total_profit  </b></td>";

    echo "<td width=20%>
    <form method='post' action='print_profit.php' target='_blank'>
    <input type='hidden' name='user_id' value='$user_id'>
    <input type='hidden' name='boss_id' value='$boss_id'>
    <input type='hidden' name='branch' value='$branch'>
    <input type='hidden' name='month' value='$month'>
    <input type='hidden' name='year' value='$year'>
    
    <button type='submit' name='profit' style='border: 1px solid green; border-radius:3px; font-size:17px; 
    color: green; background-color:white; height:30px; width:100px'>
    &nbsp;Print &nbsp;</button></form></td>
    </tr></table>";

    echo "<br><br>";
 
    echo "<table width='70%' border='1'>
    <thead>
    <tr>
    <th>REVENUE (INCOME)</th>
    <th></th>
    <th></th>
    </tr>
    </thead>
    <tbody>
    <tr>
    <td width='30%'>Gross Interest Income</td>
    <td width='10%'>$total_interests</td>";
 
    echo "<td width='10%'>
    <form method='post' action='profit_expenses.php'>
    <input type='hidden' name='month' value='$month'>
    <input type='hidden' name='year' value='$year'>
    <button type='submit' name='interest' style='border: 1px solid #D9FFD9; border-radius:3px; font-size:17px; 
    color: green; background-color:#D9FFD9; height:30px; width:160px'>
    &nbsp;View Details &nbsp;</button></form></td>
    </tr>
    <tr>
    <td width='30%'>Processing Fees</td>
    <td width='10%'>$total_fees</td>";

    echo "<td width='10%'>
    <form method='post' action='profit_expenses.php'>
    <input type='hidden' name='month' value='$month'>
    <input type='hidden' name='year' value='$year'>
    <button type='submit' name='fee' style='border: 1px solid #D9FFD9; border-radius:3px; font-size:17px; 
    color: green; background-color:#D9FFD9; height:30px; width:160px'>
    &nbsp;View Details &nbsp;</button></form></td>
    </tr>

    <tr>
    <td width='30%'>Renewed Interest</td>
    <td width='10%'>".number_format($total_fine)."</td>";
    echo "<td width='10%'>
    <form method='post' action='profit_expenses.php'>
    <input type='hidden' name='month' value='$month'>
    <input type='hidden' name='year' value='$year'>
    <button type='submit' name='fine' style='border: 1px solid #D9FFD9; border-radius:3px; font-size:17px; 
    color: green; background-color:#D9FFD9; height:30px; width:160px'>
    &nbsp;View Details &nbsp;</button></form></td>
    </tr>
    <tr>
    <td><b>TOTAL INCOME</b></td>
    <td>".number_format($total_income)."</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
    <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
    <tr>
    <td><b>EXPENSES</b></td>
    <td>&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;&nbsp;&nbsp;</td>
    </tr>";

    // 6. Fix for N+1 Queries: Fetch all expenses grouped by item in ONE query first
    $expense_totals = [];
    $total_expenses = 0;
    $q_grouped_exp = mysqli_query($conn, "SELECT item, SUM(cost) AS total_item_exp FROM expenses WHERE exp_date >= '$startDate' AND exp_date < '$endDate' AND userexp_id='$user_id' AND bossexp_id='$boss_id' GROUP BY item");
    
    while($row = mysqli_fetch_assoc($q_grouped_exp)) {
        $expense_totals[$row['item']] = (float)$row['total_item_exp'];
    }

    // Now loop through the master expenses list and map the grouped totals
    $select = mysqli_query($conn,"SELECT item, exp_id FROM expenses_list");
    while($selected = mysqli_fetch_array($select)){  
        $item = $selected["item"]; 
        $exp_id = $selected["exp_id"]; 

        $total = isset($expense_totals[$item]) ? $expense_totals[$item] : 0;
        $total_expenses += $total;

        echo"<tr>
        <td><a href='#'><font color='black'>$item</font></a></td> 
        <td><a href='#'><font color='black'>".number_format($total)."</font></a></td>
        <td width='10%'>
        <form method='post' action='profit_expenses.php'>
        <input type='hidden' name='exp_id' value='$exp_id'>
        <input type='hidden' name='user_id' value='$user_id'>
        <input type='hidden' name='boss_id' value='$boss_id'>
        <input type='hidden' name='month' value='$month'>
        <input type='hidden' name='year' value='$year'>
        <button type='submit' name='expenses' style='border: 1px solid #D9FFD9; border-radius:3px; font-size:17px; 
        color: green; background-color:#D9FFD9; height:30px; width:160px'>
        &nbsp;View Details &nbsp;</button></form></td>
        </tr>";
    }

    $profit = $total_income - $total_expenses;
    $closing_display = isset($closing) ? number_format($closing) : 0; // Ensures $closing variable from included file doesn't throw a warning

    echo "
    <tr>
    <td><b>Money in Circulation</td><td>".number_format($total_debt)."</td><td></td>
    </tr>
    <tr>
    <td><b>Total Income</td><td>".number_format($total_income)."</td><td></td>
    </tr>
    <tr>
    <td><b>Total Expenses</b></td><td>".number_format($total_exp)."</td><td></td>
    </tr>
    <tr>
    <td><b>Profit</td><td>".number_format($profit)."</td><td></td>
    </tr>
    <tr>
    <td><b>Cash at Hands</td><td>".$closing_display."</td><td></td>
    </tr>
    </tbody>
    </table>";
}
else{
    echo "Select Month and Year";
}
?>
 
</div>
</div>
</div>
 
</main>
</body> 
</html>
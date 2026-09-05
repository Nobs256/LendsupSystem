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
<p align="center"> 
<?php include ('summary_admin.php') ;

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
$month=date('m');

 
//Total Interest in this month
$select = mysqli_query($conn,"SELECT * FROM loans where  MONTH(b_date)='$month'  and bossese_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_given"]; 
$interest = 20/100*$amount;
$total_interest+=$interest;
$total_interests=number_format($total_interest);
}

//Total Reg fee in this month
$select = mysqli_query($conn,"SELECT * FROM loans where  MONTH(b_date)='$month'  and bossese_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["reg_fee"]; 
$total_fee+=$amount;
$total_fees=number_format($total_fee);
}

 

//Total Income

$total_income=$total_interest+$total_fee+$total_charge;
$total_incomes=number_format($total_income);

//Total Expenses
 
$select = mysqli_query($conn,"SELECT * FROM expenses where MONTH(exp_date)='$month'  and bossexp_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$ex_amount= $selected["cost"]; 
$total_exp+=$ex_amount;
$total_exps=number_format($total_exp);
}

//Profit
$total_profit=0;
$total_profit=number_format($total_income-$total_exp);
?>
<div id="main_heading"> 
<table><tr><td><b>STATEMENT OF INCOME AND RETAINED EARNINGS</b> </td><td><font color="#EAEAEA">--</font></td>
<td>
<form method="post"> 
<div class="select is-success">  
<select  name="user_id"  style="width:180px; border: 1px solid #006F37; height:35px" required> 
<option>Branch</option>
<?php
$comb = mysqli_query($conn,"SELECT * from new_users where boss_id='$boss_id' and active=1 and category='User'");
while($select_comb = mysqli_fetch_array($comb)){
$branch=$select_comb['branch']; 
$user_id=$select_comb['user_id']; 
echo "<option value= $user_id> $branch </option>"; 
}
echo" 
</select></div>";
?>
<div class="select is-success">                   
<select  name="months"  style="width:180px; border: 1px solid #006F37; height:35px" required> 
<option>Month</option>
<option value="01">January</option> 
<option value="02">Febuary</option>
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
<option>Year:</option>
<?php 
 for ($yr=2021; $yr <=2040 ; $yr++) { 
  echo"<option> $yr </option>";
 }  
echo" 
</select></div>";
?>
<button type="submit" name="monthly_report" class="button is-primary" 
style="border: 1px solid #006F37; border-radius:4px; color:white; background-color:#006F37">
&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;</button>
</label>   
</form>     
</td></tr></table>

</div>     
 
<div id="main_container">
<div id="main_body">   
    
<br>  
<?php
$mon="";
$total_amounts=0;
if(isset($_POST['monthly_report'])){
$month = $_POST['months'];
$user_id= $_POST['user_id'];
$year = $_POST['year'];

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from new_users where boss_id='$boss_id' and active=1 and category='User' and user_id='$user_id' "));
$branch=$results['branch']; 

}
else{
$month=date('m');
$year = date('Y');

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from new_users where boss_id='$boss_id' and active=1 and category='User'"));
$branch=$results['branch']; 
$user_id=$results['user_id'];
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

 
//Total Interest in this month
$select = mysqli_query($conn,"SELECT * FROM loans where  MONTH(b_date)='$month' and userse_id='$user_id' and bossese_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_given"]; 
$interest = 20/100*$amount;
$total_interest+=$interest;
$total_interests=number_format($total_interest);
}

//Total Reg fee in this month
$select = mysqli_query($conn,"SELECT * FROM loans where  MONTH(b_date)='$month' and userse_id='$user_id' and bossese_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["reg_fee"]; 
$total_fee+=$amount;
$total_fees=number_format($total_fee);
}

//Total Income
$total_income=$total_interest+$total_fee;
 

//Total Expenses
 
$select = mysqli_query($conn,"SELECT * FROM expenses where MONTH(exp_date)='$month' and userexp_id='$user_id' and bossexp_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$ex_amount= $selected["cost"]; 
$total_exp+=$ex_amount;
$total_exps=number_format($total_exp);
}

//Profit
$total_profit=0;
$total_profit=number_format($total_income-$total_exp);
echo"<table border=0 width=100%><tr>
<td width=40%>
<b>Total Proft in $mon-$year from $branch is $total_profit  </b></td>";

echo "<td width=20%>
<form method='post' action='print_profit.php' target='_blank'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='branch' value='$branch'>
<input type=hidden name='month' value='$month'>
<input type=hidden name='year' value='$year'>
 
<button type='submit' name=profit style='border: 1px solid #7C7C7C; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:100px'>
&nbsp;Print &nbsp;</button></form></td>
</tr></table>";

echo "<br><br>";
echo "<table width=100% border=0>
<tr>
<td width='50%'>";

echo "<table width=100% border=1>
<thead>
<tr>
<th>REVENUE (INCOME)</th>
<th></th>

</tr>
</thead>
<tbody>
<tr>
<td width=30%>Gross Interest Income</td>
<td width=10%>$total_interests</td></tr>
<td width=30%>Loan Processing Fees (Reg fee)</td>
<td width=10%>$total_fees</td></tr>
</tr>
<tr><td><b>TOTAL INCOME</b></td><td>".number_format($total_income)."</td></tr>
<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>

<tr>
<td><b>EXPENSES</b></td>
<tr>
</tr>";

$select = mysqli_query($conn,"SELECT * FROM expenses_list where bossexp_id='$boss_id'");
while($selected= mysqli_fetch_array($select)){  
$item= $selected["item"]; 
$exp_id= $selected["exp_id"]; 

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(cost) as total_exps FROM expenses where 
MONTH(exp_date)='$month' and userexp_id='$user_id' and bossexp_id='$boss_id' and item='$item'"));
$total=$results['total_exps'];
$total_expenses+=$total;
echo"
<td><a href=profit.php?item=$exp_id><font color=black>$item</font></a></td> 
<td><a href=profit.php?item=$exp_id><font color=black>".number_format($total)."</font></a></td></tr>";
}
$profit=$total_income-$total_expenses;
echo "
<tr>
<td><b>Total Expenses</b></td><td>".number_format($total_expenses)."</td></tr>
<td><b>Profit Before Tax</td><td>".number_format($profit)."</td></tr>
<tr>
<td><b>Retained Earnings</td><td><b>".number_format($profit)."</b></td></tr>
</table>

</td><td>";
//get the expenses details
if(isset($_REQUEST['item']))
{   
$exp_id = $_REQUEST['item'];

echo "<table width=100% border=1>
<thead>
<tr>
 <p align=center>Expenses Details</p>
 
</tr>
</thead>
<tbody>
<tr>
<td width=16%>Date</td>
<td width=30%>Item</td>
<td width=10%>Cost</td>
<td width=30%>Naration</td>
</tr>";

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM expenses_list where 
exp_id='$exp_id' and bossexp_id='$boss_id'"));
$item=$results['item'];
 
$select = mysqli_query($conn, "SELECT * FROM expenses where 
MONTH(exp_date)='$month' and userexp_id='$user_id' and bossexp_id='$boss_id' and item='$item' order by exp_date");
while($selected= mysqli_fetch_array($select)){
$item= $selected["item"]; 
$exp_id= $selected["exp_id"];
$item= $selected["item"]; 
$exp_date= $selected["exp_date"]; 
$cost= $selected["cost"]; 
$naration= $selected["naration"]; 

echo "<tr>";

echo"<td>".date("d-m-Y", strtotime($exp_date))."</td>
<td> $item</td>
<td> $cost</td>
<td> $naration</td>
</tr>";
}
echo "</td></tr></table>";
}
?>
 
</div>
</div>
</div>
 
</main>
</body> 
</html>
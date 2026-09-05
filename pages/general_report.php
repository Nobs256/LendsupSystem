<?php
include('header.php');
 ?>
 <style>
 tr:nth-child(even) {
background-color: #D9FFD9;
}

th, td {
text-align: left;
padding-left: 4px;
 
}
</style>
<main class="column main" style="background-color:#EAEAEA;"> 
<?php include ('summary_admin.php') ?>
 
<div id="main_heading"> 
<table><tr><td><b>General Report</b>   </td><td> <font color="#EAEAEA">---------------</font></td>
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
<p> 
</div>
<div id="main_container">     
<div id="main_body" style="border: 0px solid #006F37;"> 
<?php
if(isset($_POST['monthly_report'])){
$month = $_POST['months'];
$user_id= $_POST['user_id'];
$year = $_POST['year'];

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from new_users where boss_id='$boss_id' and active=1 and category='User' and user_id='$user_id' "));
$branch=strtoupper($results['branch']); 

}
else{
$month=date('m');
$year = date('Y');

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from new_users where boss_id='$boss_id' and active=1 and category='User'"));
$branch=strtoupper($results['branch']); 
$user_id=$results['user_id'];
}


$dateObj   = DateTime::createFromFormat('!m', $month);
$monthName = $dateObj->format('F'); 

$total_amount_mom=0;
$total_paid_loan=0;
$total_balance=0;
$total_op=0;
$total_de=0;
$total_exp=0;
$amount_ch=0;
$total_ukno=0;
$op_amount=0;
$total_given_loan=0;
$total_reg_fee=0;
$total_interest=0;
$total_cr=0;

echo"
<p align=center><b>$branch BRANCH <br> $monthName $year</b></p><br> 
<table width=100% border=1 style='font-size:14px'>
<tr>
<th width=9%>Date</th>
<th width=9%>Opening Balance</th>
<th width=10%>Cash Out<br>(Loan Given)</th>
<th width=7%>Loan Paid</th>
<th width=8%>Cash Recieved</th>
<th width=11%>Cash In<br>(Loan Paid + Others)</th>
<th width=8%>Total Interest</th>
<th width=8%>Total Expenses</th>
<th width=7%>Banking</th>
<th width=8%>Closing Stock</th>
<th width=8%>Profit</th>
</tr>";
$results = mysqli_fetch_assoc(mysqli_query($conn," SELECT * FROM op where  MONTH(op_date)='$month' and YEAR(op_date)='$year'
and userop_id='$user_id' and bossop_id='$boss_id' and op_amount>0 order by op_date "));
$op_date=$results['op_date']; 

 
$select = mysqli_query($conn,"SELECT * FROM general_report where  MONTH(op_date)='$month' and userop_id='$user_id' and bossop_id='$boss_id' order by op_date desc");
while($selected= mysqli_fetch_array($select)){  
$op_date= $selected["op_date"]; 
$op_amount= $selected["op_amount"]; 
$total_given_loan= $selected["loan_given"]; 
$total_paid_loan= $selected["loan_paid"]; 
$total_reg_fee= $selected["reg_fee"]; 
$other_cash_in= $selected["other_cash_in"]; 
$total_exp= $selected["expenses"]; 
//banking
$results = mysqli_fetch_assoc(mysqli_query($conn," SELECT * FROM deposit where de_date='$op_date'
and userde_id='$user_id' and bossde_id='$boss_id'"));
$total_de=$results['de_amount'];
$ci=0;

//cash Recived
$results = mysqli_fetch_assoc(mysqli_query($conn," SELECT * FROM cr where  cr_date='$op_date'
and usercr_id='$user_id' and bosscr_id='$boss_id'"));
$total_cr=$results['cr_amount'];
 
$ci=$total_reg_fee+$other_cash_in;
$tci=$total_paid_loan+$ci;
$total_interest=$tci*0.2;
$profit=$total_interest-($total_de+$total_exp);
$cs=($op_amount+$tci+$total_cr)-($total_given_loan+$total_exp+$total_de);

echo"
<tr>
<td>".date("d-m-Y", strtotime($op_date))."</td>
<td>".number_format($op_amount)."</td>
<td>".number_format($total_given_loan)."</td>
<td>".number_format($total_paid_loan)."</td>
<td>".number_format($total_cr)."</td>
<td>".number_format($tci)."</td>
<td>".number_format($total_interest)."</td>
<td>".number_format($total_exp)."</td>
<td>".number_format($total_de)."</td>
<td>".number_format($cs)."</td>
<td>".number_format($profit)."</td>
</tr>
";
 
}
echo "</table>";
?>
 </div>
 </div>
 <?php include('footer.php'); ?>
 </main>
</body> 
</html>
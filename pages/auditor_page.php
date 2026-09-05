<?php
include('header_user.php');
?>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> <?php include ('summary.php') ?>
<div id="main_heading"> <b>AUDITOR'S PAGE </b></div>

<div id="main_container">
<div id="main_body">   
<br>
<p><font size="4">Select </font></p>
<br>                               

<table style="width:730px;" border="1">
<tr><td width="150px">

<ul>

<li><a href="all_loans_given_out.php" style="color:black; font-size:17px;"> >> All Loan Given out</a> </li>
<li><a href="running_loans_user.php" style="color:black; font-size:17px;"> >> Running Loans</a></li>
<li><a href="clients_completed_loan.php" style="color:black; font-size:17px;"> >> Completed Loans</a></li>
<li><a href="payables.php" style="color:black; font-size:17px;"> >> Payables</a></li>
<li><a href="recievables.php" style="color:black; font-size:17px;"> >> Recievables</a></li>
<li><a href="banking_history.php" style="color:black; font-size:17px;"> >> Banking </a></li>
<li><a href="trial_balance.php" style="color:black; font-size:17px;"> >> Trial Balance</a></li>
<li><a href="mobile_money_audit.php" style="color:black; font-size:17px;"> >> Mobile Money</a></li>
<li><a href="mom_charges_audit.php" style="color:black; font-size:17px;"> >> MOM Charges </a></li>
<li><a href="profit_user.php" style="color:black; font-size:17px;"> >> Loss and Profit</a></li>
<!--<li><a href="edit_loan_date.php" style="color:black; font-size:17px;"> >> Edit Loan Dates</a></li>-->
</ul>
</li>
</td> 
</tr></table>  

</div>
</div>
<div> 
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>
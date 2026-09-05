<?php
include('header.php');
?>
<style type="text/css">
 

th, td {
text-align: left;
padding: 2px;
padding-top: 2px;
}

form {
border-collapse: collapse;
}

 
</style>
<main class="column main" style="background-color:#EAEAEA;"> 
<?php include ('summary_admin.php') ;
 ?>
 
 
<div id="main_heading"> 
 <b>Clients With Loans</b>    
</div>     
 
<div id="main_container">
<div id="main_body">   
    
<br>  
 
 
<br> 
<?php
echo"<p align=center><font size=4><b>A Graph Showing Number of Defaulters</b></font></p><br>";
echo"<table width=100% border=1>
<tr>
<td width=15%></td>
<td width=60%>&nbsp;<b>Defaulters</b></td>
<td>&nbsp;<b>At Risk</td>
<td>&nbsp;<b>Active</b></t>
<td>&nbsp;<b>Total balance</b></td>
</tr>";
 
//starting userId
$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from new_users where boss_id='$boss_id' and category='User' order by user_id"));
$user_id_st = $results["user_id"];

//starting userId
$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from new_users where  boss_id='$boss_id' and category='User' order by user_id Desc"));
$user_id_end = $results["user_id"];

$active=0;
$at_risk=0;
$defaulters=0;
$balance=0;
$balance_active=0;
$balance_risk=0;
$balance_def=0;
$total_balance=0;
$total_no=0;
$total_active=0;
$total_risk=0;
$total_defaulters=0;


for ($user_id=$user_id_st; $user_id <= $user_id_end; $user_id++) { 
//starting userId
$hup=mysqli_query($conn,"SELECT * FROM clients_with_loan where bosseseid='$boss_id' and userseid='$user_id'");
$now=mysqli_fetch_array($hup);
$user_id_loan=$now["userseid"];
if($user_id_loan==$user_id){

$search_query= mysqli_query($conn,"SELECT  *
FROM clients, clients_with_loan where bosses_id='$boss_id' and userseid='$user_id' 
and client_id=clientsid ");  
while($returned_result = mysqli_fetch_assoc($search_query)){
 
$client_id=$returned_result["client_id"];
$name = strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);
$phone=$returned_result["phone"];
$user_ids  = $returned_result["users_id"];
$loanNo = $returned_result["loan_no"];
$date_given= $returned_result["pay_date"];
$amount_given = $returned_result["amount_given"];
$daily_p = $returned_result["daily_p"];
$debt= $returned_result["debt"];

$interest=$amount_given*20/100;
$total_amount=$amount_given+$interest; 
$curr_date=date('Y-m-d');
$prev_date = date("Y-m-d", strtotime("$curr_date -1 day")); 
//starting date                                              --nyesiga david
$first_date = date("Y-m-d", strtotime("$date_given +1 day"));
$end_date = date("Y-m-d", strtotime("$date_given +30 day"));

$pre_yr=date("Y",strtotime($date_given));
$curr_yr=date("Y",strtotime($curr_date));
//First date
$x=0;
$not_paid_at_all=0; 
//No of days
$risk_date=date("Y-m-d", strtotime("$end_date +1 day"));
$defaulter_date=date("Y-m-d", strtotime("$end_date +30 day"));

 
$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from new_users where user_id='$user_id'"));
$branch = $results["branch"];
//days
$date1=date_create("$prev_date");
$date2=date_create("$date_given");
$diff=date_diff($date1,$date2);
$y=$diff->format("%a");

if($y<30){
$active++;
$balance_active+=$debt;
}

else if($y==30){
$at_risk++;
$balance_risk+=$debt;
}

else{
$defaulters++;
$balance_def+=$debt;
}
} 
 
$width=0;
$width=$defaulters*2;
if($width==0)
{
$width=10;
}

$total_active+=$active;
$total_risk+=$at_risk;
$total_defaulters+=$defaulters;

$total_balance+=$balance_active+$balance_risk+$balance_def;
$total_no+=$active+$at_risk+$defaulters;
echo"
<tr>
<td>&nbsp;".strtoupper($branch)."</td>
<td>
<table border=0>
<tr>
<td>
<div style='background-color:#006F37; width:".$width."px'> &nbsp;</div>
</td><td>$defaulters</td></tr></table>
</td>
 
<td>&nbsp;".$at_risk."</td>
<td>&nbsp;".$active."</td>

<td>&nbsp;".number_format($balance_active+$balance_risk+$balance_def)."</td>
</tr>
";
 
$balance=0;
$defaulters=0;
$$amount_given=0;
$at_risk=0;
$active=0;
$balance_active=0;
$balance_risk=0;
$balance_def=0;
 
}
}
 
echo"<tr><td></td>
<td colspan=4><b>  

 Defaulters: $total_defaulters&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 At Risk: $total_risk &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 Active: $total_active &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 
 Total: $total_no &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 Total Amount Out: ".number_format($total_balance)."</b>
</tr>
</table>";
?>

 
</div>
</div>
</div>
 
<?php include('footer.php'); ?>

</main>
<script type="text/javascript">
$(document).ready(function()
{
$("#client_info").modal("show");
$("#client_update").modal("show");
$("#tr_sms").modal("show");
 
});


</script>
</body> 
</html>
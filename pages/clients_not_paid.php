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
<p align="center"> <?php include ('summary.php') ?>
 
<div id="main_heading"> 
	<br><br>
<table border="0" width="100%">
<tr><td>
<b>Clients who Have Not Paid</b>
</td>
<td>
<font color="#E4E4E4">---------- ---</font>
</td>
<td widith="40%">
<form  method="post"> 
Select Date: 
<input type="date" name="p_date" style="width:190px; height:35px; border: 1px solid #006F37" required> 
</td><td>
<div class="select is-success">  
<select  name="officer_id"  style="width:180px; border: 1px solid #006F37; height:35px" required> 
<option>Field Officer:</option>
<?php
$comb = mysqli_query($conn,"SELECT * from officers where boss_id='$boss_id' and active=1 and branch='$bra'");

while($select_comb = mysqli_fetch_array($comb)){
$officer_id=$select_comb['officer_id'];
$officers_name=$select_comb['firstname']." ".$select_comb['lastname'];
 
echo "<option value= $officer_id>".strtoupper($officers_name)." </option>";
 
}
echo" 
</select></div>";
?>
<button type="submit" name="not_paid" class="button is-primary" 
style="background-color:#006F37; color:white; font-size:12px; border:1px solid #006F37; height:35px; margin-left:4px ">
&nbsp;OK&nbsp;</button></form> 
 
</td>
<td>
<?php
echo"
<form method='post' action='clients_not_paid_all.php'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='branch' value='$bra'>
<button type='submit' name=notpaid style='border: 1px solid #7C7C7C; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:180px; margin-left:2px'>
&nbsp;View All Clients &nbsp;</button></form>";?>
</td>
</tr></table>
</div>
<br>  
</div>     
 
<div id="main_container">
<div id="main_body" style="width: 1200px; ">   
    
<br>  
<?php
if(isset($_POST['not_paid'])){       
$date = $_POST['p_date'];  
$officer_id = $_POST['officer_id'];
$payed_date=date("Y-m-d", strtotime($date));
$d=date("d-m-Y", strtotime($date));

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from new_users where boss_id='$boss_id' and active=1 and category='User' and user_id='$user_id'"));
$branch=strtoupper($results["branch"]);

$returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and officer_id='$officer_id' and branch='$branch'"));     
$name = strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);
$location  = $returned_result["location"];
$j=0;
 


echo "<table><tr><td><p align=center><font size=4><b>$branch BRANCH $name</b><br>
   Clients not Paid on <b> $d </b> </font><br><br></td>";
echo"<td>
<form method='post' action='print_not_paid.php' target='_blank'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='branch' value='$branch'>
<input type=hidden name='officer_id' value='$officer_id'> 
<input type=hidden name='payed_date' value='$date'>

<button type='submit' name=notpaid style='border: 1px solid #7C7C7C; border-radius:3px; font-size:17px; 
color: green; background-color:white; height:30px; width:150px; margin-left:300px'>
&nbsp;Print Report &nbsp;</button></form></td></tr></table>";
echo "<table width=90% border=1 style='font-size:14px'>
<thead>
<tr>
<th width=3%>No</th>
<th width=15%>Names</th>
<th width=7%>Phone</th>
<th width=6%>Loan Given</th>
<th width=8%>Date Given</th>
<th width=8%>Last Date Paid</th>
<th width=6%>Last Amt Paid</th>
<th width=6%>Balance</th>
<th width=5%>Days Missed</th>
<th width=6%>Arears</th>
<th width=10%>Total Days</th>

</tr>
</thead>
<tbody>";

$curr_date = date('Y-m-d');
$prev_date = date("Y-m-d", strtotime("-1 day"));

$query = "SELECT c.client_id, c.firstname, c.lastname, c.phone, 
                 cl.pay_date, cl.amount_given, cl.debt, cl.loan_no, cl.daily_p,
                 COALESCE(lp_agg.total_paid, 0) as total_paid,
                 COALESCE(lp_agg.paid_on_date, 0) as paid_on_date,
                 COALESCE(lp_last.p_date, '1970-01-01') as last_p_date,
                 COALESCE(lp_last.amount_paid, 0) as last_amount_paid
          FROM clients c 
          JOIN clients_with_loan cl ON c.client_id = cl.clientsid 
          LEFT JOIN (
              SELECT clients_id, loanNo, 
                     SUM(amount_paid) as total_paid,
                     SUM(CASE WHEN p_date = '$payed_date' THEN 1 ELSE 0 END) as paid_on_date
              FROM loan_pay 
              GROUP BY clients_id, loanNo
          ) lp_agg ON cl.clientsid = lp_agg.clients_id AND cl.loan_no = lp_agg.loanNo
          LEFT JOIN (
              SELECT lp1.clients_id, lp1.loanNo, lp1.p_date, lp1.amount_paid
              FROM loan_pay lp1
              INNER JOIN (
                  SELECT clients_id, loanNo, MAX(p_date) as max_d 
                  FROM loan_pay GROUP BY clients_id, loanNo
              ) lp2 ON lp1.clients_id = lp2.clients_id AND lp1.loanNo = lp2.loanNo AND lp1.p_date = lp2.max_d
              GROUP BY lp1.clients_id, lp1.loanNo
          ) lp_last ON cl.clientsid = lp_last.clients_id AND cl.loan_no = lp_last.loanNo
          WHERE c.users_id='$user_id' AND c.bosses_id='$boss_id' AND c.b_location = '$location' AND cl.debt > 0
          ORDER BY CASE WHEN lp_last.p_date IS NULL OR lp_last.p_date = '1970-01-01' THEN cl.pay_date ELSE lp_last.p_date END ASC";

$search_query = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($search_query)) {
    $diff = date_diff(date_create($prev_date), date_create($row["pay_date"]));
    $days_elapsed = $diff->format("%a");
    $arears = $days_elapsed * $row["daily_p"] - $row['total_paid'];
    $total_days = $row['daily_p'] > 0 ? ceil($arears / $row['daily_p']) : 0;

// if ($row['paid_on_date'] == 0 && $row["pay_date"] != $curr_date && $row["pay_date"] != $prev_date && $arears > 0 && $total_days < 61) {

    if ($row['paid_on_date'] == 0 && $row["pay_date"] != $curr_date && $row["pay_date"] != $prev_date && $arears > 0) {
        $j++;
        $last_dt = $row['last_p_date'];
        $dm_diff = date_diff(date_create(($last_dt == '1970-01-01' ? $row['pay_date'] : $last_dt)), date_create($curr_date));
        $days_missed = $dm_diff->format("%a");
        
        if ($last_dt == '1970-01-01') {
            $last_date_paid = "<font color=red>Nothing Paid</font>";
            $days_missed_disp = "<font color=red><b>$days_missed days </b></font>";
        } else {
            $last_date_paid = date("d-m-Y", strtotime($last_dt));
            $days_missed_disp = $days_missed;
        }

        $status = $total_days;
        if ($total_days >= 30 && $total_days < 61) $status = "At Risk";
        else if ($total_days >= 61) $status = "Defaulter";

        echo "<tr style='font-size:12px'>
            <td>$j</td>
            <td>" . strtoupper($row['firstname'] . " " . $row['lastname']) . "</td>
            <td>{$row['phone']}</td>
            <td>" . number_format($row['amount_given']) . "</td>
            <td>{$row['pay_date']}</td>
            <td>$last_date_paid</td>
            <td>" . number_format($row['last_amount_paid']) . "</td>
            <td>" . number_format($row['debt']) . "</td>
            <td>$days_missed_disp</td>
            <td>" . number_format($arears) . "</td>
            <td>$status</td>
        </tr>";
    }
}
echo "</tbody></table>";
echo "<br><font size=4><b>Total Number of Clients Not Paid is $j<br>";

}
else{
echo" <font size=5>Select Field Officer and Date</font>
<hr></hr>
";
}

 
echo"
</div>
</div>
</div>
</div>
<div>"; 
  
?>
</div>
</main>
</body> 
</html>
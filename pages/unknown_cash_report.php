<?php
include('header_user.php');

// Fetch the user's branch BEFORE the form so the dropdown populates correctly
$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM new_users WHERE boss_id='$boss_id' AND active=1 AND category='User' AND user_id='$user_id'"));
$branch = strtoupper($results["branch"]);
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
<b>Unknown Cash Transactions Details</b>
</td>
<td>
<font color="#E4E4E4">---------- ---</font>
</td>
<td width="40%">
<form method="post"> 
From Date: 
<input type="date" name="from_date" value="<?php echo date('Y-m-01'); ?>" style="width:170px; height:35px; border: 1px solid #006F37" required> 
To Date: 
<input type="date" name="to_date" value="<?php echo date('Y-m-d'); ?>" style="width:170px; height:35px; border: 1px solid #006F37" required> 
</td><td>
<div class="select is-success">  
<select name="officer_id" style="width:180px; border: 1px solid #006F37; height:35px"> 
<option value="">All Field Officers</option>
<?php
$comb = mysqli_query($conn,"SELECT * FROM officers WHERE boss_id='$boss_id' AND active=1 AND branch='$branch'");

while($select_comb = mysqli_fetch_array($comb)){
    $officer_id = $select_comb['officer_id'];
    $officers_name = $select_comb['firstname']." ".$select_comb['lastname'];
    echo "<option value='$officer_id'>".strtoupper($officers_name)." </option>";
}
?> 
</select></div>
<button type="submit" name="unknown_cash_report" class="button is-primary" 
style="background-color:#006F37; color:white; font-size:12px; border:1px solid #006F37; height:35px; margin-left:4px ">
&nbsp;OK&nbsp;</button></form> 
 
</td>
</tr></table>
</div>
<br>  
</div>     
 
<div id="main_container">
<div id="main_body" style="width: 1200px;">   
    
<br>  
<?php
if(isset($_POST['unknown_cash_report'])){       
    $from_date = date("Y-m-d", strtotime($_POST['from_date']));  
    $to_date   = date("Y-m-d", strtotime($_POST['to_date']));
    $officer_filter = $_POST['officer_id'];
    $f = date("d-m-Y", strtotime($from_date));
    $t = date("d-m-Y", strtotime($to_date));

    // Officer names lookup for this branch
    $officer_names = array();
    $comb = mysqli_query($conn,"SELECT * FROM officers WHERE boss_id='$boss_id' AND branch='$branch'");
    while($select_comb = mysqli_fetch_array($comb)){
        $officer_names[$select_comb['officer_id']] = strtoupper($select_comb['firstname']." ".$select_comb['lastname']);
    }

    $officer_cond = "";
    if($officer_filter != ""){
        $officer_cond = " AND officer='$officer_filter'";
    }

    // Header and Print Button
    echo "<table><tr><td><p align=center><font size=4><b>$branch BRANCH</b><br>
       Unknown Cash Transactions Details from <b> $f </b> to <b> $t </b> </font><br><br></td>";
    echo"<td>
    <form method='post' action='print_unknown_cash_report.php' target='_blank'>
    <input type=hidden name='user_id' value='$user_id'>
    <input type=hidden name='boss_id' value='$boss_id'>
    <input type=hidden name='branch' value='$branch'>
    <input type=hidden name='officer_id' value='$officer_filter'> 
    <input type=hidden name='from_date' value='$from_date'>
    <input type=hidden name='to_date' value='$to_date'>

    <button type='submit' name='print_unknown' style='border: 1px solid #7C7C7C; border-radius:3px; font-size:17px; 
    color: green; background-color:white; height:30px; width:150px; margin-left:300px'>
    &nbsp;Print Report &nbsp;</button></form></td></tr></table>";

    // ============ Detailed transactions in the range ============
    $detail_query = "(SELECT uc.unknown_date AS tdate, uc.amount, uc.officer, 'Unknown Cash In' AS ttype
                     FROM unknown_cash uc 
                     WHERE uc.user_id='$user_id' AND uc.boss_id='$boss_id' AND uc.unknown_date BETWEEN '$from_date' AND '$to_date' $officer_cond)
                     UNION ALL
                     (SELECT wu.unknown_date AS tdate, wu.amount, wu.officer, 'Withdrawn' AS ttype
                     FROM withdraw_unknown_cash wu 
                     WHERE wu.user_id='$user_id' AND wu.boss_id='$boss_id' AND wu.unknown_date BETWEEN '$from_date' AND '$to_date' $officer_cond)
                     ORDER BY tdate ASC";

    $detail_rs = mysqli_query($conn, $detail_query);
    $j = 0;
    $total_detail_in = 0; $total_detail_out = 0;

    echo "<table width=95% border=1 style='font-size:14px'>
    <thead>
    <tr>
    <th width=4%>No</th>
    <th width=15%>Date</th>
    <th width=30%>Field Officer</th>
    <th width=25%>Transaction</th>
    <th width=26%>Amount</th>
    </tr>
    </thead>
    <tbody>";

    while ($row = mysqli_fetch_assoc($detail_rs)) {
        $j++;
        $officer_name = isset($officer_names[$row['officer']]) ? $officer_names[$row['officer']] : "OFFICER #".$row['officer'];
        $ttype = $row['ttype'];
        $amt_disp = number_format($row['amount']);
        if($ttype == 'Withdrawn'){
            $total_detail_out += $row['amount'];
            $amt_disp = "<font color=red>-".$amt_disp."</font>";
        } else {
            $total_detail_in += $row['amount'];
            $amt_disp = "<font color=green>".$amt_disp."</font>";
        }

        echo "<tr style='font-size:13px'>
            <td>$j</td>
            <td>".date("d-m-Y", strtotime($row['tdate']))."</td>
            <td>$officer_name</td>
            <td>$ttype</td>
            <td>$amt_disp</td>
        </tr>";
    }
    echo "</tbody>
    <tfoot>
    <tr style='font-size:13px; background-color:#006F37; color:white; font-weight:bold'>
        <td colspan=4>TOTAL BALANCE</td>
        <td>" . number_format($total_detail_in - $total_detail_out) . "</td>
    </tr>
    </tfoot>
    </table>";

    echo "<br><font size=4><b>Total Number of Transactions: $j</b></font><br>";

}
else{
    echo " <font size=5>Select Date Range and Field Officer</font><hr></hr>";
}
?>
</div>
</div>
</main>
</body> 
</html>
<?php 
session_start();
ob_start();

ini_set('memory_limit', ' 1969994M'); // or you could use 1G
ini_set('max_execution_time', 3600); //300 seconds = 5 minutes
include("conn.php");

if(isset($_POST['notpaid'])){    
$officer_id = $_POST['officer_id']; 
$branch = $_POST['branch']; 
$user_id = $_POST['user_id']; 
$boss_id = $_POST['boss_id']; 
$date = $_POST['payed_date'];  
$payed_date=date("Y-m-d", strtotime($date));
$d=date("d-m-Y", strtotime($payed_date));

//Full Names
$returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and officer_id='$officer_id' "));     
$name =strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);
$location  = $returned_result["location"];
$branch  = $returned_result["branch"];

 
 
$result = mysqli_fetch_assoc(mysqli_query($conn,"select boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
from bosses where boss_id='$boss_id'"));     
$company= strtoupper($result['firstname']);

echo" 
<p align=center> <b>$company LTD ".strtoupper($branch). "BRANCH<br>".strtoupper($name)."
  CLIENTS NOT PAID ON <u> $d </u></b> 
 </font>  <hr>
 ";
 
echo"  
<table border=1 width=100% style='font-size:8.5px; border-collapse: collapse;'>
<thead>
<tr style='white-space: nowrap;'>
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
$j=0;
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

        $firstname = strtoupper($row['firstname'] . " " . $row['lastname']);

        echo "<tr style='white-space: nowrap;'>
            <td>$j</td>
            <td>$firstname</td>
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
mysqli_close($conn);
 }


echo"
<div>"; 


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
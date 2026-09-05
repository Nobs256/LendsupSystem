<?php 
session_start();
ob_start();

ini_set('memory_limit', '1024M'); 
ini_set('max_execution_time', 3600); //300 seconds = 5 minutes
include("conn.php");

if (isset($_POST['notpaid'])) {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $boss_id = mysqli_real_escape_string($conn, $_POST['boss_id']);
    $branch = mysqli_real_escape_string($conn, $_POST['branch']);
    $today = date('d-m-Y');
    $curr_date = date('Y-m-d');
    $prev_date = date("Y-m-d", strtotime("-1 day"));
    $curr_yr = date("Y");

    // Company details
    $result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT ucase(firstname) as firstname FROM bosses WHERE boss_id='$boss_id'"));
    $company = $result['firstname'];

    echo "<p align=center><b>$company LTD <br>DEFAULTERS LIST (OVER 60 DAYS) - " . strtoupper($branch) . " Branch ($today)</b></p><br>";

    // Optimized Main Query with aggregated payments and joins
    $main_query = "SELECT c.client_id, c.firstname, c.lastname, c.phone, 
                          cl.loan_no, cl.pay_date, cl.amount_given, cl.daily_p, cl.debt,
                          g.g_phone1, g.g_names1,
                          s.security,
                          COALESCE(lp.total_amount_paid, 0) as total_amount_paid
                   FROM clients c
                   JOIN clients_with_loan cl ON c.client_id = cl.clientsid
                   LEFT JOIN (
                       SELECT clientg_id, g_date, MAX(g_phone1) as g_phone1, MAX(g_names1) as g_names1
                       FROM guarantor
                       WHERE bossesg_id = '$boss_id'
                       GROUP BY clientg_id, g_date
                   ) g ON c.client_id = g.clientg_id AND cl.pay_date = g.g_date
                   LEFT JOIN (
                       SELECT cliente_id, b_date, MAX(security) as security
                       FROM loans
                       WHERE bossese_id = '$boss_id'
                       GROUP BY cliente_id, b_date
                   ) s ON c.client_id = s.cliente_id AND cl.pay_date = s.b_date
                   LEFT JOIN (
                       SELECT clients_id, loanNo, SUM(amount_paid) as total_amount_paid 
                       FROM loan_pay 
                       GROUP BY clients_id, loanNo
                   ) lp ON cl.clientsid = lp.clients_id AND cl.loan_no = lp.loanNo
                   WHERE c.bosses_id='$boss_id' AND cl.userseid='$user_id' AND cl.debt > 0
                   ORDER BY cl.pay_date DESC";

    $search_query = mysqli_query($conn, $main_query) or die(mysqli_error($conn));

    $defaulters_data = [];
    $yearly_summary = [];

    while ($row = mysqli_fetch_assoc($search_query)) {
        $client_id = $row["client_id"];
        $date_given = $row["pay_date"];
        $debt = $row["debt"];
        $daily_p = $row["daily_p"];
        $total_amount_paid = $row["total_amount_paid"];
        $amount_given = $row["amount_given"];

        $end_date = date("Y-m-d", strtotime("$date_given +30 days"));
        $risk_date = date("Y-m-d", strtotime("$end_date +1 day"));
        $defaulter_date = date("Y-m-d", strtotime("$end_date +30 days"));
        $pre_yr = date("Y", strtotime($date_given));

        $missed_balance = 0;
        $x = 0;

        if ($defaulter_date < $prev_date) {
            $x = 91;
            $missed_balance = $debt;
        } else if ($risk_date < $prev_date) {
            $x = 30;
            $missed_balance = $debt;
        } else if ($risk_date > $prev_date && $defaulter_date > $prev_date) {
            $diff = date_diff(date_create($prev_date), date_create($date_given));
            $y = $diff->format("%a");
            $amount_supposed_paid = $y * $daily_p;
            $missed_balance = max(0, $amount_supposed_paid - $total_amount_paid);
            $x = ($daily_p > 0) ? $missed_balance / $daily_p : 0;
        }

        if ($date_given == $prev_date || $curr_date == $date_given) {
            $x = 0;
            $missed_balance = 0;
        }

        if ($pre_yr < $curr_yr) {
            $x = 120;
            $missed_balance = $debt;
        }

        if ($risk_date < $curr_date && $x < 0) {
            $x = 30;
            $missed_balance = $debt;
        }

        if ($missed_balance < $daily_p && $x == 0) {
            $missed_balance = 0;
        }

        // Defaulter logic
        if ($x > 60) {
            $row['days_missed'] = $x;
            $row['arreas'] = $missed_balance;
            $row['interest'] = $amount_given * 20 / 100;
            $row['due_date'] = $end_date;
            $defaulters_data[] = $row;

            // Yearly summary accumulation
            if (!isset($yearly_summary[$pre_yr])) {
                $yearly_summary[$pre_yr] = ['count' => 0, 'balance' => 0];
            }
            $yearly_summary[$pre_yr]['count']++;
            $yearly_summary[$pre_yr]['balance'] += $debt;
        }
    }

    // Sort defaulters by days missed descending
    usort($defaulters_data, function ($a, $b) {
        return $b['days_missed'] - $a['days_missed'];
    });

echo"  
<table border=1 width=100% style='font-size:9px'>
<tr>
<th width=5%>No</th>
<th width=16%>Client's Name</th>
<th width=12%>Guarantor</th>
<th width=10%>Date Given Loan</th>
<th width=10%>Due Date</th>
<th width=8%>Amount Given</th>
<th width=8%>Interest</th>
<th width=8%>Balance</th>
<th width=8%>Total Arrears </th>
<th width=6%>Time Missed</th>
 
</tr>";

    $j = 0;
    $total_def_count = 0;
    $total_def_balance = 0;

    foreach ($defaulters_data as $row) {
        $j++;
        $total_def_count++;
        $total_def_balance += $row['debt'];
        
        $names = strtoupper($row["firstname"] . " " . $row["lastname"]);
        $gname = strtoupper($row["g_names1"] ?: "xxxxxxxxxxxxxxx");
        $phoneg = $row["g_phone1"] ?: "0000000000";
        // $security_on = $row['security'] ? "<br><font color=red><b>(Security in Store)<b></font>" : "";
        $months = ceil($row['days_missed'] / 30);

        echo "<tr style='font-size:8px'>";
        echo "
        <td> $j </td>
        <td>$names<br>{$row['phone']}</td>
        <td>$gname<br>$phoneg </td>
        <td>" . date("d-m-Y", strtotime($row['pay_date'])) . "</td>
        <td>" . date("d-m-Y", strtotime($row['due_date'])) . " </td> 
        <td>" . number_format($row['amount_given']) . "</td>
        <td>" . number_format($row['interest']) . "</td>
        <td>" . number_format($row['debt']) . "</td>
        <td>" . number_format($row['arreas']) . "</td>
        <td><b>$months Mths</b>$security_on</td>";
        // <td><b>$months Mths</b>$security_on</td>";
        echo "</tr>";
    }

echo "
<tr>
<td><td colspan=9> &nbsp;&nbsp;&nbsp;  </td>
</tr>
<tr>
<td></td><td colspan=3>Total Defaulters: $total_def_count </td>
<td colspan=6> Total Outstanding Balance: " . number_format($total_def_balance) . "</td></tr>
</table>
<table border=1 width=100% style='font-size:12px'>
<tr>";

    ksort($yearly_summary);
    foreach ($yearly_summary as $year => $stats) {
        echo "<tr>
        <td colspan=4>$year &nbsp; &nbsp;&nbsp;Total No:{$stats['count']} &nbsp;&nbsp;&nbsp;  Total balance:&nbsp;" . number_format($stats['balance']) . "</td></tr>";
    }

echo"
</tbody></table>"; 
}

echo"
<div>"; 

include("mpdf60/mpdf.php");
$mpdf = new mPDF(); 
$stylesheet = file_get_contents('mpdf60/pdf.css'); 
$mpdf->WriteHTML($stylesheet,1);
$mpdf->SetDisplayMode('fullpage');

// LOAD a stylesheet
$stylesheet_tables = file_get_contents('mpdf60/mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet_tables,1);
$report_content=ob_get_contents();
ob_clean();

$mpdf->WriteHTML($report_content,2);
$mpdf->Output('Defaulters_Report.pdf','I');
exit;
?>
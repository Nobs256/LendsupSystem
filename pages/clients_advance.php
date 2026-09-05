<?php
include('header_user.php');

$selected_officer_id = 0;
$selected_officer = null;
$rows = [];
$report_date = date('Y-m-d', strtotime('-1 day'));

if (isset($_POST['advance_report'])) {
    $selected_officer_id = (int)($_POST['officer_id'] ?? 0);
    $officer_query = mysqli_query($conn, "SELECT officer_id, firstname, lastname, location, branch FROM officers WHERE officer_id='$selected_officer_id' AND boss_id='$boss_id' AND branch='$bra' AND active=1");
    $selected_officer = mysqli_fetch_assoc($officer_query);

    if ($selected_officer) {
        $location = mysqli_real_escape_string($conn, $selected_officer['location']);
                $query = "SELECT c.client_id, c.firstname, c.lastname, c.phone,
                                 c.b_location AS location,
                         cwl.loan_no, cwl.pay_date, cwl.amount_given,
                         cwl.daily_p, cwl.debt,
                                 COALESCE(SUM(lp.amount_paid), 0) AS total_paid
                          FROM clients c
                          JOIN clients_with_loan cwl ON cwl.clientsid=c.client_id
                              AND cwl.userseid='$user_id' AND cwl.bosseseid='$boss_id'
                              AND cwl.debt > 0
                          LEFT JOIN loan_pay lp ON lp.clients_id=cwl.clientsid
                              AND lp.loanNo=cwl.loan_no
                              AND lp.userse_id='$user_id'
                              AND lp.bossese_id='$boss_id'
                              AND lp.p_date <= '$report_date'
                          WHERE c.users_id='$user_id'
                            AND c.bosses_id='$boss_id'
                            AND c.b_location='$location'
                          GROUP BY c.client_id, c.firstname, c.lastname, c.phone, c.b_location,
                                   cwl.loan_no, cwl.pay_date, cwl.amount_given,
                                   cwl.daily_p, cwl.debt
                          HAVING total_paid > (DATEDIFF('$report_date', cwl.pay_date) * cwl.daily_p)
                             AND DATEDIFF('$report_date', cwl.pay_date) BETWEEN 0 AND 30
                          ORDER BY c.firstname, c.lastname";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
}

$escape = function ($value) use ($conn) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
};
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
    <tr>
    <td><b>Clients with Advance</b></td>
    <td><font color="#E4E4E4">---------- ---</font></td>
    <td>
    <form method="post">
    <div class="select is-success">
    <select name="officer_id" style="width:210px; border: 1px solid #006F37; height:35px" required>
    <option value="">Field Officer:</option>
    <?php
    $officers = mysqli_query($conn, "SELECT officer_id, firstname, lastname FROM officers WHERE boss_id='$boss_id' AND active=1 AND branch='$bra' ORDER BY firstname, lastname");
    while ($officer = mysqli_fetch_assoc($officers)) {
        $officer_name = strtoupper($officer['firstname'].' '.$officer['lastname']);
        $is_selected = $selected_officer_id === (int)$officer['officer_id'] ? ' selected' : '';
        echo '<option value="'.$escape($officer['officer_id']).'"'.$is_selected.'>'.$escape($officer_name).'</option>';
    }
    ?>
    </select>
    </div>
    <button type="submit" name="advance_report" class="button is-primary" style="background-color:#006F37; color:white; font-size:12px; border:1px solid #006F37; height:35px; margin-left:4px">&nbsp;OK&nbsp;</button>
    </form>
    </td>
    </tr>
    </table>
</div>
<br>

<div id="main_container">
<div id="main_body" style="width: 1200px;">
<br>
<?php
if (isset($_POST['advance_report'])) {
    if (!$selected_officer) {
        echo '<font color="red">Please select a valid field officer.</font>';
    } else {
        $officer_name = strtoupper($selected_officer['firstname'].' '.$selected_officer['lastname']);
        echo '<p align="center"><font size="4"><b>'. $escape(strtoupper($bra).' BRANCH '.$officer_name) .'</b><br>Clients with advance as at <b>'. $escape(date('d-m-Y', strtotime($report_date))) .'</b></font></p>';
        echo '<table width="100%" border="1" style="font-size:13px"><thead><tr>
            <th width="4%">No</th><th width="16%">Names</th><th width="10%">Phone</th>
            <th width="10%">Location</th><th width="9%">Loan No</th><th width="10%">Loan Given</th>
            <th width="10%">Date Given</th><th width="10%">Daily Pay</th><th width="10%">Total Paid</th>
            <th width="10%">Advance Amount</th><th width="8%">Balance</th><th width="8%">Loan Days</th>
        </tr></thead><tbody>';

        $number = 0;
        foreach ($rows as $row) {
            $expected_paid = max(0, (int)((strtotime($report_date) - strtotime($row['pay_date'])) / 86400)) * (int)$row['daily_p'];
            $advance_amount = max(0, (int)$row['total_paid'] - $expected_paid);
            $days_missed = max(0, (int)((strtotime($report_date) - strtotime($row['pay_date'])) / 86400));
            $number++;
            echo '<tr style="font-size:12px">
                <td>'.$number.'</td>
                <td>'.$escape(strtoupper($row['firstname'].' '.$row['lastname'])).'</td>
                <td>'.$escape($row['phone']).'</td>
                <td>'.$escape($row['location']).'</td>
                <td>'.$escape($row['loan_no']).'</td>
                <td>'.number_format((int)$row['amount_given']).'</td>
                <td>'.$escape($row['pay_date']).'</td>
                <td>'.number_format((int)$row['daily_p']).'</td>
                <td>'.number_format((int)$row['total_paid']).'</td>
                <td>'.number_format($advance_amount).'</td>
                <td>'.number_format((int)$row['debt']).'</td>
                <td>'.$days_missed.'</td>
            </tr>';
        }

        if ($number === 0) {
            echo '<tr><td colspan="12" style="text-align:center">No clients with advance found for this officer.</td></tr>';
        }
        echo '</tbody></table><br><font size="4"><b>Total Number of Clients with Advance is '.$number.'</b></font>';
    }
} else {
    echo '<font size="5">Select Field Officer</font><hr>';
}
?>
</div>
</div>
</div>
</main>
</body>
</html>

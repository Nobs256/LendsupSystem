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
<table width="100%">
<tr><td>
<b>DEFAULTERS REPORT</b>
</td>
<td>
<font color="#E4E4E4">---------- ---</font>
</td>
<td width="40%">
<form method="post">
<div class="select is-success">
<select name="officer_id" style="width:220px; border: 1px solid #006F37; height:35px" required>
<option value="">Field Officer:</option>
<?php
$officers = mysqli_query($conn, "SELECT officer_id, firstname, lastname FROM officers WHERE boss_id='$boss_id' AND active=1 AND branch='$bra' ORDER BY firstname, lastname");
while ($officer = mysqli_fetch_assoc($officers)) {
    $officer_name = strtoupper($officer['firstname'] . " " . $officer['lastname']);
    echo "<option value=\"" . (int) $officer['officer_id'] . "\">$officer_name</option>";
}
?>
</select>
</div>
<button type="submit" name="show_defaulters" class="button is-primary"
style="background-color:#006F37; color:white; font-size:12px; border:1px solid #006F37; height:35px; margin-left:4px">
&nbsp;OK&nbsp;</button>
</form>
</td></tr>
</table>
</div>

<div id="main_container">
<div id="main_body" style="width:1100px;">
<br>
<?php
if (isset($_POST['show_defaulters'])) {
    $officer_id = (int) $_POST['officer_id'];
    $officer_result = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT firstname, lastname, location
        FROM officers
        WHERE officer_id='$officer_id' AND boss_id='$boss_id' AND branch='$bra' AND active=1
    "));

    if ($officer_result) {
        $officer_name = strtoupper($officer_result['firstname'] . " " . $officer_result['lastname']);
        $location = $officer_result['location'];

        echo "<p align=center><font size=4><b>Defaulters For $officer_name ($location)</b></font></p>";
        echo "<table align=center width=95% border=1 style='font-size:14px; border:1px solid #aaacab;'>
        <thead>
        <tr style='font-size:14px; color:black; background-color:white; font-weight:bold;'>
        <th>No</th>
        <th>Names</th>
        <th>Date Given</th>
        <th>Amount Given</th>
        <th>Amount Paid</th>
        <th>Last Payment Date</th>
        <th>Balance</th>
        </tr>
        </thead><tbody>";
        
        // Exact same query structure as the NPL logic from daily_report.php
        $defaulters = mysqli_query($conn, "
            SELECT
                c.firstname,
                c.lastname,
                cwl.pay_date AS date_given,
                cwl.amount_given,
                COALESCE(payments.amount_paid, 0) AS amount_paid,
                payments.last_payment_date,
                cwl.debt AS balance
            FROM clients_with_loan cwl
            JOIN clients c ON c.client_id = cwl.clientsid
            LEFT JOIN (
                SELECT
                    clients_id,
                    loanNo,
                    SUM(amount_paid) AS amount_paid,
                    MAX(p_date) AS last_payment_date
                FROM loan_pay
                WHERE userse_id='$user_id' AND bossese_id='$boss_id' AND p_date <= CURDATE()
                GROUP BY clients_id, loanNo
            ) payments
                ON payments.clients_id = cwl.clientsid
                AND payments.loanNo = cwl.loan_no
            WHERE cwl.userseid='$user_id'
                AND cwl.bosseseid='$boss_id'
                AND cwl.debt > 0
                AND c.b_location='$location'
                AND DATEDIFF(CURDATE(), cwl.pay_date) >= 365
            ORDER BY cwl.pay_date, c.firstname, c.lastname
        ");

        $number = 0;
        while ($defaulter = mysqli_fetch_assoc($defaulters)) {
            $number++;
            $name = strtoupper($defaulter['firstname'] . " " . $defaulter['lastname']);
            $last_payment = $defaulter['last_payment_date']
                ? date('d-m-Y', strtotime($defaulter['last_payment_date']))
                : 'Never';

            echo "<tr style='font-size:12px'>
            <td>$number</td>
            <td>$name</td>
            <td>" . date('d-m-Y', strtotime($defaulter['date_given'])) . "</td>
            <td>" . number_format($defaulter['amount_given']) . "</td>
            <td>" . number_format($defaulter['amount_paid']) . "</td>
            <td>$last_payment</td>
            <td>" . number_format($defaulter['balance']) . "</td>
            </tr>";
        }

        if ($number === 0) {
            echo "<tr><td colspan='7' style='text-align:center;'>No defaulters found for this field officer.</td></tr>";
        }

        echo "</tbody></table>";
    }
}
?>
</div>
</div>
</main>
</body>
</html>
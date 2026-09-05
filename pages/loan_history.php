<?php
include('header_user.php');
?>
<style type="text/css">
table {
border-collapse: collapse;
border-spacing: 0;
 
border: 0px solid black;
font-size: 15px;
 
}

th, td {
text-align: left;
padding: 2px;
padding-top: 2px;
}

 
</style>
<main class="column main" style="background-color:#EAEAEA;"> 
<?php include ('summary.php');
if(isset($_GET['client_id'])){
$client_id = $_GET['client_id'];

$result = mysqli_fetch_assoc(mysqli_query($conn,"select ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
from clients where client_id='$client_id'"));     
$name= $result['firstname']." ".$result['lastname'];
$phone = $result['phone'];
echo"<br><br>
<div id='main_heading'> 
<table><tr><td><b>CLIENT'S BOOK FOR:</b> $name</td><td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Print Mini-Statement</td><td>  <p align=right>  
<form method='post' action='mini_statement.php' target='_blank'>
<input type=hidden name='client_id' value='$client_id'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>

<input type=date name=date1 required>
<input type=date name=date2 required>
<button type='submit' name=receipt style='padding-top:1px; border: 1px solid #006F37; border-radius:4px; color:#006F37'>
&nbsp;OK &nbsp;</button></form>
</p></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href=view_clients.php><font color=green><b>Search Another Client</b></font></a>
</td></tr></table>
</div>
<br> 
<div id='main_container' style='height:420px'> 
<div id='main_body' style='height:400px'> 
  
<table border=1 width=80%>
<tr>
<td width=20%><b>Loan No</b></td>
<td width=20%><b>Date Given</b></td>
<td width=20%><b>Amount Given</b></td>
<td>

<table border=0 width=90%>
<tr>
<td width=35%><b>Date</b></td>
<td width=30%><b>Amount Paid</b></td>
<td><b>Balance</b></td>
</tr></table>
</td>
</tr>";

    // --- OPTIMIZATION: BATCH DATA FETCHING ---
    // Reduce hundreds of queries to just a few global ones for this client.

    // 1. Fetch all payments indexed by [loanNo][p_date][mom]
    $payments_map = [];
    $pay_query = mysqli_query($conn, "SELECT * FROM loan_pay WHERE clients_id='$client_id' AND userse_id='$user_id' AND bossese_id='$boss_id' ORDER BY p_date ASC");
    while ($p_row = mysqli_fetch_assoc($pay_query)) {
        $payments_map[$p_row['loanNo']][$p_row['p_date']][$p_row['mom']] = $p_row;
    }

    // 2. Fetch all fines indexed by [loan_id]
    $fines_map = [];
    $fines_query = mysqli_query($conn, "SELECT * FROM loan_fines WHERE clients_id='$client_id' AND user_id='$user_id' AND boss_id='$boss_id' ORDER BY pay_date ASC");
    while ($f_row = mysqli_fetch_assoc($fines_query)) {
        $fines_map[$f_row['loan_id']][] = $f_row;
    }

    // 3. Fetch current total debt once
    $debt_res = mysqli_fetch_assoc(mysqli_query($conn, "SELECT loan_no, debt FROM clients_with_loan WHERE clientsid='$client_id' AND userseid='$user_id' AND bosseseid='$boss_id'"));
    $active_loan_no = $debt_res ? $debt_res['loan_no'] : null;
    $current_total_debt = $debt_res ? $debt_res['debt'] : 0;

    // 4. Fetch all loan summaries
    $loans_query = mysqli_query($conn, "SELECT * FROM completed_loan WHERE clientcpid='$client_id' AND userscpid='$user_id' AND bosscpid='$boss_id' ORDER BY loans_no ASC");

    while ($loan = mysqli_fetch_assoc($loans_query)) {
        $i = $loan['loans_no'];
        $amount_given = number_format($loan['amount_given']);
        $date_given = date("d-m-Y", strtotime($loan['pay_date']));

        echo "<tr>
        <td><font size=2>$i</font></td>
        <td><font size=2>$date_given</font></td>
        <td><font size=2>$amount_given</font></td>
        <td>
        <table border=1 width=80%>";

        // Process payments from map instead of database queries in loop (range: first payment to last)
        $loan_p_map = $payments_map[$i] ?? [];
        if (!empty($loan_p_map)) {
            $dates_array = array_keys($loan_p_map);
            sort($dates_array);
            $date = $dates_array[0];
            $last_date = end($dates_array);

            while (true) {
                $cash_rec = $loan_p_map[$date][0] ?? null;
                $amount_cash = $cash_rec ? $cash_rec["amount_paid"] : 0;
                $balance1 = $cash_rec ? $cash_rec["balance"] : 0;

                $mom_rec = $loan_p_map[$date][1] ?? null;
                $amount_mom = $mom_rec ? $mom_rec["amount_paid"] : 0;
                $balance2 = $mom_rec ? $mom_rec["balance"] : 0;

                
                if ($balance2 < $balance1 && $balance2 > 0) { $balance = $balance2; }
                else if ($balance1 < $balance2 && $balance1 > 0) { $balance = $balance1; }
                else if ($balance1 == 0) { $balance = $balance2; }
                else { $balance = $balance1; }

                $d_disp = date("d-m-Y", strtotime($date));
                $total_p = $amount_mom + $amount_cash;

                if (isset($loan_p_map[$date])) {
                    echo "
                    <tr>
                    <td width=30%><font size=2> $d_disp</font></td>             
                    <td width=40%><font size=2>".number_format($total_p)."</font></td>
                    <td><font size=2>".number_format($balance)."</font></td>
                    </tr>";
                } else {
                    // Unpaid days should be showing 0 on both paid and balance as requested
                    echo "
                    <tr>
                    <td width=30%><font size=2> $d_disp</font></td>             
                    <td width=40%><font size=2>0</font></td>
                    <td><font size=2>0</font></td>
                    </tr>";
                }

                if ($date == $last_date) break;
                $date = date("Y-m-d", strtotime("$date +1 day"));
            }
        }

        // Process Fines / Renewals dynamically after daily payments
        if (isset($fines_map[$i])) {
            foreach ($fines_map[$i] as $fine) {
                $dd = date("d-m-Y", strtotime($fine['pay_date']));

                // Dynamically set label based on fine_type, fallback to 'Fine' if empty
                $label = !empty($fine['fine_type']) ? ucfirst($fine['fine_type']) : 'Fine';

                echo "<tr>
                <td width=30%><font size=2><b> $dd</b></font></td>              
                <td width=30%><font size=2><b> {$label}: ".number_format($fine['amount'])." </b></font></td>
                <td><font size=2><b></b></font></td>
                </tr>";
            }
        }

        // Show Current Debt if applicable (placed down as requested)
        if ($active_loan_no !== null && $i == $active_loan_no) {
            echo "<tr>
            <td width=30%><font size=2><b> </b></font></td>             
            <td width=30%><font size=2><b> Current Debt:</b></font></td>
            <td><font size=2><b>".number_format($current_total_debt)."</b></font></td>
            </tr>";
        }
        echo "</table></td></tr>";
    }
    echo "</tbody></table>";
}
?>
 
</div>
</div>
 
<?php include('footer.php'); ?>
</div>
</main>
</script>
</body> 
</html>
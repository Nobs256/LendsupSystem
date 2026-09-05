<?php
$total_amount_mom = 0;
$total_amount = 0;
$total_balance = 0;
$total_op = 0;
$total_de = 0;
$total_exp = 0;
$amount_ch = 0;
$total_ukno = 0;
$excess = 0;
$shortage = 0;
$total_paid_loan = 0;
$total_given_loan = 0;
$total_reg_fee = 0;
$tci = 0;
$cs = 0;
$total_op_mom = 0;
$d = date('Y-m-d');

// 1. Combined Loan Payments (Cash vs MOM) in ONE query
$q_loan_pay = mysqli_query($conn, "SELECT mom, SUM(amount_paid) AS sum_amount FROM loan_pay WHERE p_date='$d' AND userse_id='$user_id' AND bossese_id='$boss_id' GROUP BY mom");
$total_amount = 0;
$total_amount_m = 0;
while ($row = mysqli_fetch_assoc($q_loan_pay)) {
    if ($row['mom'] == 0) {
        $total_amount = (float)$row['sum_amount'];
    } elseif ($row['mom'] == 1) {
        $total_amount_m = (float)$row['sum_amount'];
    }
}

// 2. Total OP (Opening Balance Cash)
$q_op = mysqli_query($conn, "SELECT SUM(op_amount) AS sum_op FROM op WHERE op_date='$d' AND userop_id='$user_id' AND bossop_id='$boss_id'");
$total_op = (float)(mysqli_fetch_assoc($q_op)['sum_op'] ?? 0);

// 3. Total OP MOM (Opening Balance Mobile Money)
$q_op_mom = mysqli_query($conn, "SELECT SUM(op_amount) AS sum_op FROM op_mom WHERE op_date='$d' AND userop_id='$user_id' AND bossop_id='$boss_id'");
$total_op_mom = (float)(mysqli_fetch_assoc($q_op_mom)['sum_op'] ?? 0);

// 4. Cr from another branch
$q_cr = mysqli_query($conn, "SELECT SUM(cr_amount) AS sum_cr FROM cr WHERE cr_date='$d' AND usercr_id='$user_id' AND bosscr_id='$boss_id'");
$cash_from_branch = (float)(mysqli_fetch_assoc($q_cr)['sum_cr'] ?? 0);

// 5. Money sent to another branch
$q_sent = mysqli_query($conn, "SELECT SUM(cr_amount) AS sum_sent FROM sent_to_branch WHERE cr_date='$d' AND usercr_id='$user_id' AND bosscr_id='$boss_id'");
$cash_to_branch = (float)(mysqli_fetch_assoc($q_sent)['sum_sent'] ?? 0);

// 6. Combined Banking Transactions (Deposit vs Withdraw) in ONE query
$q_banking = mysqli_query($conn, "SELECT transc, SUM(de_amount) AS sum_de FROM banking WHERE de_date='$d' AND userde_id='$user_id' AND bossde_id='$boss_id' GROUP BY transc");
$total_bank_deposit = 0;
$total_bank_withdraw = 0;
while ($row = mysqli_fetch_assoc($q_banking)) {
    if ($row['transc'] === 'Deposit') {
        $total_bank_deposit = (float)$row['sum_de'];
    } elseif ($row['transc'] === 'Withdraw') {
        $total_bank_withdraw = (float)$row['sum_de'];
    }
}

// 7. Total Expenses for Today
$q_expenses = mysqli_query($conn, "SELECT SUM(cost) AS sum_cost FROM expenses WHERE exp_date='$d' AND userexp_id='$user_id' AND bossexp_id='$boss_id'");
$total_exp = (float)(mysqli_fetch_assoc($q_expenses)['sum_cost'] ?? 0);

// 8. Loans Given & Reg Fees Combined in ONE query
$q_loans = mysqli_query($conn, "SELECT COUNT(*) AS no_loans, SUM(amount_given) AS total_given, SUM(reg_fee) AS total_reg FROM loans WHERE b_date='$d' AND userse_id='$user_id' AND bossese_id='$boss_id'");
$res_loans = mysqli_fetch_assoc($q_loans);
$no_loans = (int)$res_loans['no_loans'];
$total_given_loan = (float)$res_loans['total_given'];
$total_reg_fee = (float)$res_loans['total_reg'];

// 9. Unknown Payments
$q_ukno = mysqli_query($conn, "SELECT SUM(paid_amount) AS sum_paid FROM uknown WHERE rec_date='$d' AND userrec_id='$user_id' AND bossrec_id='$boss_id' AND known=0");
$total_ukno = (float)(mysqli_fetch_assoc($q_ukno)['sum_paid'] ?? 0);

// 10. Excess Payments
$q_excess = mysqli_query($conn, "SELECT SUM(paid_amount) AS sum_paid FROM excess_short WHERE rec_date='$d' AND userrec_id='$user_id' AND bossrec_id='$boss_id' AND excess_short='Excess'");
$total_excess = (float)(mysqli_fetch_assoc($q_excess)['sum_paid'] ?? 0);

// 11. Combined Withdrawals (MOM, Shortage, Unknown, Excess, Defaulters) in ONE query
$q_withdraws = mysqli_query($conn, "SELECT withdraws, SUM(amount) AS sum_amount FROM withdraws WHERE with_date='$d' AND user_id='$user_id' AND boss_id='$boss_id' GROUP BY withdraws");
$total_mom_withdraws = 0;
$recovered_shortage = 0;
$recovered_unknown = 0;
$recovered_excess = 0;
$withdraw_defaulters = 0;
while ($row = mysqli_fetch_assoc($q_withdraws)) {
    switch ($row['withdraws']) {
        case 'MOM':
            $total_mom_withdraws = (float)$row['sum_amount'];
            break;
        case 'Shortage':
            $recovered_shortage = (float)$row['sum_amount'];
            break;
        case 'Unknown':
            $recovered_unknown = (float)$row['sum_amount'];
            break;
        case 'Excess':
            $recovered_excess = (float)$row['sum_amount'];
            break;
        case 'Defaulters':
            $withdraw_defaulters = (float)$row['sum_amount'];
            break;
    }
}

// 12. Cash Payments
$q_cash = mysqli_query($conn, "SELECT SUM(amount) AS sum_amount FROM cash WHERE pay_date='$d' AND user_id='$user_id' AND boss_id='$boss_id'");
$cassh = (float)(mysqli_fetch_assoc($q_cash)['sum_amount'] ?? 0);

$total_amount_mom = ($total_amount_m + $total_ukno) - $recovered_unknown;

// 13. Trash
$q_trash = mysqli_query($conn, "SELECT SUM(amount) AS sum_amount FROM trash WHERE pay_date='$d' AND user_id='$user_id' AND boss_id='$boss_id'");
$trash = (float)(mysqli_fetch_assoc($q_trash)['sum_amount'] ?? 0);

// 14. Mom Balance
$q_mom_bal = mysqli_query($conn, "SELECT SUM(amount) AS sum_amount FROM mom_balance WHERE ch_date='$d' AND userch_id='$user_id' AND bossch_id='$boss_id'");
$mom_balance = (float)(mysqli_fetch_assoc($q_mom_bal)['sum_amount'] ?? 0);

// 15. Loan Recovery
$q_recovery = mysqli_query($conn, "SELECT SUM(amount) AS sum_amount FROM loan_recovery WHERE recovery_date='$d' AND user_id='$user_id' AND boss_id='$boss_id'");
$loan_recovery = (float)(mysqli_fetch_assoc($q_recovery)['sum_amount'] ?? 0);

// 16. Completed Loans (Using COUNT(*) instead of fetching rows)
$q_completed = mysqli_query($conn, "SELECT COUNT(*) AS total FROM completed_loan WHERE e_date='$d' AND completed=1 AND userscpid='$user_id' AND bosscpid='$boss_id'");
$completed = (int)(mysqli_fetch_assoc($q_completed)['total'] ?? 0);

// 17. Clients Paid
$q_clients_paid = mysqli_query($conn, "SELECT COUNT(*) AS total FROM loan_pay lp INNER JOIN clients c ON lp.clients_id = c.client_id WHERE lp.userse_id='$user_id' AND lp.bossese_id='$boss_id' AND lp.p_date='$d'");
$clients_paid = (int)(mysqli_fetch_assoc($q_clients_paid)['total'] ?? 0);

// 18. New Clients (Optimized to skip the loop and fetch only the last client's values directly)
$q_last_client = mysqli_query($conn, "SELECT client_id FROM clients WHERE users_id='$user_id' AND bosses_id='$boss_id' ORDER BY client_id DESC LIMIT 1");
if ($row_last_client = mysqli_fetch_assoc($q_last_client)) {
    $client_new_id = $row_last_client['client_id'];
    
    $hup = mysqli_query($conn, "SELECT b_date FROM loans WHERE b_date='$d' AND cliente_id='$client_new_id' AND userse_id='$user_id' AND bossese_id='$boss_id' LIMIT 1");
    $now = mysqli_fetch_assoc($hup);
    $b_date = $now["b_date"] ?? null;
    
    $q_count_loans = mysqli_query($conn, "SELECT COUNT(*) AS total_loans FROM loans WHERE cliente_id='$client_new_id' AND userse_id='$user_id' AND bossese_id='$boss_id'");
    $res_count_loans = mysqli_fetch_assoc($q_count_loans);
    $no_of_loans = $res_count_loans['total_loans'];
} else {
    $client_new_id = null;
    $b_date = null;
    $no_of_loans = 0;
}
$new_clients = 0;

// 19. Calculations & Closing Balances (Perfectly identical to your original formulas)
$cs_mom = ($total_op_mom + $total_amount_mom) - $total_mom_withdraws;

$trimed = trim(substr($cs_mom, 5, 2));
if($trimed > 0){
    $cs_mom = $cs_mom - $trimed;
}

$totalcoll = 0;
$total_amount_cash = $total_amount - $recovered_excess;

$tci = $total_op + $total_amount_cash + $total_mom_withdraws + $total_reg_fee +
       $recovered_shortage + $total_excess + $total_bank_withdraw + $cash_from_branch + $mom_balance + $loan_recovery;

$tco = $trash + $total_given_loan + $total_exp + $withdraw_defaulters + $cash_to_branch + $total_bank_deposit;

$totalcoll = $total_amount_cash + $total_amount_mom; 

$closing = $tci - $tco;

$trimed = trim(substr($closing, 5, 2));
if($trimed > 0){
    $closing = $closing - $trimed;
}
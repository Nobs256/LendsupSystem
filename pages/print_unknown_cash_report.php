<?php 
session_start();
ob_start();

ini_set('memory_limit', '1969994M'); // or you could use 1G
ini_set('max_execution_time', 3600); //300 seconds = 5 minutes
include("conn.php");

if(isset($_POST['print_unknown'])){    
    $officer_filter = $_POST['officer_id']; 
    $branch = $_POST['branch']; 
    $user_id = $_POST['user_id']; 
    $boss_id = $_POST['boss_id']; 
    $from_date = date("Y-m-d", strtotime($_POST['from_date'])); 
    $to_date   = date("Y-m-d", strtotime($_POST['to_date']));
    $f=date("d-m-Y", strtotime($from_date));
    $t=date("d-m-Y", strtotime($to_date));

    // Officer names lookup for this branch
    $officer_names = array();
    $comb = mysqli_query($conn,"SELECT * from officers where boss_id='$boss_id' and branch='$branch'");
    while($select_comb = mysqli_fetch_array($comb)){
        $officer_names[$select_comb['officer_id']] = strtoupper($select_comb['firstname']." ".$select_comb['lastname']);
    }

    if($officer_filter != ""){
        $name = isset($officer_names[$officer_filter]) ? $officer_names[$officer_filter] : "OFFICER #".$officer_filter;
        $officer_cond = " AND officer='$officer_filter'";
    }else{
        $name = "ALL FIELD OFFICERS";
        $officer_cond = "";
    }

    $result = mysqli_fetch_assoc(mysqli_query($conn,"select boss_id, ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
    from bosses where boss_id='$boss_id'"));     
    $company= strtoupper($result['firstname']);

    echo" 
    <p align=center> <b>$company LTD ".strtoupper($branch)." BRANCH<br>".strtoupper($name)."
      UNKNOWN CASH FROM <u> $f </u> TO <u> $t </u></b> 
     </font>  <hr>
     ";

    // ============ Detailed transactions in the range ============
    echo "<br><font size=4><b>Unknown Cash Transactions Details</b></font><br><br>";

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

    echo "<table border=1 width=100% style='font-size:8.5px; border-collapse: collapse;'>
    <thead>
    <tr style='white-space: nowrap;'>
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
        if($ttype == 'Withdrawn'){
            $total_detail_out += $row['amount'];
            $amt_disp = "-".number_format($row['amount']);
        } else {
            $total_detail_in += $row['amount'];
            $amt_disp = number_format($row['amount']);
        }

        echo "<tr style='white-space: nowrap;'>
            <td>$j</td>
            <td>" . date("d-m-Y", strtotime($row['tdate'])) . "</td>
            <td>$officer_name</td>
            <td>$ttype</td>
            <td>$amt_disp</td>
        </tr>";
    }
    echo "</tbody>
    <tfoot>
    <tr style='white-space: nowrap; font-weight:bold;'>
        <td colspan=4>TOTAL BALANCE</td>
        <td>" . number_format($total_detail_in - $total_detail_out) . "</td>
    </tr>
    </tfoot>
    </table>";

    echo "<br><font size=4><b>Total Number of Transactions: $j</b></font><br>";
    mysqli_close($conn);
}

echo"
<div>"; 

include("mpdf60/mpdf.php");
$mpdf=new mPDF('P','A3'); 
$mpdf = new mPDF(); 
$stylesheet = file_get_contents('mpdf60/pdf.css'); 
$mpdf->WriteHTML($stylesheet,1);
$mpdf->SetDisplayMode('fullpage');
//$mpdf->AddPage("L");

// LOAD a stylesheet
$stylesheet = file_get_contents('mpdf60/mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text
$report_content=ob_get_contents();
ob_clean();

$mpdf->WriteHTML($report_content,2);
$mpdf->Output('UNKNOWN_CASH_REPORT.pdf','I');
exit;
?>
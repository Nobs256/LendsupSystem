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
<p align="center"> <?php include ('summary.php') ?></p>
 <br><br>
<div id="main_heading"> <table><tr><td><b>Print Saving Reciepts</b> </td><td>
	<font color="#EAEAEA">--------------------------------------</font></td> 
</tr></table>
 
</div>     
 </form>
<div id="main_container" style="height:430px">
<div id="main_body" style="height:400px">   
    
<br>  
<?php
$j=0;

$search_query= mysqli_query($conn,"SELECT client_id, ucase(firstname) as firstname, ucase(lastname) as lastname, phone, loan_norec_id, rec_date, paid_amount, balancerec
FROM clients,  receipts  where users_id='$user_id' and bosses_id='$boss_id' and client_id=clientrec_id and rec_date>='$rec_date'  and loan_norec_id=0 order by firstname");
  
echo "<table width=80% border=1>
<thead>
<tr>
<th>No</th> 
<th>Names</th>
<th>Phone</th>
<th>Date Paid</th>
<th>Amount Paid</th>
<th>Balance</th>
<th> </th>

</tr>
</thead>
<tbody>";

while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;

$client_id=$returned_result["client_id"];
$firstname = $returned_result["firstname"]. " ". $returned_result["lastname"];
$phone  = $returned_result["phone"];
$loan_no = $returned_result["loan_norec_id"];
$date = $returned_result["rec_date"];
$d=date("d-m-Y", strtotime($date));
$amount  = number_format($returned_result["paid_amount"]);
$balance = number_format($returned_result["balancerec"]);

echo "<tr>
<td><font size=3> $j  </font></td>
<td><font size=3> $firstname  </font></td>
<td><font size=3> $phone</font></td>
<td><font size=3> $d</font></td>
<td><font size=3> $amount</font></td>
<td><font size=3> $balance</font></td>
<td><font size=3> 
<form method='post' action='saving_receipt.php' target='_blank'>
<input type=hidden name='client_id' value='$client_id'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='loan_no' value='$loan_no'>
<input type=hidden name='pay_date' value='$date'>
<button type='submit' name=receipt style='padding-top:1px; border: 1px solid white; 
color:#006F37; background-color:white; font-size:16px'>
&nbsp;Print Receipt &nbsp;</button>
</form>
</font></td>";
}

echo "</tr>";
echo "</tbody></table>";
mysqli_close($conn);
 
 echo"
<hr></hr>
";
echo"
</div>
</div>
 "; 

include('footer.php');  
?>
</div>
</main>
</body> 
</html>
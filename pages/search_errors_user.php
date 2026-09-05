<?php
include('header_user.php');

?>
<style type="text/css">
table {
border-collapse: collapse;
border-spacing: 0;
width: 100%;
border: 1px solid #D9FFD9 ;
}

th, td {
text-align: left;
padding: 4px;
padding-top: 4px;
}

tr:nth-child(even) {
background-color: #D9FFD9;
}
form {
border-collapse: collapse;
}

input, select {
width:340px; height:33px; font-size:12px; border: 2px solid green; border-radius: 4px;
}
</style>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> <?php include ('summary.php') ?>
<div id="main_heading">  
<table width="100%">
<tr>
<td><b>Data Entering Errors</b>  </td>


<td><a href="error_others.php?Expenses"> <font color="#006F37">Expenses Errors </font></a></td>
<td><a href="error_others.php?Banking"> <font color="#006F37">Banking Errors </font></a></td>
<td><a href="error_others.php?Unknown"> <font color="#006F37">Unknown Errors </font></a></td>
<td><a href="search_errors_loan_parts.php"> <font color="#006F37">Loan in Parts Errors </font></a></td>
</tr></table>
</div>

<div id="main_container">
<div id="main_body">   
<br>
  
<div class="table-responsive">
<table id="example" class="table table-striped table-bordered second" border="1" style="width:100%" align="center">            
 
<thead>
<tr>
<th width="5%">No</th>
<th width="13%">Name</th>
<th width="8%">Phone</th>
<th width="10%">Field</th>
<th width="10%"></th>
<th width="10%"></th>
<th width="13%"></th>

</tr></thead><tbody>

<?php
$search_query= mysqli_query($conn,"SELECT client_id, users_id, bosses_id, 
ucase(firstname) as firstname,ucase(lastname) as lastname,  phone, business, b_location
FROM clients where users_id='$user_id' and bosses_id='$boss_id' order by firstname");
$j=0;
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id=$returned_result["client_id"];
$name = $returned_result["firstname"]." ".$returned_result["lastname"]; 
$phone = $returned_result["phone"];                
$business = strtoupper($returned_result["business"]);
$b_location = strtoupper($returned_result["b_location"]);
$j++;

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from clients_with_loan where clientsid='$client_id'"));     
$debt= $result['debt'];

if($debt>0){

echo "<tr>
<td><font size=2> $client_id </font></td>
<td><font size=2> $name </font></td>
<td><font size=2> $phone </font></td>                   
<td><font size=2> $b_location</font></td>                  
 
<td> <a href='error_give_loan_user.php?client_id=$client_id'><font color=green><b>Give Loan</a></b></font></td>
<td> <a href='error_pay_loan_user.php?client_id=$client_id'><font color=green><b>Payment Errors</a></font></td>
<td><a href='error_mom_user.php?client_id=$client_id'><font color=green><b>MOM Errors</a></td>";

echo "</tr>";
}
}
echo "</tbody></table>";
mysqli_close($conn);
 
?>

</div>
 </div>
</div>
 
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>
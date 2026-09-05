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
<table border="0">
<tr><td>
<b>Clients Who Paid</b>
</td>
<td>
<font color="#E4E4E4">-------------------------------------------------------</font>
</td><td>
<form  method="post"> 
Select Date: 
<input type="date" name="p_date" style="width:190px; height:35px; border: 1px solid #006F37" required> 
<button type="submit" name="paid" class="button is-primary" 
style="background-color:#006F37; color:white; font-size:12px; 
border:1px solid #006F37; height:35px; margin-left:4px ">
&nbsp;OK&nbsp;</button></form> 
 
</td></tr></table></div>
     
 
<div id="main_container" style="height:420px">
<div id="main_body" style="height:390px">   
    
<br>  
<?php
$j=0;
$total_amount_paid=0;
$total_amount=0;
if(isset($_POST['paid'])){       
$date = $_POST['p_date']; 

$payed_date=date("Y-m-d", strtotime($date));
$d=date("d-m-Y", strtotime($date));

$select = mysqli_query($conn,"SELECT * FROM loan_pay where  p_date='$payed_date'
and userse_id='$user_id' and bossese_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_paid"]; 
$total_amount+=$amount;
}

//clients paid
$clients_paid = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM loan_pay, clients where 
userse_id='$user_id' and bossese_id='$boss_id' and p_date='$payed_date' and client_id=clients_id")); 


echo " <p align=center><font size=4><b>Number of Clients Paid on <b> $d </b>is <b>$clients_paid</b></font><br>
Total Amount Paid:<b>".number_format($total_amount)."<br>";
echo "<table width=100% border=1 style='font-size:14px'>
<thead>
<tr>
<th>No</th>
<th>Names</th>
<th>Phone</th>
<th>Loan Given</th>
<th>Date Given</th>
<th>Last Date Paid</th>
<th>Last Paid</th>
<th>Debt</th>
</tr>
</thead>
<tbody>";
$j=0;
$search_query= mysqli_query($conn,"SELECT client_id, ucase(firstname) as firstname, ucase(lastname) as lastname, phone, debt, loan_no, b_location 
FROM clients, clients_with_loan where users_id='$user_id' and bosses_id='$boss_id' and client_id=clientsid  order by firstname");
  
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id=$returned_result["client_id"];
$firstname = $returned_result["firstname"]. " ". $returned_result["lastname"];
$phone  = $returned_result["phone"];
$loc  = $returned_result["b_location"];
$loc  = $returned_result["b_location"];
$debt  = number_format($returned_result["debt"]);
$loanNo = $returned_result["loan_no"];
//last date
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay where clients_id='$client_id'
and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loanNo' order by p_date desc limit 1"));     
$last_date=date("d-m-Y", strtotime($result['p_date']));
$amount_paid2=number_format($result['amount_paid']);
if($last_date=='01-01-1970'){
$last_date="New Loan";
}
//Loan Given Date
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM completed_loan where clientcpid='$client_id'
and userscpid='$user_id' and bosscpid='$boss_id' and loans_no='$loanNo' and completed=0"));     
$given_date=date("d-m-Y", strtotime($result['pay_date']));
$loan_given=number_format($result['amount_given']);

$clients_paid ="select* from loan_pay where clients_id='$client_id' and userse_id='$user_id' and  bossese_id='$boss_id' and p_date='$payed_date'";
$run = mysqli_query($conn, $clients_paid) or die("Could DB");
$clients_paid = mysqli_num_rows($run);
if($clients_paid==1){
$j++;
echo "<tr style='font-size=12px'>
<td> $j </td>
<td> $firstname ($client_id)</td>
<td> $phone</td>
<td> $loan_given</td>
<td> $given_date</td>
<td> $last_date</td>
<td> $amount_paid2</td>
<td> $debt</td>";
$amount_paid2=0;
}

}
echo "</tr>";
echo "</tbody></table>";
mysqli_close($conn);
 }
 else{
 echo" Select Branch, Field Officer and Date
<hr></hr>
";
}
echo"
</div>

 
</div>";
include('footer.php');
?>
 </div>
</main>
</body> 
</html>
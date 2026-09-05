<?php
include('header.php');
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
<p align="center"> <?php include ('summary_admin.php') ?>
 
<div id="main_heading"> 
<table border="0">
<tr><td>
<b>Clients who Have Not Paid</b>
</td>
<td>
<font color="#E4E4E4">---------- ---</font>
</td><td>
<form  method="post"> 
Select Date: 
<input type="date" name="p_date" style="width:190px; height:35px; border: 1px solid #006F37" required> 

<div class="select is-success">  
<select  name="user_id"  style="width:180px; border: 1px solid #006F37; height:35px" required> 
<option>Branch</option>
<?php
$comb = mysqli_query($conn,"SELECT * from new_users where boss_id='$boss_id' and active=1 and category='User'");
while($select_comb = mysqli_fetch_array($comb)){
$branch=$select_comb['branch']; 
$user_id=$select_comb['user_id']; 
echo "<option value= $user_id> $branch </option>"; 
}
echo" 
</select></div>";
?>
<div class="select is-success">  
<select  name="fname"  style="width:180px; border: 1px solid #006F37; height:35px" required> 
<option>Field Officer:</option>
<?php
$comb = mysqli_query($conn,"SELECT * from officers where boss_id='$boss_id' and active=1");

while($select_comb = mysqli_fetch_array($comb)){
$officerid=$select_comb['officer_id'];
$officers_name=$select_comb['firstname']." ".$select_comb['lastname'];
 
echo "<option value= $officers_name>".strtoupper($officers_name)." </option>";
 
}
echo" 
</select></div>";
?>
<button type="submit" name="not_paid" class="button is-primary" 
style="background-color:#006F37; color:white; font-size:12px; border:1px solid #006F37; height:35px; margin-left:4px ">
&nbsp;OK&nbsp;</button></form> 
 
</td></tr></table></div>
<br>  
</div>     
 
<div id="main_container">
<div id="main_body">   
    
<br>  
<?php
if(isset($_POST['not_paid'])){       
$date = $_POST['p_date']; 
$user_id= $_POST['user_id'];
$off = $_POST['fname'];
$payed_date=date("Y-m-d", strtotime($date));
$d=date("d-m-Y", strtotime($date));

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from new_users where boss_id='$boss_id' and active=1 and category='User' and user_id='$user_id'"));
$branch=strtoupper($results["branch"]);

$returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and firstname='$off' and branch='$branch'"));     
$name = $returned_result["firstname"]. " ". $returned_result["lastname"];
$location  = $returned_result["location"];
$j=0;

$search_query= mysqli_query($conn,"SELECT client_id, ucase(firstname) as firstname, ucase(lastname) as lastname, phone, debt, b_location 
FROM clients, clients_with_loan where users_id='$user_id' and bosses_id='$boss_id' and client_id=clientsid and b_location='$location' order by firstname");
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id=$returned_result["client_id"];
 
$cliets_not_paid ="select* from loan_pay where clients_id='$client_id' and userse_id='$user_id' and  bossese_id='$boss_id' and p_date='$payed_date'";
$run = mysqli_query($conn, $cliets_not_paid) or die("Could DB");
$cliets_not_paid = mysqli_num_rows($run);

if($cliets_not_paid==0){
$j++;
}
}


echo " <p align=center><font size=4><b>$branch BRANCH ($location Field)</b><br>
 Number of Clients not Paid on <b> $d </b>is <b>$j</b></font><br><br>";

 
echo "<table width=90% border=1>
<thead>
<tr>
<th>No</th>
<th>Names</th>
<th>Phone</th>
<th>Location</th>
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
FROM clients, clients_with_loan where users_id='$user_id' and bosses_id='$boss_id' and client_id=clientsid and b_location='$location' order by firstname");
  
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

//Loan Given Date
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM completed_loan where clientcpid='$client_id'
and userscpid='$user_id' and bosscpid='$boss_id' and loans_no='$loanNo' and completed=0"));     
$given_date=date("d-m-Y", strtotime($result['pay_date']));
$loan_given=number_format($result['amount_given']);

$cliets_not_paid ="select* from loan_pay where clients_id='$client_id' and userse_id='$user_id' and  bossese_id='$boss_id' and p_date='$payed_date'";
$run = mysqli_query($conn, $cliets_not_paid) or die("Could DB");
$cliets_not_paid = mysqli_num_rows($run);
if($cliets_not_paid==0){
$j++;
echo "<tr>
<td><font size=3> $j </font></td>
<td><font size=3> $firstname  </font></td>
<td><font size=3> $phone</font></td>
<td><font size=3> $loc</font></td>
<td><font size=3> $loan_given</font></td>
<td><font size=3> $given_date</font></td>
<td><font size=3> $last_date</font></td>
<td><font size=3> $amount_paid2</font></td>
<td><font size=3> $debt</font></td>";
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
</div>
</div>
</div>
<div>"; 
  
?>
</div>
</main>
</body> 
</html>
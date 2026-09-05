<?php
$s="";
include('header_user.php');
?>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> <?php include ('summary.php') ?>
 
<div id="main_heading"> <b>Search a Client to be Renewed</b>
</div>     
 
<div id="main_container">
<div id="main_body">   
<br>
  <?php echo $s;?>        
  <br><br>                     
<table border="0" width="70%">
<form  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<table style="width:730px;" border="0">
<tr><td width="150px">
Enter First Name: 
</td><td width="400px">
<input type="text" name="firstname" class="input is-success" style="width:320px; border: 1px solid #006F37" required >
<button type="submit" name="submit" class="button is-primary" style="background-color:#006F37">
&nbsp;&nbsp;&nbsp;Search&nbsp;&nbsp;&nbsp;</button><div>
</form></td> </tr></table>    
<br>  
<?php
if(isset($_POST['firstname'])){
$q = $_POST['firstname'];
$j=0;
$select_client = mysqli_query($conn,"SELECT * FROM clients where firstname='$q'");
$search_query= mysqli_query($conn,"SELECT client_id, ucase(firstname) as firstname, ucase(lastname) as lastname, phone, nid, 
b_location FROM clients  where firstname Like '%$q%' and users_id='$user_id' and bosses_id='$boss_id'");
  
echo "<table  class='table table-responsive table-hover'>
<thead>
<tr>
<th>No</th>
<th>Names</th>
<th>Phone</th>
<th>Debt</th>
<th>Action</th>
</tr>
</thead>
<tbody>";

while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;
$allowed="";
$client_id=$returned_result["client_id"];
$firstname = $returned_result["firstname"]. " ". $returned_result["lastname"];
$phone  = $returned_result["phone"];
$nid  = $returned_result["nid"];
$b_location = $returned_result["b_location"];

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients_with_loan
where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'"));
$debt = number_format($results["debt"]);

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from loan_fines
where clients_id='$client_id' and user_id='$user_id' and boss_id='$boss_id'"));
$paid_amount = $results["amount"];

if($paid_amount>0){
 
$allowed="<a href='#'><font color=red>Already Renewed!!</font></a> - <a href='search_client_renew.php?client_id=$client_id'><font size=4 color=green><b>Select</b></font></a>";

}
else if($debt==0){
$allowed="<a href='#'><font color=red>Not allowed</font></a>";
}
else{
$allowed="<a href='search_client_renew.php?client_id=$client_id'><font size=4 color=green><b>Renew</b></font></a>";
  
 }
 
 
echo "<tr>
<td><font size=3> $j  </font></td>
<td><font size=3> $firstname  </font></td>
<td><font size=3> $phone</font></td>
<td><font size=3> $debt </font></td>
 
<td>$allowed</td>";
}

echo "</tr>";
}
echo "</tbody></table>";
mysqli_close($conn);
 
 echo"<hr></hr>";

if(isset($_GET['client_id'])){
$client_id = $_GET['client_id'];
include('conn.php');
$result = mysqli_fetch_assoc(mysqli_query($conn,"select ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
from clients where client_id='$client_id'"));     
$name= $result['firstname']." ".$result['lastname'];
$phone = $result['phone'];

$fine=0;
$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients_with_loan
where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'"));
$debt = $results["debt"];
$loan_no = $results["loan_no"];
$fine=0.2*$debt;
$new_debt=$debt + $fine;
$date=date('Y-m-d');

include('conn.php');
//select current balance
  
$fine_type="Renewed";

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay WHERE 
clients_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id' order by p_date Desc Limit 1"));     
$curr_balance_date= $result['p_date'];

mysqli_query($conn,"INSERT INTO loan_fines(ts_id,  user_id, boss_id, clients_id, loan_id, pay_date, amount, fine_type) 
VALUES (NULL,  '$user_id', '$boss_id', '$client_id', '$loan_no', '$date', '$fine', '$fine_type')");

mysqli_query($conn,"INSERT INTO clients_with_fines(ts_id,  user_id, boss_id, clients_id, loan_id, pay_date, amount) 
VALUES (NULL,  '$user_id', '$boss_id', '$client_id', '$loan_no', '$date', '$fine', '$fine_type')");

//===UPDATE the balance
$query ="UPDATE loan_pay set balance='$new_debt' where p_date='$curr_balance_date' and clients_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id'";
$execute = mysqli_query($conn, $query);

//===UPDATE the loans
$query ="UPDATE clients_with_loan set debt='$new_debt' where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'";
$execute = mysqli_query($conn, $query);


echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:5px; width: 630px'>$name's Loan is Successfully Renewed with $fine 
<a href='search_client_give_fine.php' style='color:white; margin-left:100px;''>X</a>
</div>";
}
?>

</div>
</div>
</div>
<?php 
include('footer.php');  
?>
</div>
</main>
</body> 
</html>
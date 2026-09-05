<?php
//get the client ID
if(isset($_GET['client_id']))
{   
$client_id = $_GET['client_id'];
}

include'header_user.php';

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients where client_id='$client_id' and bosses_id='$boss_id'"));
$names = $results['firstname']." ".$results['lastname'];

?>
<script type="text/javascript">
function isNumberKey(evt)
{var charCode=(evt.which)?evt.which:event.keyCode
if(!(charCode>=48&&charCode<=57||charCode==8||charCode==46))
return false;return true;}
 


function separator(num) {
var numb=document.getElementById("uamo2").value;

var numb=numb.replace(",","");
var strlen=numb.length;

var output;
if(strlen<=3){
output=numb;
}

else if(strlen==4){
output=numb[0]+","+numb.substring(1,4);
}

else if(strlen==5){
output=numb.substring(0,2)+","+numb.substring(2,5);
}

else if(strlen==6){
output=numb.substring(0,3)+","+numb.substring(3,6);
}

else if(strlen==7){
output=numb[0]+","+numb.substring(1,4)+","+numb.substring(4,7);
}

else if(strlen==8){
output=numb.substring(0,2)+","+numb.substring(2,5)+","+numb.substring(5,8);
}

else if(strlen==9){
output=numb.substring(0,3)+","+numb.substring(3,6)+","+numb.substring(6,9);
}

document.getElementById("uamo").value=output;
//document.getElementById("numo").innerHTML=output;
}


function validateMe(str)
{
var number=str.replace(",","");
if(number.length<=7){
document.getElementById("uamo2").value=number;
}

else{
num=document.getElementById("uamo").value;
var number=num.replace(",","");
document.getElementById("uamo2").value=number;
}

var feed=separator(1);
}

function separator2(num) {
var numb=document.getElementById("balance2").value;

var numb=numb.replace(",","");
var strlen=numb.length;

var output;
if(strlen<=3){
output=numb;
}

else if(strlen==4){
output=numb[0]+","+numb.substring(1,4);
}

else if(strlen==5){
output=numb.substring(0,2)+","+numb.substring(2,5);
}

else if(strlen==6){
output=numb.substring(0,3)+","+numb.substring(3,6);
}

else if(strlen==7){
output=numb[0]+","+numb.substring(1,4)+","+numb.substring(4,7);
}

else if(strlen==8){
output=numb.substring(0,2)+","+numb.substring(2,5)+","+numb.substring(5,8);
}

else if(strlen==9){
output=numb.substring(0,3)+","+numb.substring(3,6)+","+numb.substring(6,9);
}

document.getElementById("balance").value=output;
//document.getElementById("numo").innerHTML=output;
}


function validateMe2(str)
{
var number=str.replace(",","");
if(number.length<=7){
document.getElementById("balance2").value=number;
}

else{
num=document.getElementById("balance").value;
var number=num.replace(",","");
document.getElementById("balance2").value=number;
}

var feed=separator2(1);
}

function saveData(str)
{
var boss_id=document.getElementById("boss_id").value;
var client_id=document.getElementById("client_id").value;
var p_date=document.getElementById("p_date").value;
var change_date=document.getElementById("change_date").value;
var loan_no=document.getElementById("loan_no").value;
var pre_balance=document.getElementById("pre_balance").value;
var amo=document.getElementById("uamo").value;
if(amo.length>=3 && change_date.length>2)
{
if(window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
xmlhttp=new XMLHttpRequest();
}
 
else
{// code for IE6, IE5
xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}

xmlhttp.onreadystatechange=function()
{
if(xmlhttp.readyState==4 && xmlhttp.status==200)
{
document.getElementById("list_cont").innerHTML=xmlhttp.responseText.trim();
}

else
{
document.getElementById("list_cont").innerHTML="Please wait....";
}
} 

xmlhttp.open("GET","user_connector.php?update_pay_loan=1&boss_id="+boss_id+"&client_id="+client_id+"&change_date="+change_date+"&p_date="+p_date+"&loan_no="+loan_no+"&pre_balance="+pre_balance+"&amo="+amo,true);
xmlhttp.send();
}
}

</script>

<main class="column main" style="background-color:#EAEAEA; height:950px">
<p align="center"> <?php include ('summary.php') ?>
<div id="main_heading"> <b>Paying Loan Errors for </b> (<?php echo $names; ?>)
  
</div>   

<div id="main_container">
<div id="main_body">   
<br>
<div id="list_cont"> </div>
<br> 

<?php
$j=0;
  
echo "<table  width='800' border=1 class='table table-responsive table-hover'>
<thead>
<tr>
<th>No</th>
<th>Date </th>
<th>Loan No.</th>
<th>Paid Amount</th> 
<th>Balance</th> 
<th>Change</th>
</tr>
</thead>
<tbody>";
$search_query= mysqli_query($conn,"SELECT * FROM loan_pay  where  
clients_id='$client_id' and bossese_id='$boss_id'  order by p_date  DESC Limit 1");
while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;
$loan_id=$returned_result["loan_id"];
$clients_id=$returned_result["clients_id"];
$p_date=$returned_result["p_date"];
$loan_no=$returned_result["loanNo"];
$amount  = number_format($returned_result["amount_paid"]);
$balance  = number_format($returned_result['balance']);
$date_taken=date("d-m-Y", strtotime($p_date));
 
echo "<tr>
<td> $j </td>
<td> $date_taken </td>
<td> $loan_no </td>
<td> $amount</td> 
<td> $balance </td> 
<td> <a href='error_pay_loan_user.php?client_id=$clients_id&Payment=$loan_id'>Change</td>
<td> <a href='error_pay_loan_user.php?client_id=$clients_id&delete=$loan_id'><font color=red>Delete</font></td>

";
echo "</tr>";
}
echo "</tbody></table>";
if(isset($_GET['Payment'])){
$loan_id=$_GET['Payment'];

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from loan_pay 
where loan_id='$loan_id'"));
$client_id= $results['clients_id'];
$p_date= $results['p_date'];
$loan_no=$results["loanNo"];
$amount= $results['amount_paid'];
$debt= $results['balance'];

$pre_balance=$debt+$amount;

$amount_paid= number_format($results['amount_paid']);
$balance= number_format($results['balance']);
$date_paid=date("d-m-Y", strtotime($p_date));

echo"";

echo "<table  width='800' border=1 class='table table-responsive table-hover'>
<tr><td>Enter Correct Amount Paid on $date_paid</td><td></td></tr>
<tr>
<td> 
<form method='post'>
<input type=hidden id='client_id' value='$client_id'>
<input type=hidden id='boss_id' value='$boss_id'>
<input type=hidden id='loan_no' value='$loan_no'>
<input type=hidden id='pre_balance' value='$pre_balance'>
<input type=hidden id='p_date' value='$p_date'>

Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
<input type=date id='change_date' value='$p_date' class='input is-success' style='width:160px; height:30px; border: 1px solid #006F37'>
</td>
</tr>

<tr>
<td>
Correct Amount:
<input type=text value='$amount_paid'  class='input is-success'   id='uamo' style='width:160px; height:30px; border: 1px solid #006F37' 
onkeyup='validateMe(this.value)' onkeypress='return isNumberKey(event)' required>
<input type='hidden' class='form-control' placeholder='Enter amount' id='uamo2' style='font-size: 10pt'>
</td><td>

<button type='button' class='button is-primary' style='border: 1px solid #006F37; border-radius:4px; color:white;
 background-color:#006F37; margin-left:10px' onclick='saveData(1)'>
&nbsp;&nbsp;&nbsp; Save &nbsp;&nbsp;&nbsp;</button>  

</form>
</td>
</tr>
</table>";
}

//=======Deleting Payment 
if(isset($_GET['delete'])){
$loan_id=$_GET['delete'];
 
$curr_date=date('Y-m-d');
$pre_date = date('Y-m-d', strtotime("$curr_date -1 day"));

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from loan_pay where loan_id='$loan_id'"));
$client_id= $results['clients_id'];
$p_date= $results['p_date'];
$loan_no=$results["loanNo"];
$amount= $results['amount_paid'];
$debt= $results['balance'];
$amount_paid= number_format($results['amount_paid']);
$balance= number_format($results['balance']);
$date_paid=date("d-m-Y", strtotime($p_date));

if($p_date>$curr_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>Error! You are not Allowed to Delete this Transcation
<a href='search_errors_user.php' style='color:white; margin-left:100px;''>Back</a></div>";
}

else{

$pre_balance=$debt+$amount;
 
$query ="DELETE from loan_pay where  clients_id='$client_id' and bossese_id='$boss_id' and loanNo='$loan_no' and p_date='$p_date' and mom=0";
$execute = mysqli_query($conn, $query);

$query ="DELETE from field_payment where clientf_id='$client_id' and boss_id='$boss_id' and pay_date='$p_date' and mom=0";
$execute = mysqli_query($conn, $query);

$query ="DELETE from receipts  where clientrec_id='$client_id' and bossrec_id='$boss_id' and rec_date='$p_date' ";
$execute = mysqli_query($conn, $query);

$query ="DELETE from transcations where clientr_id='$client_id' and boss_id='$boss_id' and transc_date='$p_date' and transc_type='Cash_in' and mom=0";
$execute = mysqli_query($conn, $query);

//clients with loans table
$query ="UPDATE clients_with_loan set debt='$pre_balance' where clientsid='$client_id' and bosseseid='$boss_id'";
$execute = mysqli_query($conn, $query);

$error="Paying Loan MOM";
$amo=0;
mysqli_query($conn,"INSERT INTO errors(ts_id, boss_id, clients_id, error, pay_date, amount) 
VALUES (NULL, '$boss_id', '$client_id',  '$error', '$p_date', '$amo')");

echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>Payment is Succesfully Deleted !
<a href='pay_loan.php?client_id=$client_id' style='color:white; margin-left:100px;''>Pay Loan Again</a></div>";

}
}

?>
</div>
</div>
</div>

</body>
</html>
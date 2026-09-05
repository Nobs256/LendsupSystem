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
var numb=document.getElementById("amount_mo2").value;

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

document.getElementById("amount_mo").value=output;
//document.getElementById("numo").innerHTML=output;
}


function validateMe2(str)
{
var number=str.replace(",","");
if(number.length<=7){
document.getElementById("amount_mo2").value=number;
}

else{
num=document.getElementById("amount_mo").value;
var number=num.replace(",","");
document.getElementById("amount_mo2").value=number;
}

var feed=separator2(1);
}

function saveData(str)
{
var boss_id=document.getElementById("boss_id").value;
var user_id=document.getElementById("user_id").value;
var client_id=document.getElementById("client_id").value;
var numb=document.getElementById("numb").value;
var amount_paid=document.getElementById("amount_paid").value;
var p_date=document.getElementById("p_date").value;
var loan_no=document.getElementById("loan_no").value;
var amo=document.getElementById("uamo").value;
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

xmlhttp.open("GET","user_connector.php?update_pay_mom=1&boss_id="+boss_id+"&user_id="+user_id+"&client_id="+client_id+"&amount_paid="+amount_paid+"&numb="+numb+"&loan_no="+loan_no+"&p_date="+p_date+"&amo="+amo,true);
xmlhttp.send();
}
}
</script>

<main class="column main" style="background-color:#EAEAEA; height:950px">
<p align="center"> <?php include ('summary.php') ?>
<div id="main_heading"> <b>Paying Loan using MOM Errors for </b> (<?php echo $names; ?>)
  
</div>   

<div id="main_container" style="height:480px">
<div id="main_body" style="height:460px">   
<br>
<div id="list_cont"> </div>
<br> 

<?php
$j=0;
$month=date('m');
  
echo "<table  width='900' border=1 class='table table-responsive table-hover'>
<thead>
<tr>
<th>No</th>
<th>Loan No.</th>
<th>Date Received </th>
<th>Received Number </th>
<th>Amount Received</th>
<th>Paid</th>
<th>Change </th>
</tr>
</thead>
<tbody>";
$search_query= mysqli_query($conn,"SELECT * FROM loan_pay, mobile  where clientmo_id=clients_id and clients_id='$client_id' and bossese_id='$boss_id' and mom=1 
and MONTH(p_date)='$month' order by p_date  DESC Limit 1");
while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;
$loan_id=$returned_result["loan_id"];
$clients_id=$returned_result["clients_id"];
$p_date=$returned_result["p_date"];
$phone=$returned_result["phone_sent"];
$amount_mo=number_format($returned_result["amount_mo"]);
$loan_no=$returned_result["loanNo"];
$amount  = number_format($returned_result["amount_paid"]);
$balance  = number_format($returned_result['balance']);
$date=date("d-m-Y", strtotime($p_date));

echo "<tr>
<td> $j </td>
<td> $loan_no </td>
<td> $date </td>
<td> $phone </td>
<td> $amount_mo</td> 
<td> $amount</td> 
<td> <a href='error_mom_user.php?client_id=$clients_id&Payment=$loan_id&phone=$phone'><font color=red>DELETE</font></td>
";
echo "</tr>";
}
echo "</tbody></table>";
if(isset($_GET['Payment'])){
$loan_id=$_GET['Payment'];
$numb=$_GET['phone'];

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

if($p_date!=$curr_date && $p_date!=$pre_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>Error! You are not Allowed to Delete this Transcation
<a href='search_errors_user.php' style='color:white; margin-left:100px;''>Back</a></div>";
}

else{
$pre_balance=$debt+$amount;
$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from total_mom where user_id='$user_id' and boss_id='$boss_id' and phone='$numb'"));
$total= $results['total'];
$new_total= $total-$amount;

$query ="UPDATE total_mom set total='$new_total' where phone='$numb' and user_id='$user_id' 
and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);

$query ="DELETE from loan_pay where  clients_id='$client_id' and bossese_id='$boss_id' and loanNo='$loan_no' and p_date='$p_date' and mom=1";
$execute = mysqli_query($conn, $query);

$query ="DELETE from field_payment where client_id='$client_id' and boss_id='$boss_id' and pay_date='$p_date' and mom=1";
$execute = mysqli_query($conn, $query);

$query ="DELETE from transcations where clientr_id='$client_id' and boss_id='$boss_id' and transc_date='$p_date' and transc_type='Cash_in' and mom=1";
$execute = mysqli_query($conn, $query);

$query ="DELETE from receipts  where clientrec_id='$client_id' and bossrec_id='$boss_id' and rec_date='$p_date' ";
$execute = mysqli_query($conn, $query);

$query ="DELETE from mobile where clientmo_id='$client_id' and mom_date='$p_date' and bossmo_id='$boss_id'";
$execute = mysqli_query($conn, $query);

//clients with loans table
$query ="UPDATE clients_with_loan set debt='$pre_balance' where clientsid='$client_id' and bosseseid='$boss_id'";
$execute = mysqli_query($conn, $query);

$error="Paying Loan MOM";
$amo=0;
mysqli_query($conn,"INSERT INTO errors(ts_id, boss_id, clients_id, error, pay_date, amount) 
VALUES (NULL, '$boss_id', '$client_id',  '$error', '$p_date', '$amo')");

echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>Mom Payment is Succesfully Deleted !
<a href='pay_loan_mom.php?client_id=$client_id' style='color:white; margin-left:100px;''>Pay Loan Again</a></div>";

}
}

?>
</div>
</div>
</div>

</body>
</html>
<?php
//get the client ID
if(isset($_GET['client_id']))
{   
$client_id = $_GET['client_id'];
}

include'header.php';

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
var client_id=document.getElementById("client_id").value;
var p_date=document.getElementById("p_date").value;
var amount_mo=document.getElementById("amount_mo").value;
var loan_no=document.getElementById("loan_no").value;
var pre_balance=document.getElementById("pre_balance").value;
var amo=document.getElementById("uamo").value;
if(amo.length>=3)
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

xmlhttp.open("GET","admin_connector.php?update_pay_mom=1&boss_id="+boss_id+"&client_id="+client_id+"&amount_mo="+amount_mo+"&p_date="+p_date+"&loan_no="+loan_no+"&pre_balance="+pre_balance+"&amo="+amo,true);
xmlhttp.send();
}
}
</script>

<main class="column main" style="background-color:#EAEAEA; height:950px">
<p align="center"> <?php include ('summary_admin.php') ?>
<div id="main_heading"> <b>Paying Loan Errors for </b> (<?php echo $names; ?>)
  
</div>   

<div id="main_container">
<div id="main_body">   
<br>
<div id="list_cont"> </div>
<br> 

<?php
$j=0;
$month=date('m');
$search_query= mysqli_query($conn,"SELECT * FROM loan_pay, mobile  where clientmo_id=clients_id and clients_id='$client_id' and bossese_id='$boss_id' and mom=1 and MONTH(p_date)='$month' order by p_date  DESC Limit 4");
  
echo "<table  width='800' border=1 class='table table-responsive table-hover'>
<thead>
<tr>
<th>No</th>
<th>Loan No.</th>
<th>Date Received </th>
<th>Amount Received</th>
<th>Paid</th>
  <th> </th>
</tr>
</thead>
<tbody>";

while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;
$loan_id=$returned_result["loan_id"];
$clients_id=$returned_result["clients_id"];
$p_date=$returned_result["p_date"];
$amount_mo=number_format($returned_result["amount_mo"]);
$loan_no=$returned_result["loanNo"];
$amount  = number_format($returned_result["amount_paid"]);
$balance  = number_format($returned_result['balance']);
$date_taken=date("d-m-Y", strtotime($p_date));

echo "<tr>
<td> $j </td>
<td> $loan_no </td>
<td> $date_taken </td>
<td> $amount_mo</td> 
<td> $amount</td> 
<td> <a href='error_mom.php?client_id=$clients_id&Payment=$loan_id'>Select</td>
";
echo "</tr>";
}
echo "</tbody></table>";
if(isset($_GET['Payment'])){
$loan_id=$_GET['Payment'];

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from loan_pay where loan_id='$loan_id'"));
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
<tr><td>Enter Correct Amount Sent or Paid on $date_paid</td><td></td><td></td></tr>
<tr>
<td> 
<form method='post'>
<input type=hidden id='client_id' value='$client_id'>
<input type=hidden id='boss_id' value='$boss_id'>
<input type=hidden id='p_date' value='$p_date'>
<input type=hidden id='loan_no' value='$loan_no'>
<input type=hidden id='pre_balance' value='$pre_balance'>

Amount Received:
<input type=text value='$amount_mo'  class='input is-success'   id='amount_mo' style='width:160px; height:30px; border: 1px solid #006F37' 
onkeyup='validateMe2(this.value)' onkeypress='return isNumberKey(event)' required>
<input type='hidden' class='form-control' placeholder='Enter amount' id='amount_mo2' style='font-size: 10pt'>
</td><td>

 Amount Paid:
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
?>
</div>
</div>
</div>

</body>
</html>
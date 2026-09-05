<?php
$sa="";
if(isset($_POST['reload']))
{
$sec=$_POST['re'];
 header("refresh: $sec");
}   
 
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
var numb=document.getElementById("reg_fee2").value;

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

document.getElementById("reg_fee").value=output;
//document.getElementById("numo").innerHTML=output;
}


function validateMe2(str)
{
var number=str.replace(",","");
if(number.length<=7){
document.getElementById("reg_fee2").value=number;
}

else{
num=document.getElementById("reg_fee").value;
var number=num.replace(",","");
document.getElementById("reg_fee2").value=number;
}

var feed=separator2(1);
}

function saveData(str)
{
var boss_id=document.getElementById("boss_id").value;
var client_id=document.getElementById("client_id").value;
var b_date=document.getElementById("b_date").value;
var b_date1=document.getElementById("b_date1").value;
var reg_fee=document.getElementById("reg_fee").value;
var amo=document.getElementById("uamo").value;
if(b_date1.length>0 && amo.length>=3 && reg_fee.length>=1)
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

xmlhttp.open("GET","error_connector.php?update_loan=1&boss_id="+boss_id+"&client_id="+client_id+"&b_date1="+b_date1+"&b_date="+b_date+"&amo="+amo+"&reg_fee="+reg_fee,true);
xmlhttp.send();
}
}

</script>

<main class="column main" style="background-color:#EAEAEA; height:950px">
<p align="center"> <?php include ('summary.php') ?>
<div id="main_heading"> <b>Giving Loan Errors for </b> (<?php echo $names; ?>)
  
</div>   

<div id="main_container" style="height:450px">
<div id="main_body" style="height:430px">   
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
<th>Date</th>
<th>Process. Fee</th>
<th> Amount Given</th>
<th>Change</th>

</tr>
</thead>
<tbody>";
$search_query= mysqli_query($conn,"SELECT * FROM loans  where  cliente_id='$client_id' and bossese_id='$boss_id' order by b_date Desc limit 1");
while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;
$loan_id=$returned_result["loan_id"];
$clients_id=$returned_result["cliente_id"];
$b_date=$returned_result["b_date"];
$amount_given  = number_format($returned_result["amount_given"]);
$reg_fee  = number_format($returned_result["reg_fee"]);
$date_taken=date("d-m-Y", strtotime($b_date));


 
echo "<tr>
<td> $j </td>
<td> $date_taken </td>
<td> $reg_fee</td> 
<td> $amount_given </td> 
<td> <a href='error_give_loan_user.php?client_id=$clients_id&give=$loan_id'>Change</td>
<td> <a href='error_give_loan_user.php?client_id=$clients_id&delete=$loan_id'><font color=red>Delete</font></td>
";
echo "</tr>";
}
echo "</tbody></table>";
if(isset($_GET['give'])){
$loan_id=$_GET['give'];

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from loans where loan_id='$loan_id'"));
$client_id= $results['cliente_id'];
$b_date= $results['b_date'];
$amount_given= number_format($results['amount_given']);
$reg_fee= number_format($results['reg_fee']);
$date_taken=date("d-m-Y", strtotime($b_date));

echo "
<p align=left>Enter Correct Loan Given on $date_taken</p><br>
<table  width='400' border=1 class='table table-responsive table-hover>
<tr><td></td><td></td><td></td></tr>
<tr>
<td> 
<form method='post'>
<input type=hidden id='client_id' value='$client_id'>
<input type=hidden id='boss_id' value='$boss_id'>
<input type=hidden id='b_date' value='$b_date'>
</td>
<td>Date:</td><td>
<input type=date id='b_date1' class='input is-success' style='width:160px; height:30px; border: 1px solid #006F37'>
</td></tr><tr><td>
Reg Fee:</td><td>
<input type='text' value='$reg_fee' id='reg_fee' class='input is-success' style='width:160px; height:30px; border: 1px solid #006F37' onkeyup='validateMe2(this.value)' onkeypress='return isNumberKey(event)' required>  
<input type='hidden' class='form-control' placeholder='Enter amount' id='reg_fee2' style='font-size: 10pt' required>      
</td></tr><tr><td>
Enter Correct Amount:</td><td>
<input type=text value='$amount_given'  class='input is-success'   id='uamo' style='width:160px; height:30px; border: 1px solid #006F37' 
onkeyup='validateMe(this.value)' onkeypress='return isNumberKey(event)' required> 
<input type='hidden' class='form-control' placeholder='Enter amount' id='uamo2' style='font-size: 10pt'>
</td></tr><tr><td>
</td><td>
<button type='button' class='button is-primary' style='border: 1px solid #006F37; border-radius:4px; color:white;
 background-color:#006F37; margin-left:10px' onclick='saveData(1)'>
&nbsp;&nbsp;&nbsp; Save &nbsp;&nbsp;&nbsp;</button>  

</form>
</td>
</tr>
</table>";
}

//=======Deleting Payment =============
if(isset($_GET['delete'])){
$loan_id=$_GET['delete'];
 
$curr_date=date('Y-m-d');
$pre_date = date('Y-m-d', strtotime("$curr_date -1 day"));

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from loans  where loan_id='$loan_id'"));
$client_id= $results['cliente_id'];
$p_date= $results['b_date'];
$amount_given= $results['amount_given'];


$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM loan_pay  where  clients_id='$client_id'
and bossese_id='$boss_id' and  p_date>='$p_date' and loanNo='$loan_no'"));     
$amount = $result['amount_paid'];

if ($amount>0){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 450px'>The Loan has already started to be paid.
<a href='search_errors_user.php?reload=1' style='color:white; margin-left:50px;''>X</a></div>";
}


else{


$query ="DELETE from loans where  cliente_id='$client_id' and bossese_id='$boss_id' and b_date='$p_date'";
$execute = mysqli_query($conn, $query);

$query ="DELETE from completed_loan where clientcpid='$client_id' and bosscpid='$boss_id' and pay_date='$p_date' and completed=0";
$execute = mysqli_query($conn, $query);

$query ="DELETE from guarantor  where clientg_id='$client_id' and bossesg_id='$boss_id' and g_date='$p_date' ";
$execute = mysqli_query($conn, $query);

$query ="DELETE from transcations where clientr_id='$client_id' and boss_id='$boss_id' and transc_date='$p_date' and transc_type='Cash_out'";
$execute = mysqli_query($conn, $query);
  
$query ="DELETE from loans_in_parts where clientp_id='$client_id' and bp_date='$p_date' and part='Completed'";
$execute = mysqli_query($conn, $query);

//clients with loans table
$query ="DELETE from clients_with_loan where clientsid='$client_id' and bosseseid='$boss_id'";
$execute = mysqli_query($conn, $query);

$error="Deleted Loan";
$amo=0;
mysqli_query($conn,"INSERT INTO errors(ts_id, user_id, boss_id, clients_id, error, pay_date, pre_amount, amount) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id',  '$error', '$p_date', '$amount_given', '$amount_given')");

echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>A Loan is Succesfully Deleted !
<a href='pay_loan.php?client_id=$client_id' style='color:white; margin-left:100px;''>Pay Loan Again</a></div>";

}
}

?>
</div>
</div>
</div>

</body>
</html>
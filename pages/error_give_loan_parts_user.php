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

xmlhttp.open("GET","error_connector.php?update_loan_in_parts=1&boss_id="+boss_id+"&client_id="+client_id+"&b_date1="+b_date1+"&b_date="+b_date+"&amo="+amo+"&reg_fee="+reg_fee,true);
xmlhttp.send();
}
}

</script>

<main class="column main" style="background-color:#EAEAEA; height:950px">
<p align="center"> <?php include ('summary.php') ?>
<div id="main_heading"> <b>Giving Loan Errors in parts for </b> (<?php echo $names; ?>)
  
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
$search_query= mysqli_query($conn,"SELECT * FROM loans_in_parts  where  clientp_id='$client_id' and bossesp_id='$boss_id' and part='Part' order by loan_id Desc limit 1");
while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;
$loan_id=$returned_result["loan_id"];
$clients_id=$returned_result["clientp_id"];
$b_date=$returned_result["bp_date"];
$amount_given  = number_format($returned_result["amount_g"]);
$reg_fee  = number_format($returned_result["reg_fee"]);
$date_taken=date("d-m-Y", strtotime($b_date));
 
echo "<tr>
<td> $j </td>
<td> $date_taken </td>
<td> $reg_fee</td> 
<td> $amount_given </td> 
<td> <a href='error_give_loan_parts_user.php?client_id=$clients_id&give=$loan_id'>Change</td>
<td> <a href='error_give_loan_parts_user.php?client_id=$clients_id&delete=$loan_id'><font color=red>Delete</font></td>
";
echo "</tr>";
}
echo "</tbody></table>";
if(isset($_GET['give'])){
$loan_id=$_GET['give'];

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from loans_in_parts where loan_id='$loan_id'"));
$client_id= $results['clientp_id'];
$b_date= $results['bp_date'];
$amount_given= number_format($results['amount_g']);
$reg_fee= number_format($results['reg_fee']);
$loan_id= number_format($results['loan_id']);
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

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from loans_in_parts where loan_id='$loan_id'"));
$client_id= $results['clientp_id'];
$b_date= $results['bp_date'];
$amount_g= $results['amount_g'];

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from sent_msgs where user_id='$user_id' 
and boss_id='$boss_id' order by ts_id DESC Limit 1")); 
$msg_date= $result['msg_date'];
 
 
if($b_date<=$msg_date){
echo"<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>You Have Already Sent Report. Not Allowed to Delete this Loan
<a href='user_homepage.php' style='color:white; margin-left:70px;''>X</a></div>";
}
else{
$query ="DELETE from loans_in_parts where clientp_id='$client_id' and loan_id='$loan_id'";
$execute = mysqli_query($conn, $query);

$query ="DELETE from transcations where clientr_id='$client_id' and boss_id='$boss_id' and transc_date='$b_date' and transc_type='Cash_out'";
$execute = mysqli_query($conn, $query);

  

$error="Deleted Loan in Parts";
$amo=0;
mysqli_query($conn,"INSERT INTO errors(ts_id, user_id, boss_id, clients_id, error, pay_date, pre_amount, amount) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id',  '$error', '$p_date', '$amount_g', '$amount_g')");

echo"<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 600px'>A Loan is Succesfully Deleted !
<a href='user_homepage.php' style='color:white; margin-left:100px;''>X</a></div>";
}
}
?>
</div>
</div>
</div>

</body>
</html>
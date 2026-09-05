<?php
include'header_user.php';
$sa="";
if(isset($_POST['reload']))
{
$sec=$_POST['re'];
 header("refresh: $sec");
}   
 
if(isset($_GET['success'])){
$sa = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 400px'>
<font color=white>A Client is successfully Given Loan!!</font>
<a href='search_client_give_loan.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</div>";
}

if(isset($_GET['already_client'])){
$sa = "<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 500px'>
<font color=white>The Client is not allowed to get a Loan!!</font>
<a href='search_client_give_loan.php?reload=1' style='color:white; margin-left:120px;''>X</a>
</div>";
}
//get the client ID
if(isset($_GET['client_id'])){    
$client_id=$_GET['client_id'];
$b_date=date('Y-m-d');
$mom=0;
$printed=0;

function countExist($conn,$table,$key)
{
$k=mysqli_query($conn,"SELECT * FROM $table WHERE $key");
$rows=mysqli_num_rows($k);
return $rows;
}

$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients_with_loan where clientsid='$client_id'"));     
$loan_no=$result['loan_no'];
$pay_date=$result['pay_date'];
$amo=$result['debt'];
$debt=$result['debt'];
$balance=$debt-$amo;

$check=countExist($conn,"loan_pay","p_date='$b_date' AND amount_paid='$amo' and p_date='$b_date'");
if($check==0)
{

if($balance==0){
$query ="update completed_loan set e_date='$b_date', completed=1 where loans_no=$loan_no and clientcpid='$client_id' and userscpid='$user_id' and bosscpid='$boss_id'";
$execute = mysqli_query($conn, $query);
}

mysqli_query($conn,"INSERT INTO loan_pay(loan_id, loanNo, clients_id, userse_id, bossese_id, p_date, amount_paid, balance, mom) 
VALUES (NULL, '$loan_no', '$client_id',  '$user_id', '$boss_id', '$b_date', '$amo', '$balance', '$mom')");

mysqli_query($conn,"INSERT INTO receipts(receipt_id, userrec_id, bossrec_id, clientrec_id, loan_norec_id, rec_date, paid_amount, balancerec, printed) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$loan_no', '$b_date', '$amo', '$balance', '$printed')");
//===update the loans
$query ="update clients_with_loan set debt='$balance' where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'";
$execute = mysqli_query($conn, $query);

}

}

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients, guarantor where client_id='$client_id' 
and users_id='$user_id' and bosses_id='$boss_id' and g_date='$pay_date' and client_id=clientg_id"));
$names = $results['firstname']." ".$results['lastname'];
$g_names1 = $results['g_names1'];
$g_nid1 = $results['g_nid1'];
$g_phone1 = $results['g_phone1'];
$g_occupation1 = $results['g_occupation1'];
$place_r1 = $results['place_r1'];
$place_w1 = $results['place_w1'];
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
var user_id=document.getElementById("user_id").value;
var client_id=document.getElementById("client_id").value;
var b_date=document.getElementById("b_date").value;
var reg_fee=document.getElementById("reg_fee").value;
var security=document.getElementById("security").value;
var g_names1=document.getElementById("g_names1").value;
var g_nid1=document.getElementById("g_nid1").value;
var g_phone1=document.getElementById("g_phone1").value;
var g_occupation1=document.getElementById("g_occupation1").value;
var place_r1=document.getElementById("place_r1").value;
var place_w1=document.getElementById("place_w1").value;
var amo=document.getElementById("uamo").value;
if(b_date.length>0 && amo.length>=3)
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

xmlhttp.open("GET","give_loan_connector.php?ref=1&boss_id="+boss_id+"&user_id="+user_id+"&client_id="+client_id+"&b_date="+b_date+"&amo="+amo+"&reg_fee="+reg_fee+"&security="+security+"&g_names1="+g_names1+"&g_nid1="+g_nid1
+"&g_phone1="+g_phone1+"&g_occupation1="+g_occupation1+"&place_r1="+place_r1+"&place_w1="+place_w1,true);
xmlhttp.send();
}
}

</script>

<main class="column main" style="background-color:#EAEAEA; height:950px">
<p align="center"> <?php include ('summary.php') ?>
<div id="main_heading"> <b>Renew the loan  for</b> (<?php echo $names; $debt;?>)
<font color="#EAEAEA">-------------------</font>
  
</div>   

<div id="main_container">
<div id="main_body">   
<br>
<div id="list_cont"> </div>
<br>
<form method="GET">
<input type="hidden" id="boss_id" value="<?php echo $boss_id; ?>">
<input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
<input type="hidden" id="client_id" value="<?php echo $client_id; ?>">
<input type="hidden" id="security" value="Renewed Loan">
<input type="hidden" id="reg_fee" value="0">
<table style="width:900px;" border="0">
<tr>
<td width="100px"> 
Date: 
</td>
<td width="240px">
<input type="date" id="b_date" class="input is-success" style="width:270px; border: 1px solid #006F37" required><br><br>
</td>
<td width="100px"> 
Amount:
</td>
<td width="180px">
<input type="text"  class="input is-success" value="<?php echo $amo; ?>"  id="uamo" style="width:270px; border: 1px solid #006F37" 
onkeyup="validateMe(this.value)" onkeypress="return isNumberKey(event)" required> 
<!--<label for="inputdefault">Amount</label>-->
<input type="hidden" class="form-control" placeholder="Enter amount" id="uamo2" style="font-size: 10pt">
</td></tr>
<tr>
<tr><td><hr></td><td><hr></td><td><font size="5" color="green">  Guarantors </font></td><td><hr></td></tr>
  
<tr>
<td> 
Name: 
</td>
<td>
<input type="text" id="g_names1" value="<?php echo $g_names1; ?>" class="input is-success" style="width:270px; border: 1px solid #006F37" 
required><br><br>          
</td>
<td>      
National ID:  
</td>
<td>
<input type="text" id="g_nid1" value="<?php echo $g_nid1; ?>" class="input is-success" style="width:270px; border: 1px solid #006F37"
required><br><br>         
</td>        
</tr><!--end of tr -->

<tr> 
<td>      
Phone No:
</td>
<td>
<input type="tel" maxlength = "10" value="<?php echo $g_phone1; ?>" id="g_phone1" class="input is-success" style="width:270px; border: 1px solid #006F37"
required><br><br>
</td>     
<td>      
Occupation:
</td>
<td>
<input type="text" value="<?php echo $g_occupation1; ?>"  id="g_occupation1" class="input is-success" style="width:270px; border: 1px solid #006F37"
required><br><br>
</td>        
</tr> 

<tr> 
<td>      
Place of Residence:
</td>
<td>
<input type="text" value="<?php echo $place_r1; ?>"  id="place_r1" class="input is-success" style="width:270px; border: 1px solid #006F37"
required><br><br>
</td>     
<td>      
Place of Work:
</td>
<td>
<input type="text" value="<?php echo $place_w1; ?>" id="place_w1" class="input is-success" style="width:270px; border: 1px solid #006F37"
required><br><br>
</td>        
</tr> 
</table>          
  
<button type="button" class="button is-primary" style="border: 1px solid #006F37; border-radius:4px; color:white;
 background-color:#006F37; margin-left:400px" onclick="saveData(1)">
&nbsp;&nbsp;&nbsp; Renew Loan &nbsp;&nbsp;&nbsp;</button>  
 



</div>
</div>
</div>

</body>
</html>
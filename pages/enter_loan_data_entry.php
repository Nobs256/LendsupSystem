<?php
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
if(isset($_REQUEST['client_id']))
{   
$client_id = $_REQUEST['client_id'];
}

include'header_user.php';

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients where client_id='$client_id' and users_id='$user_id' and bosses_id='$boss_id'"));
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
var user_id=document.getElementById("user_id").value;
var client_id=document.getElementById("client_id").value;
var b_date=document.getElementById("b_date").value;
var reg_fee=document.getElementById("reg_fee").value;
var security=document.getElementById("security").value;
var g_names1=document.getElementById("g_names1").value;
var g_nid1=document.getElementById("g_nid1").value;
var nid_client=document.getElementById("nid_client").value;
var security_g=document.getElementById("security_g").value;
var g_phone1=document.getElementById("g_phone1").value;
var g_occupation1=document.getElementById("g_occupation1").value;
var place_r1=document.getElementById("place_r1").value;
var place_w1=document.getElementById("place_w1").value;
var amo=document.getElementById("uamo").value;
if(b_date.length>0 && amo.length>=3 && reg_fee.length>=3 && security.length>=2 && g_names1.length>=4 && g_phone1.length>=10)
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
+"&g_phone1="+g_phone1+"&g_occupation1="+g_occupation1+"&place_r1="+place_r1+"&nid_client="+nid_client+"&security_g="+security_g+"&place_w1="+place_w1,true);
xmlhttp.send();
}
}

</script>

<main class="column main" style="background-color:#EAEAEA; height:950px">
<p align="center"> <?php include ('summary.php') ?>
<div id="main_heading"> <b>Give loan to a Client</b> (<?php echo $names; ?> )
<font color="#EAEAEA">-------------------</font>
<a href="view_clients.php"><font color="green">View Clients With Loans</font></a> 
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
<input type="hidden"  id="g_occupation1" value="0">
<input type="hidden"  id="place_r1" value="0">
<input type="hidden"  id="place_w1" value="0">
<input type="hidden"  id="security_g" value="0">
<input type="hidden"  id="nid_client" value="Original">
<input type="hidden"  id="g_nid1" value="Photocopy">

<input type="hidden"  id="reg_fee" value="10000">
<input type="hidden"  id="security" value="xxxxxxxxxxxxxx">
<input type="hidden"  id="g_names1" value="xxxxxxxxxxxxxxx">
<input type="hidden"  id="g_phone1" value="000000000000">

<table style="width:900px;" border="0">
<tr>
<td width="130px"> 
Date: 
</td>
<td width="300px">
<input type="date" id="b_date" class="input is-success" style="width:270px; border: 1px solid #006F37" 
required><br><br>
</td>
<td> 
Amount Given:
</td>
<td>
<input type="text"  class="input is-success"   id="uamo" style="width:270px; border: 1px solid #006F37" 
onkeyup="validateMe(this.value)" onkeypress="return isNumberKey(event)" required> 
<!--<label for="inputdefault">Amount</label>-->
<input type="hidden" class="form-control" placeholder="Enter amount" id="uamo2" style="font-size: 10pt">
</td></tr>
<!--
<tr>
<td> 
Processing Fee: 
</td>
<td>
<input type="text" id="reg_fee" class="input is-success" style="width:270px; border: 1px solid #006F37" onkeyup="validateMe2(this.value)" onkeypress="return isNumberKey(event)" required><br><br>  
<input type="hidden" class="form-control" placeholder="Enter amount" id="reg_fee2" style="font-size: 10pt" required>      
</td>  
<td>      
Security:  
</td>
<td>
<input type="text"  id="security" class="input is-success" style="width:270px; border: 1px solid #006F37"
required><br><br>         
</td>        
</tr> 
<tr>
<td> 
Guarantor's Name: 
</td>
<td>
<input type="text" id="g_names1" class="input is-success" style="width:270px; border: 1px solid #006F37" 
required><br><br>          
</td>       
<td>      
Guarantor's Phone No:
</td>
<td>
<input type="tel" maxlength = "10" id="g_phone1" class="input is-success" style="width:270px; border: 1px solid #006F37"
required><br><br>
</td>     
<td>  
<!--    
Occupation:
</td>
<td>
<input type="text"  id="g_occupation1" class="input is-success" style="width:270px; border: 1px solid #006F37"
required><br><br>
</td>        
</tr> 

<tr> 
<td>      
Place of Residence:
</td>
<td>
<input type="text"  id="place_r1" class="input is-success" style="width:270px; border: 1px solid #006F37"
required><br><br>
</td>     
<td>      
Place of Work:
</td>
<td>
<input type="text"  id="place_w1" class="input is-success" style="width:270px; border: 1px solid #006F37"
required><br><br>
</td> 
</tr> 
<tr> 
<td>      
Security:  
</td>
<td>
<input type="text"  id="security_g" class="input is-success" style="width:270px; border: 1px solid #006F37"
required><br><br>         
</td>  -->   
</tr> 
</table>          
  
<button type="button" class="button is-primary" style="border: 1px solid #006F37; border-radius:4px; color:white;
 background-color:#006F37; margin-left:400px" onclick="saveData(1)">
&nbsp;&nbsp;&nbsp; Give Loan &nbsp;&nbsp;&nbsp;</button>  
 


</div>
</div>
 
 
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>
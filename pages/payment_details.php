<?php
$sa="";
include('header_user.php');

if(isset($_GET['success'])){
$sa = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 500px'>
<font color=white>Data is Successfully Entered!!</font>
<a href='search_client_withdraw.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</div>";
}
?>
 <style>

th, td {
text-align: left;
padding-left: 10px;
 
}

tr{
height: 50px;
}
</style>
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

function saveData(str)
{
var boss_id=document.getElementById("boss_id").value;
var user_id=document.getElementById("user_id").value;
var b_date=document.getElementById("b_date").value;
var transc=document.getElementById("transc").value;
var bank_name=document.getElementById("bank_name").value;
var amo=document.getElementById("uamo").value;
if(b_date.length>0 && amo.length>=3 && transc.length>3 && bank_name.length)
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

xmlhttp.open("GET","user_connector.php?banking=1&boss_id="+boss_id+"&user_id="+user_id+"&bank_name="+bank_name+"&transc="+transc+"&b_date="+b_date+"&amo="+amo,true);
xmlhttp.send();
}
}
</script>
<?php
$cent_total=0;
$equity_total=0;
$select = mysqli_query($conn,"SELECT * FROM total_banking where user_id='$user_id' and boss_id='$boss_id' and bank_name='Centenary'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["total"]; 
$cent_total+=$amount;
}

$select = mysqli_query($conn,"SELECT * FROM total_banking where user_id='$user_id' and boss_id='$boss_id' and bank_name='Equity'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["total"]; 
$equity_total+=$amount;
}

?>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> <?php include ('summary.php') ?>
<hr> 
<div id="main_heading">PAYMENT DETAILS &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </div>     

 
<div id="main_container" style="height:420px">
<div id="main_body" style="height:390px">   
<br>
<br>
<br>
<p align="center"><font size="4">You can pay using MTN Mobile Money (0777842873-AYEBAZIBWE ABEL)<br>
Centenary (3202912015-AYEBAZIBWE ABEL)<br>Stanbic (9030020632185-Kyabel (KY) Technologies)</font>
</p>
<br>
<br>
<p align="center"><font size="4"><b>Note: After paying, inform us and If the payment is not done in time, the system will be off! </b></font></p>
</div>
</div>
</div> 
 <?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>


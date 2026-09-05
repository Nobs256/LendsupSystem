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
$select = mysqli_query($conn,"SELECT * FROM total_banking where user_id='$user_id' and boss_id='$boss_id' and bank_name='ABSA'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["total"]; 
$cent_total+=$amount;
}

$select = mysqli_query($conn,"SELECT * FROM total_banking where user_id='$user_id' and boss_id='$boss_id' and bank_name='Standard Cht'");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["total"]; 
$equity_total+=$amount;
}

?>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> <?php include ('summary.php') ?>
<hr> 
<div id="main_heading">BANKING DEPOSITS/WITHDRAWS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</b> </div>     

 
<div id="main_container" style="height:420px">
<div id="main_body" style="height:390px">   
<br>
<div id="list_cont"> </div><?php echo $sa; ?>
 
<table style="width:100%;" border="0">   
<tr>
<td width="40%">                           
<table style="width:400px;" border="0">

<form method="GET">
<input type="hidden" id="boss_id" value="<?php echo $boss_id; ?>">
<input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
<input type="hidden" id="bank_name" value="Bank">
<tr>
<td width="70px"> 
Date: 
</td>
<td width="210px">
<input type="date" id="b_date" class="input is-success" style="width:220px; border: 1px solid #006F37" 
required>
</td></tr>
<tr>
<td>      
Deposit/Withdraw:  
</td>
<td>
<div class="select is-success">  
<select  id="transc"  style="width:220px; border: 1px solid #006F37; height:35px" required> 
<option></option>
<option>Deposit</option>
<option>Withdraw</option>

</select></div>     
</td>       
</tr><tr>       
<td width="100px"> 
Amount:
</td>
<td width="300px">
<input type="text"  class="input is-success"   id="uamo" style="width:220px; border: 1px solid #006F37" 
onkeyup="validateMe(this.value)" onkeypress="return isNumberKey(event)" required> 
<!--<label for="inputdefault">Amount</label>-->
<input type="hidden" class="form-control" placeholder="Enter amount" id="uamo2" style="font-size: 10pt">
</td>
</tr> 
<tr><td></td>
<td width="50px">  
<button type="button" class="button is-primary" style="border: 1px solid #006F37; border-radius:4px; color:white;
 background-color:#006F37; margin-left:0px" onclick="saveData(1)">
&nbsp;&nbsp;&nbsp; Save Data &nbsp;&nbsp;&nbsp;</button>  
</td>
</tr><!--end of tr --> 
</table> 
</form> 
</td>
</tr>
</table>
</div>
</div>
</div> 
 <?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>


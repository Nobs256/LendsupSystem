<?php
$sa="";
include('header_user.php');

if(isset($_GET['success'])){
$sa = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 500px'>
<font color=white>Data is Successfully Entered!!</font>
<a href='mom_withdraws.php' style='color:white; margin-left:100px;''>X</a>
</div>";
}

if(isset($_GET['more_entered'])){
$sa = "<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>The amount entered is greater than Total Savings
<a href='mom_withdraws.php' style='color:white; margin-left:90px;''>X</a></div>";
}
?>
 <style>
 tr:nth-child(even) {
background-color: #D9FFD9;
}

th, td {
text-align: left;
padding-left: 10px;
 
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
var with_date=document.getElementById("with_date").value;
var numb=document.getElementById("numb").value;
var amo=document.getElementById("uamo").value;
if(with_date.length>0 && amo.length>=3 && numb.length>=3)
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

xmlhttp.open("GET","mom_withdraws_connector.php?mom_withdraw=1&boss_id="+boss_id+"&user_id="+user_id+ "&numb="+numb+ "&with_date="+with_date+"&amo="+amo,true);
xmlhttp.send();
}
}

</script>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> <?php include ('summary.php') ?>
<hr> 
<div id="main_heading"> <b>
WITHDRAW MONEY FROM MOBILE MONEY</b> </div>     

<br>
<div id="main_container" style="height:420px">
<div id="main_body" style="height:390px">   
<br>
<div id="list_cont"> </div><?php echo $sa;?>
<br>                               
<table style="width:900px;" border="0">

<form method="GET">
<input type="hidden" id="boss_id" value="<?php echo $boss_id; ?>">
<input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
<tr>
<td width="70px"> 
Date: 
</td>
<td width="210px">
<input type="date" id="with_date" class="input is-success" style="width:190px; border: 1px solid #006F37" 
required><br><br>
</td>
<td>      
Number:  
</td>
<td>
<div class="select is-success">  
<select  id="numb"  style="width:200px; border: 1px solid #006F37; height:35px" required> 
<option></option>
 
<?php
$comb = mysqli_query($conn,"SELECT * from mom_phones where boss_id='$boss_id' and user_id='$user_id' and active=1 ");

while($select_comb = mysqli_fetch_array($comb)){
$phone=$select_comb['phone'];
$company=$select_comb['company'];
 
echo "<option value= $phone>$company($phone) </option>";
 
}
echo" 
</select></div>";?>

</select>
</td>  
<td width="100px"> 
Amount:
</td>
<td width="300px">
<input type="text"  class="input is-success"   id="uamo" style="width:150px; border: 1px solid #006F37" 
onkeyup="validateMe(this.value)" onkeypress="return isNumberKey(event)" required> 
<!--<label for="inputdefault">Amount</label>-->
<input type="hidden" class="form-control" placeholder="Enter amount" id="uamo2" style="font-size: 10pt">
</td>
<td width="50px">  
<button type="button" class="button is-primary" style="border: 1px solid #006F37; border-radius:4px; color:white;
 background-color:#006F37; margin-left:0px" onclick="saveData(1)">
&nbsp;&nbsp;&nbsp; Withdraw &nbsp;&nbsp;&nbsp;</button>  
</td>
</tr><!--end of tr --> 
</table> 
</form> 
</div>
</div>
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>


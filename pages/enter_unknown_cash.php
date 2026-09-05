<?php
include('header_user.php');
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

function saveData(str)
{
var boss_id=document.getElementById("boss_id").value;
var user_id=document.getElementById("user_id").value;
var b_date=document.getElementById("b_date").value;
var officer_id=document.getElementById("officer_id").value;
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

xmlhttp.open("GET","user_connector.php?unknown_cash=1&boss_id="+boss_id+"&user_id="+user_id+"+&b_date="+b_date+"+&officer_id="+officer_id+"&amo="+amo,true);
xmlhttp.send();
}
}

</script>
<main class="column main" style="background-color:#EAEAEA;">
<?php include ('summary.php') ?>

<div id="main_heading"> <b>Enter Unknown Cash</b>
<font color="#EAEAEA">-------------------</font>

</div>     
<br>
<div id="main_container">
<div id="main_body">   
<br>
<div id="list_cont"> </div>
<br>                               
<table style="width:700px;" border="0">

<form method="GET">
<input type="hidden" id="boss_id" value="<?php echo $boss_id; ?>">
<input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
<tr>
<td width="70px"> 
Date: 
</td>
<td width="200px">
<input type="date" id="b_date" class="input is-success" style="width:200px; border: 1px solid #006F37" 
required><br><br>
</td>
</tr>
<tr>
<td width="110px"> 
Amount:
</td>
<td width="230px">
<input type="text"  class="input is-success"   id="uamo" style="width:200px; border: 1px solid #006F37" 
onkeyup="validateMe(this.value)" onkeypress="return isNumberKey(event)" required> 
<!--<label for="inputdefault">Amount</label>-->
<input type="hidden" class="form-control" placeholder="Enter amount" id="uamo2" style="font-size: 10pt">
<br><br>
</td>
</tr>
<tr>
<td>Officer:</td>
<td>
<div class="select is-success">
<select  id="officer_id"  style="width:200px; border: 1px solid #006F37" required>
<option></option>
<?php
$comb = mysqli_query($conn,"SELECT * from officers where boss_id='$boss_id' and active=1 and branch='$bra'");

while($select_comb = mysqli_fetch_array($comb)){
$officerid=$select_comb['officer_id'];
$officers_name=$select_comb['firstname']." ".$select_comb['lastname'];
$phone=$select_comb['phone'];
 
echo "<option value= $officerid> $officers_name </option>";
}
?>

</td>
</tr>
<tr>
<td>&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;</td>
<td>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
<td>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;</td>
<td width="50px">  
<button type="button" class="button is-primary" style="border: 1px solid #006F37; border-radius:4px; color:white;
 background-color:#006F37; margin-left:0px" onclick="saveData(1)">
&nbsp;&nbsp;&nbsp; Save Data &nbsp;&nbsp;&nbsp;</button>  
</td>
</tr><!--end of tr --> 
</table> 
</form>   
</div>
</div>
<div> 
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>
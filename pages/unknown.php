<?php
include('header_user.php');
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
var b_date=document.getElementById("b_date").value;
var numb=document.getElementById("numb").value;
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

xmlhttp.open("GET","user_connector.php?unknown=1&boss_id="+boss_id+"&user_id="+user_id+"&numb="+numb+"&b_date="+b_date+"&amo="+amo,true);
xmlhttp.send();
}
}

</script>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> <?php include ('summary.php') ?>
<hr> 
<div id="main_heading"> <b>
UNKNOWN SOURCES OF MONEY ON MOM:</b> </div>     

<br>
<div id="main_container" style="height:420px">
<div id="main_body" style="height:390px">   
<br>
<div id="list_cont"> </div>
<br>                               
<table style="width:1000px;" border="0">

<form method="GET">
<input type="hidden" id="boss_id" value="<?php echo $boss_id; ?>">
<input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
<tr>
<td width="70px"> 
Date: 
</td>
<td width="100px">
<input type="date" id="b_date" class="input is-success" style="width:220px; border: 1px solid #006F37" 
required><br><br>
</td>

<td width="100px"> 
Sent On:
</td>
<td width="250px">
<div class="select is-success">
<select  id="numb"  style="width:200px; border: 1px solid #006F37" required>
<option></option>
<?php
$comb = mysqli_query($conn,"SELECT * from mom_phones where boss_id='$boss_id' and user_id='$user_id' and active=1 ");

while($select_comb = mysqli_fetch_array($comb)){
$phone=$select_comb['phone'];
$company=$select_comb['company'];
 
echo "<option value= $phone>$company($phone) </option>";
 
}
echo" 
</select></div>";
?>           
</select> <br><br>
</div></td>
 
<td width="100px"> 
Amount:
</td>
<td width="100px">
<input type="text"  class="input is-success"   id="uamo" style="width:150px; border: 1px solid #006F37" 
onkeyup="validateMe(this.value)" onkeypress="return isNumberKey(event)" required> 
<!--<label for="inputdefault">Amount</label>-->
<input type="hidden" class="form-control" placeholder="Enter amount" id="uamo2" style="font-size: 10pt">
</td>
<td width="50px">  
<button type="button" class="button is-primary" style="border: 1px solid #006F37; border-radius:4px; color:white;
 background-color:#006F37; margin-left:0px" onclick="saveData(1)">
&nbsp;&nbsp;&nbsp; Save Data &nbsp;&nbsp;&nbsp;</button>  
</td>
</tr><!--end of tr --> 
</table> 
</form> 
<?php
$j=0;
  
echo "<div class='table-responsive'>
<table border=1 style='width:95%;'>
<thead>
<tr>
<th>No</th>
<th>Date</th>
<th>Phone Sent</th>
<th>Amount Received</th>
<th> </th>
  
</tr>
</thead>
<tbody>";
$search_query= mysqli_query($conn,"SELECT * FROM uknown  where userrec_id='$user_id' and bossrec_id='$boss_id' and known=0");
while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;

$uknown_id=$returned_result["uknown_id"];
$phone=$returned_result["tel"];
$amount =number_format($returned_result["paid_amount"]);
$date = $returned_result["rec_date"]; 
$d=date("d-m-Y", strtotime($date));
 
 
echo "<tr>
<td><font size=3> $j  </font></td>
<td><font size=3> $d</font></td>
<td><font size=3> $phone</font></td>
<td><font size=3> $amount</font></td>
<td><a href=search_client_unknown_loan.php?unknown_id=$uknown_id><font size=4 color=green><b>Source Known</b></font></td>";
}

echo "</tr>";
echo "</tbody></table>";
?>  
</div>
</div>
</div>
<?php include('footer.php');?>

</div>
</main>
</body> 
</html>


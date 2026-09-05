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

xmlhttp.open("GET","user_connector.php?shortage_recovery=1&boss_id="+boss_id+"&user_id="+user_id+"&b_date="+b_date+"&amo="+amo,true);
xmlhttp.send();
}
}
</script>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> <?php include ('summary.php') ?>
<hr> 
<div id="main_heading"> <b>
Shortage Recovery</b> </div>    
 
 <div id="main_container" style="height:420px">
<div id="main_body" style="height:390px">    
 
<div id="list_cont"> </div>
                              
<?php
echo"
<p align=center> <b>Shortages Entered</b></p>
<br> 
<table border=1 style='width:80%;'>
<thead>
<tr>
<th>No</th>
<th>Date</th>
<th>Amount</th>  
<th></th>  
</tr>
</thead>
<tbody>";
$j=0;
$search_query= mysqli_query($conn,"SELECT * FROM shortage  where userrec_id='$user_id' and bossrec_id='$boss_id' and recovered=0");
  
while($returned_result = mysqli_fetch_assoc($search_query)){
$j++; 
$short_id =$returned_result["short_id"];
$amount =number_format($returned_result["paid_amount"]);
$date = $returned_result["rec_date"]; 
$d=date("d-m-Y", strtotime($date));
 
echo "<tr>
<td><font size=3> $short_id </font></td>
<td><font size=3> $d</font></td>
<td><font size=3> $amount</font></td>
<td><a href=user_connector.php?shortage_recovery=$short_id><font size=4 color=green><b>Recover</b></font></td>";
}

echo "</tr>";
echo "</tbody></table>";
?>  
</div>
</div>
  
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>


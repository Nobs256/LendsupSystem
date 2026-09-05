<?php
$sa="";
include('header_user.php');

$unknown_id=0;
if(isset($_GET['success'])){
$balance=$_GET['remain_balance'];
$sa = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 550px'>
<font color=white>Data is Successfully Entered!! Balance is $balance </font>
<a href='search_client_pay_loan.php?reload=1' style='color:white; margin-left:90px;''>X</a>
</div>";
}

if(isset($_GET['balance'])){
$sa = "<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 500px'>
<font color=white>The amount Entered is more than the Balance!!</font>
<a href='search_client_pay_loan.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</div>";
}

//get the client ID
if(isset($_REQUEST['client_id']))
{   
$client_id = $_REQUEST['client_id'];
$result = mysqli_fetch_assoc(mysqli_query($conn,"select ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
from clients where client_id='$client_id'"));     
$name= $result['firstname']." ".$result['lastname'];
$phone = $result['phone'];

$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients_with_loan 
where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'"));     
$daily_p =number_format($result['daily_p']);    
$loan_no = $result['loan_no'];
$heading="";
$unknown_phone="";

}

if(isset($_REQUEST['unknown_id'])){
$unknown_id = $_REQUEST['unknown_id'];
$unknown_amount=0;
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from uknown
where uknown_id='$unknown_id'"));     
$unknown_amount =number_format($result['paid_amount']); 
$unknown_phone = $result['tel']; 
}
if($unknown_id>0){
$daily_p=$unknown_amount;
 
}
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
var unknown_id=document.getElementById("unknown_id").value;
var boss_id=document.getElementById("boss_id").value;
var user_id=document.getElementById("user_id").value;
var client_id=document.getElementById("client_id").value;
var b_date=document.getElementById("b_date").value;
var loan_no=document.getElementById("loan_no").value;
var tel=document.getElementById("tel").value;
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

xmlhttp.open("GET","pay_using_unknown_connector.php?ref=1&boss_id="+boss_id+"&unknown_id="+unknown_id+"&user_id="+user_id+"&client_id="+client_id+"&b_date="+b_date+"&loan_no="+loan_no+"&tel="+tel+"&amo="+amo,true);
xmlhttp.send();
}
}

</script>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> <?php include ('summary.php') ?>
<hr> 
<div id="main_heading"> <b>
LOAN PAYMENT USING UNKNOWN:</b> <?php echo "$heading $name ($phone)";  

?>  
 </div>  
<br>
</div> 
<div id="main_container" style="height:420px">
<div id="main_body" style="height:380px">   
<br>
<div id="list_cont"> </div><?php echo $sa; ?>
<br>                               
<table style="width:650px;" border="0">

<form method="GET">
<input type="hidden" id="unknown_id" value="<?php echo $unknown_id; ?>">
<input type="hidden" id="boss_id" value="<?php echo $boss_id; ?>">
<input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
<input type="hidden" id="client_id" value="<?php echo $client_id; ?>">
<input type="hidden" id="loan_no" value="<?php echo $loan_no; ?>">
<input type="hidden" id="tel" value="<?php echo $unknown_phone; ?>">
<tr>
<td width="70px"> 
Date: 
</td>
<td width="210px">
<input type="date" id="b_date" class="input is-success" style="width:190px; border: 1px solid #006F37" 
required><br><br>
</td>
<td width="100px"> 
Amount:
</td>
<td width="300px">
<input type="text" value="<?php echo $daily_p ; ?>" class="input is-success"   id="uamo" style="width:150px; border: 1px solid #006F37" 
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
</div>
</div>
 
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>


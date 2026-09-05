<?php
include('header_user.php');
$sa="";

?>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> <?php include ('summary.php'); 

if(isset($_POST['field_clients_mom'])){   
$officer_id = $_POST['officer_id'];
$pre_date = $_POST['p_date'];

include('conn.php');
$returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and officer_id='$officer_id' "));     
$name = $returned_result["firstname"]. " ". $returned_result["lastname"];
 
}
if(isset($_GET['officer_id'])){   
$officer_id = $_GET['officer_id'];
include('conn.php');
$returned_result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM officers where boss_id='$boss_id' and officer_id='$officer_id' "));     
$name = $returned_result["firstname"]. " ". $returned_result["lastname"];
 
}
?>
 
<div id="main_heading"> <b>Pay for Field Clients that Sent Money on MOM - </b> <?php echo $name; ?>
</div>     
 
<div id="main_container">
<div id="main_body">   
 
  <?php echo $s;?>                            
<table border="0" width="70%">
<form  method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<table style="width:730px;" border="0">
<tr><td width="150px">
Enter First Name: 
</td><td width="400px">
<input type="hidden" name="officer_id" value="<?php echo $officer_id; ?>" >
<input type="text" name="firstname" class="input is-success" style="width:320px; border: 1px solid #006F37" required >
<button type="submit" name="submit" class="button is-primary" style="background-color:#006F37">
&nbsp;&nbsp;&nbsp;Search&nbsp;&nbsp;&nbsp;</button><div>
</form></td> </tr></table>    
<br>  
<?php
if(isset($_GET['firstname'])){ 
$q = $_GET['firstname'];
$officer_id = $_GET['officer_id'];
$j=0;
$date=date('Y-m-d');

$select_client = mysqli_query($conn,"SELECT * FROM clients where firstname='$q'");
$search_query= mysqli_query($conn,"SELECT client_id, ucase(firstname) as firstname, ucase(lastname) as lastname, phone,
 nid, b_location FROM clients  where firstname Like '%$q%' and users_id='$user_id' and bosses_id='$boss_id'");
  
echo "<table  class='table table-responsive table-hover'>
<thead>
<tr>
<th>No</th>
<th>Names</th>
<th>Phone</th>
<th>National ID</th>
<th>Balance</th>
<th>Action</th>
</tr>
</thead>
<tbody>";

while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;

$client_id=$returned_result["client_id"];
$firstname = $returned_result["firstname"]." ". $returned_result["lastname"];
$phone  = $returned_result["phone"];
$nid  = $returned_result["nid"];
$b_location = $returned_result["b_location"];

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from mobile
where clientmo_id='$client_id' and usermo_id='$user_id' and bossmo_id='$boss_id' and mom_date='$date'"));
$mom_use = number_format($results["amount_mo"]);

$results = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients_with_loan
where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'"));
$debt = number_format($results["debt"]);

 if($debt==0){
 $allowed="<a href='#'><font color=red>Cleared the Loan</font></a>";
 }
 else if($mom_use>0){
 $allowed="<a href='#'><font color=red>Already Paid, <a href='pay_loan_mom.php?client_id=$client_id'><font size=3 color=green>Pay More</font></a> </font></a>";
 }
 else{
 $allowed="<a href='pay_for_field_clients_mom.php?client_id=$client_id&officer_id=$officer_id'><font size=3 color=green>Pay With MOM</font></a>";
 
 }

echo "<tr>
<td><font size=3> $j  </font></td>
<td><font size=3> $firstname  </font></td>
<td><font size=3> $phone</font></td>
<td><font size=3> $nid</font></td>
<td><font size=3> $debt</font></td>
<td>$allowed</td>";
}

echo "</tr>";
}
echo "</tbody></table>";
mysqli_close($conn);
if (isset($_GET['client_id'])) {
$client_id=$_GET['client_id'];
$officer_id=$_GET['officer_id'];
include('conn.php');

if(isset($_GET['success'])){
  
$sa = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 500px'>
<font color=white>Data is Successfully Entered!!   </font>
<a href='pay_for_field_clients_mom.php?officer_id=$officer_id' 
style='color:white; margin-left:150px;'>X</a>
</div>";
}

if(isset($_GET['balance'])){
$sa = "<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 500px'>
<font color=white>The amount Entered is more than the Balance!!</font>
<a href='pay_for_field_clients_mom.php?officer_id=$officer_id&client_id=$client_id'
style='color:white; margin-left:100px;'>X</a>
</div>";
}
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients_with_loan 
where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'"));     
$daily_p =number_format($result['daily_p']);    
$loan_no = $result['loan_no'];

$result = mysqli_fetch_assoc(mysqli_query($conn,"select ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
from clients where client_id='$client_id'"));     
$name= $result['firstname']." ".$result['lastname'];
$phone = $result['phone'];

$curr_date=date('Y-m-d');
$pre_date = date('Y-m-d', strtotime("$curr_date -1 day"));

$amo=0;
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
//===============Paid
function separator2(num) {
var numb=document.getElementById("amount_paid2").value;

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

document.getElementById("amount_paid").value=output;
//document.getElementById("numo").innerHTML=output;
}


function validateMe2(str)
{
var number=str.replace(",","");
if(number.length<=7){
document.getElementById("amount_paid2").value=number;
}

else{
num=document.getElementById("amount_paid").value;
var number=num.replace(",","");
document.getElementById("amount_paid2").value=number;
}

var feed=separator2(1);
}

function saveData(str)
{
var boss_id=document.getElementById("boss_id").value;
var user_id=document.getElementById("user_id").value;
var client_id=document.getElementById("client_id").value;
var officer_id=document.getElementById("officer_id").value;
var numb=document.getElementById("numb").value;
var amount_paid=document.getElementById("amount_paid").value;
var p_date=document.getElementById("p_date").value;
var loan_no=document.getElementById("loan_no").value;
var amo=document.getElementById("uamo").value;
if(amount_paid.length>0 && amount_paid.length>=3 && p_date.length>1)
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

xmlhttp.open("GET","pay_for_officers_connector.php?mom=1&boss_id="+boss_id+"&user_id="+user_id+"&officer_id="+officer_id+"&client_id="+client_id+"&amount_paid="+amount_paid+"&numb="+numb+"&loan_no="+loan_no+"&p_date="+p_date+"&amo="+amo,true);
xmlhttp.send();
}
}
</script>
<br>
<div id="list_cont"> </div><?php echo $sa; ?>
<br>
You are paying for <?php echo $name;?> on MOM
 <hr>                             
<table style="width:500px;" border="0">

<form method="GET">
<input type="hidden" id="boss_id" value="<?php echo $boss_id; ?>">
<input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
<input type="hidden" id="client_id" value="<?php echo $client_id; ?>">
<input type="hidden" id="loan_no" value="<?php echo $loan_no; ?>">
<input type="hidden" id="officer_id" value="<?php echo $officer_id; ?>">
<input type="hidden" id="uamo" value="<?php echo $amo; ?>">
<input type="hidden" id="uamo2" value="<?php echo $amo; ?>">

<tr>
<td> 
Date: 
</td>
<td>
<input type="date"   id="p_date" class="input is-success" style="width:230px; border: 1px solid #006F37" required>   
<br><br> 
</td>
</tr>

<tr>
<td> 
Sent On:
</td>
<td width="250px">
<div class="select is-success">
<select  id="numb"  style="width:230px; border: 1px solid #006F37" required>
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
</div></td></tr>
<tr>
<td> 
Paid: 
</td>
<td>
<input type="text" value="<?php echo $daily_p ; ?>" id="amount_paid" class="input is-success" style="width:230px; border: 1px solid #006F37" onkeyup="validateMe2(this.value)" onkeypress="return isNumberKey(event)" 
required>   
<input type="hidden" class="form-control" placeholder="Enter amount" id="amount_paid2" style="font-size: 10pt">      
</td></tr>
<tr>
<td>
</td>  
<td><br>
<button type="button" class="button is-primary" style="border: 1px solid #006F37; border-radius:4px; color:white;
 background-color:#006F37; margin-left:0px" onclick="saveData(1)">
&nbsp;&nbsp;&nbsp; Save Data &nbsp;&nbsp;&nbsp;</button>  
</td>
</tr><!--end of tr --> 
</table> 
</form>
<?php 
} 

?>   
</div>
</div>
<div> 
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>
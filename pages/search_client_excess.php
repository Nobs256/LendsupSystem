<?php
$s="";
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

/* Excess/Shortage Popup */
.popup_overlay {
display: none; position: fixed; z-index: 1000; left: 0; top: 0;
width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);
}
.popup_box {
background-color: #EAEAEA; width: 92%; max-width: 760px; margin: 60px auto;
border: 3px solid #006F37; border-radius: 5px; padding: 15px;
box-sizing: border-box;
}
.popup_box input, .popup_box select {
box-sizing: border-box;
max-width: 100%;
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

function openExcessForm(cid, cname)
{
document.getElementById("popup_client_id").value=cid;
document.getElementById("popup_client_name").innerHTML=cname;
document.getElementById("excess_msg").innerHTML="";
document.getElementById("b_date").value="<?php echo date('Y-m-d'); ?>";
document.getElementById("excess_overlay").style.display="block";
}

function closeExcessForm()
{
document.getElementById("excess_overlay").style.display="none";
}

function saveExcessData(str)
{
var boss_id=document.getElementById("boss_id").value;
var user_id=document.getElementById("user_id").value;
var b_date=document.getElementById("b_date").value;
var excess=document.getElementById("excess").value;
var officer_id=document.getElementById("officer_id").value;
var client_id=document.getElementById("popup_client_id").value;
var amo=document.getElementById("uamo").value;
if(b_date.length>0 && amo.length>=3 && excess.length>3 && officer_id.length>0 && client_id>0)
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
document.getElementById("excess_msg").innerHTML=xmlhttp.responseText.trim();
}

else
{
document.getElementById("excess_msg").innerHTML="Please wait....";
}
}

xmlhttp.open("GET","user_connector.php?excess=1&boss_id="+boss_id+"&user_id="+user_id+"&officer_id="+officer_id+"&excess="+excess+"&b_date="+b_date+"&amo="+amo+"&client_id="+client_id,true);
xmlhttp.send();
}
else{
alert("Please Select Officer, Excess/Shortage and Enter the Amount First!");
}
}

</script>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> <?php include ('summary.php') ?>
<hr>
<div id="main_heading"> <b>Search a Client to Add Excess/Shortage</b>
<a href="view_excess_shortage.php" style="border: 1px solid green; margin-left:600px; border-radius:1px; font-size:17px; color: green; background-color:white; height:30px; width:200px"
class="button is-default">View Excess/Shortage</a>
<a href="add_excess_short.php" style="border: 1px solid green; margin-left:10px; border-radius:1px; font-size:17px; color: green; background-color:white; height:30px; width:170px"
class="button is-default">Add Without Client</a>
</div>

<div id="main_container">
<div id="main_body">
<br>
  <?php echo $s;?>
  <br><br>
<table border="0" width="70%">
<form  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<table style="width:730px;" border="0">
<tr><td width="200px">
Enter Name or Phone:
</td><td width="400px">
<input type="text" name="firstname" class="input is-success" style="width:320px; border: 1px solid #006F37" required >
<button type="submit" name="submit" class="button is-primary" style="background-color:#006F37">
&nbsp;&nbsp;&nbsp;Search&nbsp;&nbsp;&nbsp;</button><div>
</form></td> </tr></table>
<br>
<?php
if(isset($_POST['firstname'])){
$q = $_POST['firstname'];
$j=0;
$search_query= mysqli_query($conn,"SELECT client_id, ucase(firstname) as firstname, ucase(lastname) as lastname, phone, b_location FROM clients
where (firstname Like '%$q%' or lastname Like '%$q%' or phone Like '%$q%') and users_id='$user_id' and bosses_id='$boss_id'");

echo "<table  class='table table-responsive table-hover'>
<thead>
<tr>
<th>No</th>
<th>Names</th>
<th>Phone</th>
<th>Loan Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>";

while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;
$client_id=$returned_result["client_id"];
$firstname = $returned_result["firstname"]. " ". $returned_result["lastname"];
$phone  = $returned_result["phone"];
$b_location = $returned_result["b_location"];

//check if the client has an active loan
$loan_row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT loan_no, debt FROM clients_with_loan
WHERE clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id' and debt>0 LIMIT 1"));
if($loan_row){
$loan_status = "Loan No. ".$loan_row['loan_no']." - Debt: ".number_format($loan_row['debt']);
}else{
$loan_status = "No Active Loan";
}

$js_name = htmlspecialchars(addslashes($firstname), ENT_QUOTES, 'UTF-8');

echo "<tr>
<td><font size=3> $j  </font></td>
<td><font size=3> $firstname  </font></td>
<td><font size=3> $phone</font></td>
<td><font size=3> $loan_status </font></td>
<td><button type='button' class='button is-primary' style='background-color:#006F37; cursor:pointer' onclick=\"openExcessForm($client_id, '$js_name')\"><font size=3 color=white><b>Add Excess</b></font></button></td>";
}

echo "</tr>";
}
echo "</tbody></table>";
?>

<!-- Excess/Shortage Popup Form -->
<div id="excess_overlay" class="popup_overlay">
<div class="popup_box">
<div style="text-align:right;">
<a href="#" onclick="closeExcessForm()" style="color:red; font-size:20px;"><b>X</b></a>
</div>
<div id="main_heading"> <b>ADD EXCESS/SHORTAGE FOR: <span id="popup_client_name" style="color:#006F37"></span></b></div>
<br>
<input type="hidden" id="boss_id" value="<?php echo $boss_id; ?>">
<input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
<input type="hidden" id="popup_client_id" value="0">

<div style="max-width:100%; width:420px; margin:0 auto;">

<!-- Field Officer -->
<div style="margin-bottom:15px;">
	<label style="display:block; margin-bottom:5px; font-weight: bold;">Field Officer:</label>
	<div class="select is-success" style="width:100%;">
		<select id="officer_id" style="width:100%; border: 1px solid #006F37; height:35px" required>
			<option value="">Select Officer</option>
			<?php
			$officers = mysqli_query($conn, "SELECT officer_id, firstname, lastname FROM officers WHERE boss_id='$boss_id' AND branch='$bra' AND active=1 ORDER BY firstname, lastname");
			while($officer = mysqli_fetch_assoc($officers)){
			$officer_name = $officer['firstname'].' '.$officer['lastname'];
			echo "<option value='".$officer['officer_id']."'>".htmlspecialchars(strtoupper($officer_name), ENT_QUOTES, 'UTF-8')."</option>";
			}
			?>
		</select>
	</div>
</div>

<!-- Date -->
<div style="margin-bottom:15px;">
	<label style="display:block; margin-bottom:5px; font-weight: bold;">Date:</label>
	<input type="date" id="b_date" class="input is-success" style="width:100%; box-sizing:border-box; border: 1px solid #006F37; height:35px" required>
</div>

<!-- Excess/Shortage -->
<div style="margin-bottom:15px;">
	<label style="display:block; margin-bottom:5px; font-weight: bold;">Excess/Shortage:</label>
	<div class="select is-success" style="width:100%;">
		<select id="excess" style="width:100%; border: 1px solid #006F37; height:35px" required>
			<option></option>
			<option>Excess</option>
			<option>Shortage</option>
		</select>
	</div>
</div>

<!-- Amount -->
<div style="margin-bottom:15px;">
	<label style="display:block; margin-bottom:5px; font-weight: bold;">Amount:</label>
	<input type="text" class="input is-success" id="uamo" style="width:100%; box-sizing:border-box; border: 1px solid #006F37; height:35px" onkeyup="validateMe(this.value)" onkeypress="return isNumberKey(event)" required>
	<input type="hidden" class="form-control" placeholder="Enter amount" id="uamo2" style="font-size: 10pt">
</div>

<!-- Save Button -->
<div style="text-align:center;">
	<button type="button" class="button is-primary" style="border: 1px solid #006F37; border-radius:4px; color:white; background-color:#006F37; cursor:pointer; height: 40px; width:100%;" onclick="saveExcessData(1)">
	&nbsp;&nbsp;&nbsp; Save Data &nbsp;&nbsp;&nbsp;
	</button>
</div>

</div>

<br>
<div id="excess_msg"></div>
</div>
</div>

</div>
</div>
</div>
<?php
include('footer.php');
?>
</div>
</main>
</body>
</html>
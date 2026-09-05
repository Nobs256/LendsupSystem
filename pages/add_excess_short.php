<?php
$sa="";
include('header_user.php');

if(isset($_GET['success'])){
$sa = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 500px'>
<font color=white>Data is Successfully Entered!!</font>
<a href='user_homepage.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</div>";
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
var b_date=document.getElementById("b_date").value;
var excess=document.getElementById("excess").value;
var officer_id=document.getElementById("officer_id").value;
var amo=document.getElementById("uamo").value;
if(b_date.length>0 && amo.length>=3 && excess.length>3 && officer_id.length>0)
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

xmlhttp.open("GET","user_connector.php?excess=1&boss_id="+boss_id+"&user_id="+user_id+"&officer_id="+officer_id+"&excess="+excess+"&b_date="+b_date+"&amo="+amo,true);
xmlhttp.send();
}
}

</script>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> <?php include ('summary.php') ?>
<hr> 
<div id="main_heading"> <b>
EXCESS/SHORTAGE</b> 
<a href="shortage_recovery.php" style="border: 1px solid green; margin-left:700px; border-radius:1px; font-size:17px; color: green; background-color:white; height:30px; width:150px" 
class="button is-default">Recover Shortage</a></td> 

</div>     

<br>
<div id="main_container" style="height:420px">
<div id="main_body" style="height:390px">   
<br>
<div id="list_cont"> </div><?php echo $sa; ?>
<br>                               
<form method="GET">
	<input type="hidden" id="boss_id" value="<?php echo $boss_id; ?>">
	<input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
	
	<!-- Main Form Container -->
	<div style="display: flex; flex-direction: column; gap: 15px; max-width: 1100px; margin-bottom: 20px;">
		
		<!-- FIRST ROW -->
		<div style="display: flex; flex-wrap: nowrap; align-items: center; gap: 25px; white-space: nowrap;">
			
			<!-- Field Officer -->
			<div style="display: flex; align-items: center; gap: 10px;">
				<label style="font-weight: normal;">Field Officer:</label>
				<div class="select is-success">
					<select id="officer_id" style="width:200px; border: 1px solid #006F37; height:35px" required>
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
			<div style="display: flex; align-items: center; gap: 10px;">
				<label style="font-weight: normal;">Date:</label>
				<input type="date" id="b_date" class="input is-success" style="width:190px; border: 1px solid #006F37" required>
			</div>
			
			<!-- Excess/Shortage -->
			<div style="display: flex; align-items: center; gap: 10px;">
				<label style="font-weight: normal;">Excess/Shortage:</label>
				<div class="select is-success">  
					<select id="excess" style="width:200px; border: 1px solid #006F37; height:35px" required> 
						<option></option>
						<option>Excess</option>
						<option>Shortage</option>
					</select>
				</div> 
			</div>
			
		</div>
		
		<!-- SECOND ROW (Amount and Save Button) -->
		<div style="display: flex; flex-wrap: wrap; align-items: center; gap: 25px; margin-top: 5px;">
			
			<!-- Amount -->
			<div style="display: flex; align-items: center; gap: 10px;">
				<label style="font-weight: normal;">Amount:</label>
				<input type="text" class="input is-success" id="uamo" style="width:150px; border: 1px solid #006F37" onkeyup="validateMe(this.value)" onkeypress="return isNumberKey(event)" required> 
				<input type="hidden" class="form-control" placeholder="Enter amount" id="uamo2" style="font-size: 10pt">
			</div>
			
			<!-- Save Button -->
			<div>
				<button type="button" class="button is-primary" style="border: 1px solid #006F37; border-radius:4px; color:white; background-color:#006F37; cursor:pointer; height: 35px;" onclick="saveData(1)">
				&nbsp;&nbsp;&nbsp; Save Data &nbsp;&nbsp;&nbsp;
				</button>  
			</div>
			
		</div>
		
	</div>
</form>

<?php
$j=0;
$month=date('m');
$search_query= mysqli_query($conn,"SELECT * FROM excess_short  where userrec_id='$user_id' and bossrec_id='$boss_id' order by rec_date Desc");
  
echo "<div class='table-responsive'>
<table border=1 style='width:80%;'>
<thead>
<tr>
<th>No</th>
<th>Date</th>
<th>Field Officer</th>
<th>Excess/Shortage</th>
<th>Amount</th>
 
</tr>
</thead>
<tbody>";

while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;
 
$amount =number_format($returned_result["paid_amount"]);
$date = $returned_result["rec_date"]; 
$excess_short = $returned_result["excess_short"]; 
$officer_id = $returned_result["officer_id"];
$officer_name = 'Not assigned';
if($officer_id){
	$officer_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT firstname, lastname FROM officers WHERE officer_id='$officer_id' AND boss_id='$boss_id'"));
	if($officer_result){
		$officer_name = $officer_result['firstname'].' '.$officer_result['lastname'];
	}
}
$d=date("d-m-Y", strtotime($date));
 
 
echo "<tr>
<td><font size=3> $j  </font></td>
<td><font size=3> $d</font></td>
<td><font size=3> $officer_name</font></td>
<td><font size=3> $excess_short</font></td>
<td><font size=3> $amount</font></td>";
echo "</tr>";
}


$search_query= mysqli_query($conn,"SELECT * FROM shortage  where userrec_id='$user_id' and bossrec_id='$boss_id'");
while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;
 
$amount =number_format($returned_result["paid_amount"]);
$date = $returned_result["rec_date"]; 
$excess_short ='Shortage';
$officer_id = $returned_result["officer_id"];
$officer_name = 'Not assigned';
if($officer_id){
	$officer_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT firstname, lastname FROM officers WHERE officer_id='$officer_id' AND boss_id='$boss_id'"));
	if($officer_result){
		$officer_name = $officer_result['firstname'].' '.$officer_result['lastname'];
	}
}
$d=date("d-m-Y", strtotime($date));

echo "<tr>
<td><font size=3> $j  </font></td>
<td><font size=3> $d</font></td>
<td><font size=3> $officer_name</font></td>
<td><font size=3> $excess_short</font></td>
<td><font size=3> $amount</font></td>";
echo "</tr>";
}
echo "</tbody></table>";
?>  
</div>
</div>
</div> 
 <?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>


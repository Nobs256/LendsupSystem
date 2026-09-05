<?php
include('header_user.php');
$curr_month=date('m');
?>

<script type="text/javascript">
function isNumberKey(evt)
{var charCode=(evt.which)?evt.which:event.keyCode
if(!(charCode>=48&&charCode<=57||charCode==8||charCode==46))
return false;return true;}
 

function saveData(str)
{
var boss_id=document.getElementById("boss_id").value;
var user_id=document.getElementById("user_id").value;
var fname=document.getElementById("fname").value;
var lname=document.getElementById("lname").value;
var sex=document.getElementById("sex").value;
var phone=document.getElementById("phone").value;
var place_r=document.getElementById("place_r").value;
var business=document.getElementById("business").value;
var b_location=document.getElementById("b_location").value;

 
if(fname.length>0 && lname.length>0 && phone.length==10)
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

xmlhttp.open("GET","user_connector.php?add_clients2=1&boss_id="+boss_id+"&user_id="+user_id+"&fname="+fname+"&lname="+lname+"&sex="+sex+"&phone="+phone+"&place_r="+place_r+"&business="+business+"&b_location="+b_location,true);
xmlhttp.send();
}
}

</script>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> <?php include ('summary.php') ?>
<hr> 
<div id="main_heading"> <b>Registration of a New Clients</b>
 <font color="#EAEAEA">-------------------</font>
 <a href="view_clients.php"><font color="green">View Registered Clients</font></a> </div>   

<br>
<div id="main_container" style="height:420px">
<div id="main_body" style="height:390px">   
<br>
<div id="list_cont"> </div>
<br>                               
<form method="GET">
<input type="hidden" id="boss_id" value="<?php echo $boss_id; ?>">
<input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
<input type="hidden" id="place_r" value="xxxxxxxxxxxxxx">
<input type="hidden" id="business" value="xxxxxxxxxxxxxx">
 
<table style="width:900px;" border="0">
<tr>
<td width="100px"> 
First Name: 
</td>
<td width="240px">
<input type="text"   id="fname" class="input is-success" style="width:270px; border: 1px solid #006F37" 
required><br><br>
</td>

<td width="100px"> 
Last Name: 
</td>
<td width="180px">
<input type="text"   id="lname" class="input is-success" style="width:270px; border: 1px solid #006F37"
required><br><br>       
</td>
</tr><!--end of tr -->

<tr>
<td> 
Sex: 
</td>
<td>
<div class="select is-success">
<select  id="sex"  style="width:270px; border: 1px solid #006F37" required>
<option></option>
<option value="Male">Male</option>
<option value="Female">Female</option>            
</select> <br><br>
</div>          
</td> 

<td> 
Phone No: 
</td>
<td>
<input type="tel" maxlength = "10" id="phone" class="input is-success" style="width:270px; border: 1px solid #006F37" 
required><br><br>          
</td>       
</tr>
<!--
<tr>
<td>      
Place of Residence:  
</td>
<td>
<input type="text"   id="place_r" class="input is-success" style="width:270px; border: 1px solid #006F37"
required><br><br>         
</td>        
 
<td>      
Business Name:
</td>
<td>
<input type="text"  id="business" class="input is-success" style="width:270px; border: 1px solid #006F37"
required><br><br>
</td>
</tr><!--end of tr -->

<tr>     
<td>      
Business Location:
</td>
<td>
<div class="select is-success">
<?php
echo "
<select  id='b_location'  style='width:270px; border: 1px solid #006F37' required> 
<option value='nolocation'>No Location</option>
 ";
$comb = mysqli_query($conn,"SELECT * from location where userloc_id='$user_id' and bossloc_id='$boss_id'");

while($select_comb = mysqli_fetch_array($comb)){
$location=$select_comb['location']; 
echo "<option value= $location>".strtoupper($location)." </option>"; 
}
echo" 
</select>";?>
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
</div>
<?php include('footer.php');?>

</div>
</main>
</body> 
</html>


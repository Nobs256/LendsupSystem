<?php
if(isset($_REQUEST['client_update']))
{	  
$client_id = $_REQUEST['client_update'];
 
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from clients where client_id ='$client_id'"));
$client_id = $result["client_id"];
$firstname = $result['firstname'];
$lastname = $result['lastname'];
$sex = $result['sex'];
$phone= $result['phone'];
$dob = $result['dob'];
$nid = $result['nid']; 
$place_r= $result['place_r'];
$marital= $result['marital'];
$business = $result['business'];
$b_location = $result['b_location'];
?>
<div class="modal fade" id="client_update" role="dialog">
<div class="modal-dialog modal-lg">
<!-- Modal content-->
<div class="modal-content" style="margin-left:350px;  margin-top:10px; width:750px">
<div class="modal-header" style="background-color:#006F37;color:white;height:50px">
<font color="#006F37">----------------------------------------------</font>
<p><font color="#006F37">--------------------------------------------------</font>
CLIENT UPDATE
<font color="#006F37">---------</font>
<a href="view_clients.php"> <font color="white" size="4">X</font> </a>
</div>
<div class="modal-body" style="overflow-y:scroll;  height:auto; background-color:white; border: 3px solid #006F37">
<div id="student_details">
<br>
<div class="field is-horizontal">
<div style="width:700px"> 

<form method="post" action="user_connector.php">
<input type="hidden" name="boss_id" value="<?php echo $boss_id;?>">
<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
<input type="hidden"  value="<?php echo $client_id;?>" name="client_id">
<input type="hidden"  value="<?php echo $nid;?>" name="nid">
 

<table style="width:485px; font-size:12px; margin-left:30px">
<tr><td width="180px">        
&nbsp;&nbsp;FIRST NAME: 
</td><td width="300px"> 
<input type="text" name="firstname" value="<?php echo $firstname;?>" 
required>

</td>
</tr><tr><td>
&nbsp;&nbsp;LAST NAME: 
</td><td>
<input type="text" name="lastname" value="<?php echo $lastname;?>" 
required>

</td>
</tr><tr><td>
&nbsp;&nbsp;SEX:
</td><td> 
<select name="sex" required>
<option><?php echo $sex;?></option>
<option>Male</option>
<option>Female</option>
</select>
</td>
</tr>
<tr><td>
&nbsp;&nbsp;MARITAL STATUS:
</td><td> 
<select name="marital" required>
<option><?php echo $marital;?></option>
<option>Single</option>
<option>Married</option>
<option>Divorced</option>
</select>
</td>
</tr>
<tr><td>
&nbsp;&nbsp;YEAR OF BIRTH: 
</td><td>        
<input type="text" name="dob" value="<?php echo $dob;?>" placeholder="Enter Only Year" maxlength="4" pattern="^[0-9]+" title="User Numbers only" 
required>

</td>
</tr>

<tr><td>
&nbsp;&nbsp;PHONE NO:</td><td>
<input type="tel" maxlength = "10"  name="phone"  value="<?php echo $phone;?>"pattern="^[0-9]+" title="User Numbers only" required>

</td>
</tr>
<tr>
<td>
<div class="form-group"> 
&nbsp;&nbsp;PLACE OF RESIDENCE: 
</td><td>
<div class="col-sm-6"> 
<input type="text" name="place_r" value="<?php echo $place_r;?>">

</td>
</tr><tr><td>
&nbsp;&nbsp;BUSINESS: 
</td><td>
<div class="col-sm-6"> 
<input type="text" name="business" value="<?php echo $business;?>" >

</td>
</tr><tr><td>
&nbsp;&nbsp;BUSINESS LOCATION: 
</td><td>
<input type="text" name="b_location" value="<?php echo $b_location;?>" >

</td>
</tr> 
</table>
<label class="label" style="margin-left: 340px">
<button type="submit" name="update_clients" class="button is-primary" style="border: 1px solid #006F37; border-radius:4px; color:white; background-color:#006F37">
&nbsp;&nbsp;&nbsp; Update a Client&nbsp;&nbsp;&nbsp;</button>
</label>
</form>   
</div>               
</div>
</div>
<br>
</div>
</div>
<div class="modal-footer">    
</div>
</div>
</div>
</div>
<?php
}
//===========SMS

//=================SMS MODEL
if(isset($_REQUEST['tr_sms']))
{
$year=date('Y');
$client_id = $_REQUEST['tr_sms'];
$result = mysqli_fetch_assoc(mysqli_query($conn,"select * from teacher where client_id='$client_id' "));

$firstname = strtoupper($result['firstname']);
$lastname = strtoupper($result['lastname']);
$tel = $result['phone'];
?> 

<div class="modal fade" id="tr_sms" role="dialog">
<div class="modal-dialog modal-lg">
<!-- Modal content-->
<div class="modal-content" style="margin-left:500px;  margin-top:100px; width:700px">
<div class="modal-header" style="background-color:#006F37;color:white;height:50px">
<font color="#006F37">----------------------------------------------</font>
<p><font color="#006F37">----------------------------------------------</font>
 MESSAGE TO ONE TEACHER
<font color="#006F37">-------------------</font>

<a href="list_of_trs.php"> <font color="white" size="4">X</font> </a>
 
 
</div>
<div class="modal-body" style="overflow-y:scroll;  height:400px; background-color:white; border: 5px solid #006F37">
<div id="student_details">
<p align="center">
SMS WILL BE SENT TO TR. <?php echo $firstname.' '.$lastname; ?> (<?php  echo $tel;?>)<br>
</p>
<br><br>
<div style="width:400px; height:400px; margin-left:30px"> 
<form method="POST" action="notifier.php">
<b>Compose Message</b> <br><br>
<input type="hidden" value="<?php echo $client_id;?>" name="client_id">
<input type="hidden" name="sch_id" value="<?php echo $sch_id;?>">
<input type="hidden" name="phone" value="<?php echo $tel;?>">
<textarea name='msg' cols="5" rows="5" class="textarea is-success" 
placeholder='Type your message here!' id='msg2' 
required onkeyup="return wordCounter2(event)"></textarea>
<span id="numofwords2" style='font-weight:bold;color:red'></span> 
<br><br>
<button type="submit" name="send3" class="button is-default" 
style="border: 1px solid #006F37; border-radius:4px; color:white; background-color:#006F37">
Send SMS</button>             

</form>
<!-- End of A table for SMS -->
 
</div>
</div>
</div>
<div class="modal-footer">    
</div>
</div>
</div>
</div>
<?php
}


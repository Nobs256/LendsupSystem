<?php 
if(isset($_POST['reload']))
{
$sec=$_POST['re'];
header("refresh: $sec");
}

$success_reg="";

  
if(isset($_GET['messages'])){
$j = $_GET['messages'];
if ($j>0){
$success_reg = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:0px; padding:10px; width: 500px'>
<font color=white>SMS is Successfully Sent to $j Clients!!</font>
<a href='sms_to_clients.php?reload=1' style='color:white; margin-left:60px;''>X</a>
</div>";
}
}
include('header_user.php'); ?>
<main class="column main" style="background-color:#EAEAEA;">
<?php include ('summary.php') ?>

<div id="main_heading"> <b>Send Messages to   Clients</b>
<?php echo $success_reg;?>
</div>     
 
<div id="main_container">
<div id="main_body">   
	<br><br><br>
<div class="field is-horizontal" style="margin-left:40px">

<form method="POST" action="notifier_all.php">
<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
<input type="hidden" name="boss_id" value="<?php echo $boss_id;?>">
<table><tr><td>
Category:
</td>
<td>
<div class="select is-success">
<select name='category'   style="width:450px; border: 1px solid #006F37" required>
<option></option>
<option value="All">All Clients With Loans</option>
<option value="Risk">At Risk</option>
<option value="Defaulters">Defaulters</option>
</select>
</div><br><br>
</td></tr><tr>
<td>
Message:
</td>
<td>
<textarea name="msg" id="msg" cols="50" rows="5" class="textarea is-success" 
placeholder="Type your message here!" required></textarea>
</td></tr><tr>
<td></td><td>
<br> 
<label class="label" style="margin-left: 340px">
<button type="submit" name="send" class="button is-default" 
style="border: 1px solid; border-radius:4px; color:white; background-color: #006F37;">
Send SMS</button>             
</form>
</td></tr></table>
</div>
<!-- End of A table for SMS -->

</div>
</div>
</div>

<div> 
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>
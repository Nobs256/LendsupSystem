<?php
if(isset($_POST['reload']))
{
$sec=$_POST['re'];
header("refresh: $sec");
}
$success_reg=""; 
include('conn.php');
 

if(isset($_GET['delete_user'])){
$user_id = $_GET['delete_user'];

mysqli_query($conn,"UPDATE new_users SET active=0 WHERE user_id='$user_id'");

$success_reg = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:0px; padding:10px; width: 700px'>
<font color=white>A User is Successfully Deleted!! </font>
<a href='view_users.php?reload=1' style='color:white; margin-left:350px;''>X</a>
</div>";

}

include('header.php');
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
<main class="column main" style="background-color:#EAEAEA;"> 
 <?php include ('summary_admin.php') ?> 
<div id="main_heading"> <b>All Cashiers (Branches)</b>
 
</div>     
<div id="main_container"> 
<div id="main_body"> 
<?php
echo $success_reg;
$search_query= mysqli_query($conn,"SELECT user_id, boss_id, ucase(firstname) as firstname,ucase(lastname) as lastname,  phone, branch, username, password
FROM new_users where  boss_id='$boss_id' and category='User' and active=1 order by firstname");
?>  
 
<table border="1"  style="width:100%">            
 
<thead>
<tr><th>No</th>
<th>Name</th>
<th>Phone</th>
<th>Branch</th>
<th>Username</th>
<th>Password</th>
<th>Bio Data</th>
<th>Update</th>
<th>Delete</th>
</tr></thead><tbody>

<?php
$j=0;
while($returned_result = mysqli_fetch_assoc($search_query)){
$user_id=$returned_result["user_id"];
$name = $returned_result["firstname"]." ".$returned_result["lastname"]; 
$phone = $returned_result["phone"];                
$username = $returned_result["username"];
$branch = $returned_result["branch"];
$password = $returned_result["password"];
$j++;

echo "<tr>
<td> $j </td>
<td> $name </td>
<td> $phone </td>    
<td> $branch</td>               
<td> $username</td>
<td> $password</td>";                   

?>
<td>
<a style="border: 1px solid #006F37; border-radius:1px; font-size:10px; color:#006F37; height:20px" 
class="button is-default" href="view_users.php?info2=<?php echo $user_id;?>">&nbsp;
Bio Data&nbsp;</a>

</td>
<td>
<a style="border: 1px solid #006F37; border-radius:1px; font-size:10px; color: white; background-color:#006F37; height:20px" 
class="button is-default" href="update_users.php?user_id=<?php echo $user_id;?>">&nbsp;
Update&nbsp;</a>

</td>
 
<td>
<a style="border: 1px solid; border-radius:4px; color:white; font-size:10px; height:20px " 
class="button is-danger" href="view_users.php?delete_user=<?php echo $user_id?>">
&nbsp;Delete&nbsp;</a>

</td>

<?php
echo "</tr>";

}
echo "</tbody></table>";
?>  

<!--Student Info modal-->
<?php
if(isset($_REQUEST['info2']))
{	  
$user_id = $_REQUEST['info2'];

 
$select_app = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM new_users where user_id='$user_id'"));
$name=strtoupper($select_app['firstname'].' '.$select_app['lastname']) ;
?>
 

<div class="modal fade" id="user_info" role="dialog">
<div class="modal-dialog modal-lg">
<!-- Modal content-->
<div class="modal-content" style="margin-left:500px;  margin-top:100px; width:700px">
<div class="modal-header" style="background-color:#006F37;color:white;height:50px">
<font color="#006F37">----------------------------------------------</font>
<p><font color="#006F37">----------------------------------------------</font>
USER BIO DATA
<font color="#006F37">--------------------------</font>

<a href="view_users.php"> <font color="white" size="4">X</font> </a>
 
 
</div>
<div class="modal-body" style="overflow-y:scroll;  height:auto; background-color:white; border: 5px solid #006F37">
<div id="user_details">
<br>
<table style="width:500px;border: 1px solid #006F37; font-size:13px" align="center">
<tr>
<td width="35%">&nbsp;&nbsp;</td><td></td>
</tr>
<tr>
<td>&nbsp;&nbsp;NAME:</td><td><?php echo strtoupper ($select_app['firstname']." ".$select_app['lastname']);?></td>
</tr>
<tr>
<td>&nbsp;&nbsp;SEX:</td><td><?php echo $select_app['sex'];?></td>
</tr>
<tr>
<td>&nbsp;&nbsp;YEAR OF BIRTH:</td><td><?php echo $select_app['dob'];?></td>
</tr>
<tr>
<td>&nbsp;&nbsp;MARITAL STATUS:</td><td><?php echo strtoupper($select_app['marital']);?></td>
</tr>
<tr>
<td>&nbsp;&nbsp;NATIONAL ID:</td><td><?php echo strtoupper($select_app['nid']);?></td>
</tr>
<tr>
<td>&nbsp;&nbsp;PHONE NO:</td><td><?php echo strtoupper($select_app['phone']);?> </td>
</tr>
<tr>
<td>&nbsp;&nbsp;PLACE OF ORIGIN:</td><td><?php echo strtoupper($select_app['place_o']);?></td>
</tr>
<tr>
<td>&nbsp;&nbsp;PLACE OF RESIDENCE:</td><td><?php echo strtoupper($select_app['place_r']);?></td>
</tr>
<tr>
<td>&nbsp;&nbsp;BRANCH:</td><td><?php echo strtoupper($select_app['branch']);?></td>
</tr>
<tr>
<td>&nbsp;&nbsp;USERNAME:</td><td><?php echo strtoupper($select_app['username']);?></td>
</tr>
<tr>
<td>&nbsp;&nbsp;PASSWORD:</td><td><?php echo strtoupper($select_app['password']);?></td>
</tr>
</table><br>
</div>
</div>
<div class="modal-footer">    
</div>
</div>
</div>
</div>
<?php
} 
?>
</div>
</div>
</div>
<div> 
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js2/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js2/bootstrap.min.js"></script>

<?php include('footer.php'); ?>
</div>
</main>
<script type="text/javascript">
$(document).ready(function()
{
$("#user_info").modal("show");
$("#user_update").modal("show");
$("#tr_sms").modal("show");
 
});


</script>
</body> 
</html>
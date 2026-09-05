<?php
if(isset($_POST['reload']))
{
$sec=$_POST['re'];
header("refresh: $sec");
}
$success_reg=""; 
include('conn.php');
 

if(isset($_GET['delete_officer'])){
$officer_id = $_GET['delete_officer'];

mysqli_query($conn,"UPDATE officers SET active=0 WHERE officer_id='$officer_id'");

$success_reg = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:0px; padding:10px; width: 700px'>
<font color=white>A Field officer is Successfully Deleted!! </font>
<a href='view_officer.php?reload=1' style='color:white; margin-left:350px;''>X</a>
</div>";

}

include('header.php');
?>
<style type="text/css">
 

tr:nth-child(even) {
background-color: #D9FFD9;
}
 
 
</style>
<main class="column main" style="background-color:#EAEAEA;"> 
<?php include ('summary_admin.php') ?>
<div id="main_heading"> <b>All Officers</b>
</div>     

<br>  
<div id="main_container">
<div id="main_body"> 
<?php
echo $success_reg;
 
echo"<br><br>
<div class='table-responsive'>
<table id='example' class='table table-striped table-bordered second' border=1 style='width:100%;'>   
<thead>
<tr>
<th style='text-align:left'>No</th>
<th style='text-align:left'>Name</th>
<th style='text-align:left'>Branch</th>
<th>Phone</th>
<th>Location</th>
<th></th>
<th></th>
<th></th>
</tr></thead><tbody>";
 $j=0;
$u=mysqli_query($conn,"SELECT * from officers  where  boss_id='$boss_id' and active=1 order by branch ");
while($loop=mysqli_fetch_object($u))
{
	$j++;
echo"<tr>";
$branch=strtoupper($loop->branch); 
$officer_id=$loop->officer_id;
$firstname=$loop->firstname;
$lastname=$loop->lastname;
$name=strtoupper($firstname." ".$lastname);
$phone=$loop->phone; 
$location=strtoupper($loop->location); 

echo"<td>$officer_id</td><td>$name</td><td>$branch</td><td>$phone</td><td>$location</td>
<td>
<a style='border: 1px solid #006F37; border-radius:1px; font-size:10px; color:#006F37; height:20px' 
class='button is-default' href='view_officer.php?info2=$officer_id;'>&nbsp;
Bio Data&nbsp;</a>

</td>
<td>
<a style='border: 1px solid #006F37; border-radius:1px; font-size:10px; color: white; background-color:#006F37; height:20px' 
class='button is-default' href='update_officer.php?officer_id=$officer_id'>&nbsp;
Update&nbsp;</a>

</td>
 
<td>
<a style='border: 1px solid; border-radius:4px; color:white; font-size:10px; height:20px ' 
class='button is-danger' href='view_officer.php?delete_officer=$officer_id'>
&nbsp;Delete&nbsp;</a>

</td>
</tr>";

}
echo "</table>";


//======Student Info modal-->

if(isset($_REQUEST['info2']))
{	  
$officer_id = $_REQUEST['info2'];

 
$select_app = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM officers where officer_id='$officer_id'"));
$name=strtoupper($select_app['firstname'].' '.$select_app['lastname']) ;
?>
 

<div class="modal fade" id="officer_info" role="dialog">
<div class="modal-dialog modal-lg">
<!-- Modal content-->
<div class="modal-content" style="margin-left:500px;  margin-top:100px; width:700px">
<div class="modal-header" style="background-color:#006F37;color:white;height:50px">
<font color="#006F37">----------------------------------------------</font>
<p><font color="#006F37">----------------------------------------------</font>
OFFICER FIELD BIO DATA
<font color="#006F37">--------------------------</font>

<a href="view_officer.php"> <font color="white" size="4">X</font> </a>
 
 
</div>
<div class="modal-body" style="overflow-y:scroll;  height:auto; background-color:white; border: 5px solid #006F37">
<div id="officer_details">
<br>
<table style="width:500px;border: 1px solid #006F37; font-size:13px" align="center">
<tr>
<td width="35%">&nbsp;&nbsp;</td><td></td>
</tr>
<tr>
<td>&nbsp;&nbsp;NAME:</td><td><?php echo strtoupper ($select_app['firstname']." ".$select_app['lastname']);?></td>
</tr>
<tr>
<td>&nbsp;&nbsp;PHONE</td><td><?php echo strtoupper($select_app['phone']);?></td>
</tr>
<tr>
<td>&nbsp;&nbsp;NATIONAL ID:</td><td><?php echo strtoupper($select_app['nid']);?></td>
</tr>
<tr>
<td>&nbsp;&nbsp;BRANCH:</td><td><?php echo strtoupper($select_app['branch']);?></td>
</tr>
<tr>
<td>&nbsp;&nbsp;LOCATION:</td><td><?php echo strtoupper($select_app['location']);?></td>
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
$("#officer_info").modal("show");
$("#officer_update").modal("show");
$("#tr_sms").modal("show");
 
});


</script>
</body> 
</html>
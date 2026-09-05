<?php
if(isset($_POST['reload']))
{
$sec=$_POST['re'];
header("refresh: $sec");
}
$success_reg=""; 
include('conn.php');
 

if(isset($_GET['delete_client'])){
$client_id = $_GET['delete_client'];

mysqli_query($conn,"DELETE FROM loans WHERE cliente_id='$client_id'");	
mysqli_query($conn,"DELETE FROM loan_pay WHERE clients_id='$client_id'");
mysqli_query($conn,"DELETE FROM completed_loan WHERE clientcpid='$client_id'");
mysqli_query($conn,"DELETE FROM transcations WHERE clientr_id='$client_id'");

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from clients_with_loan where clientsid='$client_id'"));     
$debt= $result['debt'];
$loan_no= $result['loan_no'];
$pay_date= $result['pay_date'];
$amount_given= $result['amount_given'];
$user_id= $result['userseid'];
$boss_id= $result['bosseseid'];

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from clients WHERE client_id='$client_id'"));     
$firstname= $result['firstname'];
$lastname=$result['lastname'];
$phone= $result['phone'];
$names=$firstname." ".$lastname;

mysqli_query($conn,"INSERT INTO deleted_clients(loan_id, loan_no, clientsid, userseid, bosseseid, pay_date, amount_given, names, debt, phone) 
VALUES (NULL, '$loan_no', '$client_id', '$user_id', '$boss_id', '$pay_date', '$amount_given', '$names', '$debt', '$phone')");
 
mysqli_query($conn,"DELETE FROM clients_with_loan WHERE clientsid='$client_id'");
mysqli_query($conn,"DELETE FROM clients WHERE client_id='$client_id'");

$success_reg = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:0px; padding:10px; width: 700px'>
<font color=white>A Client is Successfully Deleted!! </font>
<a href='view_clients.php?reload=1' style='color:white; margin-left:350px;''>X</a>
</div>";

}

include('header_user.php');
?>
<style type="text/css">
 
tr:nth-child(even) {
background-color: #D9FFD9;
}
form {
border-collapse: collapse;
}

input, select {
width:340px; height:33px; font-size:12px; border: 2px solid green; border-radius: 4px;
}
</style>
<main class="column main" style="background-color:#EAEAEA;"> 
 <?php include ('summary.php') ?>
 
<div id="main_heading"> <b>All Clients</b>
 
</div>     

<div id="main_container" style="height:430px">
<div id="main_body" style="height:400px"> 
<?php
echo $success_reg;
$search_query= mysqli_query($conn,"SELECT client_id, users_id, bosses_id, 
ucase(firstname) as firstname,ucase(lastname) as lastname,  phone, business, b_location
FROM clients where users_id='$user_id' and bosses_id='$boss_id' order by firstname");
?>  
<div class="table-responsive">
<table id="example" class="table table-striped table-bordered second" border="1" style="width:100%">            
 
<thead>
<tr>
<th width="5%">No</th>
<th width="18%">Name</th>
<th width="8%">Phone</th>
<th width="8%">Business </th>
<th width="7%">B.Location</th>
<th width="7%">Bio Data</th>
<th width="7%">Edit</th>
<th width="7%">Delete</th>
<th width="15%">Client Book</th>

</tr></thead><tbody>

<?php
$j=0;
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id=$returned_result["client_id"];
$name = $returned_result["firstname"]." ".$returned_result["lastname"]; 
$phone = $returned_result["phone"];                
$business = strtoupper($returned_result["business"]);
$b_location = strtoupper($returned_result["b_location"]);
$j++;

echo "<tr>
<td><font size=2> $client_id </font></td>
<td><font size=2> $name </font></td>
<td><font size=2> $phone </font></td>                   
<td>
<a  href='view_clients.php?delete_client=$client_id'> <font size=2 color='black'> $business</font></a></td>
<td><font size=2> $b_location</font></td>  ";
?>            


<td>
<a style="border: 1px solid #006F37; border-radius:1px; font-size:10px; color:#006F37; height:20px" 
class="button is-default" href="view_clients.php?info2=<?php echo $client_id;?>">&nbsp;
Bio Data&nbsp;</a>

</td>

<td>
<!--<a style="border: 1px solid #006F37; border-radius:1px; font-size:10px; color:#006F37; height:20px" 
class="button is-default" href="update_clients_users.php?client_update=<?php echo $client_id;?>">&nbsp;
Edit&nbsp;</a>-->

</td>

<td>
<!--
<a style="border: 1px solid red; border-radius:1px; font-size:10px; color:white; height:20px; background-color:red;" 
class="button is-default" href="view_clients.php?delete_client=<?php echo $client_id;?>">&nbsp;
Delete&nbsp;</a>
-->
</td>

<td>
<a style="border: 1px solid #006F37; border-radius:1px; font-size:10px; color:#006F37; height:20px" 
class="button is-default" href="loan_history.php?client_id=<?php echo $client_id;?>">&nbsp;
Client Book&nbsp;</a>

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
$client_id = $_REQUEST['info2'];

$year=date('Y');
$select_app = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM clients where client_id='$client_id'"));
$name=strtoupper($select_app['firstname'].' '.$select_app['lastname']) ;
?>
 

<div class="modal fade" id="client_info" role="dialog">
<div class="modal-dialog modal-lg">
<!-- Modal content-->
<div class="modal-content" style="margin-left:500px;  margin-top:100px; width:700px">
<div class="modal-header" style="background-color:#006F37;color:white;height:50px">
<font color="#006F37">----------------------------------------------</font>
<p><font color="#006F37">----------------------------------------------</font>
CLIENT BIO DATA
<font color="#006F37">--------------------------</font>

<a href="view_clients.php"> <font color="white" size="4">X</font> </a>
 
 
</div>
<div class="modal-body" style="overflow-y:scroll;  height:auto; background-color:white; border: 5px solid #006F37">
<div id="client_details">
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
<td>&nbsp;&nbsp;PLACE OR RESIDENCE:</td><td><?php echo strtoupper($select_app['place_r']);?></td>
</tr>
<tr>
<td>&nbsp;&nbsp;BUSINESS NAME:</td><td><?php echo strtoupper($select_app['business']);?></td>
</tr>
<tr>
<td>&nbsp;&nbsp;BUSINESS LOCATION:</td><td><?php echo strtoupper($select_app['b_location']);?></td>
</tr>
</table><br>
</div>
</div>
<div class="modal-footer">    
</div>
</div>
 
 
<?php
} 
?>
</div>
</div>
</div>
 
  
<?php include('footer.php'); ?>
 
</main>
<script type="text/javascript">
$(document).ready(function()
{
$("#client_info").modal("show");
$("#client_update").modal("show");
$("#tr_sms").modal("show");
 
});


</script>
</body> 
</html>
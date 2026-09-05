<?php
if(isset($_POST['reload']))
{
$sec=$_POST['re'];
header("refresh: $sec");
}
$success_reg=""; 
include('conn.php');
 

if(isset($_GET['success'])){
 
$success_reg = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:0px; padding:10px; width: 700px'>
<font color=white>Loan is Successfully  Cleared!! </font>
<a href='search_client_clear_balance?reload=1' style='color:white; margin-left:350px;''>X</a>
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
 
<div id="main_heading"> <b>Search Client to Give Loan</b>
 
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
<table id="example" class="table table-striped table-bordered second" border="1" style="width:80%" align="center">            
 
<thead>
<tr>
<th width="5%">No</th>
<th width="18%">Name</th>
<th width="8%">Phone</th>
<th width="12%">Business </th>
<th width="10%">Location</th>
<th width="20%">Give Loan</th>

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

$hup=mysqli_query($conn,"SELECT * FROM clients_with_loan where bosseseid='$boss_id' and 
userseid='$user_id' and clientsid='$client_id'");
$now=mysqli_fetch_array($hup);
$debt=$now["debt"];

if($debt>0){

echo "<tr>
<td><font size=2> $client_id </font></td>
<td><font size=2> $name </font></td>
<td><font size=2> $phone </font></td>                   
<td><font size=2> $business</font></td>
<td><font size=2> $b_location</font></td>                  
<td>
<a href='search_client_clear_balance.php?client_id=$client_id'><font size=4 color=green><b>Clear The Loan</b></font></a>

</td>";



echo "</tr>";
}
}
echo "</tbody></table>";
?>  

<!--Student Info modal-->
<?php
if(isset($_REQUEST['client_id']))
{	  
$client_id = $_REQUEST['client_id'];

$year=date('Y');
$select_app = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM clients where client_id='$client_id'"));
$name=strtoupper($select_app['firstname'].' '.$select_app['lastname']) ;
 
 $query ="update clients_with_loan set debt='0' where clientsid='$client_id' and userseid='$user_id' and bosseseid='$boss_id'";
$execute = mysqli_query($conn, $query);


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
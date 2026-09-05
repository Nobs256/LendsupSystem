<?php
$sa="";
if(isset($_POST['reload']))
{
$sec=$_POST['re'];
 header("refresh: $sec");
}   
 include('header_user.php');
if(isset($_GET['success'])){
$sa = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 500px'>
<font color=white>Location is successfully Entered!!</font>
<a href='add_location_admin.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</div>";
}

if(isset($_GET['delete_location'])){
$location_id = $_GET['delete_location'];

mysqli_query($conn,"DELETE FROM location WHERE location_id='$location_id'");

$sa = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:0px; padding:10px; width: 700px'>
<font color=white>A Location is Successfully Deleted!! </font>
<a href='add_location_admin.php?reload=1' style='color:white; margin-left:350px;''>X</a>
</div>";

}

if(isset($_GET['already_location'])){
$sa = "<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 700px'>
<font color=white>The Location  is already Entered!!</font>
<a href='add_location_admin.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</div>";
}


?>
<style type="text/css">
  

th, td {
text-align: left;
padding: 4px;
padding-top: 1px;
}

tr:nth-child(even) {
background-color: #D9FFD9;
}

</style>
<main class="column main" style="background-color:#EAEAEA;">
<?php include ('summary.php') ?>

<div id="main_heading"> <b>Enter Locations For Field Officers</b>
<font color="#EAEAEA">-------------------</font>
<?php echo $sa;?>
</div>     
 <br>
<div id="main_container" style="height:420px">
<div id="main_body" style="height:390px">   
<br>
<?php
 
echo "<table width=100% border=0>
<tr>";
$j=0;

$search_query= mysqli_query($conn,"SELECT * FROM location where  bossloc_id='$boss_id' and userloc_id='$user_id' order by location");
while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;

$officer_id=$returned_result["location_id"];
$name  = strtoupper($returned_result["location"]);

echo "
<td width=2%>
<form method=POST action=user_connector.php>
<input type=checkbox id=loc name=loc value='$name'>
</td>
<td>$name
&nbsp;
</td>
";
}

echo "</tr>";
echo "</tbody></table>";
echo "<br> ";
echo "<table width=80% border=1 style='font-size:12px'>
<tr>
<th>No</th>
<th>Name</th>
<th>Phone</th>
<th>Place Of Resident</th>
<th>Field Location</th>
<th></th>
</tr>
<tr>";
$k=0;
$search_query= mysqli_query($conn,"SELECT * FROM clients where bosses_id='$boss_id' and users_id='$user_id' and b_location='nolocation' order by firstname");
while($returned_result = mysqli_fetch_assoc($search_query)){
$k++;

$client_id=$returned_result["client_id"];
$name  = strtoupper($returned_result["firstname"]." ".$returned_result["lastname"]);
$loc=strtoupper($returned_result["b_location"]);
$phone=$returned_result["phone"];
$place=strtoupper($returned_result["place_r"]);

echo "
<td>$k</td>
<td>$name</td>
<td>$phone</td>
<td>$place</td>
<td>$loc</td>
<td>
 
<input type=checkbox id=client_id name=client_id[] value=$client_id>
</td>";
echo "</tr>";
}
echo "</tbody></table>";

echo"<input type=submit name=submit value=SAVE style='margin-left:700px; background-color:green; color:white; width:' 
class='button is-default'>";
mysqli_close($conn);
 
?>
</div>
</div>
 
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>
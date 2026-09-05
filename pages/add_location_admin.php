<?php
$sa="";
if(isset($_POST['reload']))
{
$sec=$_POST['re'];
 header("refresh: $sec");
}   
 include('header.php');
if(isset($_GET['success'])){
$sa = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 500px'>
<font color=white>Location is successfully Entered!!</font>
<a href='add_location.php?reload=1' style='color:white; margin-left:100px;''>X</a>
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
<?php include ('summary_admin.php') ?>

<div id="main_heading"> <b>Enter Locations For Field Officers</b>
<font color="#EAEAEA">-------------------</font>
<?php echo $sa;?>
</div>     
 <br>
<div id="main_container">
<div id="main_body">   
<br>
<form method="post" action="admin_connector.php">
<input type="hidden" name="boss_id" value="<?php echo $boss_id; ?>">
<table style="width:400px;" border="0">
<tr>
<td width="160px"> 
Location: 
</td>
<td width="300px">
<input type="text" name="loc" class="input is-success" style="width:270px; border: 1px solid #006F37"
required><br><br>       
</td>
<td width="160px"> Branch:</td>
<td width="160px"> 
<div class="select is-success">  
<select  name="user_id"  style="width:180px; border: 1px solid #006F37; height:35px" required> 
<option></option>
<?php
$comb = mysqli_query($conn,"SELECT * from new_users where boss_id='$boss_id' and active=1 and category='User'");
while($select_comb = mysqli_fetch_array($comb)){
$branch=$select_comb['branch']; 
$user_id=$select_comb['user_id']; 
echo "<option value= $user_id> $branch </option>"; 
}
echo" 
</select></div>";
?>     
</td>
<td>        
<button type="submit" name="location" class="button is-primary"
 style="border: 1px solid #006F37; border-radius:4px; color:white;
  background-color:#006F37; margin-left:2px">
&nbsp;&nbsp;&nbsp;Add Location&nbsp;&nbsp;&nbsp;</button>
</td>
</tr>   
</table>
</form> 
<hr></hr>
<?php
 
echo "<table width=60% border=1>
<thead>
<tr>
<th>No</th>
<th>Location</th>
<th>Branch</th><th></th>
</tr>
</thead>
<tbody>";
$j=0;

$search_query= mysqli_query($conn,"SELECT * FROM location where  bossloc_id='$boss_id' order by userloc_id");
while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;

$location_id=$returned_result["location_id"];
$location  = $returned_result["location"];
$user_id  = $returned_result["userloc_id"];

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from new_users where user_id='$user_id'"));
$br = $results["branch"];

echo "<tr>
<td><font size=3> $j  </font></td>
<td>$location</td>
<td>$br</td>
<td><a href='add_location_admin.php?delete_location=$location_id;'><font color=red>&nbsp;DELETE</font></a></td>";
}

echo "</tr>";
echo "</tbody></table>";
mysqli_close($conn);
 
?>
</div>
</div>
<div> 
<?php include('footer.php'); ?>
</div>
</main>
</body> 
</html>
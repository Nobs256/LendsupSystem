<?php 
include("../conn.php"); 
$s="";
if(isset($_POST['reload']))
{
$sec=$_POST['re'];
header("refresh: $sec");
}
if(isset($_GET['already_user'])){
$s = "<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 800px'>
<font color=white>A user is already Entered!! Or Check the Email, it might be used by some one else!!!! </font>
<a href='admin_homepage.php?reload=1' style='color:white; margin-left:120px;''>X</a>
</div>";
}

if(isset($_GET['delete_user'])){
$s = "<div style='background-color:#5E4EA0; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:7px; width: 800px'>
<font color=white>A User is successfully deleted!!</font>
<a href='admin_homepage.php?reload=1' style='color:white; margin-left:500px;''>X</a>
</div>";
}
include('header.php'); 

?>

<main class="column main">   
<br> <br>       
<div id="main_body" style="width:1100px;">
<?php
echo $s;
$search_query= mysqli_query($conn,"SELECT boss_id, ucase(firstname) as firstname, 
ucase(lastname) as lastname, phone FROM bosses order by firstname");
?>               
<table class="table table-responsive table-hover" style="width:900px;">
<tr><th>No</th><th>Name</th> <th>No.of Users</th> <th>Phone</th><th>Manage</th>
</tr></thead><tbody>
<?php
$j=0;
while($returned_result = mysqli_fetch_assoc($search_query)){
$sch_id=$returned_result["boss_id"];
$name = $returned_result["firstname"]." ".$returned_result["lastname"];
 
$phone = $returned_result["phone"];

$total_users = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM new_users where 
boss_id='$boss_id'"));
$j++;

echo "<tr>

<td><font size=2> $j </font></td>
<td><font size=2> $name </font></td>
<td><font size=2> $total_users </font></td>
<td><font size=2> $phone</font></td>";                   

?>
<td>
<a style="border: 1px solid #5E4EA0; border-radius:4px; color:#5E4EA0" 
class="button is-default" href="update_bosses.php?boss_id=<?php echo $sch_id;?>">&nbsp;Update</a>

</td>
<td>
<a style="border: 1px solid #5E4EA0; border-radius:4px; color:white; background-color:#5E4EA0" 
class="button is-default" href="create_school_users.php?boss_id=<?php echo $sch_id;?>">&nbsp;Create Users</a>

</td>

<?php
echo "</tr>";

}
echo "</tbody></table>"    

?>  

</div>
</div>  
<div> 
<?php include('../footer.php'); ?>
</div>
</main>
</body> 
</html> 


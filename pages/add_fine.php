<?php
$s="";
include('header_user.php');
if(isset($_GET['success'])){
$client_id = $_GET['success'];
$result = mysqli_fetch_assoc(mysqli_query($conn,"select ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
from clients where client_id='$client_id'"));     
$name= $result['firstname']." ".$result['lastname'];
$phone = $result['phone'];

$s="<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:25px; padding:5px; width: 400px'>Data is Successfully Saved 
<a href='search_client_give_fine.php' style='color:white; margin-left:100px;''>X</a>
</div>";

}
if(isset($_GET['client_id'])){
$client_id = $_GET['client_id'];
$result = mysqli_fetch_assoc(mysqli_query($conn,"select ucase(firstname) as firstname, ucase(lastname)as lastname, phone 
from clients where client_id='$client_id'"));     
$name= $result['firstname']." ".$result['lastname'];
$phone = $result['phone'];
 }
?>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> <?php include ('summary.php');

?>
<div id="main_heading"> <b>Add Fine to</b> <?php echo $name ?>
</div>     
 
<div id="main_container">
<div id="main_body">   
<br>
  <?php echo $s;?>        
  <br><br>                     
<?php
echo"
 
<form method=POST action='user_connector.php'>
<input type='hidden' name='user_id' value='$user_id'>
<input type='hidden' name='boss_id' value='$boss_id'>
<input type='hidden' name='client_id' value='$client_id'>
<font size=4><b>Enter Fine:</b>
<input type='date' name='f_date' class='input is-success' style='width:160px;border: 1px solid #006F37'> 
<input type='text' name='amount' class='input is-success' style='width:160px;border: 1px solid #006F37'>

<button type='submit' name='fines' class='button is-primary'
 style='border: 1px solid #006F37; border-radius:4px; color:white;
  background-color:#006F37; margin-left:4px'>
&nbsp;&nbsp;&nbsp;Save &nbsp;&nbsp;&nbsp;</button>
";
 


echo"
</div>
</div>
</div>
 "; 
include('footer.php');  
?>
</div>
</main>
</body> 
</html>
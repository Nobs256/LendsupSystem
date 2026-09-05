<?php 
include("pages/conn.php");
//preventing sql injection
function prevent_sql_injection($data){
$data = stripslashes($data);
$data = htmlspecialchars($data);
$data = trim($data);
return $data;
}

if (isset($_POST['set_time'])) {
$user_id = $_POST['user_id'];
$boss_id = $_POST['boss_id'];
$time = $_POST['time'];
$pre_date = $_POST['pre_date'];
$changed=1;

$update = "UPDATE time_limit Set date_time='$time', pre_date='$pre_date', changed='$changed'  
where user_id='$user_id' and  boss_id='$boss_id'";
mysqli_query($conn,$update);

header("Location:kyaabel.php?time_set");   

}

if (isset($_POST['add_sms'])) {
$user_id = $_POST['user_id'];
$boss_id = $_POST['boss_id'];
$amount = $_POST['amount'];
$date = date('Y-m-d');

mysqli_query($conn,"INSERT INTO sms_add(sms_id, user_id, boss_id, sms_date, sms_amount) 
VALUES (NULL, '$user_id', '$boss_id', '$date', '$amount')");
 
$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from total_msg where user_id='$user_id' 
and boss_id='$boss_id'")); 
$total= $result['total'];
$new_total=$total+$amount;

$query ="UPDATE total_msg set total='$new_total' where user_id='$user_id' and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);

header("Location:kyaabel.php?add_sms");   

}
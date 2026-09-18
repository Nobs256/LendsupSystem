<?php
$date=date("d-m-Y", strtotime($b_date));

$message="Hello ".strtoupper($names). ", You have been Given a Loan of UGX $amo by HOBRO ($bra) on $date. Tel(0773838059)";   
 
function SendSMS($username, $password, $sender, $number, $message)
{

    $url = "www.egosms.co/api/v1/plain/?";

    $parameters = "number=[number]&message=[message]&username=[username]&password=[password]&sender=[sender]";
    $parameters = str_replace("[message]", urlencode($message) , $parameters);
    $parameters = str_replace("[sender]", urlencode($sender) , $parameters);
    $parameters = str_replace("[number]", urlencode($number) , $parameters);
    $parameters = str_replace("[username]", urlencode($username) , $parameters);
    $parameters = str_replace("[password]", urlencode($password) , $parameters);
    $live_url = "https://" . $url . $parameters;
    $parse_url = file($live_url);
    $response = $parse_url[0];
    return $response;
}
$username = "abelk";
$password = "0777842873";
$sender = "Egosms";
$number=ltrim($number, "0"); 
$number="+256".$number;
SendSMS($username, $password, $sender, $number, $message);

$result = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from total_msg where user_id='$user_id' 
and boss_id='$boss_id'")); 
$total= $result['total'];
$new_total=$total-35;

$query ="UPDATE total_msg set total='$new_total' where user_id='$user_id' and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);

?>
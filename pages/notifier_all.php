<?php
include('conn.php');

//to all Clients
if(isset($_POST['send']))
{
$user_id=$_POST['user_id'];
$boss_id=$_POST['boss_id'];
$category=$_POST['category'];
$year=date("Y");
$msg=addslashes(trim($_POST['msg']));
$j=0;

//---Send SMS to  all Clients
if($category=='All'){
echo "$msg";
$kop=mysqli_query($conn,"SELECT * FROM clients, clients_with_loan 
WHERE  users_id='$user_id' and bosses_id='$boss_id' and client_id=clientsid");
while($view=mysqli_fetch_object($kop))
{
$to=$view->phone;
$j++;
echo $to;
echo "<br>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://text.emediauganda.com/api.php");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"user=abelk&password=0777842873&sender=".urlencode($from)."&message=".urlencode($msg)."&reciever=$to");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$hamza = curl_exec ($ch);
curl_close ($ch);
 
 
}
header("Location:sms_to_clients.php?messages=$j");
echo $j;
}


//---Send SMS to  At Risk
if($category=='Risk'){

$query ="DELETE from portifolios where user_id='$user_id' and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);

$search_query= mysqli_query($conn,"SELECT  *
FROM clients, clients_with_loan where bosses_id='$boss_id' and userseid='$user_id' 
and client_id=clientsid  order by pay_date Desc");  
while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;
$client_id=$returned_result["client_id"];
$name = strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);
$phone=$returned_result["phone"];
$user_id  = $returned_result["users_id"];
$loanNo = $returned_result["loan_no"];
$date_given= $returned_result["pay_date"];
$amount_given = $returned_result["amount_given"];
$daily_p = $returned_result["daily_p"];
$debt= $returned_result["debt"];
 

$interest=$amount_given*20/100;
$total_amount=$amount_given+$interest; 
$curr_date=date('Y-m-d');
$prev_date = date("Y-m-d", strtotime("$curr_date -1 day")); 
//starting date                                              --nyesiga david
$first_date = date("Y-m-d", strtotime("$date_given +1 day"));
$end_date = date("Y-m-d", strtotime("$date_given +30 day"));

$pre_yr=date("Y",strtotime($date_given));
$curr_yr=date("Y",strtotime($curr_date));
//First date
$x=0;
$not_paid_at_all=0;

 
//No of days
$risk_date=date("Y-m-d", strtotime("$end_date +1 day"));
$defaulter_date=date("Y-m-d", strtotime("$end_date +30 day"));

if($risk_date<$prev_date){  
$x=-1;
$missed_balance=$debt;
 }
if($defaulter_date<$prev_date){  
$x=-1;
$missed_balance=$debt;
 }

$total_amount_paid=0;
$amount_supposed_paid=0;
$y=0;
//amount paid
$select = mysqli_query($conn,"SELECT * FROM loan_pay where  
clients_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loanNo' ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_paid"]; 
$total_amount_paid+=$amount;
}
//days
$date1=date_create("$prev_date");
$date2=date_create("$date_given");
$diff=date_diff($date1,$date2);
$y=$diff->format("%a");


if($risk_date<$prev_date){  
$x=30;
$missed_balance=$debt;
 }
else if($defaulter_date<$prev_date){  
$x=31;
$missed_balance=$debt;
 }

else{
$amount_supposed_paid=$y*$daily_p;
$missed_balance=$amount_supposed_paid-$total_amount_paid;
$x=$missed_balance/$daily_p;
}

if($missed_balance<0){  
$missed_balance=0;
 }
 
if($date_given==$prev_date){
 $x=0;
 $missed_balance=0;  
}
  
if($curr_date==$date_given){ 
 $x=0;                     
 $missed_balance=0; 
}
if($pre_yr<$curr_yr){  
$x=31;
$missed_balance=$debt;
 }
if($x<0){
 $x=0;
 }

if($risk_date<$curr_date and $x<0){  
$x=30;
$missed_balance=$debt;
 }

 if($missed_balance<$daily_p && $x==0){  
$missed_balance=0;
 }

$hup=mysqli_query($conn,"SELECT * FROM guarantor where clientg_id='$client_id' and bossesg_id='$boss_id' and usersg_id='$user_id' and g_date='$date_given'");
$now=mysqli_fetch_array($hup);
$phoneg=$now["g_phone1"];

$curr_date=date('Y-m-d');
mysqli_query($conn,"INSERT INTO portifolios(ts_id, user_id, boss_id, client_id, date_curr, names, phone, date_given, due_date, 
amount_given, interest, balance, arreas, days_missed, phoneg) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$curr_date', '$name',  '$phone', '$date_given', '$end_date',
'$amount_given', '$interest', '$debt', '$missed_balance', '$x', '$phoneg')");

}

$j=0;
$active=0;
$at_risk=0;
$defaulters=0;
$fined=0;

$search_query= mysqli_query($conn,"SELECT * FROM portifolios where user_id='$user_id' and boss_id='$boss_id' 
and date_curr='$curr_date' order by days_missed");
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id=$returned_result["client_id"];
$names=$returned_result["names"];
$phone = $returned_result["phone"];
$date_given  = $returned_result["date_given"];
$due_date  = $returned_result["due_date"];
$amount_given  = $returned_result["amount_given"];
$interest = $returned_result["interest"];
$balance = $returned_result["balance"];
$arreas = $returned_result["arreas"];
$days_missed = $returned_result["days_missed"];
$phoneg = $returned_result["phoneg"];
$j++;
 
if($days_missed==30){
$at_risk++;
$to=$phone;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://text.emediauganda.com/api.php");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"user=abelk&password=0777842873&sender=".urlencode($from)."&message=".urlencode($msg)."&reciever=$to");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$hamza = curl_exec ($ch);
curl_close ($ch);
 
}
}
header("Location:sms_to_clients.php?messages=$at_risk;");
}


//---Send SMS to  At Defaulters
if($category=='Defaulters'){

$query ="DELETE from portifolios where user_id='$user_id' and boss_id='$boss_id'";
$execute = mysqli_query($conn, $query);

$search_query= mysqli_query($conn,"SELECT  *
FROM clients, clients_with_loan where bosses_id='$boss_id' and userseid='$user_id' 
and client_id=clientsid  order by pay_date Desc");  
while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;
$client_id=$returned_result["client_id"];
$name = strtoupper($returned_result["firstname"]. " ". $returned_result["lastname"]);
$phone=$returned_result["phone"];
$user_id  = $returned_result["users_id"];
$loanNo = $returned_result["loan_no"];
$date_given= $returned_result["pay_date"];
$amount_given = $returned_result["amount_given"];
$daily_p = $returned_result["daily_p"];
$debt= $returned_result["debt"];
 

$interest=$amount_given*20/100;
$total_amount=$amount_given+$interest; 
$curr_date=date('Y-m-d');
$prev_date = date("Y-m-d", strtotime("$curr_date -1 day")); 
//starting date                                              --nyesiga david
$first_date = date("Y-m-d", strtotime("$date_given +1 day"));
$end_date = date("Y-m-d", strtotime("$date_given +30 day"));

$pre_yr=date("Y",strtotime($date_given));
$curr_yr=date("Y",strtotime($curr_date));
//First date
$x=0;
$not_paid_at_all=0;

 
//No of days
$risk_date=date("Y-m-d", strtotime("$end_date +1 day"));
$defaulter_date=date("Y-m-d", strtotime("$end_date +30 day"));

if($risk_date<$prev_date){  
$x=-1;
$missed_balance=$debt;
 }
if($defaulter_date<$prev_date){  
$x=-1;
$missed_balance=$debt;
 }

$total_amount_paid=0;
$amount_supposed_paid=0;
$y=0;
//amount paid
$select = mysqli_query($conn,"SELECT * FROM loan_pay where  
clients_id='$client_id' and userse_id='$user_id' and bossese_id='$boss_id' and loanNo='$loanNo' ");
while($selected= mysqli_fetch_array($select)){  
$amount= $selected["amount_paid"]; 
$total_amount_paid+=$amount;
}
//days
$date1=date_create("$prev_date");
$date2=date_create("$date_given");
$diff=date_diff($date1,$date2);
$y=$diff->format("%a");


if($risk_date<$prev_date){  
$x=30;
$missed_balance=$debt;
 }
else if($defaulter_date<$prev_date){  
$x=31;
$missed_balance=$debt;
 }

else{
$amount_supposed_paid=$y*$daily_p;
$missed_balance=$amount_supposed_paid-$total_amount_paid;
$x=$missed_balance/$daily_p;
}

if($missed_balance<0){  
$missed_balance=0;
 }
 
if($date_given==$prev_date){
 $x=0;
 $missed_balance=0;  
}
  
if($curr_date==$date_given){ 
 $x=0;                     
 $missed_balance=0; 
}
if($pre_yr<$curr_yr){  
$x=31;
$missed_balance=$debt;
 }
if($x<0){
 $x=0;
 }

if($risk_date<$curr_date and $x<0){  
$x=30;
$missed_balance=$debt;
 }

 if($missed_balance<$daily_p && $x==0){  
$missed_balance=0;
 }

$hup=mysqli_query($conn,"SELECT * FROM guarantor where clientg_id='$client_id' and bossesg_id='$boss_id' and usersg_id='$user_id' and g_date='$date_given'");
$now=mysqli_fetch_array($hup);
$phoneg=$now["g_phone1"];

$curr_date=date('Y-m-d');
mysqli_query($conn,"INSERT INTO portifolios(ts_id, user_id, boss_id, client_id, date_curr, names, phone, date_given, due_date, 
amount_given, interest, balance, arreas, days_missed, phoneg) 
VALUES (NULL, '$user_id', '$boss_id', '$client_id', '$curr_date', '$name',  '$phone', '$date_given', '$end_date',
'$amount_given', '$interest', '$debt', '$missed_balance', '$x', '$phoneg')");

}

$j=0;
$active=0;
$at_risk=0;
$defaulters=0;
$fined=0;

$search_query= mysqli_query($conn,"SELECT * FROM portifolios where user_id='$user_id' and boss_id='$boss_id' 
and date_curr='$curr_date' order by days_missed");
while($returned_result = mysqli_fetch_assoc($search_query)){
$client_id=$returned_result["client_id"];
$names=$returned_result["names"];
$phone = $returned_result["phone"];
$date_given  = $returned_result["date_given"];
$due_date  = $returned_result["due_date"];
$amount_given  = $returned_result["amount_given"];
$interest = $returned_result["interest"];
$balance = $returned_result["balance"];
$arreas = $returned_result["arreas"];
$days_missed = $returned_result["days_missed"];
$phoneg = $returned_result["phoneg"];
$j++;
 
if($days_missed>30){
$defaulters++;
$to=$phone;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://text.emediauganda.com/api.php");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"user=abelk&password=0777842873&sender=".urlencode($from)."&message=".urlencode($msg)."&reciever=$to");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$hamza = curl_exec ($ch);
curl_close ($ch);
 
}
}
header("Location:sms_to_clients.php?messages=$defaulters;");
}
}
?>
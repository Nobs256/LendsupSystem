<?php
include('header_user.php');

// Capture selected dates early so the Print button in the heading can use them
$date1 = "";
$date2 = "";
if(isset($_POST['returned_loans'])){
    $date1 = $_POST['date1'];
    $date2 = $_POST['date2'];
}
?>
 <style>
 tr:nth-child(even) {
background-color: #D9FFD9;
}

th, td {
text-align: left;
padding-left: 10px;
 
}
</style>
<main class="column main" style="background-color:#EAEAEA;">
<p align="center"> 
<?php include ('summary.php') ;?>

<div id="main_heading"> 
<table><tr><td><b>Returned Loans</b> </td><td><font color="#EAEAEA">----------------------------------</font></td>
<td>
<?php
echo "
<form method='post' action='returned_loans.php'>
From:<input type=date name=date1 required>
To:<input type=date name=date2 required>
<button type='submit' name=returned_loans style='padding-top:1px; border: 1px solid #006F37; border-radius:4px; color:#006F37'>
&nbsp;OK &nbsp;</button></form>";
?>   
</td>
<td>
<?php
echo"
<form method='post' action='print_returned_loans.php' target='_blank'>
<input type=hidden name='user_id' value='$user_id'>
<input type=hidden name='boss_id' value='$boss_id'>
<input type=hidden name='date1' value='$date1'>
<input type=hidden name='date2' value='$date2'>
<button type='submit' name=returned_loans style='border: 1px solid #7C7C7C; border-radius:3px; font-size:17px; 
color: white; background-color:green; height:30px; width:200px'>
&nbsp;Print Returned Loans &nbsp;</button></form> 	

";
?>
</td>
</tr></table>

</div>     
 
<div id="main_container">
<div id="main_body">   
    
<br>  
<?php
if(isset($_POST['returned_loans'])){

echo "<table width=100% border=0 style='font-size:14px' align=center><tr><td>
<p align=left><font size=4>LOANS RETURNED BETWEEN <b>".date('d-m-Y', strtotime($date1))."</b>
 AND <b>".date('d-m-Y', strtotime($date2))."</b>
</font><br><br>
</td></tr></table>";

$j=0;
$total_returned=0;

// Returned loans for this branch/officer only
$search_query= mysqli_query($conn,"SELECT lr.id, lr.client_id, lr.date, lr.amount_returned, 
ucase(c.firstname) as firstname, ucase(c.lastname) as lastname, c.phone, c.b_location
FROM loan_returned lr, clients c  
where lr.user_id='$user_id' and lr.boss_id='$boss_id' and lr.client_id=c.client_id 
and lr.date>='$date1' and lr.date<='$date2' order by lr.date, lr.id");

if(mysqli_num_rows($search_query) > 0){

echo "<table width=80% border=1 align=center>
<thead>
<tr>
<th>No</th>
<th>Date Returned</th>
<th>Client's Name</th>
<th>Phone</th>
<th>Location</th>
<th>Amount Returned</th>
</tr>
</thead>
<tbody>";

while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;

$firstname = $returned_result["firstname"]. " ". $returned_result["lastname"];
$phone = $returned_result["phone"];
$location = $returned_result["b_location"];
$amount = $returned_result["amount_returned"];
$date = $returned_result["date"];
$d=date("d-m-Y", strtotime($date));

$total_returned+=$amount;

echo "<tr style='font-size:13px'>
<td>&nbsp;&nbsp;&nbsp; $j  </td>
<td>&nbsp;&nbsp;&nbsp; $d  </td>
<td>&nbsp;&nbsp;&nbsp; $firstname  </td>
<td>&nbsp;&nbsp;&nbsp; $phone  </td>
<td>&nbsp;&nbsp;&nbsp; ".ucwords($location)."  </td>
<td>&nbsp;&nbsp;&nbsp; ".number_format($amount)."</td>";
echo "</tr>";
}

echo "
 <tr style=font-size:13px><td> </td><td> </td><td></td><td><b>TOTAL</b></td><td></td>
 <td>&nbsp;&nbsp;&nbsp;<b>".number_format($total_returned)."</b></td></tr>";
echo "</tbody></table>";

}
else{
echo "<p align=center><font color=red size=4>No Returned Loans found between 
<b>".date('d-m-Y', strtotime($date1))."</b> and <b>".date('d-m-Y', strtotime($date2))."</b></font></p>";
}

mysqli_close($conn);

}
else{
 
 echo"Select the Dates  when Loans were Returned
<hr></hr>
";
 
 
}
?>
</div>
</div>
 
<?php include('footer.php'); ?>
</main>
</body> 
</html>
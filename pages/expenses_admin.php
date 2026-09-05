<?php
include('header.php');
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
<?php include ('summary_admin.php') ;
 $total_amo=0;
 $total_amos=0;
 $month=date('m');
//Expense
$select = mysqli_query($conn,"SELECT * FROM expenses where  MONTH(exp_date)='$month' and bossexp_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$total_amo= $selected["cost"]; 
$total_amos+=$total_amo;
}
?>
<div id="main_heading"> 
<table><tr><td><b>Monthly Expenses Report</b> (Total Expenses: <?php echo number_format($total_amos);?>) </td><td><font color="#EAEAEA">----</font></td>
<td>
<form method="post"> 
<div class="select is-success">  
<select  name="user_id"  style="width:180px; border: 1px solid #006F37; height:35px" required> 
<option>Branch</option>
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

<div class="select is-success">                   
<select  name="months"  style="width:180px; border: 1px solid #006F37; height:35px" required> 
<option>Month</option>
<option value="01">January</option> 
<option value="02">Febuary</option>
<option value="03">March</option>
<option value="04">April</option>
<option value="05">May</option>
<option value="06">June</option>
<option value="07">July</option>
<option value="08">August</option>
<option value="09">September</option>
<option value="10">October</option>
<option value="11">November</option> 
<option value="12">December</option>
</select>
</div>
<div class="select is-success">  
<select  name="year"  style="width:120px; border: 1px solid #006F37; height:35px" required> 
<option>Year:</option>
<?php 
 for ($yr=2021; $yr <=2040 ; $yr++) { 
  echo"<option> $yr </option>";
 }  
echo" 
</select></div>";
?>
<button type="submit" name="monthly_report" class="button is-primary" 
style="border: 1px solid #006F37; border-radius:4px; color:white; background-color:#006F37">
&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;</button>
</label>   
</form>     
</td></tr></table>

</div>     
 
<div id="main_container">
<div id="main_body">   
    
<br>  
<?php
$mon="";
$total_amounts=0;
if(isset($_POST['monthly_report'])){
$month = $_POST['months'];
$user_id= $_POST['user_id'];
$year = $_POST['year'];

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from new_users where boss_id='$boss_id' and active=1 and category='User' and user_id='$user_id' "));
$branch=$results['branch']; 

}
else{
$month=date('m');
$year = date('Y');

$results = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * from new_users where boss_id='$boss_id' and active=1 and category='User'"));
$branch=$results['branch']; 
$user_id=$results['user_id'];
}

$dateObj   = DateTime::createFromFormat('!m', $month);
$mon = $dateObj->format('F');

$total_amount=0;
 
 //Expense
$select = mysqli_query($conn,"SELECT * FROM expenses where  MONTH(exp_date)='$month' and userexp_id='$user_id' and bossexp_id='$boss_id' ");
while($selected= mysqli_fetch_array($select)){  
$total_amount= $selected["cost"]; 
$total_amounts+=$total_amount;
}

echo "<b>Total Expenses in <i>$mon-$year</i> is <u>".number_format($total_amounts)."</u> from <i>$branch</i>  </b>";
echo "<br><br>";

echo "<table width=80% border=1>
<thead>
<tr>
<th>No</th>
<th>Date</th>
<th>Item</th>
<th>Naration</th>
<th>Amount</th>
</tr>
</thead>
<tbody>";

$j=0;
$search_query= mysqli_query($conn,"SELECT * FROM expenses where  MONTH(exp_date)='$month' and userexp_id='$user_id' and bossexp_id='$boss_id' ");
 while($returned_result = mysqli_fetch_assoc($search_query)){
$j++;
$item=$returned_result["item"];
$amount  = number_format($returned_result["cost"]);
$date = $returned_result["exp_date"];
$naration = $returned_result["naration"];
$d=date("d-m-Y", strtotime($date));

echo "<tr>
<td><font size=3> $j  </font></td>
<td><font size=3> $d  </font></td>
<td><font size=3> $item</font></td>
<td><font size=3> $naration </font></td>
<td><font size=3> $amount</font></td>
";
}

echo "</tr>";
echo "</tbody></table>";
mysqli_close($conn);
 
 echo"
<hr></hr>
";
echo"
 
</div>
</div>
</div>
<div>"; 
include('footer.php');  
?>
</div>
</main>
</body> 
</html>
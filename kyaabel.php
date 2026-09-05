<?php

// We remove NOTICE, WARNING, DEPRECATED, and STRICT
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT);

// 2. Hide from the user's browser
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

// 3. Keep the logger alive for E_ERROR (Fatal) and E_PARSE (Syntax)
ini_set('log_errors', '1');

$s="";
include("conn_master.php");
$branch_search = trim($_GET['branch'] ?? '');
$users_by_branch = [];

if (isset($_POST['add_sms'])) {
	$sms_tenant_id = (int) ($_POST['tenant_id'] ?? 0);
	$sms_user_id = (int) ($_POST['user_id'] ?? 0);
	$sms_amount = filter_var($_POST['amount'] ?? null, FILTER_VALIDATE_INT);
	$sms_recorded = false;

	if ($sms_amount !== false && $sms_amount > 0) {
		$sms_tenant_stmt = mysqli_prepare($master_conn, "
			SELECT db_host, db_user, db_pass, db_name
			FROM tenants
			WHERE tenant_id = ? AND status = 1
			LIMIT 1
			");

		if ($sms_tenant_stmt) {
			mysqli_stmt_bind_param($sms_tenant_stmt, 'i', $sms_tenant_id);
			mysqli_stmt_execute($sms_tenant_stmt);
			$sms_tenant_result = mysqli_stmt_get_result($sms_tenant_stmt);
			$sms_tenant = mysqli_fetch_assoc($sms_tenant_result);
			mysqli_stmt_close($sms_tenant_stmt);

			if ($sms_tenant) {
				$sms_conn = @mysqli_connect(
					$sms_tenant['db_host'],
					$sms_tenant['db_user'],
					$sms_tenant['db_pass'],
					$sms_tenant['db_name']
				);

				if ($sms_conn) {
					mysqli_query($sms_conn, "
						CREATE TABLE IF NOT EXISTS total_msg (
							msg_id bigint(22) NOT NULL AUTO_INCREMENT,
							user_id bigint(22) NOT NULL,
							boss_id bigint(22) NOT NULL,
							total varchar(20) NOT NULL,
							last_updated timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
							PRIMARY KEY (msg_id)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci
						");
					mysqli_query($sms_conn, "
						ALTER TABLE total_msg
						ADD COLUMN IF NOT EXISTS last_updated timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() AFTER total
						");
					$sms_user_stmt = mysqli_prepare($sms_conn, "
						SELECT user_id, boss_id
						FROM new_users
						WHERE user_id = ? AND category = 'User' AND active = 1
						LIMIT 1
						");

					if ($sms_user_stmt) {
						mysqli_stmt_bind_param($sms_user_stmt, 'i', $sms_user_id);
						mysqli_stmt_execute($sms_user_stmt);
						$sms_user_result = mysqli_stmt_get_result($sms_user_stmt);
						$sms_user = mysqli_fetch_assoc($sms_user_result);
						mysqli_stmt_close($sms_user_stmt);

						if ($sms_user) {
							$sms_date = date('Y-m-d');
							$sms_insert_stmt = mysqli_prepare($sms_conn, "
								INSERT INTO sms_add (user_id, boss_id, sms_date, sms_amount)
								VALUES (?, ?, ?, ?)
								");

							if ($sms_insert_stmt) {
								mysqli_stmt_bind_param($sms_insert_stmt, 'iisi', $sms_user_id, $sms_user['boss_id'], $sms_date, $sms_amount);
								$sms_inserted = mysqli_stmt_execute($sms_insert_stmt);
								mysqli_stmt_close($sms_insert_stmt);

								if ($sms_inserted) {
									$sms_total_stmt = mysqli_prepare($sms_conn, "
										SELECT msg_id, total
										FROM total_msg
										WHERE user_id = ? AND boss_id = ?
										LIMIT 1
										");

									if ($sms_total_stmt) {
										mysqli_stmt_bind_param($sms_total_stmt, 'ii', $sms_user_id, $sms_user['boss_id']);
										mysqli_stmt_execute($sms_total_stmt);
										$sms_total_result = mysqli_stmt_get_result($sms_total_stmt);
										$sms_total = mysqli_fetch_assoc($sms_total_result);
										mysqli_stmt_close($sms_total_stmt);

										if ($sms_total) {
											$new_sms_total = (int) $sms_total['total'] + $sms_amount;
											$sms_update_stmt = mysqli_prepare($sms_conn, "UPDATE total_msg SET total = ? WHERE msg_id = ?");
											if ($sms_update_stmt) {
												mysqli_stmt_bind_param($sms_update_stmt, 'ii', $new_sms_total, $sms_total['msg_id']);
												$sms_recorded = mysqli_stmt_execute($sms_update_stmt);
												mysqli_stmt_close($sms_update_stmt);
											}
										} else {
											$sms_new_total_stmt = mysqli_prepare($sms_conn, "
												INSERT INTO total_msg (user_id, boss_id, total)
												VALUES (?, ?, ?)
												");
											if ($sms_new_total_stmt) {
												mysqli_stmt_bind_param($sms_new_total_stmt, 'iii', $sms_user_id, $sms_user['boss_id'], $sms_amount);
												$sms_recorded = mysqli_stmt_execute($sms_new_total_stmt);
												mysqli_stmt_close($sms_new_total_stmt);
											}
										}
									}
								}
							}
						}
					}
					mysqli_close($sms_conn);
				}
			}
		}
	}

	header('Location: kyaabel.php?sms=' . ($sms_recorded ? 'success' : 'error'));
	exit();
}

if (isset($_POST['record_payment'])) {
	$payment_tenant_id = (int) ($_POST['tenant_id'] ?? 0);
	$payment_user_id = (int) ($_POST['user_id'] ?? 0);
	$current_month = (int) date('m');
	$current_year = (int) date('Y');

	$payment_tenant_stmt = mysqli_prepare($master_conn, "
		SELECT db_host, db_user, db_pass, db_name
		FROM tenants
		WHERE tenant_id = ? AND status = 1
		LIMIT 1
	");

	if ($payment_tenant_stmt) {
		mysqli_stmt_bind_param($payment_tenant_stmt, 'i', $payment_tenant_id);
		mysqli_stmt_execute($payment_tenant_stmt);
		$payment_tenant_result = mysqli_stmt_get_result($payment_tenant_stmt);
		$payment_tenant = mysqli_fetch_assoc($payment_tenant_result);
		mysqli_stmt_close($payment_tenant_stmt);

		if ($payment_tenant) {
			$payment_conn = @mysqli_connect(
				$payment_tenant['db_host'],
				$payment_tenant['db_user'],
				$payment_tenant['db_pass'],
				$payment_tenant['db_name']
			);

			if ($payment_conn) {
				$payment_stmt = mysqli_prepare($payment_conn, "
					SELECT u.user_id
					FROM new_users u
					WHERE u.user_id = ?
					  AND u.category = 'User'
					  AND u.active = 1
					  AND NOT EXISTS (
						  SELECT 1 FROM payments p
						  WHERE p.user_id = u.user_id
							AND p.boss_id = u.boss_id
							AND p.paid = 1
							AND MONTH(p.pay_date) = ?
							AND YEAR(p.pay_date) = ?
					  )
					LIMIT 1
				");

				if ($payment_stmt) {
					mysqli_stmt_bind_param($payment_stmt, 'iii', $payment_user_id, $current_month, $current_year);
					mysqli_stmt_execute($payment_stmt);
					mysqli_stmt_store_result($payment_stmt);

					if (mysqli_stmt_num_rows($payment_stmt) === 1) {
						$insert_stmt = mysqli_prepare($payment_conn, "
							INSERT INTO payments (user_id, boss_id, amount, pay_date, paid)
							SELECT user_id, boss_id, 50000, CURDATE(), 1
							FROM new_users
							WHERE user_id = ? AND category = 'User' AND active = 1
							LIMIT 1
						");

						if ($insert_stmt) {
							mysqli_stmt_bind_param($insert_stmt, 'i', $payment_user_id);
							mysqli_stmt_execute($insert_stmt);
							mysqli_stmt_close($insert_stmt);
							header('Location: kyaabel.php?payment=success');
							exit();
						}
					}

					mysqli_stmt_close($payment_stmt);
				}
				mysqli_close($payment_conn);
			}
		}
	}
}

$tenant_query = mysqli_query($master_conn, "SELECT tenant_id, company_name, db_host, db_user, db_pass, db_name
	FROM tenants WHERE status=1 ORDER BY company_name, db_name");
$current_month = (int) date('m');
$current_year = (int) date('Y');
while ($tenant = mysqli_fetch_assoc($tenant_query)) {
	$tenant_conn = @mysqli_connect(
		$tenant['db_host'],
		$tenant['db_user'],
		$tenant['db_pass'],
		$tenant['db_name']
	);

	if (!$tenant_conn) {
		continue;
	}

	$user_query = mysqli_query($tenant_conn, "SELECT u.user_id, u.boss_id, u.firstname, u.lastname,
		u.branch, u.username, u.password,
		CASE WHEN COUNT(p.pay_id) > 0 THEN 1 ELSE 0 END AS subscription_paid
		FROM new_users u
		LEFT JOIN payments p ON p.user_id = u.user_id
			AND p.boss_id = u.boss_id
			AND p.paid = 1
			AND MONTH(p.pay_date) = $current_month
			AND YEAR(p.pay_date) = $current_year
		WHERE u.category = 'User' AND u.active = 1
		GROUP BY u.user_id, u.boss_id, u.firstname, u.lastname, u.branch, u.username
		ORDER BY u.branch, u.firstname, u.lastname");

	if ($user_query) {
		while ($user = mysqli_fetch_assoc($user_query)) {
			$user['company_name'] = $tenant['company_name'];
			$user['tenant_id'] = $tenant['tenant_id'];
			$user['db_name'] = $tenant['db_name'];
			$search_text = strtolower($user['branch'] . ' ' . $user['company_name'] . ' ' . $user['db_name']);

			if ($branch_search === '' || strpos($search_text, strtolower($branch_search)) !== false) {
				$users_by_branch[] = $user;
			}
		}
	}

	mysqli_close($tenant_conn);
}
if(isset($_POST['reload']))
{
$sec=$_POST['re'];
header("refresh: $sec");
}
 
if(isset($_GET['success'])){
$s = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:0px; padding:7px; width: 380px'>
<font color=white>The Branch has Successfully Paid!!</font>
<a href='index.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</div>";
}

if(isset($_GET['payment'])){
$s = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:0px; padding:7px; width:420px'>
<font color=white>Monthly payment of UGX 50,000 recorded successfully!</font>
<a href='kyaabel.php' style='color:white; margin-left:20px;'>X</a>
</div>";
}

if(isset($_GET['time_set'])){
$s = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:0px; padding:7px; width: 380px'>
<font color=white>The time is Successfully Set!!</font>
<a href='index.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</div>";
}

if(isset($_GET['add_sms'])){
$s = "<div style='background-color:#006F37; border-radius:5px; color:white; 
height:40px; margin-left:0px; padding:7px; width: 380px'>
<font color=white>SMS is Successfully Added!!</font>
<a href='index.php?reload=1' style='color:white; margin-left:100px;''>X</a>
</div>";
}

if(isset($_GET['sms'])){
$sms_success = $_GET['sms'] === 'success';
$sms_message = $sms_success ? 'SMS added successfully.' : 'SMS could not be added. Enter a positive amount and try again.';
$s = "<div style='background-color:" . ($sms_success ? '#006F37' : '#b42318') . "; border-radius:5px; color:white; padding:7px; width:min(420px, 100%); box-sizing:border-box'>
<font color=white>" . htmlspecialchars($sms_message, ENT_QUOTES, 'UTF-8') . "</font>
<a href='kyaabel.php' style='color:white; float:right'>X</a>
</div>";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<meta name="robots" content="index, follow" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="language" content="en-EN" />
<meta name="author" content="Irfan Maulana" />
<title>QuickAccounts </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="pages/css/bulma.min.css">
<link rel="stylesheet" href="pages/dist/main.css">
<link rel="shortcut icon" href="pages/assets/images/logo.ico" />

<style>

#main_body{
padding-top:100px;
padding-left:200px;
width: 900px; 
height:auto;
border-radius:4px;
border: 0px solid #006F37;
margin-left: 200px;

}
#login_part{
width:800px; 
margin-left:50px; 
margin-top:0px; 
padding-left:10px;
padding-top:60px;
padding-right:10px;
border-radius:2px; 
border: 1px solid #006F37;
height:auto;
}

#main_heading{
margin-left:50px;
margin-bottom: 0px;
width: 800px;
padding-left: 20px; 
padding-top: 10px; 
padding-bottom: 10px; 
background-color:#006F37;       
border-radius: 2px; 
color: white;
height: auto;
list-style: none;  
border: 1px solid #006F37;
font-size:16px;
font-family: Arial;
}

.table-wrap {
	overflow-x: auto;
	-webkit-overflow-scrolling: touch;
}

.sms-button {
	border: 1px solid #006F37;
	border-radius: 3px;
	background-color: #006F37;
	color: white;
	cursor: pointer;
	padding: 5px 9px;
	white-space: nowrap;
}

.sms-modal {
	position: fixed;
	inset: 0;
	display: none;
	align-items: center;
	justify-content: center;
	padding: 16px;
	background: rgba(0, 0, 0, .45);
	z-index: 10;
}

.sms-modal.is-open {
	display: flex;
}

.sms-modal-content {
	width: min(100%, 420px);
	background: white;
	border: 1px solid #006F37;
	border-radius: 4px;
	padding: 20px;
}

.sms-modal-content input {
	box-sizing: border-box;
	width: 100%;
	height: 36px;
	margin: 8px 0 14px;
	padding: 6px;
}

.sms-modal-actions {
	display: flex;
	gap: 8px;
	justify-content: flex-end;
}

.sms-modal-actions button {
	min-height: 36px;
	padding: 6px 12px;
	cursor: pointer;
}

@media (max-width: 900px) {
	#main_body,
	#login_part,
	#main_heading {
		box-sizing: border-box;
		width: 100%;
		margin-left: 0;
	}

	#main_body {
		padding: 24px 12px;
	}

	#login_part {
		padding: 28px 10px;
	}

	#main_heading {
		padding-left: 10px;
		padding-right: 10px;
	}

	.branch-search {
		display: flex;
		flex-wrap: wrap;
		gap: 6px;
	}

	.branch-search input[type='search'] {
		flex: 1 1 180px;
		max-width: 100%;
		box-sizing: border-box;
	}
}
</style>

</head>
<body>

<main class="column main">  
<div id="main_body"> 
<div id="main_heading" align="center">
<font size="5" face="Verdana, Arial, Helvetica, sans-serif">
QuickAccounts</FONT><br>
<font size="2" face="Verdana, Arial, Helvetica, sans-serif">
Empowering Businesses to the next Level</font></div>                     
<div id="login_part" >   
               
<?php
echo $s;

echo "<p align=center><b>USERS</b></p>
<br>";
echo "<form class='branch-search' method='get' action='kyaabel.php' style='margin-bottom:15px'>
<input type='search' name='branch' value='" . htmlspecialchars($branch_search, ENT_QUOTES, 'UTF-8') . "'
placeholder='Search branch or company' style='width:280px; height:32px; padding:5px; border:1px solid #006F37; border-radius:3px'>
<button type='submit' style='height:32px; color:white; background-color:#006F37; border:1px solid #006F37; border-radius:3px'>Search</button>
<a href='kyaabel.php' style='margin-left:10px; color:#006F37'>Clear</a>
</form>";
echo "<div class='table-wrap'><table width='100%' border=1 style='font-size:14px' >
<thead>
<tr>
<th>&nbsp;&nbsp;&nbsp;No</th>
<th>&nbsp;&nbsp;&nbsp;Names</th>
<th>&nbsp;&nbsp;&nbsp;Company</th>
<th>&nbsp;&nbsp;&nbsp;Branch</th>
<th>&nbsp;&nbsp;&nbsp;Username</th>
<th>&nbsp;&nbsp;&nbsp;Password</th>
<th>&nbsp;&nbsp;&nbsp;Payment</th>
<th>&nbsp;&nbsp;&nbsp;SMS</th>
</tr>";
$j=0;
foreach ($users_by_branch as $returned_result) {
$j++;
$user_id=$returned_result["user_id"];
$boss_id=$returned_result["boss_id"];
$name= strtoupper($returned_result["firstname"]." ".$returned_result["lastname"]);
$branch  = $returned_result["branch"];
$username  = $returned_result["username"];
$password  = $returned_result["password"];
$company_name = $returned_result["company_name"];
$payment_status = ((int) $returned_result["subscription_paid"] === 1) ? 'Paid' : 'Unpaid';
$payment_color = $payment_status === 'Paid' ? '#006F37' : 'red';
$payment_cell = "<span style='color:$payment_color; font-weight:bold;'>$payment_status</span>";

if ($payment_status === 'Unpaid') {
	$payment_cell = "<form method='post' action='kyaabel.php' style='margin:0'>
		<input type='hidden' name='tenant_id' value='" . (int) $returned_result['tenant_id'] . "'>
		<input type='hidden' name='user_id' value='$user_id'>
		<button type='submit' name='record_payment' style='border:0; background:none; color:red; font-weight:bold; cursor:pointer; padding:0;'>Unpaid</button>
	</form>";
}

echo"<tr>
<td>&nbsp;&nbsp;&nbsp;$j</td>
<td>&nbsp;&nbsp;&nbsp;$name</td>
<td>&nbsp;&nbsp;&nbsp;$company_name</td>
<td>&nbsp;&nbsp;&nbsp;$branch</td>
<td>&nbsp;&nbsp;&nbsp;$username</td>
<td>&nbsp;&nbsp;&nbsp;$password</td>
<td>&nbsp;&nbsp;&nbsp;$payment_cell</td>
<td><button type='button' class='sms-button' data-tenant-id='" . (int) $returned_result['tenant_id'] . "' data-user-id='" . (int) $user_id . "' data-user-name='" . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "'>Add SMS</button></td>
</tr>";

}
echo"</table></div>";
 
?>
<div id="sms-modal" class="sms-modal" role="dialog" aria-modal="true" aria-labelledby="sms-modal-title">
<div class="sms-modal-content">
<h3 id="sms-modal-title">Add SMS</h3>
<p id="sms-user-name"></p>
<form method="post" action="kyaabel.php">
<input type="hidden" name="tenant_id" id="sms-tenant-id">
<input type="hidden" name="user_id" id="sms-user-id">
<label for="sms-amount">Amount</label>
<input type="number" name="amount" id="sms-amount" min="1" step="1" required>
<div class="sms-modal-actions">
<button type="button" id="sms-cancel">Cancel</button>
<button type="submit" name="add_sms" class="sms-button">Add SMS</button>
</div>
</form>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
	var modal = document.getElementById('sms-modal');
	var amount = document.getElementById('sms-amount');
	document.querySelectorAll('.sms-button[data-user-id]').forEach(function (button) {
		button.addEventListener('click', function () {
			document.getElementById('sms-tenant-id').value = button.dataset.tenantId;
			document.getElementById('sms-user-id').value = button.dataset.userId;
			document.getElementById('sms-user-name').textContent = button.dataset.userName;
			modal.classList.add('is-open');
			amount.focus();
		});
	});
	document.getElementById('sms-cancel').addEventListener('click', function () {
		modal.classList.remove('is-open');
	});
	modal.addEventListener('click', function (event) {
		if (event.target === modal) {
			modal.classList.remove('is-open');
		}
	});
});
</script>
<br>
<br>
<p align="center">
<a href="index.php"><font color="#006F37" size="4"><b>BACK TO LOGIN PAGE</b></a></font></a>
</p>


</div>
</div> 
</div>
</main>
</body> 
</html>
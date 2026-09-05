<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

session_start();
include("conn_master.php");
$show_subscription_payment = false;

function prevent_sql_injection($conn, $data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($conn, $data);
}

if(isset($_GET['pay'])){
$msg_error = "<div style='background-color:red; border-radius:5px; color:white; 
height:40px; margin-left:2px; padding:7px; width: 400px'>
<font color=white>Your monthly subscription is not active.</font>
<a href='pages/payments/subscription.php?return=index' style='color:white; margin-left:20px;'>Make Payment</a>
<a href='index.php?reload=1' style='color:white; margin-left:20px;''>X</a>
</div>";
}

if (isset($_POST['login'])) {
    $user_email = prevent_sql_injection($master_conn, $_POST["email"]);
    $user_password = $_POST['password']; // Raw password to be sanitized on tenant connection

    // Step 1: Resolve tenant from master routing table
    $query = "SELECT t.* FROM master_users mu 
              JOIN tenants t ON mu.tenant_id = t.tenant_id 
              WHERE mu.username = '$user_email' AND mu.active = 1 AND t.status = 1";
    
    $result = mysqli_query($master_conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $tenant = mysqli_fetch_assoc($result);

        // Step 2: Attempt connection to tenant database
        $tenant_conn = @mysqli_connect($tenant['db_host'], $tenant['db_user'], $tenant['db_pass'], $tenant['db_name']);

        if ($tenant_conn) {
            $clean_email = mysqli_real_escape_string($tenant_conn, $user_email);
            $clean_pass = mysqli_real_escape_string($tenant_conn, $user_password);

            // Step 3: Validate user inside target tenant database
            $user_query = "SELECT * FROM new_users WHERE username='$clean_email' AND password='$clean_pass' AND active=1";
            $user_res = mysqli_query($tenant_conn, $user_query);

            if ($user_res && mysqli_num_rows($user_res) === 1) {
                $user_data = mysqli_fetch_assoc($user_res);

                // Require an active subscription for the current calendar month.
                $current_month = date('m');
                $current_year = date('Y');
                $subscription_stmt = mysqli_prepare($tenant_conn, "
                    SELECT pay_id
                    FROM payments
                    WHERE user_id = ?
                      AND boss_id = ?
                      AND paid = 1
                      AND MONTH(pay_date) = ?
                      AND YEAR(pay_date) = ?
                    LIMIT 1
                ");
                $subscription_paid = false;

                if ($subscription_stmt) {
                    mysqli_stmt_bind_param(
                        $subscription_stmt,
                        'iiii',
                        $user_data['user_id'],
                        $user_data['boss_id'],
                        $current_month,
                        $current_year
                    );
                    mysqli_stmt_execute($subscription_stmt);
                    mysqli_stmt_store_result($subscription_stmt);
                    $subscription_paid = mysqli_stmt_num_rows($subscription_stmt) === 1;
                    mysqli_stmt_close($subscription_stmt);
                }

                if (!$subscription_paid) {
                    $_SESSION['subscription_payment_context'] = [
                        'tenant_db' => [
                            'host' => $tenant['db_host'],
                            'user' => $tenant['db_user'],
                            'pass' => $tenant['db_pass'],
                            'name' => $tenant['db_name']
                        ],
                        'tenant_id' => (int)$tenant['tenant_id'],
                        'user_id' => (int)$user_data['user_id'],
                        'boss_id' => (int)$user_data['boss_id'],
                        'return_to' => 'index'
                    ];
                    $show_subscription_payment = true;
                    $msg_error = "<div style='background-color:red; border-radius:5px; color:white; 
                    height:40px; margin-left:2px; padding:7px; width:400px'>
                    Your monthly subscription is not active.
                    <a href='pages/payments/subscription.php?return=index' style='color:white; margin-left:20px;'>Make Payment</a></div>";
                } else {

                // Step 4: Persist dynamic connection configuration in Session
                $_SESSION['tenant_db'] = [
                    'host' => $tenant['db_host'],
                    'user' => $tenant['db_user'],
                    'pass' => $tenant['db_pass'],
                    'name' => $tenant['db_name']
                ];
                $_SESSION['email']    = $user_data['username'];
                $_SESSION['category'] = $user_data['category'];
                $_SESSION['user_id']  = $user_data['user_id'];
                $_SESSION['boss_id']  = $user_data['boss_id'];
                $_SESSION['tenant_id'] = (int)$tenant['tenant_id'];

                // Route according to user role
                switch ($user_data['category']) {
                    case 'Administrator':
                        header("Location: pages/admin/admin_homepage.php");
                        break;
                    case 'Admin':
                        header("Location: pages/admin_homepage.php");
                        break;
                    case 'Officer':
                        header("Location: pages/officer_homepage.php");
                        break;
                    default:
                        header("Location: pages/user_homepage.php");
                        break;
                }
                exit();
                }
            } else {
                $msg_error = "<div style='color:red;'>Error! Invalid Username or Password.</div>";
            }
            mysqli_close($tenant_conn);
        } else {
            $msg_error = "<div style='color:red;'>Tenant Database Connection Failed.</div>";
        }
    } else {
        $msg_error = "<div style='color:red;'>Account not registered or inactive.</div>";
    }
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
width:500px; 
margin-left:50px; 
margin-top:0px; 
padding-left:80px;
padding-top:60px;
padding-right:10px;
border-radius:2px; 
border: 1px solid #006F37;
height:340px;
}

#main_heading{
margin-left:50px;
margin-bottom: 0px;
width: 500px;
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
.subscription-modal {
display: none;
position: fixed;
z-index: 10;
inset: 0;
background: rgba(0,0,0,.55);
}
.subscription-modal-content {
background: white;
width: min(520px, 90%);
height: 560px;
margin: 6vh auto;
border-radius: 4px;
}
.subscription-modal-content iframe {
width: 100%;
height: 100%;
border: 0;
}
</style>

</head>
<body>

<main class="column main">  
<div id="main_body"> 
<div id="main_heading" align="center">
<font size="6" face="Verdana, Arial, Helvetica, sans-serif">
QuickAccounts <a href="kyaabel.php"><font color="#006F37">--</font></a></FONT><br>
<font size="2" face="Verdana, Arial, Helvetica, sans-serif">
Empowering Businesses to the next Level</font></div>                 
<div id="login_part" >   
<p> <b><?php error_reporting(0);echo $msg_error; echo $category;?>  

</b> </p>   
<br>                  
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"  method="post">                               

<p class="control is-expanded has-icons-left">
<input class="input is-success"  name="email" type="text" placeholder="&nbsp;&nbsp;&nbsp;Enter Username"
style="width:320px; font-size:20px; border: 1px solid #006F37;" required>
<span class="icon is-small is-left">
<i class="fa fa-envelope"></i>
</span>
</p>
<br> 

<p class="control is-expanded has-icons-left">
<input class="input is-success" placeholder="&nbsp;&nbsp;&nbsp;Enter Password" name="password" type="password" 
style="width:320px; font-size:20px; border: 1px solid #006F37;" required>
<span class="icon is-small is-left">
<i class="fa fa-unlock"></i>
</span>
</p>
<br> 

<button type="submit" class="button is-primary"  name="login" 
style="border: 1px solid #006F37; color:white; background-color:#006F37;">
&nbsp;&nbsp;&nbsp;&nbsp;LOGIN&nbsp;&nbsp;&nbsp;&nbsp;</button>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="forgot_password.php"><font color="#006F37" size="4">Forgot Password</font></a>
</form>

</div>
</div> 
</div>
</main>
<?php if ($show_subscription_payment): ?>
<div class="subscription-modal" id="subscription-modal" role="dialog" aria-modal="true">
<div class="subscription-modal-content">
<iframe src="pages/payments/subscription.php?return=index" title="System subscription payment"></iframe>
</div>
</div>
<script>
document.getElementById('subscription-modal').style.display = 'block';
</script>
<?php endif; ?>
</body> 
</html>

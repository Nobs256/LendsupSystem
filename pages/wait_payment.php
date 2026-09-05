<?php
include('header_user.php');

$ref = trim($_GET['ref'] ?? '');
if ($ref === '') {
    header('Location: buy_sms.php');
    exit;
}
?>
<main class="column main" style="background-color:#EAEAEA;">
<div id="main_container" style="height: auto;">
   <div id="main_body">
<div style="max-width:600px;margin:40px auto;text-align:center">

    <h2>Approve Payment</h2>

    <p>
        Please approve the Mobile Money request
        on your phone.
    </p>

    <img src="assets/images/loading1.gif">

    <br><br>

    <div id="status">
        Waiting for payment confirmation...
    </div>

</div>
  </div>
  </div>
</main>

<script>

function checkPayment()
{
    fetch('check_payment.php?ref=<?=rawurlencode($ref)?>&t=' + Date.now(), {
        cache: 'no-store'
    })
    .then(function (res) {
        if (!res.ok) {
            throw new Error('Unable to check payment status');
        }
        return res.json();
    })
    .then(data => {

        if(data.status === 'success')
        {
            document.getElementById('status').innerHTML =
            '<div style="color:green;font-weight:bold">' +
            'Payment confirmed. SMS credited successfully.' +
            '</div>';

            clearInterval(paymentPoll);
            setTimeout(function(){
                window.location='buy_sms.php';
            },3000);
        }

        if(data.status === 'failed')
        {
            clearInterval(paymentPoll);
            document.getElementById('status').innerHTML =
            '<div style="color:red;font-weight:bold">' +
            'Payment failed.' +
            '</div>';
        }
    });
}

var paymentPoll = setInterval(checkPayment,5000);
checkPayment();

</script>

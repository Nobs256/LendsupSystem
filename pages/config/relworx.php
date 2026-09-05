<?php

/*
|--------------------------------------------------------------------------
| Relworx Configuration
|--------------------------------------------------------------------------
*/

define('RELWORX_API_KEY', '03d52951a59f3a.NMkBNPFqbVo4YKkomv4WCg');

define('RELWORX_ACCOUNT_NO', 'REL6207350641');

/*
|--------------------------------------------------------------------------
| Unique System Code
|--------------------------------------------------------------------------
| Used to identify which system initiated the payment.
| Examples:
| QA7, QA5,
|--------------------------------------------------------------------------
*/
define('SYSTEM_CODE', 'QALDSS');

/*
|--------------------------------------------------------------------------
| Master Callback
|--------------------------------------------------------------------------
| This is the ONLY callback configured in Relworx.
|--------------------------------------------------------------------------
*/
define('RELWORX_CALLBACK_URL', 'https://ky.quickaccount7.biz/Trust/pages/relworx_webhook.php');
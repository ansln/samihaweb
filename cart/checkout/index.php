<?php

require_once "src/co.php";
require_once "src/cartSession.php";
require_once 'src/vendor/autoload.php';
require_once '../../auth/conn.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use GuzzleHttp\RetryMiddleware;

$cM = new checkoutManagement;
$sM = new sessionManagement;

//check userSession
$cM->getSessionId();

//create session token
if (empty($_COOKIE['INVCSESS'])) {
    $sM->cartSessionToken();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="../../style/checkout.css"><link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>
<body>
    <div class="top-nav">
        <div class="top-logo">
            <a href="../../"><img src="../../assets/img/logo/logo.png"></a>
        </div>
    </div>
    <div class="co-container">
        <div class="co-ct-wrapper">

            <div class="ct-box-left">
                <div class="top-title">
                    <h1>Checkout</h1>
                </div>
                <div class="box-left-content">
                    <div class="box-left-content-wrapper">
                        <h2>Alamat Pengiriman</h2>

                        <div class="ct-content-1">
                            <?php $cM->getUserAddress(); ?>
                        </div>

                        <div class="ct-content-2">
                            <?php $cM->getUserCart(); ?>
                        </div>
                        
                        <div class="ct-content-3">
                            <?php $cM->getOngkir(); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ct-box-right">
                <div class="box-right-wrapper">
                    <div class="box-content">
                        
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="../../js/coAdd.js"></script>
</body>
</html>
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "src/co.php";
require_once "../../auth/conn2.php";
require_once "../../auth/session.php";
$coValidation = new checkoutV2;
$paymentDetail = new paymentDetail;

if (isset($_GET["order_id"])) {
    $token = $_GET["order_id"];
    
    if (empty($token)) {
        echo "tidak ada data";
    }else{
        ?>
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Samiha - Notif</title>
                <link rel="stylesheet" href="../../style/checkout.css">
                <script src="https://code.iconify.design/iconify-icon/1.0.1/iconify-icon.min.js"></script>
            </head>
            <body>
                <div class="container-pay">
                    <div class="ct-wrapper-pay">
                        <div class="card-box-pay">
                            <iconify-icon icon="ic:round-verified-user"></iconify-icon>
                            <div id="top-title-pay">Pembayaran Terverifikasi</div>
                            <div class="row-text-pay">
                                <span>Invoice ID:</span>
                                <div id="inv-data-pay"><?= $token ?></div> 
                            </div>
                            <div id="desc-text-pay">Kamu akan menerima email terkait pesanan ini dan kami akan memproses pesanan kamu, lalu kamu bisa cek status pesanan kamu di halaman daftar transaksi.</div>
                            <button id="goBackBtnPay">Kembali</button>
                            <div id="ct-support-pay">Mengalami masalah? hubungi <a href="">support</a></div>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    const backBtn = document.getElementById("goBackBtnPay");
                    
                    backBtn.addEventListener("click", function() {
                        window.location.replace('../../');
                    });
                    </script>
            </body>
            </html>
        <?php
        $coValidation->updateItem($token);
        $coValidation->updateInvoice($token);
    }
}elseif (isset($_GET["payment"])) {
    $token = $_GET["payment"];

    if (empty($token)) {
        echo "tidak ada data";
    }else{
        ?>
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Samiha - Notif</title>
                <link rel="stylesheet" href="../../style/checkout.css">
                <script src="https://code.iconify.design/iconify-icon/1.0.1/iconify-icon.min.js"></script>
            </head>
            <body>
                <div class="container-pay">
                    <div class="ct-wrapper-pay">
                        <div class="card-box-pay">
                            <iconify-icon icon="eva:info-fill"></iconify-icon>
                            <div id="top-title-pay">Selesaikan Pembayaran</div>
                            <div class="row-text-pay">
                                <span>Invoice ID:</span>
                                <div id="inv-data-pay"><?= $token ?></div> 
                            </div>
                                <?= $paymentDetail->paymentData($token); ?>
                            <button id="goBackBtnTx">Kembali</button>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    const backBtn = document.getElementById("goBackBtnTx");
                    
                    backBtn.addEventListener("click", function() {
                        window.location.replace('../../order-list/');
                    });
                    </script>
            </body>
            </html>
        <?php
        $coValidation->updateItem($token);
        $coValidation->updateInvoice($token);
    }
}else{
    ?><script>window.location.replace('../../');</script><?php
}

?>
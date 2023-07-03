<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'src/vendor/autoload.php';
require_once '../../auth/conn.php';
require_once '../../auth/conn2.php';
require_once '../../auth/session.php';
require_once 'src/co.php';

$checkout = new checkoutManagement;
$coValidation = new checkoutV2;
$coDetail = new checkoutDetail;
$coValidation->getStatus(); //run check function and get token

$shippingPrice = $checkout->fetchShippingPrice();
$totalProductPrice = $checkout->getProductPrice();
$grandTotalPrice = $shippingPrice + $totalProductPrice;

$getPaymentDetail = $coDetail->getTxDetail();
$getPaymentId = $getPaymentDetail["order_id"];

$checkout->newInvoiceItem($getPaymentId); //update cart data to invoice item

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Samiha - Checkout</title>
    <script src="../../js/jquery-3.6.0.min.js"></script><script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="Mid-client-su0c4nw1jnPC7qgj"></script><script src='//cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <link rel="stylesheet" href="../../style/checkout.css"><link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css"><link rel="stylesheet" href="../../style/cssImages.css">
</head>
<body>
    <?php $coValidation->demoAccCondition(); ?>
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
                            <?php $checkout->getUserAddress(); ?>
                        </div>

                        <div class="ct-content-2">
                            <?php $checkout->getUserCart(); ?>
                        </div>
                        
                        <div class="ct-content-3">
                            <?php $checkout->getOngkir(); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ct-box-right">
                <div class="box-right-wrapper">
                    <div class="box-content">
                        <div id="right-top-title">Rincian Pembelian</div>
                        <div class="content-mid">
                            <div class="row-mid">
                                <div id="text-child-title">Total Harga (<?= $checkout->getTotalQuantity(); ?> Produk)</div>
                                <div id="product-price">Rp<?= number_format($totalProductPrice,0,"",".") ?></div>
                            </div>
                            <div class="row-mid">
                                <div id="text-child-title">Total Ongkos Kirim</div>
                                <div id="product-price">Rp<?= number_format($shippingPrice,0,"","."); ?></div>
                            </div>
                        </div>
                        <div class="grand-total">
                            <div class="row">
                                <div id="child-title">Total Pembayaran</div>
                                <div id="child-ship">Rp<?= number_format($grandTotalPrice,0,"","."); ?></div>
                            </div>
                            <button id="choosePaymentBtn">Pilih Pembayaran</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div id="show-data-api"></div>
    <script src="../../js/coAdd.js"></script>
    <script>
        var shippingInput = <?= $shippingPrice ?>;
        $('#choosePaymentBtn').on("click", function(e){
            e.preventDefault();

            if (shippingInput == 0) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Pilih ongkir terlebih dahulu!',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    showConfirmButton: false
                })
                setTimeout(function(){
                    location.reload();
                }, 2000);
            }else{
                var spinnerLoading = '<span class="loader2"></span>';
                $("#choosePaymentBtn").html(spinnerLoading);
                
                $.ajax({
                    url:"src/get?pay",
                    type:"POST",
                    cache:false,
                    processData:false,
                    contentType:false,
                    
                    success:function(data){
                        $("#show-data-api").html(data);
                    }
                });
            }
        });
    </script>
</body>
</html>
<?php $checkout->userProductUpdate(); ?>
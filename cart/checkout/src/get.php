<?php

require_once "../../../auth/conn2.php";
require_once '../../../auth/session.php';
require_once "co.php";

$getInvoice = new checkoutManagement;
$totalProductPrice = $getInvoice->getProductPrice();
$totalProductWeight = $getInvoice->getProductWeight();
$userDestination = $getInvoice->getPostalCode();

//item data
$items = array(
    "name" => "Book",
    "description" => "Zero to One",
    "length" => "10",
    "width" => "25",
    "height" => "20",
    "weight" => $totalProductWeight,
    "value" => $totalProductPrice
);

//final data with origin and destination
$data = array(
    "origin_postal_code" => 17148,
    "destination_postal_code" => $userDestination,
    "couriers" => "jne,jnt,pos,anteraja",
    "items" => [$items]
);

$url = "https://api.biteship.com/v1/rates/couriers";

$ch = curl_init($url);
$payload = json_encode($data);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json", "authorization: biteship_live.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoic2FtaWhhU2hpcHBpbmciLCJ1c2VySWQiOiI2MzM1MTI4MmY0ZWNmNDJlYTA4MzIyNjAiLCJpYXQiOjE2NjQ0MjI2Njl9.gq8_rpKLqFSRgQ6ATWQfny7zHpOU-Oymv3R27Aaug8U"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);

$curlDecode = json_decode($result, true);
$deliveryData = $curlDecode["pricing"];

?>
    <script>
    var itemList = document.getElementById("itemList");
    var itemListLi= document.querySelectorAll("#itemList li"), tab = [];
    var getData = [<?php foreach ($deliveryData as $fetch) {

        $shippingName = '{"shippingName":"' . $fetch['courier_name'] . ' ' . $fetch['courier_service_name'] . '",';
        $shippingPrice = '"shippingPrice":"' . $fetch['price'] . '",';
        $est = '"shippingEst":"Estimasi pengiriman ' . $fetch['shipment_duration_range'];
        $duration = $fetch['shipment_duration_unit'];
        if ($duration == "days") { $duration = "hari";}elseif ($duration == "hours") { $duration = "jam";}
        $shippingEst = $est . ' ' . $duration . '",' . '},';

        echo $shippingName . $shippingPrice . $shippingEst; } ?>];

    const addLi = function (array) {
        
        for (let i = 0; i < array.length; i++) {

            var shippingName = getData[i].shippingName;
            var shippingPrice = getData[i].shippingPrice;
            var shippingEst = getData[i].shippingEst;

            const idr = new Intl.NumberFormat(`id-ID`, {
                currency: `IDR`,
                style: 'decimal',
            }).format(shippingPrice);

            var item = document.createElement("li");
            item.innerHTML = "<div id='ship-title'>" + shippingName + "</div>" +
            "<div id='ship-price'>" + "Rp" + idr + "</div>" +
            "<div id='ship-est'>" + shippingEst + "</div>";
            itemList.appendChild(item);

            $(item).click(function() {
                index = getData.indexOf(this.innerHTML);

                var getShipping = this.innerText;
                var crypS2 = btoa(getShipping);
                var sCrypt = btoa(crypS2);

                var getPrice = getData[i].shippingPrice;
                var crypP2 = btoa(getPrice);
                var pCrypt = btoa(crypP2);

                var getEst = getData[i].shippingEst;
                var crypE2 = btoa(getEst);
                var eCrypt = btoa(crypE2);

                // PUSH TO INPUT
                const inputShippingName = document.getElementById("userOngkirName");
                inputShippingName.value = getData[i].shippingName;
                const inputShipping = document.getElementById("userOngkir");
                inputShipping.value = sCrypt;
                const inputShippingPrice = document.getElementById("userOngkirPrice");
                inputShippingPrice.value = pCrypt;
                const inputShippingEst = document.getElementById("userOngkirEst");
                inputShippingEst.value = eCrypt;
            });
            
        }
    return itemList;
    }
    document.getElementById("show").appendChild(addLi(getData));

</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<ul id="itemList"></ul>
<?php

if (isset($_GET["sbShip"])) {
    $getSpVal = new shippingValidation;
    $selectedShipping = $_POST["selectedShippingName"];
    $shippingPrice = $_POST["shippingPrice"];
    $shippingEstimation = $_POST["shippingEst"];

    if (empty($selectedShipping)) {
        ?><script>window.location.replace('../')</script><?php
    }else{
        $getSpVal->pushShippingToDb($selectedShipping, $shippingPrice, $shippingEstimation);
    }
}if (isset($_GET["pay"])) {
    // \Midtrans\Config::$serverKey = 'SB-Mid-server-fQVGpchXCGIDzpwLaLAbZTrb';
    $mdAuth = new paymentDetail;
    $midtransServerKey = $mdAuth->midtransPayAuth();
    \Midtrans\Config::$serverKey = $midtransServerKey;
    \Midtrans\Config::$isProduction = true;
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;
    
    $coValidation = new checkoutV2;
    $payment = new checkoutDetail;
    $getTokenForPayment = $payment->getToken();
    $getLink = $coValidation->getLinkInvoice();
    $getPendingLink = $coValidation->getPendingLinkInvoice();
    $snapToken = \Midtrans\Snap::getSnapToken($getTokenForPayment);
    ?><style> .swal2-popup { font-size: 14px; }</style><script type="text/javascript">
        window.snap.pay('<?= $snapToken ?>', {
            onSuccess: function(result){ // sudah dibayar
                Swal.fire({
                    toast: true,
                    position: 'top',
                    icon: 'info',
                    title: 'Mohon tunggu',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    showConfirmButton: false
                })
                setTimeout(function(){
                    window.location.replace('<?= $getLink ?>');
                }, 3000);
            },
            onPending: function(result){ // menunggu dibayar
                Swal.fire({
                    toast: true,
                    position: 'top',
                    icon: 'info',
                    title: 'Mohon tunggu',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    showConfirmButton: false
                })
                setTimeout(function(){
                    window.location.replace('<?= $getPendingLink ?>');
                }, 3000);
            },
            onError: function(result){ // pembayar gagal
                Swal.fire({
                    toast: true,
                    position: 'top',
                    icon: 'error',
                    title: 'Transaksi gagal',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    showConfirmButton: false
                })
                setTimeout(function(){
                    window.location.replace('../');
                }, 3000);
            },
            onClose: function(){ // pembayaran dibatalkan / tutup popup pembayaran
                Swal.fire({
                    toast: true,
                    position: 'top',
                    icon: 'error',
                    title: 'Transaksi dibatalkan',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    showConfirmButton: false
                })
                setTimeout(function(){
                    location.reload();
                }, 3000);
            }
        });
    </script>
    <?php
}

?>
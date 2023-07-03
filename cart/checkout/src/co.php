<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';
require_once 'cartSession.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class checkoutManagement{

    //get SecretKey for login session JWT
    private function getSecret(){
        $get = new userSession;
        $secretKey = $get->generateSecretKey();
        $secretKeyUserSession = $secretKey;
        return $secretKeyUserSession;
    }

    //sanitize input, etc
    public function sanitize($value){
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    
    //get connection from database
    public function getConnection(){
        $db = new mysqli("localhost","u362596482_samiha_shop","!_Samih@!db_Password_Shop_135790_TEMP!","u362596482_shop");
        return $db;
    }

    //connection v2 more secure
    protected function connectionV2(){
        $get = new connection;
        $db = $get->getDb();
        return $db;
    }
    
    //decode the user email from jwt session
    public function decodeEmailSession(){
        $db = $this->connectionV2();
        $key = $this->getSecret();
        $token = $_COOKIE['SMHSESS'];
        $sanitize = $this->sanitize($token);
        $jwt = $db->real_escape_string($sanitize);
        
        $payload = JWT::decode($jwt, new Key($key, 'HS256'));
        $userEmail = $payload->userEmail;
        return $userEmail;
    }

    protected function getUserId(){
        $db = $this->connectionV2();
        $userEmail = $this->decodeEmailSession();

        $userSession = mysqli_query($db, "SELECT * FROM user WHERE u_email = '$userEmail' OR u_phone = '$userEmail'");
        $check = mysqli_num_rows($userSession);

        if ($check < 0) {
            echo "no";
        }else{
            if($userSession->num_rows){
                while($r = $userSession->fetch_object()){
                    $userId = $r->id;
                    return $userId;
                }
            }
        }
    }

    //get user address data from database
    public function getUserAddress(){
        $db = $this->connectionV2();
        $sess = $this->decodeEmailSession();
        $email = $this->sanitize($sess);
        $userEmail = $db->real_escape_string($email);

        $userDb = $db->query("SELECT * FROM user WHERE u_email = '$userEmail' OR u_phone = '$userEmail'");

        if($userDb->num_rows){
            while($user = $userDb->fetch_object()){
                $userId = $user->id;
                
                $userAddressDb = $db->query("SELECT * FROM user_address WHERE userId = '$userId' AND addressPrimary = 'primary'");
                $userAddressCheck = mysqli_num_rows($userAddressDb);
                if ($userAddressCheck >= 0) {
                    echo "";
                }if ($userAddressCheck <= 0) {
                    ?><p>kamu belum memiliki alamat, daftarkan alamatmu <a href="../../user/address">disini</a></p><?php
                }

                if($userAddressDb->num_rows){
                    while($userAddress = $userAddressDb->fetch_object()){

                        $addressRecName = $userAddress->u_recName;
                        $addressLabel = $userAddress->u_addressLabel;
                        $addressPhone = $userAddress->u_phone;
                        $addressComplete = $userAddress->u_completeAddress;
                        $addressMix = $userAddress->u_addressMix;
                        $addressPostalCode = $userAddress->u_postalCode;
                        ?>
                            <div class="row-content">
                                <b><?= $addressRecName ?></b><p>(<?= $addressLabel ?>)</p>
                            </div>
                            <div class="row-content2">
                                <p><?= $addressPhone ?></p>
                            </div>
                            <div class="row-content3">
                                <p><?= $addressComplete ?></p>
                                <p><?= $addressMix ?></p>
                            </div>
                        <?php
                    }
                }
            }
        }
    }

    private function getUserAddressPostalCode(){
        $db = $this->connectionV2();
        $sess = $this->decodeEmailSession();
        $email = $this->sanitize($sess);
        $userEmail = $db->real_escape_string($email);

        $userDbQuery = $db->query("SELECT * FROM user WHERE u_email = '$userEmail' OR u_phone = '$userEmail'");
        $check = mysqli_num_rows($userDbQuery);
        if ($check <= 0) {
            ?><script>window.location.replace('../../');</script><?php
        }else{
            if($userDbQuery->num_rows){
                while($user = $userDbQuery->fetch_object()){
                    $userId = $user->id;
                    
                    $userAddressDb = $db->query("SELECT * FROM user_address WHERE userId = '$userId' AND addressPrimary = 'primary'");
                    $userAddressCheck = mysqli_num_rows($userAddressDb);
                    if ($userAddressCheck <= 0) {
                        ?><script>window.location.replace('../../');</script><?php
                    }else{
                        if($userAddressDb->num_rows){
                            while($userAddress = $userAddressDb->fetch_object()){
                                $addressPostalCode = $userAddress->u_postalCode;
                                return $addressPostalCode;
                            }
                        }
                    }
                }
            }
        }

    }

    //get user cart data from database
    public function getUserCart(){
        $getCM = new checkoutManagement;
        $db = $getCM ->getConnection();
        $sess = $getCM->decodeEmailSession();
        $weight = 0;
        $price = 0;
        $qtyCart = 0;

        $userQuery = $db->query("SELECT * FROM user WHERE u_email = '$sess' OR u_phone = '$sess'");

        if($userQuery->num_rows){
            while($u = $userQuery->fetch_object()){

                $userId = $u->id;
                $userProductQuery = $db->query("SELECT * FROM cart WHERE userId=$userId AND qty != 0");
                $userProductQueryFunc = $db->query("SELECT * FROM cart WHERE userId=$userId");

                //function for check value of qty cart
                foreach ($userProductQueryFunc as $dtCart) {
                    $totalQtyCart = $dtCart["qty"];
                    $qtyCart+=$totalQtyCart;
                }

                $userProductQueryCheck = mysqli_num_rows($userProductQuery);
                if ($qtyCart <= 0) {
                    ?><script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script><style> .swal2-popup { font-size: 12px; }</style><script>
                        Swal.fire({
                            toast: true,
                            position: 'top',
                            icon: 'error',
                            title: 'Keranjang kamu masih kosong',
                            showClass: {
                                popup: 'animate__animated animate__fadeInDown'
                            },
                            showConfirmButton: false
                        })
                        setTimeout(function(){
                            window.location.replace('../');
                        }, 3000);
                    </script><?php
                }elseif ($userProductQueryCheck <= 0) {
                    ?><script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script><style> .swal2-popup { font-size: 12px; }</style><script>
                        Swal.fire({
                            toast: true,
                            position: 'top',
                            icon: 'error',
                            title: 'Keranjang kamu masih kosong',
                            showClass: {
                                popup: 'animate__animated animate__fadeInDown'
                            },
                            showConfirmButton: false
                        })
                        setTimeout(function(){
                            window.location.replace('../');
                        }, 3000);
                    </script><?php
                }else{
                    if($userProductQuery->num_rows){
                        while($uP = $userProductQuery->fetch_object()){
    
                            $productId = $uP->productId;
                            $productQty = $uP->qty;
                            $productQuery = $db->query("SELECT * FROM product WHERE pd_id=$productId");
    
                            if($productQuery->num_rows){
                                while($p = $productQuery->fetch_object()){
    
                                    $pdImg = $p->pd_img;
                                    $pdName = $p->pd_name;
                                    $pdPrice = $p->pd_price;
                                    $pdWeight = $p->pd_weight;
                                    $pdTotalWeight = $pdWeight * $productQty;
                                    $allWeight = $weight+=$pdTotalWeight;
                                    $totalPricePerProduct = $pdPrice * $productQty;
                                    $allTotalPriceProduct = $price+=$totalPricePerProduct;
    
                                    ?>
                                        <div class="ct2-card">
                                            <div class="ct2-card-left">
                                                <img src="<?= $pdImg ?>">
                                            </div>
                                            <div class="ct2-card-right">
                                                <h3><?= $pdName ?> - <?= $pdWeight ?>gram</h3>
                                                <p><?= $productQty ?> barang (<?= $pdWeight * $productQty ?> gr)</p>
                                                <b>Rp<?= number_format($pdPrice,0,"",".") ?></b>
                                            </div>
                                        </div>
                                    <?php
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    
    private function fetchShippingInvoice(){
        $db = $this->connectionV2();
        $userId = $this->getUserId();
        $userData = mysqli_query($db, "SELECT * FROM invoice WHERE userId = '$userId' ORDER BY invoiceIdPK DESC LIMIT 1");
        $userAddressData = mysqli_query($db, "SELECT * FROM user_address WHERE userId = '$userId' AND addressPrimary = 'primary'");
        $userAddressDataCheck = mysqli_num_rows($userAddressData);
        $userDataCheck = mysqli_num_rows($userData);
        if ($userDataCheck > 0) {
            if($userData->num_rows){
                while($r = $userData->fetch_object()){
                    $userShipping = $r->userShipping;
                    $userShippingEst = $r->userShippingEst;
                    if ($userAddressDataCheck <= 0) {
                        ?><button id="getOngkir2">Pilih Pengiriman</button><?php
                    }else{
                        if ($userShipping != "") {
                            ?><button id="getOngkir"><?= $userShipping . " (" . $userShippingEst . ")" ?></button><?php
                        }else{
                            ?><button id="getOngkir">Pilih Pengiriman</button><?php
                        }
                    }
                }
            }
        }
    }

    //get invoice detail
    private function getTotalProductWeight(){
        $db = $this->connectionV2();
        $userId = $this->getUserId();
        $cartQuery = $db->query("SELECT * FROM cart WHERE userId = '$userId'");
        $checkQueryCart = mysqli_num_rows($cartQuery);
        $weightTotal = 0;
        
        if ($checkQueryCart >= 1) {
            foreach ($cartQuery as $cartFetch) {
                $productId = $cartFetch["productId"];
                $productQty = $cartFetch["qty"];
                $productQuery = $db->query("SELECT * FROM product WHERE pd_id = '$productId'");
                foreach ($productQuery as $productFetch) {
                    $productWeight = $productFetch["pd_weight"];
                    $productTotalWeight = $productWeight*$productQty;
                }
                $weightTotal+=$productTotalWeight; // -> ALL QTY TOTAL WITH CALC
            }
        }
        return $weightTotal;
    }

    private function getShippingPrice(){
        $db = $this->connectionV2();
        $userId = $this->getUserId();
        $invoiceData = mysqli_query($db, "SELECT * FROM invoice WHERE userId = '$userId' ORDER BY invoiceIdPK DESC LIMIT 1");
        $invoiceDataCheck = mysqli_num_rows($invoiceData);
    
        if ($invoiceDataCheck <= 0) {
            echo "tidak ada data invoice";
        }else{
            foreach ($invoiceData as $fetch) {
                $shippingPrice = $fetch["userShippingPrice"];
                return $shippingPrice;
            }
        }

    }

    private function getTotalQty(){
        $db = $this->connectionV2();
        $userId = $this->getUserId();
        $cartQuery = $db->query("SELECT * FROM cart WHERE userId = '$userId'");
        $checkQueryCart = mysqli_num_rows($cartQuery);
        $qtyTotal = 0;
        
        if ($checkQueryCart >= 1) {
            foreach ($cartQuery as $cartFetch) {
                $productQty = $cartFetch["qty"];
                $qtyTotal+=$productQty; // -> ALL QTY TOTAL WITH CALC
            }
        }
        return $qtyTotal;
    }

    private function getTotalProductPrice(){
        $db = $this->connectionV2();
        $userId = $this->getUserId();
        $cartQuery = $db->query("SELECT * FROM cart WHERE userId = '$userId'");
        $checkQueryCart = mysqli_num_rows($cartQuery);
        $grandTotal = 0;
        
        if ($checkQueryCart >= 1) {
            foreach ($cartQuery as $cartFetch) {
                $productId = $cartFetch["productId"];
                $productQty = $cartFetch["qty"];
                $productQuery = $db->query("SELECT * FROM product WHERE pd_id = '$productId'");
                foreach ($productQuery as $productFetch) {
                    $productPrice = $productFetch["pd_price"];
                    $totalPrice = $productPrice * $productQty;
                }
                $grandTotal+=$totalPrice;
            }
        }
        return $grandTotal;
    }

    private function updatePriceWeightDb(){
        error_reporting(0);
        $db = $this->connectionV2();
        $userId = $this->getUserId();
        $totalProductPrice = $this->getTotalProductPrice();
        $totalWeight = $this->getTotalProductWeight();
        $userInvoice = $db->query("SELECT * FROM invoice WHERE userId = '$userId' AND invoiceStatus != 'done' ORDER BY invoiceIdPK DESC LIMIT 1");
        $check = mysqli_num_rows($userInvoice);
        if ($check <= 0) {
            ?><script>console.log('missing invoice data')</script><?php
        }else{
            foreach ($userInvoice as $fetch) {
                $invoiceId = $fetch["invoiceId"];

                $updateInvoiceData = $db->query("UPDATE invoice SET totalProductPrice = '$totalProductPrice', totalProductWeight = '$totalWeight' WHERE invoiceId = '$invoiceId'");
                $update = mysqli_query($db, $updateInvoiceData);
                return $update;
            }
        }
    }
    
    //fetch shipping data from API
    private function fetchOngkir(){
        ?>
        <h2>Pilih Pengiriman</h2>
        <div class="wrapper">
            <div class="ct-wrapper">
                <div class="box">        
                    <form enctype="multipart/form-data" id="fetchOngkir">
                        <div class="row-btn">
                            <?= $this->fetchShippingInvoice(); ?>
                        </div>
                    </form>
                    <div class="modal-container">
                        <div class="md-ct-wrapper">
                            <div id="show"></div>
                            <form action="src/get?sbShip" method="POST" id="selectedOptions">
                                <input type="text" value="" id="userOngkirName" name="selectedShippingName" placeholder="Pengiriman yang dipilih" readonly>
                                <input type="hidden" value="" id="userOngkir" name="selectedShipping">
                                <input type="hidden" value="" id="userOngkirEst" name="shippingEst">
                                <input type="hidden" value="" id="userOngkirPrice" name="shippingPrice">
                                <button type="submit" name="submitOngkir" class="selected" id="submitBtn">Pilih</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
        <style> .swal2-popup { font-size: 14px; }</style>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            const inputBox = document.querySelector("li");
            $(".modal-container").css("display", "none");

            $('#getOngkir2').on('click', function(e){
                e.preventDefault();

                var spinnerLoading = '<span class="loader2"></span>';
                $("#getOngkir2").html(spinnerLoading);

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Tidak ada alamat terdaftar',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    showConfirmButton: false
                })
                setTimeout(function(){
                    window.location = ".";
                }, 2000);
            });

            $(document).ready(function(){
                $('#getOngkir').on('click', function(e){
                    e.preventDefault();
                    
                    var spinnerLoading = '<span class="loader2"></span>';
                    $("#getOngkir").html(spinnerLoading);

                    suggestion();
                });
            });

            function select(element){
                let selectUserData = element.firstChild.textContent;
                $("#getOngkir").html(selectUserData);
            }

            function suggestion(){
                $.ajax({
                    url:"src/get",
                    type:"POST",
                    cache:false,
                    processData:false,
                    contentType:false,
                    
                    success:function(data){
                        $("#show").html(data);
                        $(".modal-container").css("display", "flex");
                        $("#getOngkir").css("display", "none");
                        $("#selectedOptions").css("display", "block");

                        let allList = document.querySelectorAll("#itemList li");
                        for (let i = 0; i < allList.length; i++) {
                            allList[i].setAttribute("onclick", "select(this)");
                        }
                    }
                });
            }
        </script>
        <?php
    }

    //setter getter for showing select shipping feature
    public function getOngkir(){
        return $this->fetchOngkir();
    }
    
    protected function userCheck(){
        $db = $this->connectionV2();
        $usEmail = $this->decodeEmailSession();
        $usEmailS = $this->sanitize($usEmail);
        $userEmail = $db->real_escape_string($usEmailS);
        $userQuery = $db->query("SELECT * FROM user WHERE u_email = '$userEmail' OR u_phone = '$userEmail'");
        $checkQuery = mysqli_num_rows($userQuery);
        
        if ($checkQuery < 0) {
            echo "something error";
        }else{
            return true;
        }
    }

    //all check function
    private function checkUserLoginStatus(){
        $db = $this->connectionV2();
        if (isset($_COOKIE['SMHSESS'])) {
            //userTokenCheck
            $ck = $_COOKIE['SMHSESS'];
            $cookie = $db->real_escape_string($ck);
            $userSession = mysqli_query($db, "SELECT * FROM user_session WHERE user_jwt='$cookie'");
            $userSessionCheck = mysqli_num_rows($userSession);
            
            if ($userSessionCheck < 0) {
                ?><script>window.location.replace('../../')</script><?php
            }else{
                // echo "logged in";
                return $this->checkUserCartStatus();
            }
        }else{
            ?><script>window.location.replace('../../')</script><?php
        }
    }
    
    private function checkUserCartStatus(){
        $create = new cartToken;
        $db = $this->connectionV2();
        if (empty($_COOKIE['INVCSESS'])) {
            $create->createToken();
        }else{
            echo "token already created";
        }
    }

    private function getNowTime(){
        date_default_timezone_set("Asia/Jakarta");
        $date = date("d/m/Y - h:i:sa");
        return $date;
    }

    //for invoice item
    private function addInvoiceItem($paymentId){
        $db = $this->connectionV2();
        $userId = $this->getUserId();
        $time = $this->getNowTime();
        $checkoutV2 = new checkoutV2;
        $itemRandomId = rand();
        $getCurrentInvoiceId = $checkoutV2->currentInvoice();
        $PayIdSanitize = $this->sanitize($paymentId);
        $getPaymentId = $db->real_escape_string($PayIdSanitize);
        $getUserCart = mysqli_query($db, "SELECT * FROM cart WHERE userId = '$userId'");

        foreach ($getUserCart as $cart) {
            $productIdCart = $cart["productId"];
            $productQtyCart = $cart["qty"];
            $createInvoiceItemQuery = "INSERT INTO invoice_item VALUES(NULL, '$itemRandomId', '$getCurrentInvoiceId', '$getPaymentId', '$userId', '$productIdCart', '$productQtyCart', '$time', '')";
            mysqli_query($db, $createInvoiceItemQuery);
        }
        
    }

    private function checkInvoiceItem(){
        $db = $this->connectionV2();
        $userId = $this->getUserId();
        $getInvoiceItem = mysqli_query($db, "SELECT * FROM invoice_item WHERE userId = '$userId'");
        $check = mysqli_num_rows($getInvoiceItem);
        return $check;
    }
    
    private function createInvoiceItem($paymentId){
        $db = $this->connectionV2();
        $userId = $this->getUserId();
        $time = $this->getNowTime();
        $checkoutV2 = new checkoutV2;
        $itemRandomId = rand();
        $getCurrentInvoiceId = $checkoutV2->currentInvoice();
        $getUserCart = mysqli_query($db, "SELECT * FROM cart WHERE userId = '$userId'");
        $getCount = $this->checkInvoiceItem();
        $PayIdSanitize = $this->sanitize($paymentId);
        $getPaymentId = $db->real_escape_string($PayIdSanitize);

        foreach ($getUserCart as $cart) {
            $productIdCart = $cart["productId"];
            $productQtyCart = $cart["qty"];

            if ($getCount <= 0) {
                $createInvoiceItemQuery = "INSERT INTO invoice_item VALUES(NULL, '$itemRandomId', '$getCurrentInvoiceId', '$getPaymentId', '$userId', '$productIdCart', '$productQtyCart', '$time', '')";
                mysqli_query($db, $createInvoiceItemQuery);
            }else{
                $deleteItemQuery = "DELETE FROM invoice_item WHERE userId = '$userId' AND status != 'done'";
                mysqli_query($db, $deleteItemQuery);
                return $this->addInvoiceItem($getPaymentId);
            }
        }
    }

    public function newInvoiceItem($paymentId){
        return $this->createInvoiceItem($paymentId);
    }
    
    //setter getter
    //for shipping validation
    public function getCartSecret(){
        return $this->getSecret();
    }

    public function checkUserLogin(){
        return $this->checkUserLoginStatus();
    }

    public function getProductPrice(){
        return $this->getTotalProductPrice();
    }

    public function getProductWeight(){
        return $this->getTotalProductWeight();
    }

    public function getTotalQuantity(){
        return $this->getTotalQty();
    }

    public function getPostalCode(){
        return $this->getUserAddressPostalCode();
    }

    public function userProductUpdate(){
        return $this->updatePriceWeightDb();
    }

    public function fetchShippingPrice(){
        return $this->getShippingPrice();
    }

}

class cartToken{

    private function getDb(){
        $get = new connection;
        $db = $get->getDb();
        return $db;
    }

    private function sanitize($value){
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    private function getUserEmail(){
        $get = new userSession;
        $userEmail = $get->generateEmail();
        return $userEmail;
    }

    private function generateSecretKey(){
        $firstKey = base64_encode('SAMIHAKEY');
        $randString = "hOpVrvtpTU";
        $endKey = "-cart-key";

        $this->secretKey = $firstKey . $randString . $endKey;
        return $this->secretKey;
    }

    private function getNowTime(){
        date_default_timezone_set("Asia/Jakarta");
        $date = date("d/m/Y - h:i:sa");
        return $date;
    }

    private function getUserId(){
        $db = $this->getDb();
        $userEmail = $this->getUserEmail();
        $u_fetch = $db->query("SELECT * FROM user WHERE u_email = '$userEmail' OR u_phone = '$userEmail'");
        if($u_fetch->num_rows){
			while($r = $u_fetch->fetch_object()){
                $userId = $r->id;
                return $userId;
            }
        }
    }

    private function createCartToken(){
        $secretKey = $this->generateSecretKey();
        $userEmail = $this->getUserEmail();
        $getTime = $this->getNowTime();

        //generate cartSessionId
        $str = "cart-" . rand();
        $result = md5($str);
        $cartSessionId = $result;

        $payload = [
            "cartSessionId" => "$cartSessionId",
            "email" => $userEmail,
            "time" => $getTime
        ];
        
        $jwt = JWT::encode($payload, $secretKey, 'HS256');
        setcookie("INVCSESS", $jwt);
        return $this->cartTokenPushDb($jwt);
    }

    private function decodeCartSessionId($token){
        $key = $this->generateSecretKey();
        $jwt = $token;
        
        $payload = JWT::decode($jwt, new Key($key, 'HS256'));
        $sessionId = $payload->cartSessionId;
        return $sessionId;
    }

    private function cartTokenPushDb($token){
        $db = $this->getDb();

        // jwt cart
        $userId = $this->getUserId();
        $cookieS = $this->sanitize($token);
        $userCartJWT = $db->real_escape_string($cookieS);

        //sessionId 
        $cartSessId = $this->decodeCartSessionId($token);
        $cartSessIdS = $this->sanitize($cartSessId);
        $cartSessionId = $db->real_escape_string($cartSessIdS);

        $insertToDbQuery = "INSERT INTO user_session VALUES(NULL, '$cartSessionId', '$userId', '$userCartJWT', '', 'checkout')";
        mysqli_query($db, $insertToDbQuery);      
    }

    public function createToken(){
        return $this->createCartToken();
    }
}

class shippingValidation{

    private function getDb(){
        $get = new connection;
        $db = $get->getDb();
        return $db;
    }

    private function sanitize($value){
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    private function decodeEmailSession(){
        $sM = new checkoutManagement;
        $key = $sM->getCartSecret();
        $jwt = $_COOKIE['SMHSESS'];
        
        $payload = JWT::decode($jwt, new Key($key, 'HS256'));
        $email = $payload->userEmail;
        $userEmail = $this->sanitize($email);
        return $userEmail;
    }

    private function getUserId(){
        $db = $this->getDb();
        $userEmail = $this->decodeEmailSession();

        $userSession = mysqli_query($db, "SELECT * FROM user WHERE u_email = '$userEmail' OR u_phone = '$userEmail'");
        $check = mysqli_num_rows($userSession);

        if ($check < 0) {
            echo "no";
        }else{
            if($userSession->num_rows){
                while($r = $userSession->fetch_object()){
                    $userId = $r->id;
                    return $userId;
                }
            }
        }
    }

    private function getSelectedShipping($shipName, $shipPrice, $shipEst){
        $db = $this->getDb();
        $userId = $this->getUserId();

        $shippingSanitize = $this->sanitize($shipName);
        $shippingName = $db->real_escape_string($shippingSanitize);

        $priceDecode = base64_decode($shipPrice);
        $priceDecode2 = base64_decode($priceDecode);
        $priceSanizite = $this->sanitize($priceDecode2);
        $shippingPrice = $db->real_escape_string($priceSanizite);

        $estDecode = base64_decode($shipEst);
        $estDecode2 = base64_decode($estDecode);
        $shippingSanitize = $this->sanitize($estDecode2);
        $shippingEstimation = $db->real_escape_string($shippingSanitize);

        $updateShippingQuery = $db->query("UPDATE invoice SET userShipping = '$shippingName', userShippingPrice = '$shippingPrice', userShippingEst = '$shippingEstimation' WHERE userId = '$userId' ORDER BY invoiceIdPK DESC LIMIT 1");
        ?><script>window.location.replace('../')</script><?php
        mysqli_query($db, $updateShippingQuery);
    }

    //setter getter
    public function pushShippingToDb($shippingName, $shippingPrice, $shippingEst){
        return $this->getSelectedShipping($shippingName, $shippingPrice, $shippingEst);
    }
}

class checkoutV2{

    private function getDb(){
        $new = new connection;
        $db = $new->getDb();
        return $db;
    }

    public function sanitize($value){
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    private function checkUserLogin(){
        $db = $this->getDb();
        $session = new userSession;
        if (isset($_COOKIE['SMHSESS'])) {
            //userTokenCheck
            $ck = $_COOKIE['SMHSESS'];
            $cookie = $db->real_escape_string($ck);
            $userSession = mysqli_query($db, "SELECT * FROM user_session WHERE user_jwt='$cookie'");
            $userSessionCheck = mysqli_num_rows($userSession);
            
            if ($userSessionCheck > 0) {
                //fetch email from user
                $userEmail = $session->generateEmail();
                $userData = mysqli_query($db, "SELECT * FROM user WHERE u_email='$userEmail' OR u_phone='$userEmail'");
                $userDataCheck = mysqli_num_rows($userData);
                if ($userDataCheck > 0) {
                    return $this->checkCheckoutToken();
                }else{ ?><script>window.location.replace("../../");</script><?php }
            }else{ ?><script>window.location.replace("../../");</script><?php }
        }else{ ?><script>window.location.replace("../../");</script><?php }
    }

    private function generateSecretKey(){
        $firstKey = base64_encode('SAMIHAKEY');
        $randString = "hOpVrvtpTU";
        $endKey = "-checkout-key";

        $this->secretKey = $firstKey . $randString . $endKey;
        return $this->secretKey;
    }

    private function getNowTime(){
        date_default_timezone_set("Asia/Jakarta");
        $date = date("d/m/Y - h:i:sa");
        return $date;
    }

    private function getUserId(){
        $session = new userSession;
        $db = $this->getDb();
        $userEmail = $session->generateEmail();
        $u_fetch = $db->query("SELECT * FROM user WHERE u_email = '$userEmail' OR u_phone = '$userEmail'");
        if($u_fetch->num_rows){
			while($r = $u_fetch->fetch_object()){
                $userId = $r->id;
                return $userId;
            }
        }
    }

    private function getUserCart(){
        $db = $this->getDb();
        $userId = $this->getUserId();
        $userProductQuery = $db->query("SELECT * FROM cart WHERE userId=$userId");
        $userProductQueryCheck = mysqli_num_rows($userProductQuery);
        return $userProductQueryCheck;
    }

    private function createCartToken(){
        $session = new userSession;
        $db = $this->getDb();
        $secretKey = $this->generateSecretKey();
        $userId = $this->getUserId();
        $userEmail = $session->generateEmail();
        $getTime = $this->getNowTime();
        
        $str = "cart-" . rand();
        $result = md5($str);
        $cartSessionId = $result;
        
        $payload = [
            "cartSessionId" => "$cartSessionId",
            "email" => $userEmail,
            "time" => $getTime
        ];
        
        $jwt = JWT::encode($payload, $secretKey, 'HS256');
        //push to db
        $cartSessionTokenQuery = "INSERT INTO user_session VALUES(NULL, '', $userId, '$jwt', '', 'checkout', '')";
        mysqli_query($db, $cartSessionTokenQuery);
        header('Location: .');
    }
    
    private function checkCheckoutToken(){
        $db = $this->getDb();
        $userId = $this->getUserId();
        $userCart = $this->getUserCart();
        
        $checkoutTokenQuery = mysqli_query($db, "SELECT * FROM user_session WHERE userId = '$userId' AND type = 'checkout' AND tokenStatus != 'expired' ORDER BY sessionIdPK DESC LIMIT 1");
        $sessionCheck = mysqli_num_rows($checkoutTokenQuery);
        
        if ($sessionCheck <= 0) { //jika token tidak ada di table user_session
            if ($userCart <= 0) {
                ?><script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script><style> .swal2-popup { font-size: 12px; }</style><script>
                    Swal.fire({
                        toast: true,
                        position: 'top',
                        icon: 'error',
                        title: 'Keranjang kamu masih kosong',
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        showConfirmButton: false
                    })
                    setTimeout(function(){
                        window.location.replace('../');
                    }, 3000);
                </script><?php
            }else{
                return $this->createCartToken(); //create token
            }
        }else{ //jika token ada di table user_session
            foreach ($checkoutTokenQuery as $fetch) {
                $token = $fetch["user_jwt"];
                return $this->checkUserInvoice($token);
            }
        }
    }

    //invoice function
    private function createInvoiceId(){
        $db = $this->getDb();
        date_default_timezone_set("Asia/Jakarta");
        $date = date("dmY");

        $data = mysqli_query($db, "SELECT invoiceId FROM invoice ORDER BY invoiceIdPK DESC LIMIT 1");

        $fetch = mysqli_fetch_array($data);
        $no = $fetch['invoiceId'];

        $create = substr($no, 17, 5);
        $add = (int) $create + 1;

        if (strlen($add) == 1) {
            $format = "SDO-" . $date . "-SMH-" . "0000" . $add;
        }else if (strlen($add) == 2) {
            $format = "SDO-" . $date . "-SMH-" . "000" . $add;
        }else if (strlen($add) == 3) {
            $format = "SDO-" . $date . "-SMH-" . "00" . $add;
        }else if (strlen($add) == 4) {
            $format = "SDO-" . $date . "-SMH-" . "0" . $add;
        }else{
            $format = "SDO-" . $date . "-SMH-" . $add;
        }

        $noOrder = $format;
        return $noOrder;
    }

    private function createTime(){
        date_default_timezone_set("Asia/Jakarta");
        $date = date("d/m/Y h:i:sa");
        return $date;
    }

    private function checkUserInvoice($token){
        $db = $this->getDb();
        $sanitize = $this->sanitize($token);
        $tokenClear = $db->real_escape_string($sanitize); 
        $invoiceQuery = mysqli_query($db, "SELECT * FROM invoice WHERE invoiceToken = '$tokenClear' AND invoiceStatus != 'done'");
        $invoiceCheck = mysqli_num_rows($invoiceQuery);

        if ($invoiceCheck <= 0) {
            return $this->createInvoice($tokenClear);
        }
    }

    private function createInvoice($token){
        $db = $this->getDb();
        $invoiceId = $this->createInvoiceId();
        $userId = $this->getUserId();
        $invoiceTime = $this->createTime();
        
        $createInvoiceQuery = "INSERT INTO invoice VALUES(NULL, '$invoiceId', '$token', '$invoiceTime', '$userId', '', '', '', '', '', '')";
        mysqli_query($db, $createInvoiceQuery);
    }

    private function getCurrentInvoice(){
        $db = $this->getDb();
        $userId = $this->getUserId();
        $getTokenQuery = mysqli_query($db, "SELECT * FROM user_session WHERE userId = '$userId' AND type = 'checkout' AND tokenStatus != 'expired' ORDER BY sessionIdPK DESC LIMIT 1");
        
        foreach ($getTokenQuery as $session) {
            $userInvoiceToken = $session["user_jwt"];
            $getUserInvoice = mysqli_query($db, "SELECT * FROM invoice WHERE invoiceToken = '$userInvoiceToken'");
            foreach ($getUserInvoice as $invoice) {
                $invoiceId = $invoice["invoiceId"];
                $link = "notif?order_id=" . $invoiceId;
                return $link;
            }
        }
    }

    private function getCurrentPendingInvoice(){
        $db = $this->getDb();
        $userId = $this->getUserId();
        $getTokenQuery = mysqli_query($db, "SELECT * FROM user_session WHERE userId = '$userId' AND type = 'checkout' AND tokenStatus != 'expired' ORDER BY sessionIdPK DESC LIMIT 1");
        
        foreach ($getTokenQuery as $session) {
            $userInvoiceToken = $session["user_jwt"];
            $getUserInvoice = mysqli_query($db, "SELECT * FROM invoice WHERE invoiceToken = '$userInvoiceToken'");
            foreach ($getUserInvoice as $invoice) {
                $invoiceId = $invoice["invoiceId"];                
                $link = "notif?payment=" . $invoiceId;
                return $link;
            }
        }
    }

    private function currentInvoiceData(){
        $db = $this->getDb();
        $userId = $this->getUserId();
        $getTokenQuery = mysqli_query($db, "SELECT * FROM user_session WHERE userId = '$userId' AND type = 'checkout' AND tokenStatus != 'expired' ORDER BY sessionIdPK DESC LIMIT 1");
        
        foreach ($getTokenQuery as $session) {
            $userInvoiceToken = $session["user_jwt"];
            $getUserInvoice = mysqli_query($db, "SELECT * FROM invoice WHERE invoiceToken = '$userInvoiceToken'");
            foreach ($getUserInvoice as $invoice) {
                $invoiceId = $invoice["invoiceId"];
                return $invoiceId;
            }
        }
    }

    //update data after transaction success
    private function updateInvoiceItem($invoice){
        $db = $this->getDb();
        $invoiceSanitize = $this->sanitize($invoice);
        $userInvoiceId = $db->real_escape_string($invoiceSanitize);
        
        $updateInvoiceStatusQuery = "UPDATE invoice_item SET status = 'done' WHERE invoiceId = '$userInvoiceId'";
        mysqli_query($db, $updateInvoiceStatusQuery);
    }

    private function updateInvoiceAfter($invoice){
        error_reporting(0);
        $db = $this->getDb();
        $userId = $this->getUserId();
        $getDate = $this->getNowTime();
        $invoiceSanitize = $this->sanitize($invoice);
        $userInvoiceId = $db->real_escape_string($invoiceSanitize);
        $userDataInvoice = mysqli_query($db, "SELECT * FROM invoice WHERE invoiceId = '$userInvoiceId'");
        
        foreach ($userDataInvoice as $user) {
            $getToken = $user["invoiceToken"];

            $updateInvoiceData = "UPDATE invoice SET invoiceStatus = 'done' WHERE invoiceId = '$userInvoiceId'";
            $updateSessionData = "UPDATE user_session SET tokenStatus = 'expired' WHERE user_jwt = '$getToken'";
            $deleteCartBefore = "DELETE FROM cart WHERE userId = '$userId'";
            mysqli_query($db, $updateInvoiceData); //update invoice
            mysqli_query($db, $updateSessionData); //update session
            mysqli_query($db, $deleteCartBefore); //delete cart
        }
    }

    private function accStatusDemo(){
        $db = $this->getDb();
        $session = new userSession;

        if (isset($_COOKIE['SMHSESS'])) {
            //userTokenCheck
            $ck = $_COOKIE['SMHSESS'];
            $cookie = $db->real_escape_string($ck);
            $userSession = mysqli_query($db, "SELECT * FROM user_session WHERE user_jwt='$cookie'");
            $userSessionCheck = mysqli_num_rows($userSession);
            
            if ($userSessionCheck > 0) {
                //fetch email from user
                $userEmail = $session->generateEmail();
                
                if ($userEmail == "demo@samiha.id") {
                    ?><script type="text/javascript">
                        Swal.fire({
                            toast: true,
                            position: 'top',
                            icon: 'error',
                            title: 'Fitur tidak tersedia untuk akun demo',
                            showClass: {
                                popup: 'animate__animated animate__fadeInDown'
                            },
                            showConfirmButton: false
                        })
                        setTimeout(function(){
                            window.location = "../";
                        }, 2000);
                    </script><?php
                }
            }else{ ?><script>window.location.replace("../../");</script><?php }
        }else{ ?><script>window.location.replace("../../");</script><?php }
    }
    
    //setter getter
    public function getStatus(){
        return $this->checkUserLogin();
    }

    public function updateInvoice($token){
        return $this->updateInvoiceAfter($token);
    }

    public function updateItem($token){
        return $this->updateInvoiceItem($token);
    }

    public function getLinkInvoice(){
        return $this->getCurrentInvoice();
    }

    public function getPendingLinkInvoice(){
        return $this->getCurrentPendingInvoice();
    }

    public function currentInvoice(){
        return $this->currentInvoiceData();
    }

    public function demoAccCondition(){
        return $this->accStatusDemo();
    }
}

// PAYMENT GATEWAY //
class checkoutDetail{

    private function getDb(){
        $get = new connection;
        $db = $get->getDb();
        return $db;
    }

    private function getSecret(){
        $get = new userSession;
        $secretKey = $get->generateSecretKey();
        $secretKeyUserSession = $secretKey;
        return $secretKeyUserSession;
    }

    public function decodeEmailSession(){
        $db = $this->getDb();
        $key = $this->getSecret();
        $token = $_COOKIE['SMHSESS'];
        $sanitize = $this->sanitize($token);
        $jwt = $db->real_escape_string($sanitize);
        
        $payload = JWT::decode($jwt, new Key($key, 'HS256'));
        $userEmail = $payload->userEmail;
        return $userEmail;
    }

    protected function getUserId(){
        $db = $this->getDb();
        $userEmail = $this->decodeEmailSession();

        $userSession = mysqli_query($db, "SELECT * FROM user WHERE u_email = '$userEmail' OR u_phone = '$userEmail'");
        $check = mysqli_num_rows($userSession);

        if ($check < 0) {
            echo "something wrong";
        }else{
            if($userSession->num_rows){
                while($r = $userSession->fetch_object()){
                    $userId = $r->id;
                    return $userId;
                }
            }
        }
    }

    private function sanitize($value){
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    private function txDetail(){
        $db = $this->getDb();
        $userId = $this->getUserId();
        $queryGetUserInvoice = mysqli_query($db, "SELECT * FROM invoice WHERE userId = '$userId' AND invoiceStatus != 'done' ORDER BY invoiceIdPK DESC LIMIT 1");
        
        foreach ($queryGetUserInvoice as $invoice) {
            $invoiceId = $invoice["invoiceId"];
            $shippingPrice = $invoice["userShippingPrice"];
            $totalProductPrice = $invoice["totalProductPrice"];
            $grossAmount = $shippingPrice + $totalProductPrice;

            $transaction_details = array(
                'order_id' => $invoiceId,
                'gross_amount' => $grossAmount,
            );
            return $transaction_details;
        }
    }

    private function getShippingDetail(){
        $db = $this->getDb();
        $userId = $this->getUserId();
        $queryGetUserAddress = mysqli_query($db, "SELECT * FROM user_address WHERE userId = '$userId' AND u_defaultAddress = '1'");
            
        foreach ($queryGetUserAddress as $address) {
            $receiptName = $address["u_recName"];
            $region = $address["u_addressMix"];
            $splitAddress = preg_split("/[\s,]+/", $region);
            $addressCity = $splitAddress[1];
            $fullAddress = $address["u_completeAddress"];
            $postalCode = $address["u_postalCode"];
            $userPhone = $address["u_phone"];

            $shipping_address = array(
                'first_name'    => $receiptName,
                'last_name'     => '',
                'address'       => $fullAddress,
                'city'          => $addressCity,
                'postal_code'   => $postalCode,
                'phone'         => $userPhone,
                'country_code'  => 'IDN'
            );

            return $shipping_address;
        }
    }

    private function getBillingDetail(){
        $db = $this->getDb();
        $userId = $this->getUserId();
        $queryGetUserData = mysqli_query($db, "SELECT * FROM user WHERE id = '$userId'");
        $queryGetUserAddress = mysqli_query($db, "SELECT * FROM user_address WHERE userId = '$userId' AND u_defaultAddress = '1'");
            
        foreach ($queryGetUserData as $user) {
            $userFirstName = $user["u_fName"];
            $userLastName = $user["u_lName"];
            $userPhone = $user["u_phone"];
        
            foreach ($queryGetUserAddress as $address) {
                $region = $address["u_addressMix"];
                $splitAddress = preg_split("/[\s,]+/", $region);
                $addressCity = $splitAddress[1];
                $fullAddress = $address["u_completeAddress"];
                $postalCode = $address["u_postalCode"];

                $billing_address = array(
                    'first_name'    => $userFirstName,
                    'last_name'     => $userLastName,
                    'address'       => $fullAddress,
                    'city'          => $addressCity,
                    'postal_code'   => $postalCode,
                    'phone'         => $userPhone,
                    'country_code'  => 'IDN'
                );

                return $billing_address;
            }
        }
    }

    private function getCustomerDetail(){
        $db = $this->getDb();
        $userId = $this->getUserId();
        $getUserBilling = $this->getBillingDetail();
        $getUserShipping = $this->getShippingDetail();
        $queryGetUserData = mysqli_query($db, "SELECT * FROM user WHERE id = '$userId'");

        foreach ($queryGetUserData as $user) {
            $userFirstName = $user["u_fName"];
            $userLastName = $user["u_lName"];
            $userPhone = $user["u_phone"];
            $userEmail = $user["u_email"];

            $customer_details = array(
                'first_name'    => $userFirstName,
                'last_name'     => $userLastName,
                'email'         => $userEmail,
                'phone'         => $userPhone,
                'billing_address'  => $getUserBilling,
                'shipping_address' => $getUserShipping
            );
            return $customer_details;
        }
    }

    private function getItemDetail(){
        $db = $this->getDb();
        $dataItem = array();
        $userId = $this->getUserId();
        $queryGetCart = mysqli_query($db, "SELECT * FROM cart WHERE userId = '$userId'");
        $queryGetUserInvoice = mysqli_query($db, "SELECT * FROM invoice WHERE userId = '$userId' AND invoiceStatus != 'done' ORDER BY invoiceIdPK DESC LIMIT 1");

        //for product
        foreach ($queryGetCart as $cartData) {
            $productId = $cartData["productId"];
            $productQty = $cartData["qty"];
            $queryGetProduct = mysqli_query($db, "SELECT * FROM product WHERE pd_id = '$productId'");
            foreach ($queryGetProduct as $product) {
                $productId = $product["pd_id"];
                $getProductPrice = $product["pd_price"];
                $productName = $product["pd_name"];

                $item_details = array(
                    'id' => $productId,
                    'price' => $getProductPrice,
                    'quantity' => $productQty,
                    'name' => $productName
                );
                
                array_push($dataItem, $item_details);
            }
        }

        // for shipping
        foreach ($queryGetUserInvoice as $invoice) {
            $shippingPrice = $invoice["userShippingPrice"];
            $shippingName = $invoice["userShipping"];

            $item_details = array(
                'id' => 'shipping',
                'price' => $shippingPrice,
                'quantity' => 1,
                'name' => $shippingName
            );

            array_push($dataItem, $item_details);
        }
        return $dataItem;
    }

    private function createTransactionDetail(){
        $transactionDetail = $this->txDetail();
        $customerDetail = $this->getCustomerDetail();
        $itemDetail = $this->getItemDetail();

        $transaction = array(
            'transaction_details' => $transactionDetail,
            'customer_details' => $customerDetail,
            'item_details' => $itemDetail
        );
        
        return $transaction;
    }

    private function testFunction($data){
        $db = $this->getDb();
        $invGet = new checkoutV2;
        $invoiceId = $invGet->currentInvoice();

        $userId = $this->getUserId();
        $dataSanitize = $this->sanitize($data);
        
        $insert = "INSERT INTO invoice_item VALUES(NULL, '$invoiceId', '$dataSanitize', $userId, 0, 0, '')";
        mysqli_query($db, $insert);

    }
    //setter getter
    public function getTxDetail(){
        return $this->txDetail();
    }

    public function getToken(){
        return $this->createTransactionDetail();
    }

    public function pushOrderToken($data){
        return $this->testFunction($data);
    }
}

class paymentDetail{

    private function getDb(){
        $getDb = new connection;
        $callDb = $getDb->getDb();
        return $callDb;
    }

    private function sanitize($value){
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    private function getUserEmail(){
        $db = $this->getDb();
        $session = new userSession;
        $userEmail = $session->generateEmail();
        $userEmailSanitize = $this->sanitize($userEmail);
        $userEmailClear = $db->real_escape_string($userEmailSanitize);
        return $userEmailClear;
    }

    private function getUserId(){
        $db = $this->getDb();
        $get = new userSession;
        $email = $get->generateEmail();
        $userData = mysqli_query($db, "SELECT * FROM user WHERE u_email = '$email' OR u_phone = '$email'");
        foreach ($userData as $user) {
            $userId = $user["id"];
            return $userId; 
        }
    }

    private function midtransAuth(){
        $midtransServerKey = "Mid-server-CBBdLp5fmuQ-o88qGxHd2_vT";
        return $midtransServerKey;
    }

    public function midtransPayAuth(){
        return $this->midtransAuth();
    }

    private function getPaymentDataAPI($token){
        $invoiceId = $this->sanitize($token);

        $urlForStatusTx = "https://api.midtrans.com/v2/$invoiceId/status";
        // $str = "SB-Mid-server-fQVGpchXCGIDzpwLaLAbZTrb"; //sandbox
        $str = "Mid-server-CBBdLp5fmuQ-o88qGxHd2_vT";
        $token = base64_encode($str);

        //CURL CITY
        $midtransCurl = curl_init();
        curl_setopt_array($midtransCurl, array(
            CURLOPT_URL => $urlForStatusTx,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Content-Type: application/json",
                "Authorization: $token"
            )
        ));

        $loadCurl = curl_exec($midtransCurl);
        curl_close($midtransCurl);
        $curlDecode = json_decode($loadCurl, true);
        $this->getPayType($curlDecode);
    }

    private function bankPaymentIcon($value){
        $img = '';

        if ($value == "bca") {
            $img = '<img src="https://ik.imagekit.io/samiha/payment_logo/BCA_logo_Bank_Central_Asia_QF4K2vpQB.png">';
        }elseif ($value == "bni") {
            $img = '<img src="https://ik.imagekit.io/samiha/payment_logo/105-1051729_bank-negara-indonesia-logo-bank-bni-transparan-clipart_SDqTLyrs7.png">';
        }elseif ($value == "bri") {
            $img = '<img src="https://ik.imagekit.io/samiha/payment_logo/logo-bank-BRI-baru_237-design_8jkaAC6Ao.png">';
        }elseif ($value == "permata") {
            $img = '<img src="https://ik.imagekit.io/samiha/payment_logo/Logo_Bank_Permata_GdsDJba2m.png">';
        }elseif ($value == "mandiri") {
            $img = '<img src="https://ik.imagekit.io/samiha/payment_logo/Mandiri_logo_8qAvZbTm6.png">';
        }

        return $img;
    }

    private function cstorePaymentIcon($value){
        $img = '';

        if ($value == "indomaret") {
            $img = '<img src="https://ik.imagekit.io/samiha/payment_logo/indomaret_qJuoW4q_j-.png">';
        }elseif ($value == "alfamart") {
            $img = '<img src="https://ik.imagekit.io/samiha/payment_logo/Logo_Alfamart_2RXEM_ZQw.png">';
        }

        return $img;
    }

    private function getPayType($data){
        $paymentType = $data["payment_type"];
        // echo json_encode($data);

        if ($paymentType == "bank_transfer") { //bank transfer

            $bankCode = $data["va_numbers"][0]["bank"];
            $getBankImage = $this->bankPaymentIcon($bankCode);
            $paymentCode = $data["va_numbers"][0]["va_number"];
            $bankName = strtoupper($bankCode) . " " . "Virtual Account";
            $getPrice = $data["gross_amount"];
            $getTxTime = $data["transaction_time"];
            $paymentDueDate = date('d M Y, H:i', strtotime("+1 day", strtotime($getTxTime)));
            $totalPayment = "Rp" . number_format($getPrice,0,"",".");

            ?><div id="desc-text-pay">Kamu akan menerima email terkait pesanan ini, segera selesaikan pembayaran. Batas akhir pembayaran<span><?= $paymentDueDate ?></span></div>
                <div class="card-pay-dtl">
                    <div class="top-sec-pay-dtl">
                        <div id="payment-type-pay-dtl"><?= $bankName ?></div>
                        <div id="payment-logo-pay-dtl"><?= $getBankImage ?></div>
                    </div>
                    <div class="bottom-sec-pay-dtl">
                        <div class="btm-sec1-pay-dtl">
                            <div id="payment-code-title-pay-dtl">Nomor Virtual Account</div>
                            <div id="payment-code-pay-dtl"><?= $paymentCode ?></div>
                        </div>
                        <div class="btm-sec2-pay-dtl">
                            <div id="payment-amount-title-pay-dtl">Total Pembayaran</div>
                            <div id="payment-amount-pay-dtl"><?= $totalPayment ?></div>
                        </div>
                    </div>
                </div><?php
        }elseif ($paymentType == "cstore") { //indomaret, alfamart, etc

            $storeName = ucfirst($data["store"]);
            $getIcon = $this->cstorePaymentIcon(strtolower($storeName));
            $paymentCode = $data["payment_code"];
            $getPrice = $data["gross_amount"];
            $getTxTime = $data["transaction_time"];
            $paymentDueDate = date('d M Y, H:i', strtotime("+1 day", strtotime($getTxTime)));
            $totalPayment = "Rp" . number_format($getPrice,0,"",".");

            ?><div id="desc-text-pay">Kamu akan menerima email terkait pesanan ini, segera selesaikan pembayaran. Batas akhir pembayaran<span><?= $paymentDueDate ?></span></div>
                <div class="card-pay-dtl">
                    <div class="top-sec-pay-dtl">
                        <div id="payment-type-pay-dtl"><?= $storeName ?></div>
                        <div id="payment-logo-pay-dtl"><?= $getIcon ?></div>
                    </div>
                    <div class="bottom-sec-pay-dtl">
                        <div class="btm-sec1-pay-dtl">
                            <div id="payment-code-title-pay-dtl">Kode Pembayaran</div>
                            <div id="payment-code-pay-dtl"><?= $paymentCode ?></div>
                        </div>
                        <div class="btm-sec2-pay-dtl">
                            <div id="payment-amount-title-pay-dtl">Total Pembayaran</div>
                            <div id="payment-amount-pay-dtl"><?= $totalPayment ?></div>
                        </div>
                    </div>
                </div><?php
        }elseif ($paymentType == "qris") { //qris

            $transactionIdQRIS = $data["transaction_id"];
            $storeName = strtoupper($data["acquirer"]);
            $getPrice = $data["gross_amount"];
            $getTxTime = $data["transaction_time"];
            $expireTime = strtotime($data["expire_time"]);
            $paymentDueDate = date("d M Y H:i:s", $expireTime);
            $totalPayment = "Rp" . number_format($getPrice,0,"",".");

            ?><div id="desc-text-pay">Kamu akan menerima email terkait pesanan ini, segera selesaikan pembayaran. Batas akhir pembayaran<span><?= $paymentDueDate ?></span></div>
                <div class="card-pay-dtl">
                    <div class="top-sec-pay-dtl">
                        <div id="payment-type-pay-dtl"><?= $storeName . "/QRIS" ?></div>
                        <div id="payment-logo-pay-dtl"><img src="https://ik.imagekit.io/samiha/payment_logo/QRIS__Quick_Response_Code_Indonesia_Standard__Logo__PNG720p__-_Vector69Com_xEer30mb8g.png"></div>
                    </div>
                    <div class="bottom-sec-pay-dtl">
                        <div class="btm-sec1-pay-dtl">
                            <div id="payment-code-title-pay-dtl">Kode Pembayaran</div>
                            <div id="payment-code-pay-dtl"><div id="qris-img-wrapper"><?php if ($expireTime < time()) { echo "Batas waktu pembayaran habis"; }else{ echo '<img src="https://api.veritrans.co.id/v2/qris/' . $transactionIdQRIS . '/qr-code">'; } ?></div></div>
                        </div>
                        <div class="btm-sec2-pay-dtl">
                            <div id="payment-amount-title-pay-dtl">Total Pembayaran</div>
                            <div id="payment-amount-pay-dtl"><?= $totalPayment ?></div>
                        </div>
                    </div>
                </div><?php
        }
    }

    //setter getter
    public function paymentData($token){
        $this->getPaymentDataAPI($token);
    }
}
<?php

error_reporting(0);

require_once __DIR__ . '/vendor/autoload.php';
require_once 'cartSession.php';
require_once '../../auth/session.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

    class checkoutManagement{

        protected $db;
        protected $email;
        protected $sessionId;
        protected $secretKeyUserSession;

        //get SecretKey for JWT
        public function getSecret(){
            $get = new userSession;
            $secretKey = $get->generateSecretKey();
            $this->secretKeyUserSession = $secretKey;
            return $this->secretKeyUserSession;
        }
        
        //get connection from database
        public function getConnection(){
            $this->db = new mysqli("localhost","root","","shop");
            return $this->db;
        }
        
        //decode the user email from jwt session
        public function decodeEmailSession(){
            $sM = new checkoutManagement;
            $key = $sM->getSecret();
            $jwt = $_COOKIE['SMHSESS'];
            
            $payload = JWT::decode($jwt, new Key($key, 'HS256'));
            $this->email = $payload->userEmail;
            return $this->email;
        }
        
        //decode the user sessionId from jwt session
        public function decodeSessionId(){
            $sM = new checkoutManagement;
            $key = $sM->getSecret();
            $jwt = $_COOKIE['SMHSESS'];
            
            $payload = JWT::decode($jwt, new Key($key, 'HS256'));
            $this->sessionId = $payload->sessionId;
            return $this->sessionId;
        }
        
        //get the user sessionId from database
        public function getSessionId(){
            $sM = new checkoutManagement;
            $userEmail = $sM->decodeEmailSession();
            $db = $sM->getConnection();
            
            $userDb = $db->query("SELECT * FROM user WHERE u_email = '$userEmail' OR u_phone = '$userEmail'");
            
            if($userDb->num_rows){
                while($user = $userDb->fetch_object()){
                    $userId = $user->id;
                    $userSess = $db->query("SELECT * FROM user_session WHERE userId = $userId ORDER BY sessionIdPK DESC LIMIT 1");
                    if($userSess->num_rows){
                        while($sess = $userSess->fetch_object()){
                            $sessionIdDb = $sess->sessionId;
                            $sessionIdJWT = $sM->decodeSessionId();

                            if ($sessionIdJWT != $sessionIdDb) {
                                header('Location: ../../logout.php');
                            }
                        }
                    }
                }
            }
        }

        //get user address data from database
        public function getUserAddress(){
            $getCM = new checkoutManagement;
            $db = $getCM->getConnection();
            $sess = $getCM->decodeEmailSession();

            $userDb = $db->query("SELECT * FROM user WHERE u_email = '$sess' OR u_phone = '$sess'");

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

        //get user cart data from database
        public function getUserCart(){
            $getCM = new checkoutManagement;
            $db = $getCM ->getConnection();
            $sess = $getCM->decodeEmailSession();

            $userQuery = $db->query("SELECT * FROM user WHERE u_email = '$sess' OR u_phone = '$sess'");

            if($userQuery->num_rows){
                while($u = $userQuery->fetch_object()){

                    $userId = $u->id;
                    $userProductQuery = $db->query("SELECT * FROM cart WHERE userId=$userId");

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

        //fetch shipping data from API
        public function getOngkir(){
            ?>
            <h2>Pilih Pengiriman</h2>
            <div class="wrapper">
                <div class="select-btn">
                    <span>Pilih Pengiriman</span>
                    <i class="uil uil-angle-down"></i>
                </div>
                <div class="content" id="transition">
                    <ul id="shippingOptions" class="options" name="shippingSelect">
                        <?php
                        include "../../user/ongkir/curl_delivery.php";
                        //FETCH JNE SHIPPING PRICE
                            foreach ($jneDecode as $row) {
                                $service = $row["service"];
                                $desc = $row["description"];
                                $price = $row["cost"][0]["value"];
                                $est = $row["cost"][0]["etd"];

                                ?><li onclick="updateSelect(this)">
                                    <b>JNE <?= $service . " (" . $desc . ")" ?></b>
                                    <div class="row">
                                        <p>Rp<?= number_format($price,0,"","."); ?></p>
                                        <p>Estimasi <?= $est ?> hari</p>
                                    </div>
                                </li><?php
                            }
                            //FETCH TIKI SHIPPING PRICE
                            foreach ($tikiDecode as $row) {
                                $service = $row["service"];
                                $desc = $row["description"];
                                $price = $row["cost"][0]["value"];
                                $est = $row["cost"][0]["etd"];

                                ?><li onclick="updateSelect(this)">
                                    <b>TIKI <?= $service . " (" . $desc . ")" ?></b>
                                    <div class="row">
                                        <p>Rp<?= number_format($price,0,"","."); ?></p>
                                        <p>Estimasi <?= $est ?> hari</p>
                                    </div>
                                </li><?php
                            }
                            //FETCH POS INDONESIA SHIPPING PRICE
                            foreach ($posDecode as $row) {
                                $service = $row["service"];
                                $desc = $row["description"];
                                $price = $row["cost"][0]["value"];
                                $est = $row["cost"][0]["etd"];

                                ?><li onclick="updateSelect(this)">
                                    <b><?= $service . " (" . $desc . ")" ?></b>
                                    <div class="row">
                                        <p>Rp<?= number_format($price,0,"","."); ?></p>
                                        <p>Estimasi <?= strtolower($est) ?> </p>
                                    </div>
                                </li><?php
                            }
                        ?>
                    </ul>
                </div>
                <input type="hidden" id="show" readonly disabled>
                <input type="hidden" name="getShippingSelect" id="getShippingSelect">
            </div>
            <?php
        }
    }
?>
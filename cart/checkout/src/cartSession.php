<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class sessionManagement{

    private $secretKey;
    private $invoiceToken;

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

    private function cartSessionTokenPriv(){
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
    }

    private function getUserSessionId(){
        $db = $this->getDb();
        
        if (isset($_COOKIE["INVCSESS"])) {
            $cookie= $_COOKIE['INVCSESS'];
            $cookieS = $this->sanitize($cookie);
            $jwt = $db->real_escape_string($cookieS);
            $secretKey = $this->generateSecretKey();
            $decode = JWT::decode($jwt, new Key($secretKey, 'HS256'));
            $cartSessionId = $decode->cartSessionId;
            return $cartSessionId;
        }else{
            echo "no token";
        }
    }
    
    private function checkCartToken(){
        $db = $this->getDb();
        $userCartSesionId = $this->getUserSessionId();
        $checkQuery = $db->query("SELECT * FROM user_session WHERE sessionId = '$userCartSesionId'");
        $check = mysqli_num_rows($checkQuery);

        if ($check > 1) {
            return true;
        }else{
            return false;
        }
    }
    
    private function cartSessionPushDb(){
        $db = $this->getDb();

        if (isset($_COOKIE["INVCSESS"])) {
            $userId = $this->getUserId();
            $cookie= $_COOKIE['INVCSESS'];
            $cookieS = $this->sanitize($cookie);
            $userCartJWT = $db->real_escape_string($cookieS);
            $cartSessionId = $this->getUserSessionId();

            $insertToDbQuery = "INSERT INTO user_session VALUES(NULL, '$cartSessionId', '$userId', '$userCartJWT', '', 'checkout')";
            mysqli_query($db, $insertToDbQuery);
        }else{
            echo "no token";
        }        
    }

    private function getCurrentCartSessionId(){
        $db = $this->getDb();
        $userId = $this->getUserId();
        $userCartSession = mysqli_query($db, "SELECT * FROM user_session WHERE userId ='$userId' ORDER BY sessionIdPK DESC LIMIT 1");
        $check = mysqli_num_rows($userCartSession);

        if ($check < 0) {
            echo "no data";
        }else{
            if($userCartSession->num_rows){
                while($r = $userCartSession->fetch_object()){
                    $cartSessionId = $r->sessionId;
                    return $cartSessionId;
                }
            }
        }
    }

    private function cartSessionCheck(){
        $getCheckoutVal = new checkoutManagement;
        $db = $this->getDb();
        $cartSessionId = $this->getCurrentCartSessionId();
        $cartSessionIdClear = $this->sanitize($cartSessionId);
        $userCartSession = mysqli_query($db, "SELECT * FROM user_session WHERE sessionId ='$cartSessionIdClear'");
        $check = mysqli_num_rows($userCartSession);

        if ($check < 0) {
            echo "err";
        }else{
            $getCheckoutVal->createCartInvoice();
        }
    }

    //setter getter
    public function cartSessionToken(){
        return $this->cartSessionTokenPriv();
    }

    public function pushCartSessionToDb(){
        return $this->cartSessionPushDb();
    }

    public function checkCartSession(){
        return $this->cartSessionCheck();
    }
}

?>
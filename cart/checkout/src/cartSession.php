<?php

require_once __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Firebase\JWT\JWT;

class sessionManagement{

    protected $secretKey;
    public $invoiceToken;

    public function cartSessionCheck($userSessionId){

    }

    public function generateSecretKey(){
        $firstKey = base64_encode('SAMIHAKEY');
        $randString = "hOpVrvtpTU";
        $endKey = "-cart-key";

        $this->secretKey = $firstKey . $randString . $endKey;
        return $this->secretKey;
    }

    public function cartSessionToken(){
        $getSc = new sessionManagement;
        $secretKey = $getSc->generateSecretKey();

        //generate cartSessionId
        $str = "cart-" . rand();
        $result = md5($str);
        $cartSessionId = $result;

        $payload = [
            "cartSessionId" => "$cartSessionId",
            "email" => "le@a.c"
        ];
        
        $jwt = JWT::encode($payload, $secretKey, 'HS256');
        setcookie("INVCSESS", $jwt);
    }
}

?>
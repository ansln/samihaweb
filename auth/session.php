<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'comp/vendor/autoload.php';
require_once 'conn2.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class userSession{

    protected $secretKey;
    public $jwtToken;
    public $getUserEmail;
    protected $getUserSessionId;

    private function getEmail(){
        $get = new connection;
        $db = $get->getDb();

        if (isset($_COOKIE['SMHSESS'])) {
            $userCookie = $_COOKIE['SMHSESS'];
            //get secretKey
            $getSc = new userSession;
            $secretKey = $getSc->generateSecretKey();

            $decode = JWT::decode($userCookie, new Key($secretKey, 'HS256'));
            $userEmail = $decode->userEmail;
            $sanitizeEmail = $this->sanitize($userEmail);
            $userEmailClear = $db->real_escape_string($sanitizeEmail);
            return $userEmailClear;
        }else{
            ?><script>window.location.replace("../");</script><?php
        }
    }

    private function sanitize($value){
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    public function generateSecretKey(){
        $firstKey = base64_encode('SAMIHAKEY');
        $randString = "67ICCe8FvN";
        $endKey = "-userAuth-key";

        $this->secretKey = $firstKey . $randString . $endKey;
        return $this->secretKey;
    }

    public function userAuth(string $email){
        //get secretKey
        $getSc = new userSession;
        $secretKey = $getSc->generateSecretKey();

        //generate sessionId
        $str=rand();
        $result = md5($str);
        $userSessionId = $result;

        $payload = [
            'sessionId' => $userSessionId,
            'userEmail' => $email
        ];
        
        $jwt = JWT::encode($payload, $secretKey, 'HS256');
        $this->jwtToken = $jwt;
        return $this->jwtToken;
    }

    private function token2($email){
        //get secretKey
        $secretKey = $this->generateSecretKey();

        //generate sessionId
        $str = rand();
        $result = md5($str);
        $userSessionId = $result;

        $payload = [
            'sessionId' => $userSessionId,
            'userEmail' => $email
        ];
        
        $jwt = JWT::encode($payload, $secretKey, 'HS256');
        return $jwt;
    }

    public function getToken2($email){
        return $this->token2($email);
    }

    public function generateToken(){
        $this->jwtToken = $_COOKIE['SMHSESS'];
        return $this->jwtToken;
    }

    public function generateSession(){
        $userCookie = $_COOKIE['SMHSESS'];
        //get secretKey
        $getSc = new userSession;
        $secretKey = $getSc->generateSecretKey();

        $decode = JWT::decode($userCookie, new Key($secretKey, 'HS256'));
        return $this->getUserSessionId = $decode->sessionId;
    }

    public function currentSession($userEmail){
        //get secretKey
        $getSc = new userSession;
        $secretKey = $getSc->generateSecretKey();

        //decode jwt
        if ($_COOKIE['SMHSESS']) {
            $jwt = $_COOKIE['SMHSESS'];

            $decode = JWT::decode($jwt, new Key($secretKey, 'HS256'));      
            return $this->$userEmail = $decode->userEmail;
        }else{
            echo "something error";
        }
    }

    //setter getter
    public function generateEmail(){
        return $this->getEmail();
    }
}

?>
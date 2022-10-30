<?php

require_once 'comp/vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class userSession{

    protected $secretKey;
    public $jwtToken;
    public $getUserEmail;
    protected $getUserSessionId;

    private function getEmail(){
        if (isset($_COOKIE['SMHSESS'])) {
            $userCookie = $_COOKIE['SMHSESS'];
            //get secretKey
            $getSc = new userSession;
            $secretKey = $getSc->generateSecretKey();

            $decode = JWT::decode($userCookie, new Key($secretKey, 'HS256'));
            return $this->getUserEmail = $decode->userEmail;
        }else{
            ?><script>window.location.replace("../logout.php");</script><?php
        }
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
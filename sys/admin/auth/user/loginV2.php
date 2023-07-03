<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "conn.php";
require_once "session.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class loginManagement{

    private function getDb(){
        $get = new connection;
        $db = $get->getDb();
        return $db;
    }

    private function sanitize($value){
        $db = $this->getDb();
        $getSanitize = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        $dbSanitize = $db->real_escape_string($getSanitize);
        return $dbSanitize;
    }

    private function generateSecretKey(){
        $firstKey = base64_encode('SAMIHAKEY');
        $randString = "69ICZZe8FvN";
        $endKey = "-admin-key";

        $this->secretKey = $firstKey . $randString . $endKey;
        return $this->secretKey;
    }

    private function userAuth($email, $password){
        $db = $this->getDb();
        $userEmail = $this->sanitize($email);
	    $pwd = $this->sanitize($password);
        $userPassword = md5($pwd);

        $verifyAdminFromDb = mysqli_query($db, "SELECT * FROM admin WHERE adm_email = '$userEmail' AND adm_password = '$userPassword'");
        $verifyAdminFromDbCheck = mysqli_num_rows($verifyAdminFromDb);

        if ($verifyAdminFromDbCheck <= 0) {
            echo "email or password wrong";
        }else{
            $this->createToken($userEmail);
        }
    }

    private function createToken($email){
        $db = $this->getDb();
        //get secretKey
        $secretKey = $this->generateSecretKey();

        //generate sessionId
        $str=rand();
        $result = md5($str);
        $userSessionId = $result;

        $payload = [
            'sessionId' => $userSessionId,
            'adminEmail' => $email
        ];
        
        $jwt = JWT::encode($payload, $secretKey, 'HS256');
        setcookie("ADMSESS", $jwt);

        //push to db
        $getAdminQuery = mysqli_query($db, "SELECT * FROM admin WHERE adm_email = '$email'");
        $getAdminQueryCheck = mysqli_num_rows($getAdminQuery);
        if ($getAdminQueryCheck >= 1) {
            foreach ($getAdminQuery as $user) {
                $userId = $user["adm_id"];
                $inputQuery = "INSERT INTO admin_session VALUES (NULL, '$userId', '$jwt', 'login')";
                mysqli_query($db, $inputQuery);
                ?><script>window.location.replace('./');</script><?php
            }
        }
    }

    public function loginAuth($email, $password){
        return $this->userAuth($email, $password);
    }

}

?>
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'conn.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class adminSession{

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

    private function getEmail(){
        $db = $this->getDb();
        if (isset($_COOKIE['ADMSESS'])) {
            $userCookie = $_COOKIE['ADMSESS'];
            $secretKey = $this->generateSecretKey();
    
            $decode = JWT::decode($userCookie, new Key($secretKey, 'HS256'));
            $userEmail = $decode->adminEmail;
            $sanitizeEmail = $this->sanitize($userEmail);
            $userEmailClear = $db->real_escape_string($sanitizeEmail);
            return $userEmailClear;
        }
    }

    private function getAdminId(){
        $db = $this->getDb();
        $userEmail = $this->getEmail();
        $verifyUserQuery = mysqli_query($db, "SELECT * FROM admin WHERE adm_email = '$userEmail'");
        $verifyUserQueryCheck = mysqli_num_rows($verifyUserQuery);
        if ($verifyUserQueryCheck <= 1) {
            foreach ($verifyUserQuery as $user) {
                $userId = $user["adm_id"];
                return $userId;
            }
        }
    }

    //setter getter
    public function getSecretKey(){
        return $this->generateSecretKey();
    }

    public function generateEmail(){
        return $this->getEmail();
    }

    public function generateId(){
        return $this->getAdminId();
    }
}

?>
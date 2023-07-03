<?php 

require_once 'auth/user/conn.php';
require_once 'auth/user/session.php';
require '../../auth/comp/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$getSc = new adminSession;
$getDb = new connection;
$secretKey = $getSc->getSecretKey();
$db = $getDb->getDb();

if (!isset($_COOKIE['ADMSESS'])) {
    ?><script>window.location.replace('login');</script><?php
}else{
    echo "please wait...";
    setcookie("ADMSESS", "");
    $jwt = $_COOKIE['ADMSESS'];

    try {
        $decode = JWT::decode($jwt, new Key($secretKey, 'HS256'));
    } catch (SignatureInvalidException $e) {
        ?><script>window.location.replace('./');</script><?php
    }
    
    $adminEmail = $decode->adminEmail;

    $userData = $db->query("SELECT * FROM admin WHERE adm_email = '$adminEmail'"); // -> query for fetch all data from user logged in
    
    if($userData->num_rows){
        while($r = $userData->fetch_object()){

            $userId = $r->id;
            date_default_timezone_set("Asia/Jakarta");
            $time = date("d m Y - h:i:sa");

            $updateLogoutData = "UPDATE admin_log SET u_logoutTime = '$time', u_logoutDate = '$date' WHERE userId = '$userId' ORDER BY id DESC LIMIT 1";
            ?><script>window.location.replace('./');</script><?php
            mysqli_query($db, $updateLogoutData);
        }
    }
}
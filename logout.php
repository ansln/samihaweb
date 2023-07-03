<?php

require_once 'auth/conn.php';
require_once 'auth/comp/vendor/autoload.php';
require_once 'auth/session.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

error_reporting(0);

$getSc = new userSession;
$secretKey = $getSc->generateSecretKey();

if ($_COOKIE['SMHSESS'] == "") {
    ?><script>window.location.replace('./');</script><?php
}else{
    echo "please wait...";
    setcookie("SMHSESS", "");
    $jwt = $_COOKIE['SMHSESS'];

    try {
        $decode = JWT::decode($jwt, new Key($secretKey, 'HS256'));
    } catch (SignatureInvalidException $e) {
        ?><script>window.location.replace('./');</script><?php
    }
    
    $userEmail = $decode->userEmail;

    $u_fetch = $db->query("SELECT * FROM user WHERE u_email = '$userEmail' OR u_phone = '$userEmail'"); // -> query for fetch all data from user logged in
    
    if($u_fetch->num_rows){
        while($r = $u_fetch->fetch_object()){

            $userId = $r->id;
            date_default_timezone_set("Asia/Jakarta");
            $time = date("h:i:sa");
            $date = date("d-m-Y");
            $_POST['date'] = $date;
            $_POST['time'] = $time;

            $updateLogoutData = $db->query("UPDATE user_log SET u_logoutTime = '$time', u_logoutDate = '$date' WHERE userId = '$userId' ORDER BY id DESC LIMIT 1");
            ?><script>window.location.replace('./');</script><?php //REDIRECT TO HOMEPAGE
            mysqli_query($db, $updateLogoutData); //INPUT DATA TO DATABASE
        }
    }
}
?>
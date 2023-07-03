<?php

require_once "conn.php";
require_once "conn2.php";
require_once "functions/index.php";
require_once "comp/vendor/autoload.php";
require_once "session.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if (isset($_COOKIE['SMHSESS'])) {
	//userTokenCheck
	$ck = $_COOKIE['SMHSESS'];
	$cookie = $db->real_escape_string($ck);
	$userSession = mysqli_query($db, "SELECT * FROM user_session WHERE user_jwt='$cookie'");
	$userSessionCheck = mysqli_num_rows($userSession);
	
	if ($userSessionCheck >= 1) {
		//fetch email from user
		$userEmail = $session->generateEmail();
		$userData = mysqli_query($db, "SELECT * FROM user WHERE u_email='$userEmail' OR u_phone='$userEmail'");
		$userDataCheck = mysqli_num_rows($userData);
		if ($userDataCheck >= 1) {
			?><script>window.location.replace("./");</script><?php
		}else{ ?><script>window.location.replace("logout.php");</script><?php }
	}else{ ?><script>window.location.replace("logout.php");</script><?php }
}else{
	$getEmail = $_POST["email"];
	$pwd = $_POST["password"];
	$password = sP($pwd);
	$email = sanitize($getEmail);
	$password = md5($password);

	$data = mysqli_query($db, "SELECT * FROM user WHERE u_email='$email' AND u_password='$password' OR u_phone='$email' AND u_password='$password'");
	$check = mysqli_num_rows($data);

	if($check > 0){
		$getSc = new userSession;
		$cookie = $getSc->userAuth($email); //create user session
		setcookie("SMHSESS", $cookie, time()+10800);

		//fetch token to decode
		$token = $getSc->jwtToken;
		
		//add to user log
		$secretKey = $getSc->generateSecretKey();
		$decode = JWT::decode($token, new Key($secretKey, 'HS256'));
		$userEmail = $decode->userEmail;
		$userSession = $decode->sessionId;

		$u_fetch = $db->query("SELECT * FROM user WHERE u_email = '$userEmail' OR u_phone = '$userEmail'"); // -> query for fetch all data from user logged in
		
		if($u_fetch->num_rows){ // -> fetch data
			while($r = $u_fetch->fetch_object()){

				//IP LOG
				function getIPAddress() {
						if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
							$ip = $_SERVER['HTTP_CLIENT_IP'];
						}
						elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
							$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
						}else{
							$ip = $_SERVER['REMOTE_ADDR'];
						}
					return $ip;
				}

				// browser, device log
				$browser = $_SERVER['HTTP_USER_AGENT'];

				//LOG SYSTEM
				$userId = $r->id;
				date_default_timezone_set("Asia/Jakarta");
				$time = date("h:i:sa");
				$date = date("d-m-Y");
				$_POST['date'] = $date;
				$_POST['time'] = $time;
				$ip = getIPAddress();

				// Prepare the SQL Statements to Insert User Login Time
				$insertLogin_SQL = "INSERT INTO user_log VALUES(NULL, '$userId', '$time', '$date', ' ', ' ', '$ip', '$browser')";
				$sessionQuery = "INSERT INTO user_session VALUES(NULL, '$userSession', $userId, '$token', '', 'login', '')";
				mysqli_query($db, $insertLogin_SQL);
				mysqli_query($db, $sessionQuery);
			}
		}
	}if ($email == "" || $password == "") {
		header("location: /shop/login.php?err=blank");
	}else{
		header("location: /shop/login.php?err=?");
	}
}

function sP($value){ //prevent xss attack
	return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function sanitize($value){
	$getDb = new connection;
	$db = $getDb->getDb();
	$firstSanitize = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
	$sanitizeClear = $db->real_escape_string($firstSanitize);
	return $sanitizeClear;
}

?>
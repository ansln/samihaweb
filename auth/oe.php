<?php

require_once "session.php";
require_once "conn2.php";
require_once "comp/vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class signupManagement{

    private function getDb(){
        $get = new connection;
        $db = $get->getDb();
        return $db;
    }

    private function getUserCheck(){
        $db = $this->getDb();
        $getSession = new userSession;
        if (isset($_COOKIE['SMHSESS'])) {
            //userTokenCheck
            $cookie = $_COOKIE['SMHSESS'];
            $userSession = mysqli_query($db, "SELECT * FROM user_session WHERE user_jwt='$cookie'");
            $userSessionCheck = mysqli_num_rows($userSession);
            
            if ($userSessionCheck > 0) {
                //fetch email from user
                $userEmail = $getSession->generateEmail();
                $userData = mysqli_query($db, "SELECT * FROM user WHERE u_email = '$userEmail' OR u_phone = '$userEmail'");
                $userDataCheck = mysqli_num_rows($userData);
                if ($userDataCheck > 0) {
                    ?><script>window.location.replace(".");</script><?php
                }else{ ?><script>window.location.replace("logout.php");</script><?php }
            }else{ ?><script>window.location.replace("logout.php");</script><?php }
        }
    }

    private function calDbData($email, $phone){
        $db = $this->getDb();
        $emailQuery = $db->query("SELECT * FROM user WHERE u_email = '$email'");
        $phoneQuery = $db->query("SELECT * FROM user WHERE u_phone = '$phone'");
        $emailCheck = mysqli_num_rows($emailQuery);
        $phoneCheck = mysqli_num_rows($phoneQuery);

        if ($emailCheck <= 0) {
            if ($phoneCheck <= 0) {
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    private function regValidation($fname, $uname, $uphone, $uemail, $upass, $ugender, $udob){
        $db = $this->getDb();
        $userFullName = $this->sanitize($fname);
        $splitName = $this->splitName($userFullName);
        $firstName = $splitName[0];
        $lastName = $splitName[1];
        $userName = $this->sanitize($uname);
        $userPhone = $this->sanitize($uphone);
        $userEmail = $this->sanitize($uemail);
        $userPass = $this->sanitize($upass);
        $userPassword = md5($userPass);
        $userGender = $this->sanitize($ugender);
        $dobconvert = date('d M Y', strtotime($udob));
        $userDOB = $this->sanitize($dobconvert);

        $validationCheck = $this->calDbData($userEmail, $userPhone);

        if ($validationCheck == false) {
            ?><script>window.location.replace('?err=?')</script><?php
        }else{
            $pushToDbQuery = "INSERT INTO user VALUES(NULL, 'https://ik.imagekit.io/samiha/default_33ZTxmyIC.png', '$firstName', '$lastName', '$userEmail', '$userName', '$userPassword', '$userPhone', '$userGender', '$userDOB', ' ', ' ', 0)";
            mysqli_query($db, $pushToDbQuery);
            $this->redirectAfter($userEmail);
            ?><script>window.location.reload();</script><?php
        }
    }
    
    private function redirectAfter($email){
        $getSc = new userSession;
		$cookie = $getSc->getToken2($email); //create user session
		setcookie("SMHSESS", $cookie);
        $this->logToken($cookie);
    }

    private function logToken($getToken){
        $getDb = new connection;
        $getSc = new userSession;

        //fetch token to decode
        $db = $getDb->getDb();
		$token = $this->sanitize($getToken);
		
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
    }

    public function sanitize($value){
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    public function splitName($value){
        $string = $value;
        $arrayString = explode(" ", $string );
        return $arrayString;
    } 

    //setter getter
    public function userCheck(){
        return $this->getUserCheck();
    }

    public function dataPost($fullName, $username, $userPhone, $userEmail, $userPassword, $userGender, $userDOB){
        return $this->regValidation($fullName, $username, $userPhone, $userEmail, $userPassword, $userGender, $userDOB);
    }

}

?>
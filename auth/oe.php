<?php

require_once "session.php";
require_once "conn2.php";

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
        $phoneQuery = $db->query("SELECT * FROM user WHERE u_email = '$phone'");
        $emailCheck = mysqli_num_rows($emailQuery);
        $phoneCheck = mysqli_num_rows($phoneQuery);

        if ($emailCheck <= 0) {
            return true;
        }elseif ($phoneCheck >= 1) {
            return false;
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
            header('Location: ?err=?');
        }else{
            $pushToDbQuery = "INSERT INTO user VALUES(NULL, 'https://ik.imagekit.io/samiha/default_33ZTxmyIC.png', '$firstName', '$lastName', '$userEmail', '$userName', '$userPassword', '$userPhone', '$userGender', '$userDOB', ' ', ' ', 0)";
            mysqli_query($db, $pushToDbQuery);
            header("Location: auth/success.php");
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
<?php

class productPriceView{

    private function getDb(){
        $get = new connection;
        $db = $get->getDb();
        return $db;
    }

    private function checkUserLogin(){
        error_reporting(0);
        $db = $this->getDb();
        $uCookie = $_COOKIE['SMHSESS'];
        $cookieSanitize = $db->real_escape_string($uCookie);
        $userSession = mysqli_query($db, "SELECT * FROM user_session WHERE user_jwt='$cookieSanitize'");
        $userSessionCheck = mysqli_num_rows($userSession);
	
        if ($userSessionCheck >= 1) {
           return true;
        }else{
            return false;
        }
    }

    public function getUserLoginforPrice(){
        return $this->checkUserLogin();
    }

}

?>
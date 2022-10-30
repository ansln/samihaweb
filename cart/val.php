<?php

require_once "../auth/cartV2.php";
require_once '../auth/conn2.php';
require_once "../auth/comp/vendor/autoload.php";
require_once "../auth/session.php";

class cartElement{

    private function getDb(){
        $getDb = new connection;
        $callDb = $getDb->getDb();
        return $callDb;
    }

    private function fetchDb(){
        $db = $this->getDb();
        $cartGetEmail = new cartManagement;
        $userEmail = $cartGetEmail->generateUserEmail();
        $realString = $db->real_escape_string($userEmail);

        if (isset($_COOKIE['SMHSESS'])) {
            //userTokenCheck
            $cookie = $_COOKIE['SMHSESS'];
            $userSession = mysqli_query($db, "SELECT * FROM user_session WHERE user_jwt='$cookie'");
            $userSessionCheck = mysqli_num_rows($userSession);
            
            if ($userSessionCheck > 0) {
                //fetch email from user
                $userData = mysqli_query($db, "SELECT * FROM user WHERE u_email = '$realString' OR u_phone = ' $userEmail '");
                $userDataCheck = mysqli_num_rows($userData);
                if ($userDataCheck > 0) {
                    if($userData->num_rows){
                        while($u_fetch = $userData->fetch_object()){
                            include '../layout/navwish.php';
                        }
                    }
                }else{ ?><script>window.location.replace("../logout.php");</script><?php }
            }else{ ?><script>window.location.replace("../logout.php");</script><?php }
        }else{ ?><script>window.location.replace("../logout.php");</script><?php }
    }

    private function transactionStatus(){
        
    }
    
    //setter getter
    public function getNav(){
        return $this->fetchDb();
    }
}

?>
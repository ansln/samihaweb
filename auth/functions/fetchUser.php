<?php

class userFetch{

    private function getDb(){
        $get = new connection;
        $callDb = $get->getDb();
        return $callDb;
    }

    private function userQuery($user){
        $db = $this->getDb();
        $realString = $db->real_escape_string($user);
        $userFetch = $db->query("SELECT * FROM user WHERE u_email = '$realString'");
        return $userFetch;
    }

    private function getUserEmail(){
        $session = new userSession;
        $userEmail = $session->generateEmail();
        return $this->userQuery($userEmail);
    }

    //setter getter
    protected function getDbQuery(){
        return $this->getUserEmail();
    }
}

class fetchUserData extends userFetch{
    
    private function getDb(){
        $get = new connection;
        $callDb = $get->getDb();
        return $callDb;
    }

    private function getUsername(){
        $getEmailDb = $this->getDbQuery();

        if($getEmailDb->num_rows){
            while($r = $getEmailDb->fetch_object()){
                $username = $r->u_username;
                return $username;
            }
        }
    }

    private function getUserEmail(){
        $getEmailDb = $this->getDbQuery();

        if($getEmailDb->num_rows){
            while($r = $getEmailDb->fetch_object()){
                $userEmail = $r->u_email;
                return $userEmail;
            }
        }
    }

    private function getUserPict(){
        $getEmailDb = $this->getDbQuery();

        if($getEmailDb->num_rows){
            while($r = $getEmailDb->fetch_object()){
                $userPict = $r->u_profilePict;
                return $userPict;
            }
        }
    }
    
    private function getUserCart(){
        $db = $this->getDb();
        $getEmailDb = $this->getDbQuery();
        $qty = 0;
        $userId = 0;

        foreach ($getEmailDb as $user) {
            $getId = $user["id"];
            $userId = $getId;
        }

        $userCartQuery = mysqli_query($db, "SELECT * FROM cart WHERE userId = '$userId'");

        foreach ($userCartQuery as $cart) {
            $productQty = $cart["qty"];
            $qty+=$productQty;
        }

        if ($qty <= 0) {
            $qty = 0;
        }else{
            return $qty;
        }

    }

    //setter getter
    public function username(){
        return $this->getUsername();
    }

    public function userEmail(){
        return $this->getUserEmail();
    }
    
    public function userPict(){
        return $this->getUserPict();
    }
    
    public function userCart(){
        return $this->getUserCart();
    }
}

?>
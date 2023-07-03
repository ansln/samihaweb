<?php

class cartData{

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

    private function getUserCart(){
        $db = $this->getDb();
        $getEmailDb = $this->getUserEmail();
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
    public function userCart(){
        return $this->getUserCart();
    }
}

?>
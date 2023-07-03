<?php

require_once '../auth/user/conn.php';

function sP($value){ //prevent xss attack
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}


function productRand($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = 'pd-';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function imageGenerate($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = 'img-';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

class product{

    private function getDb(){
        $get = new connection;
        $db = $get->getDb();
        return $db;
    }
    private function getProductId(){
        $db = $this->getDb();
        $fetchQuery = mysqli_query($db, "SELECT * FROM product ORDER BY pd_id DESC");
        foreach ($fetchQuery as $product) {
            $productId = $product["pd_id"];
            return $productId+1;
        }
    }

    public function getLastProductId(){
        return $this->getProductId();
    }
}
?>
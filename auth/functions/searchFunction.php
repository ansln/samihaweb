<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// require_once "../conn2.php";

class searchManagement{

    private function getDb(){
        $get = new connection;
        $db = $get->getDb();
        return $db;
    }

    private function sanitize($value){
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    private function splitQuery($value){
        // $splitQuery = explode(" ", $value); //with explode method
        $splitQuery = preg_split("/[\s,]+/", $value); //with preg split method

        return $splitQuery;
    }

    private function searchQuery($value){
        $db = $this->getDb();
        $querySanitize = $this->sanitize($value);
        $userQuery = $db->real_escape_string($querySanitize);
        return $this->findQueryDb($userQuery);
    }

    private function findQueryDb($value){
        $db = $this->getDb();
        // $fetchDb = $db->query("SELECT * FROM search_query WHERE productQuery LIKE '%{$value}%' OR productQuery = '$value'");
        $fetchDb = $db->query("SELECT * FROM product WHERE pd_name LIKE '%{$value}%' AND status != 0");
        $check = mysqli_num_rows($fetchDb);

        // if ($check >= 1) {
        //     foreach ($fetchDb as $fetchSearch) {
        //         $productCategory = $fetchSearch["productCategory"];
        //         $queryDb = $db->query("SELECT * FROM product WHERE pd_category = '$productCategory'");
        //         $this->fetchProduct($queryDb);
        //     }
        // }else{
        //     ?><!-- <small>tidak ada hasil ditemukan</small> --><?php
        // }

        if ($check >= 1) {
            $this->fetchProduct($fetchDb);
        }else{
            ?><small>tidak ada hasil ditemukan</small><?php
        }
    }

    private function fetchProduct($value){
        $productFetchQuery = $value;

        if($productFetchQuery->num_rows){
            while($product = $productFetchQuery->fetch_object()){
                $productImage = $product->pd_img;
                $pdName = $product->pd_name;
                // $productName = substr($pdName, 7);
                $productLink = $product->pd_link;
                $productPrice = $product->pd_price;
                $showPrice = $product->pd_price;
                if (empty($_COOKIE['SMHSESS'])) { $showPrice = null; }else{ $showPrice = "Rp" . number_format($productPrice,0,"","."); }

                ?><div class="search-card">
                        <div class="img">
                            <img src="<?= $productImage ?>">
                        </div>
                        <div class="text">
                            <h3><?= $pdName ?></h3>
                            <h4><?= $showPrice ?></h4>
                        </div>
                        <a href="product/view?product=<?= $productLink ?>"><span class="link"></span></a>
                </div><?php                
            }
        }
    }

    //setter getter
    public function search($value){
        return $this->searchQuery($value);
    }
}

?>
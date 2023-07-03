<?php

require "../auth/conn2.php";
require "../auth/session.php";

class productFunction{
    private $db;

    private function callDb(){
        $get = new connection;
        $this->db = $get->getDb();
        return $this->db;
    }

    protected function getDb(){
        $this->db = $this->callDb();
        return $this->db;
    }

    protected function sP($value){
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

class userFunction extends productFunction{

    private $userId;

    private function userValidation($user){
        $db = $this->getDb();
        $realString = $db->real_escape_string($user);
        $userFetch = $db->query("SELECT * FROM user WHERE u_email = '$realString'");
        return $userFetch;
    }

    private function getUserId(){
        $session = new userSession;
        $userEmail = $session->generateEmail();
        $db = $this->getDb();
        $userData = mysqli_query($db, "SELECT * FROM user WHERE u_email='$userEmail' OR u_phone='$userEmail'");
        if($userData->num_rows){
            while($r = $userData->fetch_object()){
                $this->userId = $r->id;
                return $this->userId;
            }
        }
    }

    private function userAuth(){
        //get db auth
        $db = $this->getDb();
        
        //get user session validation
        $session = new userSession;

        if (isset($_COOKIE['SMHSESS'])) {
            //userTokenCheck
            $cookie = $_COOKIE['SMHSESS'];
            $cookies = $db->real_escape_string($cookie);

            $userSession = mysqli_query($db, "SELECT * FROM user_session WHERE user_jwt='$cookies'");
            $userSessionCheck = mysqli_num_rows($userSession);
            
            if ($userSessionCheck > 0) {
                //fetch email from user
                $userEmail = $session->generateEmail();
                $userData = mysqli_query($db, "SELECT * FROM user WHERE u_email='$userEmail' OR u_phone='$userEmail'");
                $userDataCheck = mysqli_num_rows($userData);
                if ($userDataCheck > 0) {
                    if($userData->num_rows){
                        while($r = $userData->fetch_object()){
                            include '../layout/navpd1.php';
                        }
                    }
                }else{ ?><script>window.location.replace("logout.php");</script><?php }
            }else{ ?><script>window.location.replace("logout.php");</script><?php }
        }else{ include '../layout/navpd.php'; }
    }

    public function showUser($user){
        return $this->userValidation($user);
    }

    public function getVal(){
        return $this->userAuth();
    }

    public function fetchUserId(){
        return $this->getUserId();
    }
}

class productView extends productFunction{

    private function getProduct($product){
        $db = $this->getDb();
        $realString = $db->real_escape_string($product);
        $productFetch = $db->query("SELECT * FROM product WHERE pd_link = '$realString' AND status = 1");
        return $productFetch;
    }

    private function productCheck($value){
        $getProduct = $this->getProduct($value);
        $productCheck = mysqli_num_rows($getProduct);

        if ($productCheck <= 0) {
            ?><script>window.location.replace("../");</script><?php
        }else{
            return $this->getProduct($value);
        }
    }

    private function getProductImage($product){
        $db = $this->getDb();
        $realString = $db->real_escape_string($product);
        $fetchQuery = $db->query("SELECT * FROM product WHERE pd_link = '$realString'");
        if($fetchQuery->num_rows){
            while($pd = $fetchQuery->fetch_object()){
                $imgId = $pd->img_uid;
                $fetchImageQuery = $db->query("SELECT * FROM product_image WHERE img_uid = '$imgId' ORDER BY id ASC");
                return $fetchImageQuery;
            }
        }
    }

    public function showProductImage($product){
        return $this->getProductImage($product);
    }

    public function productCheckStatus($value){
        return $this->productCheck($value);
    }
}

class wishlistFunction extends userFunction{

    private $userId;
    private $uid;

    private function getUserData(){
        //get db auth
        $db = $this->getDb();
        
        //get user session validation
        $session = new userSession;

        if (isset($_COOKIE['SMHSESS'])) {
            //userTokenCheck
            $cookie = $_COOKIE['SMHSESS'];
            $cookies = $db->real_escape_string($cookie);

            $userSession = mysqli_query($db, "SELECT * FROM user_session WHERE user_jwt='$cookies'");
            $userSessionCheck = mysqli_num_rows($userSession);
            
            if ($userSessionCheck > 0) {
                //fetch email from user
                $userEmail = $session->generateEmail();
                $userData = mysqli_query($db, "SELECT * FROM user WHERE u_email='$userEmail' OR u_phone='$userEmail'");
                $userDataCheck = mysqli_num_rows($userData);
                if ($userDataCheck > 0) {
                    if($userData->num_rows){
                        while($r = $userData->fetch_object()){
                            $userId = $r->id;
                            $this->userWishlist($userId);
                        }
                    }
                }
            }
        }
    }

    private function userWishlist($user){
        $db = $this->getDb();
        $userFetch = $db->query("SELECT * FROM wishlist WHERE userId = '$user'");
        if($userFetch->num_rows){
            while($r = $userFetch->fetch_object()){
                $this->uid = $r->uid;
                $this->userId = $r->userId;
            }
        }
    }

    private function wishlistFetch($product){
        $db = $this->getDb();
        $userId = $this->userId;
        
        $wishlistValidationQuery = $db->query("SELECT * FROM wishlist WHERE userId = '$userId' AND productId = '$product'");
        if (mysqli_num_rows($wishlistValidationQuery) === 1 ){
            return $wishlistValidationQuery;
        }else{
            return false;
        }
    }

    private function fetchWishlistUID($product){
        $fetch = $this->wishlistFetch($product);
        if($fetch->num_rows){
            while($r = $fetch->fetch_object()){
                $wishlistUID = $r->uid;
                if($wishlistUID >= 1) {
                    return $wishlistUID;
                }else{
                    return false;
                }
            }
        }
    }

    private function addToDb($pdId, $userId){
        if (!isset($_COOKIE['SMHSESS'])) {
            ?><script>window.location.replace("../login.php?err=login");</script><?php
        }else{
            $db = $this->getDb(); //get db

            //create wishlistId
            $wrand = rand(10,100);
            $uid_wishlist = "ws_" . $wrand . "_" . rand();

            $addToWishQuery = "INSERT INTO wishlist VALUES(NULL, '$uid_wishlist', '$userId', '$pdId')";
            ?><script>location.reload();</script><?php
            mysqli_query($db, $addToWishQuery);
        }
    }
    
    private function delToDb($pdUid, $userId){
        if (!isset($_COOKIE['SMHSESS'])) {
            ?><script>window.location.replace("../login.php?err=login");</script><?php
        }else{
            $db = $this->getDb(); //get db
            
            $deleteToWishQuery = "DELETE FROM wishlist WHERE userId = '$userId' AND uid = '$pdUid'";
            mysqli_query($db, $deleteToWishQuery);
        }
    }

    public function getUserInfo(){
        return $this->getUserData();
    }

    public function getUserWishlist($product){
        return $this->fetchWishlistUID($product);
    }

    public function wishlistAdd($product, $userId){
        return $this->addToDb($product, $userId);
    }
    
    public function wishlistDel($product, $userId){
        return $this->delToDb($product, $userId);
    }
}

class cartFunction extends userFunction{

    private $userId;
    
    private function getUserData(){
        //get db auth
        $db = $this->getDb();
        
        //get user session validation
        $session = new userSession;

        if (isset($_COOKIE['SMHSESS'])) {
            //userTokenCheck
            $cookie = $_COOKIE['SMHSESS'];
            $cookies = $db->real_escape_string($cookie);

            $userSession = mysqli_query($db, "SELECT * FROM user_session WHERE user_jwt='$cookies'");
            $userSessionCheck = mysqli_num_rows($userSession);
            
            if ($userSessionCheck > 0) {
                //fetch email from user
                $userEmail = $session->generateEmail();
                $userData = mysqli_query($db, "SELECT * FROM user WHERE u_email='$userEmail' OR u_phone='$userEmail'");
                $userDataCheck = mysqli_num_rows($userData);
                if ($userDataCheck > 0) {
                    if($userData->num_rows){
                        while($r = $userData->fetch_object()){
                            $userId = $r->id;
                            $this->userId = $userId;
                            return $this->userId;
                        }
                    }
                }
            }
        }
    }

    private function checkProductStatus($productId){
        $db = $this->getDb();
        $userId = $this->getUserData();
        $userIdSanitize = $this->Sp($userId);
        $productIdSanitize = $this->sP($productId);

        $getDb = mysqli_query($db, "SELECT * FROM cart WHERE productId = '$productIdSanitize' AND userId = '$userIdSanitize'");
        $checkDb = mysqli_num_rows($getDb);

        if ($checkDb > 0) {
            $this->updateToDb($productIdSanitize, $userIdSanitize);
        }else{
            $this->pushToDb($productIdSanitize, $userIdSanitize);
        }

    }

    private function pushToDb($productId, $userId){
        if (!isset($_COOKIE['SMHSESS'])) {
            ?><script>window.location.replace("../login.php?err=login");</script><?php
        }else{
            $db = $this->getDb(); //get db

            //create wishlistId
            $wrand = rand(10,100);
            $uid_cart = "ct_" . $wrand . "_" . rand();

            $addToCartQuery = "INSERT INTO cart VALUES(NULL, '$uid_cart', '$userId', '$productId', 0)";
            ?>
            <script>
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Produk berhasil ditambahkan',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    showConfirmButton: false
                })
                setTimeout(function(){
                    location.reload();
                }, 2000);
            </script>
            <?php
            mysqli_query($db, $addToCartQuery);
        }
    }

    private function updateToDb($productId, $userId){
        if (!isset($_COOKIE['SMHSESS'])) {
            ?><script>window.location.replace("../login.php");</script><?php
        }else{
            error_reporting(0);
            $db = $this->getDb(); //get db

            $updateToCartQuery = $db->query("UPDATE cart SET qty = qty+1 WHERE userId = '$userId' AND productId = '$productId'");
            ?>
            <script>
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Produk berhasil ditambahkan',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    showConfirmButton: false
                })
                setTimeout(function(){
                    location.reload();
                }, 2000);
            </script>
            <?php
            mysqli_query($db, $updateToCartQuery);
        }
    }

    public function addToCartFunction($productId){
        return $this->checkProductStatus($productId);
    }

}

class productReview extends userFunction{

    private function userReviewDb($productId){
        $db = $this->getDb();
        $reviewFetch = $db->query("SELECT * FROM user_review WHERE productId = '$productId'");
        return $reviewFetch;
    }

    private function productCheckStatus($value){
        $get = $this->userReviewDb($value);
        $productCheck = mysqli_num_rows($get);

        if ($productCheck > 0) {
            return $this->userReviewDb($value);
        }else{
            
        }
    }

    private function UserDetailDb($value){
        $db = $this->getDb();
        $userFetch = $db->query("SELECT * FROM user WHERE id = '$value'");
        return $userFetch;
    }

    private function getUserDetail($value){
        $getUser = $this->UserDetailDb($value);
        $userCheck = mysqli_num_rows($getUser);

        if ($userCheck > 0) {
            return $this->UserDetailDb($value);
        }else{
            echo "false";
        }
    }

    public function getReview($value){
        return $this->productCheckStatus($value);
    }

    public function getUser($value){
        return $this->getUserDetail($value);
    }
}

?>
<?php

include "../auth/functions/productView.php";

$getWishlist = new wishlistFunction;
$getUser = new userFunction;
$getCart = new cartFunction;

if(isset($_GET['add'])) {
    if (isset($_COOKIE['SMHSESS'])) {
        if (isset($_POST['pdAdd'])) {
            $productIdAdd = $_POST['pdAdd'];
            $userId = $getUser->fetchUserId();
            $getWishlist->wishlistAdd($productIdAdd, $userId);
        }
    }else{
        ?><script>window.location.replace("../login.php?err=login");</script><?php
    }
}if(isset($_GET['del'])) {
    if (isset($_COOKIE['SMHSESS'])) {
        if (isset($_POST['pdDel'])) {
            $wishlistUIDDel = $_POST['pdDel'];
            $userId = $getUser->fetchUserId();
            $getWishlist->wishlistDel($wishlistUIDDel, $userId);
        }
    }else{
        header('Location: ../login.php?err=login');
    }
}if(isset($_GET['addtocart'])) {
    if (isset($_COOKIE['SMHSESS'])) {
        if (isset($_POST['cartProductId'])) {
            $addToCartId = $_POST['cartProductId'];
            $getCart->addToCartFunction($addToCartId);
        }
    }else{
        ?><script>window.location.replace("../login.php?err=login");</script><?php
    }
}

?>
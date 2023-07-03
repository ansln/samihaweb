<?php

include "../auth/functions/productFunctions.php";
include "../auth/functions/navUser.php";

$getHome = new homeManagement;
$getUser = new cartData;
$dbElement = $getHome->getElement("samiha-logo");
$userCart = $getUser->userCart();

if($dbElement->num_rows){
    while($dashboard = $dbElement->fetch_object()){
        ?>
<div class="navbar">
    <div id="main-logo-wrapper"><div id="main-logo"><a href="/shop"><img src="<?= $dashboard->url ?>" alt="Samiha Logo"></a></div></div>
    <div id="search-bar"><div id="sb-wrapper"><input type="text" id="searchBox" placeholder="Cari produk"><i class="fa-solid fa-magnifying-glass"></i></div></div>
    <div id="cart-btn" data-count="<?= $userCart ?>"><i class="fa-solid fa-cart-shopping"></i></div>
    <div id="notif-btn"><i class="fa-solid fa-bell"></i></div>
    <div id="msg-btn"><i class="fa-solid fa-envelope"></i></div>
    <div id="profile-pict"><img src="<?= $u_fetch->u_profilePict; ?>"></div>
</div>
<div class="show"><div class="content"></div></div>
        <?php
    }
}
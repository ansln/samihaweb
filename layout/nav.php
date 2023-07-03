<?php

include "auth/functions/index.php";

$getHome = new homeManagement;
$dbElement = $getHome->getElement("samiha-logo");

if($dbElement->num_rows){
    while($dashboard = $dbElement->fetch_object()){
        ?>
<div class="navbar">
    <div id="main-logo-wrapper"><div id="main-logo"><a href="./"><img src="<?= $dashboard->url ?>" alt="Samiha Logo"></a></div></div>
    <div id="search-bar"><div id="sb-wrapper"><input type="text" id="searchBox" placeholder="Cari produk"><i class="fa-solid fa-magnifying-glass"></i></div></div>
    <div id="cart-btn"><i class="fa-solid fa-cart-shopping"></i></div>
    <div id="reg-log-btn">
        <a href="login.php"><button class="login-btn">Masuk</button></a>
        <a href="register.php"><button class="reg-btn">Daftar</button></a>
    </div>
</div>
<div class="show"><div class="content"></div></div>
        <?php
    }
}
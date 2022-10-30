<?php

include "auth/functions/index.php";

$getHome = new homeManagement;
$dbElement = $getHome->getElement("samiha-logo");

if($dbElement->num_rows){
    while($dashboard = $dbElement->fetch_object()){
        ?>
<nav>
    <div class="left">
        <div class="top_logo"><a href="."><img src="<?= $dashboard->url ?>"></a></div>
        <a href="">Kategori</a>
    </div>

    <div class="center">
        <div class="searchbox">
            <input type="text" id="fname" name="fname" placeholder="Cari produk" autocomplete="off">
        </div>
        <div class="searchbtn">
            <button type="submit"><img src="https://api.iconify.design/akar-icons/search.svg?color=%23ffb648"></button>
        </div>
    </div>

    <div class="right">
        <div class="ico-row-before">
            <a href="cart/"><img src="https://api.iconify.design/eva/shopping-cart-fill.svg?color=%23ffb648"></a>
        </div>

        <div class="logreg-btn">
            <a href="login.php"><button class="login-btn">Masuk</button></a>
            <a href="register.php"><button class="reg-btn">Daftar</button></a>
        </div>
    </div>
</nav>
        <?php
    }
}

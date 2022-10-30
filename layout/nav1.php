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
        <div class="ico-row">
            <a href="cart/"><img src="https://api.iconify.design/eva/shopping-cart-fill.svg?color=%23ffb648"></a>    
            <a href=""><img src="https://api.iconify.design/ic/baseline-notifications.svg?color=%23ffb648"></a>
            <a href="chat/"><img src="https://api.iconify.design/fluent/mail-16-filled.svg?color=%23ffb648"></a>
        </div>
        <div class="ico-user">
            <a href="user/">
                <?php
                    $userPict = $r->u_profilePict;
                    if (!$userPict == "") {
                        ?><img src="<?= $userPict ?>"><?php
                    }else{
                        ?><img src="assets/etc/default.png"><?php
                    }
                ?>
            </a>
        </div>
    </div>
</nav>
        <?php
    }
}
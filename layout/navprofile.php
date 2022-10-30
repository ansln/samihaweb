<?php

include "../auth/functions/profileFunctions.php";

$getHome = new homeManagement;
$dbElement = $getHome->getElement("samiha-logo");

if($dbElement->num_rows){
    while($dashboard = $dbElement->fetch_object()){
        ?>
<nav>
    <div class="left">
        <div class="top_logo"><a href="../"><img src="<?= $dashboard->url ?>"></a></div>
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
            <a href="../cart"><img src="https://api.iconify.design/eva/shopping-cart-fill.svg?color=%23ffb648"></a>    
            <a href=""><img src="https://api.iconify.design/ic/baseline-notifications.svg?color=%23ffb648"></a>
        </div>

        <a href="../user/"><div class="profilePict">
            <div class="ico-user">
                    <?php
                        $userPict = $u_fetch->u_profilePict;
                        if (!$userPict == "") { // check if user has profile pict or not
                            ?><img src="<?= $userPict ?>"><?php
                        }else{
                            ?><img src="../assets/etc/default.png"><?php
                        }
                    ?>
            </div>
            <span><b><?= $u_fetch->u_username ?></b></span>
        </div></a>
    </div>
</nav>
        <?php
    }
}
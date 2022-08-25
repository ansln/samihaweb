<nav>
    <div class="left">
        <div class="top_logo">
        <a href="../"><img src="../assets/img/logo/logo2.png"></a>
        </div>
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
                            ?><img src="../assets/img/user.png"><?php
                        }
                    ?>
            </div>
            <span><b><?= $u_fetch->u_username ?></b></span>
        </div></a>
    </div>
</nav>
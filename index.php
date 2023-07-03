<?php

require_once 'auth/conn.php';
require_once 'auth/comp/vendor/autoload.php';
require_once 'auth/session.php';
require_once 'auth/functions/priceShow.php';

$session = new userSession;
$forPrice = new productPriceView;
$checkUserLoginforPrice = $forPrice->getUserLoginforPrice();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Supplier kurma terbaik di Indonesia">
    <meta name="keywords" content="samiha.id, Samiha, samiha, samiha, samihaid, kurmasamiha, kurma samiha, jual kurma, jual kurma ajwa, kurma sukari, samiha kurma">
    <meta name="author" content="ansln">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Samiha - Supplier kurma terbaik di Indonesia</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="style/style.css"><link rel="stylesheet" href="layout/nav.css"/><link rel="stylesheet" href="style/search.css"/>
    <script src="https://kit.fontawesome.com/3f3c1cf592.js" crossorigin="anonymous"></script>
    <script src="js/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="ct-wrapper">
            <!--<div class="notif-sec"><i class="fa-solid fa-bullhorn"></i><small>Untuk pembayaran masih dalam proses perbaikan, mohon maaf atas ketidaknyamanannya.</small></div>-->
        <?php
            if (isset($_COOKIE['SMHSESS'])) {
                //userTokenCheck
                $ck = $_COOKIE['SMHSESS'];
                $cookie = $db->real_escape_string($ck);
                $userSession = mysqli_query($db, "SELECT * FROM user_session WHERE user_jwt='$cookie'");
                $userSessionCheck = mysqli_num_rows($userSession);
                
                if ($userSessionCheck > 0) {
                    //fetch email from user
                    $userEmail = $session->generateEmail();
                    $userData = mysqli_query($db, "SELECT * FROM user WHERE u_email='$userEmail' OR u_phone='$userEmail'");
                    $userDataCheck = mysqli_num_rows($userData);
                    if ($userDataCheck > 0) {
                        if($userData->num_rows){
                            while($r = $userData->fetch_object()){
                                include 'layout/nav1.php';
                            }
                        }
                    }else{ ?><script>window.location.replace("logout.php");</script><?php }
                }else{ ?><script>window.location.replace("logout.php");</script><?php }
            }else{ include 'layout/nav.php'; }
        ?>
            <div class="ct-banner">
                <div class="carousell-wrapper">
                    <div class="swiper mySwiper">
                        <div class="swiper-wrapper">
                            <?php $getBannerCarousell = mysqli_query($db, "SELECT * FROM dashboard WHERE element_name = 'banner-carousell'");
                            foreach ($getBannerCarousell as $carousell) {
                                $getCarousellImage = $carousell["url"];
                                ?><div class="swiper-slide"><img src="<?= $getCarousellImage ?>" loading="lazy"></div><?php } ?>
                        </div>
                        <div class="swiper-button-next"><i class="fa-solid fa-chevron-right"></i></div>
                        <div class="swiper-button-prev"><i class="fa-solid fa-chevron-left"></i></div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
                
                <!--<div class="another-ct-img">-->
                <!--    <div id="img-new-wrapper">-->
                <!--        <img src="https://ik.imagekit.io/samiha/WhatsApp_Image_2023-01-17_at_14.18.33_Gts3Blec_.jpeg">-->
                <!--    </div>-->
                <!--</div>-->
                
                <div class="promotion-slide-wrapper">
                    <div class="swiper mySwiper">
                        <div class="swiper-wrapper">
                            <?php $getPromotionBanner = mysqli_query($db, "SELECT * FROM dashboard WHERE element_name = 'promotion-banner'");
                            foreach ($getPromotionBanner as $promotion) {
                                $getPromotionImage = $promotion["url"];
                                $getPromotionLink = $promotion["link"];
                                ?><div class="swiper-slide"><div id="promotion-banner-wrapper"><a href="<?= $getPromotionLink ?>"><img src="<?= $getPromotionImage ?>" loading="lazy"></a></div></div><?php } ?>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
                <!--<div class="banner-notif">-->
                <!--    <div class="banner-notif-wrapper">-->
                <!--        <img src="https://img.freepik.com/free-vector/shopping-time-banner-with-realistic-map-cart-gift-bags-vector-illustration_548887-120.jpg?w=1800">-->
                <!--        <img src="https://ik.imagekit.io/samiha/lunar_new_year_2023__1300___150_piksel__R5ncFJ3E3.gif">-->
                <!--    </div>-->
                <!--</div>-->
            </div>

            <div class="ct-content">
                <div class="product-section">
                    <div class="product-title">
                        <div id="pt-child1">Produk Populer</div>
                        <div id="pt-child2">Lihat Semua</div>
                    </div>
                    <div class="card-wrapper">
                    <?php 
                        $pd_1 = $db->query("SELECT * FROM product WHERE status = 1 ORDER BY pd_id DESC");

                        if($pd_1->num_rows){
                            while($r = $pd_1->fetch_object()){
                                $productPrice = $r->pd_price;
                                $productStock = $r->pd_stock;
                                if ($checkUserLoginforPrice != true) { $showPrice = null; }else{ $showPrice = number_format($productPrice,0,"","."); }
                                if ($productStock <= 0) { $showStock = 'Stok habis'; }else{ $showStock = 'Sisa ' . $productStock; } ?>
                        <div class="product-card">
                            <a href="product/view?product=<?= $r->pd_link ?>">
                                <div id="product-image"><img src="<?= $r->pd_img ?>"></div>
                                <div class="product-text-sec">
                                    <div id="product-name"><?= $r->pd_name ?></div>
                                    <div id="product-price"><?= $showPrice ?></div>
                                    <div class="product-third">
                                        <div id="product-rating"><i class="fa-solid fa-star"></i>5</div>
                                        <div id="product-sell"><?= $showStock ?></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php } } ?>
                    </div>
                </div>
            </div>

            <footer class="footer">
                <div class="footer-left">
                    <img src="https://ik.imagekit.io/samiha/logo_hgGvqn6gn.png" alt="logo samiha">
                </div>
                <ul class="footer-right">
                    <li>
                        <h2>Samiha</h2>
                        <ul class="footer-box">
                            <li><a href="/article/view?title=tentang-kami-samiha-kurma">Tentang Kami</a></li>
                            <li><a href="whatsapp://send?text=Halo! Saya ingin bertanya terkait produk kurma dari Samiha&phone=+628111960822">Kontak</a></li>
                            <li><a href="../article/">Artikel</a></li>
                        </ul>
                    </li>
                    <li class="bantuan">
                        <h2>Bantuan dan Panduan</h2>
                        <ul class="footer-box">
                            <li><a href="#">Syarat dan Ketentuan</a></li>
                            <li><a href="#">Kebijakan Privasi</a></li>
                            <li><a href="#">FAQ</a></li>
                        </ul>
                    </li>
                    <div class="medsos">
                        <h2>IKUTI KAMI</h2>
                        <div class="medsos-ct">
                            <a href="https://www.facebook.com/people/Samiha-Qurma/pfbid0fZ5E8TpDyv2S2DKdmfWYNs8c5StT47eoDHj3PgEfZsCVjNaHR9BFkQWPuBex3BW4l/"><div id="medsos-fb"><img src="https://cdn.icon-icons.com/icons2/1826/PNG/512/4202107facebookfblogosocialsocialmedia-115710_115591.png" alt="facebook-samiha"></div></a>
                            <a href="https://www.instagram.com/samihapremiumqurma/"><div id="medsos-ig"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e7/Instagram_logo_2016.svg/2048px-Instagram_logo_2016.svg.png" alt="instagram-samiha"></div></a>
                            <a href="https://www.tiktok.com/@samihaqurmapremium"><div id="medsos-tk"><img src="https://ik.imagekit.io/samiha/tiktok_z0ZLlX4ii.png" alt="tiktok-samiha"></div></a>
                            <a href="https://www.youtube.com/@samihakurmapremium"><div id="medsos-yt"><img src="https://ik.imagekit.io/samiha/youtube_1SKkJl96G.png" alt="youtube-samiha"></div></a>
                        </div>
                    </div>
                </ul>
                <div class="footer-bottom">
                    <p>© 2022 | Samiha</p>
                </div>
            </footer>
        </div>        
    </div>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="js/app.js"></script><script src="js/search.js"></script>
</body>
</html>
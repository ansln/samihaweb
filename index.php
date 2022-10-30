<?php

require_once 'auth/conn.php';
require_once 'sys/admin/auth/functions.php';
require_once 'auth/comp/vendor/autoload.php';
require_once 'auth/session.php';

$session = new userSession;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Samiha - Supplier kurma terbaik di Indonesia</title>
    <link rel="icon" type="image/x-icon" href="https://ik.imagekit.io/samiha/logo_hgGvqn6gn.png">
    <meta charset="UTF-8">
    <meta name="description" content="Supplier kurma terbaik di Indonesia">
    <meta name="keywords" content="samiha.id, Samiha, samiha, samiha, samihaid, kurmasamiha, kurma">
    <meta name="author" content="ansln">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="style/style.css"><link rel="stylesheet" href="style/slide.css"><link rel="stylesheet" href="layout/nav.css">
</head>
<body>
    <?php
        if ($_COOKIE['SMHSESS']) {
            //userTokenCheck
            $cookie = $_COOKIE['SMHSESS'];
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
        <div class="ct-content">
            <div class="content">
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide"><img src="https://ik.imagekit.io/gogomushroom/photo-1633677491262-0a51b9851f46_VNrJ96nPL.jfif" loading="lazy"></div>
                        <div class="swiper-slide"><img src="https://ik.imagekit.io/gogomushroom/photo-1649335889120-4084d7456c7c_sZrnFI1EN.jfif" loading="lazy"></div>
                        <div class="swiper-slide"><img src="https://ik.imagekit.io/gogomushroom/97uD7-Nwk-uEWe7-PZC.jpg" loading="lazy"></div>
                    </div>
                    <div class="swiper-button-next"><img src="./assets/img/arrow2.png"></div>
                    <div class="swiper-button-prev"><img src="./assets/img/arrow2.png"></div>
                    <div class="swiper-pagination"></div>
                </div>

                <div class="content-product">

                    <div class="newbanner">
                        <img src="https://ik.imagekit.io/gogomushroom/banner-test_Qrt8fUJs4.jpg">
                    </div>

                    <div class="row-ct-product">
                        <h2>Produk Populer</h2>
                        <a href="">Lihat Semua</a>
                    </div>
                    
                    <div class="pd-card">
                        <?php 
                        $pd_1 = $db->query("SELECT * FROM product WHERE status = 1 ORDER BY pd_id DESC");

                        if($pd_1->num_rows){
                            while($r = $pd_1->fetch_object()){
                                ?>
                                <a href="product/view?product=<?php echo "$r->pd_link" ?>">
                                <div class="pd-card-content">
                                    <img src="<?php echo "$r->pd_img" ?>" loading="lazy">
                                    <div class="pd-text">
                                        <h3><?php echo "$r->pd_name" ?></h3>
                                        <h4>Rp <?php echo number_format($r->pd_price,0,"",".") ?></h4>
                                        <h5>Stock: <?php echo "$r->pd_stock" ?></h5>
                                    </div>
                                </div>
                                </a>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>

                <!-- ANOTHER CONTENT -->

            </div>
        </div>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="js/app.js"></script>
</body>
</html>
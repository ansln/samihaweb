<?php
    require_once 'auth/conn.php';
    require_once 'sys/admin/auth/functions.php';
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Samiha Dates</title>
    <meta charset="UTF-8">
    <meta name="description" content="Supplier kurma terbaik di Indonesia">
    <meta name="keywords" content="samihadates.com, SamihaDates, samiha dates, samihadates">
    <meta name="author" content="ansln">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="style/style.css"><link rel="stylesheet" href="style/slide.css"><link rel="stylesheet" href="layout/nav.css">
</head>
<body>
    <?php
        if($_SESSION['status']!="login"){
                include 'layout/nav.php';
            }else{
                if(isset($_SESSION['email']) || isset($_SESSION['phone'])){ 
                    $uData = $db->real_escape_string($_SESSION['email']); // -> get data user email from session
                    $uDataP = $db->real_escape_string($_SESSION['phone']);

                    $u_fetch = $db->query("SELECT * FROM user WHERE u_email LIKE '{$uData}' OR u_phone LIKE '{$uDataP}'"); // -> query for fetch all data from user logged in
                    
                    if($u_fetch->num_rows){ // -> fetch data
                        while($r = $u_fetch->fetch_object()){
                            include 'layout/nav1.php';
                        }
                    }
                }
            }
    ?>
        <div class="ct-content">
            <div class="content">
                <!-- SLIDE SHOW -->
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide"><img src="https://images.unsplash.com/photo-1633677491262-0a51b9851f46?ixlib=rb-1.2.1" loading="lazy"></div>
                        <div class="swiper-slide"><img src="https://images.unsplash.com/photo-1648288725055-5ba4063355b9?ixlib=rb-1.2.1" loading="lazy"></div>
                        <div class="swiper-slide"><img src="https://images.unsplash.com/photo-1649335889120-4084d7456c7c?ixlib=rb-1.2.1" loading="lazy"></div>
                        <div class="swiper-slide"><img src="https://images.unsplash.com/photo-1633677491262-0a51b9851f46?ixlib=rb-1.2.1" loading="lazy"></div>
                        <div class="swiper-slide"><img src="https://images.unsplash.com/photo-1648288725055-5ba4063355b9?ixlib=rb-1.2.1" loading="lazy"></div>
                    </div>
                    <div class="swiper-button-next"><img src="./assets/img/arrow2.png"></div>
                    <div class="swiper-button-prev"><img src="./assets/img/arrow2.png"></div>
                    <div class="swiper-pagination"></div>
                </div>

                <div class="content-product">

                    <div class="newbanner">
                        <img src="./assets/img/banner/banner-test.jpg">
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
                                <a href="product/view.php?product=<?php echo "$r->pd_link" ?>">
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
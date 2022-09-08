<?php

require_once '../auth/conn.php';
session_start();

    if($_SESSION['status']=="login"){
        $uData = $db->real_escape_string($_SESSION['email']); // -> get data user email from session
        $uDataP = $db->real_escape_string($_SESSION['phone']);

        $userQuery = $db->query("SELECT * FROM user WHERE u_email = '$uData' OR u_phone = '$uDataP'"); // -> query for fetch all data from user logged in

        ?>
        <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Samiha Dates - Keranjang</title>
                <link rel="stylesheet" href="../style/cart.css">
                <script src='//cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
        <?php
        if($userQuery->num_rows){ // -> fetch user data
            while($u_fetch = $userQuery->fetch_object()){
                
                $userId = $u_fetch->id;
                $cartQuery = $db->query("SELECT * FROM cart WHERE userId=$userId ORDER BY id DESC");
                $cartQueryDetail = $db->query("SELECT * FROM cart WHERE userId=$userId ORDER BY id DESC");
                ?>
                    <nav>
                        <div class="left">
                            <a href="../">Samiha Dates</a>
                            <!-- <a href=""><button>Kategori</button></a> -->
                        </div>
                        <div class="center">
                            <!-- <button>Discover our product</button> -->
                        </div>
                        <div class="right">
                            <div class="ico-row">
                                <a href="../cart/"><img src="http://localhost/shop/assets/img/cart_ico.png"></a>
                                <a href=""><img src="http://localhost/shop/assets/img/notif.png"></a>
                            </div>
                            <span>Selamat datang, <b><?= $u_fetch->u_username ?></b></span>
                            <div class="ico-user">
                                <a href="../user/"><img src="http://localhost/shop/assets/img/user.png"></a>
                            </div>
                        </div>
                    </nav>
                <?php

                ?>
                <!-- START OF CONTAINER -->
                    <div class="container">
                        <div class="container-left">
                    <?php
//check user cart
                $check = mysqli_num_rows($cartQuery);
                if($check < 1){
                    ?>
                    <div class="col">
                        <img src="../assets/img/cart.png">
                        <b>Keranjang kamu masih kosong</b>
                        <p>Yuk cari produk yang mau kamu beli</p>
                        <div class="tst">
                            <a href="/shop"><button>Cari produk</button></a>
                        </div>
                    </div>
                    <?php
                }else{
                    ?>
                        <div class="title">
                            <h2>Keranjang</h2>
                        </div>
                    <?php
                }
                $grandTotal = 0; // CREATE GRAND TOTAL WITH 0
                $qtyTotal = 0; // CREATE QTY TOTAL WITH 0
                if($cartQuery->num_rows){ // -> fetch cart
                    while($c_fetch = $cartQuery->fetch_object()){

                        $productId = $c_fetch->productId;   
                        $productQuery = $db->query("SELECT * FROM product WHERE pd_id=$productId");
                        $qty = $c_fetch->qty;
                        
                        if($productQuery->num_rows){ // -> fetch product from wishlist
                            while($p_fetch = $productQuery->fetch_object()){

                                //SUBTOTAL VALIDATION
                                $productPrice = $p_fetch->pd_price;
                                $totalPriceProduct = $productPrice * $qty;

                                $subTotal = $productPrice * $qty;

                                ?>
                                    <div class="card">
                                        <div class="card-top">
                                            <img src="<?= $p_fetch->pd_img ?>">
                                            <div class="card-top-text">
                                                <p><?= $p_fetch->pd_name ?></p>
                                                <span>Stok: </span><b>tersedia</b>
                                                <h4>Rp <?= number_format($p_fetch->pd_price,0,"",".") ?></h4>
                                            </div>
                                        </div>
                                        <div class="card-bottom">
                                            <p>Tambahkan ke wihslist</p>
                                            <form action="?del=<?= $c_fetch->uid ?>" method="post"><button class="delete" type="submit"><img src="../assets/img/trash.png"></button></form>
                                            <?php
                                                    if ($qty <= 1) {
                                                        ?><form action="?min=<?= $c_fetch->uid ?>" method="post"><button class="min" type="submit" disabled>-</button></form><?php
                                                    }else{
                                                        ?><form action="?min=<?= $c_fetch->uid ?>" method="post"><button class="min" type="submit">-</button></form><?php
                                                    }
                                                ?>
                                            <span><?php echo "$c_fetch->qty" ?></span>
                                            <form action="?add=<?= $c_fetch->uid ?>" method="post"><button class="add" type="submit">+</button></form>
                                        </div>
                                    </div>
                                <?php
                            }
                        }
                        $qtyTotal+=$qty; // -> ALL QTY TOTAL WITH CALC
                        $grandTotal+=$subTotal; // -> ALL PRICE TOTAL WITH CALC
                        // CHECK IF QTY <= 0
                        if ($qty < 0) {
                            $delViewCart = $db->query("DELETE FROM cart WHERE qty<=0");
                            mysqli_query($db, $delViewCart);
                            ?><script>setTimeout(function(){window.location = "/shop/cart";}, 10);</script><?php
                        }
//END OF CART FETCH FROM DB
                    }
                }
//PURCHASED ITEM DETAIL AND CHECK CART
                if($check >= 1){
                    ?>
                    </div>
                    <div class="container-right">
                        <h4>Rincian Produk</h4>
                    <?php

                    if($cartQueryDetail->num_rows){ // -> fetch cart for details
                        while($c_fetch_detail = $cartQueryDetail->fetch_object()){

                            $productIdDetail = $c_fetch_detail->productId;
                            $productQtyDetail = $c_fetch_detail->qty;
                            
                            $productQueryDetail = $db->query("SELECT * FROM product WHERE pd_id=$productIdDetail");

                            if($productQueryDetail->num_rows){ // -> fetch product from wishlist
                                while($p_fetch_detail = $productQueryDetail->fetch_object()){

                                    $productName = $p_fetch_detail->pd_name;
                                    $productPriceDetail = $p_fetch_detail->pd_price;

                                    ?>
                                        <div class="container-right-product">
                                            <p><?= $productName ?></p>
                                            <span>x<?= $productQtyDetail ?></span>
                                        </div>
                                    <?php
                                }
                            }
                        }
                    }
                    
                    ?>
                        <div class="container-right-price">
                            <h5>Total Harga</h5>
                            <h6>Rp <?= number_format($grandTotal,0,"",".") ?></h6>
                        </div>
                        <a href="checkout/"><button>Checkout</button></a>
                        </div>
                    <?php
                }
//END OF PURCHASED ITEM DETAIL AND CHECK CART

// ADD AND REMOVE QTY PRODUCT FROM CART
                if (isset($_GET["del"])) {
                    $delKey = $db->real_escape_string($_GET['del']);
                    $deleteQty = "DELETE FROM cart WHERE userId=$userId AND uid='$delKey'";

                    mysqli_query($db, $deleteQty);
                    ?><script>setTimeout(function(){window.location = "/shop/cart";}, 5);</script><?php
                }
                if (isset($_GET["add"])) {
                    $addKey = $db->real_escape_string($_GET['add']);
                    $addQty = "UPDATE cart SET qty=qty+1 WHERE userId=$userId AND uid='$addKey'";

                    mysqli_query($db, $addQty);
                    ?><script>setTimeout(function(){window.location = "/shop/cart";}, 5);</script><?php
                }if (isset($_GET["min"])) {
                    $minKey = $db->real_escape_string($_GET['min']);
                    $minQty = "UPDATE cart SET qty=qty-1 WHERE userId=$userId AND uid='$minKey'";

                    mysqli_query($db, $minQty);
                    ?><script>setTimeout(function(){window.location = "/shop/cart";}, 5);</script><?php
                }
//END OF ADD AND REMOVE QTY PRODUCT FROM CART

            }
        }
    ?>
        </div>
    </body>
    </html>
    <?php
    }if($_SESSION['status']!="login"){
        header("location:/shop/login.php?err=login");
    }
?>
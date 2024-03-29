<?php

require_once '../auth/conn.php';
require_once "../auth/comp/vendor/autoload.php";
require_once "../auth/session.php";
require_once "../auth/cartV2.php";
require_once "val.php";

    if($_COOKIE['SMHSESS'] != ""){

        $user = new userSession;
        $cE = new cartElement;
        $email = $user->generateEmail();

        $userQuery = $db->query("SELECT * FROM user WHERE u_email = '$email' OR u_phone = '$email'");
        ?>
        <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Samiha - Keranjang</title>
                <link rel="stylesheet" href="../style/cart.css"><link rel="stylesheet" href="../style/scrollselection.css"><link rel="stylesheet" href="../layout/nav.css"><link rel="stylesheet" href="../layout/footer.css">
                <script src="../js/jquery-3.6.0.min.js"></script><script src='//cdn.jsdelivr.net/npm/sweetalert2@11'></script><script src="https://kit.fontawesome.com/3f3c1cf592.js" crossorigin="anonymous"></script>
            </head>
            <body>
        <?php
        if($userQuery->num_rows){
            while($u_fetch = $userQuery->fetch_object()){
                
                $userId = $u_fetch->id;
                $cartQuery = $db->query("SELECT * FROM cart WHERE userId=$userId ORDER BY id DESC");
                $cartQueryDetail = $db->query("SELECT * FROM cart WHERE userId=$userId ORDER BY id DESC");
                
                $getNavbar = $cE->getNav();
                ?>
                    <div class="container">
                        <div class="container-left">
                <?php
//check user cart
                $check = mysqli_num_rows($cartQuery);
                if($check < 1){
                    ?>
                    <div class="col">
                        <img src="../assets/etc/empty_cart.svg">
                        <div id="top-title">Keranjang kamu masih kosong</div>
                        <div id="sub-title">Yuk cari produk yang mau kamu beli</div>
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
                $lastQtyAdd = 0;
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
                                            <form action="?del=<?= $c_fetch->uid ?>" method="post"><button class="delete" type="submit"><i class="fa-solid fa-trash"></i></button></form>
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

                    $getCartUserQtyForDel = mysqli_query($db, "SELECT * FROM cart WHERE uid = '$delKey'");
                    //delete validation, give stock back when user delete product from cart
                    foreach ($getCartUserQtyForDel as $forDel) {
                        $productIdForQtyDel = $forDel["productId"];
                        $getQtyDel = $forDel["qty"] - 1;

                        $getProductQtyQueryForDel = mysqli_query($db, "SELECT * FROM product WHERE pd_id = '$productIdForQtyDel'");
                        foreach ($getProductQtyQueryForDel as $keyForProductDel) {
                            $getProductStockDel = $keyForProductDel["pd_stock"];
                            $totalStockToFetch = $getQtyDel+$getProductStockDel;

                            $deleteQty = "DELETE FROM cart WHERE userId=$userId AND uid='$delKey'";
                            $updateStockQueryDel = "UPDATE product SET pd_stock='$totalStockToFetch' WHERE pd_id='$productIdForQtyDel'";
        
                            mysqli_query($db, $updateStockQueryDel);
                            mysqli_query($db, $deleteQty);
                            ?><script>setTimeout(function(){window.location = "/shop/cart";}, 5);</script><?php
                        }
                    }
                }
                if (isset($_GET["add"])) {
                    // get stock status
                    
                    $addKey = $db->real_escape_string($_GET['add']);
                    $getCartUserQty = mysqli_query($db, "SELECT * FROM cart WHERE uid = '$addKey'");
                    foreach ($getCartUserQty as $getCartQtyKey) {
                        $productIdForQty = $getCartQtyKey["productId"];

                        $getProductQtyQuery = mysqli_query($db, "SELECT * FROM product WHERE pd_id = '$productIdForQty'");
                        foreach ($getProductQtyQuery as $keyForProduct) {
                            $getStock = $keyForProduct["pd_stock"];

                            if ($getStock <= 0) {
                                ?><script>alert("stok habis")</script><?php
                            }else{
                                $addQty = "UPDATE cart SET qty=qty+1 WHERE userId=$userId AND uid='$addKey'";
                                $updateStockQuery = "UPDATE product SET pd_stock=pd_stock-1 WHERE pd_id='$productIdForQty'";
        
                                mysqli_query($db, $updateStockQuery);
                                mysqli_query($db, $addQty);
                                ?><script>setTimeout(function(){window.location = "/shop/cart";}, 5);</script><?php
                            }
                        }
                    }
                }if (isset($_GET["min"])) {
                    $minKey = $db->real_escape_string($_GET['min']);

                    $minGetCartInfo = mysqli_query($db, "SELECT * FROM cart WHERE uid = '$minKey'");
                    foreach ($minGetCartInfo as $minInfo) {
                        $minProductId = $minInfo["productId"];

                        $minGetProductInfo = mysqli_query($db, "SELECT * FROM product WHERE pd_id = '$minProductId'");
                        foreach ($minGetProductInfo as $pdInfoMin) {
                            $getProductStock = $pdInfoMin["pd_stock"];

                            $minQty = "UPDATE cart SET qty=qty-1 WHERE userId=$userId AND uid='$minKey'";
                            $minUpdateStockQuery = "UPDATE product SET pd_stock=pd_stock+1 WHERE pd_id='$minProductId'";
    
                            mysqli_query($db, $minUpdateStockQuery);
                            mysqli_query($db, $minQty);
                            ?><script>setTimeout(function(){window.location = "/shop/cart";}, 5);</script><?php
                        }
                    }
                }
//END OF ADD AND REMOVE QTY PRODUCT FROM CART

            }
        }
    ?>
        </div>
    <script src="../js/nav.js"></script>
    </body>
    </html>
    <?php
    }else{
        header("location: /shop/login.php?err=login");
    }
?>
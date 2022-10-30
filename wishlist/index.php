<?php

require_once '../auth/conn.php';
require "../auth/comp/vendor/autoload.php";
require "../auth/session.php";

    if($_COOKIE['SMHSESS'] != ""){
        //get userEmail from JWT
        $get = new userSession;
        $email = $get->generateEmail();

        $userQuery = $db->query("SELECT * FROM user WHERE u_email = '$email' OR u_phone = '$email'"); // -> query for fetch all data from user logged in

        ?>
        <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Samiha Dates - Wishlist</title>
                <link rel="stylesheet" href="../style/wishlist.css"><link rel="stylesheet" href="../layout/nav.css">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
                <script src='//cdn.jsdelivr.net/npm/sweetalert2@11'></script><script src="https://kit.fontawesome.com/3f3c1cf592.js" crossorigin="anonymous"></script>
            </head>
            <body>
        <?php

        if($userQuery->num_rows){ // -> fetch user data
            while($u_fetch = $userQuery->fetch_object()){
                
                $userId = $u_fetch->id;
                $wishlistQuery = $db->query("SELECT * FROM wishlist WHERE userId=$userId ORDER BY id DESC");
                $cartQuery = $db->query("SELECT * FROM cart WHERE userId=$userId");
                include "../layout/navwish.php";

                ?>
                <div class="container">
                    <div class="ct-wishlist">

                        <div class="title">
                            <h2>Wishlist</h2>
                        </div>

                        <div class="width-wrap">
                    <?php
//check user wishlist
                $check = mysqli_num_rows($wishlistQuery);
                if($check < 1){
                    ?>
                    <div class="col">
                        <img src="../assets/img/find2.png">
                        <b>Wishlist kamu masih kosong</b>
                        <p>Yuk cari produk yang kamu suka</p>
                        <div class="tst">
                            <a href="/shop"><button>Cari produk</button></a>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <div class="max-width">
                <?php

                if($wishlistQuery->num_rows){ // -> fetch wishlist
                    while($w_fetch = $wishlistQuery->fetch_object()){

                        $productId = $w_fetch->productId;
                        $productQuery = $db->query("SELECT * FROM product WHERE pd_id=$productId");
                        
                        if($productQuery->num_rows){ // -> fetch product from wishlist
                            while($p_fetch = $productQuery->fetch_object()){

                                ?>
                                    <div class="card">
                                        <img src="<?php echo "$p_fetch->pd_img" ?>">
                                        <div class="card-ct">
                                            <a href="../product/view?product=<?php echo "$p_fetch->pd_link" ?>"><h3><?php echo "$p_fetch->pd_name" ?></h3></a>
                                            <b>Rp <?php echo number_format($p_fetch->pd_price,0,"",".") ?></b>
                                            <p>Terjual: <?php echo "$p_fetch->pd_stock" ?></p>
                                            <div class="mix-wish-cart">
                                                <div class="keranjang">
                                                    <?php
                                                    //ADD TO CART VALIDATION
                                                        $cartValidationQuery = $db->query("SELECT * FROM cart WHERE userid=$userId AND productId=$productId");
        
                                                        if (mysqli_num_rows($cartValidationQuery) >= 1){
                                                            ?><form action="" method="post"><input type="hidden" value="<?= $p_fetch->pd_id ?>" name="uid"><button type="submit" name="addMore">+Keranjang</button></form><?php
                                                        }if (mysqli_num_rows($cartValidationQuery) <= 0){
                                                            ?><form action="" method="post"><input type="hidden" value="<?= $p_fetch->pd_id ?>" name="uid"><button type="submit" name="addToCart">+Keranjang</button></form><?php
                                                        }
                                                    ?>
                                                </div>
                                                <div class="wishlist">
                                                    <form action="" method="post"><input type="hidden" value="<?= $w_fetch->uid ?>" name="uid"><button type="submit" name="deleteWishlist"><i class="fa-solid fa-trash"></i></button></form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                            }
                        }
//end of wishlist fetch from db
                    }
                }
//add to cart validation
                    if (isset($_POST["addToCart"])) {

                        $addToCartProductId = $db->real_escape_string($_POST['uid']); // get productId
                        $wrand = rand(10,100);
                        $uid_cart = "ct_" . $wrand . "_" . rand();

                        $productQueryCart = $db->query("SELECT * FROM product WHERE pd_id=$addToCartProductId");

                        if($productQueryCart->num_rows){ // -> fetch product 
                            while($pc_fetch = $productQueryCart->fetch_object()){

                                $productIdCart = $pc_fetch->pd_id;

                                $addToCartQuery = "INSERT INTO cart VALUES(NULL, '$uid_cart', '$userId', '$productIdCart', 1)";
                                mysqli_query($db, $addToCartQuery);
                                ?>
                                <script>
                                    Swal.fire({
                                        toast: true,
                                        position: 'top-end',
                                        icon: 'success',
                                        title: 'Barang ditambahkan ke keranjang',
                                        showClass: {
                                            popup: 'animate__animated animate__fadeInDown'
                                        },
                                        showConfirmButton: false
                                    })
                                    setTimeout(function(){
                                        window.location = "/shop/wishlist";
                                    }, 2000);
                                </script>
                                <?php
                            }
                        }
                    }
//more add to cart validation
                    if (isset($_POST["addMore"])) {

                        $moreToCartProductId = $db->real_escape_string($_POST['uid']); // get productId
                        $wrand = rand(10,100);
                        $uid_cart = "ct_" . $wrand . "_" . rand();

                        $productQueryCartMore = $db->query("SELECT * FROM product WHERE pd_id=$moreToCartProductId");

                        if($productQueryCartMore->num_rows){ // -> fetch product 
                            while($pc_fetch = $productQueryCartMore->fetch_object()){

                                $productIdCartMore = $pc_fetch->pd_id;

                                $moreAddToCartQuery = $db->query("UPDATE cart SET qty=qty+1 WHERE userId=$userId AND productId=$productIdCartMore");
                                ?>
                                <script>
                                    Swal.fire({
                                        toast: true,
                                        position: 'top-end',
                                        icon: 'success',
                                        title: 'Barang ditambahkan ke keranjang',
                                        showClass: {
                                            popup: 'animate__animated animate__fadeInDown'
                                        },
                                        showConfirmButton: false
                                    })
                                    setTimeout(function(){
                                        window.location = "/shop/wishlist";
                                    }, 2000);
                                </script>
                                <?php
                                mysqli_query($db, $moreAddToCartQuery);
                            }
                        }
                    }
//delete wishlist validation
                if(isset($_POST['deleteWishlist'])){
                    $wishKey = $db->real_escape_string($_POST['uid']);

                        // Prepare a delete statement
                        $wishlistDeleteQuery = $db->query("DELETE FROM wishlist WHERE uid='$wishKey'");
            
                        ?>
                        <script>
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Berhasil dihapus dari wishlist',
                                showClass: {
                                    popup: 'animate__animated animate__flipInX'
                                },
                                showConfirmButton: false
                            })
                            setTimeout(function(){
                                window.location = "/shop/wishlist";
                            }, 2000);
                        </script>
                        <?php
                        mysqli_query($db, $wishlistDeleteQuery);
                }
            }
        }
    ?>              </div>
                </div>
            </div>
        </div>
    
    </body>
    </html>
    <?php
    }if($_COOKIE['SMHSESS'] == ""){
        header("location:/shop/login.php?err=login");
    }
?>
<?php

session_start();
require_once 'conn.php';

if(isset($_GET['product'])){
    $keywords = $db->real_escape_string($_GET['product']);
    
    $query = $db->query("SELECT * FROM product WHERE pd_link LIKE '{$keywords}' AND status = 1");

        if($query->num_rows){
            while($r = $query->fetch_object()){
                $productStatus = $r->status;
                $productUID = $r->img_uid;

                $getProductImage = $db->query("SELECT * FROM product_image WHERE img_uid = '$productUID'");
    ?>
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Samiha Dates - <?php echo"$r->pd_name"?></title>
                    <link rel="stylesheet" href="../style/view.css">
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
                </head>
                <body>
                    <nav class="navbar navbar-expand-lg bg-light">
                        <div class="container-fluid">
                            <a class="navbar-brand" href="/shop">Samiha Dates</a>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                                <div class="navbar-nav">
                                <a class="nav-link" href="/shop">Home</a>
                                <a class="nav-link" href="">Product</a>
                                </div>
                            </div>
                        </div>
                    </nav>

                    <?php                        
                        if($getProductImage->num_rows){
                            while($pd = $getProductImage->fetch_object()){
                    ?>
                    <div class="container mt-5">
                        <div class="row gx-5">
                            <div class="col-md">
                                <img src="/shop/sys/admin/auth/uploads/<?= $pd->img_link ?>"></img>
                        <?php
                            }
                        }
                        //end of
                        ?>
                            </div>
                            <div class="col-md mt-3">
                                <h3><?php echo $r->pd_name ?></h3>
                                <h3>Rp <?php echo number_format($r->pd_price,0,"",".") ?></h4>
                                <h4>Berat: <?php echo "$r->pd_weight" ?> Gram</h4>
                                <h4>Kategori: <?php echo "$r->pd_category" ?></h4>
                                <p><?php echo "$r->pd_desc" ?></p>
                            </div>
                        </div>
                    </div>
                <?php
                $productPrice = $r->pd_price;
        }
    }

    //add to cart and add to wishlist validation
    if($_SESSION['status']=="login"){
        $uData = $db->real_escape_string($_SESSION['email']); // -> get data user email from session
        $uDataP = $db->real_escape_string($_SESSION['phone']);

        $u_fetch = $db->query("SELECT * FROM user WHERE u_email LIKE '{$uData}' OR u_phone LIKE '{$uDataP}'"); // -> query for fetch all data from user logged in
        $productQuery = $db->query("SELECT * FROM product WHERE pd_link LIKE '{$keywords}' AND status = 1");

        if($u_fetch->num_rows){ // -> fetch data
            while($r = $u_fetch->fetch_object()){
                if($productQuery->num_rows){
                    while($pd_fetch = $productQuery->fetch_object()){
                        
                        $wrand = rand(10,100);
                        $uid_cart = "ct_" . $wrand . "_" . rand();
                        $uid_wishlist = "ws_" . $wrand . "_" . rand();
                        $userId = $r->id;
                        $productId = $pd_fetch->pd_id;

                        $wishlistValidationQuery = $db->query("SELECT * FROM wishlist WHERE userid=$userId AND productId=$productId");
                        $cartValidationQuery = $db->query("SELECT * FROM cart WHERE userid=$userId AND productId=$productId");

                        // ADD AND REMOVE TO WISHLIST BUTTON
					    if (mysqli_num_rows($wishlistValidationQuery) === 1 ){
                            ?><form action="" method="post"><button type="submit" class="btn btn-primary mt-2" name="removeToWishlist">Hapus Wishlist</button></form><?php
                        }else{
                            ?><form action="" method="post"><button type="submit" class="btn btn-primary mt-2" name="addToWishlist">Wishlist</button></form><?php
                        }

                        // ADD TO CART VALIDATION
                        if (isset($_POST["addToCart"])) {
                            $addToCartQuery = "INSERT INTO cart VALUES(NULL, '$uid_cart', '$userId', '$productId', 1)";

                            mysqli_query($db, $addToCartQuery);

                            echo "barang berhasil ditambahkan ke keranjang anda!";

                            header("Refresh:2");
                        }

                        // CHECK IF USER ALREADY ADD THIS PRODUCT TO CART
                        if (mysqli_num_rows($cartValidationQuery) === 1){
                            if (isset($_POST["moreToCart"])) {
                                $moreAddToCart = $db->query("UPDATE cart SET qty=qty+1 WHERE userId=$userId AND productId=$productId");
                                
                                mysqli_query($db, $moreAddToCart);

                                echo "barang berhasil ditambahkan ke keranjang anda!";

                                header("Refresh:2");
                            }
                            ?><form action="" method="post"><button type="submit" class="btn btn-light" name="moreToCart">+Keranjang</button></form><?php
                        }else{
                            ?><form action="" method="post"><button type="submit" class="btn btn-light" name="addToCart">+Keranjang</button></form><?php
                        }
                        //END OF ADD TO CART VALIDATION

                        //WIHSLIST VALIDATION ADD AND REMOVE
                        if (isset($_POST["addToWishlist"])) {
                            $addToWishQuery = "INSERT INTO wishlist VALUES(NULL, '$uid_wishlist', '$userId', '$productId')";

                            mysqli_query($db, $addToWishQuery) or die($msg . mysqli_error($db));

                            echo "barang berhasil ditambahkan ke wishlist anda!";

                            header("Refresh:2");
                        }
                        if (isset($_POST["removeToWishlist"])) {
                            $removeToWishQuery = "DELETE FROM wishlist WHERE userId=$userId AND productId=$productId";

                            mysqli_query($db, $removeToWishQuery) or die(mysqli_error($db));

                            echo "barang berhasil dihapus dari wishlist anda!";

                            header("Refresh:2");
                        }
                        //END OF WIHSLIST VALIDATION ADD AND REMOVE
                    }
                }
            }
        }
    }if ($productStatus <= 0) {
        header("location: ../404.html");
    }if ($_SESSION['status']!="login") {
        ?><form action="" method="post"><button type="submit" class="btn btn-primary mt-2" name="addToWishlist">Wishlist</button></form><?php
        ?><form action="" method="post"><button type="submit" class="btn btn-light" name="addToCart">+Keranjang</button></form><?php
        if (isset($_POST["addToWishlist"]) || isset($_POST["addToCart"])) {
            header("location:/shop/login.php?err=login");
        }if (isset($_POST["addToCart"])) {
            header("location:/shop/login.php?err=login");
        }
    }
}
   
?>
</body>         
</html>
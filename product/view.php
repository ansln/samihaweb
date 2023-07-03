<?php

require '../auth/functions/productView.php';
require_once '../auth/functions/priceShow.php';

$getProduct = new productView;
$getUser = new userFunction;
$getWishlist = new wishlistFunction;
$getReview = new productReview;
$forPrice = new productPriceView;

$checkUserLoginforPrice = $forPrice->getUserLoginforPrice();

if(isset($_GET['product'])){

    $product = $_GET['product'];
    $dbProduct = $getProduct->productCheckStatus($product);
    
    if($dbProduct->num_rows){
        while($pd = $dbProduct->fetch_object()){

        $productId = $pd->pd_id;
        $productName = $pd->pd_name;
        $productPrimaryIMG = $pd->pd_img;
        $productStock = $pd->pd_stock;
        $productPrice = $pd->pd_price;
        $productWeight = $pd->pd_weight;
        $descSanitize = htmlspecialchars($pd->pd_desc, ENT_QUOTES, 'UTF-8');
        $descDecode = htmlspecialchars_decode($descSanitize);
        $productDesc = $descDecode;
        $reviewFetchDb = $getReview->getReview($productId);
        if ($checkUserLoginforPrice != true) { $showPrice = '<div id="lp-price"><a href="/shop/login.php">Login untuk melihat harga</a></div>'; }else{ $showPrice = "Rp" . number_format($productPrice,0,"","."); }
        if ($productStock <= 0) { $showStock = 'Stok habis'; }else{ $showStock = 'Sisa ' . $productStock; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Samiha - <?= $productName ?></title>
    <script src="https://kit.fontawesome.com/3f3c1cf592.js" crossorigin="anonymous"></script><script src="../js/jquery-3.6.0.min.js"></script><script src='//cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"><link rel="stylesheet" href="../style/view.css"><link rel="stylesheet" href="../layout/nav.css"><link rel="stylesheet" href="../layout/footer.css"><link rel="stylesheet" href="../style/cssImages.css"><link rel="stylesheet" href="../style/search.css"/>
</head>
<body>
    <div id="show"></div>
    <?php
        $getUser->getVal();
    ?>
    <div class="top-sec-container">
        <div class="sec-wrapper">

        <div class="image-sec-wrapper">
            <div class="sec1">
                <div class="ct-img">
                    <div class="small-img">
                        <?php
                            $get = new productView;
                            $dbImage = $get->showProductImage($product);
                            if($dbImage->num_rows){
                                while($img = $dbImage->fetch_object()){
                                    $fetchAllImg = $img->img_link;
                                    ?><img alt="kurma-samiha-photo" class="image-link" src="<?= $fetchAllImg ?>"><?php
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="sec2">
                <div class="ct-container">
                    <div class="img-container">
                        <img id="bigImage" src="<?= $productPrimaryIMG ?>">
                    </div>
                </div>
                <div class="all-btn-sec">
                    <div class="small">
                        <?php
                        //fetch user wishlist
                        $getWishlist->getUserInfo();
                        //fetch user wishlist for the product from the current page
                        error_reporting(0);
                        $getUserWishlistStatus = $getWishlist->getUserWishlist($productId);

                        if ($getUserWishlistStatus != "") {
                            ?><form enctype="multipart/form-data" id="wishdel"><input type="hidden" name="pdDel" value="<?= $getUserWishlistStatus ?>" id="productId"><button id="wishlistedBtn"><i class="fa-solid fa-heart"></i></button></form><?php
                        }else{
                            ?><form enctype="multipart/form-data" id="wishadd"><input type="hidden" name="pdAdd" value="<?= $productId ?>" id="productId"><button id="wishlistBtn"><i class="fa-solid fa-heart"></i>Wishlist</button></form><?php
                        } ?>
                    </div>

                    <button id="chatBtn"><i class="fa-solid fa-message"></i> Chat</button>
                    <button id="shareBtn"><i class="fa-solid fa-share-nodes"></i> Share</button>
                </div>
            </div>
        </div>

            <div class="sec3">
                <div class="title-row">
                    <h3><?= $productName ?></h3>
                </div>
                <div class="sub-title">
                    <div class="rating">
                        <i class="fa-solid fa-star"></i>
                        <b>4.8</b>
                    </div>
                    <!--<p>50 Ulasan</p>-->
                    <p><span><?= $showStock ?></span></p>
                </div>
                <div class="price-sec">
                    <h2><?= $showPrice ?></h2>
                </div>
                <div class="mindesc-sec">
                    <div class="mindesc-row">
                        <span>Berat Satuan: </span>
                        <p><?= $productWeight ?> g</p>
                    </div>
                    <div class="mindesc-row">
                        <span>Kategori: </span>
                        <a href="">Buah-buahan</a>
                    </div>
                </div>
                <div class="prod-type-sec">
                    <p>Pilih jenis produk: </p>
                    <div class="type-choose">
                        <a href="" class="active">500gr</a>
                        <a href="">850gr</a>
                        <a href="">1000gr</a>
                    </div>
                </div>
                <div class="loc-sec">
                    <i class="fa-solid fa-location-dot"></i>
                    <p>Dikirim dari <b>Bekasi Selatan</b></p>
                </div>
                <div class="cart-sec">
                    <form enctype="multipart/form-data" id="addToCart"><input type="hidden" name="cartProductId" value="<?= $productId ?>" id="cartProductId"><button id="addToCartBtn"><i class="fa-solid fa-cart-shopping"></i><b>Tambahkan ke keranjang</b></button></form>
                </div>
            </div>
        </div>
    </div>
    <div class="sec-prod-det">
        <div class="sec-prod-det-wrapper">
            <div id="top-title">Informasi Produk</div>
            <div class="prod-content">
                <?= $productDesc ?>
            </div>
        </div>
    </div>
    <div class="sec-review">
        <div class="sec-review-wrapper">
            <div id="top-title">Ulasan</div>
            <?php if($reviewFetchDb <= 0){
                ?>
                <div class="no-review-card">
                    <div class="rv-card-left">
                        <img src="../assets/etc/Beach_Two Color.svg">
                    </div>
                    <div class="rv-card-right">
                        <h3>Belum ada ulasan</h3>
                        <p>Yuk jadi orang pertama yang memberikan ulasan untuk produk ini</p>
                    </div>
                </div>
                <?php
            }else{ ?>
            <div class="total-rating">
                <i class="fa-solid fa-star"></i>
                <b>4.8</b>
                <div class="all-review">50 Ulasan</div>
            </div>

            <?php if($reviewFetchDb->num_rows){
                while($review = $reviewFetchDb->fetch_object()){
                    $userId = $review->userId;
                    $userRating = $review->userRating;
                    $userComment = $review->userComment;
                    $commentDate = $review->commentDate;

                    $userFetchDb = $getReview->getUser($userId);
                    if($userFetchDb->num_rows){
                        while($user = $userFetchDb->fetch_object()){
                            $userPict = $user->u_profilePict;
                            $username = $user->u_username;
            ?>
            <div class="box-comment">
                <div class="box-comment-title">
                    <img src="<?= $userPict ?>">
                    <b><?= $username ?></b>
                </div>
                <div class="box-comment-rating">
                    <?php while ($userRating >= 1) { $userRating--; ?><i class="fa-solid fa-star"></i><?php } ?>
                </div>
                <div id="comment-date"><?= $commentDate ?></div>
                <div id="user-comment"><?= $userComment ?></div>
            </div>
            <?php }}}}} ?>
            
        </div>
    </div>

    <div class="sec-prod-rec">
        <div class="sec-prod-rec-wrapper">
            <div id="top-title">Produk Lainnya</div>
            <div class="sec-card-container">
                <div class="sec-card-wrapper">
                    <?php
                    $getDb = new connV2;
                    $db = $getDb->getDb();

                    $otherProductQuery = $db->query("SELECT * FROM product WHERE pd_id != '$productId' AND status = 1 AND pd_id != '19' ORDER BY pd_id DESC");

                    foreach ($otherProductQuery as $pf) {
                        $oProductName = $pf["pd_name"];
                        $oProductIMG = $pf["pd_img"];
                        $oProductPrice = $pf["pd_price"];
                        $oProductStock = $pf["pd_stock"];
                        $oProductLink = $pf["pd_link"];

                        if ($checkUserLoginforPrice != true) { $oShowPrice = null; }else{ $oShowPrice = "Rp" . number_format($oProductPrice,0,"","."); }
                        if ($oProductStock <= 0) { $oShowStock = 'Stok habis'; }else{ $oShowStock = 'Sisa ' . $oProductStock; }

                        ?>
                            <a href="view?product=<?= $oProductLink ?>">
                        <div class="card">
                            <img src="<?= $oProductIMG ?>">
                            <div class="text-wrapper">
                                <div id="title"><?= $oProductName ?></div>
                                <div id="price"><?= $oShowPrice ?></div>
                                <div class="rating">
                                    <i class="fa-solid fa-star"></i>
                                    <b>5</b>
                                    <p><?= $oShowStock ?></p>
                                </div>
                            </div>
                        </div>
                    </a>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php include '../layout/footer.php'; ?>
    <script src="../js/productView.js"></script><script src="../js/search.js"></script>
</body>
</html>
<?php
        }
    }
}else{
    header('Location: ./');
}



?>

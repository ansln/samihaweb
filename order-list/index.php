<?php

require_once '../auth/comp/vendor/autoload.php';
require_once '../auth/session.php';
require_once '../auth/conn2.php';
require_once '../auth/orderList.php';
require_once '../auth/functions/fetchUser.php';

$user = new fetchUserData;
$order = new orderManagement;
$filter = new transactionFilter;
$username = $user->username();
$userEmail = $user->userEmail();
$userPict = $user->userPict();
$getNavbar = $order->getNav();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Samiha - Daftar Transaksi</title>
    <script src="https://kit.fontawesome.com/3f3c1cf592.js" crossorigin="anonymous"></script><script src="../js/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../style/order-list.css"><link rel="stylesheet" href="../layout/nav.css"><link rel="stylesheet" href="../style/scrollselection.css"><link rel="stylesheet" href="../style/cssImages.css">
</head>
<body>
    <?= $getNavbar ?>
    <div class="container">
        <div class="ct-wrapper">
            <div class="top-title">Daftar Transaksi</div>
            <div class="ct-content">
                <div class="sub-content">
                    <div class="sub-sec1">
                        <div id="profile-pict-wrapper"><img id="profile-pict-menu" src="<?= $userPict ?>"></div>
                        <div id="user-detail">
                            <b><?= $username ?></b>
                        </div>
                    </div>
                    <div class="sub-sec2">
                        <a href="?status=semua">Semua</a>
                        <a href="?status=berlangsung">Berlangsung</a>
                        <a href="?status=berhasil">Berhasil</a>
                        <a href="?status=tidak-berhasil">Tidak Berhasil</a>
                    </div>
                    <div class="sub-sec3">
                        <a href="../wishlist/">Wishlist</a>
                        <a href="">Ulasan</a>
                        <a href="">Bantuan</a>
                    </div>
                </div>

                <div class="content-detail">
                    <div id="pay-sec">
                        <div class="left-icon">
                            <i class="fa-solid fa-money-bill-wave"></i>
                            <b>Menunggu Pembayaran</b>
                        </div>
                        <i class="fa-solid fa-angle-right arr"></i>
                    </div>
                    <?php if (isset($_GET["status"])) {
                            $get = $_GET["status"]; 
                            $filter->catchFilter($get);
                        }else{
                            $filter->noFilterHome();
                        } ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("profile-pict").addEventListener("click", home);
        document.getElementById("pay-sec").addEventListener("click", test);

        function home(){
            window.location.replace("../user");
        }
        function test(){
            var spinnerLoading = '<span class="loader2"></span>';
            $(".content-detail").html(spinnerLoading);
            window.location.replace("?status=menunggu-pembayaran");
        }
        $("#main-logo").click(function(){
            window.location.replace("../");
        });
        $("#cart-btn").click(function(){
            window.location.replace("../cart/");
        });
        $("#profile-pict").click(function(){
            window.location.replace("../user/");
        });
        $("#profile-pict-menu").click(function(){
            window.location.replace("../user/");
        });
    </script>
</body>
</html>
<?php

if (isset($_GET["status-result-done"]) ) {
    $invoiceId = $_GET["status-result-done"];
    $order->updateStatusOrder($invoiceId);
}

?>
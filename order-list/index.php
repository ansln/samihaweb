<?php

require_once '../auth/comp/vendor/autoload.php';
require_once '../auth/session.php';
require_once '../auth/conn2.php';
require_once '../auth/orderList.php';
require_once '../auth/functions/fetchUser.php';

$user = new fetchUserData;
$order = new orderManagement;
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
    <script src="https://kit.fontawesome.com/3f3c1cf592.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style/order-list.css"><link rel="stylesheet" href="../layout/nav.css">
</head>
<body>
    <?= $getNavbar ?>
    <div class="container">
        <div class="ct-wrapper">
            <div class="top-title">Daftar Transaksi</div>
            <div class="ct-content">
                <div class="sub-content">
                    <div class="sub-sec1">
                        <img id="profile-pict" src="<?= $userPict ?>">
                        <div id="user-detail">
                            <b><?= $username ?></b>
                        </div>
                    </div>
                    <div class="sub-sec2">
                        <a href="">Semua</a>
                        <a href="">Berlangsung</a>
                        <a href="">Berhasil</a>
                        <a href="">Tidak Berhasil</a>
                    </div>
                    <div class="sub-sec3">
                        <a href="../wishlist/">Wishlist</a>
                        <a href="">Ulasan</a>
                        <a href="">Bantuan</a>
                    </div>
                </div>

                <div class="content-detail">
                    <div class="pay-sec">
                        <div class="left-icon">
                            <i class="fa-solid fa-money-bill-wave"></i>
                            <b>Menunggu Pembayaran</b>
                        </div>
                        <i class="fa-solid fa-angle-right arr"></i>
                    </div>
                    <div class="content-card">
                        <div class="card-child-left">
                            <div class="left-child-sec1">
                                <div id="inv-id">SDO-25102022-SMH-00014</div>
                                <div id="date">25 Oktober 2022</div>
                            </div>
                            <div class="left-child-sec2">
                                <img src="https://ik.imagekit.io/samiha/2201e734935dc002df97de25789d4c04-2965287061_xiPNPvyJ3.jpg">
                                <div class="text-pd">
                                    <h3>Samiha Kurma Ajwa 500gr</h3>
                                    <p>1 barang x Rp150.000</p>
                                </div>
                            </div>
                            <div class="left-child-sec3">
                                <span>Selesai</span>
                                <a href="">Memiliki kendala? hubungi kami</a>
                            </div>
                        </div>
                        <div class="card-child-right">
                            <div class="right-child-sec1">
                                <div class="grand-total">
                                    <p>Total Harga</p>
                                    <h3>Rp150.000</h3>
                                </div>
                            </div>
                            <div class="right-child-sec2">
                                <a href="">Detail Transaksi</a>
                                <button>Beli Lagi</button>
                            </div>
                        </div>
                    </div>
                    <div class="content-card">
                        <div class="card-child-left">
                            <div class="left-child-sec1">
                                <div id="inv-id">SDO-25102022-SMH-00014</div>
                                <div id="date">25 Oktober 2022</div>
                            </div>
                            <div class="left-child-sec2">
                                <img src="https://ik.imagekit.io/samiha/2201e734935dc002df97de25789d4c04-2965287061_xiPNPvyJ3.jpg">
                                <div class="text-pd">
                                    <h3>Samiha Kurma Ajwa 500gr</h3>
                                    <p>1 barang x Rp150.000</p>
                                </div>
                            </div>
                            <div class="left-child-sec3">
                                <span>Selesai</span>
                                <a href="">Memiliki kendala? hubungi kami</a>
                            </div>
                        </div>
                        <div class="card-child-right">
                            <div class="right-child-sec1">
                                <div class="grand-total">
                                    <p>Total Harga</p>
                                    <h3>Rp150.000</h3>
                                </div>
                            </div>
                            <div class="right-child-sec2">
                                <a href="">Detail Transaksi</a>
                                <button>Beli Lagi</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("profile-pict").addEventListener("click", home);

        function home(){
            window.location.replace("../user");
        }
    </script>
</body>
</html>
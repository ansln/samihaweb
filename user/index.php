<?php

require_once 'conn.php';
require_once '../auth/comp/vendor/autoload.php';
require_once '../auth/session.php';

$get = new userSession;

if ($_COOKIE['SMHSESS'] != "") {

    $email = $get->generateEmail();
    $userQuery = $db->query("SELECT * FROM user WHERE u_email = '$email' OR u_phone = '$email'");
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Samiha Dates - Profile</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
        <link rel="stylesheet" href="../style/user.css"><link rel="stylesheet" href="../layout/navprofile.css"><link rel="stylesheet" href="../style/cssImages.css"><link rel="stylesheet" href="../style/cssImgUpload.css"><link rel="stylesheet" href="../style/scrollselection.css"><link rel="stylesheet" href="../layout/footer.css">
        <script src="../js/jquery-3.6.0.min.js"></script>
        <script src="https://kit.fontawesome.com/3f3c1cf592.js" crossorigin="anonymous"></script>
        <script src="../js/edit-pic.js"></script><script src="../js/loading.js"></script>
        <script src='//cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <div class="loader-container">
            <span class="loader"></span>
        </div>

        <div id="editPic"></div>
        <?php
        if ($userQuery->num_rows) { // -> fetch user data
            while ($u_fetch = $userQuery->fetch_object()) {

                //FETCH USER DATA
                $userId = $u_fetch->id;
                $userProfilePict = $u_fetch->u_profilePict;
                $profilePict = userPictCheck($userProfilePict);
                $userName = $u_fetch->u_username;
                $firstName = $u_fetch->u_fName;
                $lastName = $u_fetch->u_lName;
                $userFullName = $firstName . " " . $lastName;
                $fetchDOB = new DateTime($u_fetch->u_dob);
                $userDOB = $fetchDOB->format('d M Y');
                $userGender = $u_fetch->u_gender;
                $userEmail = $u_fetch->u_email;
                $userPhone = $u_fetch->u_phone;
                $userStatus = $u_fetch->status;

                $wishlistQuery = $db->query("SELECT * FROM wishlist WHERE userId=$userId");
                $cartQuery = $db->query("SELECT * FROM cart WHERE userId=$userId");

                include "../layout/navprofile.php";
        ?>
        <div class="container">
            <div class="ct-box">
                <div class="user-left">
                    <div class="profile-details">
                        <div class="profile-detailsImg">
                            <img src="<?= $profilePict ?>">
                        </div>
                        <div class="profile-text">
                            <b><?= $userName ?></b>
                            <p><?= $userEmail ?></p>
                        </div>
                    </div>

                    <div class="menu">
                        <div class="nav-menu">
                            <a href="./">Profile</a>
                            <a href="../cart">Keranjang</a>
                            <a href="../order-list/">Daftar Transaksi</a>
                            <a href="../wishlist/">Wishlist</a>
                        </div>
                        <div class="btm-menu">
                            <a href="">Support</a>
                        </div>
                    </div>
                </div>
                <div class="user-right">
                    <div class="left-box">
                        <img src="<?= $profilePict ?>">
                        <div class="button-block">
                            <?php if ($userFullName != "demo account") { ?><a href="u/editPic.php" class="editPicButton"><button>Pilih Foto</button></a><?php }else{ ?><button id="editPicBtn">Pilih Foto</button></a><?php } ?>
                        </div>
                        <button id="userOutBtn">Logout</button>
                    </div>
                    <div class="right-box">
                        <h2>Profile</h2>
                        <div class="user-detail">
                            <div class="row">
                                <div class="left-column">
                                    <b>Nama</b>
                                    <b>Tanggal Lahir</b>
                                    <b>Jenis Kelamin</b>
                                    <b>Email</b>
                                    <b>No. Telepon</b>
                                </div>
                                <div class="right-column">
                                    <div class="rc-row">
                                        <p><?= $userFullName ?></p>
                                        <?php if ($userFullName != "demo account") { ?><form enctype="multipart/form-data" id="getName"><button id="nameEditBtn">Ubah</button></form><?php } ?>
                                    </div>
                                    <div class="rc-row">
                                        <p><?= $userDOB ?></p>
                                    </div>
                                    <div class="rc-row">
                                    <p><?php if ($userGender == "Male") { echo "Pria"; }if ($userGender == "Female") { echo "Wanita"; }if ($userGender == "Pria") { echo "Pria"; }if ($userGender == "Wanita") { echo "Wanita"; } ?></p>
                                    </div>
                                    <div class="rc-row">
                                        <p id="email"><?= $userEmail ?> <?php 
                                        if ($userStatus == 1){ ?><i class="fa-solid fa-check"></i><?php }else{ ?><b>not verified</b><?php } ?></p>
                                    </div>
                                    <div class="rc-row2">
                                        <p><?= $userPhone ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <button id="resetPasswordBtn">Ubah Password</button>
                                <button id="addressPageBtn">Daftar Alamat</button>
                            </div>
                            <?php
                                if ($userStatus == 0) {
                                    ?><form action="ver/index.php?email=<?= $userEmail ?>" method="POST"><div class="row"><button name="submit">Verifikasi Email</button></div></form><?php
                                }
                            ?>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="phone-top-navbar">
            <div id="phone-back-nav">
                <i class="fa-solid fa-angle-left"></i>
                <span>Profile</span>
            </div>
        </div>
        <div class="phone-user-container">
            <div class="phone-user-mt-ct-wrapper">
                <div class="phone-user-ct">
                    <div class="phone-user-ct-wrapper">
                        <div class="phone-user-top-sec">
                            <div class="phone-user-top-sec-left">
                                <div id="phone-user-image"><img src="<?= $userProfilePict ?>"></div>
                            </div>
                            <div class="top-sec-right">
                                <div id="phone-user-fn"><?= $userFullName ?></div>
                                <div id="phone-user-ph"><?= $userPhone ?></div>
                                <div id="phone-user-email"><?= $userEmail ?></div>
                            </div>
                        </div>
                        <div class="phone-user-btm-sec">
                            <div class="phone-user-account-menu">
                                <div id="phone-user-tx-list">
                                    <div id="tx-list-left">
                                        <i class="fa-solid fa-money-bill-transfer"></i>
                                    </div>
                                    <div id="phone-user-text-row">
                                        <div id="phone-user-text-row-title">Daftar Transaksi</div>
                                        <div id="phone-user-text-row-sub">Cek status belanjaan kamu</div>
                                    </div>
                                </div>
                                <div id="phone-user-address">
                                    <div id="ph-address-left">
                                        <i class="fa-solid fa-house-chimney-window"></i>
                                    </div>
                                    <div id="phone-user-text-row">
                                        <div id="phone-user-text-row-title">Alamat</div>
                                        <div id="phone-user-text-row-sub">Atur alamat pengiriman belanjaan</div>
                                    </div>
                                </div>
                                <div id="phone-user-cart">
                                    <div id="ph-address-left">
                                        <i class="fa-solid fa-cart-shopping"></i>
                                    </div>
                                    <div id="phone-user-text-row">
                                        <div id="phone-user-text-row-title">Keranjang</div>
                                        <div id="phone-user-text-row-sub">Cek keranjang belanjaan</div>
                                    </div>
                                </div>
                                <div id="phone-user-wishlist">
                                    <div id="ph-address-left">
                                        <i class="fa-solid fa-heart"></i>
                                    </div>
                                    <div id="phone-user-text-row">
                                        <div id="phone-user-text-row-title">Wishlist</div>
                                        <div id="phone-user-text-row-sub">Atur barang kesukaan kamu</div>
                                    </div>
                                </div>
                            </div>
                            <div id="phone-user-support"><i class="fa-solid fa-headset"></i><div id="phone-support-btn">Support</div></div>
                            <div id="phone-user-logout"><i class="fa-solid fa-right-from-bracket"></i><div id="phone-logout-btn">Logout</div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../layout/footer.php'; ?>
        <script src="../js/user.js"></script>
    </body>
    </html>
<?php
                }
            }
}if ($_COOKIE['SMHSESS'] == "") {
    ?><script>window.location.replace('../login.php?err=login');</script><?php //REDIRECT TO HOMEPAGE
}

function userPictCheck($value){
    if ($value != "") {
        return $value;
    }else{
        return "../assets/etc/default.png";
    }
}
<?php

require_once 'conn.php';
// require_once '../auth/functions/index.php';
require_once '../auth/comp/vendor/autoload.php';
require_once '../auth/session.php';

$get = new userSession;

if ($_COOKIE['SMHSESS'] == "") {
    header("location: ../");
}else{
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Samiha - Address</title>
    <link rel="stylesheet" href="../style/address.css"><link rel="stylesheet" href="../layout/nav.css"><link rel="stylesheet" href="../style/cssImages.css"><link rel="stylesheet" href="../style/addressFetch.css"><link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <script src="../js/jquery-3.6.0.min.js"></script><script src="https://kit.fontawesome.com/3f3c1cf592.js" crossorigin="anonymous"></script><script src="../js/loading.js"></script>
</head>
<body>
    <div class="loader-container">
        <span class="loader"></span>
    </div>

    <?php
    $email = $get->generateEmail();

    $userQuery = $db->query("SELECT * FROM user WHERE u_email = '$email' OR u_phone = '$email'");
    
    if($userQuery->num_rows){
        while($u_fetch = $userQuery->fetch_object()){
            include '../layout/navprofile.php';
            $userId = $u_fetch->id;
            $userAddressQuery = $db->query("SELECT * FROM user_address WHERE userId = $userId ORDER BY addressPrimary DESC");
            $checkUserAddress = mysqli_num_rows($userAddressQuery);
            ?>
                    <div class="address-container-topBox">
                        <div class="wrapper-box">
                            <div class="box-wrapper-btn">
                                <div class="left-btn-box">
                                    <a href="./"><button>Kembali ke Profile</button></a>
                                </div>
                                <div class="right-btn-box">
                                    <a href="?add"><button><i class="fa-solid fa-plus"></i> Tambah Alamat</button></a>
                                </div>
                            </div>
            <?php
            if ($checkUserAddress < 0) {
                ?><p>Kamu tidak memiliki alamat</p><?php
            }
                            if($userAddressQuery->num_rows){
                                while($uAd = $userAddressQuery->fetch_object()){
                
                                    //FETCH USER ADDRESS DETAIL
                                    $useraddressLabel = $uAd->u_addressLabel;
                                    $userRecipient = $uAd->u_recName;
                                    $userPhone = $uAd->u_phone;
                                    $userPostalCode = $uAd->u_postalCode;
                                    $userAddressMix = $uAd->u_addressMix;
                                    $userFullAddress = $uAd->u_completeAddress;
                                    $userAddresStatus = $uAd->u_defaultAddress;
                                    
                                    if ($userAddresStatus == 0) { //CHECK IF DEFAULT ADDRES OR NOT
                                        ?><div class="address-card-box2"><?php
                                    }else{
                                        ?><div class="address-card-box"><?php
                                    }
            ?>
                                <div class="wrapper-box-left">
                                    <div class="top-box">
                                        <h3>Alamat <?= $useraddressLabel ?></h3>
                                        <h2><?= $userRecipient ?></h2>
                                    </div>
                                    <div class="mid-box">
                                        <p><?= $userPhone ?></p>
                                        <p><?= $userFullAddress . ", " . $userPostalCode ?></p>
                                        <b><?= $userAddressMix ?></b>
                                    </div>
                                    <div class="btm-box">
                                        <button>Ubah Alamat</button>
                                        <a href=""><i class="fa-solid fa-trash"></i> hapus</a>
                                    </div>
                                </div>
                                <div class="wrapper-box-right">
                                    <?php
                                    if ($userAddresStatus == 0) { //CHECK IF DEFAULT ADDRES OR NOT
                                        ?><button>Pilih alamat</button><?php
                                    }else{
                                        ?><i class="fa-solid fa-check"></i><?php
                                    }
                                    ?>
                                </div>
                            </div>
            <?php
                        }
                    }
            ?>
                    </div>
            <?php
        }
    }
        if (isset($_GET["add"])) { //add address
            ?>
                <div class="modal-container">
                    <div class="top-container">
                        <div class="wrapper">
                            <div class="ct-add">
                                <div class="box-add">
                                    <div class="box-add-title">
                                        <h2>Informasi Alamat Lengkap</h2>
                                        <a href="address"><i class="fa-solid fa-xmark"></i></a>
                                    </div>
                                    <form action="" method="post" autocomplete="off">
                                        <div class="add-content">
                                            <input type="text" placeholder="Nama Penerima" name="recipientName">
                                            <input type="text" placeholder="No. Telepon" name="phoneNumber">
                                            <input type="text" placeholder="Label/Alamat" name="addressLabel">
                                            <div class="wrapper-ct-address">
                                                <input type="text" placeholder="Tulis Nama Kota / Kecamatan / Kode Pos">
                                                <div class="autocom-box"></div>
                                                <input type="hidden" id="userAddressInput" name="addressCityEtc" value="">
                                            </div>
                                            <textarea placeholder="Alamat Lengkap" name="fullAddress"></textarea>
                                            <button type="submit" name="submit">Simpan Alamat</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    <?php
        }
        ?>
        <script src="../js/addressFetch.js"></script>
        </body>
        </html>
        <?php
    if(isset($_POST["submit"])){
        if(isset($_POST["recipientName"]) != ""){
            error_reporting(0);
            include "../auth/addAddrAuth.php";
        }else{
            header("location: /shop/");
        }
    }
}
?>
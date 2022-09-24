<?php

session_start();
require_once 'conn.php';
require_once '../auth/functions/index.php';
error_reporting(0);

if ($_SESSION['status'] != "login") {
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
    <link rel="stylesheet" href="../style/address.css"><link rel="stylesheet" href="../layout/nav.css">
    <script src="https://kit.fontawesome.com/3f3c1cf592.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php
        
    $uData = $db->real_escape_string($_SESSION['email']);
    $uDataP = $db->real_escape_string($_SESSION['phone']);

    $userQuery = $db->query("SELECT * FROM user WHERE u_email = '$uData' OR u_phone = '$uDataP'");
    
    if($userQuery->num_rows){ // -> fetch data
        while($u_fetch = $userQuery->fetch_object()){
            include '../layout/navprofile.php';
            $userId = $u_fetch->id;
            $userAddressQuery = $db->query("SELECT * FROM user_address WHERE userId = $userId");
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
                            if($userAddressQuery->num_rows){ // -> fetch data
                                while($uAd = $userAddressQuery->fetch_object()){                                 
                
                                    //FETCH USER ADDRESS DETAIL
                                    $useraddressLabel = $uAd->u_addressLabel;
                                    $userRecipient = $uAd->u_recName;
                                    $userPhone = $uAd->u_phone;
                                    $userProvinceName = $uAd->u_provinceName;
                                    $userCityName = $uAd->u_cityName;
                                    $userDisctrictName = $uAd->u_disctrict;
                                    $userPostalCode = $uAd->u_postalCode;
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
                                        <p><?= $userFullAddress . ", " . $userDisctrictName . ", " . $userPostalCode ?></p>
                                        <b><?= $userProvinceName . ", " . $userCityName ?></b>
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
                                        </div>
                                        <form action="" method="post" autocomplete="off">
                                            <div class="add-content">
                                                <input type="text" placeholder="Nama Penerima" name="recipientName">
                                                <input type="text" placeholder="No. Telepon" name="phoneNumber">
                                                <input type="text" placeholder="Label/Alamat" name="addressLabel">
                                                <div class="row-add">
                                                    <div class="province">
                                                        <select name="province" id="province" onchange="province_select(this.value);">
                                                            <option disabled selected>Pilih Provinsi</option>
                                                            <?php
                                                            include_once ('ongkir/curl_prov.php'); 
                                                                foreach ($jsonDecodeProv as $row) {
                                                                    $provinceId = $row["province_id"];
                                                                    $provinceName = $row["province"];

                                                                    ?><option value="<?= $provinceId ?>"><?=$provinceName?></option><?php
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                            
                                                    <div class="city">
                                                        <div id="poll">
                                                            <select name="selectCity" id="selectCity">
                                                                <option disabled selected>Pilih Kota</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row-add">
                                                    <input type="text" placeholder="Kecamatan" name="district"> 
                                                    <input type="text" placeholder="Kode Pos" name="postalCode">
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
        <script src="../js/add.js"></script>
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
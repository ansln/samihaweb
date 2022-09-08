<?php

session_start();
require_once 'conn.php';
require_once '../auth/functions/index.php';

if ($_SESSION['status'] == "login") {
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
        <title>Samiha Dates - Profile</title>
        <link rel="stylesheet" href="../style/user.css"><link rel="stylesheet" href="../layout/nav.css">
        <script src="../js/jquery-3.6.0.min.js"></script>
    </head>

    <body>
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
                            <a href="">Chat</a>
                            <a href="">Daftar Transaksi</a>
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
                        <button>Pilih Foto</button>
                        <a href="../logout.php"><button>Logout</button></a>
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
                                        <button id="nameEditBtn">Ubah</button>
                                        <div id="show"></div>
                                    </div>
                                    <div class="rc-row">
                                        <p><?= $userDOB ?></p>
                                        <button>Ubah</button>
                                    </div>
                                    <div class="rc-row">
                                    <p><?php $gender = $u_fetch->u_gender;
                                        if ($gender == "Male"){ echo "Pria";
                                        }if ($gender == "Female"){ echo "Wanita";
                                        }if ($gender == "Other"){ echo "Lain-lain";
                                        }else if($gender == "Male" && $gender == "Female" && $gender == "Other"){ echo "Unknown";}
                                    ?></p>
                                        <button>Ubah</button>
                                    </div>
                                    <div class="rc-row">
                                        <p><?= $userEmail ?> <?php if ($userStatus == 1) { echo "Verified!"; } ?></p>
                                        <button>Ubah</button>
                                    </div>
                                    <div class="rc-row">
                                        <p><?= $userPhone ?></p>
                                        <button>Ubah</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <button>Ubah Password</button>
                                <a href="address"><button>Daftar Alamat</button></a>
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
        <script src="../js/user.js"></script>
    </body>
    </html>
<?php
                    if (isset($_POST["submit"])) {
                        include 'ver/index.php';
                    }
                }
            }
}if ($_SESSION['status'] != "login") {
    header("location:/shop/login.php?err=login");
}
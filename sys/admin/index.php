<?php
    require_once 'auth/conn.php';
    require 'auth/functions.php';
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="style/style.css"><link rel="stylesheet" href="style/product.css"><link rel="stylesheet" href="style/input.css">
    <script src="../../js/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php
    if ($_SESSION['status'] != "admin-login") {
    ?>
        <b><a href="login.php">Masuk</a></b>
    <?php
    }else{
        if (isset($_SESSION['email']) || isset($_SESSION['phone'])) {
            $uData = $db->real_escape_string($_SESSION['email']);

            $u_fetch = $db->query("SELECT * FROM admin WHERE adm_email LIKE '{$uData}'");

            if ($u_fetch->num_rows) {
                while ($r = $u_fetch->fetch_object()) {

                    $adminUsername = $r->adm_username;
                    ?>
                        <div class="container">
                            <div class="row">
                                <div class="box">
                                    <div class="title">
                                        <div class="rw">
                                            Welcome, <b><?= sP($adminUsername) ?></b>
                                            <?php
                                                if ($r->status == "dev") {
                                                    ?><img src="../../assets/img/adm-dev.png"><?php
                                                }if ($r->status == "admin") {
                                                    ?><img src="../../assets/img/adm-admin.png"><?php
                                                }if ($r->status == "seller") {
                                                    ?><img src="../../assets/img/adm-seller.png"><?php
                                                }
                                            ?>
                                        </div>
                                        <div class="rw">
                                            Server Time: <span id="dgTime"></span>
                                        </div>
                                    </div>
                                    <div class="side-link">
                                        <div class="link-icon">
                                            <img src="../../assets/img/adm-refresh.png"><button id="reload">Home</button>
                                        </div>
                                        <div class="link-icon">
                                            <img src="../../assets/img/adm-home2.png"><button id="dashboard">Dashboard</button>
                                        </div>
                                        <div class="link-icon">
                                            <img src="../../assets/img/adm-box-item.png">
                                            <button id="product-list">List Produk</button>
                                        </div>
                                        <div class="link-icon">
                                            <img src="../../assets/img/adm-product-upload.png">
                                            <button id="add-product">Tambah Produk</button>
                                        </div>
                                        <div class="link-icon">
                                            <img src="../../assets/img/adm-transaction.png">
                                            <button id="transaction">List Transaksi</button>
                                        </div>
                                        <div class="link-icon">
                                            <img src="../../assets/img/adm-user-admin.png">
                                            <button id="user-list">List User</button>
                                        </div>
                                        <div class="link-icon">
                                            <img src="../../assets/img/adm-disc.png">
                                            <button id="promo">Promo</button>
                                        </div>
                                        <a href="logout.php">
                                        <div class="link-icon">
                                            <img src="../../assets/img/adm-logout.png">
                                            <button id="">Logout</button>
                                        </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="box-content">
                                    <div id="post-data">
                                        <div class="ct-home">
                                            <img src="../../assets/img/68.png">
                                            <h1>Hi!</h1>
                                            <h3>Welcome to Admin Page</h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                }
            }
        }
    }
        ?>
    <script src="js/app.js"></script>
</body>
</html>
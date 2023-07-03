<?php

    require_once 'auth/user/conn.php';
    require_once '../../auth/comp/vendor/autoload.php';

    if (!isset($_COOKIE['ADMSESS'])) {
        ?><script>window.location.replace('logout');</script><?php
    }else{
    ?><!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Samiha - Admin</title>
            <link rel="stylesheet" href="style/style.css"><link rel="stylesheet" href="style/product.css"><link rel="stylesheet" href="style/orderList.css"><link rel="stylesheet" href="style/article.css">
            <script src="../../js/jquery-3.6.0.min.js"></script><script src="https://kit.fontawesome.com/3f3c1cf592.js" crossorigin="anonymous"></script>
        </head>
        <body>
            <div class="container">
                <div class="row">
                    <div class="box">
                        <div class="title">
                            <div class="rw">
                                Server Time: <span id="dgTime"></span>
                            </div>
                        </div>
                        <div class="side-link">
                            <div class="link-icon" id="reload">
                                <i class="fa-solid fa-house"></i><button id="homeBtn">Home</button>
                            </div>
                            <div class="link-icon" id="dashboard">
                                <i class="fa-solid fa-sliders"></i><button id="dashboardBtn">Dashboard</button>
                            </div>
                            <div class="link-icon" id="product-list">
                                <i class="fa-solid fa-box-open"></i><button id="productListBtn">List Produk</button>
                            </div>
                            <div class="link-icon" id="add-product">
                                <i class="fa-solid fa-circle-plus"></i><button id="addProductBtn">Tambah Produk</button>
                            </div>
                            <div class="link-icon" id="transaction">
                                <i class="fa-solid fa-receipt"></i><button id="transactionListBtn">List Transaksi</button>
                            </div>
                            <!--<div class="link-icon" id="user-list">-->
                            <!--    <i class="fa-solid fa-user"></i><button id="userListBtn">List User</button>-->
                            <!--</div>-->
                            <div class="link-icon" id="content">
                                <i class="fa-solid fa-newspaper"></i><button id="addContentBtn">Artikel</button>
                            </div>
                            <div class="link-icon" id="promotion">
                                <i class="fa-solid fa-rectangle-ad"></i><button id="addPromotionBtn">Banner Promosi</button>
                            </div>
                            <div class="link-icon" id="edit-promotion">
                                <i class="fa-solid fa-gears"></i><button id="editPromotion">Edit Banner</button>
                            </div>
                            <div class="link-icon" id="logout">
                                <i class="fa-solid fa-person-running"></i><button id="logoutBtn">Logout</button>
                            </div>
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
            <script src="js/app.js"></script>
        </body>
    </html>
<?php
} if (isset($_POST["detailBtnSubmit"])) { $inv = $_POST["order-data"]; ?><script>window.location.replace('edit/order.php?inv=<?= $inv ?>');</script><?php }

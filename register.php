<?php
    error_reporting(0);
    session_start();
    if($_SESSION['status']!="login"){
?>
<html>
    <head>
        <title>Samiha Dates - Register</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style/register.css"><link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    </head>
    <body>
        <div class="container">
            <div class="reg-content">
                <a href="index.php"><img src="assets/img/logo/logo2.png"></a>
                    <div class="reg-component">
                        <div class="right-text">
                            <h2>Daftar di Samiha</h2>
                            <h3>Kamu sudah punya akun? <a href="login.php">Masuk</a> di sini</h3>
                        </div>

                        <form action="?reg-submit" method="post" autocomplete="off">
                            <div class="reg-fill">
                                <div class="row">
                                    <input type="text" name="firstName" required placeholder="Nama Depan" autocomplete="off">
                                    <input type="text" name="lastName" required placeholder="Nama Belakang" autocomplete="off">
                                </div>
                                <div class="row">
                                    <input type="text" name="username" required placeholder="Username" autocomplete="off">
                                    <input type="text" name="phone" placeholder="No. Telepon" autocomplete="off">
                                </div>
                                <div class="row2">
                                    <input type="email" name="email" placeholder="Email" autocomplete="off">
                                </div>
                                <div class="row2">
                                    <input type="password" name="password" placeholder="Password" autocomplete="off">
                                </div>
                                <div class="row">
                                    <div class="wrapper">
                                        <div class="select-btn">
                                            <span>Jenis Kelamin</span>
                                            <i class="uil uil-angle-down"></i>
                                        </div>
                                        <div class="content">
                                            <ul class="options" name="genderSelect"></ul>
                                        </div>
                                        <input type="hidden" name="getGenderSelect" id="getGenderSelect">
                                    </div>
                                    <input name="dateOfBirth" type="date">
                                </div>
                            </div>
                            <div class="row">
                                <button type="submit">Daftar</button>
                            </div>
                        </form>
                    </div>
            </div>
            <b>© 2022 | Samiha Web</b>
        </div>
        <script src="js/reg.js"></script>
    </body>
</html>
<?php
    if(isset($_GET["reg-submit"])){
        if(isset($_POST["firstName"]) == ""){
            header("location: /shop/register.php");
        }else{
            error_reporting(0);
            include "auth/functions/index.php";
            include "auth/oe.php";
        }
    }

}else{
    echo "anda sudah login";
    header("location:/shop/");
}
?>
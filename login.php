<?php
    require_once "auth/conn.php";
    require "auth/comp/vendor/autoload.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        include('auth/index.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Samiha Dates - Login</title>
    <meta charset="UTF-8">
    <meta name="description" content="GoGo Mushroom">
    <meta name="keywords" content="samihadates.com, SamihaDates, samiha dates, samihadates">
    <meta name="author" content="ansln">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/login.css">
</head>
<body>
    <div class="container">
        <div class="title">
            <a href="/shop"><img src="https://ik.imagekit.io/samiha/logo_hgGvqn6gn.png" alt="Samiha Dates"></a>
        </div>
        <div class="ct-center">
            <img class ="bg-pohon" src="https://ik.imagekit.io/uqffqxbo5/datestree_wKmAIB7P2.png" width="374px"> 
        <?php

            if ($_COOKIE['SMHSESS'] == ""){
                ?>
                <!-- LOGIN FORM -->
                <div class="title-login">
                    <h4>Login</h4>
                </div>
                <div class="title-daftar">
                    <h>Kamu belum punya akun? <a href="register.php">Daftar</a> di sini</h>
                </div>
                <?php
                        if(isset($_GET['err'])){
                            if($_GET['err'] == "blank"){
                                echo "<span>Email/Phone and Password must be field!</span>";
                            }if($_GET['err'] == "?"){
                                echo "<span>Incorrect Email/Phone or Password!</span>";
                            }if($_GET['err'] == "login"){
                                echo "<span>Please login to continue!</span>";
                            }if($_GET['err'] == ""){
                                echo "<span>Something went wrong!</span>";
                            }
                        }
                ?>
                <div class="ct-login">
                    <div class="login">
                    <form method="POST" action="">
                        <div class="column">
                            <input type="text" id="fname" name="email" autocomplete="off" placeholder="Email or Phone Number">
                            <input type="password" id="lname" name="password" autocomplete="off" placeholder="Password">
                        </div>
                            <button value="Login" type="submit" name="submit">Login</button>
                        </div>
                    </form>
                    <div class="note"><a href="reset-password/"><b>Lupa kata sandi?</b></a></div>
                    </div>
                </div>
            <?php
            }else{
                $decodeJWT = $_COOKIE['SMHSESS'];
                header("location:/shop/");
            }
    ?>
        <small>© 2022 | Samiha</small>
        </div>
    </div>
</body>
</html>
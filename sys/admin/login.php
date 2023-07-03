<?php

require_once 'auth/user/session.php';
require_once 'auth/user/loginV2.php';
require '../../auth/comp/vendor/autoload.php';

$session = new adminSession;
$loginAuth = new loginManagement;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $loginAuth->loginAuth($email, $password);
}

if (isset($_COOKIE["ADMSESS"])) {
    ?><script>window.location.replace('./');</script><?php
}else{
    ?><!DOCTYPE html>
        <html lang="en">
        <head>
            <title>Samiha - Admin</title>
            <meta charset="UTF-8">
            <meta name="description" content="Samiha">
            <meta name="author" content="ansln">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="style/login-adm.css">
        </head>
        <body>
            <div class="container">
                <div class="ct-wrapper">
                    <!-- LOGIN FORM -->
                    <div class="title-login">
                        <h4>Login Admin</h4>
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
                        <form method="POST">
                            <div class="wrap-login">
                                <input type="text" id="fname" name="email" autocomplete="off" placeholder="Email or Phone Number">
                                <input type="password" id="lname" name="password" autocomplete="off" placeholder="Password">
                                <button value="Login" type="submit" name="submit">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>        
        </body>
    </html>
    <?php
}
        

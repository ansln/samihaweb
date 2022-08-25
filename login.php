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
            <a href="/shop">Samiha Dates</a>
        </div>
        <div class="ct-center">
        <?php
            error_reporting(0);
            session_start();

            if($_SESSION['status']!="login"){
                ?>
                <!-- LOGIN FORM -->
                <div class="title-login">
                    <h4>Login</h4>
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
                    <form method="POST" action="auth/">
                        <div class="column">
                            <input type="text" id="fname" name="email" autocomplete="off" placeholder="Email or Phone Number">
                            <input type="password" id="lname" name="password" autocomplete="off" placeholder="Password">
                        </div>
                            <button value="Login" type="submit">Login</button>
                        </div>
                    </form>
                    <div class="note"><a href="reset-password/"><b>Lupa kata sandi?</b></a></div>
                    </div>
                </div>
            <?php
            }else{
                header("location:/shop/");
            }
        ?>
        </div>
    </div>        
</body>
</html>
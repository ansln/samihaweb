<?php
    require_once "auth/oe.php";
    
    $getAuth = new signupManagement;
    $getAuth->userCheck();
?>
<html>
    <head>
        <title>Samiha Dates - Register</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="js/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="style/register.css"><link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    </head>
    <body>
        <div class="container">
            <div class="reg-content">
                <a href="./"><img src="https://ik.imagekit.io/samiha/logo_hgGvqn6gn.png"></a>
                    <div class="reg-component">
                        <div class="right-text">
                            <h2>Daftar di Samiha</h2>
                            <h3>Kamu sudah punya akun? <a href="login.php">Masuk</a> di sini</h3>
                        </div>

                        <?php
                            if(isset($_GET['err'])){
                                if($_GET['err'] == "?"){
                                    ?>
                                        <div id="infoMsg">
                                            <span>Email/No. Telepon sudah terdaftar!</span>
                                        </div>
                                    <?php
                                }
                            }
                        ?>

                        <form action="" method="post" autocomplete="off">
                            <div class="reg-fill">
                                <div class="row2">
                                    <input type="text" name="fullName" required placeholder="Nama Lengkap" autocomplete="off">
                                </div>
                                <div class="row">
                                    <input type="text" name="username" required placeholder="Username" autocomplete="off">
                                    <input type="text" name="phone" required placeholder="No. Telepon" autocomplete="off">
                                </div>
                                <div class="row2">
                                    <input pattern="[A-Za-z0-9._+-]+@[A-Za-z0-9 -]+\.[a-z]{2,}" type="email" name="email" required placeholder="Email" autocomplete="off">
                                </div>
                                <div class="row2">
                                    <input title="Harus" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}" type="password" name="password" placeholder="Password" autocomplete="off">
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
                                    <input name="dateOfBirth" type="date" required>
                                </div>
                            </div>
                            <div class="row">
                                <button type="submit">Daftar</button>
                            </div>
                        </form>
                    </div>
            </div>
            <b>© 2022 | Samiha</b>
        </div>
        <script src="js/reg.js"></script>
    </body>
</html>
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $userFullName = $_POST['fullName'];
        $username = $_POST['username'];
        $userPhone = $_POST['phone'];
        $userEmail = $_POST['email'];
        $userPassword = $_POST['password'];
        $userGender = $_POST['getGenderSelect'];
        $userDOB = $_POST['dateOfBirth'];

        $getAuth->dataPost($userFullName, $username, $userPhone, $userEmail, $userPassword, $userGender, $userDOB);
    }
?>
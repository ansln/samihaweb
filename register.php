<?php
    require_once "auth/oe.php";
    
    $getAuth = new signupManagement;
    $getAuth->userCheck();

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REG</title>
    <link rel="stylesheet" href="style/register.css"><link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="https://kit.fontawesome.com/3f3c1cf592.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <div class="ct-wrapper">
            <div class="mt-content">
                <div class="mt-ct-wrapper">
                    <div class="reg-content">
                        <div class="title-sec">
                            <div id="title-img"><img src="https://ik.imagekit.io/samiha/logo_hgGvqn6gn.png"></div>
                            <div id="top-title">Daftar di Samiha</div>
                            <div id="top-sub-title">Kamu sudah punya akun? <div id="login-link">Masuk</div> di sini</div>
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
                        </div>
                        <div class="form-sec">
                            <form id="input-form" action="" method="post" autocomplete="off">
                                <div class="row">
                                    <label>Nama Lengkap</label>
                                    <input type="text" name="fullName" required placeholder="Nama Lengkap" autocomplete="off">
                                </div>
                                <div class="row">
                                    <label>Username</label>
                                    <input type="text" name="username" required placeholder="Username" autocomplete="off">
                                </div>
                                <div class="row">
                                    <label>No. Telepon</label>
                                    <input type="text" name="phone" required placeholder="No. Telepon" autocomplete="off">
                                </div>
                                <div class="row">
                                    <label>Email</label>
                                    <input pattern="[A-Za-z0-9._+-]+@[A-Za-z0-9 -]+\.[a-z]{2,}" type="email" name="email" required placeholder="Email" autocomplete="off">
                                </div>
                                <div class="row">
                                    <label>Password</label>
                                    <div id="password-input-box"><input id="user-password" name="password" type="password" required placeholder="Password"><div class="toggle-password"><i class="fa-solid fa-eye"></i><i class="fa-solid fa-eye-slash"></i></div></div>
                                    <div class="password-info">
                                        <div class="password-info-text">Memiliki 8 karakter<i class="fa-solid fa-check"></i></div>
                                        <div class="password-info-text">Memiliki angka<i class="fa-solid fa-check"></i></div>
                                        <div class="password-info-text">Memiliki huruf besar<i class="fa-solid fa-check"></i></div>
                                        <div class="password-info-text">Memiliki karakter spesial<i class="fa-solid fa-check"></i></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>Tanggal Lahir</label>
                                    <input name="dateOfBirth" type="date" required>
                                </div>
                                <div class="row">
                                    <label>Jenis Kelamin</label>
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
                                </div>
                                <button id="form-submit-btn" type="submit">Buat Account</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/reg.js"></script>
</body>
</html>
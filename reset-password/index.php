<?php date_default_timezone_set("Asia/Jakarta"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Samiha - Password Reset</title>
    <script src="../js/jquery-3.6.0.min.js"></script><script src='//cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <link rel="stylesheet" href="../style/res-index.css"><link rel="stylesheet" href="../style/cssImages.css">
</head>
<body>
    <div class="next"></div>
    <div class="rp-container">
        <div class="rp-ct-wrapper">
            <h1>Atur Ulang Kata Sandi</h1>
            <div class="ct-wrapper-box">
                <form enctype="multipart/form-data" id="form">
                    <p>Masukkan Email yang terdaftar. Kami akan mengirimkan instruksi untuk mengatur ulang kata sandi anda.</p>
                    <div class="msg"><span>Email tidak boleh kosong</span></div>
                    <div class="box-form-section">
                        <label>Email address</label>
                        <input type="email" placeholder="sam@email.com" name="userEmail" id="userEmail">
                        <button type="submit" class="submitBtn" name="submit">Kirim email</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../js/resPass.js"></script>
</body>
</html>
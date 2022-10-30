<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Samiha - Password Reset</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="../style/res-index.css">
</head>
<body>
    <?php 
    if (isset($_GET['sent'])){ ?>
        <div class="rp-sc-container">
            <div class="rp-sc-ct-wrapper">
                <img src="../assets/etc/email_sent_password.svg">
                <h1>Cek email anda</h1>
                <p>Kami telah mengirimkan instruksi pemulihan kata sandi ke email anda.</p>
                <button id="emailSentBack">Kembali</button>
                <small>Tidak menerima email? <a href="./">Klik untuk mengirim ulang email.</a></small>
            </div>
        </div>
        <script> const emailSentBackBtn = document.getElementById("emailSentBack"); emailSentBackBtn.addEventListener('click', backHome); function backHome() { window.location.replace("../"); }; </script>
    <?php
    }elseif (isset($_GET['success'])) { ?>
        <div class="rp-container-done">
            <div class="rp-ct-wrapper-done">
                <img src="../assets/etc/done.svg">
                <h1>Password berhasil diubah</h1>
                <p>Password kamu berhasil diubah. Silahkan Klik dibawah untuk login ulang.</p>
                <button id="doneHome">Lanjutkan</button>
                <small>Mengalami masalah? <a href="./">Hubungi support.</a></small>
            </div>
        </div>
        <script> const doneGoHome = document.getElementById("doneHome"); doneGoHome.addEventListener('click', doneBackHome); function doneBackHome() { window.location.replace("../"); }; </script>
    <?php }else{ header('Location: ../'); } ?>
</body>
</html>
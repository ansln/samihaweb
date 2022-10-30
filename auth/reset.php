<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<?php
require_once 'comp/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Firebase\JWT\JWT;

date_default_timezone_set("Asia/Jakarta");
if (isset($_POST['userEmail'])) {
    $userEmail = $_POST['userEmail'];
    
    generateToken($userEmail); //generate token and post to db
}else{
    header('Location: ../err.html');
}

function generateToken($userEmail){
    include 'conn.php';
    
    //secret key
    $firstKey = base64_encode('SAMIHAKEY');
    $randString = "hOpVrvtZTU";
    $endKey = "-password-reset-key";
    $secretKey = $firstKey . $randString . $endKey;
    
    //get time and rand session
    $sessRand = rand();
    date_default_timezone_set("Asia/Jakarta");
    $time = time();
    $sessionTime = strtotime("1 hour");
    
    $payload = [
        "resetSessionId" => $sessRand,
        "email" => $userEmail,
        "iat" => $time,
        "expiredTime" => $sessionTime
    ];
    
    $jwt = JWT::encode($payload, $secretKey, 'HS256');
    
    // send to db
    $fetchEmailData = $db->query("SELECT * FROM user WHERE u_email = '$userEmail'");
    $dataCheck = mysqli_num_rows($fetchEmailData);
    if ($dataCheck > 0) {
        if($fetchEmailData->num_rows){
            while($r = $fetchEmailData->fetch_object()){
                $userId = $r->id;
                $fetchEmail = $r->u_email;
                $link = "http://localhost/shop/reset-password/confirm?token=" . $jwt;
                $insertTokenDb = "INSERT INTO user_password_reset VALUES(NULL, '$userId', '', '', '', '$jwt')";
                mysqli_query($db, $insertTokenDb);
                getEmailStatus($fetchEmail, $link);
            }
        }
    }else{
        ?><script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: 'Email tidak terdaftar',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                showConfirmButton: false
            })
            setTimeout(function(){
                window.location.replace('./');
            }, 2000);
        </script><?php
    }
}

function sendUserEmail($userEmail, $userLink){
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'pijeee07@gmail.com';
        $mail->Password   = 'hhfzktggadvrtyuu';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
    
        //Recipients
        $mail->setFrom('support@jmuv.my.id', 'Samiha Support');
        $mail->addAddress($userEmail);
    
        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $mail->Body    = '<b>Halo,</b><br><p>Anda telah meminta mengatur ulang kata sandi untuk akun Samiha yang terkait dengan email ' . $userEmail . '</p><p>Untuk mengatur ulang kata sandi anda, silahkan klik link dibawah ini:</p><a href="' . $userLink . '">' . $userLink . '</a><br><p>Link dan token anda akan expired dalam waktu 1 jam setelah email ini terkirim, jika ingin mengatur ulang kata sandi lagi silahkan klik link dibawah ini:</p><a href="https:/google.com/">https:/google.com/</a><br><p><b>Jika kamu merasa tidak melakukan request ini, abaikan email ini.</b></p><p>Jika kamu butuh bantuan support silahkan hubungi support.</p><p>Terimakasih,</p><p>Samiha Team</p>';
    
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function getEmailStatus($userEmail, $userLink){
    include 'conn.php';
    $getUserEmail = $userEmail;
    $getUserLink = $userLink;
    $fetchEmailData = $db->query("SELECT * FROM user WHERE u_email = '$getUserEmail'");
    $dataCheck = mysqli_num_rows($fetchEmailData);
    
    if ($dataCheck > 0) {
        sendUserEmail($getUserEmail, $getUserLink);
        ?><script>window.location.replace("../reset-password/ver?sent");</script><?php
    }else{
        ?><script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: 'Email tidak terdaftar',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                showConfirmButton: false
            })
            setTimeout(function(){
                window.location.replace('./');
            }, 2000);
        </script><?php
    }
}
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include('PHPMailer/src/Exception.php');
include('PHPMailer/src/PHPMailer.php');
include('PHPMailer/src/SMTP.php');

require_once '../conn.php';

//error message
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

    if($_SESSION['status']!="login"){
        header("location: /shop/login.php?err=login");
    }if($_SESSION['status']=="login"){

        ?><body><div class="bck-btn"><button><a href="/shop/user">back</a></button></div><?php

        $emailVal = $db->real_escape_string($_SESSION['email']);
        $data = $db->query("SELECT * FROM user WHERE u_email='$emailVal'");

        if($data->num_rows){ // -> fetch user data
            while($tst = $data->fetch_object()){
                $userStatus = $tst->status;
                if ($userStatus == 1) {
                    header("location: /shop/user");
                }if ($userStatus == 0) {
//SEND VERIFICATION CODE TO USER
                    $verification_code = rand(100000,999999);
        
                    if(isset($_GET['email'])){
                        $userEmail = $db->real_escape_string($_GET['email']);
            
                        $query = $db->query("SELECT * FROM user WHERE u_email = '$userEmail'");
            
                        if($query->num_rows){ // -> fetch data
                            while($r = $query->fetch_object()){
            
                                $userId = $r->id;
            
                                    $ver = $db->query("UPDATE user SET email_verification_code = $verification_code WHERE id = $userId");
            
                                    //SEND EMAIL VALIDATION
                                    $mail = new PHPMailer(true);
                            
                                    try {
                                        // $mail->SMTPDebug = 2;
                                        $mail->isSMTP();
                                        // $mail->Host       = 'mail.jmuv.my.id';
                                        $mail->Host       = 'smtp.gmail.com';
                                        $mail->SMTPAuth   = true;
                                        $mail->Username   = 'pijeee07@gmail.com';
                                        $mail->Password   = 'hhfzktggadvrtyuu';
                                        // $mail->Username   = 'support@jmuv.my.id';
                                        // $mail->Password   = 'Antonius123.';
                                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                                        $mail->Port       = 465;
                            
                                        //Recipients
                                        $mail->setFrom('support@jmuv.my.id', 'Samiha Dates Support');
                                        $mail->addAddress($userEmail);
                            
                                        //Content
                                        $mail->isHTML(true);
                                        $mail->Subject = 'Email Verification';
                                        $mail->Body    = '<h4>Your verification code</h4>' . $verification_code;
                            
                                        $mail->send();
                                        header("location: ?to=next-ver");
                                    } catch (Exception $e) {
                                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                                    }
                            }
                        }else{
                            header("location: /shop"); // -> IF EMAIL UNKNOWN IN DATABASE
                        }
                    }
//END OF SEND VERIFICATION CODE TO USER
                }else{
                    header("location: /shop/user");
                }
            }
        }
// NEXT PAGE IF CODE SUCCESS SEND
        if(isset($_SESSION['email'])){ 
            $uData = $db->real_escape_string($_SESSION['email']);
            $uDataP = $db->real_escape_string($_SESSION['phone']);
            $u_fetch = $db->query("SELECT * FROM user WHERE u_email = '$uData' OR u_phone = '$uDataP'");

            if($u_fetch->num_rows){ // -> fetch data
                while($f = $u_fetch->fetch_object()){

                    $getCodeFromDb = $f->email_verification_code;

                    if(isset($_GET['to'])){//start
                        if($_GET['to'] == "next-ver"){
                            ?>
                                <div class="container">
                                    <div class="content">
                                        <div class="content-product">
                                            <div class="pd-card">
                                                <form action="" method="post" autocomplete="off">
                                                    <img src="http://localhost/shop/assets/img/emailver.png">
                                                    <h2>Verification code has been sent</h2>
                                                    <p>Check your email & use the verification code to verify your account.</p>
                                                    <div class="col">
                                                        <input type="text" name="ver-code">
                                                        <button class="btn-submit" type="submit" name="ver-submit-btn">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
            
                            date_default_timezone_set("Asia/Jakarta");
                            $time = date("h:i:sa");
                            $date = date("d M Y");
                            $_POST['date'] = $date;
                            $_POST['time'] = $time;
                            $dateTime = $date . " | " . $time;
            
                            if (isset($_POST["ver-submit-btn"])) {
                                $verCode = $_POST["ver-code"];

                                if ($verCode == $getCodeFromDb) {
                                    $updateStatus = $db->query("UPDATE user SET status = 1, email_verified_at='$dateTime' WHERE email_verification_code = $verCode");
            
                                    echo "<script>alert('success!');window.location='/shop/user';</script>";
                                    mysqli_query($db, $updateStatus);
                                    
                                }if ($verCode != $getCodeFromDb) {
                                    ?>
                                        <div class="pd-card">
                                            <span>Incorrect verification code!</span>
                                        </div>
                                    <?php
                                }else{
                                    ?>
                                        <div class="pd-card">
                                            <span>Something wrong!</span>
                                        </div>
                                    <?php
                                }
                            }
                        }else{
                            header("location: /shop/user");
                        }
                    }
                }
            }
        }
    }else{
        header("location: /shop");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Samiha Dates</title>
    <script src="../../js/jquery-3.6.0.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap');
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
            outline: none;
            font-family: 'Inter', sans-serif;
            text-decoration: none;
        }
        /* 1 */
        .container{
            width: 100%;
            display: flex;
            justify-content: center;
        }
        /* 2 */
        .content{
            padding-top: 25px;
            width: 1300px;
            display: grid;
        }
        /* 3 */
        .content-product{
            margin-top: 30px;
            flex-direction: column;
            display: flex;
            align-items: center;
        }
        /* 4 */
        .pd-card{
            display: flex;
            flex-wrap: wrap;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .pd-card h2{
            font-weight: 600;
        }
        .pd-card img{
            width: 200px;
            height: 200px;
            object-fit: cover;
        }
        .pd-card span{
            margin-top: 5px;
            padding: 5px;
            width: 25%;
            background-color: #FF5D5D;
            color: white;
            font-size: 14px;
            border-radius: 5px;
        }
        .col{
            margin: 15px;
            display: flex;
            flex-direction: column;
        }
        .col input{
            transition: 0.3s;
            padding: 5px;
            height: 30px;
            border: none;
            border:1px solid #DAB88B;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .col input:focus{
            transition: 0.5s;
            border: 1px solid #F3E9DD;
        }
        .col button{
            transition: 0.3s ease;
            padding: 5px;
            border-radius: 5px;
            height: 30px;
            border: none;
            cursor: pointer;
            background-color: #F3E9DD;
        }
        .col button:hover{
            transition: 0.5s;
            background-color: #E4D1B9;
        }
        .bck-btn{
            margin: 5px;
        }
        .bck-btn button{
            color: white;
            transition: 0.3s;
            padding: 5px;
            border-radius: 5px;
            height: 30px;
            border: none;
            cursor: pointer;
            background-color: #F3E9DD;
        }
        .bck-btn a{
            color: white;
        }
    </style>
</head>
</body>
</html>

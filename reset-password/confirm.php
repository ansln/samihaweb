<?php

require_once '../auth/conn.php';
require_once 'val/index.php';

$getVal = new resetPassword;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Samiha - Password Reset</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src='//cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <link rel="stylesheet" href="../style/res-index.css">
</head>
<body>
    <?php 
    if (isset($_GET['token'])) {

        $tokenPost = $_GET['token'];
    
        if ($tokenPost != "") {
    
            $data = mysqli_query($db, "SELECT * FROM user_password_reset WHERE userAccessToken = '$tokenPost'");
            $dataCheck = mysqli_num_rows($data);
    
            if ($dataCheck > 0) {
                $timeCheck = $getVal->checkExpiredTime($tokenPost); //check user time
                $getVal->checkExpiredSession($timeCheck); //post reset page
                $getVal->generateEmail();
            }else{
                echo "This is not a valid link.";
            }
        }else{
            echo "empty token";
        }
    }else{
        echo "false";
    } ?>
</body>
</html>
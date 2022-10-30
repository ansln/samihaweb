<?php

require_once "conn.php";
require_once "comp/vendor/autoload.php";

$get = new userSession;

if ($_COOKIE['SMHSESS'] == "") {
    header("location: ../");
}if (isset($_POST["recipientName"]) == "" && $_COOKIE['SMHSESS'] != "") {
    header("location: ../");
}else{
    $email = $get->generateEmail();

    $userQuery = $db->query("SELECT * FROM user WHERE u_email = '$email' OR u_phone = '$email'");

    if ($userQuery->num_rows) { // -> fetch user data
        while ($r = $userQuery->fetch_object()) {

        $userId = $r->id;
        $userRecipientName = $_POST["recipientName"];
        $userPhoneNumber = $_POST["phoneNumber"];
        $userAddressLabel = $_POST["addressLabel"];
        $userDistrictEtc = $_POST["addressCityEtc"];
        $userPostalCode = newSplitArr($userDistrictEtc);
        $userFullAddress = $_POST["fullAddress"];

            if ($userRecipientName != "") {
                $queryData = "INSERT INTO user_address VALUES(NULL, $userId, '$userRecipientName', '$userPhoneNumber', '$userAddressLabel', '', '', '', '', '', '$userDistrictEtc', '$userPostalCode', '$userFullAddress', 0, 'primary')";
                mysqli_query($db, $queryData);
                ?><script src='//cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script type="text/javascript">
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Address successfully added',
                        timer: 2000,
                        showConfirmButton: false
                    })
                    setTimeout(function(){
                        window.location = "./address";
                    }, 2000);
                </script><?php
            }else{
                ?><script src='//cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script type="text/javascript">
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: `Address can't be added`,
                        timer: 2000,
                        showConfirmButton: false
                    })
                    setTimeout(function(){
                        window.location = " ./address";
                    }, 2000);
                </script><?php
            }
        }
    }
}
function newSplitArr($address){
    $string = $address;
    $outputArr = preg_split("/[,\s.]/", $string);
    $getEndArr = end($outputArr);
    return $getEndArr;
}
?>
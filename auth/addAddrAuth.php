<?php

require_once "conn.php";
require_once "comp/vendor/autoload.php";
?><script src='//cdn.jsdelivr.net/npm/sweetalert2@11'></script><style> .swal2-popup { font-size: 14px; }</style><?php

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
                ?><script type="text/javascript">
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Alamat berhasil ditambahkan',
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        showConfirmButton: false
                    })
                    setTimeout(function(){
                        window.location = "./address";
                    }, 2000);
                </script><?php
            }else{
                ?><script type="text/javascript">
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Alamat tidak berhasil ditambahkan',
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        showConfirmButton: false
                    })
                    setTimeout(function(){
                        window.location = "./address";
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
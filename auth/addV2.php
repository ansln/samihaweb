<?php

require_once "conn2.php";
require_once "session.php";

?><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"><script src='//cdn.jsdelivr.net/npm/sweetalert2@11'></script><style> .swal2-popup { font-size: 14px; }</style><?php

class addressFunctionV2{

    private function getDb(){
        $get = new connection;
        $db = $get->getDb();
        return $db;
    }

    private function sanitize($value){
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    private function userStatus(){
        $db = $this->getDb();
        $session = new userSession;

        if ($_COOKIE['SMHSESS']) {
            //userTokenCheck
            $ck = $_COOKIE['SMHSESS'];
            $ck_ = $this->sanitize($ck);
            $cookie = $db->real_escape_string($ck);
            $userSession = mysqli_query($db, "SELECT * FROM user_session WHERE user_jwt='$cookie'");
            $userSessionCheck = mysqli_num_rows($userSession);
            
            if ($userSessionCheck > 0) {
                //fetch email from user
                $userEmail = $session->generateEmail();
                $userData = mysqli_query($db, "SELECT * FROM user WHERE u_email='$userEmail' OR u_phone='$userEmail'");
                $userDataCheck = mysqli_num_rows($userData);
                if ($userDataCheck > 0) {
                    return true;
                }else{ ?><script>window.location.replace("logout.php");</script><?php }
            }else{ ?><script>window.location.replace("logout.php");</script><?php }
        }else{ ?><script>window.location.replace("logout.php");</script><?php }
    }

    private function checkId($value){
        $db = $this->getDb();
        $getStatus = $this->userStatus();

        if ($getStatus !== true) {
            ?><script>window.location.replace("logout.php");</script><?php
        }else{
            $addressQuery = mysqli_query($db, "SELECT * FROM user_address WHERE userAddressUID='$value'");
            $queryCheck = mysqli_num_rows($addressQuery);

            if ($queryCheck < 0) {
                ?><script>window.location.replace("logout.php");</script><?php
            }else{
                return $this->setPrimaryAddress($value);
            }
        }
    }

    private function userId(){
        $db = $this->getDb();
        $session = new userSession;
        $email = $session->generateEmail();
        $emailS = $this->sanitize($email);
        $userEmail = $db->real_escape_string($emailS);
        $userData = mysqli_query($db, "SELECT * FROM user WHERE u_email='$userEmail' OR u_phone='$userEmail'");
        $userDataCheck = mysqli_num_rows($userData);

        if ($userDataCheck > 0) {
            if($userData->num_rows){
                while($r = $userData->fetch_object()){
                    $userId = $r->id;
                    return $userId;
                }
            }
        }else{
            ?><script>window.location.replace("logout.php");</script><?php
        }
    }
    
    private function setPrimaryAddress($value){
        $db = $this->getDb();
        $userId = $this->userId();
        error_reporting(0);

        $deleteOldPrimaryAddressQuery = $db->query("UPDATE user_address SET addressPrimary = ' ', u_defaultAddress = '0' WHERE userId = '$userId'");
        $updateNewPrimaryAddressQuery = $db->query("UPDATE user_address SET addressPrimary = 'primary', u_defaultAddress = '1' WHERE userId = '$userId' AND userAddressUID = '$value'");
        ?><script type="text/javascript">
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Alamat utama berhasil diubah',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                showConfirmButton: false
            })
            setTimeout(function(){
                window.location = "./address";
            }, 2000);
        </script><?php
        mysqli_query($db, $deleteOldPrimaryAddressQuery);
        mysqli_query($db, $updateNewPrimaryAddressQuery);
    }

    private function deleteAddressDb($value){
        $db = $this->getDb();
        $userId = $this->userId();
        $address = $this->sanitize($value);
        $userAddressId = $db->real_escape_string($address);

        $addressDeleteQuery = "DELETE FROM user_address WHERE userId = '$userId' AND userAddressUID = '$userAddressId'";
        ?><script type="text/javascript">
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Alamat berhasil dihapus',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                showConfirmButton: false
            })
            setTimeout(function(){
                window.location = "./address";
            }, 2000);
        </script><?php
        mysqli_query($db, $addressDeleteQuery);
    }

    private function changeAddressDb($value){
        ?><script type="text/javascript">
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: 'Fitur belum tersedia untuk saat ini',
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

    //setter getter
    public function updatePrimaryAddress($value){
        return $this->checkId($value);
    }

    public function deleteAddress($value){
        return $this->deleteAddressDb($value);
    }
    
    public function changeAddress($value){
        return $this->changeAddressDb($value);
    }
}

?>
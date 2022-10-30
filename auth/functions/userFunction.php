<?php

// require_once "../session.php";

class userEdit{

    private function getDb(){
        $get = new connection;
        $db = $get->getDb();
        return $db;
    }

    private function getEmail(){
        $get = new userSession;
        $email = $get->generateEmail();
        return $email;
    }

    private function changeName($value){
        error_reporting(0);
        $db = $this->getDb();
        $userEmail = $this->getEmail();
        $userFullName = $this->sanitize($value);
        $splitName = $this->splitName($userFullName);
        $firstName = $splitName[0];
        $lastName = $splitName[1];

        if ($_COOKIE['SMHSESS']) {
            //userTokenCheck
            $cookie = $_COOKIE['SMHSESS'];
            $userSession = mysqli_query($db, "SELECT * FROM user_session WHERE user_jwt='$cookie'");
            $userSessionCheck = mysqli_num_rows($userSession);
            
            if ($userSessionCheck > 0) {
                //fetch email from user
                $userData = mysqli_query($db, "SELECT * FROM user WHERE u_email='$userEmail' OR u_phone='$userEmail'");
                $userDataCheck = mysqli_num_rows($userData);
                if ($userDataCheck >= 1) {

                    $updateNameQuery = $db->query("UPDATE user SET u_fName = '$firstName', u_lName = '$lastName' WHERE u_email = '$userEmail'");
                    ?>
                    <script>
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Profile updated!',
                            showClass: {
                                popup: 'animate__animated animate__fadeInDown'
                            },
                            showConfirmButton: false
                        })
                        setTimeout(function(){
                            location.reload();
                        }, 2000);
                    </script>
                    <?php
                    mysqli_query($db, $updateNameQuery);

                }else{ ?><script>window.location.replace("../logout.php");</script><?php }
            }else{ ?><script>window.location.replace("../logout.php");</script><?php }
        }else{ ?><script>window.location.replace("../logout.php");</script><?php }

    }

    public function sanitize($value){
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    public function splitName($value){
        $string = $value;
        $arrayString = explode(" ", $string );
        return $arrayString;
    } 

    // setter getter
    public function editName($value){
        return $this->changeName($value);
    }
}

?>
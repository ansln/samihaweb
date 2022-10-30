<?php
error_reporting(0);

require_once '../auth/comp/vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class resetPassword{
    
    protected $secretKey;
    protected $decodeTime;
    protected $userEmail;
    private $db;

    private function callDb(){
        require_once '../auth/conn2.php';
        $get = new connection;
        $this->db = $get->getDb();
        return $this->db;
    }

    public function generateKey(){
        //secret key
        $firstKey = base64_encode('SAMIHAKEY');
        $randString = "hOpVrvtZTU";
        $endKey = "-password-reset-key";
        $secretKey = $firstKey . $randString . $endKey;
        $this->secretKey = $secretKey;
        return $secretKey;
    }

    public function decodeExpiredTime($userToken){
        //get secret key
        $get = new resetPassword;
        $secretKey = $get->generateKey();

        $decode = JWT::decode($userToken, new Key($secretKey, 'HS256'));
        $this->decodeTime = $decode->expiredTime;
        return $this->decodeTime;
    }

    public function checkExpiredTime($userToken){
        date_default_timezone_set("Asia/Jakarta");
        $now = time();
        $get = new resetPassword;
        $userTimeStamp = $get->decodeExpiredTime($userToken);

        if ($now >= $userTimeStamp) {
            return false; //sesi habis
        }else{
            return true; //sesi berjalan
        }
    }

    public function checkExpiredSession($userTime){
        if ($userTime == false) { //sesi habis
            ?>
            <div class="rp-container-err">
                <div class="rp-ct-wrapper-err">
                    <img src="../assets/etc/err.png">
                    <h1>Sesi Habis</h1>
                    <p>Sesi habis/tidak valid.</p>
                    <button id="goBackBtn">Kembali</button>
                </div>
            </div>
            <script>
                const goBackBtn = document.getElementById("goBackBtn");
                goBackBtn.addEventListener('click', backHome);

                function backHome() {
                    window.location.replace("../");
                };
            </script>
            <?php
        }else{ //sesi berjalan
            ?>
            <div class="rp-container-pwd">
                <div class="rp-ct-wrapper-pwd">
                    <h1>Buat password baru</h1>
                    <p>Password baru harus berbeda dari password yang dipakai sebelumnya.</p>
                    <div class="input-box-pwd">
                        <form method="POST" action="" autocomplete="off">
                            <div class="row">
                                <label>Password</label>
                                <input type="password" name="userPassword" placeholder="">
                                <small>Must be at least 8 characters</small>
                            </div>
                            <div class="row">
                                <label>Confirm Password</label>
                                <input type="password" name="userConfirmPassword" placeholder="">
                                <small>Must be at least 8 characters</small>
                            </div>
                            <button name="submit" type="submit">Ubah Password</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php
        }

        // reset validation
        if (isset($_POST['submit'])) {

            $userPassword = $_POST['userPassword'];
            $userConfirmPassword = $_POST['userConfirmPassword'];

            $get = new resetPassword;
            $get->updatePassword($userPassword, $userConfirmPassword);
        }
        
    }

    public function generateEmail(){
        //get secret key & user token
        $get = new resetPassword;
        $secretKey = $get->generateKey();
        $userToken = $_GET['token'];

        $decode = JWT::decode($userToken, new Key($secretKey, 'HS256'));
        $this->userEmail = $decode->email;
        return $this->userEmail;
    }

    private function updatePassword($userPassword, $userConfirmPassword){
        date_default_timezone_set("Asia/Jakarta");
        $userToken = $_GET['token'];
        $uPassword = $userPassword;
        $userPassword = md5($userPassword);
        $get = new resetPassword;
        $getUserEmail = $get->generateEmail();
        $getDb = $get->callDb();

        $dataFetch = $getDb->query("SELECT * FROM user WHERE u_email = '$getUserEmail'");

        if($dataFetch->num_rows){
            while($r = $dataFetch->fetch_object()){
                $userId = $r->id;
                $userOldPassword = $r->u_password;
                $dateTime = date("d-m-Y h:i:sa");

                $passDataToUserPassword = $getDb->query("UPDATE user_password_reset SET userOldPassword = '$userOldPassword', userNewPassword = '$userPassword', userPasswordChangeTime = '$dateTime', userAccessToken = 'change password done' WHERE userAccessToken = '$userToken'");
                $passDataToUser = $getDb->query("UPDATE user SET u_password = '$userPassword' WHERE id = '$userId'");
                ?><script>window.location.replace("ver?success");</script><?php
                mysqli_query($getDb, $passDataToUserPassword);
                mysqli_query($getDb, $passDataToUser);
            }
        }

    }
}

?>
<?php 
    session_start();
    require_once 'auth/conn.php';

    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    if(isset($_SESSION['email'])){ 
        $uData = $db->real_escape_string($_SESSION['email']); // -> get data user email from session
        $uDataP = $db->real_escape_string($_SESSION['phone']);

        $u_fetch = $db->query("SELECT * FROM user WHERE u_email LIKE '{$uData}' OR u_phone LIKE '{$uDataP}'"); // -> query for fetch all data from user logged in
        
        if($u_fetch->num_rows){ // -> fetch data
            while($r = $u_fetch->fetch_object()){

                $userId = $r->id;
                date_default_timezone_set("Asia/Jakarta");
                $time = date("h:i:sa");
                $date = date("d-m-Y");
                $_POST['date'] = $date;
                $_POST['time'] = $time;

                $updateLogoutData = $db->query("UPDATE user_log SET u_logoutTime = '$time', u_logoutDate = '$date' WHERE userId = '$userId' ORDER BY id DESC LIMIT 1");
                session_unset(); //UNSET USER SESSION
                session_destroy(); //DELETE USER SESSION
                header('Location: /shop'); //REDIRECT TO HOMEPAGE
                mysqli_query($db, $updateLogoutData); //INPUT DATA TO DATABASE
            }
        }
    }
?>
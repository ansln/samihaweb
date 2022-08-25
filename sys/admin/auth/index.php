<?php
session_start();

require_once "conn.php";

$email = $_POST["email"];
$password = $_POST["password"];

$data = mysqli_query($db, "SELECT * FROM admin WHERE adm_email='$email' AND adm_password='$password'");
$check = mysqli_num_rows($data);

if($check > 0){
	$_SESSION['email'] = $email;
	$_SESSION['status'] = "admin-login";
	header("location: /shop/sys/admin/");

	if(isset($_SESSION['email'])){ 
		$uData = $db->real_escape_string($_SESSION['email']); // -> get data user from email session/login

		$u_fetch = $db->query("SELECT * FROM admin WHERE adm_email LIKE '{$uData}'"); // -> query for fetch all data from user logged in
		
		if($u_fetch->num_rows){ // -> fetch data
			while($r = $u_fetch->fetch_object()){

				//IP LOG
				function getIPAddress() {  
					//whether ip is from the share internet  
						if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
						$ip = $_SERVER['HTTP_CLIENT_IP'];  
					}  
					//whether ip is from the proxy  
						elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
						$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
					}  
					//whether ip is from the remote address  
					else{  
						$ip = $_SERVER['REMOTE_ADDR'];  
					}  
					return $ip;  
				}

				// browser, device log
				$browser = $_SERVER['HTTP_USER_AGENT'];

				//LOG SYSTEM
				$userId = $r->id;
				date_default_timezone_set("Asia/Jakarta");
				$time = date("h:i:sa");
				$date = date("d-m-Y");
				$_POST['date'] = $date;
				$_POST['time'] = $time;
				$ip = getIPAddress();

			}
		}
	}
}if($check <= 0){
	header("location: /shop/");
}
?>
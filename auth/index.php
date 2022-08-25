<?php
session_start();

require_once "conn.php";

// LOGIN
	$email = $_POST["email"];
	$phone = $_POST["email"];
	$password = $_POST["password"];

	$data = mysqli_query($db, "SELECT * FROM user WHERE u_email='$email' AND u_password='$password' OR u_phone='$phone' AND u_password='$password'");
	$check = mysqli_num_rows($data);

	if($check > 0){
		$_SESSION['phone'] = $phone;
		$_SESSION['email'] = $email;
		$_SESSION['status'] = "login";
		header("location:/shop/");

		if(isset($_SESSION['email'])){ 
			$uData = $db->real_escape_string($_SESSION['email']); // -> get data user from email session/login
			$uDataP = $db->real_escape_string($_SESSION['phone']); // -> get data user from phone session/login

			$u_fetch = $db->query("SELECT * FROM user WHERE u_email LIKE '{$uData}' OR u_phone LIKE '{$uDataP}'"); // -> query for fetch all data from user logged in
			
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

					// Prepare the SQL Statements  to Insert User Login Time
					$insertLogin_SQL = "INSERT INTO user_log VALUES(NULL, '$userId', '$time', '$date', ' ', ' ', '$ip', '$browser')";
					mysqli_query($db, $insertLogin_SQL);

				}
			}
		}
	}if ($email == "" || $password == "") {
		header("location: /shop/login.php?err=blank");
	}else{
		header("location: /shop/login.php?err=?");
	}

?>
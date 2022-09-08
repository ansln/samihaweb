<?php

session_start();
require_once "conn.php";
include "../auth/functions/index.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    $fName = $_POST["firstName"];
    $lName = $_POST["lastName"];
    $username = $_POST["username"];
    $u_email = $_POST["email"];
    $u_dob = date('Y-m-d', strtotime($_POST['dateOfBirth']));
    $u_password = $_POST["password"];
    $pwd = sP($u_password);
    $u_phone = $_POST["phone"];
    $gender = $_POST["getGenderSelect"];

    $emailQuery = $db->query("SELECT * FROM user WHERE u_email = '$u_email'");
    $phoneQuery = $db->query("SELECT * FROM user WHERE u_phone = '$u_phone'");
    $emailCheck = mysqli_num_rows($emailQuery);
    $phoneCheck = mysqli_num_rows($phoneQuery);

    if ($gender != "Pria" && $gender != "Wanita") {
        header("location: ../register.php");
    }else{
        if ($gender == "Pria") {
            $gender = "Male";
        }if ($gender == "Wanita") {
            $gender = "Female";
        }
    }

    $userFirstName = sP($fName);
    $userLastName = sP($lName);
    $u_username = sP($username);
    $u_password = md5($u_password);

    if ($phoneCheck > 0) {
        header("location: /shop/register.php?err=?");
    }if ($emailCheck > 0) {
        header("location: /shop/register.php?err=?");
    }else{
        $query = "INSERT INTO user VALUES(NULL, '', '$userFirstName', '$userLastName', '$u_email', '$u_username', '$u_password', '$u_phone', '$gender', '$u_dob', ' ', ' ', 0)";

        mysqli_query($db, $query) or die($msg . mysqli_error($db));

        header("Location: auth/success.php");
    }

?>
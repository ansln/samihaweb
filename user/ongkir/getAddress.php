<?php
session_start();
require_once '../conn.php';

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $uData = $db->real_escape_string($_SESSION['email']);
    $uDataP = $db->real_escape_string($_SESSION['phone']);

    $userQuery = $db->query("SELECT * FROM user WHERE u_email = '$uData' OR u_phone = '$uDataP'");

    if($userQuery->num_rows){
        while($u_fetch = $userQuery->fetch_object()){

            $userId = $u_fetch->id;
            $userAddressQuery = $db->query("SELECT * FROM user_address WHERE userId = $userId");

            if($userAddressQuery->num_rows){
                while($uAd = $userAddressQuery->fetch_object()){

                    $prov_value = $uAd->u_provinceId;
                    $city_value = $uAd->u_cityId;

                    $findCity = curl_init();
                    curl_setopt_array($findCity, array(
                        CURLOPT_URL => "https://api.rajaongkir.com/starter/city?id=$city_value&province=$prov_value",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_HTTPHEADER => array(
                            "content-type: application/x-www-form-urlencoded",
                            "key: 6d109d749f2aa7448a61f21fe0b888f9"
                        )
                    ));
            
                    $loadFindCity = curl_exec($findCity);
            
                    curl_close($findCity);
                    $jsonDecodeCity = json_decode($loadFindCity, true);
                    $jsonDecodeCityName = $jsonDecodeCity["rajaongkir"]["results"]["city_name"];
                    $jsonDecodeProvince = $jsonDecodeCity["rajaongkir"]["results"]["province"];
                    $jsonDecodeCityType = $jsonDecodeCity["rajaongkir"]["results"]["type"];

                    echo $jsonDecodeProvince . ", " . $jsonDecodeCityName . ", " . $jsonDecodeCityType;
            
                }
            }
        }
    }

<?php

$prov_value = $_GET["province"];

//CURL CITY
$findCity = curl_init();
curl_setopt_array($findCity, array(
    CURLOPT_URL => "https://api.rajaongkir.com/starter/city?&province=$prov_value",
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
$jsonDecodeCity = $jsonDecodeCity["rajaongkir"]["results"];

?><option disabled selected>Pilih Kota</option><?php
foreach ($jsonDecodeCity as $row) {
    $cityId = $row["city_id"];
    $cityName = $row["city_name"];
    $cityType = $row["type"];
    
    ?><option id="optCty" name="city" value="<?=$cityId?>"><?=$cityName . ", " . $cityType?></option><?php
}
//END CURL
?>
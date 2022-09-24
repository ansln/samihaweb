<?php
//CURL CITY
$findProv = curl_init();
curl_setopt_array($findProv, array(
    CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "content-type: application/x-www-form-urlencoded",
        "key: 6d109d749f2aa7448a61f21fe0b888f9"
    )
));

$loadFindProv = curl_exec($findProv);

curl_close($findProv);
$jsonDecodeProv = json_decode($loadFindProv, true);
$jsonDecodeProv = $jsonDecodeProv["rajaongkir"]["results"];
//END CURL
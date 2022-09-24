<?php

// $destination_value = $getOrigin;

//CURL JNE
$ongkirJNE = curl_init();
curl_setopt_array($ongkirJNE, array(
    CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "origin=153&destination=114&weight=1000&courier=jne",
    CURLOPT_HTTPHEADER => array(
        "content-type: application/x-www-form-urlencoded",
        "key: 97d9ec3f87930edf68a56b9dd23adc9a"
    )
));

$loadOngkirDataJNE = curl_exec($ongkirJNE);

curl_close($ongkirJNE);
$jneDecode = json_decode($loadOngkirDataJNE, true);
$jneDecodeDestination = $jneDecode["rajaongkir"]["destination_details"]["city_name"];
$jneDecodeDestinationType = $jneDecode["rajaongkir"]["destination_details"]["type"];
$jneDecode = $jneDecode["rajaongkir"]["results"][0]["costs"];
//END

//CURL TIKI
$ongkirTIKI = curl_init();
curl_setopt_array($ongkirTIKI, array(
    CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "origin=151&destination=114&weight=1000&courier=tiki",
    CURLOPT_HTTPHEADER => array(
        "content-type: application/x-www-form-urlencoded",
        "key: 97d9ec3f87930edf68a56b9dd23adc9a"
    )
));

$loadOngkirDataTIKI = curl_exec($ongkirTIKI);

curl_close($ongkirTIKI);
$tikiDecode = json_decode($loadOngkirDataTIKI, true);
$tikiDecodeDestination = $tikiDecode["rajaongkir"]["destination_details"]["city_name"];
$tikiDecodeDestinationType = $tikiDecode["rajaongkir"]["destination_details"]["type"];
$tikiDecode = $tikiDecode["rajaongkir"]["results"][0]["costs"];
//END

//CURL POS INDONESIA
$ongkirPOS = curl_init();
curl_setopt_array($ongkirPOS, array(
    CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "origin=151&destination=114&weight=1000&courier=pos",
    CURLOPT_HTTPHEADER => array(
        "content-type: application/x-www-form-urlencoded",
        "key: 97d9ec3f87930edf68a56b9dd23adc9a"
    )
));

$loadOngkirDataPOS = curl_exec($ongkirPOS);

curl_close($ongkirPOS);
$posDecode = json_decode($loadOngkirDataPOS, true);
$posDecodeDestination = $posDecode["rajaongkir"]["destination_details"]["city_name"];
$posDecodeDestinationType = $posDecode["rajaongkir"]["destination_details"]["type"];
$posDecode = $posDecode["rajaongkir"]["results"][0]["costs"];
//END
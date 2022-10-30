<?php

// RANDOM FOR ORDER

// $a = 1;
// $b = 1;
// $c = 21242231;
// date_default_timezone_set("Asia/Jakarta");
// $date = date("d-m-Y");
// $date2 = date("dmY");

// echo "<b>ALTERNATIVE</b>";
// echo "<br>";
// while ($c <= 21242900) {
//     echo "SDO-";
//     echo $date2;
//     echo "-SMH";
//     echo "-" . $c++;
//     echo "<br>";
// }

// 
function generateRandomString($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

echo generateRandomString();

?>
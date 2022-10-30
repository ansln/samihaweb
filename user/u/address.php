<?php
$countries = "ID";
$type = "single";

if (isset($_POST['input'])) {
    
    $input = $_POST['input'];
    $endpoint = "https://api.biteship.com/v1/maps/areas?countries=$countries&input=$input&type=$type";

    $getAddress = curl_init();
    curl_setopt_array($getAddress, array(
        CURLOPT_URL => $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 20,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Content-Type:application/json", 
            "authorization: biteship_live.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoic2FtaWhhU2hpcHBpbmciLCJ1c2VySWQiOiI2MzM1MTI4MmY0ZWNmNDJlYTA4MzIyNjAiLCJpYXQiOjE2NjQ0MjI2Njl9.gq8_rpKLqFSRgQ6ATWQfny7zHpOU-Oymv3R27Aaug8U"
        )
    ));

    $loadAddress = curl_exec($getAddress);

    curl_close($getAddress);
    $curlDecode = json_decode($loadAddress, true);
    $decodeResult = $curlDecode["areas"];

    if ($input != "") {
        ?><script>var getData = [<?php foreach ($decodeResult as $row) { echo '"' . $row["name"] . '",'; } ?>];</script><?php
    }
}else{
    header('Location: https://google.com/');
}
?>
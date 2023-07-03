<?php

require_once '../auth/conn2.php';
require_once '../auth/session.php';

$getDb = new connection;
$user = new userSession;
$db = $getDb->getDb();
$email = $user->generateEmail();

$userData = mysqli_query($db, "SELECT * FROM user WHERE u_email = '$email'");

if (isset($_GET["pending"])) {
    foreach ($userData as $user) {
        $userId = $user["id"];
        $getInvoiceData = mysqli_query($db, "SELECT * FROM invoice WHERE userId = '$userId'");
    
        foreach ($getInvoiceData as $invoice) {
            $invoiceId = $invoice["invoiceId"];
    
            $url = "https://api.sandbox.midtrans.com/v2/SANDBOX-G710367688-806/status";
            $urlForStatusTx = "https://api.sandbox.midtrans.com/v2/$invoiceId/status";
            $str = "SB-Mid-server-fQVGpchXCGIDzpwLaLAbZTrb";
            $token = base64_encode($str);
    
            //CURL CITY
            $midtransCurl = curl_init();
            curl_setopt_array($midtransCurl, array(
                CURLOPT_URL => $urlForStatusTx,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Accept: application/json",
                    "Content-Type: application/json",
                    "Authorization: $token"
                )
            ));
    
            $loadCurl = curl_exec($midtransCurl);
    
            curl_close($midtransCurl);
            $curlDecode = json_decode($loadCurl, true);
            $curlEncode = json_encode($curlDecode, true);
    
            $transactionStatus = $curlDecode["transaction_status"];
    
            if ($transactionStatus == "pending") {
                echo "<br>Menunggu pembayaran:<br>";
                echo $curlEncode;
            }
        }
    }
}if (isset($_GET["success"])) {
    foreach ($userData as $user) {
        $userId = $user["id"];
        $getInvoiceData = mysqli_query($db, "SELECT * FROM invoice WHERE userId = '$userId'");
    
        foreach ($getInvoiceData as $invoice) {
            $invoiceId = $invoice["invoiceId"];
    
            $url = "https://api.sandbox.midtrans.com/v2/SANDBOX-G710367688-806/status";
            $urlForStatusTx = "https://api.sandbox.midtrans.com/v2/$invoiceId/status";
            $str = "SB-Mid-server-fQVGpchXCGIDzpwLaLAbZTrb";
            $token = base64_encode($str);
    
            //CURL CITY
            $midtransCurl = curl_init();
            curl_setopt_array($midtransCurl, array(
                CURLOPT_URL => $urlForStatusTx,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Accept: application/json",
                    "Content-Type: application/json",
                    "Authorization: $token"
                )
            ));
    
            $loadCurl = curl_exec($midtransCurl);
    
            curl_close($midtransCurl);
            $curlDecode = json_decode($loadCurl, true);
            $curlEncode = json_encode($curlDecode, true);
    
            $transactionStatus = $curlDecode["transaction_status"];

            if ($transactionStatus != "pending") {
                echo $curlEncode;
            }
        }
    }
}else{
    foreach ($userData as $user) {
        $userId = $user["id"];
        $getInvoiceData = mysqli_query($db, "SELECT * FROM invoice WHERE userId = '$userId'");
    
        foreach ($getInvoiceData as $invoice) {
            $invoiceId = $invoice["invoiceId"];
    
            $url = "https://api.sandbox.midtrans.com/v2/SANDBOX-G710367688-806/status";
            $urlForStatusTx = "https://api.sandbox.midtrans.com/v2/$invoiceId/status";
            $str = "SB-Mid-server-fQVGpchXCGIDzpwLaLAbZTrb";
            $token = base64_encode($str);
    
            //CURL CITY
            $midtransCurl = curl_init();
            curl_setopt_array($midtransCurl, array(
                CURLOPT_URL => $urlForStatusTx,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Accept: application/json",
                    "Content-Type: application/json",
                    "Authorization: $token"
                )
            ));
    
            $loadCurl = curl_exec($midtransCurl);
    
            curl_close($midtransCurl);
            $curlDecode = json_decode($loadCurl, true);
            $curlEncode = json_encode($curlDecode, true);
    
            $transactionStatus = $curlDecode["transaction_status"];
    
            if ($transactionStatus == "pending") {
                echo "<br>Menunggu pembayaran:<br>";
                echo $curlEncode;
            }
        }
    }
}



?>
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'comp/vendor/autoload.php';
// require_once '../cart/checkout/src/co.php';

// Set your Merchant Server Key
\Midtrans\Config::$serverKey = 'SB-Mid-server-fQVGpchXCGIDzpwLaLAbZTrb';
// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
\Midtrans\Config::$isProduction = false;
// Set sanitization on (default)
\Midtrans\Config::$isSanitized = true;
// Set 3DS transaction for credit card to true
\Midtrans\Config::$is3ds = true;

$transaction_details = array(
  'order_id' => rand(),
  'gross_amount' => 94000, // no decimal allowed for creditcard
);

$item_details = array(
  [
      'id' => 'a01',
      'price' => 35000,
      'quantity' => 1,
      'name' => 'Samiha Kurma Ajwa 500gr'
  ],
  [
      'id' => 'a01',
      'price' => 35000,
      'quantity' => 1,
      'name' => 'Samiha Kurma Khalas Saad 500gr'
  ]
);

// Optional
$billing_address = array(
  'first_name'    => "Andri",
  'last_name'     => "Litani",
  'address'       => "Mangga 20",
  'city'          => "Jakarta",
  'postal_code'   => "16602",
  'phone'         => "081122334455",
  'country_code'  => 'IDN'
);

// Optional
$shipping_address = array(
  'first_name'    => "Obet",
  'last_name'     => "Supriadi",
  'address'       => "Manggis 90",
  'city'          => "Jakarta",
  'postal_code'   => "16601",
  'phone'         => "08113366345",
  'country_code'  => 'IDN'
);

// Optional
$customer_details = array(
  'first_name'    => "Andri",
  'last_name'     => "Litani",
  'email'         => "pijeee07@gmail.com",
  'phone'         => "081122334455",
  'billing_address'  => $billing_address,
  'shipping_address' => $shipping_address
);

// Fill transaction details
$transaction = array(
  'transaction_details' => $transaction_details,
  'customer_details' => $customer_details,
  'item_details' => $item_details
);
 
$snapToken = \Midtrans\Snap::getSnapToken($transaction);

?>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-OMb7_WgNym9GWYX_"></script>
  </head>
  <body>
    <script type="text/javascript">
        window.snap.pay('<?= $snapToken ?>', {
            onSuccess: function(result){
            alert("payment success!"); console.log(result);
            },
            onPending: function(result){
            alert("wating your payment!"); console.log(result);
            },
            onError: function(result){
            alert("payment failed!"); console.log(result);
            },
            onClose: function(){
            alert('you closed the popup without finishing the payment');
            }
        });
    </script>
  </body>
</html>
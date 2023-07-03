<?php

require_once '../auth/tx.php';

$get = new transactionManagement;

if (isset($_GET["inv"])) {

    $invoiceId = $_GET["inv"];
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Order</title>
        <link rel="stylesheet" href="../style/editorder.css">
    </head>
    <body>
        <div class="container">
            <div class="ct-wrapper">   
                <?php $get->editOrder($invoiceId); ?>
            </div>
        </div>
    </body>
    </html>
    <?php
}

?>
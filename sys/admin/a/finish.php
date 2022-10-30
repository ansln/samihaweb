<?php

require '../auth/conn.php';

$query = $db->query("SELECT * FROM product WHERE pd_rand = 'b6HjQR'");

if ($query->num_rows) {
    while ($r = $query->fetch_object()) {

        $stock = $r->pd_stock;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>test</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap');
        
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
            font-family: 'Inter', sans-serif;
        }
        .ct-low{
            margin: 25px;
        }
        button{
            position: relative;
            border: none;
            background-color: transparent;
        }
        button img{
            width: 24px;
            height: 24px;
            object-fit: cover;
        }
        button::before{
            content: attr(cart-total);
            position: absolute;
            top: -0.75rem;
            right: -0.75rem;
            width: 1.5em;
            height: 1.5em;
            font-size: 12px;
            font-weight: 600;
            background-color: red;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div class="ct-low">
        <button cart-total="<?= $stock ?>"><img src="../../../assets/img/cart_ico.png"></button>
    </div>
</body>
</html>

<?php
    }
}
?>
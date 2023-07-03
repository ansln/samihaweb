<?php

require_once "../auth/conn2.php";

$get = new connection;
$db = $get->getDb();

$userData = mysqli_query($db, "SELECT * FROM cart WHERE userId = '2'");
$qty = 0;

foreach ($userData as $user) {
    $productQty = $user["qty"];
    $qty+=$productQty;
}

?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap');

    ::selection {
        color: white;
        background: #ECB365;
    }
    ::-webkit-scrollbar-track{
        border-radius: 10px;
    }

    ::-webkit-scrollbar{
        width: 10px;
    }

    ::-webkit-scrollbar-thumb{
        border-radius: 10px;
        background-color: var(--card-color);
    }

    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        scroll-behavior: smooth;
        color: #F7F7F7;
        font-family: 'Inter', sans-serif;
    }
    body {
        overflow-x: hidden;
        background-color: #181820;
        padding: 20px;
    }
    button{
        position: relative;
        border: none;
        background-color: transparent;
    }
    button::before{
        content: attr(data-count);
        position: absolute;
        top: -0.75em;
        left: 0.75em;
        width: 1em;
        height: 1em;
        padding: 1px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        background-color: red;
        color: #F7F7F7;
    }
    button i{
        font-size: 16px;
        color: #FFB648;
    }
</style>
<script src="https://kit.fontawesome.com/3f3c1cf592.js" crossorigin="anonymous"></script>
<button data-count="<?= $qty ?>"><i class="fa-solid fa-cart-shopping"></i></button>

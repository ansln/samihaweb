<?php
    require_once '../auth/user/conn.php';
    $getDb = new connection;
    $db = $getDb->getDb();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="../style/product.css">
</head>
<body>
    <div class="container-product">
    <?php
    //fetch all product
        $showProduct = $db->query("SELECT * FROM product ORDER BY pd_id DESC");
        if($showProduct->num_rows){
            while($r = $showProduct->fetch_object()){
                    $productIdRand = $r->pd_rand;
                    $productImg = $r->pd_img;
                    $productName = $r->pd_name;
                    $productCategory = $r->pd_category;
                    $productStatus = $r->status;
                ?>
                <a href="edit/index?id=<?= $productIdRand ?>">
                <div class="card">
                    <div class="card-left">
                        <img src="<?= $productImg ?>">
                    </div>
                    <div class="card-right">
                        <div class="txt-left">
                            <h1><?= $productName ?></h1>
                            <div class="child-left">
                                <p>Terakhir diedit | 20 Jun 2022 - 10:20 PM</p>
                                <?php
                                    if ($productStatus == 0) {
                                        echo "<span class='d'>disabled</span>";
                                    }if ($productStatus == 1) {
                                        echo "<span class='e'>enabled</span>";
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="txt-right">
                            <span>C/E:</span>
                            <h3>ansln</h3>
                        </div>
                    </div>
                </div>
                </a>
                <?php
            }
        }
    ?>
    </div>
</body>
</html>
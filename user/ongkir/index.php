<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COBA OPTIONS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Save Address</h1>
    <form action="" method="post" autocomplete="off">
        <div class="container">

            <div class="province">
                <label>Pilih Provinsi</label>
                <select name="province" id="province" onchange="province_select(this.value);">
                <option disabled selected>--Pilih Provinsi--</option>
                    <?php
                    error_reporting(0);
                    include_once ('curl_prov.php'); 
                        foreach ($jsonDecodeProv as $row) {
                            $provinceId = $row["province_id"];
                            $provinceName = $row["province"];
                            
                            ?><option value="<?=$provinceId?>"><?=$provinceName?></option><?php
                        }
                    ?>
                </select>
            </div>
        
            <div class="city">
                <div id="poll">
                    <div class="title">
                        <label>Pilih Kota</label>
                    </div>
                    <select name="selectCity" id="selectCity">
                        <option disabled selected>--Pilih Kota--</option>
                    </select>
                </div>
            </div>

            <button type="submit" name="submit">save location</button>
        </div>
    </form>
    <script src="app.js"></script>

<?php
    if(isset($_POST["submit"])){
        $prov= $_POST["province"];
        $cty = $_POST["selectCity"];
        
        ?>
        <form action="?getongkir" method="post" autocomplete="off">
            <input type="hidden" value="<?= $cty ?>" name="cityInput">
            <button type="submit" name="ongkir-btn">cek ongkir</button>
        </form>
        <?php
    }

    if(isset($_GET["getongkir"])){
        $getOrigin = $_POST["cityInput"];

        if($getOrigin == ""){
            header("location: index.php");
        }else{
            //REDIRECT TO ANOTHER PAGE

            //END REDIRECT
            include "curl_delivery.php";

            //SHOW ONGKIR
            echo "Tujuan: " . $jneDecodeDestination . ", " .$jneDecodeDestinationType;
            echo "<br>";

            echo "Jasa Layanan Ongkir: <b>JNE</b>";
            echo "<br>";
            foreach ($jneDecode as $row) {
                $service = $row["service"];
                $desc = $row["description"];
                $price = $row["cost"][0]["value"];
                $est = $row["cost"][0]["etd"];
                
                echo $service . " (" . $desc . ")";
                echo "<br>";
                echo "Harga: " . $price;
                echo "<br>";
                echo "Estimasi Pengiriman: " . $est . " hari";
                ?><br><?php
            }
        }
    }
?>
</body>
</html>


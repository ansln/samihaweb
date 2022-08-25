<?php
    require_once '../auth/conn.php';
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MULTI UPLOAD</title>
    <link rel="stylesheet" href="../style/input.css">
</head>
<body>
    <div class="ct-top-upload">
        <form action="../auth/pd-input.php" method="post" enctype="multipart/form-data">
            <div class="ct-upload">
                <!-- IMAGE UPLOAD -->
                <input type="file" name="img1" id="image_input1" accept="image/png, image/jpg, image/jpeg">
                <input type="file" name="img2" id="image_input2" accept="image/png, image/jpg, image/jpeg">
                <input type="file" name="img3" id="image_input3" accept="image/png, image/jpg, image/jpeg">
                <input type="file" name="img4" id="image_input4" accept="image/png, image/jpg, image/jpeg">
                <input type="file" name="img5" id="image_input5" accept="image/png, image/jpg, image/jpeg">
                
                <div class="wrap">
                    <div id="display_image1">
                        <label id="upload-icon1" for="image_input1"><img class="up-icon" src="http://localhost/shop/assets/img/adm-upload-icon.png"></label>       
                    </div>
                    <b>Gambar utama</b>
                </div>
                <div class="wrap">
                    <div id="display_image2">
                        <label id="upload-icon2" for="image_input2"><img class="up-icon" src="http://localhost/shop/assets/img/adm-upload-icon.png"></label>
                    </div>
                    <b>Gambar 2</b>
                </div>
                <div class="wrap">
                    <div id="display_image3">
                        <label id="upload-icon3" for="image_input3"><img class="up-icon" src="http://localhost/shop/assets/img/adm-upload-icon.png"></label>
                    </div>
                    <b>Gambar 3</b>
                </div>
                <div class="wrap">
                    <div id="display_image4">
                        <label id="upload-icon4" for="image_input4"><img class="up-icon" src="http://localhost/shop/assets/img/adm-upload-icon.png"></label>
                    </div>
                    <b>Gambar 4</b>
                </div>
                <div class="wrap">
                    <div id="display_image5">
                        <label id="upload-icon5" for="image_input5"><img class="up-icon" src="http://localhost/shop/assets/img/adm-upload-icon.png"></label>
                    </div>
                    <b>Gambar 5</b>
                </div>
            </div>
            <div class="text-inpt">
                <!-- NAME, DESC, ETC -->
                <div class="input">
                    <label>product name</label>
                    <input type="text" name="pd_name" required value="">
                </div>
    
                <div class="input">
                    <label>product price</label>
                    <input type="text" name="pd_price" required value="">
                </div>
    
                <div class="input">
                    <label>product stock</label>
                    <input type="text" name="pd_stock" required value="">
                </div>
    
                <div class="input">
                    <label>product weight</label>
                    <input type="text" name="pd_weight" required value="">
                </div>
    
                <div class="input">
                    <label>product desc</label>
                    <input type="text" name="pd_desc" required value="">
                </div>
    
                <div class="input">
                    <label>product category</label>
                    <input type="text" name="pd_category" required value="">
                </div>
            </div>
            <button type="submit" name="submit" value="upload">submit</button>
        </form>
        <a href="../index.php"><button>cancel</button></a>
    </div>
    <script src="../js/image-input.js"></script>
</body>
</html>
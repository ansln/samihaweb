<?php
    require_once '../auth/user/conn.php';
    require_once '../auth/functions.php';
    $getDb = new connection;
 
    $db = $getDb->getDb();

    if (!isset($_COOKIE["ADMSESS"])) {
        ?><script>window.location.replace('../logout');</script><?php
    }else{ ?> <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Samiha - Input</title>
            <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
            <link rel="stylesheet" href="../style/input.css">
        </head>
        <body>
            <div class="container">
                <div class="ct-wrapper">
                    <div class="container-input">
                        <div class="title-ct-input">
                            <h4>Input Produk</h4>
                        </div>
                        <form action="../auth/pd-input.php" method="post" enctype="multipart/form-data">
                        <!-- image upload -->
                        <div class="ct-upload">
                            <div id="title-img-up">Foto Produk</div>
                            <div class="ct-image">
                                <input type="file" name="img1" id="image_input1" accept="image/png, image/jpg, image/jpeg">
                                <input type="file" name="img2" id="image_input2" accept="image/png, image/jpg, image/jpeg">
                                <input type="file" name="img3" id="image_input3" accept="image/png, image/jpg, image/jpeg">
                                
                                <div class="wrap">
                                    <div id="display_image1">
                                        <label id="upload-icon1" for="image_input1"><i class="uil uil-image-plus"></i></label>       
                                    </div>
                                    <b>Gambar utama</b>
                                </div>
                                <div class="wrap">
                                    <div id="display_image2">
                                        <label id="upload-icon2" for="image_input2"><i class="uil uil-image-plus"></i></label>
                                    </div>
                                    <b>Gambar 2</b>
                                </div>
                                <div class="wrap">
                                    <div id="display_image3">
                                        <label id="upload-icon3" for="image_input3"><i class="uil uil-image-plus"></i></label>
                                    </div>
                                    <b>Gambar 3</b>
                                </div>
                            </div>
                        </div>
                        <!-- product input -->
                        <div class="product-input-ct">
                                <div class="cl-comp">
                                    <b>Nama Produk</b>
                                    <input type="text" name="product_name_input" value="">
                                </div>
                                
                                <div class="cl-comp">
                                    <b>Harga Produk</b>
                                    <div class="row">
                                        <b>Rp</b><input type="text" name="product_price_input" value="">
                                    </div>
                                </div>
                                
                                <div class="cl-comp">
                                    <b>Stok Produk</b>
                                    <input type="text" name="product_stock_input" value="">
                                </div>
                
                                <div class="cl-comp">
                                    <b>Berat Produk <small>(gram)</small></b>
                                    <input type="text" name="product_weight_input" value="">
                                </div>
        
                                <div class="cl-comp">
                                    <b>Lebar Produk <small>(cm)</small></b>
                                    <input type="text" name="product_width_input" value="">
                                </div>
        
                                <div class="cl-comp">
                                    <b>Tinggi Produk <small>(cm)</small></b>
                                    <input type="text" name="product_height_input" value="">
                                </div>
                
                                <div class="cl-comp">
                                    <b>Kategori Produk</b>
                                    <input type="text" name="product_category_input" value="">
                                </div>
                
                                <div class="cl-comp-desc">
                                    <b>Deskripsi Produk</b>
                                    <textarea name="product_desc_input" id="editor"></textarea>
                                </div>
                
                                <div class="cl-comp">
                                    <b>Status Produk</b>
                                    <select name="product_status_input" id="status-select">
                                        <option name="select-active" value="1" selected>Enable</option>
                                        <option name="select-deactive" value="0">Disable</option>
                                    </select>
                                </div>
                
                                <div class="form-btn">
                                    <button id="submitBtn" name="input_product">Input Produk</button>
                            </form>
                                <div class="batal">
                                    <form action="../"><button>Batal</button></form>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <script src="../js/image-input.js"></script>
            <script src="https://cdn.ckeditor.com/ckeditor5/35.3.2/classic/ckeditor.js"></script>
            <script>
                ClassicEditor.create( document.querySelector( '#editor' )).catch( error => { console.error(error); });
            </script>
        </body>
        </html>
<?php }
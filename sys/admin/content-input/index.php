<?php
    require_once '../auth/user/conn.php';
    require '../../../auth/comp/vendor/autoload.php';
    $getDb = new connection;
 
    $db = $getDb->getDb();

?><!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Samiha - Input</title>
        <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
        <link rel="stylesheet" href="../style/content-input.css">
    </head>
    <body>
        <div class="container">
            <div class="ct-wrapper">
                <div class="container-input">
                    <div class="title-ct-input">
                        <h4>Tambah Artikel</h4>
                    </div>
                    <form action="../auth/ac-input.php" method="post" enctype="multipart/form-data">
                    <!-- image upload -->
                    <div class="ct-upload">
                        <div id="title-img-up">Thumbnail/Foto Artikel</div>
                        <div class="ct-image">
                            <input type="file" name="img1" id="image_input1" accept="image/png, image/jpg, image/jpeg">
                            
                            <div class="wrap">
                                <div id="display_image1">
                                    <label id="upload-icon1" for="image_input1"><i class="uil uil-image-plus"></i></label>       
                                </div>
                                <b>Gambar utama</b>
                            </div>
                        </div>
                    </div>
                    <!-- product input -->
                    <div class="product-input-ct">
                            <div class="cl-comp">
                                <b>Judul Artikel</b>
                                <input type="text" name="article_title" value="">
                            </div>
            
                            <div class="cl-comp-desc">
                                <b>Isi Konten</b>
                                <textarea name="article_content" id="editor"></textarea>
                            </div>

                            <div class="form-btn">
                                <button id="submitBtn" type="submit" name="submit">Tambah Artikel</button>
                        </form>
                            <div class="batal">
                                <form action="../"><button>Batal</button></form>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../js/article-input.js"></script>
        <script src="https://cdn.ckeditor.com/ckeditor5/35.3.2/classic/ckeditor.js"></script>
        <script>
            ClassicEditor.create( document.querySelector( '#editor' )).catch( error => { console.error(error); });
        </script>
    </body>
    </html>
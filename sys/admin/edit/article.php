<?php

require_once '../auth/user/conn.php';
require_once '../auth/functions.php';
require '../../../auth/comp/vendor/autoload.php';
$getDb = new connection;

$db = $getDb->getDb();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET["id"])) {
    if (empty($_GET["id"])) { echo "there is nothing to do"; }else{

        $getId = $_GET["id"];
        $sanitize = $db->real_escape_string($getId);
        $idClear = sp($sanitize);

        $articleEditQuery = $db->query("SELECT * FROM article WHERE id = '$idClear'");
        $articleEditQueryCheck = mysqli_num_rows($articleEditQuery);

        if ($articleEditQueryCheck <= 0) {
            echo "artikel tidak ditemukan";
        }else{

            foreach ($articleEditQuery as $article) {

                $articleTitle = $article["article_title"];
                $articleThumb = $article["article_thumb"];
                $articleContent = $article["article_content"];
                $articleLink = $article["article_link"];
    
                ?><!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Samiha - Edit Artikel</title>
                    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
                    <link rel="stylesheet" href="../style/content-edit.css">
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"><script src='//cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                </head>
                <body>
                    <div class="container">
                        <div class="ct-wrapper">
                            <div class="container-input">
                                <div class="title-ct-input">
                                    <h4>Edit Artikel <a href="/article/view?title=<?= $articleLink ?> "><?= $articleTitle ?> </a></h4>
                                </div>
                                <form action="../auth/ac-edit.php" method="post" enctype="multipart/form-data">
                                <!-- image upload -->
                                <div class="ct-upload">
                                    <div id="title-img-up">Thumbnail/Foto Artikel</div>
                                    <div class="image-row">
                                        <div class="current-thumb-wrapper">
                                            <span>Foto artikel saat ini</span>
                                            <div id="current-thumb"><img src="<?= $articleThumb ?>"></div>
                                        </div>
                                        <div class="edit-thumb-wrapper">
                                            <span>Ubah foto artikel</span>
                                            <div class="ct-image">
                                                <input type="file" name="img1" id="image_input1" accept="image/png, image/jpg, image/jpeg">
                                                
                                                <div class="wrap">
                                                    <div id="display_image1">
                                                        <label id="upload-icon1" for="image_input1"><i class="uil uil-image-plus"></i></label>       
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- product input -->
                                <div class="product-input-ct">
                                        <div class="cl-comp">
                                            <b>Ubah Judul Artikel</b>
                                            <input type="hidden" name="article_id" value="<?= $idClear ?>">
                                            <input type="text" name="article_title" value="<?= $articleTitle ?>">
                                        </div>
                        
                                        <div class="cl-comp-desc">
                                            <b>Isi Konten</b>
                                            <textarea name="article_content" id="editor"><?= $articleContent ?></textarea>
                                        </div>
    
                                        <div class="form-btn">
                                            <button id="submitBtn" type="submit" name="article_update">Ubah Artikel</button>
                                    </form>
                                        <div class="batal">
                                            <form action="../"><button>Batal</button></form>
                                        </div>
                                    </div>
                                    <div class="delBtnWrapper">
                                        <form action="" method="post"><input type="hidden" value="<?= $getId ?>" name="article_id"><button name="deleteArticle" id="delBtnArticle">Hapus Artikel</button></form>
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
                </html> <?php }
        }
    }
}else{
    echo "null";
}

if (isset($_POST["deleteArticle"])) {
    $getId = $db->real_escape_string($_POST["article_id"]);
    $articleDeleteQuery = "DELETE FROM article WHERE id = '$getId'";
    $articleInfoDeleteQuery = "DELETE FROM article_info WHERE article_id = '$getId'";
    mysqli_query($db, $articleDeleteQuery);
    mysqli_query($db, $articleInfoDeleteQuery);
    ?><script>
        Swal.fire({
            toast: true,
            position: 'top',
            icon: 'success',
            title: 'Artikel berhasil dihapus',
            showClass: {
                        popup: 'animate__animated animate__fadeInDown'
            },
            showConfirmButton: false
        })
        setTimeout(function(){
            window.location = "../";
        }, 2000);
        </script>
    <?php
}

?>
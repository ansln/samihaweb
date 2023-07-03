<?php

ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

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
    <link rel="stylesheet" href="../style/article.css"><script src="../../js/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container-product">
        <button id="add-article"><i class="fa-solid fa-plus"></i> Tambah Artikel</button>
        <div class="content-edit-wrapper">
            <?php
                $articleQuery = $db->query("SELECT * FROM article ORDER BY id DESC");

                foreach ($articleQuery as $article) {

                    $articleId = $article["id"];
                    $articleThumb = $article["article_thumb"];
                    $articleTitle = $article["article_title"];
                    $articleDate = $article["article_date"];

                    $articleInfoQuery = $db->query("SELECT * FROM article_info WHERE article_id = '$articleId' ORDER BY id ASC");
                    $articleInfoQueryCheck = mysqli_num_rows($articleInfoQuery);

                    ?><a href="edit/article?id=<?= $articleId ?>">
                    <div class="card">
                        <div class="card-left">
                            <img src="<?= $articleThumb ?>">
                        </div>
                        <div class="card-right">
                            <div class="txt-left">
                                <h1><?= $articleTitle ?></h1>
                                <div class="child-left">
                                    <?php
                                    foreach ($articleInfoQuery as $articleInfo) {
                                        if ($articleInfoQueryCheck <= 0) {
                                            ?><p>Terakhir diedit | <?= $articleDate ?></p><?php
                                        }else{
                                            $articleLastEdit = $articleInfo["last_update"];
                                            $editor = $articleInfo["editor_name"];
                                            ?><p>Terakhir diedit | <?= $articleLastEdit ?></p><?php
                                        }
                                        ?>
                                </div>
                            </div>
                            <div class="txt-right">
                                <span>C/E:</span>
                                <h3><?= $editor ?></h3>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                    </a><?php
                }
            ?>
        </div>
    </div>
    <script>
        $('#add-article').on('click', function(e){
            e.preventDefault();
            
            window.location.replace('content-input/index.php');
        });
    </script>
</body>
</html>
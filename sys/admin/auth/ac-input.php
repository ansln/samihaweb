<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'user/conn.php';
require_once 'user/session.php';
require_once 'functions.php';
require '../../../auth/comp/vendor/autoload.php';

use ImageKit\ImageKit;

$getDb = new connection;
$getUser = new adminSession;
$ImgKitAPI = new getImageKit;
$db = $getDb->getDb();
$adminId = $getUser->generateId();

$adminInfoQuery = $db->query("SELECT * FROM admin WHERE adm_id = '$adminId'");
$lastArticleIdQuery = $db->query("SELECT * FROM article ORDER BY id DESC LIMIT 1");
$adminInfoQueryCheck = mysqli_num_rows($adminInfoQuery);

if ($adminInfoQueryCheck <= 0) {
    ?><script>window.location.replace('../');</script><?php
}else{
    // FOR IMAGE KIT
    $url_end_point = "https://upload.imagekit.io/api/v1/files/upload";
    $url_link_path = "https://ik.imagekit.io/samiha/";
    $public_key = $ImgKitAPI->getPublicKey();
    $imageKit_private_key = $ImgKitAPI->getPrivateKey();
    $imageKit = new ImageKit($public_key, $imageKit_private_key, $url_end_point);

    if(isset($_POST["submit"]) && isset($_FILES["img1"])){

        $articleId = 0;

        //for article info
        foreach ($lastArticleIdQuery as $article) {
            $getArticleId = $article["id"];
            $articleId = $getArticleId+1; 
        }

        //name, desc, etc
        date_default_timezone_set("Asia/Jakarta");
        $time = date("d m Y - h:i:sa");
        $article_title = $db->real_escape_string($_POST["article_title"]);
        $article_content = $db->real_escape_string($_POST["article_content"]);
        $nl = strtolower($article_title);
        $article_link = str_replace(' ', '-', $nl);

        //image
        $img1 = $_FILES["img1"];

        $tmp_name1 = $_FILES["img1"]["tmp_name"];

        if($img1['error'] === 0){
            if($img1['size'] > 5000000){
                echo "files to large :(";
            }else{

                //fetch image name with extension file
                $imgEx1 = pathinfo($img1['name'], PATHINFO_EXTENSION);
                $imgExLower1 = strtolower($imgEx1);

                $allowEx = array("jpg", "jpeg", "png"); //allowed extension

                // upload image to storage
                if(in_array($imgExLower1, $allowEx) || in_array($imgExLower2, $allowEx) || in_array($imgExLower3, $allowEx)){
                    // create unique name for new uploaded image
                    $newImageName1 = uniqid("SD-IMG-", true).'.'. $imgExLower1; //image 1 -> this variable add to database
                    $imgUploadPath1 = 'uploads/'.$newImageName1;
                    move_uploaded_file($tmp_name1, $imgUploadPath1);
                    
                    //when the user uploads a new item, the image will be checked first, after that the item data will be checked
                    //image check
                    $imageUID = imageGenerate();

                    if ($imgExLower1 != ""){

                        //upload to imagekit
                        $uploadFile1 = $imageKit->uploadFile([
                            "file" => fopen(__DIR__."/uploads/$newImageName1", "r"),
                            "fileName" => $newImageName1
                        ]);
                        $result1 = $uploadFile1->result->name;
                        $imgLink1 = $url_link_path . $result1;
                    }

                    $firstProductImg = $url_link_path . $result1;

                    //data check and upload to database
                    $articleUploadQuery = "INSERT INTO article VALUES(NULL, '$article_title', '$firstProductImg', '$article_content', '$article_link', '$time', 0)";
                    mysqli_query($db, $articleUploadQuery);

                    foreach ($adminInfoQuery as $admin) {
                        $editorName = $admin["adm_username"];
                        $articleInfoUploadQuery = "INSERT INTO article_info VALUES(NULL, '$articleId', '$editorName', '$editorName', ' ')";
                        mysqli_query($db, $articleInfoUploadQuery);
                    }

                    ?><script>window.location.replace("../index.php");</script><?php
                }else{
                    echo "file type is not allowed!";
                }
            }
        }else{
            echo "oops!";
        }
    }else{
        echo "if you see this message, contact your web developer immediately!";
    }
}

class getImageKit{

    private function publicKey(){
        $public_key = "public_55OM4ao8P0dX0Cca4058hWWoUzU=";
        return $public_key;
    }
    
    private function imageKitPrivateKey(){
        $imageKit_private_key = "private_0mCjBvpn0QnAA1gNmE3s3Nlx5XM=";
        return $imageKit_private_key;
    }

    public function getPublicKey(){
        return $this->publicKey();
    }
    public function getPrivateKey(){
        return $this->imageKitPrivateKey();
    }  
}

?>
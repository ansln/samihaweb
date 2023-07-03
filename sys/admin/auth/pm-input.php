<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'user/conn.php';
require_once 'functions.php';
require '../../../auth/comp/vendor/autoload.php';

use ImageKit\ImageKit;

$getDb = new connection;
$ImgKitAPI = new getImageKit;
$db = $getDb->getDb();

// FOR IMAGE KIT
$url_end_point = "https://upload.imagekit.io/api/v1/files/upload";
$url_link_path = "https://ik.imagekit.io/samiha/";
$public_key = $ImgKitAPI->getPublicKey();
$imageKit_private_key = $ImgKitAPI->getPrivateKey();
$imageKit = new ImageKit($public_key, $imageKit_private_key, $url_end_point);

if(isset($_POST["submit"]) && isset($_FILES["img1"])){

    //name, desc, etc
    $promotion_link = $db->real_escape_string($_POST["promotion_link"]);

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
                $promotionBannerUpload = "INSERT INTO dashboard VALUES(NULL, 'home', 'promotion-banner', 'image', '$firstProductImg', '$promotion_link')";
                mysqli_query($db, $promotionBannerUpload);

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
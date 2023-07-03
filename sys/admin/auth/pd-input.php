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
$product = new product;
$lastProductId = $product->getLastProductId();
$db = $getDb->getDb();

// FOR IMAGE KIT
$url_end_point = "https://upload.imagekit.io/api/v1/files/upload";
$url_link_path = "https://ik.imagekit.io/samiha/";
$public_key = $ImgKitAPI->getPublicKey();
$imageKit_private_key = $ImgKitAPI->getPrivateKey();
$imageKit = new ImageKit($public_key, $imageKit_private_key, $url_end_point);

if(isset($_POST["input_product"]) && isset($_FILES["img1"]) && isset($_FILES["img2"]) && isset($_FILES["img3"])){

    //name, desc, etc
    $productId = $lastProductId;
    $pd_name = $db->real_escape_string($_POST["product_name_input"]);
    $pd_price = $db->real_escape_string($_POST["product_price_input"]);
    $pd_stock = $db->real_escape_string($_POST["product_stock_input"]);
    $pd_weight = $db->real_escape_string($_POST["product_weight_input"]);
    $pd_width = $db->real_escape_string($_POST["product_width_input"]);
    $pd_height = $db->real_escape_string($_POST["product_height_input"]);
    $pd_desc = $_POST["product_desc_edit"];
    $pd_category = $db->real_escape_string($_POST["product_category_input"]);
    $nl = strtolower($pd_name);
    $pd_link = str_replace(' ', '-', $nl);

    //image
    $img1 = $_FILES["img1"];
    $img2 = $_FILES["img2"];
    $img3 = $_FILES["img3"];

    $tmp_name1 = $_FILES["img1"]["tmp_name"];
    $tmp_name2 = $_FILES["img2"]["tmp_name"];
    $tmp_name3 = $_FILES["img3"]["tmp_name"];

    if($img1['error'] === 0){
        if($img1['size'] > 5000000){
            echo "files to large :(";
        }else{

            //fetch image name with extension file
            $imgEx1 = pathinfo($img1['name'], PATHINFO_EXTENSION);
            $imgExLower1 = strtolower($imgEx1);
            $imgEx2 = pathinfo($img2['name'], PATHINFO_EXTENSION);
            $imgExLower2 = strtolower($imgEx2);
            $imgEx3 = pathinfo($img3['name'], PATHINFO_EXTENSION);
            $imgExLower3 = strtolower($imgEx3);

            $allowEx = array("jpg", "jpeg", "png"); //allowed extension

            // upload image to storage
            if(in_array($imgExLower1, $allowEx) || in_array($imgExLower2, $allowEx) || in_array($imgExLower3, $allowEx)){
                // create unique name for new uploaded image
                $newImageName1 = uniqid("SD-IMG-", true).'.'. $imgExLower1; //image 1 -> this variable add to database
                $imgUploadPath1 = 'uploads/'.$newImageName1;
                move_uploaded_file($tmp_name1, $imgUploadPath1);

                $newImageName2 = uniqid("SD-IMG-", true).'.'. $imgExLower2; //image 2 -> this variable add to database
                $imgUploadPath2 = 'uploads/'.$newImageName2;
                move_uploaded_file($tmp_name2, $imgUploadPath2);

                $newImageName3 = uniqid("SD-IMG-", true).'.'. $imgExLower3; //image 3 -> this variable add to database
                $imgUploadPath3 = 'uploads/'.$newImageName3;
                move_uploaded_file($tmp_name3, $imgUploadPath3);
                
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
                    $pic1 = "INSERT INTO product_image VALUES(NULL, '$imageUID', '$imgLink1')";
                    mysqli_query($db, $pic1);

                }if ($imgExLower2 != ""){

                    //upload to imagekit
                    $uploadFile2 = $imageKit->uploadFile([
                        "file" => fopen(__DIR__."/uploads/$newImageName2", "r"),
                        "fileName" => $newImageName2
                    ]);
                    $result2 = $uploadFile2->result->name;
                    $imgLink2 = $url_link_path . $result2;
                    $pic2 = "INSERT INTO product_image VALUES(NULL, '$imageUID', '$imgLink2')";
                    mysqli_query($db, $pic2);

                }if ($imgExLower3 != ""){

                    //upload to imagekit
                    $uploadFile3 = $imageKit->uploadFile([
                        "file" => fopen(__DIR__."/uploads/$newImageName3", "r"),
                        "fileName" => $newImageName3
                    ]);
                    $result3 = $uploadFile3->result->name;
                    $imgLink3 = $url_link_path . $result3;
                    $pic3 = "INSERT INTO product_image VALUES(NULL, '$imageUID', '$imgLink3')";
                    mysqli_query($db, $pic3);

                }if ($imgExLower1 == ""){
                    $newImageName1 = "";
                }if ($imgExLower2 == "") {
                    $newImageName2 = "";
                }if ($imgExLower3 == "") {
                    $newImageName3 = "";
                }

                $firstProductImg = $url_link_path . $result1;

                //data check and upload to database
                $productUID = productRand();
                $productUploadQuery = "INSERT INTO product VALUES(NULL, '$firstProductImg', '$imageUID', '$pd_name', $pd_price, $pd_stock, $pd_weight, '$pd_desc', '$pd_category', 1, '$productUID', '$pd_link')";
                $productDetailUploadQuery = "INSERT INTO product_detail VALUES(NULL, '$productId', '$pd_weight', '$pd_width', '$pd_height')";
                mysqli_query($db, $productUploadQuery);
                mysqli_query($db, $productDetailUploadQuery);

                echo "product successfully added!";
                ?><a href="../index.php">back</a><?php
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
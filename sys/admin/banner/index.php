<?php

require_once '../auth/user/conn.php';
require '../../../auth/comp/vendor/autoload.php';
require '../auth/banner-edit.php';
require '../auth/functions.php';

use ImageKit\ImageKit;
$getDb = new connection;
$getBanner = new bannerEdit;
$ImgKitAPI = new getImageKit;
$url_end_point = "https://upload.imagekit.io/api/v1/files/upload";
$url_link_path = "https://ik.imagekit.io/samiha/";
$public_key = $ImgKitAPI->getPublicKey();
$imageKit_private_key = $ImgKitAPI->getPrivateKey();
$imageKit = new ImageKit($public_key, $imageKit_private_key, $url_end_point);

$db = $getDb->getDb();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Banner</title>
    <link rel="stylesheet" href="../style/banner-edit.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://kit.fontawesome.com/3f3c1cf592.js" crossorigin="anonymous"></script>
    <script src='//cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
    <div class="container">
        <div class="ct-wrapper">
            <div id="backBtnHome">
                <i class="fa-solid fa-angle-left"></i>
                <a href="../">Kembali Menu Utama</a>
            </div>
            <?php $getBanner->dahsboardData(); ?>
        </div>
    </div>

    <?php
    
    if(isset($_GET["edit"])){
        $getId = $_GET["edit"];
        $getBanner->changeData($getId);

        if(isset($_POST["banner_update"]) && isset($_FILES["img1"])){

            $secId = $_POST["sec-id"];
            $link = $_POST["link"];
    
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
                        $updateDataQuery = "UPDATE dashboard SET url = '$firstProductImg', link = '$link' WHERE id = '$secId'";
                        mysqli_query($db, $updateDataQuery);
                        ?><script>
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Berhasil diubah',
                                showClass: {
                                    popup: 'animate__fadeInDown'
                                },
                                showConfirmButton: false
                            })
                            setTimeout(function(){
                                window.location = "../";
                            }, 2000);
                        </script><?php
                    }else{
                        echo "file type is not allowed!";
                    }
                }
            }else{
                echo "oops!";
            }
        }if(isset($_POST["banner_delete"])){
            $secId = $_POST["sec-id"];

            $deleteBannerQuery = "DELETE FROM dashboard WHERE id = $secId";
            mysqli_query($db, $deleteBannerQuery);
            ?><script>
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Data dihapus',
                    showClass: {
                        popup: 'animate__fadeInDown'
                    },
                    showConfirmButton: false
                })
                setTimeout(function(){
                    window.location = "../";
                }, 2000);
            </script><?php
        }
    
    }

    ?>
    <script>
        const image_input1 = document.querySelector("#image_input1");
        var uploaded_image = "";

        image_input1.addEventListener("change", function(){
            const reader = new FileReader();
            reader.addEventListener("load", () => {
                uploaded_image = reader.result;
                document.querySelector("#display_image1").style.backgroundImage = `url(${uploaded_image})`
                document.querySelector("#display_image1").style.border = "none";
                document.querySelector("#upload-icon1").style.display = "none";
            });
            reader.readAsDataURL(this.files[0]);
        });
    </script>
</body>
</html>
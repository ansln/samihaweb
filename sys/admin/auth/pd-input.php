<?php

session_start();

require_once 'conn.php';
require 'functions.php';

if(isset($_POST["submit"]) && isset($_FILES["img1"]) && isset($_FILES["img2"]) && isset($_FILES["img3"]) && isset($_FILES["img4"]) && isset($_FILES["img5"])){

    //name, desc, etc
    $pd_name = $_POST["pd_name"];
    $pd_price = $_POST["pd_price"];
    $pd_stock = $_POST["pd_stock"];
    $pd_weight = $_POST["pd_weight"];
    $desc = $_POST["pd_desc"];
    $pd_desc = sP($desc);
    $pd_category = $_POST["pd_category"];
    $nl = strtolower($pd_name);
    $pd_link = str_replace(' ', '-', $nl);

    //image
    $img1 = $_FILES["img1"];
    $img2 = $_FILES["img2"];
    $img3 = $_FILES["img3"];
    $img4 = $_FILES["img4"];
    $img5 = $_FILES["img5"];

    $tmp_name1 = $_FILES["img1"]["tmp_name"];
    $tmp_name2 = $_FILES["img2"]["tmp_name"];
    $tmp_name3 = $_FILES["img3"]["tmp_name"];
    $tmp_name4 = $_FILES["img4"]["tmp_name"];
    $tmp_name5 = $_FILES["img5"]["tmp_name"];

    if($img1['error'] === 0){ // if no error while upload

        if($img1['size'] > 12500000){ //check if image size too large
            echo "file kegedean bro!";
        }else{ //check if image size isn't too large and upload other file 

            //fetch image name with extension file
            $imgEx1 = pathinfo($img1['name'], PATHINFO_EXTENSION);
            $imgExLower1 = strtolower($imgEx1);
            $imgEx2 = pathinfo($img2['name'], PATHINFO_EXTENSION);
            $imgExLower2 = strtolower($imgEx2);
            $imgEx3 = pathinfo($img3['name'], PATHINFO_EXTENSION);
            $imgExLower3 = strtolower($imgEx3);
            $imgEx4 = pathinfo($img4['name'], PATHINFO_EXTENSION);
            $imgExLower4 = strtolower($imgEx4);
            $imgEx5 = pathinfo($img5['name'], PATHINFO_EXTENSION);
            $imgExLower5 = strtolower($imgEx5);

            $allowEx = array("jpg", "jpeg", "png"); //allowed extension

            // upload image to storage
            if(in_array($imgExLower1, $allowEx) || in_array($imgExLower2, $allowEx) || in_array($imgExLower3, $allowEx) || in_array($imgExLower4, $allowEx) || in_array($imgExLower5, $allowEx)){
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

                $newImageName4 = uniqid("SD-IMG-", true).'.'. $imgExLower4; //image 4 -> this variable add to database
                $imgUploadPath4 = 'uploads/'.$newImageName4;
                move_uploaded_file($tmp_name4, $imgUploadPath4);

                $newImageName5 = uniqid("SD-IMG-", true).'.'. $imgExLower5; //image 5 -> this variable add to database
                $imgUploadPath5 = 'uploads/'.$newImageName5;
                move_uploaded_file($tmp_name5, $imgUploadPath5);

                $addDirImage1 = "/shop/sys/admin/auth/uploads/".$newImageName1;
                $addDirImage2 = "/shop/sys/admin/auth/uploads/".$newImageName2;
                $addDirImage3 = "/shop/sys/admin/auth/uploads/".$newImageName3;
                $addDirImage4 = "/shop/sys/admin/auth/uploads/".$newImageName4;
                $addDirImage5 = "/shop/sys/admin/auth/uploads/".$newImageName5;
                
                //when the user uploads a new item, the image will be checked first, after that the item data will be checked
                //image check
                $imageUID = imageGenerate(); //get image id from function
                //check if image 2,3,4,5 doesn't exist
                if ($imgExLower1 != ""){
                    $pic1 = "INSERT INTO product_image VALUES(NULL, '$imageUID', '$newImageName1')";
                    mysqli_query($db, $pic1) or die(mysqli_error($db));
                }if ($imgExLower2 != ""){
                    $pic2 = "INSERT INTO product_image VALUES(NULL, '$imageUID', '$newImageName2')";
                    mysqli_query($db, $pic2) or die(mysqli_error($db));
                }if ($imgExLower3 != ""){
                    $pic3 = "INSERT INTO product_image VALUES(NULL, '$imageUID', '$newImageName3')";
                    mysqli_query($db, $pic3) or die(mysqli_error($db));
                }if ($imgExLower4 != ""){
                    $pic4 = "INSERT INTO product_image VALUES(NULL, '$imageUID', '$newImageName4')";
                    mysqli_query($db, $pic4) or die(mysqli_error($db));
                }if ($imgExLower5 != ""){
                    $pic5 = "INSERT INTO product_image VALUES(NULL, '$imageUID', '$newImageName5')";
                    mysqli_query($db, $pic5) or die(mysqli_error($db));
                }if ($imgExLower1 == ""){
                    $newImageName1 = "";
                }if ($imgExLower2 == "") {
                    $newImageName2 = "";
                }if ($imgExLower3 == "") {
                    $newImageName3 = "";
                }if ($imgExLower4 == "") {
                    $newImageName4 = "";
                }if ($imgExLower5 == "") {
                    $newImageName5 = "";
                }

                $firstProductImg = "/shop/sys/admin/auth/uploads/" . $newImageName1;

                //data check and upload to database
                $productUID = productRand();
                $productUploadQuery = "INSERT INTO product VALUES(NULL, '$firstProductImg', '$imageUID', '$pd_name', $pd_price, $pd_stock, $pd_weight, '$pd_desc', '$pd_category', 1, '$productUID', '$pd_link')";
                mysqli_query($db, $productUploadQuery) or die(mysqli_error($db));

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

?>
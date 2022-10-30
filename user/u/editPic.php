<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOADING</title>
    <link rel="stylesheet" href="../style/cssImages.css"><link rel="stylesheet" href="../style/cssImgUpload.css"><link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <script src="../js/jquery-3.6.0.min.js"></script><script src="https://kit.fontawesome.com/3f3c1cf592.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="loader-container">
        <span class="loader"></span>
    </div>
    
    <div class="profilePic-ct">
        <div class="ct-top-upload">
            <div class="cancelBtn">
                <a href=""><i class="fa-solid fa-xmark"></i></a>
            </div>
            <form enctype="multipart/form-data" id="form">
                
                <div class="image"></div>
                
                <div class="ct-upload">
                    <input type="file" name="file" id="file" accept="image/png, image/jpg, image/jpeg">
                    <div class="wrap">
                        <div id="display_image1">
                            <label id="upload-icon1" for="file"><i class="uil uil-image-upload"></i></label>       
                        </div>
                        <b>Maksimum 5.000.000 bytes (5 Megabytes). Ekstensi file yang diperbolehkan: .JPG .JPEG .PNG</b>
                    </div>
                </div>
                <button type="submit" name="file" class="editPicBtn" value="submit">SUBMIT</button>
            </form>
        </div>    
    </div>
    <script src="../js/edit-pic.js"></script>
    <script src="../js/image-input.js"></script>
</body>
</html>
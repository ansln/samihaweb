<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<?php

require_once '../../auth/conn.php';
require_once '../../auth/comp/vendor/autoload.php';
require_once '../../auth/session.php';

use ImageKit\ImageKit;

//PRIMARY
$public_key = "public_55OM4ao8P0dX0Cca4058hWWoUzU=";
$imageKit_private_key = "private_0mCjBvpn0QnAA1gNmE3s3Nlx5XM=";
$url_end_point = "https://upload.imagekit.io/api/v1/files/upload";
$url_link_path = "https://ik.imagekit.io/samiha/";

//BACKUP
// $public_key = "public_RjP4vmY0js3ER0HHZAsWpuR+IjY=";
// $imageKit_private_key = "private_lwormzWECSKrH8tEGcxUz/DIGLg=";
// $url_link_path = "https://ik.imagekit.io/uqffqxbo5/";

$imageKit = new ImageKit($public_key, $imageKit_private_key, $url_end_point);

$get = new userSession;
$email = $get->generateEmail();

$data = mysqli_query($db, "SELECT * FROM user WHERE u_email = '$email' OR u_phone = '$email'");

if($data->num_rows){
    while($r = $data->fetch_object()){

        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $ex = pathinfo($file['name'], PATHINFO_EXTENSION);
            $exLower = strtolower($ex);
            $temp = $_FILES['file']['tmp_name'];
            $location = "tmp_img/";

            if($file['error'] === 0){
                if($file['size'] > 1000000){
                    echo "files to large :(";
                }else{
                    $rand = rand(1000, 9999);
                    $finalFile = uniqid($rand, true).'.'. $exLower;
                    move_uploaded_file($temp,$location.$finalFile);

                    $uploadFile = $imageKit->uploadFile([
                        "file" => fopen(__DIR__."/tmp_img/$finalFile", "r"),
                        "fileName" => $finalFile
                    ]);
                
                    $result = $uploadFile->result->name;
                    $imgLink = $url_link_path . $result;

                    $updateQuery = "UPDATE user SET u_profilePict = '$imgLink' WHERE u_email = '$email' OR u_phone = '$email'";
                    mysqli_query($db, $updateQuery);
                    ?>
                    <script>
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Profile updated!',
                            showClass: {
                                popup: 'animate__animated animate__fadeInDown'
                            },
                            showConfirmButton: false
                        })
                        setTimeout(function(){
                            window.location.replace('./');
                        }, 2000);
                    </script>
                    <?php
                }
            }
// end of image file validation
        }else{
            header('Location: ../');
        }
//end of image file check
    }
}
//end of user fetch
?>
<?php
class bannerEdit{

    private function getDb(){
        $get = new connection;
        $db = $get->getDb();
        return $db;
    }

    private function sanitize($value){
        $db = $this->getDb();
        $getSanitize = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        $dbSanitize = $db->real_escape_string($getSanitize);
        return $dbSanitize;
    }

    private function allDashboardData(){
        $db = $this->getDb();
        $allData = array();
        $getBannerData = mysqli_query($db, "SELECT * FROM dashboard");
        foreach ($getBannerData as $data) {
            $elementId = $data["id"];
            $elementName = $data["element_name"];
            $elementUrl = $data["url"];
            $imageLink = $data["link"];

            $productJSON = array(
                'id' => $elementId,
                'category' => $elementName,
                'img_url' => $elementUrl,
                'link' => $imageLink
            );

            array_push($allData, $productJSON);
        }
        return $allData;
    }

    private function editBanner(){
        $db = $this->getDb();
        $dashboardData = $this->allDashboardData();
        ?><div class="dashboard-ct"><?php
        foreach ($dashboardData as $data) {
            $elementId = $data["id"];
            $elementName = $data["category"];
            $elementUrl = $data["img_url"];

            $search = '-' ;
            $trimmed = str_replace($search, ' ', $elementName);
            $element_name = strtoupper($trimmed);

            ?><a href="?edit=<?= $elementId ?>">
                <div class="db-card">
                        <div id="image-dashboard"><img src="<?= $elementUrl ?>"></div>
                        <div id="category-dashboard"><?= $element_name ?></div>
                    </div>
                </a><?php
        }
        ?></div><?php
    }

    private function editFunction($id){
        $db = $this->getDb();
        $idSanitize = $this->sanitize($id);
        $getBannerData = mysqli_query($db, "SELECT * FROM dashboard WHERE id = '$idSanitize'");
        foreach ($getBannerData as $data) {
            $elementName = $data["element_name"];
            $elementUrl = $data["url"];
            $imageLink = $data["link"];

            $search = '-' ;
            $trimmed = str_replace($search, ' ', $elementName);
            $element_name = strtoupper($trimmed);
            

            ?>
            <div class="edit-db-style">
                <div class="edit-db-style-wrapper">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div id="title-db">Edit <?= $element_name ?></div>
                        <div id="image-db-edit"><img src="<?= $elementUrl ?>"></div>
                        <div class="edit-thumb-wrapper">
                            <span>Ubah foto</span>
                            <div class="ct-image">
                                <input type="file" name="img1" id="image_input1" accept="image/png, image/jpg, image/jpeg">
                                
                                <div class="wrap">
                                    <div id="display_image1">
                                        <label id="upload-icon1" for="image_input1"><i class="uil uil-image-plus"></i></label>       
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="title-ubah-link">Ubah Link</div>
                        <input type="hidden" value="<?= $idSanitize ?>" name="sec-id">
                        <input type="text" name="link" id="name-link" value="<?= $imageLink ?>">
                        <div id="submit-btn-sec">
                            <button id="submitBtn" type="submit" name="banner_update">Ubah Data</button>
                        </div>
                        <div id="del-btn-sec">
                            <button id="deleteBtn" type="submit" name="banner_delete">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php
        }
    }

    public function dahsboardData(){
        return $this->editBanner();
    }

    public function changeData($id){
        return $this->editFunction($id);
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
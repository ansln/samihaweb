<?php
    require_once '../auth/user/conn.php';
    require_once '../auth/functions.php';
    $getDb = new connection;
    $db = $getDb->getDb();

    if(isset($_GET['id'])){
        $keywords = $db->real_escape_string($_GET['id']);
        $query = $db->query("SELECT * FROM product WHERE pd_rand = '$keywords'");

        if($query->num_rows){
            while($r = $query->fetch_object()){
                $productIdRand = $r->pd_rand;
                $productName = $r->pd_name;
                $productPrice = $r->pd_price;
                $productStock = $r->pd_stock;
                $productWeight = $r->pd_weight;
                $productDesc = $r->pd_desc;
                $productCategory = $r->pd_category;
                $productStatus = $r->status;
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit - <?= $productName ?></title>
    <link rel="stylesheet" href="../style/edit.css">
</head>
<body>
    <div class="ct-edit">
        <form action="" method="post" enctype="multipart/form-data">
                <div class="title-ct-edit">
                    <h4>Informasi Produk</h4>
                </div>

                <div class="cl-comp">
                    <b>Nama Produk</b>
                    <input type="text" name="product_name_edit" value="<?= $productName ?>">
                </div>
                
                <div class="cl-comp">
                    <b>Harga Produk</b>
                    <div class="row">
                        <b>Rp</b><input type="text" name="product_price_edit" value="<?= $productPrice ?>">
                    </div>
                </div>
                
                <div class="cl-comp">
                    <b>Stok Produk</b>
                    <input type="text" name="product_stock_edit" value="<?= $productStock ?>">
                </div>

                <div class="cl-comp">
                    <b>Berat Produk</b>
                    <input type="text" name="product_weight_edit" value="<?= $productWeight ?>">
                </div>

                <div class="cl-comp">
                    <b>Kategori Produk</b>
                    <input type="text" name="product_category_edit" value="<?= $productCategory ?>">
                </div>

                <div class="cl-comp-desc">
                    <b>Deskripsi Produk</b>
                    <textarea name="product_desc_edit" id="editor"><?= $productDesc ?></textarea>
                </div>

                <div class="cl-comp">
                    <b>Status Produk</b>
                    <select name="product_status_edit" id="status-select">
                        <option name="select-active" value="1" selected>Enable</option>
                        <option name="select-deactive" value="0">Disable</option>
                    </select>
                </div>

                <div class="form-btn">
                    <button class="update" name="update_product">Update Produk</button>
            </form>
                <div class="batal">
                    <form action="../"><button>Batal</button></form>
                </div>
        </div>
    </div>
    <script src="https://cdn.ckeditor.com/ckeditor5/35.3.2/classic/ckeditor.js"></script>
    <script>
        ClassicEditor.create( document.querySelector( '#editor' )).catch( error => { console.error(error); });
    </script>
</body>
</html>

    <?php if (isset($_POST["update_product"])) {
                $pd_name_edit = $db->real_escape_string($_POST["product_name_edit"]);
                $pd_price_edit = $db->real_escape_string($_POST["product_price_edit"]);
                $pd_stock_edit = $db->real_escape_string($_POST["product_stock_edit"]);
                $pd_weight_edit = $db->real_escape_string($_POST["product_weight_edit"]);
                $pd_desc_edit = $_POST["product_desc_edit"];
                $pd_category_edit = $db->real_escape_string($_POST["product_category_edit"]);
                $pd_status_edit = $db->real_escape_string($_POST["product_status_edit"]);

                if ($pd_name_edit == "") {
                    echo "form tidak boleh kosong";
                }if ($pd_name_edit != "") {
                    $editProductQuery = "UPDATE product SET pd_name = '$pd_name_edit', pd_price = '$pd_price_edit', pd_stock = '$pd_stock_edit', pd_weight = '$pd_weight_edit', pd_desc = '$pd_desc_edit', pd_category = '$pd_category_edit', status = '$pd_status_edit' WHERE pd_rand = '$productIdRand'";

                    mysqli_query($db, $editProductQuery);
                    
                    echo "produk berhasil diupdate!";
                    ?><script>window.location.replace("../");</script><?php
                }
            }
        }
    }else{
        echo "produk tidak ada!";
    }
}
    
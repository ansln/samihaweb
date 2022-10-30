<?php

require_once "../auth/conn2.php";
require_once "../auth/session.php";
require_once "../auth/functions/userFunction.php";

$getUserFunction = new userEdit;

if (isset($_GET["next"])) {
    $userEditName = $_POST["userEditName"];
    $getUserFunction->editName($userEditName);
}elseif (isset($_GET["change-name"])) {
    ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <div class="modal-container">
        <div class="content">
            <div class="content-wrapper">
                <div class="box">
                    <div id="close">
                        <button id="modal-close"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="sec1">
                        <div id="top-title">Ubah Nama</div>
                        <div id="sub-title">Pastikan nama yang akan diubah sudah benar.</div>
                    </div>
                    <div class="sec2">
                        <form enctype="multipart/form-data" id="pushName">
                            <div class="field">
                                <div id="field-title">Nama</div>
                                <input type="text" name="userEditName" id="userEditName">
                            </div>
                            <button type="submit" name="submit">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $("#pushName").on("submit", function(e){
                e.preventDefault();
                
                var formData = new FormData(this);
                const getName = $("#userEditName").val();

                if (getName == "") {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Nama harus diisi!',
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        showConfirmButton: false
                    });
                }else{
                    $.ajax({
                        url:"val?next",
                        type:"POST",
                        data:formData,
                        cache:false,
                        processData:false,
                        contentType:false,
                        
                        success:function(data){
                            $("#editPic").html(data);
                        }
                    });
                }
                
            });
        });
    </script>
    <?php
}else{
    header('Location: ./');
}

?>
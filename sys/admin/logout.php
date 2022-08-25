<?php 
    session_start();

    include("auth/conn.php");

    unset($_SESSION);
    session_destroy();
    session_write_close();
    
    header("location: ../admin/");
?>
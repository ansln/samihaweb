<?php

require_once "../auth/conn2.php";
require_once "../auth/functions/searchFunction.php";

$search = new searchManagement;

if (isset($_GET["q"])) {
    $userQuery = $_GET["q"];

    if ($userQuery != "") {
        $search->search($userQuery);
    }else{
        echo "not found";
    }
}else{
    echo "null";
}

?>
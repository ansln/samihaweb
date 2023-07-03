<?php

require_once "../auth/conn2.php";
require_once "../auth/functions/searchFunction.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
</head>
<body>
    <form action="search" method="GET">
        <input type="text" value="" name="q">
        <button type="submit">Search</button>
    </form>
</body>
</html>
<?php
$string = "Samiha Kurma Ajwa 500gr)";
$newString = substr($string, 7);
?>
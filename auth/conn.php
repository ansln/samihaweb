<?php

error_reporting(0);
$db = new mysqli("localhost","u362596482_samiha_shop","!_Samih@!db_Password_Shop_135790_TEMP!","u362596482_shop");

if (!($st = $db->prepare("SELECT * FROM user WHERE id = ?"))) {
  die( "Can't prepare the statement :(" );
}

// Check connection
if ($db -> connect_errno) {
  echo "<b style='color: red'>no connection</b>";
  exit();
}
?>
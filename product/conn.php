<?php

error_reporting(0);

$db = new mysqli("localhost","root","","shop");

if (!($st = $db->prepare("SELECT * FROM product WHERE pd_id = ?"))) {
  die( "Can't prepare the statement :(" );
}

// Check connection
if ($db -> connect_errno) {
  echo "<b style='color: red'>no connection</b>";
  exit();
}
?>
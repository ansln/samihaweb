<?php

error_reporting(0);

$db = new mysqli("localhost","root","","shop");

// Check connection
if ($db -> connect_errno) {
  echo "<b style='color: red'>no connection</b>";
  exit();
}
?>
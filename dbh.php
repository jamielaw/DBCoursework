<?php

$conn = mysqli_connect("localhost:3306", "root", "root", "logintest")

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

?>
